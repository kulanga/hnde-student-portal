<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_model extends CI_Model
{
	//allowed login types.
	private $login_types = array('student', 'lecturer', 'admin');
	private $table = 'users';
	
	public function __construct()
	{
		parent::__construct();
	}

	public function insert($data = array()) {
		if (!$this->db->insert($this->table, $data)) {
            return false;
        }
        return $this->db->insert_id();
	}

	public function update($id, $data = array()) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
	}
	

	function login($username, $password)
	{
		$this->db->select('id, user_type_id, username, password, full_name, email');
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->where('password', md5($password));
		$this->db->limit(1);

		$query = $this->db->get();

		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}
}