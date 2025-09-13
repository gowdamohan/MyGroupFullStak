<?php

class Myunions extends CI_Controller {
               
	function __construct(){
 		parent::__construct();
	 	$this->load->model('needy_model');
	 	$this->load->model('home_model'); 
        $this->load->model('admin_model'); 
    	$this->load->model('Mygroup_model');
	  	$this->load->model('country_model');
	  	$this->load->library('filemanager');
	}

	public function myunions_news($groupname, $union_type){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
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
    	// $data['top_header_ads'] = $this->home_model->get_top_header_ads_bycurrentlocation($groupname, $union_type);
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['country'] = $this->admin_model->get_country_flag();
	 	$data['category'] = $this->home_model->get_all_unions_category();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_news';
	 	}else{
	 		$data['main_content']    = 'home/union_news';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function get_union_news_posted(){
		$location_id = $_POST['location_id'];
    	$unions_cat = $_POST['unions_cat'];
    	$selectValue = $_POST['selectValue'];
    	$notice_selectType = $_POST['notice_selectType'];

    	$result = $this->home_model->get_union_news_posted_list($location_id, $notice_selectType, $unions_cat, $selectValue);
	 	$news_ids = array_chunk($result, 5);
        echo json_encode($news_ids);
	}

	public function get_union_news_posted_data(){
		$unionNewIds = $_POST['notice_ids'];
    	$result = $this->home_model->get_union_news_posted_list_data($unionNewIds);
		foreach ($result as $key => $val) {
			if ($val->category == '1') {
				$val->img = base_url().'assets/text_file_icon.png';
				$val->img2 = base_url().'assets/text_file_icon.png';
				$val->img3 = base_url().'assets/text_file_icon.png';
				if ($val->union_main_img1 !='') {
					$val->img = $this->filemanager->getFilePath($val->union_main_img1);
				}
				if ($val->union_main_img2 !='') {
					$val->img2 = $this->filemanager->getFilePath($val->union_main_img2);
				}
				if ($val->union_main_img3 !='') {
					$val->img3 = $this->filemanager->getFilePath($val->union_main_img3);
				}
			}else if($val->category == '2'){
				$val->img = base_url().'assets/video_file_icon.png';
			}else{
				$val->img = base_url().'assets/text_file_icon.png';
			}
    	}
    	echo json_encode($result);
	}

	public function unions_news_details($groupname, $union_type,$unions_new_id){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['country'] = $this->admin_model->get_country_flag();
	 	$data['category'] = $this->home_model->get_all_unions_category();
	 	$data['unions_news'] = $this->home_model->get_unions_news_databy_id($unions_new_id);
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/unions_new_details';
	 	}else{
	 		$data['main_content']    = 'home/unions_new_details';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function myunions_list($groupname, $union_type){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_list';
	 	}else{
	 		$data['main_content']    = 'home/union_list';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function get_union_list_ids(){
		$union_type = $_POST['union_type'];
    	$result = $this->home_model->get_union_list_ids($union_type);
	 	$union_ids = array_chunk($result, 5);
        echo json_encode($union_ids);
	}

	public function get_union_list_data(){
		$unions_ids = $_POST['unions_ids'];
		$union_type = $_POST['union_type'];
		$result = $this->home_model->get_union_list_databyIds($unions_ids, $union_type);
		echo json_encode($result);
	}

	public function unions_single($groupname, $union_type, $unionId){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['single_union'] = $this->home_model->get_single_union_by_id($unionId);
	 	$data['user_validate'] = $this->home_model->check_user_member_validate($data['single_union']->user_id);
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_single_view';
	 	}else{
	 		$data['main_content']    = 'home/union_single_view';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function myunions_me($groupname, $union_type){

		$userId = $this->ion_auth->user()->row();
    	$data['isLoggedin'] = 0;
    	if (!empty($userId)) {
    		$data['isLoggedin'] = 1;
    	}
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_me';
	 	}else{
	 		$data['main_content']    = 'home/union_me';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function get_union_list_me(){
		$union_type = $_POST['union_type'];
    	$result = $this->home_model->get_union_list_me($union_type);
        echo json_encode($result);
	}

	public function invite_membership_form($groupname, $union_type, $client_user_id){
		$data['user_data'] = $this->ion_auth->user()->row();
		// echo "<pre>"; print_r($data['user_data']); die();
		$data['user_regiser'] = $this->db->select('*')->where('user_id',$data['user_data']->id)->get('user_registration_form')->row();
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['client_user_id'] = $client_user_id;
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
	 	$data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        $data['enabled_fields'] = $this->admin_model->enabled_fields_member_form_me($client_user_id);
        $data['required_fields'] = $this->admin_model->get_union_form_required_fieldsme($client_user_id);
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection_me($client_user_id);
        $data['member_validity'] = $this->admin_model->get_member_validity_details($client_user_id);
    	// echo "<pre>"; print_r($data['member_validity']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_me_membership_form';
	 	}else{
	 		$data['main_content']    = 'home/union_me_membership_form';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function edit_membership_form($groupname, $union_type, $client_user_id){
		$data['user_data'] = $this->ion_auth->user()->row();
		$data['user_regiser'] = $this->db->select('*')->where('user_id',$data['user_data']->id)->get('user_registration_form')->row();
		$data['edit_member_form'] = $this->db->select('*')
		->where('user_name',$data['user_data']->username)
		->where('client_user_id',$client_user_id)
		->get('member_registration')->row();
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['client_user_id'] = $client_user_id;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
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
	 	$data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        $data['enabled_fields'] = $this->admin_model->enabled_fields_member_form_me($client_user_id);
        $data['required_fields'] = $this->admin_model->get_union_form_required_fieldsme($client_user_id);
        $data['union_location_type'] = $this->admin_model->get_union_location_type_selection_me($client_user_id);

    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/edit_union_me_membership_form';
	 	}else{
	 		$data['main_content']    = 'home/edit_union_me_membership_form';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function member_registration_insert(){
		$input = $this->input->post();
        $file = $_FILES['profile_photo'];
        $imagepathurl = '';
        if (!empty($file)) {
            $file = $_FILES['profile_photo'];
            $imagepathurl = $this->s3FileUpload_profile($file);
        }
        $result = $this->admin_model->member_registration_insertbypost($imagepathurl);
        if ($result) {
        	$this->admin_model->update_invite_union_status($input['mobile_number']);
          	$this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('myunions/myunions_me/'.$input['groupname'].'/'.$input['union_type']);
    }

    public function member_registration_update($id){
		$input = $this->input->post();
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
        redirect('myunions/myunions_me/'.$input['groupname'].'/'.$input['union_type']);
    }

     public function s3FileUpload_profile($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'profile');
        //print_r($uploadResult); die();
        return $uploadResult;
    }

    public function s3FileUpload_union_post_data($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'union_data');
        //print_r($uploadResult); die();
        return $uploadResult;
    }

    public function union_me_single_page_unions_details($groupname, $union_type, $client_user_id){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['client_user_id'] = $client_user_id;
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
	 	$data['country_flag'] = $this->admin_model->get_country_flag();
        $data['education'] = $this->admin_model->get_education_list();
        $data['profession'] = $this->admin_model->get_profession_list();
        $data['language'] = $this->admin_model->get_language_details();
        
        $data['partner_details'] = $this->home_model->get_partner_details_by_client_id($client_user_id);
        $data['members_data'] = $this->home_model->members_registration_data($client_user_id);
    	// echo "<pre>"; print_r($data['members_data']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_me_partner_details';
	 	}else{
	 		$data['main_content']    = 'home/union_me_partner_details';
	 	}
        $this->load->view('front/template_post_group', $data);
    }

    public function about_union($groupname,$union_type,$unionId){
    	$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['union_details'] = $this->home_model->get_union_profile_details($unionId, 'client_about');
	 	// echo "<pre>"; print_r($data['union_details']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_profile_common_page';
	 	}else{
	 		$data['main_content']    = 'home/union_profile_common_page';
	 	}
        $this->load->view('front/template_group', $data);
    }

    public function about_objectives($groupname,$union_type,$unionId){
    	$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['union_details'] = $this->home_model->get_union_profile_details($unionId, 'client_objectivies');
	 	// echo "<pre>"; print_r($data['union_details']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_profile_objectivies_page';
	 	}else{
	 		$data['main_content']    = 'home/union_profile_objectivies_page';
	 	}
        $this->load->view('front/template_group', $data);
    }

    public function about_news_letter($groupname,$union_type,$unionId){
    	$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
		$data['top_icon'] = $this->admin_model->get_topnav_icon_list();
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['union_details'] = $this->home_model->get_union_profile_details($unionId, 'client_news_letter');
	 	// echo "<pre>"; print_r($data['union_details']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_profile_news_letter_page';
	 	}else{
	 		$data['main_content']    = 'home/union_profile_news_letter_page';
	 	}
        $this->load->view('front/template_group', $data);
    }
	
	public function about_awards($groupname,$union_type,$unionId){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['union_details'] = $this->home_model->get_union_profile_details($unionId, 'client_awards');
	 	// echo "<pre>"; print_r($data['union_details']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_profile_awards_page';
	 	}else{
	 		$data['main_content']    = 'home/union_profile_awards_page';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function union_director_list($groupname,$union_type,$unionId){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['union_director'] = $this->home_model->get_union_director_details($unionId);
	 	// echo "<pre>"; print_r($data['union_director']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_director_list';
	 	}else{
	 		$data['main_content']    = 'home/union_director_list';
	 	}
        $this->load->view('front/template_group', $data);
	}
	public function union_member_list($groupname,$union_type,$unionId){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['union_member'] = $this->home_model->get_union_member_details($unionId);
	 	// echo "<pre>"; print_r($data['union_director']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_member_list';
	 	}else{
	 		$data['main_content']    = 'home/union_member_list';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function union_member_notice($groupname,$union_type,$unionId){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	// $data['union_member'] = $this->home_model->get_union_notice_details($unionId);
	 	// echo "<pre>"; print_r($data['union_director']);die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/union_member_notice';
	 	}else{
	 		$data['main_content']    = 'home/union_member_notice';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function get_union_notice_posted(){
    	$selectValue = $_POST['selectValue'];
    	$unionId = $_POST['unionId'];
    	$result = $this->home_model->get_union_notice_details($unionId, $selectValue);
	 	$notice_ids = array_chunk($result, 5);
        echo json_encode($notice_ids);
	}

	public function get_union_notice_posted_data(){
		$unionNewIds = $_POST['notice_ids'];
    	$result = $this->home_model->get_union_notice_posted_list_data($unionNewIds);
		foreach ($result as $key => $val) {
			if ($val->category == '1') {
				$val->img = base_url().'assets/text_file_icon.png';
				$val->img2 = base_url().'assets/text_file_icon.png';
				$val->img3 = base_url().'assets/text_file_icon.png';
				if ($val->union_main_img1 !='') {
					$val->img = $this->filemanager->getFilePath($val->union_main_img1);
				}
				if ($val->union_main_img2 !='') {
					$val->img2 = $this->filemanager->getFilePath($val->union_main_img2);
				}
				if ($val->union_main_img3 !='') {
					$val->img3 = $this->filemanager->getFilePath($val->union_main_img3);
				}
			}else if($val->category == '2'){
				$val->img = base_url().'assets/video_file_icon.png';
			}else{
				$val->img = base_url().'assets/text_file_icon.png';
			}
    	}
    	echo json_encode($result);
	}

	public function unions_notice_details($groupname, $union_type,$unions_new_id, $unionId){
		$data['groupname'] = $groupname;
		$data['union_type'] = $union_type;
		$data['unionId'] = $unionId;
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
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	$data['country'] = $this->admin_model->get_country_flag();
	 	$data['category'] = $this->home_model->get_all_unions_category();
	 	$data['unions_news'] = $this->home_model->get_unions_notice_databy_id($unions_new_id);
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/unions_notice_details';
	 	}else{
	 		$data['main_content']    = 'home/unions_notice_details';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function post_member_data(){
		$files = $_FILES['file_name'];
        $input = $this->input->post();
        $union_client_id = $input['union_client_id'];

        $resultLastId = $this->home_model->post_data_by_members($union_client_id);

        $failed = 0;
        // echo '<pre>'; print_r($files); die();
        foreach($files['tmp_name'] as $i => $file_name) {
            $file = array(
                'tmp_name' => $file_name,
                'name' => 'img'.$i.'.png'
            );
            $img = $this->s3FileUpload_union_post_data($file);
            if($img['file_name'] == null || $img['file_name'] == ''){
                $failed++;
            }else {
                $save_images[] = array(
                    'member_union_post_id' => $resultLastId,
                    'file_path' => $img['file_name'],
                    'type' => 'Image',
                );
            }
        }
        if(!empty($save_images)) {
            $this->home_model->save_member_posted__images($save_images);
        }

        echo $failed;

		
	}

	public function get_mypost_data_unionwise_ids(){
		$union_id = $_POST['union_id'];
    	$result = $this->home_model->get_mypost_data_unionwise_id($union_id);
	 	$post_ids = array_chunk($result, 5);
        echo json_encode($post_ids);
	}

	public function get_mypost_data_unionwise_data(){
		$unions_post_ids = $_POST['unions_post_ids'];
    	$result = $this->home_model->get_mypost_data_unionwise_data($unions_post_ids);
    	echo json_encode($result);
	}
}