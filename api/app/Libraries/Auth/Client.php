<?php

    namespace App\Libraries\Auth;
    
    use App\Libraries\Auth\Token as AuthToken;
    use Config\Services;
    use Config\Database;

    class Client
    {   
        private $request;
        private $response;
        private $clientDetails = [];
        private $dataLevels = [];

        public function __construct()
        {
            $this->request = Services::request();
            $this->response = Services::response();
        }

        public function getDetails()
        {
            return $this->clientDetails;
        }

        public function validateAccess($options = [])
        {
            $config = array_merge([
                'authType' => 'jwt',
                'checkExpiry' => true
            ], $options);
            
            $db = Database::connect('default', false);
            helper('client_user'); // Load helper

            if ($config['authType'] == 'ciEncrypt') {
                helper('encryption'); // Load helper
                $authorization = $this->request->getServer('HTTP_AUTH_TOKEN');
                $authdata = decrypt($authorization);

                if (isset($authdata['status']) && $authdata['status'] == 'success') {
                    $authData = [
                        'status' => $authdata['status'],
                        'data' => $this->parseDataLevels($authdata['data'])
                    ];
                } else {
                    $authData = $authdata;
                }
            } else {
                $authorization = $this->request->getServer('HTTP_AUTHORIZATION');
                $authData = AuthToken::getJWT($authorization);
            }
           
            if ($this->request->hasHeader('Remote-Domain')) {
                $hostname = $this->request->header('Remote-Domain')->getValue();
                
                $authStatus = isset($authData['status']) ? $authData['status'] : false;
                if ($authStatus == 'success') {
                    $clientUid = isset($authData['data']['cid']) ? $authData['data']['cid'] : '';
                    $clientUDID = isset($authData['data']['cdid']) ? $authData['data']['cdid'] : '';
                    
                    $client = $db->table('client AS c')
                        ->join('client_domain AS cd', 'cd.client_id = c.client_id')
                        ->join('client_domain_service AS cds', 'cds.client_domain_id = cd.client_domain_id')
                        ->select('c.*, cd.client_domain_id AS domain_id, cd.domain, cd.status AS domain_status, cds.end_date AS expiry_date')
                        ->where(['cd.client_domain_id' => $clientUDID, 'cd.client_id' => $clientUid])
                        ->get()->getRow();
                    if ($client) {
                        $this->clientDetails = $client;
                        // Check client status
                        if ($client->status == 1) { 
                            if ($client->domain_status == 1) {
                                // Check host name
                                if ($hostname == $client->domain) {
                                    if ($client->expiry_date >= date('Y-m-d')) {                         
                                        $assocResponse = [
                                            'code' => 200,
                                            "status" => "success",
                                            'message' => lang('Client.api.domain_verified')
                                        ];
                                    } else {
                                        $assocResponse = [
                                            'code' => 201,
                                            "status" => "error",
                                            'message' => lang('Client.api.service_expired'),
                                        ];
                                    }
                                } else {
                                    $assocResponse = [
                                        "status" => "error",
                                        "message" => lang('Client.api.domain_not_verified'),
                                    ];
                                }
                            } else {
                                $assocResponse = [
                                    "status" => "error",
                                    "message" => lang('Client.api.inactive_domain_status'),
                                ];
                            }
                        } else {
                            $assocResponse = [
                                "status" => "error",
                                "message" => lang('Client.api.inactive_status'),
                            ];
                        }
                    } else {
                        $assocResponse = [
                            "status" => "error",
                            "message" => lang('Client.api.no_client'),
                        ];
                    }
                    $assocResponse['authData'] = $authData['data'];
                } else {
                    $assocResponse = [
                        "status" => "error",
                        "message" => $authData["message"],
                        "authData" => []
                    ];
                }
            } else {
                $assocResponse = [
                    "status" => "error",
                    "message" => lang('Client.api.invalid_remote_domain'),
                    "authData" => []
                ];
            }

            return $assocResponse;
        }

    protected function parseDataLevels($datas)
    {
        $dataLevel = [];
        if (strpos($datas, ',')) {
            $dataLevel1 = explode(',', $datas);
            if ($dataLevel1) {
                $dataLevel = $dataLevel1;
            }
        }

        if ($dataLevel) {
            if (is_array($dataLevel)) {
                foreach ($dataLevel as $dataLevelz) {
                    $this->parseDataLevel($dataLevelz);
                }
            } else {
                $this->parseDataLevel($dataLevel);
            }
        }

        return $this->dataLevels;
    }

    protected function parseDataLevel($dataLevelz)
    {
        if (strpos($dataLevelz, ':')) {
            $dataLevel2 = explode(':', $dataLevelz);
            if ($dataLevel2) {
                $chunkDatas = array_chunk($dataLevel2, 2);
                foreach ($chunkDatas as $chunkData) {
                    if ($chunkData[0]) { 
                        $this->dataLevels[$chunkData[0]] = $chunkData[1];
                    }
                }
            }
        }
    }
    }