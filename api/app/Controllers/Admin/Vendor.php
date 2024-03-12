<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\VendorModel;
use App\Models\Admin\LocalisationModel;
use App\Models\Admin\DepartmentModel;
use App\Models\Admin\DesignationModel;
use App\Models\Admin\WorkExpertiseModel;
use App\Models\Admin\RegionModel;
use App\Models\Admin\BranchModel;
use App\Models\Admin\AreaModel;

class Vendor extends ResourceController
{

    public function index()
    {

        $this->validatePermission('view_vendor');    // Check permission
        $modelVendor = new VendorModel(); // Load model

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
        );

        $total_vendors = $modelVendor->getTotalVendors($filter_data);
        $vendors = $modelVendor->getVendors($filter_data);
        // $upload = $this->export();

        if ($vendors) {
            $response = array(
                'status' => 'success',
                'message' => lang('Vendor.Vendor.success_list'),
                'vendors' => [
                    'data' => $vendors,
                    'pagination' => array(
                        'total' => (int)$total_vendors,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($vendors)
                    )
                ]
            );

            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('Vendor.Vendor.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getVendor()
    {
        $response = array();
        $this->validatePermission('view_vendor');    // Check permission

        $modelVendor = new VendorModel(); // Load model

        $vendor_id = $this->request->getVar('vendor_id');
        $vendor = $modelVendor->getVendor($vendor_id);
        if ($vendor) {
            $response['status'] = 'success';
            $response['message'] = lang('Vendor.Vendor.success_detail');
            $vendor_evaluation = $modelVendor->getVendorEvaluations($vendor_id);
            $response['vendor'] = [
                'data' => $vendor,
                'file' => $vendor_evaluation
            ];
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Vendor.Vendor.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addVendor()
    {
        $response = array();
        $this->validatePermission('add_vendor');    // Check permission

        $rules = [
            "email" => "required|valid_email",
            "mobile" => "required|numeric",
        ];

        $messages = [
            "email" => [
                "required" => "Email is required",
                "valid_emil" => "Invalid email"
            ],
            "mobile" => [
                "required" => "Mobile number is required",
                "numeric" => "Mobile number must be numeric"
            ]
        ];
        if ($this->validate($rules, $messages)) {

            $modelVendor = new VendorModel(); // Load model
            $vendor_data = array_merge($this->request->getPost(null), [
                'is_exist' => 0
            ]);

            $filter_data = array('removed', 0);
            $vendor = $modelVendor->getVendorByEmail($vendor_data['email'], $filter_data);
            if ($vendor) {
                $response['status'] = 'error';
                $response['message'] = lang('Vendor.Vendor.error_exist');
                return $this->setResponseFormat("json")->respond($response, 201);
            } else {
                $add = $modelVendor->addVendor($vendor_data);
                if ($add) {
                    // Upload file
                    if (!empty($this->request->getFiles('vendor_evaluation'))) {
                        $files = $this->request->getFiles('vendor_evaluation');
                        foreach ($files['vendor_evaluation'] as $file) {
                            $upload = $this->saveFile($file, '',  'blob'); // save file
                            if ($upload['status'] == 'success') {
                                $addVendorEvaluation = $modelVendor->addVendorEvaluation($upload, $add); // add file name
                            }
                        }
                    }
                    $response['status'] = 'success';
                    $response['message'] = lang('Vendor.Vendor.success_add');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Vendor.Vendor.error_add');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ];
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function editVendor()
    {
        $response = array();

        $this->validatePermission('edit_vendor');    // Check permission

        // $user_type = getUserTypeByCode($this->empType);

        $rules = [
            "email" => "required|valid_email",
            "mobile" => "required|numeric"
        ];

        $messages = [
            "email" => [
                "required" => "Email is required",
                "valid_emil" => "Invalid email"
            ],
            "mobile" => [
                "required" => "mobile is required",
                "numeric" => "Mobile number must be numeric"
            ]
        ];
        if ($this->validate($rules, $messages)) {
            $modelVendor = new VendorModel(); // Load model

            $vendor_data = array_merge($this->request->getPost(null));

            $vendor_id = $this->request->getVar('vendor_id');

            $vendor = $modelVendor->getVendor($vendor_id);

            if ($vendor) {
                $edit = $modelVendor->editVendor($vendor_id, $vendor_data);
                if ($edit) {
                    // Upload file
                    $files = $this->request->getFiles('vendor_evaluation');
                    if ($files['vendor_evaluation']) {
                        foreach ($files['vendor_evaluation'] as $file) {
                            $upload = $this->saveFile($file, '',  'blob'); // save file
                            if ($upload['status'] == 'success') {
                                $addVendorEvaluation = $modelVendor->addVendorEvaluation($upload, $vendor_id); // add file name
                            }
                        }
                    }
                    $response['status'] = 'success';
                    $response['message'] = lang('Vendor.Vendor.success_edit');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Vendor.Vendor.error_edit');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Vendor.Vendor.error_detail');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ];
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function deleteVendor()
    {
        $response = array();

        $this->validatePermission('edit_vendor');    // Check permission
        $modelVendor = new VendorModel(); // Load model

        $vendor_id = $this->request->getVar('vendor_id');
        $vendor = $modelVendor->getVendor($vendor_id);
        if ($vendor) {
            $remove = $modelVendor->removeVendor($vendor_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Vendor.Vendor.success_removed');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Vendor.Vendor.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Vendor.Vendor.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function deleteVendorEvaluation()
    {
        $response = array();

        $this->validatePermission('edit_vendor');    // Check permission
        $modelVendor = new VendorModel(); // Load model

        $vendor_evaluation_id = $this->request->getVar('vendor_evaluation_id');
        $file = $this->request->getVar('file');
        $vendor_evaluation = $modelVendor->getVendorEvaluation($vendor_evaluation_id);
        $path_to_file = ROOTPATH . 'uploads/vendor_evaluation/' . $file;
        if ($vendor_evaluation) {
            $remove = $modelVendor->deleteVendorEvaluation($vendor_evaluation_id);
            if ($remove) {
                $result = unlink($path_to_file);
                $response['status'] = 'success';
                $response['message'] = lang('Vendor.Vendor_evaluation.success_removed');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Vendor.Vendor_evaluation.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Vendor.Vendor.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function setVendorStatus()
    {
        $response = array();

        //$this->validatePermission('edit_AISDHead');	// Check permission
        $modelVendor = new VendorModel();

        $vendor_id = $this->request->getVar('vendor_id');
        $status = $this->request->getVar('status');
        $AISDHead = $modelVendor->getVendor($vendor_id);
        if ($AISDHead) {
            $remove = $modelVendor->setVendorStatus($vendor_id, $status);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Vendor.AISDHead.success_status');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Vendor.AISDHead.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Vendor.AISDHead.error_detail');
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

    public function autocomplete()
    {
        $this->validatePermission('view_vendor');    // Check permission
        $modelVendor = new VendorModel(); // Load model

        $filter_data = array(
            'removed' => 0,
            'status' => 1
        );
        $vendorArray = array();
        $vendors = $modelVendor->getVendors($filter_data);
        if ($vendors) {
            foreach ($vendors as $vendor) {
                $vendorArray[] = array(
                    'id' => (int)$vendor->vendor_id,
                    'name' => html_entity_decode($vendor->organization_name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'vendors' => $vendorArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
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
            $filename = $uploadFile->getName();

            $file_data = array(
                'file' => $uploadFile,
                'newName' => $filename . 'file' . date('YmdHis'),
                'uploadPath' => ROOTPATH . 'uploads/vendor_evaluation'
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
}
