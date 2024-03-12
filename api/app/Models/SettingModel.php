<?php

namespace App\Models;

class SettingModel
{
	protected $db;
    protected $request;

	public function __construct()
	{
		$this->db = \Config\Database::connect();
        $this->request = \Config\Services::request();
	}

	//Setting
    public function getSettings($code)
	{
    	$condition = array(
			'code' => $code,
		);
		$builder = $this->db->table('setting');
		$builder->where($condition);
		$query = $builder->get();
        if ($query->getNumRows() > 0) {
            $result_array = array();
        	$results = $query->getResult();
            foreach ($results as $result) {
                if ($result->is_serialized) {
                    $result_value = json_decode($result->value);
                } else {
                    $result_value = $result->value;
                }

                $result_array[$result->keyword] = $result_value;
            }
            return (object)$result_array;
        } else {
        	return false;
        }
	}

    public function getSetting($code, $keyword)
	{
        $condition = array(
			'keyword' => $keyword,
            'code' => $code
		);
		$builder = $this->db->table('setting');
		$builder->where($condition);
		$query = $builder->get();
        if ($query->getNumRows() > 0) {
        	$row = $query->getRow();
            if ($row->is_serialized == 1) {
                $row->value = json_decode($row->value);
            }
            return $row;
        } else {
        	return false;
        }
	}

    public function addSetting($code, $keyword, $value)
	{
        if (is_array($value)) {
            $keyValue = json_encode($value);
            $serialized = 1;
        } else {
            $keyValue = esc($value);
            $serialized = 0;
        }

        $insert_data = array(
            'code' => $code,
            'keyword' => $keyword,
            'value' => $keyValue,
            'is_serialized' => $serialized,
            'created_datetime' => date('Y-m-d H:i:s')
        );

        $query = $this->db->table('setting')->insert($insert_data);
        return $this->db->insertId();
    }

    public function editSetting($setting_id, $value)
	{
        if (is_array($value)) {
            $keyValue = json_encode($value);
            $serialized = 1;
        } else {
            $keyValue = esc($value);
            $serialized = 0;
        }

        $update_data = array(
            'value' => $keyValue,
            'is_serialized' => $serialized,
            'updated_datetime' => date('Y-m-d H:i:s')
        );

		$builder = $this->db->table('setting');
        $builder->where('setting_id', $setting_id);
        $query = $builder->update($update_data);
        return $query;
    }
}