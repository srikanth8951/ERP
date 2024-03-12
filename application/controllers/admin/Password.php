<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller {

	public function index()
	{
        $data = array(
            'view_name' => "admin/password_change_view",
			'page_name' => "CHANGE PASSWORD",
        );
        
		$this->load->view('admin/account_view',$data);
	}

    public function forgot()
	{
		$this->load->view('admin/password_forgot_view');
	}

	public function new()
	{
		$this->load->view('admin/password_new_view');
	}

   
}
