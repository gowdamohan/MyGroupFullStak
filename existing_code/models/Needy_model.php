<?php

class Needy_model extends CI_Model{
  
  	public function __construct(){
    	parent::__construct();
  	}

 	public function get_needy_category_by_name($needy_type){
        return $this->db->select('*')
        ->from('needy_category')
        ->where('needy_type',$needy_type)
        ->get()->result();
    }

    public function get_needy_client_list_id($needy_type_id){
        $result =  $this->db->select('*')
        ->from('needy_client_services_details')
        ->where('needy_category',$needy_type_id)
        ->get()->result();
        $needy_ids = [];
        foreach ($result as $key => $res) {
          array_push($needy_ids, $res->id);
        }    
        return $needy_ids;
    }

    public function get_needy_client_list_details($client_neeyd_ids){
        return $this->db->select('*')
        ->from('needy_client_services_details')
        ->where_in('id',$client_neeyd_ids)
        ->order_by('id','desc')
        ->get()->result();
    }

    public function get_needy_client_view_list_id($needy_client_id){
        $master =  $this->db->select('*')
        ->from('needy_client_services_details')
        ->where('id',$needy_client_id)
        ->get()->row();

        $master->slave = $this->db->select('*')
        ->from('needy_client_services_time_details')
        ->where('needy_client_services_id',$needy_client_id)
        ->get()->result();

        $master->review = $this->db->select('ucsr.*, u.first_name')
        ->from('needy_client_services_review ucsr')
        ->join('users u','ucsr.user_id=u.id')
        ->where('needy_client_services_id',$needy_client_id)
        ->get()->result();

        return $master;
    }
}