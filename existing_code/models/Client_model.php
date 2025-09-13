<?php

class Client_model extends CI_Model{
  
  	public function __construct(){
    	parent::__construct();
  	}

    public function get_client_logo_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_logo')->row();   
    }

    public function get_client_name_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_registration')->row();  
    }

    public function get_unions_detailsbyid(){
        $user_id = $this->ion_auth->user()->row()->id;
        $result = $this->db->select('cr.*, mc.category as cat_name')
        ->from('client_registration cr')
        ->join('myunions_category mc','cr.category=mc.id')
        ->where('cr.user_id',$user_id)
        ->where('cr.group_id','13')
        ->get()->row();
        if ($result->type == 1) {
            $result->country_name = '';
            $result->state_name = '';
            $result->district_name = '';
            $result->type = 1;
        }
        if ($result->type == 2) {
           $countryName = $this->db->select('ct.country as country_name')
           ->from('country_tbl ct')
           ->where('ct.id',$result->country)
           ->get()->row();
           $result->country_name = $countryName->country_name;
           $result->type = 2;
        }

        if ($result->type == 3) {
           $state =  $this->db->select('ct.country as country_name, st.state as state_name')
           ->from('country_tbl ct')
           ->join('state_tbl st','ct.id=st.country_id')
           ->where('ct.id',$result->country)
           ->where('st.id',$result->state)
           ->get()->row();
           $result->country_name = $state->country_name;
           $result->state_name =  $state->state_name;
           $result->type = 3;
        }
        if ($result->type == 4) {
           $district = $this->db->select('ct.country as country_name, st.state as state_name, dt.district as district_name')
           ->from('country_tbl ct')
           ->join('state_tbl st','ct.id=st.country_id')
           ->join('district_tbl dt','st.id=dt.state_id')
           ->where('ct.id',$result->country)
           ->where('st.id',$result->state)
           ->where('dt.id',$result->district)
           ->get()->row();
           $result->country_name = $district->country_name;
           $result->state_name =  $district->state_name;
           $result->district_name =  $district->district_name;
           $result->type = 4;
        }
        return $result;
    }
    public function get_client_admin_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('id',$user_id)->get('users')->row();  
    }

    public function upload_admin_details_by_id($id){
        $data = array(
          'first_name' => $this->input->post('first_name'),
          'phone' => $this->input->post('phone'),
          'address' => $this->input->post('address'),
          'email' => $this->input->post('email'),
        );
        $this->db->where('id',$id);
        return $this->db->update('users',$data);
    }

    public function insert_client_logo($picurl){
        $Imagepath = '';
        if($picurl['file_name'] != '') {
          $Imagepath = $picurl['file_name'];
        }

        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'logo' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('client_logo',$data);
    }

    public function insert_client_logo_name($picurl){
        $Imagepath = '';
        if($picurl['file_name'] != '') {
          $Imagepath = $picurl['file_name'];
        }

        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'name_of_the_organization'=> $this->input->post('client_name'),
          'regional_lang_name'=> $this->input->post('regional_lang_name')
        );
        if (!empty($Imagepath)) {
           $data = array_merge($data,['client_logo' => $Imagepath]); 
        }
        $this->db->where('user_id',$user_id);
        return $this->db->update('client_registration',$data);
    }

    public function insert_client_name(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'name' => $this->input->post('client_name'),
          'color' => $this->input->post('color'),
          'user_id' => $user_id,
        );
        return $this->db->insert('client_name',$data);
    }

    public function get_client_document_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_document')->result();   
    }

    public function get_client_awards_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_awards')->result();   
    }

    public function get_client_news_letter_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_news_letter')->result();   
    }

    public function get_client_objectivies_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_objectivies')->result();    
    }

    public function get_client_about_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('client_about')->result();    
    }

    public function delete_doc_by_client_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_document');
    }

    public function delete_awards_by_client_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_awards');
    }

    public function delete_objectivies_by_client_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_objectivies');
    }

    public function delete_about_by_client_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_about');
    }

    public function delete_news_letter_by_client_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_news_letter');
    }


    public function download_client_document($id){
        return $this->db->select('document_name,document_path')
        ->where('id', $id)
        ->get('client_document')
        ->row();
    }

    public function download_client_awards($id){
        return $this->db->select('awards_name,awards_path')
        ->where('id', $id)
        ->get('client_awards')
        ->row();
    }

    public function download_client_news_letter($id){
        return $this->db->select('news_letter_name,news_letter_path')
        ->where('id', $id)
        ->get('client_news_letter')
        ->row();
    }

    public function download_client_objectivies($id){
        return $this->db->select('objectivies_name,objectivies_path')
        ->where('id', $id)
        ->get('client_objectivies')
        ->row();
    }
    public function download_client_about($id){
        return $this->db->select('about_name,about_path')
        ->where('id', $id)
        ->get('client_about')
        ->row();
    }

    public function insert_client_document($document){
        $Imagepath = '';
        if($document['file_name'] != '') {
          $Imagepath = $document['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'document_name' => $this->input->post('document_name'),
          'document_path' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('client_document',$data);
    }

    public function insert_client_awards($awards){
        $Imagepath = '';
        if($awards['file_name'] != '') {
          $Imagepath = $awards['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'awards_name' => $this->input->post('awards_name'),
          'awards_path' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('client_awards',$data);
    }

    public function insert_client_objectivies($document){
        $Imagepath = '';
        if($document['file_name'] != '') {
          $Imagepath = $document['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'objectivies_name' => $this->input->post('objectivies_name'),
          'objectivies_description' => $this->input->post('description'),
          'objectivies_path' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('client_objectivies',$data);
    }

    public function insert_client_about($document){
        $Imagepath = '';
        if($document['file_name'] != '') {
          $Imagepath = $document['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'about_name' => $this->input->post('about_name'),
          'about_description' => $this->input->post('description'),
          'about_path' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('client_about',$data);
    }

    public function insert_client_news_letter($awards){
        $Imagepath = '';
        if($awards['file_name'] != '') {
          $Imagepath = $awards['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;

        $data = array(
          'news_letter_name' => $this->input->post('news_letter_name'),
          'news_letter_path' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('client_news_letter',$data);
    }

    public function get_media_category_by_type($mediaType){
        return $this->db->select('id, category')->where('media_type',$mediaType)->get('media_category')->result();
    }

    public function insert_media_channel_form(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'media_type' => $this->input->post('media_type'),
          'location_type' => $this->input->post('type'),
          'country' => $this->input->post('country'),
          'state' => $this->input->post('state'),
          'district' => $this->input->post('district'),
          'media_category' => $this->input->post('media_category'),
          'language' =>  $this->input->post('language'),
          'media_name' => $this->input->post('media_name'),
          'media_name_regional' => $this->input->post('media_name_regional'),
          'img_path' => $this->input->post('img_path'),
          'user_id' => $user_id,
        );
        return $this->db->insert('media_channel',$data);  
    }

    public function get_client_channel_list(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('media_channel')
        ->where('user_id',$user_id)
        ->get()->result();
    }

    public function get_client_channel_created_list_for_lock(){
        $user_id = $this->ion_auth->user()->row()->id;
        $result =  $this->db->select('id, media_type, media_name, media_name_regional')
        ->from('media_channel')
        ->where('user_id',$user_id)
        ->get()->result();
        $channelArry =[];
        foreach ($result as $key => $res) {
            $channelArry[$res->media_type][] = $res;
        }
        return $channelArry;
    }

    public function each_channel_page($mediaType){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('media_channel')
        ->where('user_id',$user_id)
        ->where('media_type',$mediaType)
        ->get()->row();
    }

    public function insert_media_client_document($file_path, $media_channel_id, $media_type, $date){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'user_id' => $user_id, 
            'media_channel_id' => $media_channel_id, 
            'file_path' => $file_path,
            'media_type' => $media_type,
            'date' => date('Y-m-d',strtotime($date)),
        );
        return $this->db->insert('media_channel_document',$data);
    }

    public function view_client_document_file_by_id($media_channel_id){
        return $this->db->where('media_channel_id',$media_channel_id)->get('media_channel_document')->row();
    }

    public function insert_media_client_live_url_link_by_id($input){
        $data = array(
            'media_channel_id' => $input['media_channel_id'], 
            'media_link' => $input['media_link']
        );
        return $this->db->insert('media_link',$data); 
    }

    public function get_media_link_by_id($media_channel_id){
       return $this->db->where('media_channel_id',$media_channel_id)->get('media_link')->row(); 
    }

    public function update_media_client_live_url_link_by_id($input){
        $data = array(
            'media_link' => $input['media_link']
        );
        $this->db->where('media_channel_id',$input['media_channel_id']);
        return $this->db->update('media_link',$data); 
    }


    public function insert_needy_client_service_form_details($picurl, $input){
        $Imagepath = '';
        if($picurl['file_name'] != '') {
          $Imagepath = $picurl['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'group_id' => $input['group_id'],
            'type' => $input['type'],
            'user_id' => $user_id,
            'status' => 0,
            'country' => $input['country'],
            'state' => $input['state'],
            'district' => $input['district'],
            'needy_category' => $input['needy_category'],
            'consultancy_charges_from' => $input['consultancy_charges_from'],
            'consultancy_charges_to' => $input['consultancy_charges_to'],
            'address' => $input['address'],
            'area' => $input['area'],
            'pincode' => $input['pincode'],
            'descriptions' => $input['descriptions'],
            'services_name' => $input['services_name'],
            'name_regional_language' => $input['name_regional_language'],
            'contact_number' => $input['contact_number'],
            'photo' => $Imagepath,
        );
        $this->db->insert('needy_client_services_details',$data);
        $insert_id = $this->db->insert_id();
        foreach ($input['from_time'] as $week => $fromTime) {
            $timeData[] = array(
                'week_name' => $week,
                'from_time' => $fromTime,
                'to_time' => $input['to_time'][$week],
                'needy_client_services_id' => $insert_id,
            );
        }
        $this->db->insert_batch('needy_client_services_time_details',$timeData);
        return $insert_id;
    }

    public function update_needy_client_services_details_by_id($picurl, $input, $id){

        if(empty($picurl)) $picurl['file_name'] = '';
        
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'consultancy_charges_from' => $input['consultancy_charges_from'],
            'consultancy_charges_to' => $input['consultancy_charges_to'],
            'address' => $input['address'],
            'area' => $input['area'],
            'pincode' => $input['pincode'],
            'descriptions' => $input['descriptions'],
            'services_name' => $input['services_name'],
            'name_regional_language' => $input['name_regional_language'],
            'contact_number' => $input['contact_number'],
        );
        if($picurl['file_name'] != '') {
            $data = array_merge($data,['photo' => $picurl['file_name']]);
        }
        $this->db->where('id',$id);
        $this->db->update('needy_client_services_details',$data);
        foreach ($input['from_time'] as $week => $fromTime) {
            $timeData[] = array(
                'week_name' => $week,
                'from_time' => $fromTime,
                'to_time' => $input['to_time'][$week],
                'needy_client_services_id' => $id,
            );
        }
        $this->db->where('needy_client_services_id',$id);
        $this->db->delete('needy_client_services_time_details');

        return $this->db->insert_batch('needy_client_services_time_details',$timeData);

    }
    public function created_services_count($group_id){
        $result =  $this->db->select('count(id) as count, type')
        ->from('needy_client_services_details')
        ->group_by('type')
        ->get()->result();

        $countService = [];
        foreach ($result as $key => $value) {
            $countService[$value->type] = $value->count;
        }
        return $countService;
    }

    public function view_created_services_list($group_id){
        $user_id = $this->ion_auth->user()->row()->id;
        $result =  $this->db->select('ncsd.id, ncsd.status, nc.category, type')
        ->from('needy_client_services_details ncsd')
        ->join('needy_category nc','ncsd.needy_category=nc.id')
        ->where('ncsd.group_id',$group_id)
        ->where('ncsd.user_id',$user_id)
        ->get()->result();
        $listServices = [];
        foreach ($result as $key => $val) {
            $listServices[$val->type][] = $val;
        }
        return $listServices;

    }

    public function edit_needy_client_services_by_id($id){
        $result =  $this->db->select('ncsd.*, nc.category, ct.country, st.state, dt.district')
        ->from('needy_client_services_details ncsd')
        ->join('needy_category nc','ncsd.needy_category=nc.id')
        ->join('country_tbl ct','ncsd.country=ct.id')
        ->join('state_tbl st','ncsd.state=st.id')
        ->join('district_tbl dt','ncsd.district=dt.id')
        ->where('ncsd.id',$id)
        ->get()->row(); 

        $time =  $this->db->select('*')
        ->from('needy_client_services_time_details')
        ->where('needy_client_services_id',$id)
        ->get()->result();
        $weektime = [];
        foreach ($time as $key => $val) {
           $weektime[$val->week_name] = $val;
        }
        return array('service'=>$result,'time'=>$weektime);
    }

    public function needy_services_status_udpate_by_id($stngId,$value){
        $data = array(
            'status' => $value,
        );
        $this->db->where('id',$stngId);
        return $this->db->update('needy_client_services_details', $data);
    }

    public function insert_my_shop_client_details_by_id(){
        $user_id = $this->ion_auth->user()->row()->id;
        $input = $this->input->post();
        $data = array(
            'name' => $this->input->post('shop_name'),
            'name_in_regional_lang' => (!isset($input['shop_name_regional']) || $input['shop_name_regional'] == '')? null : $input['shop_name_regional'],
            'address' => (!isset($input['address']) || $input['address'] == '')? null : $input['address'],
            'landmark' => (!isset($input['landmark']) || $input['landmark'] == '')? null : $input['landmark'],
            'pincode' => (!isset($input['pincode']) || $input['pincode'] == '')? null : $input['pincode'],
            'shop_logo' =>$input['img_path'],
            'shop_type' =>$input['shop_type'],
            'user_id' =>  $user_id,
            'category_id' => $input['category']
        );
        return $this->db->insert('client_my_shop_details',$data);
    }

    public function get_shop_category_for_product($shop_type){
       return $this->db->select('*')
       ->from('myshop_category')
       ->where('shop_type',$shop_type)
       ->get()->result();
    }

    public function get_sub_categorybycat_id($category, $shop_type){
        if ($category !=0) {
            if ($shop_type == 'local' || $shop_type == 'wholesale' || $shop_type == 'brands') {
                return $this->db->select('id, sub_category')
                ->from('myshop_client_sub_category')
                ->where('category_id',$category)
                ->get()->result();
            }else{
                return $this->db->select('id, sub_category')
                ->from('group_sub_category')
                ->where('category_id',$category)
                ->get()->result();
            }
        }
        
       
    }

    public function get_sub_sub_categorybycat_id($sub_cat, $category, $shop_type){
        return $this->db->select('id, sub_sub_category')
        ->from('group_sub_sub_category')
        ->where('sub_category_id',$sub_cat)
        ->where('category_id',$category)
        ->get()->result();
    }

    public function insert_myshop_product_form_data_detaiils(){
        $user_id = $this->ion_auth->user()->row()->id;
        $input = $this->input->post();
        $this->db->trans_start();
        $data = array(
            'category' => $input['category'],
            'sub_category' => (!isset($input['sub_category']) || $input['sub_category'] == '')? null : $input['sub_category'],
            'sub_sub_category' => (!isset($input['sub_sub_category']) || $input['sub_sub_category'] == '')? null : $input['sub_sub_category'],
            'product_name' => (!isset($input['product_name']) || $input['product_name'] == '')? null : $input['product_name'],
            'product_tag_line' => (!isset($input['product_tag_line']) || $input['product_tag_line'] == '')? null : $input['product_tag_line'],
            'product_mrp' => (!isset($input['product_mrp']) || $input['product_mrp'] == '')? null : $input['product_mrp'],
            'product_my_price' =>$input['product_my_price'],
            'product_details' =>$input['product_details'],
            'specifications' =>$input['specifications'],
            'features' =>$input['features'],
            'shop_type' =>$input['shop_type'],
            'user_id' =>  $user_id,
        );

        $this->db->insert('my_shop_prodcut_details',$data);
        $inser_id = $this->db->insert_id();
         $imgdata = [];
        foreach ($input['img_path'] as $key => $val) {
            if (!empty($val)) {
                $imgdata[] = array(
                    'my_shop_product_id' => $inser_id,
                    'image' => $val,
                );
            }
            
        }
        $this->db->insert_batch('my_shop_product_image_details',$imgdata);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_myshop_product_details($shop_type){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('spd.*, mc.category as category_name')
        ->from('my_shop_prodcut_details spd')
        ->where('spd.shop_type',$shop_type)
        ->where('spd.user_id',$user_id)
        ->join('myshop_category mc','spd.category=mc.id')
        ->get()->result();
    }

    public function get_local_shop_details($shop_type){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('client_my_shop_details')
        ->where('user_id',$user_id)
        ->where('shop_type',$shop_type)
        ->get()->row();
    }

    public function my_shop_product_status_udpate_by_id($stngId,$value){
        $data = array(
            'status' => $value,
        );
        $this->db->where('id',$stngId);
        return $this->db->update('my_shop_prodcut_details', $data);
    }

    public function get_my_shop_product_details($shop_prod_id){
        $master =  $this->db->select('spd.*, mc.category as category_name, gsc.sub_category as subcatname, fssc.sub_sub_category as subsubcatname')
        ->from('my_shop_prodcut_details spd')
        ->where('spd.id',$shop_prod_id)
        ->join('myshop_category mc',"spd.category=mc.id")
        ->join('group_sub_category gsc','spd.sub_category=gsc.id','left')
        ->join('group_sub_sub_category fssc','spd.sub_sub_category=fssc.id','left')
        ->get()->row();

        $images =  $this->db->select('spid.*')
        ->from('my_shop_product_image_details spid')
        ->where('spid.my_shop_product_id',$shop_prod_id)
        ->get()->result();

        $master->image = $images;
        return $master;

    }


    public function get_client_category_details($client_category_id){
      return $this->db->select('*')
      ->from('myshop_category')
      ->where('id',$client_category_id)
      ->get()->row();
    }

    public function insert_my_shop_sub_category_by_client($group_name){
      $input = $this->input->post();
      $user_id = $this->ion_auth->user()->row()->id;
      $data = array(
        'group_name' => $group_name, 
        'category_id' => $input['client_category_id'],
        'sub_category' => $input['sub_category'], 
        'client_my_shop_details_id' => $input['client_shop_id'],
        'shop_type' => $input['shop_type'],
        'user_id' => $user_id
      );
      return $this->db->insert('myshop_client_sub_category', $data);
    }


    public function get_client_sub_category_details($client_shop_id){
        return $this->db->select('*')
        ->from('myshop_client_sub_category')
        ->where('client_my_shop_details_id',$client_shop_id)
        ->get()->result();
    }

    public function check_shop_client_count(){
        $user_id = $this->ion_auth->user()->row()->id;
        $result = $this->db->select('*')
        ->from('client_my_shop_details')
        ->where('user_id',$user_id)
        ->get()->result();
        $clientShopCount = []; 
        foreach ($result as $key => $val) {
            $clientShopCount[$val->shop_type][] = $val;
        }
        return $clientShopCount;
    }

    public function upload_client_union_news_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'news_letter_name' => $this->input->post('news_letter_name'),
            'description' => $this->input->post('description'),
            'category' => $this->input->post('category'),
            'union_main_img' => $this->input->post('union_main_img'),
            'union_main_img1' => $this->input->post('union_main_img1'),
            'union_main_img2' => $this->input->post('union_main_img2'),
            'union_main_img3' => $this->input->post('union_main_img3'),
            'video_path'=>$this->input->post('video_path'),
            'user_id'=>$user_id
        );
        return $this->db->insert('client_news',$data);
    }

    public function get_client_union_news(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('client_news')
        ->where('user_id',$user_id)
        ->get()->result();
    }

    public function delete_union_newsby_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_news');
    }

    public function upload_client_union_notice_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'news_notice_type' => $this->input->post('news_notice_type'),
            'news_letter_name' => $this->input->post('news_letter_name'),
            'description' => $this->input->post('description'),
            'category' => $this->input->post('category'),
            'union_main_img' => $this->input->post('union_main_img'),
            'union_main_img1' => $this->input->post('union_main_img1'),
            'union_main_img2' => $this->input->post('union_main_img2'),
            'union_main_img3' => $this->input->post('union_main_img3'),
            'video_path'=>$this->input->post('video_path'),
            'user_id'=>$user_id
        );
        return $this->db->insert('client_notice',$data);
    }

    public function get_client_union_notice(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('client_notice')
        ->where('user_id',$user_id)
        ->get()->result();
    }

    public function delete_union_noticeby_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_notice');
    }

    public function upload_client_union_invitation_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'news_notice_type' => $this->input->post('news_notice_type'),
            'news_letter_name' => $this->input->post('news_letter_name'),
            'union_main_img' => $this->input->post('union_main_img'),
            'user_id'=>$user_id
        );
        return $this->db->insert('client_invitation',$data);
    }

    public function get_client_union_invitation(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('client_invitation')
        ->where('user_id',$user_id)
        ->get()->result();
    }

    public function delete_union_invitationby_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_invitation');
    }

    public function upload_client_union_press_note_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'news_notice_type' => $this->input->post('news_notice_type'),
            'news_letter_name' => $this->input->post('news_letter_name'),
            'union_main_img' => $this->input->post('union_main_img'),
            'user_id'=>$user_id
        );
        return $this->db->insert('client_press_note',$data);
    }

    public function get_client_union_press_note(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('client_press_note')
        ->where('user_id',$user_id)
        ->get()->result();
    }

    public function delete_union_pressnoteby_id($id){
        $this->db->where('id',$id);
        return $this->db->delete('client_press_note');
    }

    public function partner_enabled_for_application_for_public_updatebyId($stngId,$value){
        $data = array(
            'enabled_public_form' => $value,
        );
        $this->db->where('id',$stngId);
        return $this->db->update('client_registration', $data);
    }

    public function get_invate_user_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('unions_invate_application_data')
        ->where('client_user_id',$user_id)
        ->where('status',1)
        ->get()->result();
    }

    public function insert_unions_invate_application_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('mobile_number',$this->input->post('mobile_number'));
        $this->db->where('client_user_id',$user_id);
        $result = $this->db->get('unions_invate_application_data')->row();
        if (empty($result)) {
            $data = array(
                'mobile_number' => $this->input->post('mobile_number'),
                'client_user_id' => $user_id,
                'status' => 1
            );
            return $this->db->insert('unions_invate_application_data', $data);
        }else{
            return false;
        }
       
    }

    public function get_member_received_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
        ->from('member_registration')
        ->where('status','Received')
        ->where('client_user_id',$user_id)
        ->get()->result();
    }

    public function check_mobile_no_availability_union_member($mobilenumber){

        $members = $this->db->select('mr.client_user_id')
        ->from('member_registration mr')
        ->where('mr.mobile_number',$mobilenumber)
        ->get()->result();

        $directory = $this->db->select('dr.client_user_id')
        ->from('director_registration dr')
        ->where('dr.mobile_number',$mobilenumber)
        ->get()->result();

        $staff = $this->db->select('usr.client_user_id')
        ->from('union_staff_registration usr')
        ->where('usr.mobile_number',$mobilenumber)
        ->get()->result();

        $leaders = $this->db->select('hlr.client_user_id')
        ->from('header_leader_registration hlr')
        ->where('hlr.mobile_number',$mobilenumber)
        ->get()->result();

        $invite = $this->db->select('uiad.client_user_id')
        ->from('unions_invate_application_data uiad')
        ->where('uiad.mobile_number',$mobilenumber)
        ->where('uiad.status',1)
        ->get()->result();

        $result = array_merge($members,$directory,$staff,$leaders, $invite);
        if (!empty($result)) {
           return true;
        }else{
            return false;        
        }
    }

    public function insert_validity_member_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'member_ship_validity' => $this->input->post('member_validitiy'),
            'member_ship_paid_free' => $this->input->post('membership_paid'),
            'member_life_time' => $this->input->post('lifetime_member'),
            'member_others' => $this->input->post('member_other'),
            'fixed_fresher_amount' => $this->input->post('fixed_fresher_amount'),
            'fxied_renewal_amount' => $this->input->post('fxied_renewal_amount'),
            'validate_date' => date('Y-m-d',strtotime($this->input->post('validate_date'))),
            'manual_fresher_amount' => $this->input->post('manual_fresher_amount'),
            'manual_renewal_amount' => $this->input->post('manual_renewal_amount'),
            'auto_selection' => json_encode($this->input->post('auto_selection')),
            'auto_fresher_amount' => json_encode($this->input->post('auto_fresher_amount')),
            'auto_renewal_amount' =>  json_encode($this->input->post('auto_renewal_amount')),
            'life_member_ship_amount' =>$this->input->post('life_member_ship_amount'), 
            'client_user_id' => $user_id,
        );
        $this->db->where('client_user_id',$user_id);
        $query = $this->db->get('union_validity');
        if ($query->num_rows() > 0) {
            $this->db->where('client_user_id',$user_id);
            $this->db->delete('union_validity');
        }
        return $this->db->insert('union_validity', $data); 
    }

    public function get_member_validity_by_client_id(){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('client_user_id',$user_id);
        return $this->db->get('union_validity')->row();
    }

    public function check_memberid_unique_id($member_id_number){
        $this->db->select('mr.member_id_number');
        $this->db->from('member_registration mr');
        $this->db->where('mr.member_id_number',$member_id_number);
        $result = $this->db->get();
        if ($result->num_rows() > 0)
          return true;        
        else 
          return false;   
    }

     public function check_mobile_number_unique_all($mobilenumber){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->select('dr.mobile_number');
        $this->db->from('director_registration dr');
        $this->db->where('dr.mobile_number',$mobilenumber);
        $this->db->where('dr.client_user_id',$user_id);
        $director = $this->db->get()->row();

        $this->db->select('hlr.mobile_number');
        $this->db->from('header_leader_registration hlr');
        $this->db->where('hlr.mobile_number',$mobilenumber);
        $this->db->where('hlr.client_user_id',$user_id);
        $leaders = $this->db->get()->row();

        $this->db->select('usr.mobile_number');
        $this->db->from('union_staff_registration usr');
        $this->db->where('usr.mobile_number',$mobilenumber);
        $this->db->where('usr.client_user_id',$user_id);
        $staffReg = $this->db->get()->row();;

        $this->db->select('mr.mobile_number');
        $this->db->from('member_registration mr');
        $this->db->where('mr.mobile_number',$mobilenumber);
        $this->db->where('mr.client_user_id',$user_id);
        $memberReg = $this->db->get()->row();;

        if (!empty($director) || !empty($leaders) || !empty($staffReg) ||  !empty($memberReg)) {
            return true;        
        }else{
            return false;
        }
    }

    public function deleted_invate_memberbyid($id){
       $this->db->where('id',$id);
       return $this->db->delete('unions_invate_application_data');
    }

    public function get_client_mygod_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->select('cmd.*, CONCAT_WS(",", cmd.address_1, cmd.address_2, ct.country, st.state, dt.district) AS address')
        ->from('client_mygod_details cmd')
        ->join('country_tbl ct','cmd.country=ct.id')
        ->join('state_tbl st','cmd.state=st.id')
        ->join('district_tbl dt','cmd.district=dt.id')
        ->where('user_id',$user_id)
        ->get()->row();
    }
    public function upload_client_mygod_details(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'name' => $this->input->post('title'),
            'regional_lang' => $this->input->post('name_regional_lang'),
            'image' => $this->input->post('union_main_img'),
            'name_of_the_god' => $this->input->post('name_of_god'),
            'address_1' => $this->input->post('address_line1'),
            'address_2' => $this->input->post('address_line2'),
            'country' => $this->input->post('mygod_location_country'),
            'state'=>$this->input->post('mygod_location_state'),
            'district'=>$this->input->post('mygod_location_district'),
            'pincode'=>$this->input->post('address_pincode'),
            'user_id'=>$user_id
        );
        return $this->db->insert('client_mygod_details',$data);
    }

    public function mygod_get_social_link(){
        $user_id = $this->ion_auth->user()->row()->id;
            $this->db->where('user_id',$user_id);
        return $this->db->get('my_god_social_link')->result();
    }

    public function mygod_save_social_link($url,$uploadId, $type){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('id',$uploadId);
        $this->db->where('user_id',$user_id);
      $query = $this->db->get('my_god_social_link')->row();
      if (!empty($query)) {
               $this->db->where('id',$uploadId);
               $this->db->where('user_id',$user_id);
        return $this->db->update('my_god_social_link',array('url'=>$url,'type'=>$type));
      }else{
        return $this->db->insert('my_god_social_link',array('url'=>$url,'type'=>$type,'user_id'=>$user_id));
      }
    }

    public function mygod_get_live_link(){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('user_id',$user_id);
        return $this->db->get('my_god_live_link')->row();
    }

    public function upload_mygodlivelink_data($url){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('user_id',$user_id);
        $query = $this->db->get('my_god_live_link')->row();
        if (!empty($query)) {
                $this->db->where('id',$query->id);
                $this->db->where('user_id',$user_id);
            return $this->db->update('my_god_live_link',array('url'=>$url));
        }else{
            return $this->db->insert('my_god_live_link',array('url'=>$url,'user_id'=>$user_id));
        }
    }

    public function mygod_admin_details_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('user_id',$user_id);
       return $this->db->get('my_god_admin_details')->row(); 
    }

    public function user_admin_details_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('id',$user_id);
       return $this->db->get('users')->row();  
    }

    public function upload_mygod_admin_details(){
        $user_id = $this->ion_auth->user()->row()->id;

        $this->db->where('id',$user_id);
        $this->db->update('users',array('first_name'=>$this->input->post('first_name'),'phone'=>$this->input->post('phone')));

        $this->db->where('user_id',$user_id);
        $query = $this->db->get('my_god_admin_details')->row();
        if (!empty($query)) {
                $this->db->where('id',$query->id);
            return $this->db->update('my_god_admin_details',array('email'=>$this->input->post('email'),'address'=>$this->input->post('address')));
        }else{
            return $this->db->insert('my_god_admin_details',array('email'=>$this->input->post('email'),'address'=>$this->input->post('address'),'user_id'=>$user_id));
        }
    }

    public function insert_media_god_timings_morning(){
        $input = $this->input->post();
        $user_id = $this->ion_auth->user()->row()->id;
        foreach ($input['from_time'] as $week => $fromTime) {
            $timeData[] = array(
                'day_type'=>'Morning',
                'week_name' => $week,
                'open_time' => $fromTime, 
                'close_time' => $input['to_time'][$week],
                'user_id' => $user_id,
            );
        }

        $this->db->where('day_type','Morning');
        $this->db->where('user_id',$user_id);
        $morningQuery = $this->db->get('media_god_timings')->row();
        if (!empty($morningQuery)) {
            $this->db->where('day_type','Morning');
            $this->db->where('user_id',$user_id);
            $this->db->delete('media_god_timings');
        }

        return $this->db->insert_batch('media_god_timings',$timeData);
    }

    public function insert_media_god_timings_evening(){
        $input = $this->input->post();
        $user_id = $this->ion_auth->user()->row()->id;
        foreach ($input['from_time'] as $week => $fromTime) {
            $timeData[] = array(
                'day_type'=>'Evening',
                'week_name' => $week,
                'open_time' => date('h:i:s a',strtotime($fromTime)),
                'close_time' => $input['to_time'][$week],
                'user_id' => $user_id,
            );
        }
        $this->db->where('day_type','Evening');
        $this->db->where('user_id',$user_id);
        $EveningQuery = $this->db->get('media_god_timings')->row();
        if (!empty($EveningQuery)) {
            $this->db->where('day_type','Evening');
            $this->db->where('user_id',$user_id);
            $this->db->delete('media_god_timings');
        }
        return $this->db->insert_batch('media_god_timings',$timeData);
    }

    public function get_client_god_timings($dayType){
        $result =  $this->db->where('day_type',$dayType)->get('media_god_timings')->result();
        $godTimings = [];
        if (!empty($result)) {
            foreach ($result as $key => $value) {
               $godTimings[$value->week_name] = $value;
            }
        }
        return $godTimings;
    }

    public function get_client_god_desciption($type){
        $user_id = $this->ion_auth->user()->row()->id;
        $this->db->where('page_type',$type);
        $this->db->where('user_id',$user_id);
        return $this->db->get('media_god_description')->row();
    }

     public function insert_client_god_desciption($input){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
            'page_type'=>$input['page_type'],
            'description' => $input['description'],
            'user_id' => $user_id,
            'url' => $input['url'],
        );
        $this->db->where('page_type',$input['page_type']);
        $this->db->where('user_id',$user_id);
        $query = $this->db->get('media_god_description')->row();
        if (!empty($query)) {
            $this->db->where('page_type',$input['page_type']);
            $this->db->where('user_id',$user_id);
            return $this->db->update('media_god_description', $data);
        }
        return $this->db->insert('media_god_description',$data);
    }

    public function upload_god_today_photo($path){
        $user_id = $this->ion_auth->user()->row()->id;
        $today = date('Y-m-d');
        $data = array(
            'image'=>$path,
            'user_id' => $user_id,
        );
        $this->db->where('user_id',$user_id);
        $this->db->where('date_format(image_date,"%Y-%m-%d")',$today);
        $query = $this->db->get('my_god_today_photo')->row();
        if (!empty($query)) {
            $this->db->where('id',$query->id);
            return $this->db->update('my_god_today_photo', $data);
        }
        return $this->db->insert('my_god_today_photo',$data);
    }

    public function get_client_god_today_photo(){
        $sql= 'SELECT * FROM `my_god_today_photo` WHERE DATE(`image_date`) = CURDATE()';
        return $this->db->query($sql)->row();
    }

    public function get_client_god_old_photo(){
        $sql= 'SELECT * FROM `my_god_today_photo` WHERE DATE(`image_date`) < CURDATE() order by id';
        return $this->db->query($sql)->result();
    }

    public function insert_god_event_data($picurl){
        $Imagepath = '';
        if($picurl['file_name'] != '') {
          $Imagepath = $picurl['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'god_event_date' => date('Y-m-d',strtotime($this->input->post('god_event_date'))),
          'god_event_name' => $this->input->post('god_event_name'),
          'description' => $this->input->post('description'),
          'god_event_file' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('my_god_event',$data);
    }

    public function get_client_god_event(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('my_god_event')->result();
    }

    public function get_client_god_event_by_id($id){
        return $this->db->where('id',$id)->get('my_god_event')->row();
    }

    public function update_god_event_data_by_id($picurl, $id){
       
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'god_event_date' => date('Y-m-d',strtotime($this->input->post('god_event_date'))),
          'god_event_name' => $this->input->post('god_event_name'),
          'description' => $this->input->post('description'),
          'user_id' => $user_id,
        );
        if ($picurl['file_name'] != '') {
            $data = array_merge($data, array('god_event_file' => $picurl['file_name']));
        }
        $this->db->where('id',$id);
        return $this->db->update('my_god_event',$data);
    }


    public function insert_god_must_visit_data($picurl){
        $Imagepath = '';
        if($picurl['file_name'] != '') {
          $Imagepath = $picurl['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'name_of_the_place' => $this->input->post('name_of_the_place'),
          'description' => $this->input->post('description'),
          'god_must_visit_file' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('my_god_must_visit',$data);
    }

    public function get_client_god_must_visit(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('my_god_must_visit')->result();
    }

    public function get_client_god_must_visit_by_id($id){
        return $this->db->where('id',$id)->get('my_god_must_visit')->row();
    }

    public function update_god_must_visit_data_by_id($picurl, $id){
       
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'name_of_the_place' => $this->input->post('name_of_the_place'),
          'description' => $this->input->post('description'),
          'user_id' => $user_id,
        );
        if ($picurl['file_name'] != '') {
            $data = array_merge($data, array('god_must_visit_file' => $picurl['file_name']));
        }
        $this->db->where('id',$id);
        return $this->db->update('my_god_must_visit',$data);
    }


    public function insert_god_nearest_place_data($picurl){
        $Imagepath = '';
        if($picurl['file_name'] != '') {
          $Imagepath = $picurl['file_name'];
        }
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'name_of_the_place' => $this->input->post('name_of_the_place'),
          'description' => $this->input->post('description'),
          'god_nearest_place_file' => $Imagepath,
          'user_id' => $user_id,
        );
        return $this->db->insert('my_god_nearest_place',$data);
    }

    public function get_client_god_nearest_place(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('my_god_nearest_place')->result();
    }

    public function get_client_god_nearest_place_by_id($id){
        return $this->db->where('id',$id)->get('my_god_nearest_place')->row();
    }

    public function update_god_nearest_place_data_by_id($picurl, $id){
       
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'name_of_the_place' => $this->input->post('name_of_the_place'),
          'description' => $this->input->post('description'),
          'user_id' => $user_id,
        );
        if ($picurl['file_name'] != '') {
            $data = array_merge($data, array('god_nearest_place_file' => $picurl['file_name']));
        }
        $this->db->where('id',$id);
        return $this->db->update('my_god_nearest_place',$data);
    }

    public function god_get_all_galleries(){
        $user_id = $this->ion_auth->user()->row()->id;
      $masterCount =  $this->db->select("gallery_id, count(gallery_id) as img_count, image_name")
      ->from('god_gallery_images_master')
      ->where('user_id',$user_id)
      ->group_by('user_id')
      ->get()->result();
      $this->db->select('gl.*')
            ->from('god_gallery_list gl')
            ->where('user_id',$user_id);
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

    public function god_addGallery(){
        $user_id = $this->ion_auth->user()->row()->id; 
      $data = array(
        'gallery_name'=>$_POST['gallery_name'],
        'gallery_description'=>$_POST['gallery_description'],
        'user_id'=>$user_id
      );
      $status = $this->db->insert('god_gallery_list',$data);

      if($status) {
        $gallery['id'] = $this->db->insert_id();
        $gallery['name'] = $data['gallery_name'];
        return $gallery;
      } else {
          return 0;
      }
    }

    public function god_get_gallery_info($gallery_id){
        $user_id = $this->ion_auth->user()->row()->id; 
      $query = $this->db->where('user_id',$user_id)->get_where('god_gallery_list', array('gallery_id' => $gallery_id))->row();
      return $query;
    }

    public function god_get_images_info($gallery_id){
        $user_id = $this->ion_auth->user()->row()->id; 
      $this->db->order_by('god_gallery_images_master.image_id','DESC');
      $query = $this->db->where('user_id',$user_id)->get_where('god_gallery_images_master', array('gallery_id' => $gallery_id));
      return $query->result_array();;
    }

    public function god_save_gallery_images($images){
        return $this->db->insert_batch('god_gallery_images_master',$images);
    }

    public function god_delete_image_db($image_id){
      $this->db->where('image_id', $image_id);
      $res1 = $this->db->delete('god_gallery_images_master');
      return $res1;
    }

    public function delete_album_db($gId){
      $this->db->where('gallery_id',$gId);
      $this->db->delete('god_gallery_list');

      $this->db->where('gallery_id', $gId);
      return $this->db->delete('god_gallery_images_master');
      
    }

    // how to reach

    public function insert_god_how_to_reach_data(){
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'how_to_reach' => $this->input->post('how_to_reach'),
          'how_to_reach_title' => $this->input->post('how_to_reach_title'),
          'description' => $this->input->post('description'),
          'user_id' => $user_id,
        );
        return $this->db->insert('my_god_how_to_reach',$data);
    }

    public function get_client_god_how_to_reach(){
        $user_id = $this->ion_auth->user()->row()->id;
        return $this->db->where('user_id',$user_id)->get('my_god_how_to_reach')->result();
    }

    public function get_client_god_how_to_reach_id($id){
        return $this->db->where('id',$id)->get('my_god_how_to_reach')->row();
    }

    public function update_god_how_to_reach_by_id($id){
       
        $user_id = $this->ion_auth->user()->row()->id;
        $data = array(
          'how_to_reach' => $this->input->post('how_to_reach'),
          'how_to_reach_title' => $this->input->post('how_to_reach_title'),
          'description' => $this->input->post('description'),
          'user_id' => $user_id,
        );
        $this->db->where('id',$id);
        return $this->db->update('my_god_how_to_reach',$data);
    }

    public function upload_god_photo($save_images){
        return $this->db->insert_batch('my_god_photo',$save_images);
    }

    public function get_client_god_photo(){
        $sql= 'SELECT * FROM `my_god_photo` order by id';
        return $this->db->query($sql)->result();
    }

    public function deletegodphotobyid($id){
        $this->db->where('id',$id);
        return $this->db->delete('my_god_photo');
    }
}
?>