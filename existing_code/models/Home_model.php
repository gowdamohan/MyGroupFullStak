<?php

class Home_model extends CI_Model{
  
  public function __construct(){
    parent::__construct();
  }


  public function insert_user_registration_details($user_id){
    $input = $this->input->post();
    $dob = $input['from_year'].'-'.$input['from_month'].'-'.$input['from_date'];
    $data = array(
      // 'country_flag' => $input['country_flag'], 
      'country_code' => $input['country_code'],
      'gender' => $input['gender'],
      'dob' => date('Y-m-d', strtotime($dob)),
      'country' => $input['country'], 
      'state' => $input['state'],
      'district' => $input['district'],
      'education' => $input['education'],
      'profession' => $input['profession'],
      'education_others' => isset($input['education_others']) ? $input['education_others'] : '',
      'work_others' => isset($input['work_others']) ? $input['work_others'] : '',
      'user_id' => $user_id,
      'dob_date' =>$input['from_date'],
      'dob_month' => $input['from_month'],
      'dob_year' => $input['from_year'],
      // 'country_flag_alter' => $input['country_flag_alter'],
      'country_code_alter' => $input['country_code_alter'],
      'nationality' => $input['nationality'],
      'mobile_number_alter' => $input['mobile_number_alter'],
      'marital' => $input['marital'],
    );
    return $this->db->insert('user_registration_form',$data);
  }

  public function insert_client_registration_details(){
    $input = $this->input->post();
    $userData = array(
      'first_name'=> $input['first_name'],
      'phone'=> $input['mobile_number'],
      'active'=> 1,
    );
    $this->db->where('id',$input['user_id']);
    $this->db->update('users',$userData);
    $data = array(
      'name_of_the_organization' => $input['name_of_the_organization'], 
      'type' => $input['type'],
      'country' => $input['country'],
      'state' => $input['state'],
      'district' => $input['district'], 
      'pin_code' => $input['pin_code'], 
      'address' => $input['address'], 
      'category' => $input['category'],
      'union_type' => $input['union_type'],
      'user_id' => $input['user_id'],
      'group_id' => $input['group_id'],
    );
    return $this->db->insert('client_registration',$data);
  }
  
  public function profile_edit_by_id($user_id){
    return $this->db->select("u.id as user_id, username, u.email, u.first_name, u.last_name, u.phone, u.profile_img, u.display_name, urf.*, ifnull(u.alter_number,'') as alter_number ")
    ->from('users u')
    ->where('u.id',$user_id)
    ->join('user_registration_form urf','u.id=urf.user_id')
    ->get()->row();
  }

  public function update_user_registration_details($id, $input){
    $input = $this->input->post();
    $dob = $input['from_year'].'-'.$input['from_month'].'-'.$input['from_date'];
    $data = array(
      // 'country_flag' => $input['country_flag'], 
      'country_code' => $input['country_code'],
      'gender' => $input['gender'],
      'dob' => $dob,
      'country' => $input['country'], 
      'state' => $input['state'],
      'district' => $input['district'],
      'education' => $input['education'],
      'profession' => $input['profession'],
      'education_others' => isset($input['education_others']) ? $input['education_others'] : '',
      'work_others' => isset($input['work_others']) ? $input['work_others'] : '',
      'dob_date' =>$input['from_date'],
      'dob_month' => $input['from_month'],
      'dob_year' => $input['from_year'],
      // 'country_flag_alter' => $input['country_flag_alter'],
      'country_code_alter' => $input['country_code_alter'],
      'mobile_number_alter' => $input['mobile_number_alter'],
      'nationality' => $input['nationality'],
      'marital' => $input['marital'],
    );
          $this->db->where('user_id',$id);
    return $this->db->update('user_registration_form',$data);
  }

    public function load_galleries(){
        $galleries =    $this->db->select("gl.gallery_id, gl.gallery_name, gim.image_name as image_name, count(gim.gallery_id) as img_count, gl.gallery_date")
            ->from('gallery_list gl')
            ->join('gallery_images_master gim', 'gim.gallery_id=gl.gallery_id')
            ->group_by('gl.gallery_id')
            ->order_by('gl.gallery_id', 'DESC')
            ->get()->result();

        $today = date('Y-m-d');
        foreach ($galleries as $key => $val) {
            $weekPlus = date('Y-m-d', strtotime("+7 day", strtotime($val->gallery_date)));
            $val->is_new = 0;
            if($today >= $val->gallery_date && $today <= $weekPlus) {
                $val->is_new = 1;
            }
        }
        return $galleries;
    }

    public function contact_insert_data(){
      $input = $this->input->post();
      $data = array(
          'first_name' => $input['first_name'],
          'email' => $input['email_id'],
          'phone_number' =>$input['mobile_number'],
          'comment' => $input['comments']
      );
      $this->db->insert('contact_form', $data);
      return $this->db->insert_id();
    }

     public function insert_user_message($message, $userId, $displayName){
      $data = array(
        'display_name' => $displayName, 
        'in_out' => 'in', 
        'message' => $message, 
        'user_id' => $userId,
        'status' => 0,
      );

      return $this->db->insert('feedback_suggetions',$data);
    }

    public function insert_feeback_suggetion($message, $userId){
      $data = array(
        'message' => $message, 
        'user_id' => $userId
      );
      return $this->db->insert('feedback_suggetions_user',$data);
    }

    public function updateProfilePhoto($userId, $profileimgurl){
        $photos = array();
        if($profileimgurl['file_name'] != '') {
          $photos['profile_img'] = $profileimgurl['file_name'];
        }
        return $this->db->where('id', $userId)->update('users', $photos);
    }

    public function get_group_name_detailsbyname($groupname){
      $this->db->where('name',$groupname);
      return $this->db->get('group_create')->row();
    }

    public function get_group_header_adsbyId($groupId){
      $this->db->select('ads1 as header_ads1, ads2 as header_ads2, ads3 as header_ads3, ads1_url as header_ads_url_1, ads2_url as header_ads_url_2, ads3_url as header_ads_url_3');
      $this->db->from('aderttise');
      $this->db->where('create_id',$groupId);
      return $this->db->get()->row();
    }
    public function get_group_logo_byId($groupId){
      $this->db->where('create_id',$groupId);
      return $this->db->get('create_details')->row();
    }

    public function get_poupads_groupbyId($groupId){
      $this->db->where('group_id',$groupId);
      return $this->db->get('popup_ads')->row();
    }

    public function get_country_list(){
      return $this->db->select('cn.continent, co.country, co.id, co.order, co.status, co.code, co.currency, co.phone_code, co.nationality')
      ->from('country_tbl co')
      ->join('continent_tbl cn','co.continent_id=cn.id')
      ->get()->result();
    }

    public function get_categorybygropuid($gropuId){
      return $this->db->where('group_id',$gropuId)->get('category')->result();
    }

    public function insert_apply_form($url_path){
      $dataArry = array();
      if($url_path['file_name'] != '') {
        $dataArry['upload_path'] = $url_path['file_name'];
        $dataArry['apply_for'] = $this->input->post('apply_for');
      }
      return $this->db->insert('apply_now', $dataArry);
    }

    public function insert_franchise_apply_form($url_path){
      $dataArry = array();
      $upload_path = '';
      if($url_path['file_name'] != '') {
        $upload_path = $url_path['file_name'];
      }
      $dataArry = array(
        'upload_path'=>$upload_path,
        'franchise_country'=>$this->input->post('franchise_country'),
        'franchise_state'=>$this->input->post('franchise_state'),
        'franchise_district'=>$this->input->post('franchise_district'),
        'applier_name'=>$this->input->post('applier_name'),
        'applyeer_mobile'=>$this->input->post('applyeer_mobile'),
        'applyeer_email_id'=>$this->input->post('applyeer_email_id'),
        'laptop_computer'=>$this->input->post('laptop_computer'),
        'govt_school'=>$this->input->post('govt_school'),
        'applier_education'=>$this->input->post('applier_education'),
        'applyeer_experience'=>$this->input->post('applyeer_experience'),
        'applyeer_any_other_details'=>$this->input->post('applyeer_any_other_details'),
      );
      return $this->db->insert('apply_franchise_now', $dataArry);
    }

    public function insert_job_apply_form($url_path){
      $dataArry = array();
      $upload_path = '';
      if($url_path['file_name'] != '') {
        $upload_path = $url_path['file_name'];
      }
      $dataArry = array(
        'upload_path'=>$upload_path,
        'franchise_country'=>$this->input->post('franchise_country'),
        'applier_name'=>$this->input->post('applier_name'),
        'applyeer_mobile'=>$this->input->post('applyeer_mobile'),
        'applyeer_email_id'=>$this->input->post('applyeer_email_id'),
        'applier_education'=>$this->input->post('applier_education'),
        'applyeer_experience'=>$this->input->post('applyeer_experience'),
        'applyeer_any_other_details'=>$this->input->post('applyeer_any_other_details'),
        'user_id'=>$this->input->post('job_user_id'),
        'job_type'=>$this->input->post('job_type'),
      );
      return $this->db->insert('apply_job_now', $dataArry);
    }

    public function get_mytv_public_list_details($notice_select_lang, $selectValue){
      $noticeType = strtolower($selectValue);
      $this->db->select('id');
      $this->db->from('mytv_public');
      $this->db->where('status','Publish');
      if ($notice_select_lang) {
        $this->db->where('language',$notice_select_lang);
      }
      if ($selectValue !='All') {
        $this->db->where('category',$noticeType);
      }
      $this->db->order_by('id','desc');
      $result =  $this->db->get()->result();
      $missingIds = [];
      foreach ($result as $key => $res) {
        array_push($missingIds, $res->id);
      }    
      return $missingIds;

    }

    public function get_mytv_public_list_ids_details($notice_ids){
      $this->db->select("nb.*, nbi.image,  date_format(created_on, '%d-%m-%Y') as post_date");
      $this->db->from('mytv_public nb');
      $this->db->join('mytv_public_img nbi','nb.id=nbi.mytv_public_id','left');
      $this->db->where('status','Publish');
      $this->db->where_in('nb.id',$notice_ids);
      $this->db->group_by('nb.id');
      $this->db->order_by('nb.id','desc');
      return $this->db->get()->result();
    }

    public function get_my_shop_category_sub_category_details($shop_type){
      $result = $this->db->select('mc.*')
      ->from('myshop_category mc')
      ->where('shop_type',$shop_type)
      //->join('group_sub_category gsc','mc.id=gsc.category_id','left')
      ->get()->result();
      // foreach ($result as $key => &$val) {
      //   $val->subcat = $this->db->select('gsc.*')
      //   ->from('group_sub_category gsc')
      //   ->where('gsc.category_id',$val->id)
      //   ->get()->result();
      // }
      foreach ($result as $key => &$cat) {
        $cat->subcat = $this->db->select('gsc.*')
        ->from('group_sub_category gsc')
        ->where('gsc.category_id',$cat->id)
        ->get()->result();
        foreach ($cat->subcat as $key => $val) {
          $val->subSubcat = $this->db->select('gssc.*')
          ->from('group_sub_sub_category gssc')
          ->where('gssc.sub_category_id',$val->id)
          ->get()->result();
        }
      }
      return $result;
      // echo "<pre>"; print_r($result); die();
      // $productList = [];
      // foreach ($result as $key => $val) {
      //   $productList[$val->id][$val->category][] = $val->sub_category;
      // }
     
      // return $productList;
    }

    public function get_my_shop_products_by_cat_id($cat_id){
      $result = $this->db->select('mc.*')
      ->from('myshop_category mc')
      ->where('id',$cat_id)
      ->get()->row();

      $result->sub_cat = $this->db->select('gsc.*')
      ->from('group_sub_category gsc')
      ->where('category_id',$cat_id)
      ->get()->result();

      $result->sub_sub_cat = $this->db->select('gssc.*')
      ->from('group_sub_sub_category gssc')
      ->where('category_id',$cat_id)
      ->get()->result();

      return $result;

    }

    public function get_my_shop_products_details_by_cat_id($cat_id, $shop_type){
      return $this->db->select('*')
      ->from('my_shop_prodcut_details')
      ->where('category',$cat_id)
      ->where('shop_type',$shop_type)
      ->get()->result();
    }

    public function get_my_shop_details_by_cat_id($cat_id, $shop_type){
      return $this->db->select('*')
      ->from('client_my_shop_details')
      ->where('category_id',$cat_id)
      ->where('shop_type',$shop_type)
      ->get()->result();
    }
    public function get_my_shop_products_details_by_sub_cat_id($sub_cat_id, $shop_type){
      return $this->db->select('*')
      ->from('my_shop_prodcut_details')
      ->where('sub_category',$sub_cat_id)
      ->where('shop_type',$shop_type)
      ->get()->result();
    }

    public function get_all_unions_category(){
      return $this->db->select('*')
      ->from('myunions_category')
      ->get()->result();
    }

    public function get_union_news_posted_list($location_id, $notice_selectType, $unions_cat, $selectValue){

      $noticeType = strtolower($selectValue);
      $this->db->select('cn.id as news_id');
      $this->db->from('client_news cn');
      $this->db->join('client_registration cr','cn.user_id=cr.user_id');

      if ($location_id) {
        if ($notice_selectType == 'national') {
         $this->db->where('cr.country',$location_id);
        }else if($notice_selectType == 'regional'){
          $this->db->where('cr.state',$location_id);
        }else{
          $this->db->where('cr.district',$location_id);
        }
      }
      if ($selectValue !='All') {
        $this->db->where('cn.category',$noticeType);
      }
      $this->db->order_by('cn.id','desc');
      $result =  $this->db->get()->result();
      $newsIds = [];
      foreach ($result as $key => $res) {
        array_push($newsIds, $res->news_id);
      }    
      return $newsIds;
    }

    public function get_union_news_posted_list_data($unionNewIds){
      $this->db->select("cn.*,  date_format(cn.created_date, '%d-%m-%Y') as post_date");
      $this->db->from('client_news cn');
      $this->db->where_in('cn.id',$unionNewIds);
      $this->db->order_by('cn.id','desc');
      return $this->db->get()->result();
    }

    public function get_unions_news_databy_id($unionNewId){
      $this->db->select("cn.*, date_format(cn.created_date, '%d-%m-%Y') as post_date");
      $this->db->from('client_news cn');
      $this->db->where('cn.id',$unionNewId);
      return $this->db->get()->row();
    }

    public function get_unions_notice_databy_id($unionNewId){
      $this->db->select("cn.*");
      $this->db->from('client_notice cn');
      $this->db->where('cn.id',$unionNewId);
      return $this->db->get()->row();
    }

    public function get_union_list_ids($union_type){
      $result =  $this->db->select('id as client_id')
      ->from('client_registration')
      ->where('group_id','13')
      ->where('union_type',$union_type)
      ->get()->result();
      $unionIds = [];
      foreach ($result as $key => $res) {
        array_push($unionIds, $res->client_id);
      }    
      return $unionIds;
    }

    public function get_union_list_databyIds($unions_ids, $union_type){
      return $this->db->select('cr.*, count(mr.id) as member_count')
      ->from('client_registration cr')
      ->where_in('cr.id', $unions_ids)
      ->join('member_registration mr','cr.user_id=mr.client_user_id','left')
      ->group_by('cr.id')
      ->where('group_id','13')
      ->where('union_type',$union_type)
      ->get()->result();
    }

    public function get_single_union_by_id($unionId){
       return $this->db->select('cr.*')
      ->from('client_registration cr')
      ->where('cr.id', $unionId)
      ->get()->row();
    }


    public function get_union_list_me($union_type){
      $mobilenumber = $this->ion_auth->user()->row()->username;
      $members = $this->db->select('mr.client_user_id, status as user_type')
      ->from('member_registration mr')
      ->where('mr.mobile_number',$mobilenumber)
      ->get()->result();

      $directory = $this->db->select('dr.client_user_id, "Director" as  user_type')
      ->from('director_registration dr')
      ->where('dr.mobile_number',$mobilenumber)
      ->get()->result();

      $staff = $this->db->select('usr.client_user_id, "Staff" as  user_type')
      ->from('union_staff_registration usr')
      ->where('usr.mobile_number',$mobilenumber)
      ->get()->result();

      $leaders = $this->db->select('hlr.client_user_id, "Leader" as  user_type')
      ->from('header_leader_registration hlr')
      ->where('hlr.mobile_number',$mobilenumber)
      ->get()->result();

      $invite = $this->db->select('uiad.client_user_id, "invite" as  user_type')
      ->from('unions_invate_application_data uiad')
      ->where('uiad.mobile_number',$mobilenumber)
      ->where('uiad.status',1)
      ->get()->result();
      $result = array_merge($members,$directory,$staff,$leaders, $invite);

      $unionMe = [];
      foreach ($result as $key => $res) {
        $result = $this->db->select('*')
        ->from('client_registration')
        ->where('user_id', $res->client_user_id)
        ->get()->row();
        $unionMe[$res->user_type] = $result;
      }      
      return $unionMe;
    }

    public function check_user_member_validate($client_user_id){
      $mobilenumber = $this->ion_auth->user()->row()->username;
      $members = $this->db->select('mr.client_user_id, status as user_type')
      ->from('member_registration mr')
      ->where('mr.mobile_number',$mobilenumber)
      ->where('mr.client_user_id',$client_user_id)
      ->get()->row();
      if (!empty($members)) {
        return 1;
      }

      $directory = $this->db->select('dr.client_user_id, "Director" as  user_type')
      ->from('director_registration dr')
      ->where('dr.mobile_number',$mobilenumber)
      ->where('dr.client_user_id',$client_user_id)
      ->get()->row();
      if (!empty($directory)) {
        return 1;
      }
      $staff = $this->db->select('usr.client_user_id, "Staff" as  user_type')
      ->from('union_staff_registration usr')
      ->where('usr.mobile_number',$mobilenumber)
      ->where('usr.client_user_id',$client_user_id)
      ->get()->row();

      if (!empty($staff)) {
        return 1;
      }
      $leaders = $this->db->select('hlr.client_user_id, "Leader" as  user_type')
      ->from('header_leader_registration hlr')
      ->where('hlr.mobile_number',$mobilenumber)
      ->where('hlr.client_user_id',$client_user_id)
      ->get()->row();

      if (!empty($leaders)) {
        return 1;
      }

      $invite = $this->db->select('uiad.client_user_id, "invite" as  user_type')
      ->from('unions_invate_application_data uiad')
      ->where('uiad.mobile_number',$mobilenumber)
      ->where('uiad.client_user_id',$client_user_id)
      ->get()->row();
      if (!empty($invite)) {
       return 1;
      }
      return 0;
    }

    public function get_partner_details_by_client_id($client_user_id){
      return $this->db->select('cr.*, u.phone as mobilenumber')
        ->from('client_registration cr')
        ->join('users u','cr.user_id=u.id')
        ->where('user_id', $client_user_id)
        ->get()->row();
    }

    public function get_union_profile_details($unionId, $tableName) {
      return  $this->db->select($tableName.'.*')
      ->from('client_registration cr')
      ->where('cr.id',$unionId)
      ->join($tableName,  $tableName.'.user_id=cr.user_id')
      ->get()->result();
    }

    public function get_union_director_details($unionId){
      return  $this->db->select('dr.*')
      ->from('client_registration cr')
      ->where('cr.id',$unionId)
      ->join('director_registration dr', 'dr.client_user_id=cr.user_id')
      ->get()->result();
    }

    public function get_union_member_details($unionId){
      return  $this->db->select('mr.*')
      ->from('client_registration cr')
      ->where('cr.id',$unionId)
      ->join('member_registration mr', 'mr.client_user_id=cr.user_id')
      ->get()->result();
    }

    public function get_union_notice_details($unionId, $selectValue){

      $this->db->select('cn.id as noticeId');
      $this->db->from('client_registration cr');
      $this->db->where('cr.id',$unionId);
      $this->db->join('client_notice cn', 'cn.user_id=cr.user_id');
      if ($selectValue !='All') {
        $this->db->where('cn.category',$selectValue);
      }
      $result =  $this->db->get()->result();
      $noticeIds = [];
      foreach ($result as $key => $res) {
        array_push($noticeIds, $res->noticeId);
      }    
      return $noticeIds;
    }

    public function get_union_notice_posted_list_data($unionNoticeIds){
      $this->db->select("cn.*");
      $this->db->from('client_notice cn');
      $this->db->where_in('cn.id',$unionNoticeIds);
      $this->db->order_by('cn.id','desc');
      return $this->db->get()->result();
    }

    public function get_top_header_ads_bycurrentlocation($main_app, $sub_app){
      $userId = $this->ion_auth->user()->row();

      $headerAdsLocations = [];
      if (!empty($userId)) {
        $usersLocation = $this->db->select('(case when set_country is not null then set_country else country end) as country, (case when set_state is not null then set_state else state end) as state, (case when set_district is not null then set_district else district end) as district')
        ->from('user_registration_form')
        ->where('user_id',$userId->id)
        ->get()->row();
       
        if (empty($usersLocation)) {
          return $headerAdsLocations;
        }
        $branchOfficeAds = $this->db->select('fa.image_path, fa.image_url')
        ->from('franchise_advertise fa')
        ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
        ->join('users u','fh.user_id=u.id')
        ->join('users_groups ug','u.id=ug.user_id')
        ->join('groups gp','ug.group_id=gp.id')
        ->where('gp.name','branch')
        ->where('fh.country',$usersLocation->country)
        ->where('fh.state',$usersLocation->state)
        ->where('fh.district',$usersLocation->district)
        ->where('fa.my_app_name',$main_app)
        ->where('fa.my_app_sub_name',$sub_app)
        ->get()->row_array();
        if (!empty($branchOfficeAds)) {
         $result2 = $this->object_to_array($branchOfficeAds);
         $headerAdsLocations[] = $result2;
        }

        $branchOfficeAds1 = $this->db->select('fa.image_path, fa.image_url')
        ->from('franchise_advertise1 fa')
        ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
        ->join('users u','fh.user_id=u.id')
        ->join('users_groups ug','u.id=ug.user_id')
        ->join('groups gp','ug.group_id=gp.id')
        ->where('gp.name','branch')
        ->where('fh.country',$usersLocation->country)
        ->where('fh.state',$usersLocation->state)
        ->where('fh.district',$usersLocation->district)
        ->where('fa.my_app_name',$main_app)
        ->where('fa.my_app_sub_name',$sub_app)
        ->get()->row_array();
         if (!empty($branchOfficeAds1)) {
          $result3 = $this->object_to_array($branchOfficeAds1);
          $headerAdsLocations[] = $result3;
        }

         $headOfficeAds = $this->db->select('fa.image_path, fa.image_url')
        ->from('franchise_advertise fa')
        ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
        ->join('users u','fh.user_id=u.id')
        ->join('users_groups ug','u.id=ug.user_id')
        ->join('groups gp','ug.group_id=gp.id')
        ->where('gp.name','head_office')
        ->where('fh.country',$usersLocation->country)
        ->where('fa.my_app_name',$main_app)
        ->where('fa.my_app_sub_name',$sub_app)
        ->get()->row_array();
        if (!empty($headOfficeAds)) {
          $result = $this->object_to_array($headOfficeAds); 
          $headerAdsLocations[] = $result;
        }
        
        $regionalOfficeAds = $this->db->select('fa.image_path, fa.image_url')
        ->from('franchise_advertise fa')
        ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
        ->join('users u','fh.user_id=u.id')
        ->join('users_groups ug','u.id=ug.user_id')
        ->join('groups gp','ug.group_id=gp.id')
        ->where('gp.name','regional')
        ->where('fh.country',$usersLocation->country)
        ->where('fh.state',$usersLocation->state)
        ->where('fa.my_app_name',$main_app)
        ->where('fa.my_app_sub_name',$sub_app)
        ->get()->row_array();
        if (!empty($regionalOfficeAds)) {
          $result1 = $this->object_to_array($regionalOfficeAds); 
          $headerAdsLocations[] = $result1;
        }

        
      }
      return $headerAdsLocations;
    }

    private function object_to_array($data){
      if (is_array($data) || is_object($data))
      {
          $result = [];
          foreach ($data as $key => $value)
          {
              $result[$key] = (is_array($value) || is_object($value)) ? object_to_array($value) : $value;
          }
          return $result;
      }
      return $data;
  }

  public function set_current_location_by_user_id($district, $current_country, $current_state){
    $userId = $this->ion_auth->user()->row()->id;
    $data = array(
        'set_country'=> $current_country,
        'set_state'=> $current_state,
        'set_district'=> $district,
      );
    $this->db->where('user_id',$userId);
    return $this->db->update('user_registration_form',$data);
  }

  public function post_data_by_members($client_union_id){
    $userId = $this->ion_auth->user()->row()->id;
    $data = array(
      'user_id'=> $userId,
      'union_id'=> $client_union_id,
      'content'=> $this->input->post('post_content'),
      'status'=> 1, // posted,
      'created_on'=> date('Y-m-d h:i')
    );
    $this->db->insert('member_union_post',$data);
    return $this->db->insert_id();
  }

  public function save_member_posted__images($save_images){
    return $this->db->insert_batch('member_union_post_attached',$save_images);
  }

  // public function members_registration_data(){
  //   $mobilenumber = $this->ion_auth->user()->row()->username;
  //   return $this->db->select('mr.*')
  //   ->from('member_registration mr')
  //   ->where('mr.mobile_number',$mobilenumber)
  //   ->get()->row();
  // }

  public function members_registration_data($client_user_id){
    $mobilenumber = $this->ion_auth->user()->row()->username;
    $members = $this->db->select('*')
    ->from('member_registration mr')
    ->where('mr.mobile_number',$mobilenumber)
    ->where('mr.client_user_id',$client_user_id)
    ->get()->row();
    if (!empty($members)) {
      return $members;
    }

    $directory = $this->db->select('dr.*, dr.name as full_name, dr.director_id_number as member_id_number')
    ->from('director_registration dr')
    ->where('dr.mobile_number',$mobilenumber)
    ->where('dr.client_user_id',$client_user_id)
    ->get()->row();
    if (!empty($directory)) {
      return $directory;
    }
    $staff = $this->db->select('usr.*, usr.name as full_name, "staff_member" as member_id_number')
    ->from('union_staff_registration usr')
    ->where('usr.mobile_number',$mobilenumber)
    ->where('usr.client_user_id',$client_user_id)
    ->get()->row();

    if (!empty($staff)) {
      return $staff;
    }
    $leaders = $this->db->select('*')
    ->from('header_leader_registration hlr')
    ->where('hlr.mobile_number',$mobilenumber)
    ->where('hlr.client_user_id',$client_user_id)
    ->get()->row();

    if (!empty($leaders)) {
      return $leaders;
    }
    return 0;
  }

  public function get_mypost_data_unionwise_id($union_id){
    $user_id = $this->ion_auth->user()->row()->id;
    $this->db->select('id as union_post_id');
    $this->db->from('member_union_post');
    $this->db->where('union_id',$union_id);
    $this->db->where('user_id',$user_id);
    $this->db->order_by('id','desc');
    $result = $this->db->get()->result();

    $unionPostIds = [];
    foreach ($result as $key => $res) {
      array_push($unionPostIds, $res->union_post_id);
    }    
    return $unionPostIds;
  }

  public function get_mypost_data_unionwise_data($unions_post_ids){
    $mobilenumber = $this->ion_auth->user()->row()->id;

    $master = $this->db->select('mup.*, mupa.file_path, mr.full_name as posted_name, mr.member_photo')
    ->from('member_union_post mup')
    ->where_in('mup.id',$unions_post_ids)
    ->join('member_union_post_attached mupa','mup.id=mupa.member_union_post_id')
    ->join('users u','u.id=mup.user_id')
    ->join('member_registration mr','u.username=mr.user_name')
    ->order_by('mup.id','desc')
    ->get()->result();

    $comments = $this->db->select('*')
    ->from('member_union_post_like_comments')
    ->where_in('member_union_post_id',$unions_post_ids)
    ->get()->result();
    $commentsArry = [];
    foreach ($comments as $key => $val) {
      if(!array_key_exists($val->member_union_post_id, $commentsArry)) {
        $commentsArry[$val->member_union_post_id]['like_user_id'] = 0;
        $commentsArry[$val->member_union_post_id]['shared_user_id'] = 0;
      }
      if (!empty($val->member_like_user_id)) {
        $commentsArry[$val->member_union_post_id]['like_user_id']++;
      }
      if (!empty($val->member_shared_user_id)) {
        $commentsArry[$val->member_union_post_id]['shared_user_id']++;
      }

      if (!empty($val->member_comments)) {
        $usersDetails = $this->_get_users_detailsbyuserId($val->member_comment_user_id, $val->member_comments, $val->date_time);
      }
      $commentsArry[$val->member_union_post_id]['comments'][] = $usersDetails;
    }
  
    $myposts = [];
    foreach ($master as $key => $val) {
      $memberPhoto = base_url().'assets/front/logo.png';
      if (!empty($val->member_photo)) {
        $memberPhoto = $this->filemanager->getFilePath($val->member_photo);
      }
      $myposts[$val->id]['posted_name'] = $val->posted_name;
      $myposts[$val->id]['posted_photo'] = $memberPhoto;
      $myposts[$val->id]['content'] = $val->content;
      $myposts[$val->id]['id'] = $val->id;
      $myposts[$val->id]['date_time'] =  $this->_time_elapsed_string($val->created_on);
      $myposts[$val->id]['images'][] =  $this->filemanager->getFilePath($val->file_path);
      if (array_key_exists($val->id, $commentsArry)) {
        $myposts[$val->id]['comments'] =  $commentsArry[$val->id];
      }
    }
    return $myposts;
  }

  private function _get_users_detailsbyuserId($member_user_id, $member_comments,$dateTime){
    $users = $this->db->select('mr.full_name as posted_name, mr.member_photo')
    ->from('users u')
    ->where('u.id',$member_user_id)
    ->join('member_registration mr','u.username=mr.user_name')
    ->get()->row();

    $users->memberCommentsPhoto = base_url().'assets/front/logo.png';
    $users->member_comments = $member_comments;
    if (!empty($users->member_photo)) {
      $users->memberCommentsPhoto = $this->filemanager->getFilePath($users->member_photo);
      $users->comment_date_time = $this->_time_elapsed_string($dateTime);
    }
    return $users;
  }

  private function _time_elapsed_string($datetime, $full = false){
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }


  public function get_my_groups_apps_list($apps_name){
    return  $this->db->select('gc.id as apps_id, gc.name, cd.icon, cd.id as icon_id')
    ->from('group_create gc')
    ->or_where('gc.apps_name',$apps_name)
    ->join('create_details cd','gc.id=cd.create_id')
    ->order_by('gc.id')
    ->get()->result();
  }

  public function get_all_mygroups_apps_list(){
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
  

  public function get_header_ads_list($main_app, $sub_app){
    if (empty($main_app)) {
      return $this->_corporate_my_company_ads('Mygroup');
    }
    if ($sub_app =='my-company') {
      return $this->_corporate_my_company_ads($main_app);
    }

    if ($main_app =='Mygroup') {
      return $this->_corporate_my_company_ads($main_app);
    }
    
    $userId = $this->ion_auth->user()->row();
    $headerAdsLocations = [];
    if (!empty($userId)) {
      $usersLocation = $this->db->select('(case when set_country is not null then set_country else country end) as country, (case when set_state is not null then set_state else state end) as state, (case when set_district is not null then set_district else district end) as district')
      ->from('user_registration_form')
      ->where('user_id',$userId->id)
      ->get()->row();
     
      if (empty($usersLocation)) {
        return $headerAdsLocations;
      }
      $branchOfficeAds = $this->db->select('fa.image_path, fa.image_url')
      ->from('franchise_advertise fa')
      ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
      ->join('users u','fh.user_id=u.id')
      ->join('users_groups ug','u.id=ug.user_id')
      ->join('groups gp','ug.group_id=gp.id')
      ->where('gp.name','branch')
      ->where('fh.country',$usersLocation->country)
      ->where('fh.state',$usersLocation->state)
      ->where('fh.district',$usersLocation->district)
      ->where('fa.my_app_name',$main_app)
      ->where('fa.my_app_sub_name',$sub_app)
      ->get()->row_array();
      if (!empty($branchOfficeAds)) {
       $result2 = $this->object_to_array($branchOfficeAds);
       $headerAdsLocations[] = $result2;
      }else{
        $corporateAds = $this->_corporate_ads_get($main_app, $sub_app);
        $result2 = $this->object_to_array($corporateAds);
        $headerAdsLocations[] = $result2;
      }

      $regionalOfficeAds = $this->db->select('fa.image_path, fa.image_url')
      ->from('franchise_advertise fa')
      ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
      ->join('users u','fh.user_id=u.id')
      ->join('users_groups ug','u.id=ug.user_id')
      ->join('groups gp','ug.group_id=gp.id')
      ->where('gp.name','regional')
      ->where('fh.country',$usersLocation->country)
      ->where('fh.state',$usersLocation->state)
      ->where('fa.my_app_name',$main_app)
      ->where('fa.my_app_sub_name',$sub_app)
      ->get()->row_array();
      if (!empty($regionalOfficeAds)) {
        $result1 = $this->object_to_array($regionalOfficeAds); 
        $headerAdsLocations[] = $result1;
      }else{
        $corporateAds = $this->_corporate_ads_get($main_app, $sub_app);
        $result1 = $this->object_to_array($corporateAds); 
        $headerAdsLocations[] = $result1;
      }

      $branchOfficeAds1 = $this->db->select('fa.image_path, fa.image_url')
      ->from('franchise_advertise1 fa')
      ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
      ->join('users u','fh.user_id=u.id')
      ->join('users_groups ug','u.id=ug.user_id')
      ->join('groups gp','ug.group_id=gp.id')
      ->where('gp.name','branch')
      ->where('fh.country',$usersLocation->country)
      ->where('fh.state',$usersLocation->state)
      ->where('fh.district',$usersLocation->district)
      ->where('fa.my_app_name',$main_app)
      ->where('fa.my_app_sub_name',$sub_app)
      ->get()->row_array();
       if (!empty($branchOfficeAds1)) {
        $result3 = $this->object_to_array($branchOfficeAds1);
        $headerAdsLocations[] = $result3;
      }else{
        $corporateAds = $this->_corporate_ads_get($main_app, $sub_app);
        $result3 = $this->object_to_array($corporateAds);
        $headerAdsLocations[] = $result3;
      }

      $headOfficeAds = $this->db->select('fa.image_path, fa.image_url')
      ->from('franchise_advertise fa')
      ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
      ->join('users u','fh.user_id=u.id')
      ->join('users_groups ug','u.id=ug.user_id')
      ->join('groups gp','ug.group_id=gp.id')
      ->where('gp.name','head_office')
      ->where('fh.country',$usersLocation->country)
      ->where('fa.my_app_name',$main_app)
      ->where('fa.my_app_sub_name',$sub_app)
      ->get()->row_array();
      if (!empty($headOfficeAds)) {
        $result = $this->object_to_array($headOfficeAds); 
        $headerAdsLocations[] = $result;
      }else{
        $corporateAds = $this->_corporate_ads_get($main_app, $sub_app);
        $result = $this->object_to_array($corporateAds); 
        $headerAdsLocations[] = $result;
      }
        
    }
    return $headerAdsLocations;
  }

  public function _corporate_ads_get($main_app, $sub_app){
    return $this->db->select('fa.image_path, fa.image_url')
      ->from('franchise_advertise fa')
      ->join('franchise_holder fh','fa.franchise_holder_id=fh.id')
      ->join('users u','fh.user_id=u.id')
      ->join('users_groups ug','u.id=ug.user_id')
      ->join('groups gp','ug.group_id=gp.id')
      ->where('gp.name','corporate')
      ->where('fa.my_app_name',$main_app)
      ->where('fa.my_app_sub_name',$sub_app)
      ->get()->row_array();
  }

  public function _corporate_my_company_ads($main_app){
    $result =  $this->db->select('gc.id,gc.name, ad.ads1, ad.ads2, ad.ads3,  ad.ads1_url, ad.ads2_url, ad.ads3_url')
    ->from('group_create gc')
    ->where('gc.name',$main_app)
    ->join('aderttise ad','gc.id=ad.create_id','left')
    ->get()->row();

    $adsArry[0] = array('img'=>base_url().$result->ads1,'image_url'=>$result->ads1_url);
    $adsArry[1] = array('img'=>base_url().$result->ads2,'image_url'=>$result->ads2_url);
    $adsArry[2] = array('img'=>base_url().$result->ads3,'image_url'=>$result->ads3_url);
   
    return $adsArry;
  }

  public function count_total_rating($where) {
    $this->db->select('AVG(rating) as average');
    $this->db->where('blog_id', $where);
    $this->db->from('rating');
    return $query = $this->db->get()->result_array();
  }
  
  public function get_rating_data($blogid){
      $this->db->select('*');
      $this->db->from('users u');
      $this->db->join('rating r', 'r.user_id = u.user_id');
      $this->db->where('blog_id', $blogid);
      return $query = $this->db->get()->result();
  }

} ?>