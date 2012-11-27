<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
Class Userq extends CI_Model {

	public function user_profile($id="") {

            $uid = empty($id) ? $this->session->userdata('uid') : $id;
            if(is_numeric($uid)) {
                $sql = $this->db->query("
                                            SELECT a.username,a.usertype,a.confirmation,a.date,a.date,a.expiration,a.status,b.* 
                                            FROM pre_users a, pre_profile b WHERE
                                            a.id=b.id AND a.id=$uid
                ");
                return $sql->result();
            }
            return FALSE;
	}
        
        public function all_users() {
            
            $this->db->select('pre_users.username,pre_users.usertype,pre_users.expiration,
                               pre_users.status,pre_users.date,pre_users.confirmation,pre_profile.*');
            $this->db->from('pre_users');
            $this->db->where_not_in('pre_profile.id',  array($this->session->userdata('uid')));
            $this->db->join('pre_profile', 'pre_users.id = pre_profile.id');

            $query = $this->db->get();
            
            return $query->result();
        }
        
        public function usernames() {
            
            $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
            
            if ($this->cache->apc->is_supported()) {
            
                if ( ! $usernames = $this->cache->get('usernames'))
                {
                    $this->db->select('username');
                    $sql = $this->db->get("pre_users");
                    $users = $sql->result();

                    $this->cache->save('usernames', $users, 500);
                }
                $usernames = $this->cache->get('usernames');
            } else {
                    $this->db->select('username');
                    $sql = $this->db->get("pre_users");
                    $usernames = $sql->result();
            }
            return $usernames;
        }
        
        public function get_username($id) {
            if(is_numeric($id) && !empty($id)) {
                $this->db->select("username");
                $sql = $this->db->get_where("pre_users",array("id"=>$id));
                
                return $sql->result();
            }
        }
        
        public function get_administrators($return_type="object") {
            $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
            
            if ($this->cache->apc->is_supported()) {
            
                if ( ! $administrators = $this->cache->get('administrators'))
                {
                    $this->db->select('pre_users.username,pre_users.usertype,pre_users.expiration,
                                        pre_users.status,pre_users.date,pre_users.confirmation,pre_profile.*');
                    $this->db->from('pre_users');
                    $this->db->where('pre_users.usertype','admin');
                    $this->db->join('pre_profile', 'pre_users.id = pre_profile.id');
                    $sql = $this->db->get();
                    if($return_type === 'object') {
                        $administrators = $sql->result();
                    } else {
                        $administrators = $sql->result_array();
                    }
                    $this->cache->save('administrators', $administrators, 1000);
                }
                $administrators = $this->cache->get('administrators');
            } else {
                    $this->db->select('pre_users.username,pre_users.usertype,pre_users.expiration,
                                        pre_users.status,pre_users.date,pre_users.confirmation,pre_profile.*');
                    $this->db->from('pre_users');
                    $this->db->where('pre_users.usertype','admin');
                    $this->db->join('pre_profile', 'pre_users.id = pre_profile.id');
                    $sql = $this->db->get();
                    
                    if($return_type === 'object') {
                        $administrators = $sql->result();
                    } else {
                        $administrators = $sql->result_array();
                    }
            }
            return $administrators;
        }
}