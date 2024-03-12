<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\BranchModel;

class Uploadbranch extends ResourceController
{

	function __construct()
	{

	}

    public function index()
    {
        $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv,xls,xlsx],'
        ]);
        if (! $input) {
            $response = array(
                'status' => 'error',
                'message' => $this->validator->getErrors()
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        } else { 
            $upload['status'] = 'error';
            $upload['message'] = '';

            // Upload file
            if (! empty($this->request->getFile('file'))) {
                $imageFile = $this->request->getFile('file');

                $upload = $this->saveFile($imageFile, '',  'blob');
            }
            //echo WRITEPATH . 'uploads';
            $modelbranch = new branchModel(); // Load model
            if ($upload['status'] == 'success') {

                $file = fopen(WRITEPATH . "uploads/".$upload['image'],"r");
                $i = 0;
                $numberOfFields = 3;
                $csvArr = array();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

               // $reader= PHPExcel_IOFactory::createReader('Excel2007');
                $reader->setReadDataOnly(true);
                $path=(WRITEPATH . "uploads/".$upload['image']);
                $excel=$reader->load($path);
                $sheet=$excel->setActiveSheetIndex(0);
                $allDataInSheet = $excel->getActiveSheet()->toArray(null,true,true,true);
                $arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
                $branchArr = array();

                for($i=2;$i<=$arrayCount;$i++)
                {   
                    $reg =  explode('-',$allDataInSheet[$i]["A"]);   
                    $branchArr[$i]["name"] =  $allDataInSheet[$i]["B"];
                    $branchArr[$i]["code"] = $allDataInSheet[$i]["C"];
                    $branchArr[$i]["region_id"]= $reg[1];
                    $branchArr[$i]['status'] = 0;
                    $branchArr[$i]['is_exist'] = 3;
                    $branchArr[$i]['removed'] = 0;
                    $branchArr[$i]['created_datetime'] = date('Y-m-d H:i:s');
                }
                fclose($file);
                foreach($branchArr as $userdata) { 
                    $dfilter_data = array(
                        'removed' => 0,
                        'status' => 1,
                        'is_exist' => 0,
                        'code' => $userdata['code']
                    );
                   
                    $rdetails = $modelbranch->getBranchByCodeExist($dfilter_data);
                   
                    if (!empty($rdetails)) {
                        $modelbranch->updateBranch($rdetails->branch_id, $dfilter_data);
                    }
                   
                   $add = $modelbranch->addBranch($userdata);
                    
                    $nfilter_data = array(
                        'removed' => 0,
                        'status' => 1,
                        'is_exist' => 2,
                        'code' => $userdata['code']
                    );

                    $fdetails = $modelbranch->getBranchByCodeExists($nfilter_data);

                    if ($fdetails) { 
                        $modelbranch->updateBranchDetails($fdetails->code);
                    }
                 }
               
                 $path_to_file = WRITEPATH . "uploads/".$upload['image'];
                 unlink($path_to_file);

                $rfilter_data = array(
                    'removed' => 0,
                    'is_exist' => 0,
                    'type' =>'upload'
                );

                foreach($branchArr as $userdata) {
                   $final = $modelbranch->getBranches($rfilter_data);
                }

                $response = array(
                    'status' => 'success',
                    'upload_branchs' => $final
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
    private function saveFile($uploadFile, $thumb='', $uploadType='file')
    { 
        $json = array();
        $json['status'] = false;
        $file_upload = false;

        if (! empty($uploadFile)) {
            
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
                $json['status']= 'error';
                $json['message'] = $file_upload['message'];
            }
        } else {
            $json['status']= 'error';
            $json['message'] = 'Please upload valid file!';
        }
        
        return $json;
    }


    public function saveBranch()
	{
		$response = [];
		$modelbranch = new branchModel(); // Load model

        $add = $modelbranch->updateUploadBranch();

        if ($add) {
            $response['status'] = 'success';
            $response['message'] = lang('branch.success_add');
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('branch.error_add');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
		
	}

    public function cancel()
    {   
        $response = [];
        $modelbranch = new branchModel(); // Load model

        $add = $modelbranch->cancelUpload();

        if ($add) {
            $response['status'] = 'success';
            $response['message'] = lang('branch.success_upload_cancel');
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('branch.error_add');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

}