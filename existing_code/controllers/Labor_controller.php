<?php

class Labor_controller extends CI_Controller {
               
	function __construct(){
 		parent::__construct();	
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
        $this->load->model('labor_model');
        $this->load->library('filemanager');
        $this->load->model('admin_model');
	}

    public function category(){
        $data['category'] = $this->labor_model->get_category_list();
        $data['main_content'] = 'admin/labor/category';
        $this->load->view('admin/inc/template_labor', $data); 
    }

    public function insert_categroy(){
        $result = $this->labor_model->insert_category();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category');
    }

    public function edit_category($id){
        $data['edit_category'] = $this->labor_model->get_category_by_id($id);
        $data['category'] = $this->labor_model->get_category_list();
        $data['main_content'] = 'admin/labor/category';
        $this->load->view('admin/inc/template_labor', $data); 
    }
    public function update_categroy($id){
        $result = $this->labor_model->update_categorybyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category');
    }

    public function delete_category($id){
        $result = $this->labor_model->delete_categorybyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category');
    }

    public function labor_details(){
       
        $data['labor'] = $this->labor_model->get_labor_details();
        $data['main_content'] = 'admin/labor/labor_details';
        $this->load->view('admin/inc/template_labor', $data);   
    }

    public function add_labor(){
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['category'] = $this->labor_model->get_category_list();
        $data['category1'] = $this->labor_model->get_category_list1();
        $data['category2'] = $this->labor_model->get_category_list2();
        $data['contractor'] = $this->labor_model->get_contractor_list1();
        $data['main_content'] = 'admin/labor/labor_add';
        $this->load->view('admin/inc/template_labor', $data);   
    }

    public function insert_labor_details(){
        $result = $this->labor_model->insert_labor_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/labor_details');
    }

    public function edit_labor($id){
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['edit_labor'] = $this->labor_model->get_labor_detailsbyid($id);
        $data['category'] = $this->labor_model->get_category_list();
        $data['category1'] = $this->labor_model->get_category_list1();
        $data['category2'] = $this->labor_model->get_category_list2();
        $data['contractor'] = $this->labor_model->get_contractor_list1();
        $data['main_content'] = 'admin/labor/labor_edit';
        $this->load->view('admin/inc/template_labor', $data);   
    }

    public function update_labor_details($id){
        $result = $this->labor_model->update_labor_details($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updte");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/labor_details');
    }


    // Category1
    public function category1(){
        $data['category1'] = $this->labor_model->get_category_list1();
        $data['contractor'] = $this->labor_model->get_contractor_list1();
        $data['main_content'] = 'admin/labor/category1';
        $this->load->view('admin/inc/template_labor', $data); 
    }

    public function insert_categroy1(){
        $result = $this->labor_model->insert_category1();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category1');
    }

    public function edit_category1($id){
        $data['edit_category1'] = $this->labor_model->get_category_by_id1($id);
        $data['category1'] = $this->labor_model->get_category_list1();
        $data['contractor'] = $this->labor_model->get_contractor_list1();
        $data['main_content'] = 'admin/labor/category1';
        $this->load->view('admin/inc/template_labor', $data); 
    }
    public function update_categroy1($id){
        $result = $this->labor_model->update_categorybyId1($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category1');
    }

    public function delete_category1($id){
        $result = $this->labor_model->delete_categorybyId1($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category1');
    }

    // Category2
    public function category2(){
        $data['category2'] = $this->labor_model->get_category_list2();
        $data['main_content'] = 'admin/labor/category2';
        $this->load->view('admin/inc/template_labor', $data); 
    }

    public function insert_categroy2(){
        $result = $this->labor_model->insert_category2();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category2');
    }

    public function edit_category2($id){
        $data['edit_category2'] = $this->labor_model->get_category_by_id2($id);
        $data['category2'] = $this->labor_model->get_category_list2();
        $data['main_content'] = 'admin/labor/category2';
        $this->load->view('admin/inc/template_labor', $data); 
    }
    public function update_categroy2($id){
        $result = $this->labor_model->update_categorybyId2($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category2');
    }

    public function delete_category2($id){
        $result = $this->labor_model->delete_categorybyId2($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/category2');
    }

    public function labor_details_seperate(){
        $data['labor'] = $this->labor_model->get_labor_details();
        $data['main_content'] = 'admin/labor/labor_details_view';
        $this->load->view('admin/inc/template_labor', $data); 
    }

    public function labor_view_add($id){
        $data['laborView'] = $this->labor_model->get_labor_detailsviewbyId($id);
        $data['main_content'] = 'admin/labor/labor_details_viewbyId';
        $this->load->view('admin/inc/template_labor', $data); 
    }

    public function labor_create_login(){
        $data['laborlogin']= $this->labor_model->get_labor_login_details();
        $data['main_content'] = 'admin/labor/labor_create_login';
        $this->load->view('admin/inc/template_labor', $data);  
    }

    public function labor_login_insert(){
        $result = $this->labor_model->labor_login_insert();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/labor_create_login');
    }

    public function labor_permission_view($id){
        $data['id']= $id;
        $laborlogin= $this->labor_model->get_labor_login_detailsbyId($id);
        $data['accounts'] = json_decode($laborlogin->account_details);
        $data['main_content'] = 'admin/labor/labor_permission_login';
        $this->load->view('admin/inc/template_labor', $data);  
    }

    public function labor_permission_insert($id){
        $result = $this->labor_model->labor_permission_insert($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/labor_permission_view/'.$id);
    }

    public function my_labor_dashboard(){
        $data['main_content']    = 'admin/labor/labor_dashboard';
        $this->load->view('admin/inc/template_labor',$data);
    }

    // Contractor
    public function contractor(){
        $data['contractor'] = $this->labor_model->get_contractor_list1();
        $data['main_content'] = 'admin/labor/contractor';
        $this->load->view('admin/inc/template_labor', $data); 
    }

    public function insert_contractor(){
        $result = $this->labor_model->insert_contractor();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/contractor');
    }

    public function edit_contractor($id){
        $data['edit_contractor'] = $this->labor_model->get_contractor_by_id1($id);
        $data['contractor'] = $this->labor_model->get_contractor_list1();
        $data['main_content'] = 'admin/labor/contractor';
        $this->load->view('admin/inc/template_labor', $data); 
    }
    public function update_contractor($id){
        $result = $this->labor_model->update_contractorbyId1($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/contractor');
    }

    public function delete_contractor($id){
        $result = $this->labor_model->delete_contractorbyId1($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('labor_controller/contractor');
    }


}