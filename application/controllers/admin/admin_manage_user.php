<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_manage_user extends MY_Controller {

	public function __construct() {
		parent::__construct();
        $this->set_topnav('manage_user');

        $this->load->library('form_validation');
	}

    public function index() {
        $this->load->model('staff_model');
        $data = array();
        $data['list'] = $this->staff_model->get_stffs();
        $this->layout->view('/admin/manage_user/index', $data);
    }


	//create/edit accedmic user.
	public function create_staff() {

        $this->load->model('staff_designation_model');
        $this->load->model('user_model');
        $this->load->model('staff_model');

        $data = array();
        $data['designations'] = $this->staff_designation_model->get_disgnations_list();

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email|matches[confirm_email]|is_unique[users.email]');
            $this->form_validation->set_rules('confirm_email', 'Confirm Email', 'trim|required|xss_clean|');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required|xss_clean|numeric');
            $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean|numeric');

            if ($this->form_validation->run()) {
                $save_data['full_name'] = ucfirst($this->input->post('full_name'));
                $save_data['email'] = $this->input->post('email');
                $save_data['username'] = $this->input->post('email');
                $save_data['mobile_no'] = $this->input->post('mobile_no');
                $save_data['user_type_id'] = 2;
                $user_id = $this->user_model->insert($save_data);

                $staff_data = array('staff_designation_id' => $this->input->post('designation'), 'user_id' => $user_id);
                $user_id = $this->staff_model->insert($staff_data);

                redirect('/admin/manage_user/edit/' .  $user_id);
            }
        }

        $this->layout->view('/admin/manage_user/create_staff', $data);
	}

    public function edit_staff($user_id) {
        $this->load->model('staff_model');
        $this->load->model('staff_designation_model');

        $data = array();
        $data['designations'] = $this->staff_designation_model->get_disgnations_list();
        $data['staff'] =  $this->staff_model->get_staff_profile($user_id);
        $this->layout->view('/admin/manage_user/edit_staff', $data);
    }
}