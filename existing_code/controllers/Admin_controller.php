<?php

class Admin_controller extends CI_Controller {
               
	function __construct(){
 		parent::__construct();	
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
        $this->load->model('admin_model');
        $this->load->library('filemanager');
        $this->load->model('home_model'); 
	}	

	public function index(){
        $userid = $this->ion_auth->user()->row()->id;
        $groups = $this->ion_auth->get_users_groups($userid)->row()->name;
        switch ($groups) {
            case 'admin':
            case 'groups':
                $this->_default_dashboard();
                break;
            case 'client':
            case 'client_god':
                $this->_client_dashboard();
                break;
            case 'corporate':
             $this->_franchise_head_dashboard();
                break;
            case 'head_office':
            case 'regional':
            case 'branch':
                $this->_franchise_dashboard();
                break;
             case 'labor':
                $this->_labor_dashboard();
                break;
            default:
              
                break;
        }
    }

    public function _default_dashboard(){
        $data['main_content']    = 'admin/admin_dashboard';
        $this->load->view('admin/inc/template',$data);
    }

    public function _labor_dashboard(){
        $data['main_content']    = 'admin/labor/labor_dashboard';
        $this->load->view('admin/inc/template_labor',$data);
    }
    public function _client_dashboard(){
        $data['check_user_active'] =$this->admin_model->check_user_activation();
        $data['main_content']    = 'admin/admin_dashboard';
        $this->load->view('admin/inc/template_client',$data);
    }

    public function _franchise_head_dashboard(){
        $data['main_content']    = 'admin/corporate_dashboard';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function change_password_head_dashboard(){
        $data['main_content'] = 'admin/franchise/change_password';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function change_password_branches_dashboard(){
        $data['main_content']    = 'admin/franchise/change_password';
        $this->load->view('admin/inc/template_franchise',$data);
    }

    public function _franchise_dashboard(){
        $data['franchise_ads'] = $this->admin_model->get_franchise_offer_ads();
        $data['main_content']    = 'admin/head_dashboard';
        $this->load->view('admin/inc/template_franchise',$data);
    }

    public function logo(){
	  	$data['logo'] = $this->admin_model->get_logo_image();
        $data['main_content']    = 'admin/pages/logo';
        $this->load->view('admin/inc/template', $data);
    }
    
    public function upload($filename = '', $upload_path){
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = '*';
        $config['remove_spaces'] = true;
        $config['overwrite'] = true;
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

 	public function submit_logo(){
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['logo']['name'] != "") {
                $ret_val = $this->upload('logo', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        $result = $this->admin_model->insert_logo($file_data);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Logo Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/logo');
    }

    public function update_logo($id){
        $this->db->where('id',$id);
        $file = $this->db->get('logo')->row();

        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['logo']['name'] != "") {
                $filename = $file->logo; 
                if (file_exists($filename)){
                  unlink($filename);
                }
                $ret_val = $this->upload('logo', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        $result = $this->admin_model->update_logobyId($file_data, $id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Logo Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/logo');
    }

    public function delete_logo($id){
        $result = $this->admin_model->delete_logo($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Delete Logo Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/logo');
    }

    public function edit_logo($id){
        $data['logo'] = $this->admin_model->get_logo_image();
        $data['edit_logo'] = $this->admin_model->get_logo_imagebyId($id);
        $data['main_content']    = 'admin/pages/logo';
        $this->load->view('admin/inc/template', $data);
    }

    public function header_slider(){
        $data['sliders'] = $this->admin_model->get_header_slider_image();
        // echo "<pre>"; print_r($data['sliders']); die();
        $data['main_content']    = 'admin/pages/header_slider';
        $this->load->view('admin/inc/template', $data);
    }

    public function edit_header_slider($id){
        $data['sliders'] = $this->admin_model->get_header_slider_image();
        $data['edit_header_slider'] = $this->admin_model->edit_header_slider_by_id($id);
        $data['main_content']    = 'admin/pages/header_slider';
        $this->load->view('admin/inc/template', $data);
    }

    public function submit_header_slider(){
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['header_slider']['name'] != "") {
                $ret_val = $this->upload('header_slider', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        $result = $this->admin_model->insert_header_slider($file_data);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Slider Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/header_slider');
    }

    public function update_header_slider($id) {
        $this->db->where('id',$id);
        $file = $this->db->get('header_slider')->row();

        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['header_slider']['name'] != "") {

            $filename = $file->image; 
            if (file_exists($filename)){
              unlink($filename);
            }
                $ret_val = $this->upload('header_slider', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        $result = $this->admin_model->update_header_slider($file_data, $id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Slider Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/header_slider');
    }
    
    public function delete_header_slider($id){
        $result = $this->admin_model->delete_header_slider($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Delete Slider Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/header_slider');
    }

    public function right_slider(){
        $data['rightSliders'] = $this->admin_model->get_right_slider_image();
        $data['main_content']    = 'admin/pages/right_slider';
        $this->load->view('admin/inc/template', $data);
    }

    public function edit_right_slider($id){
        $data['rightSliders'] = $this->admin_model->get_right_slider_image();
        $data['edit_right_slider'] = $this->admin_model->get_right_slider_by_id($id);
        $data['main_content']    = 'admin/pages/right_slider';
        $this->load->view('admin/inc/template', $data);
    }
    public function submit_right_slider(){
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['right_slider']['name'] != "") {
                $ret_val = $this->upload('right_slider', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        $result = $this->admin_model->insert_right_slider($file_data);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Slider Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/right_slider');
    }

     public function delete_right_slider($id){
        $result = $this->admin_model->delete_right_slider($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Delete Slider Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/right_slider');
    }

    public function update_right_slider($id){
        $this->db->where('id',$id);
        $file = $this->db->get('right_slider')->row();
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['right_slider']['name'] != "") {
                $filename = $file->image; 
                if (file_exists($filename)){
                  unlink($filename);
                }
                $ret_val = $this->upload('right_slider', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        $result = $this->admin_model->update_right_slider($file_data, $id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Slider Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/right_slider');
    }


    public function add_mygroup_sub_logo(){
        $data['sub_logo'] = $this->admin_model->get_sub_logo_details();
        $data['main_content']    = 'admin/pages/add_new';
        $this->load->view('admin/inc/template', $data);
    }

    public function submit_sub_logo(){
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['icon']['name'] != "") {
                $ret_val = $this->upload('icon', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        if ($_FILES['logo']['name'] != "") {
                $ret_val = $this->upload('logo', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data1 = $ret_val['data'];
            }else{
                $file_data1 = $ret_val['data'];
            }
        }else{
            $file_data1 ="";
        }

        $result = $this->admin_model->insert_sub_logo($file_data, $file_data1);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/add_mygroup_sub_logo');
    }

    public function delete_sub_logo($id){
        $result = $this->admin_model->delete_sub_logo($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Delete Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/add_mygroup_sub_logo');
    }

    public function edit_sub_logo($id){
        $data['sub_logo'] = $this->admin_model->get_sub_logo_details();
        $data['edit_sub_logo'] = $this->admin_model->get_sub_logo_detailsbyId($id);
        $data['main_content']    = 'admin/pages/add_new';
        $this->load->view('admin/inc/template', $data);
    }

    public function update_sub_logo($id){
        $this->db->where('id',$id);
        $file = $this->db->get('sub_logo')->row();

        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['icon']['name'] != "") {
                $filename = $file->icon; 
                if (file_exists($filename)){
                  unlink($filename);
                }
                $ret_val = $this->upload('icon', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data="";
        }

        if ($_FILES['logo']['name'] != "") {
                $logofile = $file->logo; 
                if (file_exists($logofile)){
                  unlink($logofile);
                }
                $ret_val = $this->upload('logo', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data1 = $ret_val['data'];
            }else{
                $file_data1 = $ret_val['data'];
            }
        }else{
            $file_data1 ="";
        }

        $result = $this->admin_model->update_sub_logo($file_data, $file_data1, $id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/add_mygroup_sub_logo');
    }

    public function add_slide_sub_logo($id){
        $data['id'] = $id;
        $data['sub_logo_slides'] = $this->admin_model->get_sub_logo_slides_details($id);
        $data['main_content']    = 'admin/pages/sub_logo_slides';
        $this->load->view('admin/inc/template', $data);
    }


    public function delete_sub_logo_slides($id, $subId){
        $result = $this->admin_model->delete_sub_logo_slides_by_id($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Delete Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/add_slide_sub_logo/'.$subId);
    }

    public function submit_sub_logo_slide($id){
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if ($_FILES['sub_logo_slides']['name'] != "") {
                $ret_val = $this->upload('sub_logo_slides', $creDir);
            if ($ret_val['status'] == 'success') {
                $file_data = $ret_val['data'];
            }else{
                $file_data = $ret_val['data'];
            }
        }else{
            $file_data ="";
        }

        $result = $this->admin_model->insert_sub_logo_slides($id, $file_data);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('admin_controller/add_slide_sub_logo/'.$id);
    }

    public function backup(){
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('zip');
        $this->load->dbutil();
        $db_format=array('format'=>'zip','filename'=>'my_group.sql');
        $backup=& $this->dbutil->backup($db_format);
        $dbname='backup-on-'.date('d-m-y H:i').'.zip';
        force_download($dbname,$backup);
    }

    public function group(){
        $data['group'] = $this->admin_model->group_details();
        $data['main_content']    = 'admin/pages/group';
        $this->load->view('admin/inc/template', $data);
    }

    public function create(){
        $data['create'] = $this->admin_model->create_group();
        // echo "<pre>"; print_r($data['create']); die();
        $data['main_content']    = 'admin/pages/create';
        $this->load->view('admin/inc/template', $data);
    }

    public function advertise(){
        $data['advertise'] = $this->admin_model->advertise_group();
        $data['main_content']    = 'admin/pages/advertise';
        $this->load->view('admin/inc/template', $data); 
    }

    public function upload_group(){
        $group = $this->db->get('group')->row();
        $creDir = 'uploads/group/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if (!empty($_FILES['icon_photo'])) {
            if ($_FILES['icon_photo']['name'] != "") {

                $filename = $group->icon; 
                if (file_exists($filename)){
                  unlink($filename);
                }

                $ret_val = $this->upload('icon_photo', $creDir);
                if ($ret_val['status'] == 'success') {
                    $icon = $ret_val['data'];
                }else{
                    $icon = $ret_val['data'];
                }
            }else{
                $icon ="";
            }
        }else{
            $icon ="";
        }
        
        if (!empty($_FILES['logo_photo'])) {
            if ($_FILES['logo_photo']['name'] != "") {
                $filename2 = $group->logo; 
                if (file_exists($filename2)){
                  unlink($filename2);
                }
                    $ret_val = $this->upload('logo_photo', $creDir);
                if ($ret_val['status'] == 'success') {
                    $logo = $ret_val['data'];
                }else{
                    $logo = $ret_val['data'];
                }
            }else{
                $logo ="";
            }  
        }else{
            $logo ="";
        }

        
        if (!empty($_FILES['name_photo'])) {
            if ($_FILES['name_photo']['name'] != "") {
                $filename3 = $group->name_image; 
                if (file_exists($filename3)){
                  unlink($filename3);
                }
                $ret_val = $this->upload('name_photo', $creDir);
                if ($ret_val['status'] == 'success') {
                    $namephoto = $ret_val['data'];
                }else{
                    $namephoto = $ret_val['data'];
                }
            }else{
                $namephoto ="";
            }
        }else{
            $namephoto ="";
        }
        
        if (!empty($_FILES['header_ads1'])) {
            if ($_FILES['header_ads1']['name'] != "") {
                $filename4 = $group->header_ads1; 
                if (file_exists($filename4)){
                  unlink($filename4);
                }
                $ret_val = $this->upload('header_ads1', $creDir);
                if ($ret_val['status'] == 'success') {
                    $ads1 = $ret_val['data'];
                }else{
                    $ads1 = $ret_val['data'];
                }
            }else{
                $ads1 ="";
            }
        }else{
            $ads1 ="";
        }

        if (!empty($_FILES['header_ads2'])) {
            if ($_FILES['header_ads2']['name'] != "") {
                $filename5 = $group->header_ads2; 
                if (file_exists($filename5)){
                  unlink($filename5);
                }
                $ret_val = $this->upload('header_ads2', $creDir);
                if ($ret_val['status'] == 'success') {
                    $ads2 = $ret_val['data'];
                }else{
                    $ads2 = $ret_val['data'];
                }
            }else{
                $ads2 ="";
            }
        }else{
             $ads2 ="";
        }

        if (!empty($_FILES['header_ads3'])) {
            if ($_FILES['header_ads3']['name'] != "") {
                $filename6 = $group->header_ads3; 
                if (file_exists($filename6)){
                  unlink($filename6);
                }
                $ret_val = $this->upload('header_ads3', $creDir);
                if ($ret_val['status'] == 'success') {
                    $ads3 = $ret_val['data'];
                }else{
                    $ads3 = $ret_val['data'];
                }
            }else{
                $ads3 ="";
            }
        }else{
            $ads3 ="";
        }

        if (!empty($_FILES['side_ads'])) {
            if ($_FILES['side_ads']['name'] != "") {
                $filename7 = $group->side_ads; 
                if (file_exists($filename7)){
                  unlink($filename7);
                }
                $ret_val = $this->upload('side_ads', $creDir);
                if ($ret_val['status'] == 'success') {
                    $sideAds = $ret_val['data'];
                }else{
                    $sideAds = $ret_val['data'];
                }
            }else{
                $sideAds ="";
            }
        }else{
            $sideAds ="";
        }

        if (!empty($_FILES['main_ads'])) {
            if ($_FILES['main_ads']['name'] != "") {
                $filename8 = $group->main_ads; 
                if (file_exists($filename8)){
                  unlink($filename8);
                }
                $ret_val = $this->upload('main_ads', $creDir);
                if ($ret_val['status'] == 'success') {
                    $mainAds = $ret_val['data'];
                }else{
                    $mainAds = $ret_val['data'];
                }
            }else{
                $mainAds ="";
            }
        }else{
            $mainAds ="";
        }

       
        $result = $this->admin_model->insert_upload_group($icon, $logo, $namephoto, $ads1, $ads2, $ads3, $sideAds,$mainAds);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/group');
    }


    public function create_associates(){
        $result = $this->admin_model->insert_group_associates();
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/create');
    }

    public function upload_group_create(){

        $creDir = 'uploads/create/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if (!empty($_FILES['icon_photo'])) {
            if ($_FILES['icon_photo']['name'] != "") {
                $ret_val = $this->upload('icon_photo', $creDir);
                if ($ret_val['status'] == 'success') {
                    $icon = $ret_val['data'];
                }else{
                    $icon = $ret_val['data'];
                }
            }else{
                $icon ="";
            }
        }else{
            $icon ="";
        }
        
        if (!empty($_FILES['logo_photo'])) {
            if ($_FILES['logo_photo']['name'] != "") {
                    $ret_val = $this->upload('logo_photo', $creDir);
                if ($ret_val['status'] == 'success') {
                    $logo = $ret_val['data'];
                }else{
                    $logo = $ret_val['data'];
                }
            }else{
                $logo ="";
            }  
        }else{
            $logo ="";
        }

        
        if (!empty($_FILES['name_photo'])) {
            if ($_FILES['name_photo']['name'] != "") {
                $ret_val = $this->upload('name_photo', $creDir);
                if ($ret_val['status'] == 'success') {
                    $namephoto = $ret_val['data'];
                }else{
                    $namephoto = $ret_val['data'];
                }
            }else{
                $namephoto ="";
            }
        }else{
            $namephoto ="";
        }
        
        if (!empty($_FILES['banner'])) {
            if ($_FILES['banner']['name'] != "") {
                $ret_val = $this->upload('banner', $creDir);
                if ($ret_val['status'] == 'success') {
                    $banner = $ret_val['data'];
                }else{
                    $banner = $ret_val['data'];
                }
            }else{
                $banner ="";
            }
        }else{
            $banner ="";
        }

        $result = $this->admin_model->insert_upload_group_create($icon, $logo, $namephoto, $banner);

        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/create');
    }

    public function delete_group_create($id){
        $result = $this->admin_model->delete_upload_group_create($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Delete Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/create');
    }

    public function upload_group_adertise(){
        $creDir = 'uploads/adertise/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if (!empty($_FILES['ads1'])) {
            if ($_FILES['ads1']['name'] != "") {
                $ret_val = $this->upload('ads1', $creDir);
                if ($ret_val['status'] == 'success') {
                    $ads1 = $ret_val['data'];
                }else{
                    $ads1 = $ret_val['data'];
                }
            }else{
                $ads1 ="";
            }
        }else{
            $ads1 ="";
        }
        
        if (!empty($_FILES['ads2'])) {
            if ($_FILES['ads2']['name'] != "") {
                    $ret_val = $this->upload('ads2', $creDir);
                if ($ret_val['status'] == 'success') {
                    $ads2 = $ret_val['data'];
                }else{
                    $ads2 = $ret_val['data'];
                }
            }else{
                $ads2 ="";
            }  
        }else{
            $ads2 ="";
        }

        
        if (!empty($_FILES['ads3'])) {
            if ($_FILES['ads3']['name'] != "") {
                $ret_val = $this->upload('ads3', $creDir);
                if ($ret_val['status'] == 'success') {
                    $ads3 = $ret_val['data'];
                }else{
                    $ads3 = $ret_val['data'];
                }
            }else{
                $ads3 ="";
            }
        }else{
            $ads3 ="";
        }
        
       
        $result = $this->admin_model->insert_upload_group_adverise($ads1, $ads2, $ads3);

        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/advertise');
    }

    

    public function language(){
        $data['language'] = $this->admin_model->get_language_details();
        $data['main_content']    = 'admin/pages/language';
        $this->load->view('admin/inc/template', $data);
    }

    public function insert_langague(){
        $result = $this->admin_model->insert_langague_details();
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/language');
    }

    public function edit_langauge($id){
        $data['language'] = $this->admin_model->get_language_details();
        $data['edit_language'] = $this->admin_model->get_language_by_id($id);

        $data['main_content']    = 'admin/pages/language';
        $this->load->view('admin/inc/template', $data);
    }

    public function update_langague($id){
        $result = $this->admin_model->update_langague_details($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/language');
    }

    public function delete_language(){
        $result = $this->admin_model->delete_language_by_id($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Deelte Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/language');
    }

    // Education

    public function education(){
        $data['education'] = $this->admin_model->get_education_details();
        $data['main_content']    = 'admin/pages/education';
        $this->load->view('admin/inc/template', $data);
    }

    public function insert_education(){
        $result = $this->admin_model->insert_education_details();
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/education');
    }

    public function edit_education($id){
        $data['education'] = $this->admin_model->get_education_details();
        $data['edit_education'] = $this->admin_model->get_education_by_id($id);

        $data['main_content']    = 'admin/pages/education';
        $this->load->view('admin/inc/template', $data);
    }

    public function update_education($id){
        $result = $this->admin_model->update_education_details($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/education');
    }

    public function delete_education($id){
        $result = $this->admin_model->delete_education_by_id($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Deelte Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/education');
    }

    // profession

    public function profession(){
        $data['profession'] = $this->admin_model->get_profession_details();
        $data['main_content']    = 'admin/pages/profession';
        $this->load->view('admin/inc/template', $data);
    }

    public function insert_profession(){
        $result = $this->admin_model->insert_profession_details();
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/profession');
    }

    public function edit_profession($id){
        $data['profession'] = $this->admin_model->get_profession_details();
        $data['edit_profession'] = $this->admin_model->get_profession_by_id($id);

        $data['main_content']    = 'admin/pages/profession';
        $this->load->view('admin/inc/template', $data);
    }

    public function update_profession($id){
        $result = $this->admin_model->update_profession_details($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/profession');
    }

    public function delete_profession($id){
        $result = $this->admin_model->delete_profession_by_id($id);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Deelte Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/profession');
    }


    public function public_database(){
        $data['public_database'] = $this->admin_model->get_public_database();
        $data['main_content']    = 'admin/pages/public_database';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function profile_view($user_id){
        $data['profile_database'] = $this->admin_model->get_public_database_by_id($user_id);
        // echo "<pre>"; print_r($data); die();
        $data['main_content']    = 'admin/pages/profile_view';
        $this->load->view('admin/inc/template', $data);
    }

    public function about_us(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['about'] = $this->admin_model->get_about_all($group_id);
        $data['main_content']    = 'admin/pages/about_us';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function footer_same_page($pagename){
        $data['pagename']=$pagename;
        $data['data'] = $this->admin_model->get_page_allbytable($pagename);
        // echo "<pre>"; print_r($data['data']);die();
        $data['main_content']    = 'admin/pages/footer_page';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function add_about(){
        $data['main_content']    = 'admin/pages/about_add';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function add_page($pagename){
        $data['pagename']=$pagename;
        $data['main_content']    = 'admin/pages/footer_add';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function upload_about(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['about_title'];
        $summernote = $input['summernote'];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $save_news[] = array(
                    'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'group_id'=> $this->ion_auth->user()->row()->group_id
                );
            }
        }else{
            $save_news[] = array(
                'image' => $img['file_name'],
                'title' => $title,
                'content' => $summernote,
                'group_id'=> $this->ion_auth->user()->row()->group_id
            );
        }
        if(!empty($save_news)) {
            $this->admin_model->save_news($save_news,'about');
        }
        echo 1;
    }

    public function upload_footer_page($pageName){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title ='';
        if (isset( $input['about_title'])) {
            $title = $input['about_title'];
        }

        $summernote ='';
        if (isset( $input['summernote'])) {
            $summernote = $input['summernote'];
        }
        $tag_line ='';
        if (isset($input['tag_line'])) {
            $tag_line = $input['tag_line'];
        }

        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $save_news[] = array(
                    'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'tag_line' => $tag_line,
                    'group_id'=> 0
                );
            }
        }else{
            $save_news[] = array(
                'image' => $img['file_name'],
                'title' => $title,
                'content' => $summernote,
                'tag_line' => $tag_line,
                'group_id'=> 0
            );
        }
        if(!empty($save_news)) {
            $this->admin_model->save_news($save_news,$pageName);
        }
        echo 1;
    }

    public function edit_about_us($id){
        $data['edit_about'] = $this->admin_model->edit_about_us_by_id($id);
        $data['main_content']    = 'admin/pages/about_edit';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function edit_page($id, $pagename){
        $data['pagename']=$pagename;
        $data['edit_page'] = $this->admin_model->edit_page_us_by_id($id, $pagename);
        $data['main_content']    = 'admin/pages/page_edit';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function upload_about_by_id(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['about_title'];
        $summernote = $input['summernote'];
        $exit_fie_url = $input['exit_fie_url'];
        $about_id = $input['about_id'];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $update_about[] = array(
                    'id' => $about_id,
                     'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'group_id'=> $this->ion_auth->user()->row()->group_id
                );
            }
        }else{
            $update_about[] = array(
                'id' => $about_id,
                'image' => $exit_fie_url,
                'title' => $title,
                'content' => $summernote,
                'group_id'=> $this->ion_auth->user()->row()->group_id
            );
        }
        if(!empty($update_about)) {
            $this->admin_model->update_news($update_about,'about');
        }
        echo 1;
    }

     public function upload_page_by_id($pageName){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
      
        $about_id = $input['about_id'];

        $title ='';
        if (isset( $input['about_title'])) {
            $title = $input['about_title'];
        }

        $summernote ='';
        if (isset( $input['summernote'])) {
            $summernote = $input['summernote'];
        }
        $tag_line ='';
        if (isset($input['tag_line'])) {
            $tag_line = $input['tag_line'];
        }

        $exit_fie_url ='';
        if (isset($input['exit_fie_url'])) {
            $exit_fie_url = $input['exit_fie_url'];
        }

        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $update_about[] = array(
                    'id' => $about_id,
                    'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                     'tag_line' => $tag_line,
                    'group_id'=> 0
                );
            }
        }else{
            $update_about[] = array(
                'id' => $about_id,
                'image' => $exit_fie_url,
                'title' => $title,
                'content' => $summernote,
                'tag_line' => $tag_line,
                'group_id'=> 0
            );
        }
        if(!empty($update_about)) {
            $this->admin_model->update_news($update_about,$pageName);
        }
        echo 1;
    }

    public function deleted_about_us($id){
        $this->db->where('id',$id);
        $result =  $this->db->delete('about');
        if ($result) {
            redirect('admin_controller/about_us');
        }
    }

    public function deleted_page($id, $pagename){
        $this->db->where('id',$id);
        $result =  $this->db->delete($pagename);
        if ($result) {
            redirect('admin_controller/footer_same_page/'.$pagename);
        }
    }

    public function s3FileUpload($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'Logo');
        //print_r($uploadResult); die();
        return $uploadResult;
    }

    public function gallery(){
        $gallery_info['image_count'] = 0;
        $group_id = $this->ion_auth->user()->row()->group_id;
        $gallery_info = $this->admin_model->get_all_galleries($group_id);
        $data['gallery_info'] = $gallery_info;
        $data['main_content']    = 'admin/galleries/index';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function create_gallery(){
        $data['main_content']    = 'admin/galleries/create_gallery';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function gallery_created(){
        if (!(isset($_POST['gallery_name']))) {
            redirect('admin_controller/gallery');
        }
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['gallery'] = $this->admin_model->addGallery($group_id);
        redirect('admin_controller/view_gallery/' . $data['gallery']['id']);
    }

    public function view_gallery($gallery_id){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['gallery_info'] = $this->admin_model->get_gallery_info($gallery_id, $group_id);
        $data['image_info'] = $this->admin_model->get_images_info($gallery_id, $group_id);
        $data['main_content'] = 'admin/galleries/view_gallery';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function upload_multiple_images(){
        $files = $_FILES['file_name'];
        $input = $this->input->post();
        $gid = $input['gal_id'];
        $img_desc = $input['desc'];
        $failed = 0;
        $group_id = $this->ion_auth->user()->row()->group_id;
        // echo '<pre>'; print_r($files); die();
        foreach($files['tmp_name'] as $i => $file_name) {
            $file = array(
                'tmp_name' => $file_name,
                'name' => 'img'.$i.'.png'
            );
            $img = $this->s3FileUpload($file, $gid);
            trigger_error(json_encode($img));
            if($img['file_name'] == null || $img['file_name'] == ''){
                $failed++;
            }else {
                $save_images[] = array(
                    'gallery_id' => $gid,
                    'image_name' => $img['file_name'],
                    'image_description' => $img_desc,
                    'group_id'=>$group_id
                );
            }
        }
        if(!empty($save_images)) {
            $this->admin_model->save_gallery_images($save_images);
        }

        echo $failed;
    }


    public function delete_image(){
        $image_id = $_POST['image_id'];
        $result = $this->admin_model->delete_image_db($image_id);
        echo $result;
    }

    public function delete_gallery_by_id(){
        $gId = $_POST['gId'];
        $result = $this->admin_model->delete_album_db($gId);
        echo $result;
    }

    public function copy_rights(){
        $data['copy_rights'] = $this->admin_model->get_copy_right();
        $data['main_content'] = 'admin/pages/copy_rights';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function update_copy_rights(){
        $input = $this->input->post();

        if (!empty($input['copy_right_id'])) {
            $updatedata = array('copy_right'=>$input['copy_right']);
            $this->admin_model->update_data($updatedata, $input['copy_right_id']);
        }else{
            $insertdata = array('copy_right'=>$input['copy_right']);
            $this->admin_model->insert_data($insertdata);
        }
        redirect('admin_controller/copy_rights');
    }

    public function contact_us(){
        $data['contact'] = $this->admin_model->get_contact_details();
        $data['main_content'] = 'admin/pages/contact_us';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function update_contact(){
        
        $result = $this->admin_model->insert_update_contact();
        redirect('admin_controller/contact_us');
    }

    public function update_contact_by_id($id){
        $result = $this->admin_model->update_contact_byId($id);
        redirect('admin_controller/contact_us');
    }

     public function social_link(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['social_link'] = $this->admin_model->get_social_link($group_id);
        $data['main_content']    = 'admin/pages/social_link';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function upload_social_link(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $socialurl = $_POST['socialurl'];
        $uploadId = $_POST['uploadId'];
        echo $this->admin_model->save_social_link($socialurl, $uploadId, $group_id);
    }

    public function tnc(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['tnc'] = $this->admin_model->get_tnc_details($group_id);
        $data['main_content'] = 'admin/pages/tnc';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function pnp(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['pnp'] = $this->admin_model->get_pnp_details($group_id);
        $data['main_content'] = 'admin/pages/pnp';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function pnp_insert(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $result = $this->admin_model->insert_pnp_details($group_id);
        redirect('admin_controller/pnp');
    }

    public function pnp_update($id){
        $result = $this->admin_model->update_pnp_byId($id);
        redirect('admin_controller/pnp');
    }


    public function tnc_insert(){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $result = $this->admin_model->insert_tnc_details($group_id);
        redirect('admin_controller/tnc');
    }

    public function tnc_update($id){
        $result = $this->admin_model->update_tnc_byId($id);
        redirect('admin_controller/tnc');
    }

    public function change_password(){
        $data['main_content'] = 'admin/pages/change_password';
        $this->load->view('admin/inc/template', $data); 
    }

    public function feed_back_users(){
        $data['feebackGroup'] = $this->admin_model->get_feedackGroups();

        $userId = $this->input->post('user_id');
        if (!empty($userId)) {
           $data['user_id'] = $userId;
        }else{
            if (!empty($data['feebackGroup'])) {
                $data['user_id'] = $data['feebackGroup'][0]->user_id;
            }else{
                $data['user_id'] = '';
            }
        }
        $data['user_feedback'] = $this->admin_model->get_in_feedback($data['user_id']);
        $data['logo'] = $this->admin_model->get_logo_image();
        // echo "<pre>"; print_r($data['logo']); die();
        $data['main_content'] = 'admin/pages/feedback';
        $this->load->view('admin/inc/template_head_franchise',$data);
    }

    public function send_user_message(){
        $message = $_POST['message'];
        $userId = $_POST['userId'];
        echo $this->admin_model->insert_admin_message($message, $userId);
    }

    public function user_group_creation(){
        $data['groups'] = $this->admin_model->get_group_for_account_creation();
        $data['main_content'] = 'admin/group_account';
        $this->load->view('admin/inc/template', $data); 
    }

    public function register_group_creation(){

        $group_id = $this->input->post('group_name');
        $username = $this->input->post('group_username');
        $password = $this->input->post('group_password');
        $email = 'mygroup@gmail.com';
        $additional_data = array(
                    'group_id' => $group_id
                );
        $group = array('3'); // Sets user to admin.

        $this->ion_auth->register($username, $password, $email, $additional_data, $group);
        redirect('admin_controller/user_group_creation');
    }

    public function applications_form(){
        $fields = $this->db->list_fields('member_registration');
        $direct_fields = $this->db->list_fields('director_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id') {
            array_push($fData, $field);
          } 
        }
        $data['fields'] = $fData;

        $directData = [];
        foreach ($direct_fields as $direct){
          if ($direct !='id') {
            array_push($directData, $direct);
          }
        }
        $data['fields'] = $fData;
        $data['directfields'] = $directData;
        $data['selected_enabled_fields'] = $this->admin_model->get_union_form_enabled_fields();
        $data['selected_required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['selected_direct_enabled_fields'] = $this->admin_model->get_direct_application_form_enabled_fields();
        $data['main_content'] = 'admin/pages/application_form';
        $this->load->view('admin/inc/template', $data); 
    }

    public function member_applications_form($unionCat=''){
        if (empty($unionCat)) {
            $unionCat = $this->input->post('unions_category');
        }
        $data['selected_enabled_fields'] = [];
        $data['selected_required_fields'] = [];
        if (!empty($unionCat)) {
            $data['selected_enabled_fields'] = $this->admin_model->get_union_form_enabled_fields_admin($unionCat);
            $data['selected_required_fields'] = $this->admin_model->get_union_form_required_fields_admin($unionCat);
        }

        $fields = $this->db->list_fields('member_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id' && $field!='client_user_id' && $field!='created_date' && $field!='member_id_number'&& $field!='validity_type' && $field!='validity_up_to_date' && $field!='union_location_country' && $field!='union_location_state' && $field!='union_location_district' && $field!='location_type' && $field!='mobile_number') {
            array_push($fData, $field);
          } 
        }
        $data['enabled_fields'] = $fData;
        $data['unionCat'] = $unionCat;
        $data['myunions_category'] = $this->admin_model->get_member_unions_category();
        $data['main_content'] = 'admin/pages/admin/member_application_form';
        $this->load->view('admin/inc/template', $data); 
    }

    public function director_applications_form($unionCat=''){
        if (empty($unionCat)) {
            $unionCat = $this->input->post('unions_category');
        }
        $data['selected_enabled_fields'] = [];
        $data['selected_required_fields'] = [];
        if (!empty($unionCat)) {
            $data['selected_enabled_fields'] = $this->admin_model->get_union_director_form_enabled_fields_admin($unionCat);
            $data['selected_required_fields'] = $this->admin_model->get_union_director_form_required_fields_admin($unionCat);
        }

        $fields = $this->db->list_fields('director_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id' && $field!='client_user_id' && $field!='created_date' && $field!='director_id_number' && $field!='validity_type' && $field!='validity_up_to_date' && $field!='union_location_country' && $field!='union_location_state' && $field!='union_location_district' && $field!='location_type' && $field!='mobile_number') {
            array_push($fData, $field);
          } 
        }
        $data['enabled_fields'] = $fData;
        $data['unionCat'] = $unionCat;
        $data['myunions_category'] = $this->admin_model->get_member_unions_category();
        $data['main_content'] = 'admin/pages/admin/director_application_form';
        $this->load->view('admin/inc/template', $data); 
    }

    public function header_leader_applcation_form($unionCat=''){
        if (empty($unionCat)) {
            $unionCat = $this->input->post('unions_category');
        }
        $data['selected_enabled_fields'] = [];
        $data['selected_required_fields'] = [];
        if (!empty($unionCat)) {
            $data['selected_enabled_fields'] = $this->admin_model->get_union_header_leader_form_enabled_fields_admin($unionCat);
            $data['selected_required_fields'] = $this->admin_model->get_union_header_leader_form_required_fields_admin($unionCat);
        }

        $fields = $this->db->list_fields('header_leader_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id' && $field!='client_user_id' && $field!='created_date' && $field!='member_id_number'&& $field!='validity_type' && $field!='validity_up_to_date' && $field!='union_location_country' && $field!='union_location_state' && $field!='union_location_district' && $field!='location_type'  && $field!='mobile_number') {
            array_push($fData, $field);
          }
        }
        $data['enabled_fields'] = $fData;
        $data['unionCat'] = $unionCat;
        $data['myunions_category'] = $this->admin_model->get_member_unions_category();
        $data['main_content'] = 'admin/pages/admin/header_leader_application_form';
        $this->load->view('admin/inc/template', $data); 
    }

    public function staff_applcation_form($unionCat=''){
        if (empty($unionCat)) {
            $unionCat = $this->input->post('unions_category');
        }
        $data['selected_enabled_fields'] = [];
        $data['selected_required_fields'] = [];
        if (!empty($unionCat)) {
            $data['selected_enabled_fields'] = $this->admin_model->get_union_staff_form_enabled_fields_admin($unionCat);
            $data['selected_required_fields'] = $this->admin_model->get_union_staff_form_required_fields_admin($unionCat);
        }

        $fields = $this->db->list_fields('union_staff_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id' && $field!='client_user_id' && $field!='created_date' && $field!='member_id_number'&& $field!='validity_type' && $field!='validity_up_to_date' && $field!='union_location_country' && $field!='union_location_state' && $field!='union_location_district' && $field!='location_type' && $field!='mobile_number') {
            array_push($fData, $field);
          }
        }
        $data['enabled_fields'] = $fData;
        $data['unionCat'] = $unionCat;
        $data['myunions_category'] = $this->admin_model->get_member_unions_category();
        $data['main_content'] = 'admin/pages/admin/union_staff_application_form';
        $this->load->view('admin/inc/template', $data); 
    }

    public function application_configure_fields(){
        $result = $this->admin_model->insert_admission_configure_fields();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/applications_form');
    }

    public function category(){
        $data['category'] = $this->admin_model->get_category_list();
        $data['main_content'] = 'admin/pages/category';
        $this->load->view('admin/inc/template', $data); 
    }

    public function insert_categroy(){
        $result = $this->admin_model->insert_category();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/category');
    }

    public function edit_category($id){
        $data['edit_category'] = $this->admin_model->get_category_by_id($id);
        $data['category'] = $this->admin_model->get_category_list();
        $data['main_content'] = 'admin/pages/category';
        $this->load->view('admin/inc/template', $data); 
    }
    public function update_categroy($id){
        $result = $this->admin_model->update_categorybyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/category');
    }

    public function delete_category($id){
        $result = $this->admin_model->delete_categorybyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/category');
    }

    public function create_form(){

        $client_create_fields = $this->admin_model->get_client_application_fields();

        foreach ($client_create_fields as $key => $value) {
            $fields[$value->name] = json_decode($value->value);
        }

        $fData = [];
        foreach ($fields['application_show_enabled_fields'] as $field){
            array_push($fData, $field);
        }
        $data['fields'] = $fData;

        $directData = [];
        foreach ($fields['director_application_show_enabled_fields'] as $direct){
            array_push($directData, $direct);
        }
        $data['fields'] = $fData;
        $data['directfields'] = $directData;

        $data['selected_enabled_fields'] = $this->admin_model->get_union_form_enabled_fields();
        $data['selected_required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['selected_direct_enabled_fields'] = $this->admin_model->get_direct_application_form_enabled_fields();

        $data['main_content'] = 'admin/pages/client_create_application';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function member_create_form(){

        $fields = $this->db->list_fields('member_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id' && $field!='client_user_id' && $field!='union_location_country' && $field!='union_location_state' && $field!='union_location_district' && $field!='mobile_number') {
            array_push($fData, $field);
          } 
        }

        $admin_enabled_fields = $this->admin_model->get_admin_enabled_fields();
       
        $data['enabled_fields'] =   $this->_filter_enabled_fieds($fData, $admin_enabled_fields);
        $data['selected_enabled_fields'] = $this->admin_model->get_union_form_enabled_fields();
        $data['selected_required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['main_content'] = 'admin/pages/member_create_application';
        $this->load->view('admin/inc/template_client', $data); 
    }

    private function _filter_enabled_fieds($fData,$admin_enabled_fields){
        if (!empty($admin_enabled_fields)) {
            $enabled_fields = [];
            foreach ($admin_enabled_fields as $key => $val) {
                if ($val->name == 'show_enabled_fields') {
                    $jsonValue = json_decode($val->value);
                }
                if ($val->name == 'required_fields') {
                    $jsonRequired = json_decode($val->value);
                }
            }
            return array('enabled_fields' => $jsonValue, 'required_fields' => $jsonRequired);
        }
    }
    public function director_create_form(){
        $fields = $this->db->list_fields('director_registration');
        $fData = [];
        foreach ($fields as $field){
          if ($field !='id' && $field!='client_user_id') {
            array_push($fData, $field);
          } 
        }

        $admin_enabled_fields = $this->admin_model->get_admin_enabled_fields();
       
        $data['enabled_fields'] =   $this->_filter_enabled_fieds($fData, $admin_enabled_fields);
        $data['selected_enabled_fields'] = $this->admin_model->get_union_form_enabled_fields();
        $data['selected_required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['main_content'] = 'admin/pages/director_create_application';
        $this->load->view('admin/inc/template_client', $data); 

        // $client_create_fields = $this->admin_model->get_client_application_fields();

        // foreach ($client_create_fields as $key => $value) {
        //     $fields[$value->name] = json_decode($value->value);
        // }

        // $directData = [];
        // foreach ($fields['director_application_show_enabled_fields'] as $direct){
        //     array_push($directData, $direct);
        // }
        // $data['directfields'] = $directData;
        // $data['selected_direct_enabled_fields'] = $this->admin_model->get_direct_application_form_enabled_fields();
        // $data['main_content'] = 'admin/pages/director_create_application';
        // $this->load->view('admin/inc/template_client', $data); 
    }

    public function member_application_configure_fields(){
        $result = $this->admin_model->insert_admission_configure_fields();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/member_create_form');
    }

    public function director_application_configure_fields(){
        $result = $this->admin_model->insert_admission_configure_fields();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/director_create_form');
    }
    
    public function member_registration($trans = 0){
        $data['trans'] = $trans;
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        $data['enabled_fields'] = $this->admin_model->enabled_fields_member_form();
        $data['required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['member_validity'] = $this->admin_model->get_member_validity_data();
        // echo "<pre>"; print_r($data['member_validity']); die();
        $ArraySelection = [];
        $ArrayFreshAmount = [];
        if (!empty($data['member_validity'])) {
            $ArraySelection = json_decode($data['member_validity']->auto_selection);
            $ArrayFreshAmount = json_decode($data['member_validity']->auto_fresher_amount);
        }
        $AutoArry = [];
        if (!empty($ArraySelection)) {
            foreach ($ArraySelection as $key => $value) {
               if (array_key_exists($key, $ArrayFreshAmount)) {
                  $AutoArry[$value] = $ArrayFreshAmount[$key];
               }
            }
        }
        // echo "<pre>"; print_r($data['enabled_fields']); die();
        $data['member_auto'] = $AutoArry;
        $data['main_content'] = 'admin/pages/member_registration';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function director_registration(){
        $data['enabled_fields'] = $this->admin_model->enabled_fields_director_form();
        $data['required_fields'] = $this->admin_model->required_fields_director_form();
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/director_registration';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function member_registration_index(){
        $data['enabled_fields'] = $this->admin_model->enabled_fields_member_form();
        $data['member_view'] = $this->admin_model->get_members_list();
        $data['main_content'] = 'admin/pages/member_registration_index';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function member_registration_insert(){
        $file = $_FILES['profile_photo'];
        $imagepathurl = '';
        if (!empty($file)) {
            $file = $_FILES['profile_photo'];
            $imagepathurl = $this->s3FileUpload_profile($file);
        }
        $result = $this->admin_model->member_registration_insertbypost($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/member_registration_index');
    }

    public function edit_union_members_list($id){
        $data['member_edit'] = $this->admin_model->get_member_edit_form_by_id($id);
        // echo "<pre>"; print_r($data['member_edit']); die();
        $data['enabled_fields'] = $this->admin_model->enabled_fields_member_form();
        $data['required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['member_view'] = $this->admin_model->get_members_list();
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        $data['main_content'] = 'admin/pages/member_registration_edit';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function member_registration_update($id){
        $file = $_FILES['profile_photo'];
        $imagepathurl = '';
        if (!empty($file)) {
            $file = $_FILES['profile_photo'];
            $imagepathurl = $this->s3FileUpload_profile($file);
        }
        $result = $this->admin_model->member_registration_updatebypost($imagepathurl, $id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/member_registration_index');
    }
    public function director_registration_insert(){
        $imagepathurl['file_name'] = '';
        if (isset($_FILES['profile_photo'])) {
            $file = $_FILES['profile_photo'];
            $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
            $picture = array('tmp_name' => $min_size, 'name' => $min_size);
            $imagepathurl = $this->s3FileUpload_profile($picture);
        }
        $result = $this->admin_model->director_registration_insertbypost($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/director_registration');
    }

    public function director_registration_update($id){
        $imagepathurl['file_name'] = '';
        if (isset($_FILES['profile_photo'])) {
            $file = $_FILES['profile_photo'];
            $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
            $picture = array('tmp_name' => $min_size, 'name' => $min_size);
            $imagepathurl = $this->s3FileUpload_profile($picture);
        }
        $result = $this->admin_model->director_registration_updatebypost($imagepathurl, $id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/director_registration_index');
    }

    public function s3FileUpload_profile($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'profile');
        //print_r($uploadResult); die();
        return $uploadResult;
    }
    
    private function _resize_image($file, $max_resolution, $type) {
        if(file_exists($file)) {
            if($type == 'image/jpeg' || $type == 'image/png' || $type == 'image/PNG' || $type == 'image/jpg')
                $original_image = imagecreatefromjpeg($file);
            else 
                $original_image = imagecreatefrompng($file);

            //check orientation 
            // $exif = exif_read_data($file);

            try {
                $exif = exif_read_data($file);
            }
            catch (Exception $exp) {
                $exif = false;
            }

            if($exif) {
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $original_image = imagerotate($original_image, 180, 0);
                            break;

                        case 6:
                            $original_image = imagerotate($original_image, -90, 0);
                            break;

                        case 8:
                            $original_image = imagerotate($original_image, 90, 0);
                            break;
                    }
                }
            }

            //resolution
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            //try width first
            $ratio = $max_resolution / $original_width;
            $new_width = $max_resolution;
            $new_height = $original_height * $ratio;

            //if that dosn't work
            if($new_height > $max_resolution) {
                $ratio = $max_resolution / $original_height;
                $new_height = $max_resolution;
                $new_width = $original_width * $ratio;
            }

            if($original_image) {
                $new_image = imagecreatetruecolor($new_width, $new_height);
                imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
                if($type == 'image/jpeg')
                    imagejpeg($new_image, $file);
                else 
                    imagepng($new_image, $file);
            }

            return $file;
        }
    }

    public function deleted_member_list($id){
        $result = $this->admin_model->member_registration_deletebyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Deleted Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/member_registration_index');
    }

    public function deleted_header_leader_list(){
        $result = $this->admin_model->header_leader_registration_deletebyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Deleted Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/header_leader_index');
    }

    public function deleted_director_list($id){
        $result = $this->admin_model->director_registration_deletebyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Deleted Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/director_registration_index');
    }

    public function director_registration_index(){
        $data['enabled_fields'] = $this->admin_model->enabled_fields_director_form();
        $data['director_view'] = $this->admin_model->get_director_list();
        $data['main_content'] = 'admin/pages/director_registration_index';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function apply_database(){
        $data['apply_database'] = $this->admin_model->get_apply_database();
        $data['main_content']    = 'admin/pages/apply_database';
        $this->load->view('admin/inc/template', $data);
    }

    public function add_media_category($groupId, $mediaType){
        $data['group_id'] = $groupId;
        $data['mediaType'] = $mediaType;
        $data['category'] = $this->admin_model->get_media_category($groupId,$mediaType);
        $data['main_content']    = 'admin/pages/media/category';
        $this->load->view('admin/inc/template', $data);
    }

    public function get_media_clientsbygroup($groupId, $mediaType){
        $data['client'] = $this->admin_model->get_client_registration($groupId,$mediaType);
        $data['main_content']    = 'admin/pages/media/client';
        $this->load->view('admin/inc/template', $data);
    }

    public function insert_media_categroy(){
        $input = $this->input->post();
        $result = $this->admin_model->insert_media_categroy_details($input);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Insert Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/add_media_category/'.$input['group_id'].'/'.$input['media_type']);
    }

    public function delete_media_category($catId, $group_id, $mediaType){

        $result = $this->admin_model->delete_media_categroy_details($catId);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Delete Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/add_media_category/'.$group_id.'/'.$mediaType);
    }

    public function add_needy_category($groupId, $needyType){
        $data['group_id'] = $groupId;
        $data['needyType'] = $needyType;
        $data['category'] = $this->admin_model->get_needy_category($groupId,$needyType);
        $data['main_content']    = 'admin/pages/needy/category';
        $this->load->view('admin/inc/template', $data);
    }

    public function insert_needy_categroy(){
        $input = $this->input->post();
        $result = $this->admin_model->insert_needy_categroy_details($input);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Insert Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/add_needy_category/'.$input['group_id'].'/'.$input['needy_type']);
    }

    public function delete_needy_category($catId, $group_id, $needyType){

        $result = $this->admin_model->delete_needy_categroy_details($catId);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Delete Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/add_needy_category/'.$group_id.'/'.$needyType);
    }

    public function update_needy_category_img(){
        $input = $this->input->post();
        $creDir = 'uploads/needy_category/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if (!empty($_FILES['category_img'])) {
            if ($_FILES['category_img']['name'] != "") {
                $ret_val = $this->upload('category_img', $creDir);
                if ($ret_val['status'] == 'success') {
                    $cat_img = $ret_val['data'];
                }else{
                    $cat_img = $ret_val['data'];
                }
            }else{
                $cat_img ="";
            }
        }else{
            $cat_img ="";
        }

        $result = $this->admin_model->insert_upload_needy_category_img($cat_img, $input['cat_id']);

        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('admin_controller/add_needy_category/'.$input['group_id'].'/'.$input['needy_type']);

    }

    public function needy_my_orders($groupId, $needyType){
        $data['group_id'] = $groupId;
        $data['needyType'] = $needyType;
        $data['category'] = $this->admin_model->get_needy_category($groupId,$needyType);
        $data['main_content']    = 'admin/pages/needy/my_orders';
        $this->load->view('admin/inc/template', $data);
    }
    
    public function create_category($group_name){
        $data['group_name'] = $group_name;
        $data['main_content']    = 'admin/pages/group_category';
        $this->load->view('admin/inc/template', $data);
    }

    public function create_group_category($group_name, $category, $buttonActive){
        $data['selectedCatButton']='singleActive';
        $data['group_name'] = $group_name;
        $data['category'] = $category;
        $data['buttonActive'] = $buttonActive;
        if ($buttonActive == 'singleActive') {
            switch ($group_name) {
                case 'Mymedia':
                    $result = $this->admin_model->get_group_created_category_single('media_category','media_type',$category);
                    break;
                case 'Myjoy':
                    $result = $this->admin_model->get_group_created_category_single('myjoy_category','joy_type',$category);
                    break;
                case 'Myshop':
                    $result = $this->admin_model->get_group_created_category_single('myshop_category','shop_type',$category);
                    break;
                case 'Myfriend':
                    $result = $this->admin_model->get_group_created_category_single('myfriend_category','friend_type',$category);
                    break;
                case 'Myunions':
                    $result = $this->admin_model->get_group_created_category_single('myunions_category','unions_type',$category);
                    break;
                case 'Mybiz':
                    $result = $this->admin_model->get_group_created_category_single('mybiz_category','biz_type',$category);
                    break;
                case 'Mytv':
                    $result = $this->admin_model->get_group_created_category_single('mytv_category','tv_type',$category);
                    break;
                case 'Myneedy':
                    $result = $this->admin_model->get_group_created_category_single('needy_category','needy_type',$category);
                    break;
            }
            $data['view_cat'] = $result;
            $data['main_content']    = 'admin/pages/group_category_index';
        }
        if ($buttonActive == 'subActive') {
             switch ($group_name) {
                case 'Mymedia':
                    $result = $this->admin_model->get_group_created_category_sub('media_category','media_type',$category);
                    break;
                case 'Myjoy':
                    $result = $this->admin_model->get_group_created_category_sub('myjoy_category','joy_type',$category);
                    break;
                case 'Myshop':
                    $result = $this->admin_model->get_group_created_category_sub('myshop_category','shop_type',$category);
                    break;
                case 'Myfriend':
                    $result = $this->admin_model->get_group_created_category_sub('myfriend_category','friend_type',$category);
                    break;
                case 'Myunions':
                    $result = $this->admin_model->get_group_created_category_sub('myunions_category','unions_type',$category);
                    break;
                case 'Mybiz':
                    $result = $this->admin_model->get_group_created_category_sub('mybiz_category','biz_type',$category);
                    break;
                 case 'Mytv':
                    $result = $this->admin_model->get_group_created_category_single('mytv_category','tv_type',$category);
                case 'Myneedy':
                    $result = $this->admin_model->get_group_created_category_sub('needy_category','needy_type',$category);
                    break;
            }
            $data['view_cat'] = $result;
            $data['main_content']    = 'admin/pages/group_sub_category_index';
        }
        if ($buttonActive == 'subSubActive') {
             switch ($group_name) {
                case 'Mymedia':
                    $result = $this->admin_model->get_group_created_category_sub_sub('media_category','media_type',$category);
                    break;
                case 'Myjoy':
                    $result = $this->admin_model->get_group_created_category_sub_sub('myjoy_category','joy_type',$category);
                    break;
                case 'Myshop':
                    $result = $this->admin_model->get_group_created_category_sub_sub('myshop_category','shop_type',$category);
                    break;
                case 'Myfriend':
                    $result = $this->admin_model->get_group_created_category_sub_sub('myfriend_category','friend_type',$category);
                    break;
                case 'Myunions':
                    $result = $this->admin_model->get_group_created_category_sub_sub('myunions_category','unions_type',$category);
                    break;
                case 'Mybiz':
                    $result = $this->admin_model->get_group_created_category_sub_sub('mybiz_category','biz_type',$category);
                    break;
                case 'Mytv':
                    $result = $this->admin_model->get_group_created_category_single('mytv_category','tv_type',$category);
                case 'Myneedy':
                    $result = $this->admin_model->get_group_created_category_sub_sub('needy_category','needy_type',$category);
                    break;
            }
            $data['view_cat'] = $result;
            $data['main_content']    = 'admin/pages/group_sub_sub_category_index';
        }
        $this->load->view('admin/inc/template', $data);  
    }
    
    public function group_category($group_name, $category){
        $data['group_name'] = $group_name;
        $data['category'] = $category;
        $data['selectedCatButton']='singleActive';
        switch ($group_name) {
            case 'Mymedia':
                $result = $this->admin_model->get_group_created_category('media_category','media_type',$category);
                break;
            case 'Myjoy':
                $result = $this->admin_model->get_group_created_category('myjoy_category','joy_type',$category);
                break;
            case 'Myshop':
                $result = $this->admin_model->get_group_created_category('myshop_category','shop_type',$category);
                break;
            case 'Myfriend':
                $result = $this->admin_model->get_group_created_category('myfriend_category','friend_type',$category);
                break;
            case 'Myunions':
                $result = $this->admin_model->get_group_created_category('myunions_category','unions_type',$category);
                break;
            case 'Mybiz':
                $result = $this->admin_model->get_group_created_category('mybiz_category','biz_type',$category);
                break;
            case 'Mytv':
                $result = $this->admin_model->get_group_created_category('mytv_category','tv_type',$category);
            case 'Myneedy':
                $result = $this->admin_model->get_group_created_category('needy_category','needy_type',$category);
                break;
        }
        $data['view_cat'] = $result;
        $data['main_content']    = 'admin/pages/group_single_category_create';
        $this->load->view('admin/inc/template', $data);   
    }

    public function insert_category_data_group_wise($group_name, $category,$buttonActive){
        $data['group_name'] = $group_name;
        $data['category'] = $category;
        $input = $this->input->post();
        switch ($group_name) {
            case 'Mymedia':
                $result = $this->admin_model->insert_group_created_category('media_category','media_type',$input,$category,'8');
                break;
            case 'Myjoy':
                $result = $this->admin_model->insert_group_created_category('myjoy_category','joy_type',$input,$category);
                break;
            case 'Myshop':
                $result = $this->admin_model->insert_group_created_category('myshop_category','shop_type',$input,$category);
                break;
            case 'Myfriend':
                $result = $this->admin_model->insert_group_created_category('myfriend_category','friend_type',$input,$category);
                break;
            case 'Myunions':
                $result = $this->admin_model->insert_group_created_category('myunions_category','unions_type',$input,$category);
                break;
            case 'Mybiz':
                $result = $this->admin_model->insert_group_created_category('mybiz_category','biz_type',$input,$category);
                break;
             case 'Mytv':
                $result = $this->admin_model->insert_group_created_category('mytv_category','tv_type',$input,$category);
                break;
            case 'Myneedy':
                $result = $this->admin_model->insert_group_created_category('needy_category','needy_type',$input,$category);
                break;
        }
       
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Insert Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.$buttonActive);
    }

    public function update_group_category_img(){
        $input = $this->input->post();
        $creDir = 'uploads/group_category/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }
        if (!empty($_FILES['category_img'])) {
            if ($_FILES['category_img']['name'] != "") {
                $ret_val = $this->upload('category_img', $creDir);
                if ($ret_val['status'] == 'success') {
                    $cat_img = $ret_val['data'];
                }else{
                    $cat_img = $ret_val['data'];
                }
            }else{
                $cat_img ="";
            }
        }else{
            $cat_img ="";
        }
        $input = $this->input->post();
        switch ($input['group_name']) {
            case 'Mymedia':
                $result = $this->admin_model->insert_cat_img_group_created('media_category',$cat_img,$input['cat_id']);
                break;
            case 'Myjoy':
                $result = $this->admin_model->insert_cat_img_group_created('myjoy_category',$cat_img,$input['cat_id']);
                break;
            case 'Myshop':
                $result = $this->admin_model->insert_cat_img_group_created('myshop_category',$cat_img,$input['cat_id']);
                break;
            case 'Myfriend':
                $result = $this->admin_model->insert_cat_img_group_created('myfriend_category',$cat_img,$input['cat_id']);
                break;
            case 'Myunions':
                $result = $this->admin_model->insert_cat_img_group_created('myunions_category',$cat_img,$input['cat_id']);
                break;
            case 'Mybiz':
                $result = $this->admin_model->insert_cat_img_group_created('mybiz_category',$cat_img,$input['cat_id']);
                break;
            case 'Myneedy':
                $result = $this->admin_model->insert_cat_img_group_created('needy_category',$cat_img,$input['cat_id']);
                break;
        }
        echo 1;
        // if ($result) {
        //     $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        // }else{
        //     $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        // }
        // redirect('admin_controller/create_group_category/'.$input['group_name'].'/'.$input['category']);
    }

    public function insert_sub_category_data_group_wise($group_name, $category){
        $data['group_name'] = $group_name;
        $data['category'] = $category;
        $input = $this->input->post();
        $data['cat_id'] = $input['category_id'];
        $result = $this->admin_model->insert_group_created_sub_category($group_name,$input,$category,$input['category_id']);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Insert Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'subActive');
    }


     public function insert_sub_sub_category_data_group_wise($group_name, $category){
        $data['group_name'] = $group_name;
        $data['category'] = $category;
        $input = $this->input->post();
        $data['cat_id'] = $input['category_id'];
        $data['sub_cat_id'] = $input['sub_category_id'];
       
        $result = $this->admin_model->insert_group_created_sub_sub_category($group_name,$input,$category,$input['category_id'],$input['sub_category_id']); 
        if ($result) {
            $this->session->set_flashdata('flashSuccess', "Insert Successfully");
        }else{
            $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/create_group_category/'.$group_name.'/'.$category.'/'.'subSubActive');
    }

    public function get_category_wise_sub_category(){
        $group_name =$_POST['group_name'];
        $category_id =$_POST['category_id'];
        $result = $this->admin_model->get_category_wise_sub_categoryby_id($group_name, $category_id);
        echo json_encode($result);
    }

    public function insert_member_application_config_fields(){
        $result = $this->admin_model->insert_admission_configure_fields();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/member_create_form');
    }

    public function insert_member_application_config_fields_admin($unionCat){
        $result = $this->admin_model->insert_admission_configure_fields_admin($unionCat);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/member_applications_form/'.$unionCat);
    }
    
    public function insert_header_leader_application_config_fields_admin($unionCat){
        $result = $this->admin_model->insert_admission_configure_fields_admin($unionCat);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/header_leader_applcation_form/'.$unionCat);
    }

    public function insert_director_application_config_fields_admin($unionCat){
        $result = $this->admin_model->insert_admission_configure_fields_admin($unionCat);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/director_applications_form/'.$unionCat);
    }

    public function insert_staff_application_config_fields_admin($unionCat){
        $result = $this->admin_model->insert_admission_configure_fields_admin($unionCat);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/staff_applcation_form/'.$unionCat);
    }
    
    public function client_database_unions(){
        $data['partner_list'] = $this->admin_model->get_parter_register_list();
        $data['main_content'] = 'admin/pages/union_partner_list';
        $this->load->view('admin/inc/template', $data);
    }

    public function header_leader_index(){
        $data['enabled_fields'] = $this->admin_model->enabled_fields_header_leader_form();
        $data['header_leader_view'] = $this->admin_model->get_header_leader_list(4);
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/header_leader_registration_index';
        $this->load->view('admin/inc/template_client', $data);
    }
    
    public function header_leader_index_post($type=0, $val =''){
        $data['type']=$type;
        $data['leader_head']=$val;
        $data['enabled_fields'] = $this->admin_model->enabled_fields_header_leader_form();
        $data['header_leader_view'] = $this->admin_model->get_header_leader_list($type);
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/header_leader_registration_index_post';
        $this->load->view('admin/inc/template_client', $data);
    }
     public function header_leader_registration($type,$leader_head){
        $data['location_type'] = $type;
        $data['leader_head'] = $leader_head;
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        $data['enabled_fields'] = $this->admin_model->enabled_fields_header_leader_form();
        $data['required_fields'] = $this->admin_model->required_fields_header_leader_form();
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/header_leader_registration';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function header_leader_registration_insert(){
        $imagepathurl = '';
        if (!empty($_FILES['profile_photo'])) {
              $file = $_FILES['profile_photo'];
            $imagepathurl = '';
            if (!empty($file)) {
                $file = $_FILES['profile_photo'];
                $imagepathurl = $this->s3FileUpload_profile($file);
            }
        }
      
        $result = $this->admin_model->header_leader_registration_insertbypost($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/header_leader_index');
    }

    public function union_staff_index(){
        $data['enabled_fields'] = $this->admin_model->enabled_fields_union_staff_form();
        $data['union_staff_view'] = $this->admin_model->get_union_staff_list(4);
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/union_staff_registration_index';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function union_staff_index_post($type=0, $val =''){
        $data['type']=$type;
        $data['leader_head']=$val;
        $data['enabled_fields'] = $this->admin_model->enabled_fields_union_staff_form();
        $data['union_staff_view'] = $this->admin_model->get_union_staff_list($type);
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/union_staff_registration_index_post';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function union_staff_registration($type,$leader_head){
        $data['location_type'] = $type;
        $data['leader_head'] = $leader_head;
        $data['enabled_fields'] = $this->admin_model->enabled_fields_union_staff_form();
        $data['required_fields'] = $this->admin_model->required_fields_union_staff_form();
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/union_staff_registration';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function union_staff_registration_insert(){
        $imagepathurl['file_name'] = '';
        if (isset($_FILES['profile_photo'])) {
            $file = $_FILES['profile_photo'];
            $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
            $picture = array('tmp_name' => $min_size, 'name' => $min_size);
            $imagepathurl = $this->s3FileUpload_profile($picture);
        }
        $result = $this->admin_model->union_staff_registration_insertbypost($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/union_staff_index');
    }

     public function deleted_union_staff_list($id){
        $result = $this->admin_model->director_union_staff_deletebyId($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Deleted Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/union_staff_index');
    }

    public function union_director_switch_check(){
        $stngId = $_POST['stngId'];
        $value = $_POST['value'];
        echo $this->admin_model->union_director_switch_checkby_id($stngId,$value); 
    }

    public function union_partner_switch_check(){
        $stngId = $_POST['stngId'];
        $value = $_POST['value'];
        echo $this->admin_model->union_partner_switch_status_by_id($stngId,$value); 
    }

    public function edit_director_list($id){
        $data['enabled_fields'] = $this->admin_model->enabled_fields_director_form();
        $data['required_fields'] = $this->admin_model->required_fields_director_form();
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['edit_director'] = $this->admin_model->get_director_details_by_id($id);
        $data['main_content'] = 'admin/pages/director_registration_edit';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function franchise_application(){
        $data['franchise_database'] = $this->admin_model->get_franchise_database();
        $data['main_content']    = 'admin/pages/franchise_database';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function job_application(){
        $data['job_database'] = $this->admin_model->get_job_database();
        $data['main_content']    = 'admin/pages/job_database';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function enquiry_form(){
        $data['enquiry_database'] = $this->admin_model->get_enquiry_database();
        $data['main_content']    = 'admin/pages/enquiry_database';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function feed_back_suggetion(){
        $data['feedback_database'] = $this->admin_model->get_feedback_database();
        $data['main_content']    = 'admin/pages/feedback_database';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }

    public function application_details(){
        $data['application'] = $this->admin_model->get_all_application_details();
        $data['main_content']    = 'admin/pages/application_details';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }
    public function application_details_add(){
        $data['main_content']    = 'admin/pages/application_details_add';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }
    public function application_details_edit($id){
        $data['edit_apps_about'] = $this->admin_model->get_application_details_edit($id);
        $data['main_content']    = 'admin/pages/application_details_edit';
        $this->load->view('admin/inc/template_head_franchise',$data); 
    }


    public function get_group_wise_app_byselection(){
        $groupName = $_POST['groupName'];
        $result = $this->admin_model->get_group_wise_app_byselection_data($groupName);
        echo json_encode($result);
    }

    public function upload_about_apps(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['about_title'];
        $summernote = $input['summernote'];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $save_news[] = array(
                    'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'group_name'=> $input['group_name'],
                    'app_name'=> $input['apps_name'],
                );
            }
        }else{
            $save_news[] = array(
                'image' => $img['file_name'],
                'title' => $title,
                'content' => $summernote,
                'group_name'=> $input['group_name'],
                'app_name'=> $input['apps_name'],
            );
        }
        if(!empty($save_news)) {
            $this->admin_model->save_news($save_news,'my_aps_about_details');
        }
        echo 1;
    }

    public function upload_about_apps_by_id(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['about_title'];
        $summernote = $input['summernote'];
        $exit_fie_url = $input['exit_fie_url'];
        $about_id = $input['about_id'];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $update_about[] = array(
                    'id' => $about_id,
                     'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'group_name'=> $input['group_name'],
                    'app_name'=> $input['apps_name'],
                );
            }
        }else{
            $update_about[] = array(
                'id' => $about_id,
                'image' => $exit_fie_url,
                'title' => $title,
                'content' => $summernote,
                'group_name'=> $input['group_name'],
                'app_name'=> $input['apps_name'],
            );
        }
        if(!empty($update_about)) {
            $this->admin_model->update_news($update_about,'my_aps_about_details');
        }
        echo 1;
    }

    public function application_details_delete($id){
        $result = $this->admin_model->application_details_delete_by_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Deleted Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('admin_controller/application_details');  
    }

    public function get_needy_clientsbygroup($groupId, $mediaType){
        $data['client'] = $this->admin_model->get_client_registration($groupId,$mediaType);
        $data['main_content']    = 'admin/pages/needy/client';
        $this->load->view('admin/inc/template', $data);
    }
}   

?>