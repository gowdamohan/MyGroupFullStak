<?php

class Client_controller extends CI_Controller {
               
	function __construct(){
    parent::__construct();
    $this->load->model('client_model');
    $this->load->model('admin_model');
    $this->load->library('filemanager');     
  }


  public function logo(){
    $data['logo'] = $this->client_model->get_client_logo_details();
    $data['main_content']    = 'admin/pages/client/logo';
    $this->load->view('admin/inc/template_client', $data);
  }

  public function logo_name(){
    $data['client_name'] = $this->client_model->get_client_name_details();
    $data['main_content']    = 'admin/pages/client/logo_name';
    $this->load->view('admin/inc/template_client', $data); 
  }

  public function unions_details(){
    $data['unions_details'] = $this->client_model->get_unions_detailsbyid();
    // echo "<pre>"; print_r($data['unions_details']); die();
    $data['main_content']    = 'admin/pages/client/logo_name';
    $this->load->view('admin/inc/template_client', $data); 
  }

  public function client_name(){
    $data['main_content']    = 'admin/pages/client/client_name';
    $this->load->view('admin/inc/template_client', $data);
  }

  public function upload_logo(){
    $file = $_FILES['logo_photo'];
    $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
    $picture = array('tmp_name' => $min_size, 'name' => $min_size);
    $imagepathurl = $this->s3FileUpload_client($picture);

    $result = $this->client_model->insert_client_logo($imagepathurl);
    if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully inserted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    redirect('client_controller/logo');
  }

  public function upload_logo_name(){
    $file = $_FILES['logo_photo'];
    $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
    $picture = array('tmp_name' => $min_size, 'name' => $min_size);
    $imagepathurl = $this->s3FileUpload_client($picture);

    $result = $this->client_model->insert_client_logo_name($imagepathurl);
    if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully inserted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    redirect('client_controller/unions_details');
  }


    public function upload_name_color(){
       $result = $this->client_model->insert_client_name();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_name');
    }

    public function s3FileUpload_client($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'client');
        //print_r($uploadResult); die();
        return $uploadResult;
    }

    private function _resize_image($file, $max_resolution, $type) {
        if(file_exists($file)) {
            if($type == 'image/jpeg')
                $original_image = imagecreatefromjpeg($file);
            else 
                $original_image = imagecreatefrompng($file);

            //check orientation 
            // $exif = exif_read_data($file);

            try {
                $exif = @exif_read_data($file);
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

    public function client_document(){
        $data['client_document'] = $this->client_model->get_client_document_details();
        $data['main_content']    = 'admin/pages/client/client_document';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_document(){
        $file = $_FILES['client_document'];
        $document = $this->s3FileUpload_profile($file);
        $result = $this->client_model->insert_client_document($document);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_document');
    }

    public function delete_doc($id){
        $result = $this->client_model->delete_doc_by_client_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully delete");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_document');
    }

    public function download_client_doc($id){
        $link = $this->client_model->download_client_document($id);
        // $url = $this->filemanager->getFilePath($link->document_path);

        $link = $link->document_path;
        $file = explode("/", $link);
        $file_name1 = $link->document_name.'_'.$link->document_name;
        $ext = explode(".", $file[count($file)-1])[1];
        $ext = ($ext == '')?'png':$ext;
        $fname = $file_name1 .'.'. $ext;
        $url = $this->filemanager->getFilePath($link);
        $data = file_get_contents($url);
        $this->load->helper('download');
        force_download($fname, $data, TRUE);
    }

    public function client_awards(){
        $data['client_awards'] = $this->client_model->get_client_awards_details();
        $data['main_content']    = 'admin/pages/client/client_awards';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_awards(){
        $file = $_FILES['awards_path'];
        $document = $this->s3FileUpload_profile($file);
        $result = $this->client_model->insert_client_awards($document);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_awards');
    }

    public function delete_awards($id){
        $result = $this->client_model->delete_awards_by_client_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully delete");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_awards');
    }

    public function download_client_awards($id){
        $link = $this->client_model->download_client_awards($id);
        // $url = $this->filemanager->getFilePath($link->document_path);

        $link = $link->awards_path;
        $file = explode("/", $link);
        $file_name1 = $link->awards_name.'_'.$link->awards_name;
        $ext = explode(".", $file[count($file)-1])[1];
        $ext = ($ext == '')?'png':$ext;
        $fname = $file_name1 .'.'. $ext;
        $url = $this->filemanager->getFilePath($link);
        $data = file_get_contents($url);
        $this->load->helper('download');
        force_download($fname, $data, TRUE);
    }

    // New Letter
    public function client_news_letter(){
        $data['client_news_letter'] = $this->client_model->get_client_news_letter_details();
        $data['main_content']    = 'admin/pages/client/client_news_letter';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_news_letter(){
        $file = $_FILES['news_letter_path'];
        $document = $this->s3FileUpload_profile($file);
        $result = $this->client_model->insert_client_news_letter($document);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_news_letter');
    }

    public function delete_news_letter($id){
        $result = $this->client_model->delete_news_letter_by_client_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully delete");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_news_letter');
    }

    public function download_client_news_letter($id){
        $link = $this->client_model->download_client_news_letter($id);
        // $url = $this->filemanager->getFilePath($link->document_path);

        $link = $link->news_letter_path;
        $file = explode("/", $link);
        $file_name1 = $link->news_letter_name.'_'.$link->news_letter_name;
        $ext = explode(".", $file[count($file)-1])[1];
        $ext = ($ext == '')?'png':$ext;
        $fname = $file_name1 .'.'. $ext;
        $url = $this->filemanager->getFilePath($link);
        $data = file_get_contents($url);
        $this->load->helper('download');
        force_download($fname, $data, TRUE);
    }

    // Objectivies
    public function client_objectives(){
        $data['client_objectivies'] = $this->client_model->get_client_objectivies_details();
        $data['main_content']    = 'admin/pages/client/client_objectivies';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_objectivies(){
        $file = $_FILES['objectivies_path'];
        $document = $this->s3FileUpload_profile($file);
        $result = $this->client_model->insert_client_objectivies($document);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_objectives');
    }

    public function delete_objectivies($id){
        $result = $this->client_model->delete_objectivies_by_client_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully delete");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_objectives');
    }

    public function download_client_objectivies($id){
        $link = $this->client_model->download_client_objectivies($id);
        // $url = $this->filemanager->getFilePath($link->document_path);

        $link = $link->objectivies_path;
        $file = explode("/", $link);
        $file_name1 = $link->objectivies_name.'_'.$link->objectivies_name;
        $ext = explode(".", $file[count($file)-1])[1];
        $ext = ($ext == '')?'png':$ext;
        $fname = $file_name1 .'.'. $ext;
        $url = $this->filemanager->getFilePath($link);
        $data = file_get_contents($url);
        $this->load->helper('download');
        force_download($fname, $data, TRUE);
    }

    public function s3FileUpload_profile($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'profile');
        //print_r($uploadResult); die();
        return $uploadResult;
    }

    public function client_admin_details(){
        $data['client_admin'] = $this->client_model->get_client_admin_details();
        $data['main_content']    = 'admin/pages/client/client_admin_details';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_admin_details($id){
        $result = $this->client_model->upload_admin_details_by_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_admin_details');
    }

    public function media_dashboard(){
        $data['lock_media'] = $this->client_model->get_client_channel_created_list_for_lock();
        // echo "<pre>"; print_r($data['lock_media']); die();
        $data['main_content']    = 'admin/pages/client/create_media_dashboard';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function create_media($mediaType){
        $data['mediaType'] = $mediaType;
        $data['media_category'] = $this->client_model->get_media_category_by_type($mediaType);
        $data['language'] = $this->admin_model->get_language_details();
        $data['country'] = $this->admin_model->get_country_flag();
        $data['main_content']    = 'admin/pages/client/create_media';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_channel_media_logo(){
        $file = $_FILES['media_logo_file'];
        $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
        $picture = array('tmp_name' => $min_size, 'name' => $min_size);
        $result = $this->s3FileUpload_folder($file,'meida-logo');
        echo json_encode($result);
    }

    public function upload_media_channel_form(){
        $result = $this->client_model->insert_media_channel_form();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/media_dashboard');
    }

    public function s3FileUpload_folder($file, $folder){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], $folder);
        return $uploadResult;
    }

    public function my_channel_list(){
        $data['channel_list'] = $this->client_model->get_client_channel_list();
        $data['main_content']    = 'admin/pages/client/my_channel_list';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function create_each_channel_list($mediaType){
        $data['mediaType'] = $mediaType;
        $data['get_media_type'] = $this->client_model->each_channel_page($mediaType);
        $data['main_content']    = 'admin/pages/client/channel';
        $this->load->view('admin/inc/template_channel', $data); 
    }

    public function upload_page_display($mediaType){
        $data['mediaType'] = $mediaType;
        $data['get_media_type'] = $this->client_model->each_channel_page($mediaType);
        $data['main_content']    = 'admin/pages/client/upload_page';
        $this->load->view('admin/inc/template_channel1', $data); 
    }

    public function upload_media_client_document(){
        $files = $_FILES['video_file'];
        $result = $this->s3FileUpload_folder($files,'client-document');
        echo json_encode($result);
    }


    public function insert_media_client_document(){
        $file_path = $_POST['file_path'];
        $media_channel_id = $_POST['media_channel_id'];
        $media_type = $_POST['media_type'];
        $date = $_POST['date'];
        $result = $this->client_model->insert_media_client_document($file_path, $media_channel_id, $media_type, $date);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/upload_page_display/'.$media_type);  
    }

    public function view_client_document_file($mediaType){
        $data['mediaType'] = $mediaType;
        $data['get_media_type'] = $this->client_model->each_channel_page($mediaType);
        $data['view_file'] = $this->client_model->view_client_document_file_by_id($data['get_media_type']->id);
        if ($mediaType == 'tv') {
            $data['view_file'] = $this->client_model->get_media_link_by_id($data['get_media_type']->id);
            $data['main_content']    = 'admin/pages/client/view_tv';
        }else{
            $data['main_content']    = 'admin/pages/client/view_document';
        }
        $this->load->view('admin/inc/template_channel1', $data); 
    }

    public function live_url_form($mediaType, $media_chanel_id){
        $data['mediaType'] = $mediaType;
        $data['media_chanel_id'] = $media_chanel_id;
        $data['get_media_type'] = $this->client_model->each_channel_page($mediaType);
        $data['media_link'] = $this->client_model->get_media_link_by_id($media_chanel_id);
        $data['main_content']    = 'admin/pages/client/create_url_link';
        $this->load->view('admin/inc/template_channel', $data); 
    }

    public function insert_media_client_live_url_link(){
        $input = $this->input->post();
        $result = $this->client_model->insert_media_client_live_url_link_by_id($input);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/live_url_form/'.$input['media_type'].'/'.$input['media_channel_id']); 
    }

    public function update_media_client_live_url_link(){
        $input = $this->input->post();
        $result = $this->client_model->update_media_client_live_url_link_by_id($input);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/live_url_form/'.$input['media_type'].'/'.$input['media_channel_id']); 
    }

    public function swicher_page($mediaType){
        $data['mediaType'] = $mediaType;
        $data['get_media_type'] = $this->client_model->each_channel_page($mediaType);
        if ($mediaType == 'tv') {
            $data['main_content']    = 'admin/pages/client/swicher_tv';
        }else{
            $data['main_content']    = 'admin/pages/client/swicher_radio';
        }
        
        $this->load->view('admin/inc/template_channel_without_sidebar', $data); 
    }

    public function create_services($group_id){
        $data['group_id'] = $group_id;
        $data['services_count'] = $this->client_model->created_services_count($group_id);
        $data['main_content']    = 'admin/pages/needy/client_services';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function creted_needy_services($type, $group_id){
        $data['type'] = $type;
        $data['group_id'] = $group_id;
        $data['country'] = $this->admin_model->get_country_flag();
        $data['needy_category'] = $this->admin_model->get_needy_category_by_type($type, $group_id);
        $data['main_content']    = 'admin/pages/needy/client_services_form';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function insert_needy_client_services_details(){
        $input = $this->input->post();
        $file = $_FILES['photo'];
        $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
        $picture = array('tmp_name' => $min_size, 'name' => $min_size);
        $imagepathurl = $this->s3FileUpload_client($picture);

        $result = $this->client_model->insert_needy_client_service_form_details($imagepathurl, $input);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/create_services/'.$input['group_id']);

    }

    public function view_listservices($group_id){
        $data['group_id'] = $group_id;
        $data['services_list'] = $this->client_model->view_created_services_list($group_id);
        $data['main_content']    = 'admin/pages/needy/view_services';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function needy_services_status_udpate(){
        $stngId = $_POST['stngId'];
        $value = $_POST['value'];
        echo $this->client_model->needy_services_status_udpate_by_id($stngId,$value); 
    }

    public function edit_needy_client_services($id){
        $data['edit_needy_service'] = $this->client_model->edit_needy_client_services_by_id($id);
        $data['main_content']    = 'admin/pages/needy/client_services_form_edit';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function update_needy_client_services_details($id){
        $input = $this->input->post();
        $file = $_FILES['photo'];
        $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
        $picture = array('tmp_name' => $min_size, 'name' => $min_size);
        $imagepathurl = $this->s3FileUpload_client($picture);

        $result = $this->client_model->update_needy_client_services_details_by_id($imagepathurl, $input, $id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Updated");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/view_listservices/'.$input['group_id']);
    }

    public function validate_services($group_id){
        $data['group_id'] = $group_id;
        $data['services_list'] = $this->client_model->view_created_services_list($group_id);
        $data['main_content']    = 'admin/pages/needy/validate_services';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function my_order_client_needy(){
        $data['main_content']    = 'admin/pages/needy/my_orders';
        $this->load->view('admin/inc/template_client', $data);   
    }
    public function my_order_needy_category(){
        $data['client_needy_order_list'] = $this->client_model->client_needy_order_list();
        $data['main_content']    = 'admin/pages/needy/my_orders_category';
        $this->load->view('admin/inc/template_client', $data);   
    }

    public function create_client_shop(){
        $data['client_shop_create'] = $this->client_model->check_shop_client_count();
        $data['main_content']    = 'admin/pages/myshop/create_shop_dashboard';
        $this->load->view('admin/inc/template_client', $data);   
    }

    public function view_created_client_shop(){
        $data['main_content']    = 'admin/pages/myshop/view_created_shop';
        $this->load->view('admin/inc/template_client', $data);   
    }

    public function create_shop_details($shot_type){
        $data['shop_type'] = $shot_type;
        $data['shop_category'] = $this->client_model->get_shop_category_for_product($shot_type);
        $data['main_content']    = 'admin/pages/myshop/create_shop';
        $this->load->view('admin/inc/template_client', $data);   
    }

    public function insert_create_shop_new(){
        $result = $this->client_model->insert_my_shop_client_details_by_id();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/create_client_shop');
    }

    public function myshop_product($shop_type){
        $data['shop_type'] = $shop_type;
        $data['product_details'] = $this->client_model->get_myshop_product_details($shop_type);
        $data['local_shop'] = $this->client_model->get_local_shop_details($shop_type);
        $data['main_content']    = 'admin/pages/myshop/myshop_prodcut';
        $this->load->view('admin/inc/template_client', $data);  
    }

    public function add_myshop_product($shop_type, $category_id=0){
        $data['shop_type'] = $shop_type;
        $data['category_id'] = $category_id;
        $data['shop_category'] = $this->client_model->get_shop_category_for_product($shop_type);
        $data['main_content']    = 'admin/pages/myshop/myshop_prodcut_add';
        $this->load->view('admin/inc/template_client', $data);  
    }

    public function get_sub_categorybyid(){
        $category = $_POST['category'];
        $shop_type = $_POST['shop_type'];
        $result = $this->client_model->get_sub_categorybycat_id($category, $shop_type);
        echo json_encode($result);
    }

    public function get_sub_sub_categorybyid(){
        $sub_cat = $_POST['sub_cat'];
        $shop_type = $_POST['shop_type'];
        $category = $_POST['category'];
        $result = $this->client_model->get_sub_sub_categorybycat_id($sub_cat, $category, $shop_type);
        echo json_encode($result);
    }

    public function insert_myshop_product_form_data(){
        $input = $this->input->post();
        echo $this->client_model->insert_myshop_product_form_data_detaiils($input);
    }

    public function my_shop_product_status_udpate(){
        $stngId = $_POST['stngId'];
        $value = $_POST['value'];
        echo $this->client_model->my_shop_product_status_udpate_by_id($stngId,$value);
    }
    
    public function my_shop_product_view($shop_product_id){
        $data['view_product'] = $this->client_model->get_my_shop_product_details($shop_product_id);
        // echo "<pre>"; print_r($data['view_product']); die();
        $data['main_content']    = 'admin/pages/myshop/myshop_product_view';
        $this->load->view('admin/inc/template_client', $data);  
    }

    public function insert_local_shop_category(){
        $local_shop_category = $_POST['local_shop_category'];
        echo $this->client_model->insert_local_shop_category_();
    }

    public function create_shop_categoryby_client($shop_type,$client_shop_id, $client_category_id){
        $data['shop_type'] = $shop_type;
        $data['client_shop_id'] = $client_shop_id;
        $data['client_category_id'] = $client_category_id;
        $data['client_cat'] = $this->client_model->get_client_category_details($client_category_id);
        $data['client_sub_cat'] = $this->client_model->get_client_sub_category_details($client_shop_id);
        $data['main_content']    = 'admin/pages/myshop/client_myshop_sub_category_create';
        $this->load->view('admin/inc/template_client', $data);  
    }

    public function insert_sub_cateogyr_by_clienty($group_name){
        $input = $this->input->post();
        $result = $this->client_model->insert_my_shop_sub_category_by_client($group_name);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/create_shop_categoryby_client/'.$input['shop_type'].'/'.$input['client_shop_id'].'/'.$input['client_category_id']);
    }

    public function union_category_get_by_type(){
        $union_type = $_POST['union_type'];
        $result = $this->admin_model->union_category_get_by_type($union_type);
        echo json_encode($result);
    }

    public function change_password_client(){
        $data['main_content'] = 'admin/pages/change_password';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function client_union_news(){
        $data['client_union_news'] = $this->client_model->get_client_union_news();
        $data['main_content']    = 'admin/pages/client/union_news';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_union_news(){
        $result = $this->client_model->upload_client_union_news_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_news');
    }

    public function delete_union_news($id){
        $result = $this->client_model->delete_union_newsby_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_news');
    }

    public function client_union_notice(){
        $data['client_union_notice'] = $this->client_model->get_client_union_notice();
        $data['main_content']    = 'admin/pages/client/union_notice';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_union_notice(){
        $result = $this->client_model->upload_client_union_notice_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_notice');
    }

    public function delete_union_notice($id){
        $result = $this->client_model->delete_union_noticeby_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_notice');
    }

    public function client_union_invitation(){
        $data['client_union_invitation'] = $this->client_model->get_client_union_invitation();
        $data['main_content']    = 'admin/pages/client/union_invitation';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_union_invitation(){
        $result = $this->client_model->upload_client_union_invitation_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_invitation');
    }

    public function delete_union_invitation($id){
        $result = $this->client_model->delete_union_invitationby_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_invitation');
    }

    public function client_union_press_note(){
        $data['client_union_press_note'] = $this->client_model->get_client_union_press_note();
        $data['main_content']    = 'admin/pages/client/union_press_note';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_union_press_note(){
        $result = $this->client_model->upload_client_union_press_note_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_press_note');
    }

    public function delete_union_press_note($id){
        $result = $this->client_model->delete_union_pressnoteby_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_union_press_note');
    }

    public function client_union_meeting(){
        $data['main_content']    = 'admin/pages/client/union_meeting';
        $this->load->view('admin/inc/template_client', $data); 
    }
    public function client_union_live_streaming(){
        $data['main_content']    = 'admin/pages/client/union_live_streaming';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function client_union_sms_service(){
        $data['main_content']    = 'admin/pages/client/union_sms_service';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function client_union_email_service(){
        $data['main_content']    = 'admin/pages/client/union_email_service';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function id_cards_view(){
        $data['member_view'] = $this->admin_model->get_members_list();
        $data['main_content']    = 'admin/pages/client/id_cards_view';
        $this->load->view('admin/inc/template_client', $data); 
    }
    public function view_id_card_by_id(){
        $id = $_POST['id'];
        $result = $this->admin_model->get_id_card_view_html($id);
        echo json_encode($result);
    }

    public function enabled_for_public(){
        $data['client_name'] = $this->client_model->get_client_name_details();
        $data['main_content'] = 'admin/pages/client/enabled_for_public';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function partner_enabled_for_application_for_public_update(){
        $stngId = $_POST['stngId'];
        $value = $_POST['value'];
        echo $this->client_model->partner_enabled_for_application_for_public_updatebyId($stngId,$value);
    }

    public function member_validity(){
        $data['member_validity'] = $this->client_model->get_member_validity_by_client_id();
        $data['main_content'] = 'admin/pages/client/member_validity';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function union_application(){
        $data['main_content'] = 'admin/pages/client/union_application';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function get_invite_data(){
        $data['invate_data'] = $this->client_model->get_invate_user_data();
        $data['main_content'] = 'admin/pages/client/union_application_invite';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function get_received_data(){
        $data['application'] = $this->client_model->get_member_received_data();
        $data['main_content'] = 'admin/pages/client/union_application_received';
        $this->load->view('admin/inc/template_client', $data); 
    }
    public function insert_unions_invate_application_data(){
        $result = $this->client_model->insert_unions_invate_application_data();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/get_invite_data');
    }

    public function check_mobile_no_availability_union_member(){
        $mobileNumber = $_POST['mobileNumber'];
        if($this->client_model->check_mobile_no_availability_union_member($mobileNumber))
            echo 'exists';
        else
            echo 'not found';
    }

    public function receive_application_view_by_union($id){
        $data['member_edit'] = $this->admin_model->get_member_edit_form_by_id($id);
        // echo "<pre>"; print_r($data['member_edit']); die();
        $data['enabled_fields'] = $this->admin_model->enabled_fields_member_form();
        $data['required_fields'] = $this->admin_model->get_union_form_required_fields();
        $data['member_view'] = $this->admin_model->get_members_list();
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection();
        $data['main_content'] = 'admin/pages/recevied_member_registration_edit';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function insert_validity_member(){
        $input = $this->input->post();
        $result = $this->client_model->insert_validity_member_data();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/member_validity');
    }

    public function check_memberid_unique(){
        $member_id_number = $_POST['member_id_number'];
        if($this->client_model->check_memberid_unique_id($member_id_number))
            echo 'exists';
        else
         echo 'not found';
    }

    public function deleted_invate_member($id){
        $result = $this->client_model->deleted_invate_memberbyid($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Deleted Successfully");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/get_invite_data');
    }

    // About us
    public function client_about_us(){
        $data['client_about_us'] = $this->client_model->get_client_about_details();
        $data['main_content']    = 'admin/pages/client/client_about';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_about(){
        $file = $_FILES['about_path'];
        $document = $this->s3FileUpload_profile($file);
        $result = $this->client_model->insert_client_about($document);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_about_us');
    }

    public function delete_about($id){
        $result = $this->client_model->delete_about_by_client_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully delete");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_about_us');
    }

    public function download_client_about($id){
        $link = $this->client_model->download_client_objectivies($id);
        // $url = $this->filemanager->getFilePath($link->document_path);

        $link = $link->objectivies_path;
        $file = explode("/", $link);
        $file_name1 = $link->objectivies_name.'_'.$link->objectivies_name;
        $ext = explode(".", $file[count($file)-1])[1];
        $ext = ($ext == '')?'png':$ext;
        $fname = $file_name1 .'.'. $ext;
        $url = $this->filemanager->getFilePath($link);
        $data = file_get_contents($url);
        $this->load->helper('download');
        force_download($fname, $data, TRUE);
    }

    public function check_mobile_number_unique(){
        $mobile_number = $_POST['mobile_number'];
        if($this->client_model->check_mobile_number_unique_all($mobile_number))
            echo 'exists';
        else
            echo 'not found';
    }

    public function mygod_profile_details(){
        $data['country_flag'] = $this->admin_model->get_country_flag();
        $data['client_mygod'] = $this->client_model->get_client_mygod_details();
        $data['main_content']    = 'admin/pages/client/god_profile_details';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_client_mygod_details(){
        $result = $this->client_model->upload_client_mygod_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/mygod_profile_details');
    }

    public function mygodsocial_link(){
        $data['social_link'] = $this->client_model->mygod_get_social_link();
        $data['main_content']    = 'admin/pages/client/mygod_social_link';
        $this->load->view('admin/inc/template_client', $data);
    }
    public function mygod_upload_social_link(){
        $socialurl = $_POST['socialurl'];
        $uploadId = $_POST['uploadId'];
        $type = $_POST['type'];
        echo $this->client_model->mygod_save_social_link($socialurl, $uploadId, $type);
    }

    public function mygodlivelink(){
        $data['live_link'] = $this->client_model->mygod_get_live_link();
        $data['main_content']    = 'admin/pages/client/mygod_live_link';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_mygodlivelink(){
        $url = $_POST['livelink'];
        echo $this->client_model->upload_mygodlivelink_data($url);
    }

    public function mygod_admin_details(){
        $data['user_details'] = $this->client_model->user_admin_details_data();
        $data['mygod_admin_details'] = $this->client_model->mygod_admin_details_data();
        $data['main_content']    = 'admin/pages/client/mygod_admin_details';
        $this->load->view('admin/inc/template_client', $data);
    }

    public function upload_mygod_admin_details(){
        $result = $this->client_model->upload_mygod_admin_details();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/mygod_admin_details');
    }

    public function client_god_timings(){
        $data['morning_god_timing'] = $this->client_model->get_client_god_timings('Morning');
        $data['evening_god_timing'] = $this->client_model->get_client_god_timings('Evening');
        $data['main_content']    = 'admin/pages/media/god_timings';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function insert_media_god_timings_morning(){
        $result = $this->client_model->insert_media_god_timings_morning();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_timings');
    }

    public function insert_media_god_timings_evening(){
        $result = $this->client_model->insert_media_god_timings_evening();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_timings');
    }

    public function client_god_desciption($urlType){
        $type = urlencode($urlType);
        $a = str_replace("%2520", " ", $type);
        $b = str_replace("_", "/", $a);
        $data['type'] = $b;
        $data['urlType'] = $urlType;
        $data['god_desciption'] = $this->client_model->get_client_god_desciption($data['type']);

        $data['main_content']    = 'admin/pages/media/god_desciption';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function insert_client_god_desciption(){
        $input = $this->input->post();
        $result = $this->client_model->insert_client_god_desciption($input);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_desciption/'.$input['page_type_url']);
    }

    public function client_god_poja_timings(){
        $data['main_content']    = 'admin/pages/media/god_pooja_timings';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function client_god_notice_board(){
         $data['main_content']    = 'admin/pages/media/god_notice';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function client_god_today_photo(){
        $data['today_god_photo'] = $this->client_model->get_client_god_today_photo();
        $data['old_god_photo'] = $this->client_model->get_client_god_old_photo();
        // echo "<pre>"; print_r($data['today_god_photo']);die();
        $data['main_content']    = 'admin/pages/media/god_today_photo';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function client_god_event(){
        $data['god_event'] = $this->client_model->get_client_god_event();
        $data['main_content']    = 'admin/pages/media/god_event';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function upload_god_today_photo(){
        $path = $_POST['path'];
        echo $this->client_model->upload_god_today_photo($path);
       
    }

    public function client_god_gallery(){
        $gallery_info['image_count'] = 0;
        // $group_id = $this->ion_auth->user()->row()->group_id;
        $gallery_info = $this->client_model->god_get_all_galleries();
        $data['gallery_info'] = $gallery_info;
        $data['main_content']    = 'admin/pages/media/god_galleries/index';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function god_create_gallery(){
        $data['main_content']    = 'admin/pages/media/god_galleries/create_gallery';
        $this->load->view('admin/inc/template_client',$data);
    }

    public function god_gallery_created(){
        if (!(isset($_POST['gallery_name']))) {
            redirect('admin_controller/client_god_gallery');
        }
        $data['gallery'] = $this->client_model->god_addGallery();
        redirect('client_controller/god_view_gallery/' . $data['gallery']['id']);
    }

    public function god_view_gallery($gallery_id){
        // $group_id = $this->ion_auth->user()->row()->group_id;
        $data['gallery_info'] = $this->client_model->god_get_gallery_info($gallery_id);
        $data['image_info'] = $this->client_model->god_get_images_info($gallery_id);
        $data['main_content'] = 'admin/pages/media/god_galleries/view_gallery';
        $this->load->view('admin/inc/template_client',$data);
    }

    public function god_upload_multiple_images(){
        $files = $_FILES['file_name'];
        $input = $this->input->post();
        $gid = $input['gal_id'];
        $img_desc = $input['desc'];
        $failed = 0;
        $user_id = $this->ion_auth->user()->row()->id; 
        // echo '<pre>'; print_r($files); die();
        foreach($files['tmp_name'] as $i => $file_name) {
            $file = array(
                'tmp_name' => $file_name,
                'name' => 'img'.$i.'.png'
            );
            $img = $this->s3FileUpload($file, $gid);
            if($img['file_name'] == null || $img['file_name'] == ''){
                $failed++;
            }else {
                $save_images[] = array(
                    'gallery_id' => $gid,
                    'image_name' => $img['file_name'],
                    'image_description' => $img_desc,
                    'user_id'=>$user_id
                );
            }
        }
        if(!empty($save_images)) {
            $this->client_model->god_save_gallery_images($save_images);
        }

        echo $failed;
    }

    public function s3FileUpload($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'god_gallery');
        //print_r($uploadResult); die();
        return $uploadResult;
    }


    public function god_delete_image(){
        $image_id = $_POST['image_id'];
        $result = $this->client_model->god_delete_image_db($image_id);
        echo $result;
    }

    public function god_delete_gallery_by_id(){
        $gId = $_POST['gId'];
        $result = $this->client_model->delete_album_db($gId);
        echo $result;
    }

    public function client_god_photos(){
        $data['god_photo'] = $this->client_model->get_client_god_photo();
        $data['main_content']    = 'admin/pages/media/god_photo';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_event(){
        $file = $_FILES['god_event_file'];
        $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
        $picture = array('tmp_name' => $min_size, 'name' => $min_size);
        $imagepathurl = $this->s3FileUpload_client($picture);

        $result = $this->client_model->insert_god_event_data($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_event');
    }

    public function edit_god_event($id){
        $data['god_event_edit'] = $this->client_model->get_client_god_event_by_id($id);
        $data['god_event'] = $this->client_model->get_client_god_event();
        $data['main_content']    = 'admin/pages/media/god_event';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_eventbyid($id){
        $file = $_FILES['god_event_file'];
        $imagepathurl = '';
        if (!empty($file)) {
            $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
            $picture = array('tmp_name' => $min_size, 'name' => $min_size);
            $imagepathurl = $this->s3FileUpload_client($picture);
        }

        $result = $this->client_model->update_god_event_data_by_id($imagepathurl, $id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_event');
    }

    public function client_god_must_visit(){
        $data['god_must_visit'] = $this->client_model->get_client_god_must_visit();
        $data['main_content']    = 'admin/pages/media/god_must_visit';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_must_visit(){
        $file = $_FILES['god_must_visit_file'];
        $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
        $picture = array('tmp_name' => $min_size, 'name' => $min_size);
        $imagepathurl = $this->s3FileUpload_client($picture);

        $result = $this->client_model->insert_god_must_visit_data($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_must_visit');
    }

    public function edit_god_must_visit($id){
        $data['god_must_visit_edit'] = $this->client_model->get_client_god_must_visit_by_id($id);
        $data['god_must_visit'] = $this->client_model->get_client_god_must_visit();
        $data['main_content']    = 'admin/pages/media/god_must_visit';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_must_byid($id){
        $file = $_FILES['god_must_visit_file'];
        $imagepathurl = '';
        if (!empty($file)) {
            $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
            $picture = array('tmp_name' => $min_size, 'name' => $min_size);
            $imagepathurl = $this->s3FileUpload_client($picture);
        }

        $result = $this->client_model->update_god_must_visit_data_by_id($imagepathurl, $id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_must_visit');
    }

    public function client_god_nearest_places(){
        $data['god_nearest_places'] = $this->client_model->get_client_god_nearest_place();
        $data['main_content']    = 'admin/pages/media/god_nearest_places';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_nearest_places(){
        $file = $_FILES['god_nearest_place_file'];
        $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
        $picture = array('tmp_name' => $min_size, 'name' => $min_size);
        $imagepathurl = $this->s3FileUpload_client($picture);

        $result = $this->client_model->insert_god_nearest_place_data($imagepathurl);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_nearest_places');
    }

    public function edit_god_nearest_places($id){
        $data['god_nearest_places_edit'] = $this->client_model->get_client_god_nearest_place_by_id($id);
        $data['god_nearest_places'] = $this->client_model->get_client_god_nearest_place();
        $data['main_content']    = 'admin/pages/media/god_nearest_places';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_nearest_places_byid($id){
        $file = $_FILES['god_nearest_place_file'];
        $imagepathurl = '';
        if (!empty($file)) {
            $min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
            $picture = array('tmp_name' => $min_size, 'name' => $min_size);
            $imagepathurl = $this->s3FileUpload_client($picture);
        }

        $result = $this->client_model->update_god_nearest_place_data_by_id($imagepathurl, $id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_nearest_places');
    }


    // how to reach
    public function client_god_how_to_reach(){
        $data['god_how_to_reach'] = $this->client_model->get_client_god_how_to_reach();
        $data['main_content']    = 'admin/pages/media/god_how_to_reach';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_how_to_reach(){
        $result = $this->client_model->insert_god_how_to_reach_data();
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_how_to_reach');
    }

    public function edit_god_how_to_reach($id){
        $data['god_how_to_reach_edit'] = $this->client_model->get_client_god_how_to_reach_id($id);
        $data['god_how_to_reach'] = $this->client_model->get_client_god_how_to_reach();
        $data['main_content']    = 'admin/pages/media/god_how_to_reach';
        $this->load->view('admin/inc/template_client', $data); 
    }

    public function update_god_how_to_reach_byid($id){
        $result = $this->client_model->update_god_how_to_reach_by_id($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_how_to_reach');
    }

    public function upload_god_photo(){
        $files = $_FILES['god_images'];
        $user_id = $this->ion_auth->user()->row()->id;   
        foreach($files['tmp_name'] as $i => $file_name) {
            $file = array(
                'tmp_name' => $file_name,
                'name' => 'img'.$i.'.png'
            );
            $img = $this->s3FileUpload($file);
            if($img['file_name'] == null || $img['file_name'] == ''){
                $failed++;
            }else {
                $save_images[] = array(
                    'image'=>$img['file_name'],
                    'user_id'=>$user_id
                );
            }
        }
        $result = $this->client_model->upload_god_photo($save_images);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_photos');
       
    }

    public function deletegodphotobyid($id){
        $result = $this->client_model->deletegodphotobyid($id);
        if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('client_controller/client_god_photos'); 
    }
}