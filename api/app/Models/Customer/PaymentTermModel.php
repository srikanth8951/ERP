<?php 

namespace App\Models\Customer;

use Config\Services;
use Config\Database;

class PaymentTermModel
{
    private $cdb;

	public function __construct()
    {        
        $this->cdb = Database::connect('default');
	}

    public function getTotalPaymentTerms($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['is_exist'] = (int)$data['is_exist'];
        }
        
        $builder = $this->cdb->table('payment_term');
        $builder->select('COUNT(*) AS total');
        if ($condition) {
            $builder->where($condition);
        }

        // Search
        $searchData = $data['search'] ?? '';
        if ($searchData) {
            if ($data['search']) {
                $builder->groupStart()
                    ->like('name', $searchData)
                    ->groupEnd();
            }
        }
        
        if (isset($data['except'])) {
            $builder->whereNotIn('payment_term_id', $data['except']);
        }

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow()->total;
        } else {
            return 0;
        }
    }

    public function getPaymentTerms($data = [])
    {
        $condition = array();  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        if (isset($data['is_exist'])) {
            $condition['is_exist'] = (int)$data['is_exist'];
        }

        $builder = $this->cdb->table('payment_term');
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
                    ->groupEnd();
            }
        }
        
        if (isset($data['except'])) {
            $builder->whereNotIn('payment_term_id', $data['except']);
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
            $sort = $data['sort'];
        } else {
            $sort = 'payment_term_id';
        }

        if (isset($data['order'])) {
            $order = $data['order'];
        } else {
            $order = 'DESC';
        }
        $builder->orderBy($sort, $order);

        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }

    public function getPaymentTerm($payment_term_id, $data = [])
    {
        $condition = array(
            'payment_term_id' => (int)$payment_term_id
        );  

        if (isset($data['status'])) {
            $condition['status'] = (int)$data['status'];
        }

        $builder = $this->cdb->table('payment_term');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function getPaymentTermByTitle($title, $data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['removed'] = $data['removed'];
        }


        $builder = $this->cdb->table('payment_term');
        $builder->select('*');
        if ($condition) {
            $builder->where($condition);
        }
        
        // Check title
        $check_title = 'REPLACE(LOWER(title), " ", "_") = REPLACE(LOWER("'. $title .'")," ", "_")';
        $builder->where($check_title);

        if (isset($data['except'])) {
            $builder->whereNotIn('payment_term_id', $data['except']);
        }
        
        $query = $builder->get();    

        if ($query->getNumRows() > 0  ) {
            return $query->getRow();
        } else {
            return 0;
        }
    }

    public function addPaymentTerm($data)
    {
        $this->cdb->transStart();
        $insert_data = array(
            'title' => $data['title'], 
            'description' => $data['description'],    
            'status' => (int)$data['status'],
            'is_exist' => (int)$data['is_exist'] ?? 0,
            'created_datetime' =>date('Y-m-d H:i:s')
        );
        $this->cdb->table('payment_term')->insert($insert_data); 
        $insertId = $this->cdb->insertID();
        $this->cdb->transComplete();

        if($data['title'] == ''){
            $this->removePaymentTerm($insertId);
        }

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $insertId;
        }
    } 

    public function editPaymentTerm($payment_term_id, $data)
    {
        $this->cdb->transStart();
        $condition = array(
            'payment_term_id' => $payment_term_id
        );
        $update_data = array(
            'title' => $data['title'],   
            'description' => $data['description'], 
            'status' => (int)$data['status'],
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition);
        $result = $builder->update($update_data); 

        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    }

    public function removePaymentTerm($payment_term_id)
    {
        $this->cdb->transStart();
        $condition = array(
            'payment_term_id' => $payment_term_id
        );
        $update_data = array( 
            'removed' => 1,   
            'status' => 0,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    
    function getPaymentTermExist($data = [])
    { 
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist']
        );
        $builder = $this->cdb->table('payment_term');
        $builder->select('*');
        $builder->groupStart()
        ->like('title', $data['title'])
        ->groupEnd();       
        $builder->where($condition);

        $query = $builder->get();    
        if ($query->getNumRows() > 0  ) {
            $val = $query->getRow(); 
            return $val;
        } else {
            return 0;
        }
    }

    function getPaymentTermExists($data = [])
    {
        $condition = array();  

        $condition = array( 
            'removed' => 0,   
            'status' => 1,
            'is_exist' => $data['is_exist'],
            'title' => $data['title'],
        );
        $builder = $this->cdb->table('payment_term');
        $builder->select('*');
        $builder->groupStart()
        ->like('title', $data['title'])
        ->groupEnd();       
        $builder->where($condition);

        $query = $builder->get();    
        if ($query->getNumRows() > 0  ) {
            $val = $query->getRow();
            return $val;
        } else {
            return 0;
        }
    }

    public function updatePaymentTerm($payment_term_id,$data = [])
    {
        $condition = array(
            'payment_term_id' => $payment_term_id,
            'title' => $data['title'],
        );
        $update_data = array(   
            'is_exist' => 2,//already existing PaymentTerm in db
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    public function updatePaymentTermDetails($title)
    {
        $condition = array(
            'title' => $title,
            'is_exist' => 3,
            'status' => 0,
            'removed' => 0,
        );
        $update_data = array(   
            'is_exist' => 4,//newly existing PaymentTerm in db
            'status' => 2,
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return $result;
        }
    } 

    public function cancelUpload()
    {
        $this->cdb->transStart();
    
        $builder = $this->cdb->table('payment_term');
        $builder->whereIn('status', array('0','2'));
        $builder->whereIn('is_exist', array('3','4'));

        $result = $builder->delete(); 
        
        $condition = array(   
            'is_exist' => 2,
            'status' => 1,
        );
        
        $update_data = array(   
            'is_exist' =>0
        );
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    } 

    public function updateUploadPaymentTerm()
    {
        $condition = array(   
            'is_exist' => 3,
            'status' => 0,
        );
        $update_data = array(   
            'is_exist' => 0,//already existing PaymentTerm in db
            'status' => 1,
            'updated_datetime' => date('Y-m-d H:i:s')
        );
        $this->cdb->transStart();
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition);
        $result = $builder->update($update_data); 
        $this->cdb->transComplete();

        //already existing payment term in db
        $condition1 = array(   
            'is_exist' => 2,
            'status' => 1,
            'removed' => 0
        );
        $update_data1 = array(   
            'is_exist' => 0
        );

        $this->cdb->transStart();
        $builder = $this->cdb->table('payment_term');
        $builder->where($condition1);
        $builder->update($update_data1); 
        $this->cdb->transComplete();

        //delete duplicate entries
        $this->cdb->transStart();
        $builder = $this->cdb->table('payment_term');
        $builder->where('status',2);
        $builder->where('is_exist', 4);
        $result = $builder->delete(); 
        $this->cdb->transComplete();

        if ($this->cdb->transStatus() === false) {
            return false;
        } else {
            return true;
        }
    } 

    public function getPaymentTermList($data = [])
    {
        $condition = array();  

        if (isset($data['removed'])) {
            $condition['removed'] = (int)$data['removed'];
        }

        $builder = $this->cdb->table('payment_term');
        $builder->select('*');
        $builder->whereNotIn('is_exist', array('0'));
        $builder->whereNotIn('status', array('1'));
        $builder->where($condition);

        $query = $builder->get();  
        
        if ($query->getNumRows() > 0  ) {
            return $query->getResult();
        } else {
            return 0;
        }
    }


}