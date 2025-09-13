<?php

class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('home_model'); 
		$this->load->model('admin_model'); 
		$this->load->model('Mygroup_model');
		$this->load->library('filemanager');
		$this->load->model('needy_model');
		$this->load->model('franchise_model');
	}	

	// public function default(){
	// 	if ($this->mobile_detect->isMobile()) {
	// 		$this->load->view('home/default_mobile');
	// 	}else{
	// 		$this->load->view('home/default');
	// 	}
	// }

	public function index_old(){
		$data['groupname'] = 'Mygroup';
		$data['logo'] =  $this->admin_model->get_logo_image();

		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
		$data['popupads'] = $this->admin_model->get_poupads();
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['copy_right'] = $this->admin_model->get_copy_right();

		$this->session->set_userdata('logo', $data['logo']);
		$this->session->set_userdata('header_sliders', $data['header_sliders']);
		$this->session->set_userdata('popupads', $data['popupads']);
		$this->session->set_userdata('top_icon', $data['top_icon']);
		$this->session->set_userdata('total_apps', $data['total_apps']);
		$this->session->set_userdata('copy_right', $data['copy_right']);

		$data['navName'] = '';
		$navName = 'my-apps';
		$data['body_content'] = $this->admin_model->get_bodynav_icon_list($navName);

		$group = $this->home_model->get_group_name_detailsbyname('Mygroup');
		$data['group_details'] = $group;
		$data['social_link'] = $this->admin_model->get_social_link($group->id);
		$data['base_url'] = 1;
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'home/index_mobile';
		}else{
			$data['main_content']    = 'home/index';
		}
		$this->load->view('front/template', $data);

	}

	public function index(){
			
		$data['groupname'] = 'Mygroup';
		$data['logo'] = $this->admin_model->get_logo_image();

	 	// $data['top_header_ads'] = $this->home_model->get_top_header_ads_bycurrentlocation($groupname, $needy_type);

		// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
		// ;
		
		// $data['popupads'] = $this->admin_model->get_poupads();
		
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	 	// echo "<pre>"; print_r($data['top_icon']);die();
		$data['navName'] = '';
		$navName = 'my-apps';
		$data['body_content'] = $this->admin_model->get_bodynav_icon_list($navName);

		// $data['total_apps'] = $this->admin_model->get_all_apsname();
		// echo "<pre>"; print_r($data['total_apps']);die();
		$group = $this->home_model->get_group_name_detailsbyname('Mygroup');
		$data['group_details'] = $group;
		$data['social_link'] = $this->admin_model->get_social_link($group->id);
		// echo "<pre>"; print_r($data['social_link']);die();
		$data['copy_right'] = $this->admin_model->get_copy_right();
		$data['about_us'] = $this->admin_model->get_about_all(0);
		$data['main_ads'] = $this->admin_model->main_ads_group();
		$data['newsroom'] = $this->admin_model->get_newsroom_latest_data();
		$data['awards'] = $this->admin_model->get_awards_latest_data();
		$data['event'] = $this->admin_model->get_events_latest_data();
		$data['gallery'] = $this->admin_model->get_gallery_latest_data();
		$data['testimonials'] = $this->admin_model->get_testimonials_data();
		$data['base_url'] = 1;
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'home/index_mobile';
		}else{
			$data['main_content']    = 'home/index';
		}
		$this->load->view('front/template', $data);
	}

	public function change_group($groupname){

		switch ($groupname) {
			case 'Mychat':
			redirect('home/category/'.$groupname.'/'.'Mychat');
			break;
			case 'Mydiary':
			redirect('home/category/'.$groupname.'/'.'Qk Note');
			break;
			case 'Mymedia':
			redirect('home/category/'.$groupname.'/'.'Tv');
			break;
			case 'Myjoy':
			redirect('home/category/'.$groupname.'/'.'Myvideo');
			break;
			case 'Mybank':
			redirect('home/category/'.$groupname.'/'.'Mypay');
			break;
			case 'Myshop':
			redirect('myshop/myshop_category/'.$groupname.'/'.'Shop');
			break;
			case 'Myfriend':
			redirect('home/category/'.$groupname.'/'.'Myfriend');
			break;
			case 'Myunions':
			redirect('myunions/myunions_news/'.$groupname.'/'.'News');
			break;
			case 'Mybiz':
			redirect('home/category/'.$groupname.'/'.'Production');
			break;
			case 'Mytv':
			redirect('home/category/'.$groupname.'/'.'Mytv');
			break;
			case 'Myneedy':
			redirect('home/category/'.$groupname.'/'.'Doorstep');
			break;
			default:
            redirect('home/my_company_page/'.$groupname.'/'.'my-company');
			break;
		}

		if ($groupname == 'Myads') {
			redirect('myads/index/'.$groupname);
		}
		$data['groupname'] = $groupname;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();    		
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
	 	// $data['top_header_ads'] = $this->home_model->get_top_header_ads_bycurrentlocation($groupname, $needy_type = '');
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
			$data['header_sliders'] = $this->home_model->get_group_header_adsbyId($group->id);
			$data['popupads'] = $this->home_model->get_poupads_groupbyId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
			$data['header_sliders'] = $this->admin_model->get_header_slider_image();
			$data['popupads'] = $this->home_model->get_poupads();
		}
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'home/group_index_mobile';
		}else{
			$data['main_content']    = 'home/group_index';
		}
		$data['group_details'] = $group;
		$this->load->view('front/template_group', $data);
	}

	public function change_nav($navName){
		$data['base_url'] = 1;
		$data['navName'] = $navName;
		$data['logo'] = $this->admin_model->get_logo_image();
		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$data['body_content'] = $this->admin_model->get_bodynav_icon_list($navName);
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$group = $this->home_model->get_group_name_detailsbyname('Mygroup');
		$data['group_details'] = $group;
		$data['social_link'] = $this->admin_model->get_social_link($group->id);
		$data['copy_right'] = $this->admin_model->get_copy_right();
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'home/index_mobile';
		}else{
			$data['main_content']    = 'home/index';
		}
		$this->load->view('front/template', $data);
	}

	public function post_icons_names(){
		$result = $this->admin_model->get_sub_logo_details();
		$success = [];
		if ($result) {
			$success = ['status' => '1', 'message' => 'Successull', 'data' => $result];
		}else{ 
			$success = ['status' => '0', 'message' => 'error', 'data' =>[]];
		}
		echo json_encode($success);
	}

	public function get_data_into_table(){

		$data = (array) json_decode(file_get_contents('php://input'), TRUE);

	    // $result = $this->admin_model->get_sub_logo_details();
	    // // $table = $data['table_name'];
	    // // $groupName = $data['group_name'];
		$table = 'create';
		$groupName = 'Mydairy';

		$result = $this->Mygroup_model->get_data_content($table, $groupName);
		$success = [];
		if ($result) {
			$success = ['status' => '1', 'message' => 'Successull', 'data' => $result];
		}else{ 
			$success = ['status' => '0', 'message' => 'error', 'data' =>[]];
		}    
		echo json_encode($success);
	}

	public function profile(){
		$data['logo'] = $this->admin_model->get_logo_image();
		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['social_link'] = $this->admin_model->get_social_link();
		$data['copy_right'] = $this->admin_model->get_copy_right();
		$data['navName'] = '';
		$data['main_content'] = 'home/profile';
		$this->load->view('front/template', $data);
	}

	public function register_users(){
		$data['logo'] = $this->admin_model->get_logo_image();
		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$data['navName'] = '';
		$data['country_flag'] = $this->admin_model->get_country_flag();
		$data['education'] = $this->admin_model->get_education_list();
		$data['profession'] = $this->admin_model->get_profession_list();
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['social_link'] = $this->admin_model->get_social_link();
		$data['copy_right'] = $this->admin_model->get_copy_right();
		if ($this->mobile_detect->isMobile()) {
			$data['main_content'] = 'home/register_user_form_mobile';
		}else{
			$data['main_content'] = 'home/register_user_form';
		}

		$this->load->view('front/template', $data);
	}

	public function change_country_code(){
    	// $country_id = $_POST['country_id'];
		$country_id = 1;
		$result = $this->admin_model->get_country_code_by_id($country_id);
		echo json_encode($result);
	}

	public function get_disctrict_by_stateId(){
		$state_id = $_POST['state_id'];
    	// $state_id =1;
		$result = $this->admin_model->get_details_disctrict_by_stateId($state_id);
		echo json_encode($result);
	}
	public function user_register_submit(){

		$input = $this->input->post();
		$username =  $input['mobile_number'];
		$password =  $input['password'];
		$email = $input['email'];
		$additional_data = array(
			'first_name' => $input['first_name'],
			'phone' => $input['mobile_number'],
			'display_name' => $input['display_name'],
			'alter_number' => $input['mobile_number_alter'],
		);
	    $group = array('2'); // Sets user to member.
	    $this->db->trans_start();
	    // $result = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
	    $result = 1;
	    if ($result) {
	    	$this->home_model->insert_user_registration_details($result);
	    }
	    $this->db->trans_complete();
	    if ($this->db->trans_status()) {
	    	redirect('home/success/');
	    }else{
	    	redirect('home/failed');
	    }

	}

	public function user_register_submit_popup(){

		$input = $this->input->post();
		$username =  $input['mobile_number'];
		$password =  $input['password'];
		$email = $input['email'];
		$additional_data = array(
			'first_name' => $input['first_name'],
			'phone' => $input['mobile_number'],
			'display_name' => $input['display_name'],
			'alter_number' => $input['mobile_number_alter'],
		);
	    $group = array('2'); // Sets user to member.
	    $result = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
	    if ($result) {
	    	$res = $this->home_model->insert_user_registration_details($result);
	    	if ($res) {
	    		if ($this->ion_auth->login($input['mobile_number'], $input['password'], 1)) {
	    			echo 1;
	    		} else {
	    			echo 0;
	    		}
	    	}
	    }else{
	    	echo 0;
	    }
	}

	public function first_step_register_submit_popup(){
		$input = $this->input->post();
		$username =  $input['mobile_number'];
		$password =  $input['password'];
		$email = '';
		$additional_data = array(
			'first_name' => $input['first_name'],
			'phone' => $input['mobile_number'],	                
		);
	    $group = array('2'); // Sets user to member.
	    $checkuserName = $this->ion_auth->username_check($username);
	    if ($checkuserName) {
	    	echo 'exits';
	    }else{
	    	$result = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
	    	if ($result) {
	    		echo $result;
	    	}else{
	    		echo 0;
	    	}
	    }
	}

	public function user_update_register_submit_popup(){
		$user_id = $this->input->post('register_user_id');
		$register_username = $this->input->post('register_username');
		$register_password = $this->input->post('register_password');

		$display_name = $this->input->post('display_name');
		$alter_number = $this->input->post('alter_number');
		$email = $this->input->post('email');

		$additional_data = array(
			'display_name' => $display_name,
			'alter_number' => $alter_number,	                
			'email' => $email,
			'active'=>1              
		);

		$res = $this->home_model->insert_user_registration_details($user_id);
		if ($res) {
			$this->db->where('id',$user_id);
			$this->db->update('users',$additional_data);
			if ($this->ion_auth->login($register_username, $register_password, 1)) {
				echo 1;
			} else {
				echo 0;
			}
		}else{
			echo 0;
		}
	}


	public function login_profile_popup(){
		$input = $this->input->post();
		$result = $this->ion_auth->user_login($input['identity'], $input['password'], 1);
		if ($result =='success') {
			echo 'success';
		}else if($result != 0){
			echo $result;
		}else{
			echo 0;
		}

	}
	public function success(){

		$data['logo'] = $this->admin_model->get_logo_image();
		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['social_link'] = $this->admin_model->get_social_link();
		$data['copy_right'] = $this->admin_model->get_copy_right();
		$data['navName'] = '';
		$data['main_content'] = 'home/success';
		$this->load->view('front/template', $data);
	}

	public function failed(){
		$data['logo'] = $this->admin_model->get_logo_image();
		$data['header_sliders'] = $this->admin_model->get_header_slider_image();
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['social_link'] = $this->admin_model->get_social_link();
		$data['copy_right'] = $this->admin_model->get_copy_right();
		$data['navName'] = '';
		$data['main_content'] = 'home/failed';
		$this->load->view('front/template', $data);
	}

	public function check_user_active(){
		$mobileNumber = $_POST['mobileNumber'];
		$password = $_POST['password'];

		$this->db->where('username',$mobileNumber);
		$this->db->where('active',1);
		$query = $this->db->get('users')->row();
		if (!empty($query)) {
			if ($this->ion_auth->hash_password_db($query->id, $password) == TRUE)
			{
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
		
	}

	public function profile_login(){
		$input = $this->input->post();
		$identity = $input['identity'];
		$password = $input['password'];

		if(isset($input['identity']))
            $remember = TRUE; // remember the user
    else
            $remember = FALSE; // remember the user

    if($this->ion_auth->login($identity, $password, $remember)){

    	$data['logo'] = $this->admin_model->get_logo_image();
    	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
    	$data['navName'] = '';
    	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
    	redirect('home');
    }
    else{
    	$this->session->set_flashdata('flashError', 'In-correct username or Password .');
    	$refering_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ;
    	$this->session->set_userdata('url', $refering_url);
    	$url = $this->session->userdata('url');
    	redirect($url, 'refresh');   
    }
}

public function tnc_view($groupname){
	$data['navName'] = '';
	$data['groupname'] = $groupname;
	$group_id = $this->ion_auth->user()->row()->group_id;
	if ($groupname == 'Mygroup') {
		$group_id = '0';
	}
	$data['logo'] = $this->admin_model->get_logo_image();
	// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
	// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['tnc'] = $this->admin_model->get_tnc_details($group_id);
	$data['social_link'] = $this->admin_model->get_social_link($group_id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['group_details'] = $group;
	$data['main_content'] = 'home/tnc_view';
	$this->load->view('front/template',$data);	
}	

public function pnp_view($groupname){
	$data['navName'] = '';
	$data['groupname'] = $groupname;
	$group_id = $this->ion_auth->user()->row()->group_id;
	if ($groupname == 'Mygroup') {
		$group_id = '0';
	}
	$data['logo'] = $this->admin_model->get_logo_image();
	// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
	// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['pnp'] = $this->admin_model->get_pnp_details($group_id);
	$data['social_link'] = $this->admin_model->get_social_link($group_id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['group_details'] = $group;
	$data['main_content'] = 'home/pnp_view';
	$this->load->view('front/template',$data);	
}

public function profile_pic_update(){
	$input = $_POST;
	$file = $_FILES['file'];
	$min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
	$picture = array('tmp_name' => $min_size, 'name' => $min_size);
	$sResigedPhoto = $this->s3FileUpload_profile($picture);
	echo $this->home_model->updateProfilePhoto($input['id'], $sResigedPhoto);	
}

public function profile_pic_update1(){
	$input = $_POST;
	$file = $_FILES['file'];
	$min_size = $this->_resize_image($file['tmp_name'], 200, $file['type']);
	$picture = array('tmp_name' => $min_size, 'name' => $min_size);
	$sResigedPhoto = $this->s3FileUpload_profile($picture);
	echo $this->home_model->updateProfilePhoto($input['id'], $sResigedPhoto);	
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
public function s3FileUpload_profile($file){
	if ($file['tmp_name'] == '' || $file['name'] == '') {
		return ['status' => 'empty', 'file_name' => ''];
	}
	$uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'profile');
        //print_r($uploadResult); die();
	return $uploadResult;
}

public function s3FileUpload_resume($file){
	if ($file['tmp_name'] == '' || $file['name'] == '') {
		return ['status' => 'empty', 'file_name' => ''];
	}
	$uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'apply_now');
        //print_r($uploadResult); die();
	return $uploadResult;
}

public function logout_desktop(){
	$result = $this->ion_auth->logout();
	if ($result) {
		redirect('home');
	}else{
		redirect('home');
	}
}

public function logout(){
	$result = $this->ion_auth->logout();
	if ($result) {
		echo 1;
	}else{
		echo 0;
	}
}

public function logout_change_password($groupname){
	$this->ion_auth->logout();
	redirect('home/success_change_password/'.$groupname);
}

public function success_change_password($groupname){
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$user = $this->ion_auth->user()->row();
	if (!empty($user)) {
		$data['user_feedback'] = $this->admin_model->get_in_feedback($user->id);
	}else{
		redirect('home');
	}
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['group_details'] = $group;
	$data['main_content'] = 'home/success_changepassword';
	$this->load->view('front/template_without_footer',$data);
}

public function edit_profile(){
	$data['navName'] = '';
	$user_id = $this->input->post('user_id');
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['country_flag'] = $this->admin_model->get_country_flag();
	$data['education'] = $this->admin_model->get_education_list();
	$data['profession'] = $this->admin_model->get_profession_list();
	$data['profile_edit'] = $this->home_model->profile_edit_by_id($user_id);
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link();
	$data['copy_right'] = $this->admin_model->get_copy_right();
	 	// echo "<pre>"; print_r($data['profile_edit']); die();
	$data['main_content'] = 'home/register_user_form_edit';
	$this->load->view('front/template', $data);
}


public function edit_profile_mobile(){
	$user_id =$_POST['user_id'];
	$result  = $this->home_model->profile_edit_by_id($user_id);
	$country_flag = $this->admin_model->get_country_flag();
	$education = $this->admin_model->get_education_list();
	$profession = $this->admin_model->get_profession_list();
	echo json_encode(array('profile'=>$result, 'country_flag'=>$country_flag,'education'=>$education,'profession'=>$profession));
}

public function profile_login_modal(){
	$country_flag = $this->admin_model->get_country_flag();
	$education = $this->admin_model->get_education_list();
	$profession = $this->admin_model->get_profession_list();
	echo json_encode(array('country_flag'=>$country_flag,'education'=>$education,'profession'=>$profession));
}

public function user_register_update(){

	$input = $this->input->post();

	$email = $input['email'];
	$additional_data = array(
		'first_name' => $input['first_name'],
		'display_name' => $input['display_name'],
		'alter_number' => $input['mobile_number_alter'],
	);
	$result = $this->ion_auth->update($input['user_id'], $additional_data);
	if ($result) {
		$res = $this->home_model->update_user_registration_details($input['user_id'], $input);
		if ($res) {
			echo 1;
		}
	}else{
		echo 0;
	}
}

public function update_success(){
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['contact'] = $this->admin_model->get_contact_details();
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link();
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['main_content'] = 'home/update_success';
	$this->load->view('front/template',$data);	
}

public function feedback_user($groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$user = $this->ion_auth->user()->row();
	if (!empty($user)) {
		$data['user_feedback'] = $this->admin_model->get_in_feedback($user->id);
	}else{
		redirect('home');
	}
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['group_details'] = $group;
	$data['main_content'] = 'home/user_feedback';
	$this->load->view('front/template',$data);	
}

public function chatwith_us_user($groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$user = $this->ion_auth->user()->row();
	$data['user_feedback'] = $this->admin_model->get_in_feedback($user->id);
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['group_details'] = $group;
	$data['main_content'] = 'home/chat_with_us';
	$this->load->view('front/template',$data);	
}

public function send_user_message(){
	$message = $_POST['message'];
	$user = $this->ion_auth->user()->row();
	echo $this->home_model->insert_user_message($message, $user->id, $user->display_name);
}

public function submit_feeback_suggetion(){
	$feed_back_suggetions = $_POST['feed_back_suggetions'];
	$user = $this->ion_auth->user()->row();
	echo $this->home_model->insert_feeback_suggetion($feed_back_suggetions, $user->id);
}

public function profile_change_password($groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$user = $this->ion_auth->user()->row();
	if (!empty($user)) {
		$data['user_feedback'] = $this->admin_model->get_in_feedback($user->id);
	}else{
		redirect('home');
	}
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['group_details'] = $group;

	$data['main_content'] = 'home/change_password';
	$this->load->view('front/template_without_footer',$data);
}

public function about_us($groupname){
	$data['groupname'] = $groupname;
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	if ($group->name =='Mygroup') {
		$groupId = 0;
	}else{
		$groupId = $group->id;
	}
	$data['about_us'] =  $this->admin_model->get_about_all($groupId);
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link($groupId);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['navName'] = '';
	if ($this->mobile_detect->isMobile()) {
		$data['main_content'] = 'home/about_us_mobile';
	}else{
		$data['main_content'] = 'home/about_us';
	}
	$data['group_details'] = $group;
	$this->load->view('front/template',$data);
}

public function footer_page($groupname, $pagename){
	$data['groupname'] = $groupname;
	$data['pagename'] = $pagename;
	$data['logo'] = $this->admin_model->get_logo_image();
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	if ($group->name =='Mygroup') {
		$groupId = 0;
	}else{
		$groupId = $group->id;
	}
	$data['footer_data'] =  $this->admin_model->get_all_footer_data($groupId, $pagename);
	$data['social_link'] = $this->admin_model->get_social_link($groupId);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['navName'] = '';
	$data['group_details'] = $group;
	if ($this->mobile_detect->isMobile()) {
		if ($pagename == 'testimonials') {
			$data['main_content'] = 'home/footer_testimonials_mobile';
		}else{
			$data['main_content'] = 'home/footer_page_mobile';
		}
		
	}else{
		$data['main_content'] = 'home/footer_page';
	}
	$this->load->view('front/template',$data);
}

public function gallery($groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['gallery_info'] = $this->home_model->load_galleries();
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['group_details'] = $group;
	$data['main_content'] = 'home/gallery';
	$this->load->view('front/template',$data);
}

public function view_gallery($gallery_id, $groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$groupId = $group->id;
	if ($groupname =='Mygroup') {
		$groupId = '0';
	}
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['gallery_info'] = $this->admin_model->get_gallery_info($gallery_id,$groupId);
	$data['image_info'] = $this->admin_model->get_images_info($gallery_id, $groupId);
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['group_details'] = $group;
	$data['main_content'] = 'home/gallery_view';
	$this->load->view('front/template',$data);
}

public function contact($groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
	// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['contact'] = $this->admin_model->get_contact_details();
	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	
	$user_location = $this->franchise_model->get_bycurrentlocation_address();

	$data['head_office'] = $this->franchise_model->get_franhise_holder_address_head_office($user_location->country, 6)->result();
	$data['regional_office'] = $this->franchise_model->get_franhise_holder_regional_address($user_location->country, $user_location->state, 7)->result();
	$data['branch_office'] = $this->franchise_model->get_franhise_branch_holder_address($user_location->country, $user_location->state, $user_location->district, 8)->result();
	// echo "<pre>"; print_r($data['head_office']);
	// echo "<pre>"; print_r($data['regional_office']);
	// echo "<pre>"; print_r($data['branch_office']);

	//  die();
	$data['user'] = $this->ion_auth->user()->row();
	$data['group_details'] = $group;
	$data['main_content'] = 'home/contact';
	$this->load->view('front/template',$data);
}

public function get_branch_address(){
	$country = $_POST['country'];
	$state = $_POST['state'];
	$district = $_POST['district'];
	$branch_office = $this->franchise_model->get_franhise_branch_holder_address($country, $state, $district, 8)->result();
	echo json_encode($branch_office);
}

public function get_regional_address(){
	$country = $_POST['country'];
	$state = $_POST['state'];
	$regional_office = $this->franchise_model->get_franhise_holder_regional_address($country, $state,  7)->result();
	echo json_encode($regional_office);
}

public function get_head_office_address(){
	$country = $_POST['country'];
	$head_office = $this->franchise_model->get_franhise_holder_address_head_office($country, 6)->result();
	echo json_encode($head_office);
}

public function enquiry($groupname){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['user'] = $this->ion_auth->user()->row();
	// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
	// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['contact'] = $this->admin_model->get_contact_details();
	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['group_details'] = $group;
	$data['main_content'] = 'home/enquiry';
	$this->load->view('front/template',$data);
}

public function contact_enquiry($groupname){

	$insertId = $this->home_model->contact_insert_data();

	if ($insertId) {
		$final_output = 'Enquiry Form Submitted';

		$config = $this->config->item('email_config', 'ion_auth');
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from($config['smtp_user']);
		$this->email->to('gm.mygroup@gmail.com','Enquiry Form');
		$this->email->subject('Enquiry Form');
		$this->email->message($final_output);

		if ($this->email->send()) {
			$this->session->set_flashdata('flashSuccess', 'Successfully added will get back soon');
		} else {
			$this->session->set_flashdata('flashError', 'Email is not valid...');
		}
		redirect('home/contact_success/'.$groupname);
	}
}

public function contact_success($groupname){
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	$data['groupname'] = $groupname;
	$data['navName'] = '';
	$data['logo'] = $this->admin_model->get_logo_image();
	$data['header_sliders'] = $this->admin_model->get_header_slider_image();
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$data['contact'] = $this->admin_model->get_contact_details();
	$data['total_apps'] = $this->admin_model->get_all_apsname();
	$data['social_link'] = $this->admin_model->get_social_link($group->id);
	$data['copy_right'] = $this->admin_model->get_copy_right();
	$data['main_content'] = 'home/contact_success';
	$this->load->view('front/template',$data);
}

public function switch_darkmode(){
	$switch_mode = $_POST['switch_mode'];
	$this->session->set_userdata('switch_mode', $switch_mode);
	echo 1;
}


public function client_login(){
	$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
	$this->load->view('auth/client_login_group',$data);
}


public function client_login_group($groupName){
	$group = $this->home_model->get_group_name_detailsbyname($groupName);
	if (!empty($group)) {
		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
	}else{
		$data['logo'] = $this->admin_model->get_logo_image();
	}
	$data['groupName'] = $groupName;
	$data['group_id'] = $group->id;
	$this->load->view('auth/client_login',$data);
}

public function god_login_group($groupName, $subGroup){
	$group = $this->home_model->get_group_name_detailsbyname($groupName);
	if (!empty($group)) {
		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
	}else{
		$data['logo'] = $this->admin_model->get_logo_image();
	}
	$data['groupName'] = $groupName;
	$data['subGroup'] = $subGroup;
	$data['group_id'] = $group->id;
	$this->load->view('auth/client_login',$data);
}

public function media_login_group($groupName){
	$group = $this->home_model->get_group_name_detailsbyname($groupName);
	if (!empty($group)) {
		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
	}else{
		$data['logo'] = $this->admin_model->get_logo_image();
	}
	$data['groupName'] = $groupName;
	$data['group_id'] = $group->id;
	$this->load->view('auth/media_login',$data);
}

public function client_register($groupName){
	$group = $this->home_model->get_group_name_detailsbyname($groupName);
	if (!empty($group)) {
		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
	}else{
		$data['logo'] = $this->admin_model->get_logo_image();
	}
	$data['groupName'] = $groupName;
	$data['subGroup'] = '';
	$data['group_id'] = $group->id;
		// $data['main_content'] = 'auth/register_form/'.$groupName;
	$this->load->view('auth/register',$data);
}

public function god_client_register($groupName, $subGroup){
	$group = $this->home_model->get_group_name_detailsbyname($groupName);
	if (!empty($group)) {
		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
	}else{
		$data['logo'] = $this->admin_model->get_logo_image();
	}
	$data['groupName'] = $groupName;
	$data['subGroup'] = $subGroup;
	$data['group_id'] = $group->id;
	$this->load->view('auth/register',$data);
}

public function client_register_form($groupName, $group_id, $user_id, $uri=''){
	$data['groupName'] = $groupName;
	$data['logo'] = $this->home_model->get_group_logo_byId($group_id);
	$data['group_id'] = $group_id;
	$data['user_id'] = $user_id;
	$data['uri'] = $uri;
	$data['country'] = $this->home_model->get_country_list();
	$data['category'] = $this->home_model->get_categorybygropuid($group_id);
	$this->load->view('auth/register_form/'.$groupName, $data);
}


public function media_category_get_by_type(){
	$media_type = $_POST['media_type'];
    $result = $this->admin_model->media_category_get_by_type($media_type);
        echo json_encode($result);
}

public function client_register_submit(){
	$input = $this->input->post();
	$res = $this->home_model->insert_client_registration_details();
	if ($res) {
		redirect('home/client_success/'.$input['group_name']);
	}else{
		redirect('home/client_login/'.$input['group_name']);
	}
}

public function client_registeration(){
	$emailId =  $_POST['emailId'];
	$password =	$_POST['password'];
	$additional_data = array(
		'group_id'=> $_POST['group_id']
	);
	$uri = $_POST['uri'];
	if (!empty($uri)) {
		$group = array('9'); // Sets Client
	}else{
		$group = array('4'); // Sets Client
	}
    $result = $this->ion_auth->client_register($emailId, $password, $emailId, $additional_data, $group,  $_POST['group_id']);
    if ($result) {
    	echo $result;
    }else{
    	echo 0;
    }
}

	public function client_success($groupName){
		$group = $this->home_model->get_group_name_detailsbyname($groupName);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
		}
		$data['groupName'] = $groupName;
		$this->load->view('auth/client_sucess',$data);
	}


	public function register_unique_check(){
		$mobileNumber = $_POST['mobileNumber'];
		
		$this->db->where('username',$mobileNumber);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return 1;
		}else{
			return 0;
		}
	}


	public function apply_now($groupname){
		$data['groupname'] = $groupname;
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
		$data['total_apps'] = $this->admin_model->get_all_apsname();

		$data['group_details'] = $group;
		$data['main_content'] = 'home/apply_now';
		$this->load->view('front/template_group', $data);
	}

	public function apply_for_franchaise($groupname){
		$data['groupname'] = $groupname;
		// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
			// $data['header_sliders'] = $this->home_model->get_group_header_adsbyId($group->id);
			// $data['popupads'] = $this->home_model->get_poupads_groupbyId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
			// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
			// $data['popupads'] = $this->home_model->get_poupads();
		}
		// $data['total_apps'] = $this->admin_model->get_all_apsname();

		$data['group_details'] = $group;
		$data['main_content'] = 'home/apply_now_franchise';
		$this->load->view('front/template', $data);
	}

	public function apply_my_jobs($groupname){
		$data['groupname'] = $groupname;
		// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
			// $data['header_sliders'] = $this->home_model->get_group_header_adsbyId($group->id);
			// $data['popupads'] = $this->home_model->get_poupads_groupbyId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
			// $data['header_sliders'] = $this->admin_model->get_header_slider_image();
			// $data['popupads'] = $this->home_model->get_poupads();
		}
		// $data['total_apps'] = $this->admin_model->get_all_apsname();

		$data['group_details'] = $group;
		$data['main_content'] = 'home/apply_now_jobs';
		$this->load->view('front/template', $data);
	}

	public function franchaise_resume_form($groupname,$selectRegion){
		$data['groupname'] = $groupname;
		$data['selectRegion'] = $selectRegion;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
		}
		$data['countryList'] = $this->db->get('country_tbl')->result();
		$data['user'] = $this->ion_auth->user()->row();
		$data['group_details'] = $group;
		$data['main_content'] = 'home/franchise_resume_form';
		$this->load->view('front/template_group', $data);
	}

	public function apply_for_myjobs($groupname, $jobType){
		$data['groupname'] = $groupname;
		$data['jobType'] = $jobType;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
		}
		$data['countryList'] = $this->db->get('country_tbl')->result();
		$data['user'] = $this->ion_auth->user()->row();
		$data['group_details'] = $group;
		$data['main_content'] = 'home/apply_resume_form';
		$this->load->view('front/template_group', $data);
	}
	public function apply_franchise_insert($groupname){
		$file = $_FILES['franchise_upload_file'];
		$url_path = $this->s3FileUpload_resume($file);
		$result =  $this->home_model->insert_franchise_apply_form($url_path);
		if ($result) {
			$this->session->set_flashdata('flashSuccess', 'Successfully added will get back soon');
		} else {
			$this->session->set_flashdata('flashError', 'Something went wrong...');
		}
		redirect('home/apply_now_success/'.$groupname);
	}

	public function apply_job_insert($groupname){
		$file = $_FILES['franchise_upload_file'];
		$url_path = $this->s3FileUpload_resume($file);
		$result =  $this->home_model->insert_job_apply_form($url_path);
		if ($result) {
			$this->session->set_flashdata('flashSuccess', 'Successfully added will get back soon');
		} else {
			$this->session->set_flashdata('flashError', 'Something went wrong...');
		}
		redirect('home/apply_now_success_job/'.$groupname);
	}

	public function apply_now_insert(){
		$file = $_FILES['upload_document'];
		$url_path = $this->s3FileUpload_resume($file);
		$result =  $this->home_model->insert_apply_form($url_path);
		if ($result) {
			$this->session->set_flashdata('flashSuccess', 'Successfully added will get back soon');
		} else {
			$this->session->set_flashdata('flashError', 'Something went wrong...');
		}
		redirect('home/apply_now_success/'.$this->input->post('group_name'));
	}

	public function apply_now_success($groupname){
		$data['groupname'] = $groupname;
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
		$data['total_apps'] = $this->admin_model->get_all_apsname();

		$data['group_details'] = $group;
		$data['main_content'] = 'home/apply_now_success';
		$this->load->view('front/template_group', $data);
	}

	public function apply_now_success_job($groupname){
		$data['groupname'] = $groupname;
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
		$data['total_apps'] = $this->admin_model->get_all_apsname();

		$data['group_details'] = $group;
		$data['main_content'] = 'home/apply_now_success_job';
		$this->load->view('front/template_group', $data);
	}

	public function mydiary_home($groupname){        
		$data['groupname'] = $groupname;
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
		$data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['group_details'] = $group;
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'admin/mydiary_home';
		}else{
			$data['main_content']    = 'admin/mydiary_home';
		}
		$this->load->view('front/template_group', $data);
	}

	public function category( $groupname = 'Myneedy',$needy_type ='Doorstep'){
		$needy_type = urldecode($needy_type);
		$data['groupname'] = $groupname;
		$data['needy_type'] = $needy_type;
		$data['needy_type'] = $this->needy_model->get_needy_category_by_name($needy_type);
		// echo "<pre>"; print_r($data['needy_type']); die();
		// $data['top_icon'] = $this->admin_model->get_topnav_icon_list();

		// $data['top_header_ads'] = $this->home_model->get_top_header_ads_bycurrentlocation($groupname, $needy_type);
		
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
		}
		$data['user'] = $this->ion_auth->user()->row();
		//echo "<pre>"; print_r($data['user']); die();
    	// echo "<pre>"; print_r($data['needy_type']); die();
		// $data['total_apps'] = $this->admin_model->get_all_apsname();
		$data['group_details'] = $group;
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'home/needy_type';
		}else{
			$data['main_content']    = 'home/needy_type';
		}
		$this->load->view('front/template_group', $data);
	}

	public function my_company_page( $groupname = 'Myneedy',$needy_type ='Doorstep'){
		$needy_type = urldecode($needy_type);
		$data['groupname'] = $groupname;
		$data['needy_type'] = $needy_type;
		$group = $this->home_model->get_group_name_detailsbyname($groupname);
		if (!empty($group)) {
			$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
		}else{
			$data['logo'] = $this->admin_model->get_logo_image();
		}
		$data['user'] = $this->ion_auth->user()->row();
		$data['group_details'] = $group;
		if ($this->mobile_detect->isMobile()) {
			$data['main_content']    = 'home/my_company_page';
		}else{
			$data['main_content']    = 'home/my_company_page';
		}
		$this->load->view('front/template_group', $data);
	}


	public function mymediafooter($groupname, $mediatype){
		$data['groupname'] = $groupname;
		$data['mediatype'] = $mediatype;
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
		$data['group_details'] = $group;
		switch ($mediatype) {
			case 'tv':
			$data['main_content']    = 'home/media_tv';
			break;
			case 'radio':
			$data['main_content']    = 'home/media_radio';
			break;
			case 'e-paper':
			$data['main_content']    = 'home/media_e_paper';
			break;
			case 'magazine':
			$data['main_content']    = 'home/media_magazine';
			break;
			case 'web':
			$data['main_content']    = 'home/media_web';
			break;
			case 'youtube':
			$data['main_content']    = 'home/media_youtube';
			break;
			default:
	 			// code...
			break;
		}
		$this->load->view('front/template_group', $data);
	}

	public function set_current_location_by_user(){
		$district = $_POST['district'];
		$current_country = $_POST['current_country'];
		$current_state = $_POST['current_state'];
		echo $this->home_model->set_current_location_by_user_id($district, $current_country, $current_state);
	}

	// New  list

	public function get_my_groups_apps(){
		$apps_name = $_POST['apps_name'];
		$result = $this->home_model->get_my_groups_apps_list($apps_name);
		echo json_encode($result);
	}

	public function get_all_mygroups_apps(){
		$result = $this->home_model->get_all_mygroups_apps_list();
		// echo "<pre>"; print_r($result);die();
		echo json_encode($result);
	}

	public function get_header_ads(){
		$main_app = $_POST['main_app'];
		$sub_app = urldecode($_POST['sub_app']);
		$result = $this->home_model->get_header_ads_list($main_app, $sub_app);
		if (!empty($main_app)) {
			if($sub_app != 'my-company' && $main_app != 'Mygroup'){
				foreach ($result as $key => &$val) {	
					$val['img'] = $this->filemanager->getFilePath($val['image_path']);
				}
			}
		}
		
		// if($sub_app =='my-company'){
		// 	foreach ($result as $key => &$val) {	
		// 		$val['img'] = base_url().$val['image_path'];
		// 	}
		// }
		
		echo json_encode($result);
	}

	public function get_location_wise_data(){
	 	$userId = $this->ion_auth->user()->row()->id;
	 	$totalGloal ='';
	 	$national ='';
	 	$regional ='';
	 	$local ='';
	 	if (!empty($userId)) {
	 		$global = "select count(id) as globalCount from user_registration_form";
	      	$totalGloal = $this->db->query($global)->row();

	      	$setLoc = "select (case when set_country is null then country else set_country end) as national,
	      	(case when set_state is null then state else set_state end) as regional,
	      	(case when set_district is null then district else set_district end) as Local 
	      	from user_registration_form where user_id = $userId";
	      	$locations = $this->db->query($setLoc)->row();

	      	$sql = "select count(urf.id) as natioanlCount, ct.country from 
	      	user_registration_form urf
	      	join country_tbl ct on $locations->national = ct.id
	      	where urf.country = $locations->national";
	      	$national = $this->db->query($sql)->row();

	      	$sql1 = "select count(urf.id) as regionalCount, st.state from user_registration_form urf 
	      	join state_tbl st on $locations->regional = st.id
	      	where urf.state = $locations->regional";
	      	$regional = $this->db->query($sql1)->row();

	      	$sql1 = "select count(urf.id) as localCount,  dt.district from user_registration_form urf 
	      	join district_tbl dt on $locations->Local = dt.id
	      	where urf.district = $locations->Local";
	      	$local = $this->db->query($sql1)->row();
	 	}
	 	echo json_encode(array('global'=>$totalGloal,'national'=>$national,'regional'=>$regional,'local'=>$local));
      	

	}


public function downloadapp(){
	$this->load->view('download_the_app');
}

public function review_ratings($groupname ='Mygroup'){
	$data['groupname'] = $groupname;
	$group = $this->home_model->get_group_name_detailsbyname($groupname);
	if (!empty($group)) {
		$data['logo'] = $this->home_model->get_group_logo_byId($group->id);
	}else{
		$data['logo'] = $this->admin_model->get_logo_image();
	}
	$data['group_details'] = $group;

 	$blogid = 1; 
    // $data['get_avg_rating'] = $this->home_model->count_total_rating($blogid);
    // $data['rating_data'] = $this->home_model->get_rating_data($blogid);

	$data['main_content']    = 'home/rating_review';
	$this->load->view('front/template', $data);
}

public function mylabor(){
	$user = $this->ion_auth->user()->row();
	if (!empty($user)) {
		$this->db->where('labor_mobile_number',$user->username);
		$query = $this->db->get('labor_account');
		if ($query->num_rows()>0) {
			redirect('labor_controller/my_labor_dashboard');
		}
	}else{
		redirect('home');
	}
}
	
}   


?>