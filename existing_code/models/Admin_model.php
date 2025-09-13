<?php

class Admin_model extends CI_Model{
  
  	public function __construct(){
    	parent::__construct();
  	}

 	public function get_logo_image(){
    return $this->db->get('group')->row();
  }

  public function get_logo_imagebyId($id){
    $this->db->where('id',$id);
    return $this->db->get('logo')->row();
  }
 	public function get_header_slider_image(){
    return $this->db->get('group')->row();
  }

  public function get_poupads(){
    return $this->db->get('popup_ads')->row();
  }

  public function get_member_validity_data(){
    $userId = $this->ion_auth->user()->row()->id;
    $this->db->where('client_user_id',$userId);
    return $this->db->get('union_validity')->row();
  }

  public function get_all_apsname(){
    $result = $this->db->select('gc.id, gc.apps_name, gc.name, cd.icon, cd.url')
    ->from('group_create gc')
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();

    $totalApps = [];
    foreach ($result as $key => $val) {
      $totalApps[$val->apps_name][] = $val;
    }
    return $totalApps;
  }
  public function get_topnav_icon_list(){
    $myAps =  $this->db->select('gc.name, cd.*')
    ->from('group_create gc')
    ->or_where('gc.apps_name','My Apps')
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();

    $myCompany = $this->db->select('gc.name, cd.*')
    ->from('group_create gc')
    ->or_where('gc.apps_name','My Company')
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();

    $online = $this->db->select('gc.name, cd.*')
    ->from('group_create gc')
    ->or_where('gc.apps_name','My Onine Apps')
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();

    $offline = $this->db->select('gc.name, cd.*')
    ->from('group_create gc')
    ->or_where('gc.apps_name','My Offline Apps')
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();

    return array('myapps' => $myAps, 'myCompany' => $myCompany,'online' => $online,'offline' => $offline);
  }

  public function get_bodynav_icon_list($navName){

    $str_replace = str_replace('-', ' ', $navName);
    $navName = ucwords($str_replace);
    return  $this->db->select('gc.name, cd.*')
    ->from('group_create gc')
    ->where('gc.apps_name',$navName)
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();
  }

  public function edit_header_slider_by_id($id){
    $this->db->where('id',$id);
    return $this->db->get('header_slider')->row();
  }

  public function get_right_slider_image(){
    return $this->db->get('right_slider')->result();
  }

  public function get_right_slider_by_id($id){
    $this->db->where('id',$id);
    return $this->db->get('right_slider')->row();
  }
  
 	public function insert_logo($file_data){
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= "";
    }

    $data = array('logo' =>$path,'logo_name'=>$this->input->post('logo_name'));
    return $this->db->insert('logo',$data);
  }

  public function update_logobyId($file_data, $id){
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= $this->input->post('logo1');;
    }

    $data = array('logo' =>$path,'logo_name'=>$this->input->post('logo_name'));
    $this->db->where('id',$id);
    return $this->db->update('logo',$data);
  }

 	public function delete_logo($id){
    $this->db->where('id',$id);
    $file = $this->db->get('logo')->row();

    $filename = $file->logo; 
    if (file_exists($filename)){
      unlink($filename);
    }
    return $this->db->where('id',$id)->delete('logo');
  }

 	public function insert_header_slider($file_data){
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= "";
    }

    $data = array('image' =>$path);
    return $this->db->insert('header_slider',$data);
  }

  public function update_header_slider($file_data, $id){
   
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= $this->input->post('header_slider1');
    }

    $data = array('image' =>$path);
    $this->db->where('id',$id);
    return $this->db->update('header_slider',$data);
  }

  public function insert_right_slider($file_data){
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= "";
    }

    $data = array('image' =>$path);
    return $this->db->insert('right_slider',$data);
  }

  public function update_right_slider($file_data, $id){
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= $this->input->post('right_slider1');
    }

    $data = array('image' =>$path);
    $this->db->where('id',$id);
    return $this->db->update('right_slider',$data);
  }
 	public function delete_header_slider($id){
    $this->db->where('id',$id);
    $file = $this->db->get('header_slider')->row();

    $filename = $file->image; 
    if (file_exists($filename)){
      unlink($filename);
    }
    return $this->db->where('id',$id)->delete('header_slider');

  }

  public function delete_right_slider($id){
      $this->db->where('id',$id);
      $file = $this->db->get('right_slider')->row();

      $filename = $file->image; 
      if (file_exists($filename)){
        unlink($filename);
      }
      return $this->db->where('id',$id)->delete('right_slider');
  }

  public function insert_sub_logo($file_data, $file_data1){

    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= "";
    }

    if (!empty($file_data1)) {
      $path1 = "uploads/files/".$file_data1['orig_name'];
    }else{
      $path1 = "";
    }

    $data = array(
      'logo_name'=>$this->input->post('logo_name'),
      'icon'=>$path,
      'logo'=>$path1,
      'url'=>$this->input->post('url'),
    );
    return $this->db->insert('sub_logo',$data);
  }

  public function update_sub_logo($file_data, $file_data1, $id){
    if (!empty($file_data)) {
      $path= "uploads/files/".$file_data['orig_name'];
    }else{
      $path= $this->input->post('icon1');
    }

    if (!empty($file_data1)) {
      $path1 = "uploads/files/".$file_data1['orig_name'];
    }else{
      $path1 = $this->input->post('logo1');
    }

    $data = array(
      'logo_name'=>$this->input->post('logo_name'),
      'icon'=>$path,
      'logo'=>$path1,
      'url'=>$this->input->post('url'),
    );
    $this->db->where('id',$id);
    return $this->db->update('sub_logo',$data);
  }

  public function get_sub_logo_details(){
    return $this->db->get('sub_logo')->result();
  }

  public function get_sub_logo_detailsbyId($id){
    $this->db->where('id',$id);
    return $this->db->get('sub_logo')->row();
  }

  public function delete_sub_logo($id){
    $this->db->where('id',$id);
    $file = $this->db->get('sub_logo')->row();

    $filename = $file->icon; 
    if (file_exists($filename)){
      unlink($filename);
    }

    $logofile = $file->logo; 
    if (file_exists($logofile)){
      unlink($logofile);
    }

    $this->db->where('id',$id);
    return $this->db->delete('sub_logo');
  }

  public function get_sub_logo_slides_details($id){
    $this->db->where('sub_logo_id',$id);
    return $this->db->get('sub_logo_slides')->result();
  }

  public function delete_sub_logo_slides_by_id($id){
    $this->db->where('id',$id);
    return $this->db->delete('sub_logo_slides');
  }

  public function insert_sub_logo_slides($id, $file_data){

    if (!empty($file_data)) {
      $path = "uploads/files/".$file_data['orig_name'];
    }else{
      $path = "";
    }

    $data = array(
      'sub_logo_id'=>$id,
      'slides'=>$path,
    );
    return $this->db->insert('sub_logo_slides',$data);

  }

  public function get_sub_logo_details_home(){
   $sub_logo = $this->get_sub_logo_details();
   $slides = $this->db->get('sub_logo_slides')->result();
   $sliderArry = [];
   foreach ($slides as $key => $val) {
     $sliderArry[$val->sub_logo_id][] = $val->slides;
   }
   foreach ($sub_logo as $key => $log) {
      if (array_key_exists($log->id, $sliderArry)) {
        $log->slides = $sliderArry[$log->id];
      }
   }
   return $sub_logo;
  }


  public function insert_upload_group($icon, $logo, $namephoto, $ads1, $ads2, $ads3, $sideAds, $mainAds){

    // echo "<pre>"; print_r($sideAds); die();
    if (!empty($icon)) {
      $path = "uploads/group/".$icon['orig_name'];
    }else{
      $path = $this->input->post('icon_edit');;
    }

    if (!empty($logo)) {
      $path1 = "uploads/group/".$logo['orig_name'];
    }else{
      $path1 = $this->input->post('logo_edit');;
    }

    if (!empty($namephoto)) {
      $path2 = "uploads/group/".$namephoto['orig_name'];
    }else{
      $path2 = $this->input->post('name_photo_edit');;
    }

    if (!empty($ads1)) {
      $path3 = "uploads/group/".$ads1['orig_name'];
    }else{
      $path3 = $this->input->post('header_ads1_edit');;
    }

    if (!empty($ads2)) {
      $path4 = "uploads/group/".$ads2['orig_name'];
    }else{
      $path4 = $this->input->post('header_ads2_edit');;
    }

    if (!empty($ads3)) {
      $path5 = "uploads/group/".$ads3['orig_name'];
    }else{
      $path5 = $this->input->post('header_ads3_edit');;
    }

    if (!empty($sideAds)) {
      $path6 = "uploads/group/".$sideAds['orig_name'];
    }else{
      $path6 = $this->input->post('side_ads_edit');;
    }

    if (!empty($mainAds)) {
      $path7 = "uploads/group/".$mainAds['orig_name'];
    }else{
      $path7 = $this->input->post('main_edit');;
    }

    $data = array(
      'icon' => $path, 
      'logo' => $path1, 
      'name_image' => $path2, 
      'background_color' => $this->input->post('background_color'), 
      'header_ads1' => $path3, 
      'header_ads2' => $path4, 
      'header_ads3' => $path5,
      'side_ads' => $path6,
      'main_ads' => $path7,
      'header_ads_url_1' => $this->input->post('header_ads_url_1'), 
      'header_ads_url_2' => $this->input->post('header_ads_url_2'), 
      'header_ads_url_3' => $this->input->post('header_ads_url_3'), 
      'side_ads_url' => $this->input->post('side_ads_url'), 
      'main_ads_url' => $this->input->post('main_ads_url'), 
      'side_seconds' => $this->input->post('side_seconds'), 
    );

    $query = $this->db->get('group')->row();
    if (!empty($query)) {
      $this->db->where('id',$query->id);
      return $this->db->update('group',$data);
    }else{
      return $this->db->insert('group',$data);
    }

  }

  public function group_details(){
    return $this->db->get('group')->row();  
  }

  public function insert_group_associates(){
    return $this->db->insert('group_create', array('name' =>$this->input->post('group_name'),'apps_name' =>$this->input->post('apps_name')));
  }

  public function create_group(){

    return $this->db->select('gc.id,gc.name, cd.icon, cd.logo, cd.name_image, cd.background_color, cd.banner, cd.url')
    ->from('group_create gc')
    ->join('create_details cd','gc.id=cd.create_id','left')
    ->order_by('gc.id')
    ->get()->result();


  }

  public function advertise_group(){
    return $this->db->select('gc.id,gc.name, ad.ads1, ad.ads2, ad.ads3,  ad.ads1_url, ad.ads2_url, ad.ads3_url')
    ->from('group_create gc')
    ->where('gc.apps_name','My Company')
    ->join('aderttise ad','gc.id=ad.create_id','left')
    ->order_by('gc.id')
    ->get()->result();

  }

  public function main_ads_group(){
    return $this->db->select('mn.id, mn.ads1, mn.ads2, mn.ads3,  mn.ads1_url, mn.ads2_url, mn.ads3_url')
    ->from('main_ads mn')
    ->get()->row();
  }

  public function insert_upload_group_create($icon, $logo, $namephoto, $banner){
    if (!empty($icon)) {
      $path = "uploads/create/".$icon['orig_name'];
    }else{
      $path = $this->input->post('icon_edit');;
    }

    if (!empty($logo)) {
      $path1 = "uploads/create/".$logo['orig_name'];
    }else{
      $path1 = $this->input->post('logo_edit');;
    }

    if (!empty($namephoto)) {
      $path2 = "uploads/create/".$namephoto['orig_name'];
    }else{
      $path2 = $this->input->post('name_photo_edit');;
    }

    if (!empty($banner)) {
      $path3 = "uploads/create/".$banner['orig_name'];
    }else{
      $path3 = $this->input->post('banner_edit');;
    }
    
    $data = array(
      'create_id' => $this->input->post('group_id'), 
      'icon' => $path, 
      'logo' => $path1, 
      'name_image' => $path2, 
      'background_color' => $this->input->post('background_color'), 
      'url' => $this->input->post('url'), 
      'banner' => $path3, 
    );

            $this->db->where('create_id',$this->input->post('group_id'));
    $query = $this->db->get('create_details')->row();
    if (!empty($query)) {
      $this->db->where('id',$query->id);
      return $this->db->update('create_details',$data);
    }else{
      return $this->db->insert('create_details',$data);
    }

  }

  public function delete_upload_group_create($id){
    
    $this->db->where('id',$id);
    $this->db->delete('group_create');

    $this->db->where('create_id',$id);
    return $this->db->delete('create_details');

  }

  public function insert_upload_group_adverise($ads1, $ads2, $ads3){
     if (!empty($ads1)) {
      $path = "uploads/adertise/".$ads1['orig_name'];
    }else{
      $path = $this->input->post('ads1_edit');;
    }

    if (!empty($ads2)) {
      $path1 = "uploads/adertise/".$ads2['orig_name'];
    }else{
      $path1 = $this->input->post('ads2_edit');;
    }

    if (!empty($ads3)) {
      $path2 = "uploads/adertise/".$ads3['orig_name'];
    }else{
      $path2 = $this->input->post('ads3_edit');;
    }
    $data = array(
      'create_id' => $this->input->post('group_id'), 
      'ads1' => $path, 
      'ads2' => $path1, 
      'ads3' => $path2, 
      'ads1_url' => $this->input->post('ads1_url'), 
      'ads2_url' => $this->input->post('ads2_url'), 
      'ads3_url' => $this->input->post('ads3_url'), 
    );

            $this->db->where('create_id',$this->input->post('group_id'));
    $query = $this->db->get('aderttise')->row();
    if (!empty($query)) {
      $this->db->where('id',$query->id);
      return $this->db->update('aderttise',$data);
    }else{
      return $this->db->insert('aderttise',$data);
    }

  }

  public function insert_upload_group_main_adverise($ads1, $ads2, $ads3){
     if (!empty($ads1)) {
      $path = "uploads/adertise/".$ads1['orig_name'];
    }else{
      $path = $this->input->post('ads1_edit');;
    }

    if (!empty($ads2)) {
      $path1 = "uploads/adertise/".$ads2['orig_name'];
    }else{
      $path1 = $this->input->post('ads2_edit');;
    }

    if (!empty($ads3)) {
      $path2 = "uploads/adertise/".$ads3['orig_name'];
    }else{
      $path2 = $this->input->post('ads3_edit');;
    }
    $data = array(
      'ads1' => $path, 
      'ads2' => $path1, 
      'ads3' => $path2, 
      'ads1_url' => $this->input->post('ads1_url'), 
      'ads2_url' => $this->input->post('ads2_url'), 
      'ads3_url' => $this->input->post('ads3_url'), 
    );

    $query = $this->db->get('main_ads')->row();
    if (!empty($query)) {
      $this->db->where('id',$query->id);
      return $this->db->update('main_ads',$data);
    }else{
      return $this->db->insert('main_ads',$data);
    }

  }

  public function get_language_details(){
    return $this->db->get('language')->result();
  }

  public function insert_langague_details(){
    return $this->db->insert('language',$_POST);
  }

  public function get_language_by_id($id){
    return $this->db->where('id',$id)->get('language')->row();
  }

  public function update_langague_details($id){
    $this->db->where('id',$id);
    return $this->db->update('language',$_POST);
  }

  public function delete_language_by_id($id){
    $this->db->where('id',$id);
    return $this->db->delete('language');
  }


  // Education

  public function get_education_details(){
    return $this->db->get('education')->result();
  }

  public function insert_education_details(){
    return $this->db->insert('education',$_POST);
  }

  public function get_education_by_id($id){
    return $this->db->where('id',$id)->get('education')->row();
  }

  public function update_education_details($id){
    $this->db->where('id',$id);
    return $this->db->update('education',$_POST);
  }

  public function delete_education_by_id($id){
    $this->db->where('id',$id);
    return $this->db->delete('education');
  }


   // profession

  public function get_profession_details(){
    return $this->db->get('profession')->result();
  }

  public function insert_profession_details(){
    return $this->db->insert('profession',$_POST);
  }

  public function get_profession_by_id($id){
    return $this->db->where('id',$id)->get('profession')->row();
  }

  public function update_profession_details($id){
    $this->db->where('id',$id);
    return $this->db->update('profession',$_POST);
  }

  public function delete_profession_by_id($id){
    $this->db->where('id',$id);
    return $this->db->delete('profession');
  }
  
  public function get_country_flag(){
    return $this->db->get('country_tbl')->result();
  }

  public function get_country_code_by_id($country_id){
    $this->db->where('id',$country_id);
    return $this->db->get('country_tbl')->row();
  }

  public function get_details_disctrict_by_stateId($state_id){
    return  $this->db->select('*')
    ->from('district_tbl')
    ->where('state_id',$state_id)
    ->get()->result();
  }

  public function get_education_list(){
    return $this->db->get('education')->result(); 
  }

  public function get_profession_list(){
    return $this->db->get('profession')->result(); 
  }

  public function get_tnc_details($group_id){
    return $this->db->where('group_id',$group_id)->get('tnc_details')->row();
  }

  public function get_pnp_details($group_id){
    return $this->db->where('group_id',$group_id)->get('pnp_details')->row();
  }

  public function get_in_feedback($user_id){
    return $this->db->select('fs.*, u.id as userId,  u.profile_img, u.first_name')
    ->from('feedback_suggetions fs')
    ->join('users u','fs.user_id=u.id')
    ->where('fs.user_id',$user_id)
    ->order_by('date')
    ->get()->result();
  }

  public function get_feedback_details_all($user_id, $replyId){
    return $this->db->select('fs.*, u.id as userId, u.first_name,  u.profile_img')
    ->from('feedback_suggetions fs')
    ->join('users u','fs.replyed_by=u.id')
    ->where('fs.user_id',$user_id)
    ->where('fs.replyed_by',$replyId)
    ->order_by('date')
    ->get()->result();
  }

  public function get_feedackGroups(){
    return $this->db->select("fs.*, u.id as userId, u.profile_img, (case when fs.status = 0 then count(fs.status) else 0 end) countMessage")
    ->from('feedback_suggetions fs')
    ->join('users u','fs.user_id=u.id')
    ->order_by('date')
    ->group_by('fs.user_id')
    ->get()->result();
  }

  public function insert_admin_message($message, $userId){
    $data = array(
      'display_name' => 'admin', 
      'in_out' => 'out', 
      'message' => $message, 
      'user_id' => $userId,
      'status' => 1,
    );

  $this->db->where('user_id',$userId);
    $this->db->update('feedback_suggetions', array('status'=> 1));

    return $this->db->insert('feedback_suggetions',$data);
  }

  public function get_public_database(){
    return $this->db->select('u.email, u.id as userId, u.phone, u.first_name, u.display_name, u.phone, ct.country as country_name, st.state as state_name, dt.district as district_name, urf.*')
    ->from('users u')
    ->join('user_registration_form urf','u.id=urf.user_id')
    ->join('country_tbl ct','urf.country=ct.id')
    ->join('state_tbl st','urf.state=st.id')
    ->join('district_tbl dt','urf.district=dt.id')
    ->get()->result();
  }

  public function get_franchise_database(){
    return $this->db->select('ct.country as country_name, st.state as state_name, dt.district as district_name, afn.*')
    ->from('apply_franchise_now afn')
    ->join('country_tbl ct','afn.franchise_country=ct.id')
    ->join('state_tbl st','afn.franchise_state=st.id')
    ->join('district_tbl dt','afn.franchise_district=dt.id')
    ->order_by('afn.id','desc')
    ->get()->result();
  }

  public function get_job_database(){
    return $this->db->select('ct.country as country_name, ajn.*')
    ->from('apply_job_now ajn')
    ->join('country_tbl ct','ajn.franchise_country=ct.id')
    ->order_by('ajn.id','desc')
    ->get()->result();
  }
  public function get_enquiry_database(){
    return $this->db->select('*')
    ->from('contact_form')
    ->order_by('id','desc')
    ->get()->result();
  }

  public function get_feedback_database(){
     return $this->db->select('u.email, u.phone, u.first_name, u.phone, fsu.message')
    ->from('users u')
    ->join('feedback_suggetions_user fsu','u.id=fsu.user_id')
    ->order_by('fsu.id','desc')
    ->get()->result();
  }

  public function get_public_database_by_id($user_id){
    return $this->db->select('u.email, u.id as userId, u.phone, u.first_name, u.display_name, u.phone, ct.country as country_name, st.state as state_name, dt.district as district_name, u.profile_img,  urf.*')
    ->from('users u')
    ->where('u.id',$user_id)
    ->join('user_registration_form urf','u.id=urf.user_id')
    ->join('country_tbl ct','urf.country=ct.id')
    ->join('state_tbl st','urf.state=st.id')
    ->join('district_tbl dt','urf.district=dt.id')
    ->get()->row();
  }

  public function get_about_all($groupId){
    return $this->db->where('group_id',$groupId)->get('about')->result();
  }

  public function get_page_allbytable($pagename){
    return $this->db->get($pagename)->result();
  }

  public function get_all_footer_data($groupId, $pagename){
    return $this->db->where('group_id',$groupId)->order_by('id','desc')->get($pagename)->result();
  }

  public function edit_about_us_by_id($id){
    $this->db->where('id',$id);
    return $this->db->get('about')->row();
  }

  public function edit_page_us_by_id($id, $pageName){
    $this->db->where('id',$id);
    return $this->db->get($pageName)->row();
  }

  public function get_all_galleries($group_id){

      $masterCount =  $this->db->select("gallery_id, count(gallery_id) as img_count, image_name")
      ->from('gallery_images_master')
      ->where('group_id',$group_id)
      ->group_by('gallery_id')
      ->get()->result();
      $this->db->select('gl.*')
            ->from('gallery_list gl');
      $gallery_info=$this->db->get()->result_array();

      if(sizeof($masterCount) == 0){
          foreach($gallery_info as &$info){
              $info['image_count'] = 0;
              $info['image_name'] = '';
          }    
      }
      foreach($gallery_info as &$info){
          foreach($masterCount as $count){
              $info['image_count'] = 0;         
              if($info['gallery_id'] == $count->gallery_id){
                  $info['image_count'] = $count->img_count;
                  $info['image_name'] = $count->image_name;
                  break;
              }
          }
      }
      return $gallery_info;

    }

    public function addGallery($group_id){
      $data = array(
        'gallery_name'=>$_POST['gallery_name'],
        'gallery_description'=>$_POST['gallery_description'],
        'group_id'=>$group_id
      );
      $status = $this->db->insert('gallery_list',$data);

      if($status) {
        $gallery['id'] = $this->db->insert_id();
        $gallery['name'] = $data['gallery_name'];
        return $gallery;
      } else {
          return 0;
      }
    }

    public function get_gallery_info($gallery_id, $group_id){

      $query = $this->db->where('group_id',$group_id)->get_where('gallery_list', array('gallery_id' => $gallery_id))->row();
      return $query;
    }

    public function get_images_info($gallery_id, $group_id){
      $this->db->order_by('gallery_images_master.image_id','DESC');
      $query = $this->db->where('group_id',$group_id)->get_where('gallery_images_master', array('gallery_id' => $gallery_id));
      return $query->result_array();;
    }

    public function save_gallery_images($images){
        return $this->db->insert_batch('gallery_images_master',$images);
    }

    public function delete_image_db($image_id){
      $this->db->where('image_id', $image_id);
      $res1 = $this->db->delete('gallery_images_master');
      return $res1;
    }

    public function delete_album_db($gId){
      $this->db->where('gallery_id',$gId);
      $this->db->delete('gallery_list');

      $this->db->where('gallery_id', $gId);
      return $this->db->delete('gallery_images_master');
      
    }

    public function get_copy_right(){
     return $this->db->get('copy_rights')->row();
    }

    public function update_data($data, $id){
      $this->db->where('id',$id);
      return $this->db->update('copy_rights',$data);
    }

    public function insert_data($data){
      return $this->db->insert('copy_rights',$data);
    }

    public function insert_update_contact(){
      $data = array( 
        'address' => $this->input->post('address'),
        'email' =>$this->input->post('emaiil_id'),
        'contact_number' => $this->input->post('contact_number'),
        'group_id'=> $this->ion_auth->user()->row()->group_id
      );
      return $this->db->insert('contact',$data);
    }

    public function get_contact_details(){
      return $this->db->get('contact')->row();
    }

    public function update_contact_byId($id){
      $data = array( 
        'address' => $this->input->post('address'),
        'email' =>$this->input->post('emaiil_id'),
        'contact_number' => $this->input->post('contact_number'),
        'group_id'=> $this->ion_auth->user()->row()->group_id
      );
      $this->db->where('id',$id);
      return $this->db->update('contact',$data);
    }

    public function deleted_enquiry_by_id($id){
      return $this->db->where('id',$id)->delete('contact_form');
    }

    public function get_contact_enquiry_details(){
      return $this->db->get('contact_form')->result();
    }

 

    public function insert_pnp_details($group_id){
       $data = array( 
        'pnp_content' => $this->input->post('pnp_content'),
        'group_id'=>$group_id
      );
      return $this->db->insert('pnp_details',$data);
    }

    public function update_pnp_byId($id){
       $data = array( 
        'pnp_content' => $this->input->post('pnp_content')
      );
       $this->db->where('id',$id);
      return $this->db->update('pnp_details',$data);
    }

    public function insert_tnc_details($group_id){
       $data = array( 
        'tnc_content' => $this->input->post('tnc_content'),
        'group_id'=>$group_id
      );
      return $this->db->insert('tnc_details',$data);
    }

    public function update_tnc_byId($id){
       $data = array( 
        'tnc_content' => $this->input->post('tnc_content')
      );
       $this->db->where('id',$id);
      return $this->db->update('tnc_details',$data);
    }

    public function save_news($newsData, $table){
      return $this->db->insert_batch($table,$newsData);
    }

    public function update_news($newsData, $table){
      return $this->db->update_batch($table,$newsData,'id');
    }

    public function get_social_link($group_id){
      if ($group_id == 17) {
        $group_id = 0;
      }
      return $this->db->where('group_id',$group_id)->get('social_link')->result();
    }

    public function save_social_link($url,$uploadId, $group_id){
      $this->db->where('id',$uploadId);
      $query = $this->db->get('social_link')->row();
      if (!empty($query)) {
               $this->db->where('id',$uploadId);
        return $this->db->update('social_link',array('url'=>$url,'group_id'=>$group_id));
      }else{
        return $this->db->insert('social_link',array('url'=>$url,'group_id'=>$group_id));
      }
    }

    public function get_popu_ads(){
      return $this->db->get('popup_ads')->row();
    }

    public function insert_upload_sides($sideAds){
      if (!empty($sideAds)) {
        $path6 = "uploads/files/".$sideAds['orig_name'];
      }else{
        $path6 = $this->input->post('side_ads_edit');;
      }
    $data = array(
      'side_ads' => $path6,
      'side_ads_url' => $this->input->post('side_ads_url'), 
      'side_seconds' => $this->input->post('side_seconds'), 
    );
    $query = $this->db->get('popup_ads')->row();
    if (!empty($query)) {
      $this->db->where('id',$query->id);
      return $this->db->update('popup_ads',$data);
    }else{
      return $this->db->insert('popup_ads',$data);
    }
  }


  public function get_group_for_account_creation(){
   return  $this->db->select('gc.*')
    ->from('group_create gc')
    ->get()->result();
  }

  public function get_union_form_enabled_fields(){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','show_enabled_fields')
    ->where('user_id',$usersId)
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_form_enabled_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','show_enabled_fields')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_director_form_enabled_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','show_enabled_fields_director')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_header_leader_form_enabled_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','show_enabled_fields_header_leader')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_staff_form_enabled_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','show_enabled_fields_staff')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_form_required_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_director_form_required_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields_director')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_header_leader_form_required_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields_header_leader')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_staff_form_required_fields_admin($category_id){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields_staff')
    ->where('user_id',$usersId)
    ->where('category_id',$category_id)
    ->where('created_by','Admin')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_form_required_fields(){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields')
    ->where('user_id',$usersId)
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_form_required_fieldsme($client_user_id){

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields')
    ->where('user_id',$client_user_id)
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_union_director_form_required_fields(){
    $usersId = $this->ion_auth->user()->row()->id;

    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','required_fields_director')
    ->where('user_id',$usersId)
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_direct_application_form_enabled_fields(){
    $usersId = $this->ion_auth->user()->row()->id;
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('name','director_application_show_enabled_fields')
    ->where('user_id',$usersId)
    ->get()->row();
    if (!empty($result->value)) {
     return json_decode($result->value);
    }else{
      return array();
    }
  }

  public function insert_admission_configure_fields(){
    $usersId = $this->ion_auth->user()->row()->id;
    foreach ($this->input->post() as $name => $value) {
      $query = $this->db->where('name',$name)->where('user_id',$usersId)->get('group_config');
      if ($query->num_rows() > 0) {
        $UpdaterFields = array(
          'name' =>$name,
          'value' => json_encode($value),
          'type' => 'multiple',
          'user_id'=> $usersId,
        );
        
        $this->db->where('user_id',$usersId);
        $this->db->where('name',$name);
        $this->db->update('group_config',$UpdaterFields);
      }else{
        $rFields = array(
          'name' =>$name,
          'value' => json_encode($value),
          'type' => 'multiple',
          'user_id'=> $usersId,
        );
        $this->db->insert('group_config',$rFields);
      }  
    }
    return 1;
  }

  public function insert_admission_configure_fields_admin($unionCat){
    $usersId = $this->ion_auth->user()->row()->id;
    foreach ($this->input->post() as $name => $value) {
      $query = $this->db->where('name',$name)
      ->where('user_id',$usersId)
      ->where('category_id',$unionCat)
      ->where('created_by','Admin')
      ->get('group_config');
      if ($query->num_rows() > 0) {
        $UpdaterFields = array(
          'name' =>$name,
          'value' => json_encode($value),
          'type' => 'multiple',
          'user_id'=> $usersId,
          'category_id'=>$unionCat,
          'created_by'=>'Admin',
        );
        
        $this->db->where('user_id',$usersId);
        $this->db->where('name',$name);
        $this->db->where('category_id',$unionCat);
        $this->db->where('created_by','Admin');
        $this->db->update('group_config',$UpdaterFields);
      }else{
        $rFields = array(
          'name' =>$name,
          'value' => json_encode($value),
          'type' => 'multiple',
          'user_id'=> $usersId,
          'category_id'=>$unionCat,
          'created_by'=>'Admin',
        );
        $this->db->insert('group_config',$rFields);
      }  
    }
    return 1;
  }

  public function get_category_list(){
    $groupId = $this->ion_auth->user()->row()->group_id;
    return $this->db->where('group_id',$groupId)->get('category')->result();
  }

  public function get_category_by_id($id){
    return $this->db->where('id',$id)->get('category')->row();
  }

  public function insert_category(){
    $groupId = $this->ion_auth->user()->row()->group_id;
    $data = array(
      'name' => $this->input->post('category'), 
      'group_id' => $groupId 
    );
    return $this->db->insert('category',$data);
  }

  public function update_categorybyId($id){
    $data = array(
      'name' => $this->input->post('category'), 
    );
    $this->db->where('id',$id);
    return $this->db->update('category',$data);  
  }

  public function delete_categorybyId($id){
    return $this->db->where('id',$id)->delete('category');
  }

  public function get_client_application_fields(){
    $group_id = $this->ion_auth->user()->row()->group_id;
    $this->db->where('group_id',$group_id);
    $this->db->order_by('id');
    $userId = $this->db->get('users')->row()->id;
    if (!empty($userId)) {
      $this->db->where('user_id',$userId);
      return $this->db->get('group_config')->result();
    }else{
      return array();
    }
   
  }

  public function enabled_fields_member_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('user_id',$usersId)
    ->where('name','show_enabled_fields')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function enabled_fields_member_form_me($client_user_id){
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('user_id',$client_user_id)
    ->where('name','show_enabled_fields')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function enabled_fields_director_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $this->db->where('user_id',$usersId);
    $res = $this->db->get('client_registration')->row();
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('category_id',$res->category)
    ->where('name','show_enabled_fields_director')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function enabled_fields_header_leader_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $this->db->where('user_id',$usersId);
    $res = $this->db->get('client_registration')->row();
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('category_id',$res->category)
    ->where('name','show_enabled_fields_header_leader')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function enabled_fields_union_staff_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $this->db->where('user_id',$usersId);
    $res = $this->db->get('client_registration')->row();
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('category_id',$res->category)
    ->where('name','show_enabled_fields_staff')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function required_fields_director_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $this->db->where('user_id',$usersId);
    $res = $this->db->get('client_registration')->row();
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('category_id',$res->category)
    ->where('name','required_fields_director')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function required_fields_header_leader_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $this->db->where('user_id',$usersId);
    $res = $this->db->get('client_registration')->row();
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('category_id',$res->category)
    ->where('name','required_fields_header_leader')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }
  public function required_fields_union_staff_form(){
    $usersId = $this->ion_auth->user()->row()->id;
    $this->db->where('user_id',$usersId);
    $res = $this->db->get('client_registration')->row();
    $result = $this->db->select('*')
    ->from('group_config')
    ->where('category_id',$res->category)
    ->where('name','required_fields_staff')
    ->get()->row();
    if (!empty($result->value)) {
       return json_decode($result->value);
    }else{
        return array();
    }
  }

  public function get_member_edit_form_by_id($id){
    return $this->db->select('mr.*, date_format(mr.date_of_birth,"%d") as from_date, date_format(mr.date_of_birth,"%m") as from_month, date_format(mr.date_of_birth,"%Y") as from_year')
    ->from('member_registration mr')
    ->where('mr.id',$id)
    ->get()->row();
  }
  // public function enabled_fields_director_form(){
  //   $usersId = $this->ion_auth->user()->row()->id;
  //   $result = $this->db->select('*')
  //   ->from('group_config')
  //   ->where('user_id',$usersId)
  //   ->where('name','director_application_show_enabled_fields')
  //   ->get()->row();
  //   if (!empty($result->value)) {
  //      return json_decode($result->value);
  //   }else{
  //       return array();
  //   }
  // }

  public function member_registration_insertbypost($profileimgurl){
    $memberImagepath = '';
    if($profileimgurl['file_name'] != '') {
      $memberImagepath = $profileimgurl['file_name'];
    }

    $input = $this->input->post();

    if (isset($input['membership_amount'])) {
      $list = explode('_', $input['membership_amount']);
    }
    $prsentCountry = '';
    if (isset($input['present_country'])) {
      $prscountryname = $this->db->where('id',$input['present_country'])->get('country_tbl')->row();
      if (!empty($prscountryname)) {
        $prsentCountry = $prscountryname->country;
      }
    }
    
    $presentStatename = '';
    if (isset($input['present_state'])) {
      $prsStatename = $this->db->where('id',$input['present_state'])->get('state_tbl')->row();
      if (!empty($prsStatename)) {
        $presentStatename = $prsStatename->state;
      }
    }
    $presentDistrictname = '';
    if (isset($input['present_district'])) {
      $prsDistrictname = $this->db->where('id',$input['present_district'])->get('district_tbl')->row();
      if (!empty($prsDistrictname)) {
        $presentDistrictname = $prsDistrictname->district;
      }
    }
    $permanentCountry = '';
    if (isset($input['permanent_country'])) {
      $percountryname = $this->db->where('id',$input['permanent_country'])->get('country_tbl')->row();
      if (!empty($percountryname)) {
        $permanentCountry = $percountryname->country;
      }
    }
    $permanentStatename = '';
    if (isset($input['permanent_state'])) {
      $perStatename = $this->db->where('id',$input['permanent_state'])->get('state_tbl')->row();
      if (!empty($perStatename)) {
        $permanentStatename = $perStatename->state;
      }
    }
    $permanentDistrictname = '';
    if (isset($input['permanent_district'])) {
      $perDistrictname = $this->db->where('id',$input['permanent_district'])->get('district_tbl')->row();
      if (!empty($perDistrictname)) {
        $permanentDistrictname = $perDistrictname->district;
      }
    }
    $present_pincode = '';
    if (isset($input['present_pincode'])) {
      $present_pincode = $input['present_pincode'];
    }

    $permanent_pincode = '';
    if (isset($input['permanent_pincode'])) {
      $permanent_pincode = $input['permanent_pincode'];
    }
    
    $dob = $input['from_year'].'-'.$input['from_month'].'-'.$input['from_date'];
    $prsentAddress = $input['present_area'].','.$presentDistrictname.','.$presentStatename.','.$prsentCountry.','. $present_pincode;
    $permanentAddress = $input['permanent_area'].','.$perDistrictname.','.$perStatename.','.$percountryname.','.$permanent_pincode;

    $data = array(
      'full_name' => (!isset($input['full_name']) || $input['full_name'] == '')? null : $input['full_name'], 
      'display_name' => (!isset($input['display_name']) || $input['display_name'] == '')? null : $input['display_name'], 
      'mobile_number' => (!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'], 
      'email_id' => (!isset($input['email_id']) || $input['email_id'] == '')? null : $input['email_id'], 
      'father_name' => (!isset($input['father_name']) || $input['father_name'] == '')? null : $input['father_name'], 
      'mother_name' => (!isset($input['mother_name']) || $input['mother_name'] == '')? null : $input['mother_name'], 
      'date_of_birth' => (!isset($dob) || $dob == '')? null : date('Y-m-d',strtotime($dob)),
      'gender' => (!isset($input['gender']) || $input['gender'] == '')? null : $input['gender'], 
      'nationality' => (!isset($input['nationality']) || $input['nationality'] == '')? null : $input['nationality'], 
      'martial_status' => (!isset($input['martial_status']) || $input['martial_status'] == '')? null : $input['martial_status'], 
      'spouse_name' => (!isset($input['spouse_name']) || $input['spouse_name'] == '')? null : $input['spouse_name'], 
      'education' => (!isset($input['education']) || $input['education'] == '')? null : $input['education'], 
      'work_profession' => (!isset($input['work_profession']) || $input['work_profession'] == '')? null : $input['work_profession'], 
      'languages_known' => (!isset($input['languages_known']) || $input['languages_known'] == '')? null : $input['languages_known'], 
      'blood_group' => (!isset($input['blood_group']) || $input['blood_group'] == '')? null : $input['blood_group'], 
      'present_area' => (!isset($input['present_area']) || $input['present_area'] == '')? null : $input['present_area'], 
      'present_country' => (!isset($input['present_country']) || $input['present_country'] == '')? null : $input['present_country'], 
      'present_state' => (!isset($input['present_state']) || $input['present_state'] == '')? null : $input['present_state'], 
      'present_district' => (!isset($input['present_district']) || $input['present_district'] == '')? null : $input['present_district'], 
      'present_pincode' => (!isset($input['present_pincode']) || $input['present_pincode'] == '')? null : $input['present_pincode'],
      'permanent_area' => (!isset($input['permanent_area']) || $input['permanent_area'] == '')? null : $input['permanent_area'], 
      'permanent_country' => (!isset($input['permanent_country']) || $input['permanent_country'] == '')? null : $input['permanent_country'], 
      'permanent_state' => (!isset($input['permanent_state']) || $input['permanent_state'] == '')? null : $input['permanent_state'], 
      'permanent_district' => (!isset($input['permanent_district']) || $input['permanent_district'] == '')? null : $input['permanent_district'], 
      'permanent_pincode' => (!isset($input['permanent_pincode']) || $input['permanent_pincode'] == '')? null : $input['permanent_pincode'], 
      'introducer_name' => (!isset($input['introducer_name']) || $input['introducer_name'] == '')? null : $input['introducer_name'], 
      'introducer_number' => (!isset($input['introducer_number']) || $input['introducer_number'] == '')? null : $input['introducer_number'], 
      'union_location_country' => (!isset($input['union_location_country']) || $input['union_location_country'] == '')? null : $input['union_location_country'], 
      'union_location_state' => (!isset($input['union_location_state']) || $input['union_location_state'] == '')? null : $input['union_location_state'], 
      'union_location_district' => (!isset($input['union_location_district']) || $input['union_location_district'] == '')? null : $input['union_location_district'], 
      'apply_for' => (!isset($input['apply_for']) || $input['apply_for'] == '')? null : $input['apply_for'], 
      'status' => (!isset($input['status']) || $input['status'] == '')? null : $input['status'], 
      'present_address' => (!isset($prsentAddress) || $prsentAddress == '')? null : $prsentAddress, 
      'permanent_address' => (!isset($permanentAddress) || $permanentAddress == '')? null : $permanentAddress,
      'member_photo'=> (!isset($memberImagepath) || $memberImagepath == '')? null : $memberImagepath,
      'client_user_id'=> $input['client_user_id'],
      'user_name'=> (!isset($input['user_name']) || $input['user_name'] == '')? null : $input['user_name'], 
      'member_choose_type'=> (!isset($list[0]) || $list[0] == '')? null : $list[0], 
      'member_choose_amount'=> (!isset($list[1]) || $list[1] == '')? null : $list[1], 

    );
    return $this->db->insert('member_registration',$data);
  }

  public function header_leader_registration_insertbypost($profileimgurl){
    $usersId = $this->ion_auth->user()->row()->id;
    $memberImagepath = '';
    if (!empty($profileimgurl)) {
      if($profileimgurl['file_name'] != '') {
        $memberImagepath = $profileimgurl['file_name'];
      }
    }
    

    $input = $this->input->post();

   
    $prsentCountry = '';
    if (!empty($input['present_country'])) {
      $prscountryname = $this->db->where('id',$input['present_country'])->get('country_tbl')->row();
      $prsentCountry = $prscountryname->country;
    }
    
    $presentStatename = '';
    if (!empty($input['present_state'])) {
      $prsStatename = $this->db->where('id',$input['present_state'])->get('state_tbl')->row();
      $presentStatename = $prsStatename->state;
    }

    
    $presentDistrictname = '';
    if (!empty($input['present_district'])) {
      $prsDistrictname = $this->db->where('id',$input['present_district'])->get('district_tbl')->row();
      $presentDistrictname = $prsDistrictname->district;
    }

    $permanentCountry = '';
    if (!empty($input['permanent_country'])) {
      $percountryname = $this->db->where('id',$input['permanent_country'])->get('country_tbl')->row();
      $permanentCountry = $percountryname->country;
    }
    
    $permanentStatename = '';
    if (!empty($input['permanent_state'])) {
      $perStatename = $this->db->where('id',$input['permanent_state'])->get('state_tbl')->row();
      $permanentStatename = $perStatename->state;
    }

    $permanentDistrictname = '';
    if (!empty($input['permanent_district'])) {
      $perDistrictname = $this->db->where('id',$input['permanent_district'])->get('district_tbl')->row();
      $permanentDistrictname = $perDistrictname->district;
    }

    $fromyear = '';
    if (!empty($input['from_year'])) {
      $fromyear = (!isset($input['from_year']) || $input['from_year'] == '')? null : $input['from_year'];
    }
    $fromMonth = '';
    if (!empty($input['from_month'])) {
      $fromMonth = (!isset($input['from_month']) || $input['from_month'] == '')? null : $input['from_month'];
    }

    $fromDate = '';
    if (!empty($input['from_date'])) {
      $fromDate = (!isset($input['from_date']) || $input['from_date'] == '')? null : $input['from_date'];
    }
    $dob = $fromyear.'-'.$fromMonth.'-'.$fromDate;
    $presentArea = '';
    if (!empty($input['present_area'])) {
      $presentArea = $input['present_area'];
    }
    $permanentArea = '';
    if (!empty($input['permanent_area'])) {
      $permanentArea = $input['permanent_area'];
    }
    $prsentAddress = $presentArea.','.$presentDistrictname.','.$presentStatename.','.$prsentCountry.','.$input['present_pincode'];
    $permanentAddress = $permanentArea.','.$perDistrictname.','.$perStatename.','.$percountryname.','.$input['permanent_pincode'];

    $data = array(
      'full_name' => (!isset($input['full_name']) || $input['full_name'] == '')? null : $input['full_name'], 
      'display_name' => (!isset($input['display_name']) || $input['display_name'] == '')? null : $input['display_name'], 
      'mobile_number' => (!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'], 
      'email_id' => (!isset($input['email_id']) || $input['email_id'] == '')? null : $input['email_id'], 
      'father_name' => (!isset($input['father_name']) || $input['father_name'] == '')? null : $input['father_name'], 
      'mother_name' => (!isset($input['mother_name']) || $input['mother_name'] == '')? null : $input['mother_name'], 
      'date_of_birth' => (!isset($dob) || $dob == '')? null : date('Y-m-d',strtotime($dob)),
      'gender' => (!isset($input['gender']) || $input['gender'] == '')? null : $input['gender'], 
      'nationality' => (!isset($input['nationality']) || $input['nationality'] == '')? null : $input['nationality'], 
      'martial_status' => (!isset($input['martial_status']) || $input['martial_status'] == '')? null : $input['martial_status'], 
      'spouse_name' => (!isset($input['spouse_name']) || $input['spouse_name'] == '')? null : $input['spouse_name'], 
      'education' => (!isset($input['education']) || $input['education'] == '')? null : $input['education'], 
      'work_profession' => (!isset($input['work_profession']) || $input['work_profession'] == '')? null : $input['work_profession'], 
      'languages_known' => (!isset($input['languages_known']) || $input['languages_known'] == '')? null : $input['languages_known'], 
      'blood_group' => (!isset($input['blood_group']) || $input['blood_group'] == '')? null : $input['blood_group'], 
      'present_area' => (!isset($input['present_area']) || $input['present_area'] == '')? null : $input['present_area'], 
      'present_country' => (!isset($input['present_country']) || $input['present_country'] == '')? null : $input['present_country'], 
      'present_state' => (!isset($input['present_state']) || $input['present_state'] == '')? null : $input['present_state'], 
      'present_district' => (!isset($input['present_district']) || $input['present_district'] == '')? null : $input['present_district'], 
      'present_pincode' => (!isset($input['present_pincode']) || $input['present_pincode'] == '')? null : $input['present_pincode'],
      'permanent_area' => (!isset($input['permanent_area']) || $input['permanent_area'] == '')? null : $input['permanent_area'], 
      'permanent_country' => (!isset($input['permanent_country']) || $input['permanent_country'] == '')? null : $input['permanent_country'], 
      'permanent_state' => (!isset($input['permanent_state']) || $input['permanent_state'] == '')? null : $input['permanent_state'], 
      'permanent_district' => (!isset($input['permanent_district']) || $input['permanent_district'] == '')? null : $input['permanent_district'], 
      'permanent_pincode' => (!isset($input['permanent_pincode']) || $input['permanent_pincode'] == '')? null : $input['permanent_pincode'], 
      'introducer_name' => (!isset($input['introducer_name']) || $input['introducer_name'] == '')? null : $input['introducer_name'], 
      'introducer_number' => (!isset($input['introducer_number']) || $input['introducer_number'] == '')? null : $input['introducer_number'], 
      'union_location_country' => (!isset($input['union_location_country']) || $input['union_location_country'] == '')? null : $input['union_location_country'], 
      'union_location_state' => (!isset($input['union_location_state']) || $input['union_location_state'] == '')? null : $input['union_location_state'], 
      'union_location_district' => (!isset($input['union_location_district']) || $input['union_location_district'] == '')? null : $input['union_location_district'], 
      'location_type' => (!isset($input['location_type']) || $input['location_type'] == '')? null : $input['location_type'], 
      'present_address' => (!isset($prsentAddress) || $prsentAddress == '')? null : $prsentAddress, 
      'permanent_address' => (!isset($permanentAddress) || $permanentAddress == '')? null : $permanentAddress,
      'member_photo'=> (!isset($memberImagepath) || $memberImagepath == '')? null : $memberImagepath,
      'client_user_id'=> $usersId
    );
    return $this->db->insert('header_leader_registration',$data);
  }

  public function member_registration_updatebypost($profileimgurl, $id){
    $memberImagepath = '';
    if($profileimgurl['file_name'] != '') {
      $memberImagepath = $profileimgurl['file_name'];
    }
    $input = $this->input->post();

    // $prscountryname = $this->db->where('id',$input['present_country'])->get('country_tbl')->row();
    // $prsentCountry = '';
    // if (!empty($prscountryname)) {
    //   $prsentCountry = $prscountryname->country;
    // }
    // $prsStatename = $this->db->where('id',$input['present_state'])->get('state_tbl')->row();
    // $presentStatename = '';
    // if (!empty($prsStatename)) {
    //   $presentStatename = $prsStatename->state;
    // }

    // $prsDistrictname = $this->db->where('id',$input['present_district'])->get('district_tbl')->row();
    // $presentDistrictname = '';
    // if (!empty($prsDistrictname)) {
    //   $presentDistrictname = $prsDistrictname->district;
    // }

    // $percountryname = $this->db->where('id',$input['permanent_country'])->get('country_tbl')->row();
    // $permanentCountry = '';
    // if (!empty($percountryname)) {
    //   $permanentCountry = $percountryname->country;
    // }
    // $perStatename = $this->db->where('id',$input['permanent_state'])->get('state_tbl')->row();
    // $permanentStatename = '';
    // if (!empty($perStatename)) {
    //   $permanentStatename = $perStatename->state;
    // }

    // $perDistrictname = $this->db->where('id',$input['permanent_district'])->get('district_tbl')->row();
    // $permanentDistrictname = '';
    // if (!empty($perDistrictname)) {
    //   $permanentDistrictname = $perDistrictname->district;
    // }

    $dob = (!isset($input['from_year']) || $input['from_year'] == '')? '' : $input['from_year'].'-'.(!isset($input['from_month']) || $input['from_month'] == '')? '' : $input['from_month'].'-'.(!isset($input['from_date']) || $input['from_date'] == '')? '' : $input['from_date'];
    // $prsentAddress = $input['present_area'].','.$presentDistrictname.','.$presentStatename.','.$prsentCountry.','.$input['present_pincode'];
    // $permanentAddress = $input['permanent_area'].','.$perDistrictname.','.$perStatename.','.$percountryname.','.$input['permanent_pincode'];

    $data = array(
      'full_name' => (!isset($input['full_name']) || $input['full_name'] == '')? null : $input['full_name'], 
      'display_name' => (!isset($input['display_name']) || $input['display_name'] == '')? null : $input['display_name'], 
      'mobile_number' => (!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'], 
      'email_id' => (!isset($input['email_id']) || $input['email_id'] == '')? null : $input['email_id'], 
      'father_name' => (!isset($input['father_name']) || $input['father_name'] == '')? null : $input['father_name'], 
      'mother_name' => (!isset($input['mother_name']) || $input['mother_name'] == '')? null : $input['mother_name'], 
      'date_of_birth' => (!isset($dob) || $dob == '')? null : date('Y-m-d',strtotime($dob)),
      'gender' => (!isset($input['gender']) || $input['gender'] == '')? null : $input['gender'], 
      'nationality' => (!isset($input['nationality']) || $input['nationality'] == '')? null : $input['nationality'], 
      'martial_status' => (!isset($input['martial_status']) || $input['martial_status'] == '')? null : $input['martial_status'], 
      'spouse_name' => (!isset($input['spouse_name']) || $input['spouse_name'] == '')? null : $input['spouse_name'], 
      'education' => (!isset($input['education']) || $input['education'] == '')? null : $input['education'], 
      'work_profession' => (!isset($input['work_profession']) || $input['work_profession'] == '')? null : $input['work_profession'], 
      'languages_known' => (!isset($input['languages_known']) || $input['languages_known'] == '')? null : $input['languages_known'], 
      'blood_group' => (!isset($input['blood_group']) || $input['blood_group'] == '')? null : $input['blood_group'], 
      'present_area' => (!isset($input['present_area']) || $input['present_area'] == '')? null : $input['present_area'], 
      'present_country' => (!isset($input['present_country']) || $input['present_country'] == '')? null : $input['present_country'], 
      'present_state' => (!isset($input['present_state']) || $input['present_state'] == '')? null : $input['present_state'], 
      'present_district' => (!isset($input['present_district']) || $input['present_district'] == '')? null : $input['present_district'], 
      'present_pincode' => (!isset($input['present_pincode']) || $input['present_pincode'] == '')? null : $input['present_pincode'],
      'permanent_area' => (!isset($input['permanent_area']) || $input['permanent_area'] == '')? null : $input['permanent_area'], 
      'permanent_country' => (!isset($input['permanent_country']) || $input['permanent_country'] == '')? null : $input['permanent_country'], 
      'permanent_state' => (!isset($input['permanent_state']) || $input['permanent_state'] == '')? null : $input['permanent_state'], 
      'permanent_district' => (!isset($input['permanent_district']) || $input['permanent_district'] == '')? null : $input['permanent_district'], 
      'permanent_pincode' => (!isset($input['permanent_pincode']) || $input['permanent_pincode'] == '')? null : $input['permanent_pincode'], 
      'introducer_name' => (!isset($input['introducer_name']) || $input['introducer_name'] == '')? null : $input['introducer_name'], 
      'introducer_number' => (!isset($input['introducer_number']) || $input['introducer_number'] == '')? null : $input['introducer_number'],
      'member_id_number' => (!isset($input['member_id_number']) || $input['member_id_number'] == '')? null : $input['member_id_number'],
      'validity_type' => (!isset($input['validity_type']) || $input['validity_type'] == '')? null : $input['validity_type'],
      'status' => (!isset($input['member_status']) || $input['member_status'] == '')? null : $input['member_status'],
      // 'present_address' => (!isset($prsentAddress) || $prsentAddress == '')? null : $prsentAddress, 
      // 'permanent_address' => (!isset($permanentAddress) || $permanentAddress == '')? null : $permanentAddress,
    );
    if (!empty($memberImagepath['file_name'])) {
      $data = array_merge($data,['member_photo' => $memberImagepath['file_name']]);
    }
    $this->db->where('id',$id);
    return $this->db->update('member_registration',$data);
  }

  public function director_registration_insertbypost($profileimgurl){
    $memberImagepath = '';
    if($profileimgurl['file_name'] != '') {
      $memberImagepath = $profileimgurl['file_name'];
    }

    $input = $this->input->post();

    $data = array(
      'name' => (!isset($input['director_name']) || $input['director_name'] == '')? null : $input['director_name'], 
      'designation' => (!isset($input['designation']) || $input['designation'] == '')? null : $input['designation'], 
      'mobile_number' => (!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'], 
      'email_id' => (!isset($input['email_id']) || $input['email_id'] == '')? null : $input['email_id'], 
      'description' => (!isset($input['description']) || $input['description'] == '')? null : $input['description'], 
      'address' => (!isset($input['address']) || $input['address'] == '')? null : $input['address'], 
      'name_of_media' => (!isset($input['name_of_media']) || $input['name_of_media'] == '')? null : $input['name_of_media'],
      'union_location_country' => (!isset($input['union_location_country']) || $input['union_location_country'] == '')? null : $input['union_location_country'], 
      'union_location_state' => (!isset($input['union_location_state']) || $input['union_location_state'] == '')? null : $input['union_location_state'], 
      'union_location_district' => (!isset($input['union_location_district']) || $input['union_location_district'] == '')? null : $input['union_location_district'], 
      'designation_in_media' => (!isset($input['designation_in_media']) || $input['designation_in_media'] == '')? null : $input['designation_in_media'], 
      'photo'=> (!isset($memberImagepath) || $memberImagepath == '')? null : $memberImagepath,
      'client_user_id'=> $this->ion_auth->user()->row()->id
    );
    return $this->db->insert('director_registration',$data);
  }

  public function director_registration_updatebypost($profileimgurl, $id){
   
    $input = $this->input->post();

    $data = array(
      'name' => (!isset($input['director_name']) || $input['director_name'] == '')? null : $input['director_name'], 
      'designation' => (!isset($input['designation']) || $input['designation'] == '')? null : $input['designation'], 
      'mobile_number' => (!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'], 
      'email_id' => (!isset($input['email_id']) || $input['email_id'] == '')? null : $input['email_id'], 
      'description' => (!isset($input['description']) || $input['description'] == '')? null : $input['description'], 
      'address' => (!isset($input['address']) || $input['address'] == '')? null : $input['address'], 
      'name_of_media' => (!isset($input['name_of_media']) || $input['name_of_media'] == '')? null : $input['name_of_media'],
      'union_location_country' => (!isset($input['union_location_country']) || $input['union_location_country'] == '')? null : $input['union_location_country'], 
      'union_location_state' => (!isset($input['union_location_state']) || $input['union_location_state'] == '')? null : $input['union_location_state'], 
      'union_location_district' => (!isset($input['union_location_district']) || $input['union_location_district'] == '')? null : $input['union_location_district'], 
      'designation_in_media' => (!isset($input['designation_in_media']) || $input['designation_in_media'] == '')? null : $input['designation_in_media']
    );
     if (!empty($profileimgurl['file_name'])) {
      $data = array_merge($data,['photo' => $profileimgurl['file_name']]);
    }
          $this->db->where('id',$id);
    return $this->db->update('director_registration',$data);
  }

  public function union_staff_registration_insertbypost($profileimgurl){
    $memberImagepath = '';
    if($profileimgurl['file_name'] != '') {
      $memberImagepath = $profileimgurl['file_name'];
    }

    $input = $this->input->post();

    $data = array(
      'name' => (!isset($input['director_name']) || $input['director_name'] == '')? null : $input['director_name'], 
      'designation' => (!isset($input['designation']) || $input['designation'] == '')? null : $input['designation'], 
      'mobile_number' => (!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'], 
      'email_id' => (!isset($input['email_id']) || $input['email_id'] == '')? null : $input['email_id'], 
      'address' => (!isset($input['address']) || $input['address'] == '')? null : $input['address'],      
      'union_location_country' => (!isset($input['union_location_country']) || $input['union_location_country'] == '')? null : $input['union_location_country'], 
      'union_location_state' => (!isset($input['union_location_state']) || $input['union_location_state'] == '')? null : $input['union_location_state'], 
      'union_location_district' => (!isset($input['union_location_district']) || $input['union_location_district'] == '')? null : $input['union_location_district'],
      'photo'=> (!isset($memberImagepath) || $memberImagepath == '')? null : $memberImagepath,
      'client_user_id'=> $this->ion_auth->user()->row()->id
    );
    return $this->db->insert('union_staff_registration',$data);
  }

  public function get_members_list(){
    $userid = $this->ion_auth->user()->row()->id;
    return $this->db->where('client_user_id',$userid)->where('status','Approved')->get('member_registration')->result();
  }

  public function get_director_list(){
    $userid = $this->ion_auth->user()->row()->id;
    return $this->db->where('client_user_id',$userid)->get('director_registration')->result();
  }

  public function get_header_leader_list($type){
    $userid = $this->ion_auth->user()->row()->id;
    return $this->db->where('client_user_id',$userid)->where('location_type',$type)->get('header_leader_registration')->result();
  }

  public function get_union_staff_list($type){
    $userid = $this->ion_auth->user()->row()->id;
    return $this->db->where('client_user_id',$userid)->get('union_staff_registration')->result();
  }
 

  public function member_registration_deletebyId($id){
    return $this->db->where('id',$id)->delete('member_registration');
  }

  public function header_leader_registration_deletebyId($id){
    return $this->db->where('id',$id)->delete('header_leader_registration');
  }
  public function director_registration_deletebyId($id){
    return $this->db->where('id',$id)->delete('director_registration');
  }

  public function director_union_staff_deletebyId($id){
    return $this->db->where('id',$id)->delete('union_staff_registration');
  }

  public function get_apply_database(){
    return $this->db->get('apply_now')->result();
  }

  public function insert_media_categroy_details(){
    $input = $this->input->post();
    $data = array(
      'category' => $input['media_category'], 
      'group_id' => $input['group_id'], 
      'media_type' => $input['media_type'], 
    );
    return $this->db->insert('media_category', $data);
  }

  public function get_media_category($groupId,$mediaType){
    return $this->db->select('*')
    ->from('media_category')
    ->where('group_id',$groupId)
    ->where('media_type',$mediaType)
    ->get()->result();
  }

  public function get_needy_category($groupId,$needy_type){
    return $this->db->select('*')
    ->from('needy_category')
    ->where('group_id',$groupId)
    ->where('needy_type',$needy_type)
    ->get()->result();
  }

  public function insert_needy_categroy_details(){
    $input = $this->input->post();
    $data = array(
      'category' => $input['needy_category'], 
      'group_id' => $input['group_id'], 
      'needy_type' => $input['needy_type'], 
    );
    return $this->db->insert('needy_category', $data);
  }
  
  public function delete_media_categroy_details($cat_id){
      $this->db->where('id',$cat_id);
      $this->db->delete('media_category');
  }

  public function delete_needy_categroy_details($cat_id){
      $this->db->where('id',$cat_id);
      $this->db->delete('needy_category');
  }

  public function insert_upload_needy_category_img($cat_img, $cat_id){
    if (!empty($cat_img)) {
      $path = "uploads/needy_category/".$cat_img['orig_name'];
    }else{
      $path = $this->input->post('category_edit');
    }

    $data = array(     
      'category_img' => $path
    );
            $this->db->where('id',$cat_id);
    $query = $this->db->get('needy_category')->row();
    if (!empty($query)) {
      $this->db->where('id',$query->id);
      return $this->db->update('needy_category',$data);
    }else{
      return $this->db->insert('needy_category',$data);
    }
  }

  public function get_needy_category_by_type($type, $group_id){
    return $this->db->select('nc.*')
      ->from('needy_category nc')
      ->where('needy_type',$type)
      ->where('group_id',$group_id)
      ->where('nc.id not in (select needy_category from needy_client_services_details)')
      ->get()->result();
  }

  public function get_group_created_category_single($table_name, $columName, $category){
    return $this->db->select('cat.*')
    ->from($table_name.' cat')
    ->where($columName,$category)
    ->get()->result();
  }
  public function get_group_created_category_sub($table_name, $columName, $category){
    return $this->db->select('cat.*, gsc.id as sub_cat_id, gsc.sub_category')
    ->from($table_name.' cat')
    ->join('group_sub_category gsc','cat.id=gsc.category_id','left')
    ->where($columName,$category)
    // ->group_by('gsc.category_id')
    ->get()->result();
  }
  public function get_group_created_category_sub_sub($table_name, $columName, $category){
      return $this->db->select('cat.*, gsc.id as sub_cat_id, gsc.sub_category, gssc.id as sub_sub_cat_id, gssc.sub_sub_category')
      ->from($table_name.' cat')
      ->join('group_sub_category gsc','cat.id=gsc.category_id','left')
      ->join('group_sub_sub_category gssc','gsc.id=gssc.sub_category_id','left')
      ->where($columName,$category)
      ->get()->result();
  }

  public function insert_group_created_category($table_name,$columName, $input, $category, $group_id = 0){
    $data = array(
      'category' => $input['category'], 
      $columName => $category, 
    );

    return $this->db->insert($table_name, $data);
  }

  public function insert_cat_img_group_created($table_name, $cat_img, $cat_id){
    if (!empty($cat_img)) {
      $path = "uploads/group_category/".$cat_img['orig_name'];
    }else{
      $path = $this->input->post('category_edit');
    }
    $data = array(     
      'category_img' => $path
    );
    $this->db->where('id',$cat_id);
    return $this->db->update($table_name,$data);
    
  }

  public function insert_group_created_sub_category($group_name,$input,$category,$cat_id){
    $data = array(
      'group_name' => $group_name, 
      'category_id' => $cat_id, 
      'sub_category' => $input['sub_category'], 
      'category_type' => $category,
    );
    return $this->db->insert('group_sub_category', $data);
  }

  public function insert_group_created_sub_sub_category($group_name,$input,$category,$cat_id,$sub_cat_id){
    $data = array(
      'group_name' => $group_name, 
      'category_id' => $cat_id, 
      'sub_category_id' => $sub_cat_id, 
      'sub_sub_category' => $input['sub_sub_category'], 
      'category_type' => $category,
    );
    
    return $this->db->insert('group_sub_sub_category', $data);
  }

  public function get_category_wise_sub_categoryby_id($group_name, $category_id){
    return $this->db->select('*')
      ->from('group_sub_category')
      ->where('category_id',$category_id)
      ->where('group_name',$group_name)
      ->get()->result();
  }

   public function save_mytv_public_form($data, $table, $imgs, $videoPath){
      $insertId =  $this->db->insert($table, $data);
      $insert_id = $this->db->insert_id();

      if (!empty($videoPath)) {
        $saveImages = array(
          'image' => $videoPath,
          'mytv_public_id' =>$insert_id
        );
        return $this->db->insert('mytv_public_img',$saveImages);
      }
      if (!empty($imgs)) {
        $saveImages = [];
        foreach ($imgs as $key => $img) {
          $saveImages[] = array(
            'image' => $img['file_name'],
            'mytv_public_id' =>$insert_id
          );
        }
        return $this->db->insert_batch('mytv_public_img',$saveImages);
      }else{
        return $insert_id;
      }
    }

    public function union_category_get_by_type($union_type){
      return $this->db->select('*')
      ->from('myunions_category')
      ->where('unions_type',$union_type)
      ->get()->result();
    }

    public function media_category_get_by_type($media_type){
      return $this->db->select('*')
      ->from('media_category')
      ->where('media_type',$media_type)
      ->get()->result();
    }

    public function get_member_unions_category(){
      return $this->db->select('*')
      ->from('myunions_category')
      ->where('unions_type','unions')
      ->get()->result();
    }

    public function get_admin_enabled_fields(){
      $userid = $this->ion_auth->user()->row()->id;
      $unionCat = $this->db->select('category')
      ->from('client_registration')
      ->where('user_id',$userid)
      ->get()->row();
      if (!empty($unionCat)) {
        $groupFields = $this->db->select('*')
        ->from('group_config gc')
        ->where('category_id',$unionCat->category)
        ->where('created_by','Admin')
        ->get()->result();
      }
      return $groupFields;
    }

    public function get_parter_register_list(){
      // $user_id = $this->ion_auth->user()->row()->id;
      return $this->db->select('cr.*, mc.category as cat_name, u.email, u.first_name, u.phone, ifnull(cr.status,0) as status')
      ->from('client_registration cr')
      ->join('myunions_category mc','cr.category=mc.id')
      ->join('users u','cr.user_id=u.id')
      // ->where('cr.user_id',$user_id)
      ->where('cr.group_id','13')
      ->get()->result();
    }

    public function get_client_registration($groupId,$mediaType){
      return $this->db->select('cr.*, u.email, u.first_name, u.phone, ifnull(cr.status,0) as status')
      ->from('client_registration cr')
      ->join('users u','cr.user_id=u.id')
      ->join('users_groups ug','u.id=ug.user_id')
      ->join('groups g','ug.group_id=g.id')
      ->where('cr.group_id',$groupId)
      ->where('g.name',$mediaType)
      ->get()->result();
    }

    public function get_id_card_view_html($id){
      $userId = $this->ion_auth->user()->row()->id;
      $template = $this->db->select('id_card_template')->where('user_id',$userId)->get('client_registration')->row()->id_card_template;
      if (!empty($template)) {
        $members = $this->db->select('mr.*, dt.district as district_name')
        ->from('member_registration mr')
        ->join('district_tbl dt','mr.present_district=dt.id','left')
        ->where('mr.id',$id)
        ->get()->row();
        $memberPhoto = '';
        if ($members->member_photo !='') {
          $memberphotoPath = $this->filemanager->getFilePath($members->member_photo);
          $memberPhoto = '<img class="memberPhoto" src="'.$memberphotoPath.'" />';
        }
        $template = str_replace('%%full_name%%',$members->full_name, $template);
        $template = str_replace('%%designation%%',$members->work_profession, $template);
        $template = str_replace('%%media_name%%','NA', $template);
        $template = str_replace('%%place%%',$members->district_name, $template);
        $template = str_replace('%%id_card_number%%','NA', $template);
        $template = str_replace('%%validate_up_to%%','NA', $template);
        $template = str_replace('%%member_photo%%',$memberPhoto, $template);      
        return $template;
      }else{
        return '';
      }
      
    }
    
    public function get_union_location_type_selection(){
      $usersId = $this->ion_auth->user()->row()->id;
      $this->db->select('type as location_type, country as union_country, state as union_state, district as union_district');
      $this->db->where('user_id',$usersId);
      return $this->db->get('client_registration')->row();
    }

    public function get_union_location_type_selection_me($client_user_id){
      $this->db->select('type as location_type, country as union_country, state as union_state, district as union_district');
      $this->db->where('user_id',$client_user_id);
      return $this->db->get('client_registration')->row();
    }

    public function update_invite_union_status($mobileNumber){
      $this->db->where('mobile_number', $mobileNumber);
      return $this->db->update('unions_invate_application_data',array('status'=>'2'));
    }

    public function union_partner_switch_status_by_id($stngId,$value){
       $data = array(
            'status' => $value,
        );
        $this->db->where('id',$stngId);
        return $this->db->update('client_registration', $data);
    }

    public function union_director_switch_checkby_id($stngId,$value){
      $data = array(
          'status' => $value,
      );
      $this->db->where('id',$stngId);
      return $this->db->update('director_registration', $data);
    }

    public function view_partner_details_by_id($id){

      return  $this->db->select('cr.*, u.phone, u.first_name, u.email')
        ->from('client_registration cr')
        ->join('users u','cr.user_id=u.id')
        ->where('cr.id', $id)
        ->get()->row();
      // $category =  $this->db->where('group_id',$partner->group_id)->get('category')->result();

      // $partner->category_list = $category;
      return $partner;
    }

    public function update_union_parter_details_by_admin($id)  {
      $input = $this->input->post();
      $data = array( 
        'type' => $input['type'],
        'country' => $input['country'],
        'state' => $input['state'],
        'district' => $input['district'],
        'category' => $input['category'],
        'union_type' => $input['union_type'],
      );
      $this->db->where('id',$id);
      return $this->db->update('client_registration',$data);
    }

    public function get_member_validity_details($client_user_id){
      $this->db->where('client_user_id',$client_user_id);
      $result =  $this->db->get('union_validity')->row();
      $memberShip = [];
      if (!empty($result)) {
         if ($result->member_life_time == 'Yes') {
          $memberShip[$result->life_member_ship_amount] ='Life Time Membership';
        }
        if ($result->member_others == 'Auto') {
            $selection = json_decode($result->auto_selection);
            $selectionAmount = json_decode($result->auto_fresher_amount);
            foreach ($selection as $key => $val) {
              $memberShip[$selectionAmount[$key]] = str_replace('_', ' ', $val) ;
            }
        }
      }
     
      return $memberShip;
      
      // foreach ($result as $key => $val) {
      //     if ($val->member_ship_validity == 'Yes') {
      //         if ($val->member_ship_validity == 'Yes') {
      //           if ($val->member_life_time == 'Yes') {
      //             $selection = json_decode($val->auto_selection);
      //             $selectionAmount = json_decode($val->auto_fresher_amount);
      //             echo "<pre>"; print
      //             // $validity[$val->member_others] = $val->auto_selection[''];

      //             // $validity['Life Time'] = $val->life_member_ship_amount;
      //           }
      //         }
      //     }
      // }
    }

    public function check_user_activation(){
      $usersId = $this->ion_auth->user()->row()->id;
      return $this->db->select('status')
      ->from('client_registration')
      ->where('user_id',$usersId)
      ->get()->row();
    }

    public function get_director_details_by_id($id){
       return $this->db->select('*')
      ->from('director_registration')
      ->where('id',$id)
      ->get()->row();
    }

    public function get_franchise_offer_ads(){
      return $this->db->select('*')
      ->from('franchise_ads')
      ->get()->result();
    }

    public function get_newsroom_latest_data(){
      return $this->db->select('*')
      ->from('newsroom')
      ->order_by('id','desc')
      ->get()->row();
    }
    public function get_gallery_latest_data(){
      return $this->db->select('*')
      ->from('gallery_images_master')
      ->order_by('image_id','desc')
      ->get()->row();
    }
    public function get_awards_latest_data(){
      return $this->db->select('*')
      ->from('awards')
      ->order_by('id','desc')
      ->get()->row();
    }
    public function get_events_latest_data(){
      return $this->db->select('*')
      ->from('events')
      ->order_by('id','desc')
      ->get()->row();
    }

    public function get_testimonials_data(){
      return $this->db->select('*')
      ->from('testimonials')
      ->order_by('id','desc')
      ->limit(4)
      ->get()->result();
    }

  //    public function get_all_apsname(){
  //   $result = $this->db->select('gc.id, gc.apps_name, gc.name, cd.icon, cd.url')
  //   ->from('group_create gc')
  //   ->join('create_details cd','gc.id=cd.create_id')
  //   ->order_by('gc.id')
  //   ->get()->result();

  //   $totalApps = [];
  //   foreach ($result as $key => $val) {
  //     $totalApps[$val->apps_name][] = $val;
  //   }
  //   return $totalApps;
  // }
  // public function get_topnav_icon_list(){
  //   $myAps =  $this->db->select('gc.name, cd.*')
  //   ->from('group_create gc')
  //   ->or_where('gc.apps_name','My Apps')
  //   ->join('create_details cd','gc.id=cd.create_id')
  //   ->order_by('gc.id')
  //   ->get()->result();

  //   $myCompany = $this->db->select('gc.name, cd.*')
  //   ->from('group_create gc')
  //   ->or_where('gc.apps_name','My Company')
  //   ->join('create_details cd','gc.id=cd.create_id')
  //   ->order_by('gc.id')
  //   ->get()->result();

  //   $online = $this->db->select('gc.name, cd.*')
  //   ->from('group_create gc')
  //   ->or_where('gc.apps_name','My Onine Apps')
  //   ->join('create_details cd','gc.id=cd.create_id')
  //   ->order_by('gc.id')
  //   ->get()->result();

  //   $offline = $this->db->select('gc.name, cd.*')
  //   ->from('group_create gc')
  //   ->or_where('gc.apps_name','My Offline Apps')
  //   ->join('create_details cd','gc.id=cd.create_id')
  //   ->order_by('gc.id')
  //   ->get()->result();

  //   return array('myapps' => $myAps, 'myCompany' => $myCompany,'online' => $online,'offline' => $offline);
  // }

    public function get_all_application_details(){
      return $this->db->select('*')
      ->from('my_aps_about_details')
      ->get()->result();
    }

    public function get_application_details_edit($id){
      return $this->db->select('*')
      ->from('my_aps_about_details')
      ->where('id',$id)
      ->get()->row();
    }

    public function get_group_wise_app_byselection_data($groupName){
      return $this->db->select('gc.id, gc.apps_name, gc.name')
      ->from('group_create gc')
      ->where('gc.apps_name',$groupName)
      ->get()->result();
    }

    public function application_details_delete_by_id($id){
      $this->db->where('id',$id);
      return $this->db->delete('my_aps_about_details');
    }
}
?>