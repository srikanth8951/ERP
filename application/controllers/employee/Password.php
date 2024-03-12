<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Password extends CI_Controller {


    public function forgot()
	{
		$this->load->view('employee/password_forgot_view');
	}

	public function new()
	{
		$this->load->view('employee/password_new_view');
	}

   
}
