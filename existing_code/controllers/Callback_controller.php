<?php

class Callback_controller extends CI_Controller {
               
	function __construct(){
    parent::__construct();
    $this->load->model('Mygroup_model');		     
  }

  public function get_data_into_table(){
    
    $data = (array) json_decode(file_get_contents('php://input'), TRUE);
    $table = $data['table_name'];
    $groupName = $data['group_name'];
    $result = $this->Mygroup_model->get_data_content($table, $groupName);
    $success = [];
    if ($result) {
      $success = ['status' => '1', 'message' => 'Successull', 'data' => $result];
    }else{ 
      $success = ['status' => '0', 'message' => 'error', 'data' =>[]];
    }    
    echo json_encode($success);
  }

  public function get_top_icon_datato_mygroup(){
    $result = $this->Mygroup_model->get_data_top_icon();
    $success = [];
    if ($result) {
      $success = ['status' => '1', 'message' => 'Successull', 'data' => $result];
    }else{ 
      $success = ['status' => '0', 'message' => 'error', 'data' =>[]];
    }    
    echo json_encode($success);
  }

  public function get_users_details_mygroup(){
    $data = (array) json_decode(file_get_contents('php://input'), TRUE);
    $number = $data['number'];
    $result = $this->Mygroup_model->check_user_name_with_mygroup($number);
    $success = [];
    if ($result) {
      $success = ['status' => '1', 'message' => 'Successull', 'data' => $result];
    }else{ 
      $success = ['status' => '0', 'message' => 'error', 'data' =>[]];
    }    
    echo json_encode($success);
  }

  public function insert_users_details_mygroup(){
    $data = (array) json_decode(file_get_contents('php://input'), TRUE);
    $result = $this->Mygroup_model->insert_user_registration_mygroup($data);
    $success = [];
    if ($result) {
      $success = ['status' => '1', 'message' => 'Successull', 'data' => $result];
    }else{ 
      $success = ['status' => '0', 'message' => 'error', 'data' =>[]];
    }    
    echo json_encode($success);
  }

}
?>