<?php

namespace App\Models\Employee;

use Config\Services;
use Config\Database;

class StoreProductModel
{
    private $cdb;

    public function __construct()
    {
        $this->cdb = Database::connect('default');
    }

    public function getTotalProducts($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['sp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['sp.removed'] = (int)$data['removed'];
        }

        if (isset($data['created_by'])) {
            $condition['sp.created_by'] = $data['created_by'];
        }

        if (isset($data['category_id'])) {
            $condition['sp.category_id'] = $data['category_id'];
        }

        if (isset($data['sub_category_id'])) {
            $condition['sp.sub_category_id'] = $data['sub_category_id'];
        }

        $builder = $this->cdb->table('store_product as sp');
        $builder->join('store_category sc1', 'sc1.category_id = sp.category_id');
        $builder->join('store_category sc2', 'sc2.category_id = sp.sub_category_id');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('sp.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('sp.product_id', $data['except']);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getProducts($data = [])
    {
        $condition = array();

        if (isset($data['status'])) {
            $condition['sp.status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['sp.removed'] = (int)$data['removed'];
        }

        if (isset($data['created_by'])) {
            $condition['sp.created_by'] = $data['created_by'];
        }

        if (isset($data['category_id'])) {
            $condition['sp.category_id'] = $data['category_id'];
        }

        if (isset($data['sub_category_id'])) {
            $condition['sp.sub_category_id'] = $data['sub_category_id'];
        }

        $builder = $this->cdb->table('store_product as sp');
        $builder->join('store_category sc1', 'sc1.category_id = sp.category_id');
        $builder->join('store_category sc2', 'sc2.category_id = sp.sub_category_id');
        $builder->select('sp.*, sc1.name as category_name, sc2.name as sub_category_name');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('sp.name', $searchData)
                    ->groupEnd();
            }
        }

        if (isset($data['except'])) {
            $builder->whereNotIn('sp.product_id', $data['except']);
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
            $sort = 'sp.product_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getProduct($product_id, $data = [])
    {
        $condition = array(
            'product_id' => (int)$product_id
        );

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('store_product');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            return 0;
        }
    }


    public function addProduct($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'name' => $data['name'],
            'created_by' => $data['created_by'],
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'amount' => $data['amount'],
            'specification' => $data['specification'],
            'status' => 1,
            'removed' => 0,
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('store_product')->insert($insert_data);
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();
        // echo $this->cdb->getLastQuery();
        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    }

    public function editProduct($product_id, $data)
    {

        $condition = array(
            'product_id' => $product_id
        );
        $update_data = array(
            'name' => $data['name'],
            'created_by' => $data['created_by'],
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'amount' => $data['amount'],
            'specification' => $data['specification'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_product');
        $builder->where($condition);
        $result = $builder->update($update_data);

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removeProduct($product_id)
    {

        $condition = array(
            'product_id' => $product_id
        );
        $update_data = array(
            'removed' => 1,
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('store_product');
        $builder->where($condition);
        $result = $builder->update($update_data);
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    // Product Stock
    public function addProductStock($product_id, $data)
    {
        $this->cdb->transStart();
        // Update quantity to product
        $this->cdb->table('store_product')
            ->where(['product_id' => (int)$product_id])
            ->set('quantity', 'quantity +' . $data['quantity'], false)
            ->update();
        
        // Add stock detail for tracking
        $stock_data = array(
            'product_id' => (int)$product_id,
            'updated_by' => (int)$data['updated_by'],
            'quantity' => (int)$data['quantity'],
            'operation' => 'plus', 
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('store_product_stock');
        $result = $builder->insert($stock_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function reduceProductStock($product_id, $data)
    {
        $this->cdb->transStart();
        // Update quantity to product
        $this->cdb->table('store_product')
            ->where(['product_id' => (int)$product_id])
            ->set('quantity', 'quantity - ' . $data['quantity'], false)
            ->update();
        
        // Add stock detail for tracking
        $stock_data = array(
            'product_id' => (int)$product_id,
            'updated_by' => (int)$data['updated_by'],
            'quantity' => (int)$data['quantity'],
            'operation' => 'minus', 
            'created_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->table('store_product_stock')
            ->insert($stock_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getProductStock($product_id, $data = [])
    {
        $condition = array(
            'product_id' => (int)$product_id,
        );  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('store_product');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        if($data['quantity']) {
            $builder->where('quantity >=', $data['quantity']);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return true;
        } else {
            return false;
        }
    }
}
