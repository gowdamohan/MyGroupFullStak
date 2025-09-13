<?php

class Myshop extends CI_Controller {
               
	function __construct(){
 		parent::__construct();
	 	$this->load->model('needy_model');
	 	$this->load->model('home_model'); 
        $this->load->model('admin_model'); 
    	$this->load->model('Mygroup_model');
	  	$this->load->model('country_model');
	  	$this->load->library('filemanager');
	}

	public function myshop_category($groupname, $shop_type){
		// $userId = $this->ion_auth->user()->row();
  //   	$data['isLoggedin'] = 0;
  //   	if (!empty($userId)) {
  //   		$data['isLoggedin'] = 1;
  //   	}
		$data['groupname'] = $groupname;
		$data['shop_type'] = $shop_type;
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
	 	$data['shop_details'] = $this->home_model->get_my_shop_category_sub_category_details($shop_type);
	 	$data['top_header_ads'] = $this->home_model->get_top_header_ads_bycurrentlocation($groupname, $shop_type);
	 	// echo "<pre>"; print_r($data['shop_details']); die();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/myshop';
	 	}else{
	 		$data['main_content']    = 'home/myshop';
	 	}
        $this->load->view('front/template_group', $data);
	}
	public function myshop_category_products($groupname, $shop_type, $cat_id){
		$data['groupname'] = $groupname;
		$data['shop_type'] = $shop_type;
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
	 	$data['shop_product'] = $this->home_model->get_my_shop_products_by_cat_id($cat_id);
	 	
	 	if ($this->mobile_detect->isMobile()) {
	 		if ($shop_type == 'local' || $shop_type == 'wholesale'  || $shop_type == 'brands') {
	 			$data['shop_details'] = $this->home_model->get_my_shop_details_by_cat_id($cat_id, $shop_type);
	 			$data['main_content']    = 'home/myshop_local_products';
	 		}else{
	 			$data['shop_product_details'] = $this->home_model->get_my_shop_products_details_by_cat_id($cat_id, $shop_type);
	 			$data['main_content']    = 'home/myshop_products';
	 		}
	 	}else{
	 		$data['main_content']    = 'home/myshop_products';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function myshop_category_sub_products($groupname, $shop_type, $sub_cat_id, $cat_id){
		$data['groupname'] = $groupname;
		$data['shop_type'] = $shop_type;
		$data['cat_id'] = $cat_id;
		$data['sub_cat_id'] = $sub_cat_id;
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
	 	$data['shop_product'] = $this->home_model->get_my_shop_products_by_cat_id($cat_id);
	 	$data['shop_product_details'] = $this->home_model->get_my_shop_products_details_by_sub_cat_id($sub_cat_id, $shop_type);
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/myshop_products';
	 	}else{
	 		$data['main_content']    = 'home/myshop_products';
	 	}
        $this->load->view('front/template_group', $data);
	}
}