<?php

namespace App\Controllers\Admin;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Auth\User as AuthUser;
use App\Models\Admin\ContractJobModel;
// use App\Models\Admin\ContractJobAssetModel;
use App\Models\Admin\ContractJobPPMModel;
use App\Libraries\AppJobManager;

class ContractJob extends ResourceController
{
    private $PPMFrequencyDates = [];
    private $PPMFrequencyErrorMsg = '';

    public function __construct()
    {
        helper(['user', 'contract_job']); // Loading user & contract_job helpers
    }

    // Get list of Contract/jobs
    public function index()
    {
        $this->validatePermission('view_contract_job'); // Check permission
        $modelContractJob = new ContractJobModel(); // Load model

        $start = $this->request->getGet('start');
        if ($start) {
            $start = (int) $start;
        } else {
            $start = 1;
        }

        $length = $this->request->getGet('length');
        if ($length) {
            $limit = (int) $length;
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

        $filter_data = [
            // 'removed' => 0,
            'search' => $search,
            'start' => $start - 1,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'status' => 1,
        ];

        $contractTypeId = $this->request->getGet('contract_type_id');
        if ($contractTypeId) {
            if (strpos($contractTypeId, ',')) {
                $filter_data['contract_type_id'] = explode(',', $contractTypeId);
            } else {
                $filter_data['contract_type_id'] = (int)$contractTypeId;
            }
        }

        $total_contract_jobs = $modelContractJob->getTotalContractJobs(
            $filter_data
        );
        $jobs = $modelContractJob->getContractJobs($filter_data);
        if ($jobs) {
            $response = [
                'status' => 'success',
                'message' => lang('ContractJob.success_list'),
                'contract_jobs' => [
                    'data' => $jobs,
                    'pagination' => [
                        'total' => (int) $total_contract_jobs,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($jobs),
                    ],
                ],
            ];

            return $this->setResponseFormat('json')->respond($response, 200);
        } else {
            $response = [
                'status' => 'error',
                'message' => lang('ContractJob.error_list'),
            ];

            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Get list of Contract/jobs
    public function logs()
    {
        $this->validatePermission('view_contract_job'); // Check permission
        $modelContractJob = new ContractJobModel(); // Load model

        $start = $this->request->getGet('start');
        if ($start) {
            $start = (int) $start;
        } else {
            $start = 1;
        }

        $length = $this->request->getGet('length');
        if ($length) {
            $limit = (int) $length;
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
        $contract_job_id = $this->request->getGet('contract_job_id');
        if ($contract_job_id) {
            $contract_job_id = $contract_job_id;
        } else {
            $contract_job_id = '';
        }

        $job = $modelContractJob->getContractJob($contract_job_id);

        $filter_data = [
            'search' => $search,
            'start' => $start - 1,
            'limit' => $limit,
            'sort' => $sort,
            'order' => $order,
            'status' => 0,
            'contract_job_id' => explode(',', $job->parent_path),
        ];
        // print_r(explode(",", $job->parent_path));
        $total_contract_jobs = $modelContractJob->getTotalContractJobs(
            $filter_data
        );
        $jobs = $modelContractJob->getContractJobs($filter_data);
        if ($jobs) {
            $response = [
                'status' => 'success',
                'message' => lang('ContractJob.success_list'),
                'contract_jobs' => [
                    'data' => $jobs,
                    'pagination' => [
                        'total' => (int) $total_contract_jobs,
                        'length' => $limit,
                        'start' => $start,
                        'records' => count($jobs),
                    ],
                ],
            ];

            return $this->setResponseFormat('json')->respond($response, 200);
        } else {
            $response = [
                'status' => 'success',
                'message' => lang('ContractJob.error_list'),
            ];

            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Get detail of Contract/job
    public function getContractJob()
    {
        $response = [];
        $this->validatePermission('view_contract_job'); // Check permission

        $modelContractJob = new ContractJobModel(); // Load model

        $contract_job_id = $this->request->getVar('contract_job_id');
        $job = $modelContractJob->getContractJob($contract_job_id);
        if ($job) {
            $response['status'] = 'success';
            $response['message'] = lang('ContractJob.success_detail');
            $response['contract_job'] = $job;
            return $this->setResponseFormat('json')->respond($response, 200);
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ContractJob.error_detail');
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Get Assets of Contract/job
    public function getContractJobAssets()
    {
        $response = [];
        $this->validatePermission('view_contract_job'); // Check permission

        $modelContractJob = new ContractJobModel(); // Load model

        $contract_job_id = $this->request->getVar('contract_job_id');
        $job = $modelContractJob->getContractJob($contract_job_id);
        if ($job) {
            $jobAssets = $modelContractJob->getContractJobAssets(
                $contract_job_id
            );
            if ($jobAssets) {
                $response['status'] = 'success';
                $response['message'] = lang(
                    'ContractJob.Assets.success_detail'
                );
                $response['contract_job_assets'] = $jobAssets;
                return $this->setResponseFormat('json')->respond(
                    $response,
                    200
                );
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ContractJob.Assets.error_detail');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ContractJob.error_detail');
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Get PPM Frequency of Contract/job
    public function getContractJobPPMFrquencies()
    {
        $response = [];
        $this->validatePermission('view_contract_job'); // Check permission

        $modelContractJob = new ContractJobModel(); // Load model
        $modelContractJobPPM = new ContractJobPPMModel(); // Load model

        $contract_job_id = $this->request->getVar('contract_job_id');
        $job = $modelContractJob->getContractJob($contract_job_id);
        if ($job) {
            $frequencies = [];
            $jobPPMs = $modelContractJobPPM->getPPMFrequencies(
                $contract_job_id,
                ['order' => 'ASC']
            );
            if ($jobPPMs) {
                foreach ($jobPPMs as $key => $value) {
                    $PPM_status = getPPMFrequencyStatusById($value->status);
                    if ($PPM_status) {
                        $jobPPMs[$key]->status = $PPM_status;
                    }
                }
                $response['status'] = 'success';
                $response['message'] = lang('ContractJob.PPM.success_detail');
                $response['contract_job_ppm_frequencies'] = $jobPPMs;
                return $this->setResponseFormat('json')->respond(
                    $response,
                    200
                );
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ContractJob.PPM.error_detail');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ContractJob.error_detail');
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Get Checklists of Contract/job Assets
    public function getContractJobAssetChecklists()
    {
        $response = [];
        $this->validatePermission('view_contract_job'); // Check permission

        $modelContractJob = new ContractJobModel(); // Load model

        $asset_id = $this->request->getVar('asset_id');
        $asset = $modelContractJob->getContractJobAssetById($asset_id);
        if ($asset) {
            $assetChecklists = $modelContractJob->getContractJobAssetChecklists(
                $asset_id
            );
            if ($assetChecklists) {
                $response['status'] = 'success';
                $response['message'] = lang(
                    'ContractJob.AssetChecklist.success_detail'
                );
                $response['asset_checklists'] = $assetChecklists;
                return $this->setResponseFormat('json')->respond(
                    $response,
                    200
                );
            } else {
                $response['status'] = 'error';
                $response['message'] = lang(
                    'ContractJob.AssetChecklist.error_detail'
                );
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ContractJob.error_detail');
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Add Contract/job
    public function addContractJob()
    {
        $response = [];
        $this->validatePermission('add_contract_job'); // Check permission

        $rules = [
            'job_title' => 'required',
            'sap_job_number' => 'required',
        ];

        $messages = [
            'job_title' => [
                'required' => 'Job title is required',
            ],
            'sap_job_number' => [
                'required' => 'SAP job number is required',
            ],
        ];
        if ($this->validate($rules, $messages)) {
            // Validate ppm frequency
            if (!$this->validatePPMFrequency()) {
                $response = [
                    'status' => 'error',
                    'message' => $this->PPMFrequencyErrorMsg,
                ];
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }

            $modelContractJob = new ContractJobModel(); // Load model

            $user_type = getUserTypeByCode('customer');
            $postDatas = $this->request->getPost();

            //Get job prefix
            $contract_type = $this->request->getPost('contract_type');
            $contract_nature = $this->request->getPost('contract_nature');
            $contractJobPrefix = $this->getContractJobPrefix(
                $contract_type,
                $contract_nature
            );

            // Merge post datas with default data
            $job_data = $postDatas;
            $job_data['created_user'] = AuthUser::getId();
            $job_data['status'] = 1;
            $job_data['type'] = 'new';
            $job_data['job_prefix'] = $contractJobPrefix;
            $job_data['user_type'] = $user_type['type_id'];

            if ($postDatas['customer_type'] == 'new') {
                $filter_data = [
                    'removed' => 0,
                    'status' => 1,
                    'username' => $postDatas['customer_username'],
                ];
                $customer = $modelContractJob->getCustomerValidation(
                    $filter_data
                );
            } else {
                $customer = false;
            }

            if ($customer) {
                $response['status'] = 'error';
                $response['message'] = 'Username or email already exits';
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            } else {
                $filter_data = ['removed' => 0];
                $add = $modelContractJob->addContractJob($job_data);
                if ($add) {
                    // Add PPM Frequencies
                    $modelContractJob->addPPMFrequencies(
                        $add,
                        $this->PPMFrequencyDates
                    );

                    // add checklist tracks
                    AppJobManager::run('cron/ContractJob/addChecklistsTracks', [
                        'contract_job_id' => $add,
                    ]);

                    $response['status'] = 'success';
                    $response['message'] = lang('ContractJob.success_add');
                    return $this->setResponseFormat('json')->respond(
                        $response,
                        200
                    );
                } else {
                    $response['status'] = 'error';
                    $response['message'] = lang('ContractJob.error_add');
                    return $this->setResponseFormat('json')->respond(
                        $response,
                        201
                    );
                }
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors(),
            ];
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Edit/Update Contract/job
    public function updateContractJob()
    {
        $response = [];
        // Check permission
        $this->validatePermission('update_contract_job');

        $rules = [
            'job_title' => 'required',
            'sap_job_number' => 'required',
        ];

        $messages = [
            'job_title' => [
                'required' => 'Job title is required',
            ],
            'sap_job_number' => [
                'required' => 'SAP job number is required',
            ],
        ];
        if ($this->validate($rules, $messages)) {
            // Validate ppm frequency
            if (!$this->validatePPMFrequency()) {
                $response = [
                    'status' => 'error',
                    'message' => $this->PPMFrequencyErrorMsg,
                ];
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }

            $modelContractJob = new ContractJobModel(); // Load model

            $user_type = getUserTypeByCode('customer');
            $postDatas = $this->request->getPost();

            $contract_job_id = $this->request->getVar('contract_job_id');
            $job = $modelContractJob->getContractJob($contract_job_id);

            if ($job) {
                // Merge post datas with default data
                $job_data = $postDatas;
                $job_data['created_user'] = AuthUser::getId();
                $job_data['type'] = 'update';
                $job_data['parent_job'] = $job;

                // Check customer username existance
                if ($postDatas['customer_type'] == 'new') {
                    $filter_data = [
                        'removed' => 0,
                        'status' => 1,
                        'username' => $postDatas['customer_username'],
                        // 'email' => $postDatas['customer_billing_address_email']
                    ];
                    $customer = $modelContractJob->getCustomerValidation(
                        $filter_data
                    );
                } else {
                    $customer = false;
                }

                if ($customer) {
                    $response['status'] = 'error';
                    $response['message'] = 'Username or email already exits';
                    return $this->setResponseFormat('json')->respond(
                        $response,
                        201
                    );
                } else {
                    $update = $modelContractJob->updateContractJob($job_data);
                    if ($update) {
                        // Add PPM Frequencies
                        $modelContractJob->addPPMFrequencies(
                            $update,
                            $this->PPMFrequencyDates
                        );

                        // Disable Existing contract job
                        $modelContractJob->disableContractJob(
                            $job->contract_job_id
                        );

                        // add checklist tracks
                        $command = AppJobManager::run(
                            'cron/ContractJob/addChecklistsTracks',
                            [
                                'contract_job_id' => $update,
                            ]
                        );

                        $response['status'] = 'success';
                        $response['message'] = lang(
                            'ContractJob.success_update'
                        );
                        return $this->setResponseFormat('json')->respond(
                            $response,
                            200
                        );
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = lang('ContractJob.error_update');
                        return $this->setResponseFormat('json')->respond(
                            $response,
                            201
                        );
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ContractJob.error_detail');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors(),
            ];
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Renew Contract/job
    public function renewContractJob()
    {
        $response = [];
        // Check permission
        $this->validatePermission('renew_contract_job');

        $rules = [
            'job_title' => 'required',
            'sap_job_number' => 'required',
        ];

        $messages = [
            'job_title' => [
                'required' => 'Job title is required',
            ],
            'sap_job_number' => [
                'required' => 'SAP job number is required',
            ],
        ];
        if ($this->validate($rules, $messages)) {
            // Validate ppm frequency
            if (!$this->validatePPMFrequency()) {
                $response = [
                    'status' => 'error',
                    'message' => $this->PPMFrequencyErrorMsg,
                ];
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }

            $modelContractJob = new ContractJobModel(); // Load model

            $user_type = getUserTypeByCode('customer');
            $job_number = $this->request->getPost('job_number');
            $postDatas = $this->request->getPost();

            //Get job prefix
            $contract_type = $this->request->getPost('contract_type');
            $contract_nature = $this->request->getPost('contract_nature');
            $contractJobPrefix = $this->getContractJobPrefix(
                $contract_type,
                $contract_nature
            );

            $contract_job_id = $this->request->getVar('contract_job_id');
            $job = $modelContractJob->getContractJob($contract_job_id);
            if ($job) {
                // Find job parent
                $jobParent = $job->contract_job_id;
                $jobParentPath = '';
                $paths = [];
                if ($job->parent_path) {
                    $paths = explode(',', $job->parent_path);
                }
                array_push($paths, $jobParent);
                $jobParentPath = implode(',', $paths);

                $job_data = $postDatas;
                $job_data['created_user'] = AuthUser::getId();
                $job_data['type'] = 'renew';
                $job_data['job_prefix'] = $contractJobPrefix;
                $job_data['parent_job'] = $job;

                $filter_data = [
                    'removed' => 0,
                    'status' => 1,
                    'username' => $postDatas['customer_username'],
                    // 'email' => $postDatas['customer_billing_address_email'],
                    'except' => [$job->user_id],
                ];
                $customer = $modelContractJob->getCustomerValidation(
                    $filter_data
                );
                if ($customer) {
                    $response['status'] = 'error';
                    $response['message'] = 'Username or email already exits';
                    return $this->setResponseFormat('json')->respond(
                        $response,
                        201
                    );
                } else {
                    $add = $modelContractJob->updateContractJob($job_data);
                    if ($add) {
                        // Add PPM Frequencies
                        $modelContractJob->addPPMFrequencies(
                            $add,
                            $this->PPMFrequencyDates
                        );

                        // add checklist tracks
                        AppJobManager::run(
                            'cron/ContractJob/addChecklistsTracks',
                            [
                                'contract_job_id' => $add,
                            ]
                        );

                        $response['status'] = 'success';
                        $response['message'] = lang(
                            'ContractJob.success_update'
                        );
                        return $this->setResponseFormat('json')->respond(
                            $response,
                            200
                        );
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = lang('ContractJob.error_update');
                        return $this->setResponseFormat('json')->respond(
                            $response,
                            201
                        );
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ContractJob.error_detail');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => $this->validator->getErrors(),
            ];
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    protected function getContractJobPrefix($contract_type, $contract_nature)
    {
        $modelContractType = new \App\Models\Admin\ContractTypeModel(); // Load model
        $contractType = $modelContractType->getContractType($contract_type);
        $contractTypeName = isset($contractType->name)
            ? strtolower($contractType->name)
            : '';
        $contractTypePrefix = $contractType->job_prefix ?? '';

        if (in_array($contractTypeName, ['lamc', 'camc'])) {
            $modelContractNature = new \App\Models\Admin\ContractNatureModel(); // Load model
            $contractNature = $modelContractNature->getContractNature(
                $contract_nature
            );
            $contractNatureCode = $contractNature->code ?? '';
            $contractJobPrefix =
                $contractTypePrefix . $contractNatureCode . '-';
        } else {
            $contractJobPrefix = $contractTypePrefix;
        }

        return $contractJobPrefix;
    }

    // Set Contract/job Status
    public function setContractJobStatus()
    {
        $response = [];

        //$this->validatePermission('update_contract_job');	// Check permission
        $modelContractJob = new ContractJobModel();

        $contract_job_id = $this->request->getVar('contract_job_id');
        $status = $this->request->getVar('status');
        $job = $modelContractJob->getContractJob($contract_job_id);
        if ($job) {
            // $remove = $modelContractJob->setContractJobStatus($contract_job_id, $status);
            $remove = $modelContractJob->removeContractJob(
                $contract_job_id,
                $status
            );
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('ContractJob.success_status');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    200
                );
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ContractJob.error_removed');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ContractJob.error_detail');
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Delete Contract/job
    public function deleteContractJob()
    {
        $response = [];

        $this->validatePermission('update_contract_job'); // Check permission
        $modelContractJob = new ContractJobModel(); // Load model

        $contract_job_id = $this->request->getVar('contract_job_id');
        $job = $modelContractJob->getContractJob($contract_job_id);
        if ($job) {
            $remove = $modelContractJob->removeContractJob($contract_job_id);
            if ($remove) {
                $response['status'] = 'success';
                $response['message'] = lang('ContractJob.success_removed');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    200
                );
            } else {
                $response['status'] = 'error';
                $response['message'] = lang('ContractJob.error_removed');
                return $this->setResponseFormat('json')->respond(
                    $response,
                    201
                );
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = lang('ContractJob.error_detail');
            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    // Contract/job Validation
    protected function validatePermission($permission_name)
    {
        $permission = AuthUser::checkPermission($permission_name);
        if (!$permission) {
            $response = [
                'status' => 'error',
                'message' => lang('Common.error_permission'),
            ];

            return $this->setResponseFormat('json')->respond($response, 201);
        }
    }

    protected function validatePPMFrequency()
    {
        $freqResponse = [];
        $frquencyDates = [];
        $ppm_frequency = $this->request->getPost('ppm_frequency');
        $start_datez = dbdate_format(
            $this->request->getPost('period_fromdate')
        );
        $end_datez = dbdate_format($this->request->getPost('period_todate'));
        $start_date_obj = new \DateTime($start_datez);
        $start_date_obj->setTime(0, 0, 0);
        $start_date = $start_date_obj->format('Y-m-d H:i:s');

        $end_date_obj = new \DateTime($end_datez);
        $end_date_obj->setTime(24, 0, 0);
        $end_date = $end_date_obj->format('Y-m-d H:i:s');
        $ppmFrequency = getPPMFrequencyByCode($ppm_frequency);
        if ($ppmFrequency) {
            $frequencyCode = $ppmFrequency['frequencyCode'];
            $frequencyNo = $ppmFrequency['frequencyNo'];
            $monthDays = 30;
            $yearMonths = 12;

            // Get total days between two dates
            $startDateObj = new \DateTime($start_date);
            $endDateObj = new \DateTime($end_date);
            $diff = $startDateObj->diff($endDateObj);
            $days = (int) $diff->days;

            // calculate no of days based on frequency
            if ($frequencyCode == 'monthly') {
                $frequencyDays = $frequencyNo * $monthDays;
            } elseif ($frequencyCode == 'yearly') {
                $frequencyDays = $frequencyNo * $monthDays;
            } elseif ($frequencyCode == 'quarterly') {
                $frequencyDays = $frequencyNo * $monthDays;
            } elseif ($frequencyCode == 'half_yearly') {
                $frequencyDays = $frequencyNo * $monthDays;
            } elseif ($frequencyCode == 'monthly_twice') {
                $frequencyDays = ($frequencyNo * $monthDays) / 2;
            } else {
                $frequencyDays = $frequencyNo;
            }

            $intervalString = "+{$frequencyDays} days";
            $startDate = $start_date;
            $slotDays = $days;

            // Segregate dates accroding to frequency and stored in array
            while (strtotime($startDate) < strtotime($end_date)) {
                $frequencyDateObj = new \DateTime($startDate);
                $frequencyStartDate = $frequencyDateObj->format('Y-m-d');
                $frequencyDateObj->modify($intervalString);
                $endDate = $frequencyDateObj->format('Y-m-d H:i:s');
                $startDate = $endDate;

                if ($slotDays >= $frequencyDays) {
                    $frequencyEndDate = (new \DateTime($endDate))
                        ->modify('-1 day')
                        ->format('Y-m-d');
                } else {
                    $frequencyEndDate = (new \DateTime($end_date))
                        ->modify('-1 day')
                        ->format('Y-m-d');
                }
                array_push($frquencyDates, [
                    'start' => $frequencyStartDate,
                    'end' => $frequencyEndDate,
                ]);

                $slotDays = $slotDays - $frequencyDays;
            }

            // check end date is reside in array
            $dateLength = count($frquencyDates);
            $lastDate = $frquencyDates[$dateLength - 1] ?? '';
            $lastDatez = $lastDate['end'] ?? '';
            if ($lastDatez) {
                $endDatez = (new \DateTime($end_date))
                    ->modify('-1 day')
                    ->format('Y-m-d');
                if (strtotime($lastDatez) == strtotime($endDatez)) {
                    $proceed = true;
                    $this->PPMFrequencyErrorMsg =
                        'Dates calculated according to ppm frequency';
                } else {
                    $proceed = false;
                    $this->PPMFrequencyErrorMsg = "Error occured on end date calculation. Frequency {$frequencyCode}";
                }
            } else {
                $proceed = false;
                $this->PPMFrequencyErrorMsg = "Error occured on date segregation. Frequency {$frequencyCode}";
            }
        } else {
            $proceed = false;
            $this->PPMFrequencyErrorMsg = 'No PPM frequency data';
        }

        $this->PPMFrequencyDates = $frquencyDates;

        return $proceed;
    }

    
}
