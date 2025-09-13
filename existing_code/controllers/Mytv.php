<?php

class Mytv extends CI_Controller {
               
	function __construct(){
 		parent::__construct();
	 	$this->load->model('needy_model');
	 	$this->load->model('home_model'); 
        $this->load->model('admin_model'); 
    	$this->load->model('Mygroup_model');
	  	$this->load->model('country_model');
	  	$this->load->library('filemanager');
	}

	public function public_notice($groupname, $mytvType){
		$userId = $this->ion_auth->user()->row();
    	$data['isLoggedin'] = 0;
    	if (!empty($userId)) {
    		$data['isLoggedin'] = 1;
    	}
    	$data['country'] = $this->country_model->get_country_list();
	 	$data['language'] = $this->admin_model->get_language_details();
		$data['groupname'] = $groupname;
		$data['mytvType'] = $mytvType;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
    	$group = $this->home_model->get_group_name_detailsbyname($groupname);
    	if (!empty($group)) {
    		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
    		$data['header_sliders'] = $this->home_model->get_group_header_adsbyId($group->id);
    		$data['popupads'] = $this->home_model->get_poupads_groupbyId($group->id);
    	}else{
    		$data['logo'] = $this->admin_model->get_logo_image();
	 		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	 		$data['popupads'] = $this->home_model->get_poupads();
    	}
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	$data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/mytv_public';
	 	}else{
	 		$data['main_content']    = 'home/mytv_public';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function public_form($groupname, $mytvType){
		$userId = $this->ion_auth->user()->row();
    	$data['isLoggedin'] = 0;
    	if (!empty($userId)) {
    		$data['isLoggedin'] = 1;
    	}
    	$data['country'] = $this->country_model->get_country_list();
	 	$data['language'] = $this->admin_model->get_language_details();
		$data['groupname'] = $groupname;
		$data['mytvType'] = $mytvType;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
    	$group = $this->home_model->get_group_name_detailsbyname($groupname);
    	if (!empty($group)) {
    		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
    		$data['header_sliders'] = $this->home_model->get_group_header_adsbyId($group->id);
    		$data['popupads'] = $this->home_model->get_poupads_groupbyId($group->id);
    	}else{
    		$data['logo'] = $this->admin_model->get_logo_image();
	 		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	 		$data['popupads'] = $this->home_model->get_poupads();
    	}
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	$data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/mytv_public_form';
	 	}else{
	 		$data['main_content']    = 'home/mytv_public_form';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function upload_video_publis_form(){
	 	$files = $_FILES['video_file'];
        $result = $this->s3FileUpload_folder($files,'mytv-video');
        echo json_encode($result);
	}

	public function upload_myshop_product(){
		$files = $_FILES['product_file'];
        $result = $this->s3FileUpload_folder($files,'myshop-product');
        echo json_encode($result);
	}

	public function s3FileUpload_folder($file, $folder){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], $folder);
        return $uploadResult;
    }

    public function upload_mytv_public(){
	 	$files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['missing_title'];
        $summernote = $input['description'];
        $country = $input['country'];
        $state = $input['state'];
        $district = $input['district'];

        $category = $input['category'];
        $language = $input['language'];
        $sender_show_button = $input['sender_status'];
        $videoPath = '';
        if ($category == 2) {
        	$videoPath = $input['videoPath'];
        }
    	
    	$img = [];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img[] = $this->s3FileUpload_folder($file,'mytv_image');
            }
        }
        $save_board = array(
            'title' => $title,
            'content' => $summernote,
            'country' => $country,
            'state' => $state,
            'district' => $district,
         	'status'=>'Created',
         	'user_id'=> $this->ion_auth->user()->row()->id,
         	'category'=> $category,
            'language'=> $language,
            'sender_show_button'=>$sender_show_button,
            'paid_amount' => (!isset($input['paid_amount']) || $input['paid_amount'] == '')? null : $input['paid_amount'],
            'type' =>$input['mytv_type']
        );
        if(!empty($save_board)) {
            $this->admin_model->save_mytv_public_form($save_board,'mytv_public', $img, $videoPath);
        }
        echo 1;
    }

    public function mtv_public_success($groupname, $mytvType){
		$userId = $this->ion_auth->user()->row();
    	$data['isLoggedin'] = 0;
    	if (!empty($userId)) {
    		$data['isLoggedin'] = 1;
    	}
    	$data['country'] = $this->country_model->get_country_list();
	 	$data['language'] = $this->admin_model->get_language_details();
		$data['groupname'] = $groupname;
		$data['mytvType'] = $mytvType;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
    	$group = $this->home_model->get_group_name_detailsbyname($groupname);
    	if (!empty($group)) {
    		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
    		$data['header_sliders'] = $this->home_model->get_group_header_adsbyId($group->id);
    		$data['popupads'] = $this->home_model->get_poupads_groupbyId($group->id);
    	}else{
    		$data['logo'] = $this->admin_model->get_logo_image();
	 		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	 		$data['popupads'] = $this->home_model->get_poupads();
    	}
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	$data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/mytv_public_form_success';
	 	}else{
	 		$data['main_content']    = 'home/mytv_public_form_success';
	 	}
        $this->load->view('front/template_group', $data);
    }

 	public function get_mytv_public_list(){
    	$notice_select_lang = $_POST['notice_select_lang'];
    	$selectValue = $_POST['selectValue'];
    	$result = $this->home_model->get_mytv_public_list_details($notice_select_lang, $selectValue);
	 	$missing_ids = array_chunk($result, 5);
        echo json_encode($missing_ids);
    }

    public function get_mytv_public_list_ids(){
    	$public_ids = $_POST['public_ids'];
    	$result = $this->home_model->get_mytv_public_list_ids_details($public_ids);
		foreach ($result as $key => $val) {
			if ($val->category == '1') {
    			$val->img = $this->filemanager->getFilePath($val->image);
			}else if($val->category == '2'){
				$val->img = base_url().'assets/img/video_file_icon.png';
			}else{
				$val->img = base_url().'assets/img/text_file_icon.png';
			}
    	}
    	echo json_encode($result);
    }
}