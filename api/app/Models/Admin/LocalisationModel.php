<?php

namespace App\Models\Admin;

use Config\Services;
use Config\Database;

class LocalisationModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getCountries($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('countries');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('name', $searchData)
                    ->orLike('iso2', $searchData)
                    ->groupEnd();
            }
        }

        // Except Areas
        $exceptData = $data['except'] ?? [];
        if ($exceptData) {
            $builder->whereNotIn('country_id', $data['except']);
        }

        //Limit
        if (isset($data['limit'])) {
            $limit = 20;
            $start = 0;
            if (isset($data['start'])) {
                $start = $data['start'];
            } 

            if ($data['limit']) {
              $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }
              
        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 'country_id';
        }

        $sortOrderData = $data['order'] ?? '';
        if ($sortOrderData) {
            $order = $sortOrderData;
        } else {
            $order = 'desc';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getStates($country_id, $data = [])
    {
        $condition = array(
            's.country_id' => $country_id
        );

        if (isset($data['status'])) {
            $condition['s.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('states s');
        $builder->join('countries c', 'c.country_id = s.country_id');
        $builder->select('s.*, c.name as country_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('s.name', $searchData)
                    ->orLike('s.iso2', $searchData)
                    ->groupEnd();
            }
        }

        // Except Areas
        $exceptData = $data['except'] ?? [];
        if ($exceptData) {
            $builder->whereNotIn('s.state_id', $data['except']);
        }

        //Limit
        if (isset($data['limit'])) {
            $limit = 20;
            $start = 0;
            if (isset($data['start'])) {
                $start = $data['start'];
            } 

            if ($data['limit']) {
              $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }
              
        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 's.state_id';
        }

        $sortOrderData = $data['order'] ?? '';
        if ($sortOrderData) {
            $order = $sortOrderData;
        } else {
            $order = 'desc';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getCities($state_id, $data = [])
    {
        $condition = array(
            'c.state_id' => $state_id
        );

        // if (isset($data['country_id'])) {
        //     $condition['c.country_id'] = (int)$data['country_id'];
        // }

        if (isset($data['status'])) {
            $condition['c.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('cities c');
        $builder->join('states s', 's.state_id = c.state_id');
        $builder->select('c.*, s.name as state_name');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('c.name', $searchData)
                    ->groupEnd();
            }
        }

        // Except Cities
        $exceptData = $data['except'] ?? [];
        if ($exceptData) {
            $builder->whereNotIn('c.city_id', $data['except']);
        }

        //Limit
        if (isset($data['limit'])) {
            $limit = 20;
            $start = 0;
            if (isset($data['start'])) {
                $start = $data['start'];
            } 

            if ($data['limit']) {
              $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }
              
        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 'c.city_id';
        }

        $sortOrderData = $data['order'] ?? '';
        if ($sortOrderData) {
            $order = $sortOrderData;
        } else {
            $order = 'desc';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    
        // Print_r($builder->getLastQuery());
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getStatesList($data = [])
    {

        if (isset($data['status'])) {
            $condition['s.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('states s');
        $builder->select('s.name,s.state_id');
        if ($condition) {
            $builder->where($condition);
        }
        $query = $builder->get();    
        
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getCitiesList($data = [])
    {

        if (isset($data['status'])) {
            $condition['c.status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('cities c');
        $builder->select('c.name,c.city_id');
        if ($condition) {
            $builder->where($condition);
        }
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getCountries23($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('countries');
        $builder->select('country_id,name');
        if ($condition) {
            $builder->where($condition);
        }

        //Limit
            $limit = 17;
         

        $builder->limit($limit);
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }

    public function getCurrencies($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }
        
        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('currency');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('name', $searchData)
                    ->orLike('code', $searchData)
                    ->groupEnd();
            }
        }

        // Except Areas
        $exceptData = $data['except'] ?? [];
        if ($exceptData) {
            $builder->whereNotIn('currency_id', $data['except']);
        }

        //Limit
        if (isset($data['limit'])) {
            $limit = 20;
            $start = 0;
            if (isset($data['start'])) {
                $start = $data['start'];
            } 

            if ($data['limit']) {
              $limit = $data['limit'];
            }

            $builder->limit($limit, $start);
        }
              
        //Sort
        $sortData = $data['sort'] ?? '';
        if ($sortData) {
            $sort = $sortData;
        } else {
            $sort = 'currency_id';
        }

        $sortOrderData = $data['order'] ?? '';
        if ($sortOrderData) {
            $order = $sortOrderData;
        } else {
            $order = 'desc';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return [];
        }
    }
}