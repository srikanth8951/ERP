<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use App\Libraries\AppLog;
use App\Libraries\AppJobManager;
use App\Libraries\AppCliUri;
use App\Models\Admin\ContractJobModel;
use App\Models\Admin\EmployeeModel;
use App\Libraries\Template\Twig_loader;
use App\Libraries\App_email as ciMailer;
use Config\Database;

class ContractJob extends BaseController
{
    private $PPMFrequencyDates = [];

    private $db;

    public function __construct()
    {
        $this->db = Database::connect(); // connect to default database

        helper(['default', 'user', 'contract_job']); // Loading user & contract_job helpers

        AppLog::initLog(); // Init log
    }

    // Get list of Contract/jobs
    public function contractJob()
    {
        $modelContractJob = new ContractJobModel(); // Load model

        $response = [];

        $filter_data = [
            'removed' => 0,
            'status' => 1,
            'contract_status_id' => 2,
        ];

        $jobs = $modelContractJob->getContractJobs($filter_data);
        if ($jobs) {
            foreach ($jobs as $job) {
                $this->checkExpiry($job->contract_job_id);
            }
            $response['status'] = 'success';
            $responseMessage = lang('ContractJob.success_detail');
        } else {
            $response['status'] = 'error';
            $responseMessage = "lang('ContractJob.error_detail')";
        }
        AppLog::writeLog('response', json_encode($response));
    }

    /**
     * Check Contract/Job Expiry
     */
    public function checkExpiry()
    {
        $modelContractJob = new ContractJobModel(); // Load model
        $expiredId = getContractJobStatusByName('Expired')->id ?? 4;
        $warrentyId = getContractJobStatusByName('Warrenty')->id ?? 1;
        $inContractId = getContractJobStatusByName('In Contract')->id ?? 2;
        $responseMessage = '';
        $employee_details = [];
        $jobs = $modelContractJob->getContractJobs([
            'status' => 1,
            'contract_status' => [(int)$warrentyId, (int)$inContractId]
        ]);
        
        if ($jobs) {
            foreach ($jobs as $job) {
                $start_date = $job->period_fromdate;
                $end_date = $job->period_todate;
                $currentDate = date('Y-m-d');
                $mailTrigger_30_DaysBefore = (new \DateTime($end_date))->modify("-30 days");

                //checking contract job expiry date 30 days before
                if ($currentDate == $mailTrigger_30_DaysBefore->format('Y-m-d')) {
                    if ($job->engineer_id && $job->customer_account_manager_id) {
                        //getting employee email id
                        $employee_details = $this->getEmployeeDetails(
                            $job->engineer_id,
                            $job->customer_account_manager_id
                        );
                    }
                    $message =
                        'Your Job/contract (job_number: ' .
                        $job->job_number .
                        ') will expire in 30 days';
                    $this->sendExpiryMail($job, $message, $employee_details);
                    $responseMessage .= 'Job/contract (job_number: ' .$job->job_number .') - Mail triggered 30 days before expiry';
                } elseif ($currentDate > $end_date) {
                    //checking contract job expiry date is exceeded or not
                    $contract_status_id = $expiredId;
                    $result = $modelContractJob->setContractStatus(
                        $job->contract_job_id,
                        $contract_status_id
                    );
                    if ($result) {
                        if (
                            $job->engineer_id &&
                            $job->customer_account_manager_id
                        ) {
                            $employee_details = $this->getEmployeeDetails(
                                $job->engineer_id,
                                $job->customer_account_manager_id
                            );
                        }
                        $message = 'Job/contract (job_number: ' .$job->job_number .') is expired';
                        $this->sendExpiryMail($job, $message, $employee_details);
                        $responseMessage .= 'Job/contract (job_number: ' .$job->job_number .') is expired. Mail triggered after job/contract expired'.PHP_EOL;
                    } else {
                        $responseMessage .= 'Job/contract (job_number: ' .$job->job_number .') is expired. Mail was not triggered'.PHP_EOL;
                    }
                } else {
                    $responseMessage .= 'Job/contract (job_number: ' .$job->job_number .') is not expired.'.PHP_EOL;
                }
            }
        } else {
            $responseMessage = lang('ContractJob.error_detail');
        }
        AppLog::writeLog('check_expiry', $responseMessage);
    }

    public function getEmployeeDetails($employee_id, $cam_id)
    {
        $response = [];

        $modelEmployee = new EmployeeModel(); // Load model

        $engineer = $modelEmployee->getEmployeeDetails($employee_id);
        if ($engineer) {
            $filter_data = [
                'removed' => 0,
                'status' => 1,
                '',
            ];
            $asd = $modelEmployee->getEmployeeByTypes(221, [
                'removed' => 0,
                'status' => 1,
                'region_id' => $engineer->region_id,
                'branch_id' => $engineer->branch_id,
            ]);
            $rsd = $modelEmployee->getEmployeeByTypes(220, [
                'removed' => 0,
                'status' => 1,
                'region_id' => $engineer->region_id,
            ]);
            $regional_head = $modelEmployee->getEmployeeByTypes(211, [
                'removed' => 0,
                'status' => 1,
                'region_id' => $engineer->region_id,
            ]);
            $aisd_head = $modelEmployee->getEmployeeByTypes(210, [
                'removed' => 0,
                'status' => 1,
            ]);
            $national_head = $modelEmployee->getEmployeeByTypes(201, [
                'removed' => 0,
                'status' => 1,
            ]);
            $dmts = $modelEmployee->getEmployeesByTypes(217, [
                'removed' => 0,
                'status' => 1,
            ]);
            $cam = $modelEmployee->getEmployeeByID($cam_id);
            if (
                $asd &&
                $rsd &&
                $regional_head &&
                $aisd_head &&
                $national_head
            ) {
                $response['employee_email'] = [
                    $engineer->engineer_email,
                    $asd->email,
                    $rsd->email,
                    $regional_head->email,
                    $aisd_head->email,
                    $national_head->email,
                    $cam->email,
                ];
            }
            if ($dmts) {
                foreach ($dmts as $dmt) {
                    array_push($response['employee_email'], $dmt->email);
                }
            }
            return $response;
        } else {
            return $response;
        }
    }

    public function sendExpiryMail($data, $message, $employee_details)
    {
        $response = false;

        $twig = new Twig_loader();
        $settings = getSettings('config_system');
        $customerData = [];

        $customer_id = $data->customer_id;
        $customer = $this->db
            ->table('customer')
            ->where('customer_id', $customer_id)
            ->get()
            ->getRow();
        if ($customer) {
            $customerData['company_name'] = $customer->company_name;
            $customerData['message'] = $message;

            $mail_params = [
                'to_name' => $customer->company_name,
                'to_email' => $customer->billing_address_email,
                'to_additionals' => $employee_details['employee_email'],
                'subject' => 'Job/contract Expiry Status',
                'content' => $twig->render('email/contract_job_expiry_status', [
                    'customer' => $customerData,
                    'settings' => $settings,
                ]),
            ];

            // Initiate mail
            $ciMailer = new ciMailer();
            $response = $ciMailer->send($mail_params);
        }

        return $response;
    }

    // Checklist track
    public function addChecklistsTracks()
    {
        $cliUri = new AppCliUri();
        $contract_job_id = $cliUri->getQuery('contract_job_id');

        $message = "Checklist track of contract job {$contract_job_id} is initiated";
        AppLog::writeLog('checklist_track', $message);

        $modelContractJob = new ContractJobModel(); // Load model
        $tracks = [];
        $ppmRecords = $modelContractJob->getPPMFrequencies($contract_job_id, [
            'job_status' => 1,
        ]);
        $assets = $modelContractJob->getContractJobAssetsChecklists(
            $contract_job_id
        );

        if ($ppmRecords && $assets) {
            foreach ($ppmRecords as $ppm) {
                foreach ($assets as $asset) {
                    $tracks[] = [
                        'contract_job_id' => $asset->contract_job_id,
                        'contract_job_asset_id' =>
                            $asset->contract_job_asset_id,
                        'contract_job_ppm_id' => $ppm->contract_job_ppm_id,
                        'asset_checklist_id' => $asset->asset_checklist_id,
                    ];
                }
            }
        }

        if ($tracks) {
            $result = $modelContractJob->addChecklistTracks($tracks);
            if ($result) {
                $message = 'Checklists track added';
            } else {
                $message = 'Error occured while adding checklist tracks';
            }
        } else {
            $result = false;
            $message = 'No tracks are available to add!';
        }

        AppLog::writeLog('checklist_track', $message);
    }

    public function addChecklistsTracksz()
    {
        $cliUri = new AppCliUri();
        $contract_job_id = $cliUri->getQuery('contract_job_id');

        $message = "Checklist track of contract job {$contract_job_id} is initiated";
        AppLog::writeLog('checklist_track', $message);

        $modelContractJobAsset = new \App\Models\Employee\ContractJobAssetModel(); // Load model
        $modelContractJobPPM = new \App\Models\Employee\ContractJobPPMModel(); // Load model
        $tracks = [];
        $ppmRecords = $modelContractJobPPM->getPPMFrequencies(
            $contract_job_id,
            ['job_status' => 1]
        );
        $assets = $modelContractJobAsset->getAssetsChecklists($contract_job_id);

        if ($ppmRecords && $assets) {
            foreach ($ppmRecords as $ppm) {
                foreach ($assets as $asset) {
                    $tracks[] = [
                        'contract_job_id' => $asset->contract_job_id,
                        'contract_job_asset_id' =>
                            $asset->contract_job_asset_id,
                        'contract_job_ppm_id' => $ppm->contract_job_ppm_id,
                        'asset_checklist_id' => $asset->asset_checklist_id,
                    ];
                }
            }
        }

        if ($tracks) {
            $result = $modelContractJobAsset->addChecklistTracks($tracks);
            if ($result) {
                $message = 'Checklists track added';
            } else {
                $message = 'Error occured while adding checklist tracks';
            }
        } else {
            $result = false;
            $message = 'No tracks are available to add!';
        }

        AppLog::writeLog('checklist_track', $message);
    }

    /**
     * Check PPM Frequency
     */
    public function checkPPMFrequencies()
    {
        $modelContractJob = new ContractJobModel(); // Load model
        $warrentyId = getContractJobStatusByName('Warrenty')->id ?? 1;
        $inContractId = getContractJobStatusByName('In Contract')->id ?? 2;
        $notInContractId = getContractJobStatusByName('Not in Contract')->id ?? 2;
        $responseMessage = '';
        $employee_details = [];
        $jobs = $modelContractJob->getContractJobs([
            'status' => 1,
            'contract_status' => [(int)$warrentyId, (int)$inContractId]
        ]);
        
        if ($jobs) {
            foreach ($jobs as $job) {
                $responseMessage = 'Contract/Job number: ' . $job->job_number . ' - Initiated to check ppm frequency';
                AppLog::writeLog('check_expiry', $responseMessage);
                AppJobManager::run('cron/ContractJob/updatePPMFrquenciesStatus', [
                    'contract_job_id' => $job->contract_job_id
                ]);
            }
        } else {
            $responseMessage = lang('ContractJob.error_detail');
            AppLog::writeLog('check_expiry', $responseMessage);
        }
       
    }

    /**
     * Update ppm frequencies Status
     */
    public function updatePPMFrquenciesStatus()
    {
        $currentDate = date('Y-m-d');
        $cliUri = new AppCliUri();
        $contract_job_id = $cliUri->getQuery('contract_job_id');

        $message = "Job - {$contract_job_id}: PPM status check is initiated";
        AppLog::writeLog('ppm_frequencies_update', $message);

        $modelContractJob = new ContractJobModel(); // Load model
        $modelContractJobPPM = new \App\Models\Employee\ContractJobPPMModel(); // Load model

        $job = $modelContractJob->getContractJob($contract_job_id);
        if ($job) {
            $frequencies = [];
            $pending = getPPMFrequencyStatusByCode('pending');
            $pendingId = $pending['id'] ?? 0;
            $upcoming = getPPMFrequencyStatusByCode('upcoming');
            $upcomingId = $upcoming['id'] ?? 0;
            $ongoing = getPPMFrequencyStatusByCode('ongoing');
            $ongoingId = $ongoing['id'] ?? 0;
            $ppm = $modelContractJobPPM->getCurrentPPMFrequency(
                $contract_job_id
            );
            $jobPPMs = $modelContractJobPPM->getPPMFrequencies(
                $contract_job_id,
                [
                    'ppm_status' => [$pendingId, $upcomingId, $ongoingId],
                    'job_status' => 1,
                ]
            );

            $message =
                "Job - {$contract_job_id}: Available job frequencies - " .
                count($jobPPMs);
            AppLog::writeLog('ppm_frequencies_update', $message);

            if ($jobPPMs) {
                // $ppmStartDate = $ppm->start_date;
                // $ppmEndDate = $ppm->end_date;
                $ppmMessage = '';
                foreach ($jobPPMs as $frequency) {
                    $startDate = $frequency->start_date;
                    $endDate = $frequency->end_date;
                    if (
                        $startDate <= $currentDate &&
                        $currentDate <= $endDate
                    ) {
                        $statusId = $ongoingId ?? 1;
                    } elseif ($currentDate < $endDate) {
                        $statusId = $upcomingId ?? 1;
                    } elseif ($currentDate > $endDate) {
                        $status = getPPMFrequencyStatusByCode('incomplete');
                        $statusId = $status['id'] ?? 1;
                    }

                    // set status
                    $modelContractJobPPM->setPPMFrequencyStatus(
                        $frequency->contract_job_ppm_id,
                        $statusId
                    );
                    $contract_job_ppm_id = $frequency->contract_job_ppm_id;
                    $ppmMessage .= "Job - {$contract_job_id}: PPM Frequency({$contract_job_ppm_id}) status[{$statusId}] updated" . PHP_EOL;
                }

                AppLog::writeLog('ppm_frequencies_update', $ppmMessage);
            } else {
                $message = "Job - {$contract_job_id}: no PPM frequencies/current frequency";
                AppLog::writeLog('ppm_frequencies_update', $message);
            }
        } else {
            $message = "Job - {$contract_job_id}: No job available";
            AppLog::writeLog('ppm_frequencies_update', $message);
        }

        $message = "Job - {$contract_job_id}: PPM frequencies update completed";
        AppLog::writeLog('ppm_frequencies_update', $message);
    }

    /**
     * Update ppm frequency status
     */
    // public function updatePPMFrquencyStatus()
    // {
    //     $currentDate = date('Y-m-d');
    // 	$cliUri = new AppCliUri();
    //     $contract_job_asset_id = $cliUri->getQuery('contract_job_asset_id');
    //     $contract_job_ppm_id = $cliUri->getQuery('contract_job_ppm_id');

    //     $message = "PPM - {$contract_job_ppm_id}: Checklist track is initiated";
    // 	AppLog::writeLog('ppm_frequency_update', $message);

    // 	$modelContractJobPPM = new \App\Models\Employee\ContractJobPPMModel();	// Load model
    //     $modelContractJobAsset = new \App\Models\Employee\ContractJobAssetModel();	// Load model

    //     $statuses = [];
    //     $assetChecklists = $modelContractJobAsset->getAssetChecklists($contract_job_asset_id, [
    //         'contract_job_ppm_id' => $contract_job_ppm_id,
    //         'order' => 'ASC'
    //     ]);
    //     $message = "PPM - {$contract_job_ppm_id}: Available asset checklists - " . count($assetChecklists);
    // 	AppLog::writeLog('ppm_frequency_update', $message);

    //     if ($assetChecklists) {
    //         foreach ($assetChecklists as $checklist) {
    //             $status = (int)$checklist->track_status;
    //             if ($status == 3) {
    //                 array_push($statuses, 1);
    //             } else {
    //                 array_push($statuses, 0);
    //             }
    //         }

    //         if ($statuses && in_array(0, $statuses) === false) {
    //             $complete = getPPMFrequencyStatusByCode('completed');
    //             $completeId = $complete['id'] ?? 0;
    //             $update = $modelContractJobPPM->setPPMFrequencyStatus($contract_job_ppm_id, $completeId);
    //             $message = "PPM - {$contract_job_ppm_id}:" . ($update ? "PPM status changed to complete" : "PPM status not changed to complete");
    //         } else {
    //             $message = "PPM - {$contract_job_ppm_id}: No changes in PPM status";
    //             // $message = "PPM - {$contract_job_ppm_id}: All checklists are not completed" . PHP_EOL;
    //             // $incomplete = getPPMFrequencyStatusByCode('incomplete');
    //             // $incompleteId = $incomplete['id'] ?? 0;
    //             // $update = $modelContractJobPPM->setPPMFrequencyStatus($contract_job_ppm_id, $incompleteId);
    //             // $message .= "PPM - {$contract_job_ppm_id}:" . ($update ? "PPM status changed to incomplete " : "PPM status not changed to incomplete");
    //         }

    //         AppLog::writeLog('ppm_frequency_update', $message);
    //     } else {
    //         $message = "PPM - {$contract_job_ppm_id}: No Checklist track available";
    // 	    AppLog::writeLog('ppm_frequency_update', $message);
    //     }

    // }

    public function updatePPMFrquencyStatus()
    {
        $currentDate = date('Y-m-d');
        $cliUri = new AppCliUri();
        $contract_job_id = $cliUri->getQuery('contract_job_id');
        $contract_job_ppm_id = $cliUri->getQuery('contract_job_ppm_id');

        $message = "PPM - {$contract_job_ppm_id}: Checklist track is initiated";
        AppLog::writeLog('ppm_frequency_update', $message);

        $modelContractJobPPM = new \App\Models\Employee\ContractJobPPMModel(); // Load model
        $modelContractJobAsset = new \App\Models\Employee\ContractJobAssetModel(); // Load model
        $statuses = [];
        $assets = $modelContractJobAsset->getAssets($contract_job_id);
        if ($assets) {
            foreach ($assets as $asset) {
                $assetChecklists = $modelContractJobAsset->getAssetChecklists(
                    $asset->contract_job_asset_id,
                    [
                        'contract_job_ppm_id' => $contract_job_ppm_id,
                        'track_status' => 3,
                        'order' => 'ASC',
                    ]
                );
                $message =
                    "PPM - {$contract_job_ppm_id}: Completed asset" .
                    $asset->contract_job_asset_id .
                    ' checklists - ' .
                    count($assetChecklists);
                AppLog::writeLog('ppm_frequency_update', $message);
                if ($assetChecklists) {
                    array_push($statuses, 1);
                } else {
                    array_push($statuses, 0);
                }
            }
        }

        if ($statuses && in_array(0, $statuses) === false) {
            $complete = getPPMFrequencyStatusByCode('completed');
            $completeId = $complete['id'] ?? 0;
            $update = $modelContractJobPPM->setPPMFrequencyStatus(
                $contract_job_ppm_id,
                $completeId
            );
            $message =
                "PPM - {$contract_job_ppm_id}:" .
                ($update
                    ? 'PPM status changed to complete'
                    : 'PPM status not changed to complete');
        } else {
            $message = "PPM - {$contract_job_ppm_id}: No changes in PPM status";
        }

        AppLog::writeLog('ppm_frequency_update', $message);
    }

}
