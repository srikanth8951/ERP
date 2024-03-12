<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\AssetModel;
use App\Models\Admin\AssetGroupModel;
use App\Models\Admin\ChecklistModel;

class Asset extends ResourceController
{
    
	public function __construct()
	{
		helper('common');
	}

    public function index()
    {

        $this->validatePermission('view_asset');    // Check permission
        $modelAsset = new AssetModel(); // Load model

        $start = $this->request->getGet('start');
        if ($start) {
            $start = (int)$start;
        } else {
            $start = 1;
        }

        $length = $this->request->getGet('length');
        if ($length) {
            $limit = (int)$length;
        } else {
            $limit = 10;
        }

        $search = $this->request->getGet('search');
        if ($search) {
            $search = $search;
        } else {
            $search = '';
        }

        $sort = $this->request->getGet('sort_column');
        if ($sort) {
            $sort = $sort;
        } else {
            $sort = '';
        }

        $order = $this->request->getGet('sort_order');
        if ($order) {
            $order = $order;
        } else {
            $order = '';
        }


        $filter_data = array(
            'removed' => 0,
            'search' => $search,
            'start' => ($start - 1),
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'is_exist' => 0,
            'parent' => false
        );

        $total_assets = $modelAsset->getTotalAssets($filter_data);
        $assets = $modelAsset->getAssets($filter_data);
        if ($assets) {
            $response = array(
                'status' => 'success',
                'message' => lang('AssetGroup.success_list'),
                'assets' => [
                    'data' => $assets,
                    'pagination' => array(
                        'total' => (int)$total_assets,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($assets)
                    )
                ]
            );
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'success',
                'message' => lang('AssetGroup.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    protected function validatePermission($permission_name)
	{
		$permission = AuthUser::checkPermission($permission_name);
		if (!$permission) {
			$response = array(
				'status' => 'error',
				'message' => lang('Common.error_permission')
			);

			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

    public function getAsset()
	{
		$response = array();
		$this->validatePermission('view_asset');	// Check permission

		$modelAsset = new AssetModel(); // Load model

		$asset_id = $this->request->getVar('asset_id');
		$asset = $modelAsset->getAsset($asset_id);
		if ($asset) {
            $assetJobs = $modelAsset->getAssetJobs($asset_id, [
                'job_status' => 1,
                'status' => 1
            ]); // Get asset jobs
            
            if ($assetJobs) {
                $asset->jobs = $assetJobs;
            } else {
                $asset->jobs = [];
            }
            
            $response['status'] = 'success';
			$response['message'] = lang('Asset.success_detail');
			$response['asset'] = $asset;
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Asset.error_detail');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

    public function autocomplete()
	{
		$this->validatePermission('view_asset');	// Check permission
		$modelAsset = new AssetModel(); // Load model

		$asset_id = $this->request->getVar('asset_id');
		$filter_data = array(
			'asset_id' => $asset_id ?? 0,
			'removed' => 0,
			'status' => 1,
            'is_exist' => 0,
		);
		$assetArray = array();
		$assets = $modelAsset->getAssets($filter_data);
		if ($assets) {
			foreach ($assets as $asset) {
				$assetArray[] = array(
					'id' => (int)$asset->asset_id,
					'name' => html_entity_decode($asset->name)
				);
			}
		}
		$response = array(
			'status' => 'success',
			'assets' => $assetArray,
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}
    
    // sample file download
	public function downloadSample()
	{
		$content = $this->createExcel();
		$response = array(
			'status' => 'success',
			'message' => lang('Asset.success_sample_download'),
			'content' => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' . base64_encode($content),
		);

		return $this->setResponseFormat("json")->respond($response, 200);
	}

	/** create excel 
	 *** return excel content  
	 **/
	public function createExcel()
	{
        $modelAssetGroup = new AssetGroupModel(); // Load model
		$modelChecklist = new ChecklistModel();

        $filter_data = array(
            'removed' => 0,
            'parent' => 0,
            'status' => 1,
			'order' => 'ASC'
        );
        $groups = $modelAssetGroup->getGroups($filter_data);
        
        $filter_data1 = array(
            'removed' => 0,
            'parent' => 1,
            'status' => 1,
			'order' => 'ASC'
        );
        $subGroups = $modelAssetGroup->getGroups($filter_data1);

        $units = getMeasurementUnits();
		$compressors = [1,2,3,4];

		$checklists = $modelChecklist->getChecklists(['order' => 'ASC']);

		// Create a new Spreadsheet object
		$currentDate = date('d M Y H:i A');
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getProperties()
			->setCreator("Admin")
			->setLastModifiedBy("Admin")
			->setTitle("Asset Sample Excel File - {$currentDate}")
			->setSubject("Office 2007 XLSX Test Document");

		// Create a checklist spreadsheet object
		$spreadsheet2 = $spreadsheet->createSheet();
		$spreadsheet2->setTitle('Checklists');
		$spreadsheet2->getColumnDimension('A')->setWidth(16);
		$spreadsheet2->setCellValue('A1', 'Checklist Id');
		$spreadsheet2->getStyle('A1')->getFont()->setBold(true);
		$spreadsheet2->getColumnDimension('B')->setWidth(38);
		$spreadsheet2->setCellValue('B1', 'Checklist Name');
		$spreadsheet2->getStyle('B1')->getFont()->setBold(true);
		$csii = 2;
		foreach ($checklists as $checklist) {
			$spreadsheet2->setCellValue('A' . $csii, $checklist->checklist_id);
			$spreadsheet2->setCellValue('B' . $csii, $checklist->name);
			$csii++;
		}

		// Create a provider spreadsheet object
		$spreadsheet3 = $spreadsheet->createSheet();
		$spreadsheet3->setTitle('Informations');

		$spreadsheet3->getColumnDimension('A')->setWidth(38);
		$spreadsheet3->setCellValue('A1', 'Asset Groups');
		$spreadsheet3->getStyle('A1')->getFont()->setBold(true);
		$agsiii = 2;
		foreach ($groups as $group) {
			$spreadsheet3->setCellValue('A' . $agsiii, $group->name);
			$agsiii++;
		}

		$spreadsheet3->getColumnDimension('B')->setWidth(38);
		$spreadsheet3->setCellValue('B1', 'Asset Sub Groups');
		$spreadsheet3->getStyle('B1')->getFont()->setBold(true);
		$asgsiii = 2;
		foreach ($subGroups as $group) {
			$spreadsheet3->setCellValue('B' . $asgsiii, $group->name);
			$asgsiii++;
		}

		$spreadsheet3->getColumnDimension('C')->setWidth(18);
		$spreadsheet3->setCellValue('C1', 'UOM');
		$spreadsheet3->getStyle('C1')->getFont()->setBold(true);
		$musiii = 2;
		foreach ($units as $unit) {
			$spreadsheet3->setCellValue('C' . $musiii, $unit['name']);
			$musiii++;
		}

		$spreadsheet3->getColumnDimension('D')->setWidth(18);
		$spreadsheet3->setCellValue('D1', 'No of compressor');
		$spreadsheet3->getStyle('D1')->getFont()->setBold(true);
		$tcsiii = 2;
		foreach ($compressors as $compressor) {
			$spreadsheet3->setCellValue('D' . $tcsiii, $compressor);
			$tcsiii++;
		}

		// Retrieve the first worksheet
		$spreadsheet1 = $spreadsheet->setActiveSheetIndex(0);
		$spreadsheet1->setTitle('Asset');

		$spreadsheet1->getColumnDimension('A')->setWidth(36);
		$spreadsheet1->setCellValue('A1', 'Asset Category*');
		$spreadsheet1->getStyle('A1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('B')->setWidth(28);
		$spreadsheet1->setCellValue('B1', 'Asset Group*');
		$spreadsheet1->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('C')->setWidth(28);
		$spreadsheet1->setCellValue('C1', 'Asset Sub Group*');
		$spreadsheet1->getStyle('C1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('D')->setWidth(18);
		$spreadsheet1->setCellValue('D1', 'Is Composer Required?');
		$spreadsheet1->getStyle('D1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('E')->setWidth(18);
		$spreadsheet1->setCellValue('E1', 'Make of Compressor');
		$spreadsheet1->getStyle('E1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('F')->setWidth(18);
		$spreadsheet1->setCellValue('F1', 'No. of Compressor');
		$spreadsheet1->getStyle('F1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('G')->setWidth(18);
		$spreadsheet1->setCellValue('G1', 'Make');
		$spreadsheet1->getStyle('G1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('H')->setWidth(18);
		$spreadsheet1->setCellValue('H1', 'Model');
		$spreadsheet1->getStyle('H1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('I')->setWidth(18);
		$spreadsheet1->setCellValue('I1', 'Sr.No');
		$spreadsheet1->getStyle('I1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('J')->setWidth(16);
		$spreadsheet1->setCellValue('J1', 'Capacity*');
		$spreadsheet1->getStyle('J1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('K')->setWidth(16);
		$spreadsheet1->setCellValue('K1', 'UOM*');
		$spreadsheet1->getStyle('K1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('L')->setWidth(16);
		$spreadsheet1->setCellValue('L1', 'Quantity*');
		$spreadsheet1->getStyle('L1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('M')->setWidth(16);
		$spreadsheet1->setCellValue('M1', 'Asset Location*');
		$spreadsheet1->getStyle('M1')->getFont()->setBold(true);
		$spreadsheet1->getColumnDimension('N')->setWidth(40);
		$spreadsheet1->setCellValue('N1', 'Checklist');
		$spreadsheet1->getStyle('N1')->getFont()->setBold(true);


		$i = 1;

		for ($x = 2; $x < 100; $x++) {

			$validation2 = $spreadsheet1->getCell('B' . $x)->getDataValidation();
			$validation2->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation2->setFormula1('\'Informations\'!$A$2:$A$' . ($agsiii - 1));
			$validation2->setAllowBlank(false);
			$validation2->setShowDropDown(true);
			$validation2->setShowInputMessage(true);
			$validation2->setPromptTitle('Assets');
			$validation2->setPrompt('Choose Asset');
			$validation2->setShowErrorMessage(true);
			$validation2->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation2->setErrorTitle('Invalid option');
			$validation2->setError('Select one from the drop down list.');

			$validation3 = $spreadsheet1->getCell('C' . $x)->getDataValidation();
			$validation3->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation3->setFormula1('\'Informations\'!$B$2:$B$' . ($asgsiii - 1));
			$validation3->setAllowBlank(false);
			$validation3->setShowDropDown(true);
			$validation3->setShowInputMessage(true);
			$validation3->setPromptTitle('Sub Group');
			$validation3->setPrompt('Choose Sub-Group');
			$validation3->setShowErrorMessage(true);
			$validation3->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation3->setErrorTitle('Invalid option');
			$validation3->setError('Select one from the drop down list.');

			$validation4 = $spreadsheet1->getCell('D' . $x)->getDataValidation();
			$validation4->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation4->setFormula1('"Yes,No"');
			$validation4->setAllowBlank(false);
			$validation4->setShowDropDown(true);
			$validation4->setShowInputMessage(true);
			$validation4->setPromptTitle('Sub Group');
			$validation4->setPrompt('Choose Compressor is required or not');
			$validation4->setShowErrorMessage(true);
			$validation4->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation4->setErrorTitle('Invalid option');
			$validation4->setError('Select one from the drop down list.');

			$validation11 = $spreadsheet1->getCell('F' . $x)->getDataValidation();
			$validation11->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation11->setFormula1('\'Informations\'!$D$2:$D$' . ($tcsiii - 1));
			$validation11->setAllowBlank(false);
			$validation11->setShowDropDown(true);
			$validation11->setShowInputMessage(true);
			$validation11->setPromptTitle('No of Compressor');
			$validation11->setPrompt('Choose no of compressor');
			$validation11->setShowErrorMessage(true);
			$validation11->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation11->setErrorTitle('Invalid option');
			$validation11->setError('Select one from the drop down list.');

			$validation11 = $spreadsheet1->getCell('K' . $x)->getDataValidation();
			$validation11->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
			$validation11->setFormula1('\'Informations\'!$C$2:$C$' . ($musiii - 1));
			$validation11->setAllowBlank(false);
			$validation11->setShowDropDown(true);
			$validation11->setShowInputMessage(true);
			$validation11->setPromptTitle('UOM');
			$validation11->setPrompt('Choose UOM');
			$validation11->setShowErrorMessage(true);
			$validation11->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
			$validation11->setErrorTitle('Invalid option');
			$validation11->setError('Select one from the drop down list.');

			$validation12 = $spreadsheet1->getCell('N' . $x)->getDataValidation();
			$validation12->setShowInputMessage(true);
			$validation12->setPromptTitle('Checklists');
			$validation12->setPrompt('To add multiple checklist use comma(,)');

		}

		$fileName = 'asset-' . date('YmdHis') . '.xlsx';
		$writer = new Xlsx($spreadsheet);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');

		ob_start();
		$writer->save('php://output');
		$xlxsData = ob_get_contents();
		ob_end_clean();

		return $xlxsData;
	}

	public function upload()
	{
		$input = $this->validate([
			'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv,xls,xlsx],'
		]);

		if (!$input) {
			$response = array(
				'status' => 'error',
				'message' => $this->validator->getErrors()
			);
			return $this->setResponseFormat("json")->respond($response, 201);
		} else {

			$upload['status'] = 'error';
			$upload['message'] = '';

			// Upload file
			if (!empty($this->request->getFile('file'))) {
				$imageFile = $this->request->getFile('file');
				$upload = $this->saveFile($imageFile, '',  'blob');
			}

            $modelAsset = new AssetModel(); // Load model

			if ($upload['status'] == 'success') {

				$file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
				$i = 0;

				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
				$reader->setReadDataOnly(true);
				$path = (WRITEPATH . "uploads/" . $upload['image']);
				$excel = $reader->load($path);
				$sheet = $excel->setActiveSheetIndex(0);
				$allDataInSheet = $excel->getActiveSheet()->toArray(null, true, true, true);
				$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
				$assetArr = array();

				for ($i = 2; $i <= $arrayCount; $i++) {
					
                    $group   =  explode('-', $allDataInSheet[$i]["B"]);
					$sub 	 =  explode('-', $allDataInSheet[$i]["C"]);
					$uom     =  explode('-', $allDataInSheet[$i]["I"]);
					
					$assetArr[$i]["name"] =  $allDataInSheet[$i]["A"] ?? 0;
					$assetArr[$i]["group"] = $group[1] ?? 0;
					$assetArr[$i]["sub_group"] =  $sub[1] ?? 0;
					$assetArr[$i]["compressor_type"] = $allDataInSheet[$i]["D"] ?? '';
					$assetArr[$i]["make"] =  $allDataInSheet[$i]["E"] ?? 0;
					$assetArr[$i]["model"] = $allDataInSheet[$i]["F"] ?? 0;
					$assetArr[$i]["serial_number"] = $allDataInSheet[$i]["G"] ?? 0;
					$assetArr[$i]["capacity"] = $allDataInSheet[$i]["H"] ?? 0;
					$assetArr[$i]["measurement_unit"] = $uom[1] ?? '';
					$assetArr[$i]['status'] = 0;
					$assetArr[$i]['is_exist'] = 3;
					$assetArr[$i]['quantity'] = $allDataInSheet[$i]["J"] ?? 0;
					$assetArr[$i]["location"] = $allDataInSheet[$i]["K"] ?? 0;
					$assetArr[$i]['created_datetime'] = date('Y-m-d H:i:s');
				}
				fclose($file);
				foreach ($assetArr as $userdata) {
					$dfilter_data = array(
						'status' => 1,
						'is_exist' => 0,
						'name' => $userdata['name'],
						'group_id'=>$userdata['group'],
						'sub_id'=>$userdata['sub_group']
					);

					$rdetails = $modelAsset->getAssetExist($dfilter_data);

					if (!empty($rdetails)) {
						$modelAsset->updateAsset($rdetails->asset_id, $dfilter_data);
					}

					$add = $modelAsset->addAsset($userdata);

					$nfilter_data = array(
						'status' => 1,
						'is_exist' => 2,
						'name' => $userdata['name']
					);

					$fdetails = $modelAsset->getAssetExists($nfilter_data);

					if ($fdetails) {
						$modelAsset->updateAssetDetails($fdetails->name);
					}

					$rfilter_data = array('status' => 0);

					$final = $modelAsset->getAssetData($rfilter_data);
				}


				$path_to_file = WRITEPATH . "uploads/" . $upload['image'];
				unlink($path_to_file);

				$response = array(
					'status' => 'success',
					'upload' => $final
				);
				return $this->setResponseFormat("json")->respond($response, 200);
			} else {
				$response = array(
					'status' => 'error',
					'message' => $upload['message']
				);
				return $this->setResponseFormat("json")->respond($response, 201);
			}
		}
	}

	//Save file
	private function saveFile($uploadFile, $thumb = '', $uploadType = 'file')
	{
		$json = array();
		$json['status'] = false;
		$file_upload = false;

		if (!empty($uploadFile)) {

			// Load file storage library
			$fileStorage = new \App\Libraries\Storage\DefaultStorage();

			$filename = $uploadFile->getClientName();
			$file_data = array(
				'file' => $uploadFile,
				'newName' => $filename . 'file' . date('YmdHis'),
				'uploadPath' => WRITEPATH . 'uploads'
			);

			$file_upload = $fileStorage->uploadFile($file_data);

			$file_upload_status = isset($file_upload['status']) ? $file_upload['status'] : false;
			if ($file_upload_status) {
				$json['status'] = 'success';
				$json['message'] = 'File uploaded';
				$json['image'] = $file_upload['name'];
			} else {
				$json['status'] = 'error';
				$json['message'] = $file_upload['message'];
			}
		} else {
			$json['status'] = 'error';
			$json['message'] = 'Please upload valid file!';
		}

		return $json;
	}


	public function saveAssets()
	{
		$response = [];
        $modelAsset = new AssetModel(); // Load model
		$add = $modelAsset->updateUploadAsset();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Asset.success_add');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Asset.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}

	public function cancel()
	{
		$response = [];
        $modelAsset = new AssetModel(); // Load model
		$add = $modelAsset->cancelUpload();

		if ($add) {
			$response['status'] = 'success';
			$response['message'] = lang('Asset.success_upload_cancel');
			return $this->setResponseFormat("json")->respond($response, 200);
		} else {
			$response['status'] = 'error';
			$response['message'] = lang('Asset.error_add');
			return $this->setResponseFormat("json")->respond($response, 201);
		}
	}
}

