<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settingDatas = [
            [
                'code' => 'config_system',
                'keyword' => 'app_company_name',
                'value' => 'Mentric Tech',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_email',
                'value' => 'sasics.2394@gmail.com',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_mobile',
                'value' => '7904633883',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_email',
                'keyword' => 'mail_engine',
                'value' => 'smtp',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_email',
                'keyword' => 'host_email',
                'value' => 'sasics.2394@gmail.com',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_email',
                'keyword' => 'smtp_port',
                'value' => '465',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_email',
                'keyword' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_email',
                'keyword' => 'smtp_username',
                'value' => 'mentricadm@gmail.com',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_email',
                'keyword' => 'smtp_password',
                'value' => 'Mentric@2021',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_name',
                'value' => 'Sterling Wilson',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_slogan',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_file_storage',
                'value' => 'default',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_addr_streetname',
                'value' => 'Dasarahalli',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_addr_city',
                'value' => 'Bangalore',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_addr_state',
                'value' => 'Karnataka',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_addr_pincode',
                'value' => '666666',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_facebook_profile',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_google_profile',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_twitter_profile',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_linkedin_profile',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_youtube_profile',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_instagram_profile',
                'value' => '',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s')
            ],
            [
                'code' => 'config_system',
                'keyword' => 'app_logo',
                'value' => 'applogo20220117193940.png',
                'is_serialized' => '0',
                'created_datetime' => date('Y-m-d H:i:s'),
            ]
        ];
        $this->db->table('setting')->insertBatch($settingDatas);
    }
}
