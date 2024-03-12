<?php

namespace App\Controllers\Admin;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\UserModel;
use App\Models\Admin\CustomerModel;
use App\Models\Admin\LocalisationModel;
use App\Models\Admin\CustomerSectorModel;
use App\Models\Admin\PaymentTermModel;

class Customer extends ResourceController
{
    protected $userType = 'customer';

    public function __construct()
    {
        helper('user');
    }


    public function index()
    {

        $this->validatePermission('view_customer');    // Check permission
        $modelCustomer = new CustomerModel(); // Load model

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

        $user_type = getUserTypeByCode($this->userType);

        $filter_data = array(
            'removed' => 0,
            'search' => $search,
            'start' => ($start - 1),
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'is_exist' => 0,
            'user_type' => $user_type['type_id']
        );

        $total_customers = $modelCustomer->getTotalCustomers($filter_data);
        $customers = $modelCustomer->getCustomers($filter_data);

        //upload data exixt
        $add = $modelCustomer->cancelUpload();
        //end

        if ($customers) {
            $response = array(
                'status' => 'success',
                'message' => lang('Customer.Customer.success_list'),
                'customers' => [
                    'type' => $this->userType,
                    'data' => $customers,
                    'pagination' => array(
                        'total' => (int)$total_customers,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($customers)
                    )
                ]
            );

            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response = array(
                'status' => 'error',
                'message' => lang('Customer.Customer.error_list')
            );
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function getCustomer()
    {
        $response = array();
        $this->validatePermission('view_customer');    // Check permission

        $modelCustomer = new CustomerModel(); // Load model

        $customer_id = $this->request->getVar('customer_id');
        $customer = $modelCustomer->getCustomer($customer_id);
        // print_r($customer);
        if ($customer) {
            $response['status'] = 'success';
            $response['message'] = lang('Customer.Customer.success_detail');
            $response['customer'] = [
                'type' => $this->userType,
                'data' => $customer
            ];
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Customer.Customer.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function addCustomer()
    {
        $response = array();
        $this->validatePermission('add_customer');    // Check permission

        $rules = [
            "company_name" => "required",
            // "email" => "required|valid_email",
            // "mobile" => "required|numeric"
        ];

        $messages = [
            "company_name" => [
                "required" => "Company Name/Customer Name Required"
            ]
            // "email" => [
            //     "required" => "Email is required",
            //     "valid_emil" => "Invalid email"
            // ],
            // "mobile" => [
            //     "required" => "mobile is required",
            //     "numeric" => "Mobile number must be numeric"
            // ]
        ];
        if ($this->validate($rules, $messages)) {

            $user_type = getUserTypeByCode($this->userType);
            $modelCustomer = new CustomerModel(); // Load model

            $customer_data = array_merge($this->request->getPost(null), [
                'user_type' => $user_type['type_id'],
                'is_exist' => 0
            ]);

            // $filter_data = array('removed', 0);
            $filter_data = array(
                'removed' => 0,
                'status' => 1,
                'username' => $customer_data['username'],
                // 'email' => $customer_data['email']
            );

            $customer = $modelCustomer->getCustomerValidation($filter_data);
            if ($customer) {
                $response['status'] = 'error';
                $response['message'] = lang('Customer.Customer.error_exist');
                return $this->setResponseFormat("json")->respond($response, 201);
            } else {
                $add = $modelCustomer->addCustomer($customer_data);
                if ($add) {
                    $response['status'] = 'success';
                    $response['message'] = lang('Customer.Customer.success_add');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Customer.Customer.error_add');
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

    public function editCustomer()
    {
        $response = array();

        $this->validatePermission('edit_customer');    // Check permission

        $user_type = getUserTypeByCode($this->userType);

        $rules = [
            "company_name" => "required",
            // "email" => "required|valid_email",
            // "mobile" => "required|numeric"
        ];

        $messages = [
            "company_name" => [
                "required" => "Company Name/Customer Name Required"
            ]
            // "email" => [
            //     "required" => "Email is required",
            //     "valid_emil" => "Invalid email"
            // ],
            // "mobile" => [
            //     "required" => "mobile is required",
            //     "numeric" => "Mobile number must be numeric"
            // ]
        ];
        if ($this->validate($rules, $messages)) {
            $modelCustomer = new CustomerModel(); // Load model

            $customer_data = array_merge($this->request->getPost(null), [
                'user_type' => $user_type['type_id']
            ]);

            $customer_id = $this->request->getVar('customer_id');

            $customer = $modelCustomer->getCustomer($customer_id);

            if ($customer) {
                $edit = $modelCustomer->editCustomer($customer_id, $customer_data);
                if ($edit) {
                    $response['status'] = 'success';
                    $response['message'] = lang('Customer.Customer.success_edit');
                    return $this->setResponseFormat("json")->respond($response, 200);
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('Customer.Customer.error_edit');
                    return $this->setResponseFormat("json")->respond($response, 201);
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Customer.Customer.error_detail');
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

    public function deleteCustomer()
    {
        $response = array();

        $this->validatePermission('edit_customer');    // Check permission
        $modelCustomer = new CustomerModel(); // Load model

        $customer_id = $this->request->getVar('customer_id');
        $customer = $modelCustomer->getCustomer($customer_id);
        if ($customer) {
            $remove = $modelCustomer->removeCustomer($customer_id, $customer->user_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Customer.Customer.success_removed');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Customer.Customer.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Customer.Customer.error_detail');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function setCustomerStatus()
    {
        $response = array();

        //$this->validatePermission('edit_AISDHead');	// Check permission
        $modelUsers = new UserModel(); // Load model
        $modelCustomer = new CustomerModel();

        $customer_id = $this->request->getVar('customer_id');
        $status = $this->request->getVar('status');
        $customer = $modelCustomer->getCustomer($customer_id);
        if ($customer) {
            $remove = $modelCustomer->setCustomerStatus($customer_id, $status, $customer->user_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('Customer.AISDHead.success_status');
                return $this->setResponseFormat("json")->respond($response, 200);
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('Customer.AISDHead.error_removed');
                return $this->setResponseFormat("json")->respond($response, 201);
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('Customer.AISDHead.error_detail');
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
        $this->validatePermission('view_customer');    // Check permission
        $modelCustomer = new CustomerModel(); // Load model

        $search = $this->request->getGet('search');
        if ($search) {
            $search = $search;
        } else {
            $search = '';
        }

        $filter_data = array(
            'removed' => 0,
            'status' => 1,
            'search' => $search
        );
        $customerArray = array();
        $customers = $modelCustomer->getCustomers($filter_data);
        if ($customers) {
            foreach ($customers as $customer) {
                $customerArray[] = array(
                    'id' => (int)$customer->customer_id,
                    'name' => html_entity_decode($customer->company_name)
                );
            }
        }
        $response = array(
            'status' => 'success',
            'customers' => $customerArray,
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }

    // sample file download
    public function downloadSample()
    {
        $content = $this->createExcel();
        $response = array(
            'status' => 'success',
            'message' => lang('customer.success_sample_download'),
            'content' => 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' . base64_encode($content),
        );

        return $this->setResponseFormat("json")->respond($response, 200);
    }

    /** create excel 
     *** return excel content  
     **/
    public function createExcel()
    {
        $modelPaymentTerm = new PaymentTermModel(); // Load model
        $modelCustomerSector = new CustomerSectorModel(); // Load model
        $modelLocalisation = new LocalisationModel(); // Load model

        $filter_data = array(
            'removed' => 0,
            'status' => 1
        );
        $cfilter_data = array(
            'removed' => 0,
            'status' => 1,
            'sort' => 'country_id',
            'order' => 'asc'
        );

        $country = $modelLocalisation->getCountries($cfilter_data);
        $state = $modelLocalisation->getStatesList($filter_data);

        $customer_sector = $modelCustomerSector->getCustomerSectores($filter_data);
        $paymentTerms = $modelPaymentTerm->getPaymentTerms($filter_data);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Retrieve the current active worksheet
        $sheet = $spreadsheet->getActiveSheet();

        $configs = '"';
        foreach ($customer_sector as $config) {
            $configs .= $config->title . '-' . $config->customer_sector_id . ', ';
        }
        $configs .= '"';

        $configs1 = '"';
        foreach ($paymentTerms as $config1) {
            $configs1 .= $config1->title . '-' . $config1->payment_term_id . ', ';
        }
        $configs1 .= '"';

        $sheet->setCellValue('A1', 'Company Name/Customer Name');
        $sheet->setCellValue('B1', 'username');
        $sheet->setCellValue('C1', 'password');
        $sheet->setCellValue('D1', 'Billing Adderss');
        $sheet->setCellValue('E1', 'Billing Address Contact Person Name');
        $sheet->setCellValue('F1', 'Billing Address Email ID');
        $sheet->setCellValue('G1', 'Billing Address Country');
        $sheet->setCellValue('H1', 'Billing Address Mobile');
        $sheet->setCellValue('I1', 'Billing Address State');
        $sheet->setCellValue('J1', 'Billing Address Pincode');
        $sheet->setCellValue('K1', 'Site Address');
        $sheet->setCellValue('L1', 'Site Address Contact Person Name');
        $sheet->setCellValue('M1', 'Site Address Email ID');
        $sheet->setCellValue('N1', 'Site Address Country');
        $sheet->setCellValue('O1', 'sITE Address Mobile');
        $sheet->setCellValue('P1', 'Site Address State');
        $sheet->setCellValue('Q1', 'Site Address Pincode');
        $sheet->setCellValue('R1', 'Customer Sector');
        $sheet->setCellValue('S1', 'Website');
        $sheet->setCellValue('T1', 'GST No');
        $sheet->setCellValue('U1', 'PAN/SSN No');
        $sheet->setCellValue('V1', 'Term of Payment');

        $spreadsheet->createSheet();
        // Create a new worksheet
        $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Country List');
        $myWorkSheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'State List');

        // Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
        $spreadsheet->addSheet($myWorkSheet, 1);
        $spreadsheet->getSheet(1);
        $spreadsheet->addSheet($myWorkSheet1, 2);
        $spreadsheet->getSheet(2);

        $i = 1;
        foreach ($country as $provider) {
            $myWorkSheet->setCellValue('A' . $i, $provider->name . '-' . $provider->country_id);
            $i++;
        }

        $countryData = $sheet->getHighestRow('A');

        for ($j = 1; $j < 30; $j++) {
            $dropdownlist = $myWorkSheet->getCell('A' . $j)->getDataValidation();
            $dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setFormula1('=\'PROVIDERS\'!$A$3:$A$' . $countryData);
        }

        //for state list
        $i = 1;
        foreach ($state as $provider) {
            $myWorkSheet1->setCellValue('A' . $i, $provider->name . '-' . $provider->state_id);
            $i++;
        }

        $stateData = $sheet->getHighestRow('A');
        for ($j = 1; $j < 30; $j++) {
            $dropdownlist = $myWorkSheet1->getCell('A' . $j)->getDataValidation();
            $dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setFormula1('=\'STATES\'!$A$3:$A$' . $stateData);
        }

        for ($x = 2; $x < 30; $x++) {

            $validation = $sheet->getCell('R' . $x)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setFormula1($configs);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Customer Sector');
            $validation->setPrompt('Choose Customer Sector');
            $validation->setShowErrorMessage(true);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setErrorTitle('Invalid option');
            $validation->setError('Select one from the drop down list.');

            $validation = $sheet->getCell('V' . $x)->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setFormula1($configs1);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Term of Payment');
            $validation->setPrompt('Choose Term of Payment');
            $validation->setShowErrorMessage(true);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
            $validation->setErrorTitle('Invalid option');
            $validation->setError('Select one from the drop down list.');
        }

        $fileName = 'customer-' . date('YmdHis') . '.xlsx';
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

            $modelCustomer = new CustomerModel(); // Load model

            if ($upload['status'] == 'success') {
                $user_type = getUserTypeByCode($this->userType);

                $file = fopen(WRITEPATH . "uploads/" . $upload['image'], "r");
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $reader->setReadDataOnly(true);
                $path = (WRITEPATH . "uploads/" . $upload['image']);
                $excel = $reader->load($path);
                $sheet = $excel->setActiveSheetIndex(0);
                $allDataInSheet = $excel->getActiveSheet()->toArray(null, true, true, true);
                $arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
                $customerArr = array();

                for ($i = 2; $i <= $arrayCount; $i++) {
                    $biilingAddressCountry =  explode('-', $allDataInSheet[$i]["G"]);
                    $biilingAddressState      =  explode('-', $allDataInSheet[$i]["I"]);
                    $siteAddressCountry =  explode('-', $allDataInSheet[$i]["N"]);
                    $siteAddressState      =  explode('-', $allDataInSheet[$i]["P"]);
                    $sector  =  explode('-', $allDataInSheet[$i]["R"]);
                    $term    =  explode('-', $allDataInSheet[$i]["V"]);

                    $customerArr[$i]["company_name"]    = $allDataInSheet[$i]["A"] ?? '';
                    $customerArr[$i]["username"]            =  $allDataInSheet[$i]["B"] ?? '';
                    $customerArr[$i]["password"]            =  $allDataInSheet[$i]["C"] ?? '';
                    $customerArr[$i]["billing_address"]           =  $allDataInSheet[$i]["D"] ?? '';
                    $customerArr[$i]["billing_address_contact_name"]    = $allDataInSheet[$i]["E"] ?? '';
                    $customerArr[$i]["billing_address_email"]      =  $allDataInSheet[$i]["F"] ?? '';
                    $customerArr[$i]["billing_address_mobile"]        = $allDataInSheet[$i]["H"] ?? '';
                    $customerArr[$i]["billing_address_country_id"]        =  $biilingAddressCountry ?? 0;
                    $customerArr[$i]["billing_address_state_id"]        = $biilingAddressState ?? 0;
                    $customerArr[$i]["billing_address_pincode"]        = $allDataInSheet[$i]["J"] ?? '';

                    $customerArr[$i]["site_address"]          = $allDataInSheet[$i]["K"] ?? '';
                    $customerArr[$i]["site_address_contact_name"]            = $allDataInSheet[$i]["L"] ?? '';
                    $customerArr[$i]["site_address_email"]         = $allDataInSheet[$i]["M"] ?? '';
                    $customerArr[$i]["site_address_mobile"]         =  $allDataInSheet[$i]["O"] ?? '';
                    $customerArr[$i]["site_address_country_id"]      = $siteAddressCountry ?? 0;
                    $customerArr[$i]["site_address_state_id"]      = $siteAddressState ?? 0;
                    $customerArr[$i]["site_address_pincode"]      = $allDataInSheet[$i]["Q"] ?? '';
                    $customerArr[$i]["website"]        = $allDataInSheet[$i]["S"] ?? '';
                    $customerArr[$i]["gst_number"]        = $allDataInSheet[$i]["T"] ?? '';
                    $customerArr[$i]["pan_number"]        = $allDataInSheet[$i]["U"] ?? '';
                    $customerArr[$i]["sector"]          =  $sector[1] ?? 0;
                    $customerArr[$i]["payment_term"]    = $term[1] ?? 0;
                    $customerArr[$i]['status']      = 0;
                    $customerArr[$i]['is_exist']    = 3;
                    $customerArr[$i]['removed']     = 0;
                    $customerArr[$i]["user_type"]   = $user_type['type_id'];
                }
                fclose($file);
                foreach ($customerArr as $userdata) {
                    $dfilter_data = array(
                        'removed' => 0,
                        'status' => 1,
                        'is_exist' => 0,
                        'username' => $userdata['username']
                    );

                    $rdetails = $modelCustomer->getCustomerExist($dfilter_data);

                    if (!empty($rdetails)) {
                        $modelCustomer->updateCustomer($rdetails->customer_id, $dfilter_data);
                    }

                    $add = $modelCustomer->addCustomer($userdata);
                    $nfilter_data = array(
                        'removed' => 0,
                        'status' => 1,
                        'is_exist' => 2,
                        'username' => $userdata['username']
                    );

                    $fdetails = $modelCustomer->getCustomerExists($nfilter_data);
                    // print_r($fdetails);
                    if ($fdetails) {
                        $modelCustomer->updateCustomerDetails($fdetails->username);
                    }

                    $rfilter_data = array(
                        'removed' => 0,
                        'is_exist' => 0,
                        'user_type' => $user_type['type_id']
                    );

                    $final = $modelCustomer->getCustomersDetails($rfilter_data);
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


    public function saveCustomer()
    {
        $response = [];
        $modelCustomer = new CustomerModel(); // Load model

        $add = $modelCustomer->updateUploadcustomer();

        if ($add) {
            $response['status'] = 'success';
            $response['message'] = lang('customer.success_add');
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('customer.error_add');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }

    public function cancel()
    {
        $response = [];
        $modelCustomer = new CustomerModel(); // Load model

        $add = $modelCustomer->cancelUpload();

        if ($add) {
            $response['status'] = 'success';
            $response['message'] = lang('customer.success_upload_cancel');
            return $this->setResponseFormat("json")->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('customer.error_add');
            return $this->setResponseFormat("json")->respond($response, 201);
        }
    }
}
