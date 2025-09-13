<?php

class Needy extends CI_Controller {
               
	function __construct(){
 		parent::__construct();
	 	$this->load->model('needy_model');
	 	$this->load->model('home_model'); 
        $this->load->model('admin_model'); 
    	$this->load->model('Mygroup_model');
	  	$this->load->library('filemanager');
	}

	public function category($groupname, $needy_type){
		$data['groupname'] = $groupname;
		$data['needy_type'] = $needy_type;
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
    	// $data['top_header_ads'] = $this->home_model->get_top_header_ads_bycurrentlocation($groupname, $needy_type);
    	$data['needy_type'] = $this->needy_model->get_needy_category_by_name($needy_type);
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

	public function my_order_needy($groupname, $needy_type){
		$data['groupname'] = $groupname;
		$data['needy_type'] = $needy_type;
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
    	$data['needy_type'] = $this->needy_model->get_needy_category_by_name($needy_type);
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/my_order_needy';
	 	}else{
	 		$data['main_content']    = 'home/my_order_needy';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function client_needy_list($groupname, $needy_type, $needy_type_id){
		$data['groupname'] = $groupname;
		$data['needy_type'] = $needy_type;
		$data['needy_type_id'] = $needy_type_id;
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
    	$data['client_needy_type'] = $this->needy_model->get_needy_client_list_id($needy_type_id);
    	// echo "<pre>"; print_r($data['needy_type']); die();
	 	// $data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/client_needy_list';
	 	}else{
	 		$data['main_content']    = 'home/my_order_needy';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function get_client_needy_details(){
		$needy_type_id = $_POST['needy_type_id'];
		$result =  $this->needy_model->get_needy_client_list_id($needy_type_id);
        $news_ids = array_chunk($result, 5);
        echo json_encode($news_ids);
	}

	public function get_client_needy_list(){
		$client_neeyd_ids = $_POST['client_neeyd_ids'];
		$result =  $this->needy_model->get_needy_client_list_details($client_neeyd_ids);
        foreach ($result as $val) {
            $val->img = $this->filemanager->getFilePath($val->photo);
        }
        echo json_encode($result);
	}

	public function client_needy_view_by_id($groupname, $needy_type, $needy_client_id){
		$data['groupname'] = $groupname;
		$data['needy_type'] = $needy_type;
		$data['needy_client_id'] = $needy_client_id;
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
    	$data['client_needy_view'] = $this->needy_model->get_needy_client_view_list_id($needy_client_id);
    	//echo "<pre>"; print_r($data['client_needy_view']); die();
	 	$data['total_apps'] = $this->admin_model->get_all_apsname();
	 	$data['group_details'] = $group;
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/client_needy_view';
	 	}else{
	 		$data['main_content']    = 'home/my_order_needy';
	 	}
        $this->load->view('front/template_group', $data);
	}
}	