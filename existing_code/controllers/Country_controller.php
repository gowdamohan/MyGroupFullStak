<?php

class Country_controller extends CI_Controller {
               
	function __construct()
	{
  parent::__construct();
    $this->load->model('country_model');		     
   }	
    
    public function continent(){
      $data['continent'] = $this->country_model->get_continet();
      $data['main_content']    = 'admin/country/continent';
      $this->load->view('admin/inc/template', $data);
    }

    public function insert_continent(){
      $result = $this->country_model->insert_continent();
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/continent');
    }

    public function update_order(){
      $cateId = $_POST['cateId'];
      $order_wise = $_POST['order_wise'];
      $table = $_POST['table'];
      $result = $this->country_model->update_order($cateId, $order_wise, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/continent');
    }

    public function update_status(){
      $cId = $_POST['cId'];
      $mode = $_POST['mode'];
      $table = $_POST['table'];
      $result = $this->country_model->update_status($cId, $mode, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/continent');
    }

    public function edit_continent($id){
      $data['continent'] = $this->country_model->get_continet();
      $data['edit_continent'] = $this->country_model->edit_continent_by_id($id);
      $data['main_content']    = 'admin/country/continent';
      $this->load->view('admin/inc/template', $data);
    }

    public function update_continent($id){
      $result = $this->country_model->update_continent($id);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/continent');
    }

    public function delete_continent($id){
      $table = 'continent_tbl';
      $result = $this->country_model->delete_country_list($id, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully Deleted');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/continent');
    }
    public function upload($filename = '', $upload_path){
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx';
        $config['remove_spaces'] = true;
        $config['overwrite'] = false;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($filename)) {
            $error = array('status' => 'error', 'data' => $this->upload->display_errors());
            $this -> session -> set_flashdata('flashError', 'Failed to upload - ' . $filename);
            return $error;
        } else {
            $image = $this->upload->data();
            $success = array('status' => 'success', 'data' => $image);
            return $success;
        }
    }

    // Country
    public function country(){
      $data['continent'] = $this->country_model->get_continet();
      $data['country'] = $this->country_model->get_country_list();
      $data['main_content']    = 'admin/country/country';
      $this->load->view('admin/inc/template', $data);
    }

    public function insert_country(){

      $creDir = 'uploads/country_flag/';
      if (!is_dir($creDir)) {
          mkdir($creDir, 0777, TRUE);
      }
      if ($_FILES['country_flag']['name'] != "") {
              $ret_val = $this->upload('country_flag', $creDir);
          if ($ret_val['status'] == 'success') {
              $file_data = $ret_val['data'];
          }else{
              $file_data = $ret_val['data'];
          }
      }else{
          $file_data="";
      }
      $result = $this->country_model->insert_country($file_data);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/country');
    }

    public function update_order_country(){
      $cateId = $_POST['cateId'];
      $order_wise = $_POST['order_wise'];
      $table = $_POST['table'];
      $result = $this->country_model->update_order($cateId, $order_wise, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/country');
    }

    public function update_status_country(){
      $cId = $_POST['cId'];
      $mode = $_POST['mode'];
      $table = $_POST['table'];
      $result = $this->country_model->update_status($cId, $mode, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/country');
    }

    public function delete_country($id){
      $table = 'country_tbl';
      $result = $this->country_model->delete_country_list($id, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully Deleted');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/country');
    }

    public function edit_country($id){
      $data['continent'] = $this->country_model->get_continet();
      $data['country'] = $this->country_model->get_country_list();
      $data['edit_country'] = $this->country_model->edit_country_by_id($id);
      $data['main_content']    = 'admin/country/country';
      $this->load->view('admin/inc/template', $data);
    }

    public function update_country($id){
      $this->db->where('id',$id);
      $file = $this->db->get('country_tbl')->row();

      $creDir = 'uploads/country_flag/';
      if (!is_dir($creDir)) {
          mkdir($creDir, 0777, TRUE);
      }
      if ($_FILES['country_flag']['name'] != "") {
              $filename = $file->country_flag; 
              if (file_exists($filename)){
                unlink($filename);
              }
              $ret_val = $this->upload('country_flag', $creDir);
          if ($ret_val['status'] == 'success') {
              $file_data = $ret_val['data'];
          }else{
              $file_data = $ret_val['data'];
          }
      }else{
          $file_data="";
      }

      $result = $this->country_model->update_country($file_data, $id);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully updated');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/country');
    }

    // State

    public function state(){
      $data['continent'] = $this->country_model->get_continet();
      $data['country'] = $this->country_model->get_country_list();
      $data['state'] = $this->country_model->get_all_state();
      $data['main_content']    = 'admin/country/state';
      $this->load->view('admin/inc/template', $data);
    }

    public function get_country_by_conId(){
      $conId=$this->input->post('conId');
      $result=$this->country_model->get_country_by_conId($conId);
      echo json_encode($result);
    }

    public function insert_state(){
      $result = $this->country_model->insert_state();
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/state');
    }

    public function update_order_state(){
      $cateId = $_POST['cateId'];
      $order_wise = $_POST['order_wise'];
      $table = $_POST['table'];
      $result = $this->country_model->update_order($cateId, $order_wise, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/state');
    }

    public function update_status_state(){
      $cId = $_POST['cId'];
      $mode = $_POST['mode'];
      $table = $_POST['table'];
      $result = $this->country_model->update_status($cId, $mode, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/state');
    }

    public function edit_state($id){
      $data['continent'] = $this->country_model->get_continet();
      $data['country'] = $this->country_model->get_country_list();
      $data['state'] = $this->country_model->get_all_state();
      $data['edit_state'] = $this->country_model->edit_state_by_id($id);
      $data['main_content']    = 'admin/country/state';
      $this->load->view('admin/inc/template', $data);
    }
    
    public function delete_state($id){
      $table = 'state_tbl';
      $result = $this->country_model->delete_country_list($id, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully Deleted');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/state');
    }

    public function update_state($id){
      $result = $this->country_model->update_state_byId($id);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully updated');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/state');
    }


    // District


    public function district(){
      $data['continent'] = $this->country_model->get_continet();
      $data['country'] = $this->country_model->get_country_list();
      $data['state'] = $this->country_model->get_all_state();
      $data['district'] = $this->country_model->get_district_details();
      $data['main_content']    = 'admin/country/district';
      $this->load->view('admin/inc/template', $data);
    }
 
    public function get_state_by_countryId(){
      $countryId=$this->input->post('countryId');
      $result=$this->country_model->get_state_by_countryId($countryId);
      echo json_encode($result);
    }

     public function get_state_by_district(){
      $state=$this->input->post('state');
      $result=$this->country_model->get_state_by_districtby_id($state);
      echo json_encode($result);
    }
    
    public function insert_district(){
      $result = $this->country_model->insert_district();
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/district');
    }

    public function update_order_district(){
      $cateId = $_POST['cateId'];
      $order_wise = $_POST['order_wise'];
      $table = $_POST['table'];
      $result = $this->country_model->update_order($cateId, $order_wise, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('admin/country/country_controller/district');
    }

    public function update_status_district(){
      $cId = $_POST['cId'];
      $mode = $_POST['mode'];
      $table = $_POST['table'];
      $result = $this->country_model->update_status($cId, $mode, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully added');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/district');
    }

    public function edit_district($id){
      $data['continent'] = $this->country_model->get_continet();
      $data['country'] = $this->country_model->get_country_list();
      $data['state'] = $this->country_model->get_all_state();
      $data['district'] = $this->country_model->get_district_details();
      $data['edit_district'] = $this->country_model->edit_district_by_id($id);
      $data['main_content']    = 'admin/country/district';
      $this->load->view('admin/inc/template', $data);
    }
    
    public function delete_district($id){
      $table = 'district_tbl';
      $result = $this->country_model->delete_country_list($id, $table);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully Deleted');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/district');
    }

    public function update_district($id){
      $result = $this->country_model->update_district_byId($id);
      if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Successfully updated');
      }else{
        $this->session->set_flashdata('flashError', 'Something went wrong.');
      }
      redirect('country_controller/district');
    }



     public function countrywise()
      {
        $country=$this->input->post('country');
        $result=$this->country_model->get_countrywise($country);
        echo json_encode($result);
      }
      public function statewise()
      {
        $state=$this->input->post('state');
        $result=$this->country_model->get_statewise($state);
        echo json_encode($result);
      }
      public function citywise()
      {
        $district=$this->input->post('district');
        $result=$this->country_model->get_citywise($district);
        echo json_encode($result);
      }
      public function talukwise()
      {
        $taluk=$this->input->post('taluk');
        $result=$this->country_model->get_talukwise($taluk);
        echo json_encode($result);
      }

}
?>