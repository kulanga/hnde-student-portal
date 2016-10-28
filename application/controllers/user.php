<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
	public $layout_view = 'layout/default';

	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('user_id')) {
			redirect('/login');
		}
	}

	public function index()
	{	
		if($this->session->userdata('user_type_id') == 1) { //admin
			redirect('/admin/course');

		} elseif($this->session->userdata('user_type_id') == 2) { //Staff
			redirect('/staff/my-timetable');

		} elseif($this->session->userdata('user_type_id') == 3) { //Student
			redirect('/student/timetable');

		} else {
			die('Access Denied');
		}

		$this->layout->view('/user/index');
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('user');
	}

}