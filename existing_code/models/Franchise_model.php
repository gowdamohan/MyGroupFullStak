<?php
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Franchise_model extends CI_Model {

        public function __construct(){
                parent::__construct();
        }

        public function get_corporate_users($group_id){
            return $this->db->select('u.*')
            ->from('users_groups ug')
            ->where('ug.group_id',$group_id)
            ->join('users u','ug.user_id=u.id')
            ->get();
        }

        public function get_franhise_holder($group_id){
           return $this->db->select('u.*, country, state, district')
            ->from('users_groups ug')
            ->where('ug.group_id',$group_id)
            ->join('users u','ug.user_id=u.id')
            ->join('franchise_holder fh','u.id=fh.user_id')
            ->get();
        }

        public function get_franhise_holder_address_head_office($country, $group_id){
            return $this->db->select('fs.address, fs.phone, fs.email, fs.first_name, ct.country as country_name')
            ->from('users_groups ug')
            ->where('ug.group_id',$group_id)
            ->join('users u','ug.user_id=u.id')
            ->join('franchise_holder fh','u.id=fh.user_id')
            ->join('country_tbl ct','fh.country=ct.id')
            ->join('franchise_staff fs','u.id=fs.user_id')
            ->where('fh.country',$country)
            ->get();
        }

        public function get_franhise_holder_regional_address($country, $state, $group_id){
            return $this->db->select('fs.address, fs.phone, fs.email, fs.first_name, ct.country as country_name, st.state as state_name')
            ->from('users_groups ug')
            ->where('ug.group_id',$group_id)
            ->join('users u','ug.user_id=u.id')
            ->join('franchise_holder fh','u.id=fh.user_id')
            ->join('country_tbl ct','fh.country=ct.id')
            ->join('state_tbl st','fh.state=st.id')
            ->join('franchise_staff fs','u.id=fs.user_id')
            ->where('fh.country',$country)
            ->where('fh.state',$state)
            ->get();
        }

        public function get_franhise_branch_holder_address($country, $state, $district, $group_id){
            return $this->db->select('fs.address, fs.phone, fs.email, fs.first_name, ct.country as country_name, st.state as state_name, dt.district as district_name')
            ->from('users_groups ug')
            ->where('ug.group_id',$group_id)
            ->join('users u','ug.user_id=u.id')
            ->join('franchise_holder fh','u.id=fh.user_id')
            ->join('country_tbl ct','fh.country=ct.id')
            ->join('state_tbl st','fh.state=st.id')
            ->join('district_tbl dt','fh.district=dt.id')
            ->join('franchise_staff fs','u.id=fs.user_id')
            ->where('fh.country',$country)
            ->where('fh.state',$state)
            ->where('fh.district',$district)
            ->get();
        }

        public function get_bycurrentlocation_address(){
            $userId = $this->ion_auth->user()->row();

            return $this->db->select('(case when set_country is not null then set_country else country end) as country, (case when set_state is not null then set_state else state end) as state, (case when set_district is not null then set_district else district end) as district')
            ->from('user_registration_form')
            ->where('user_id',$userId->id)
            ->get()->row();
             
        }

        public function save_franchise_login_details($country,$franchise_state,$franchise_district,$user_id){
            $data = array(
              'country'=>$country,
              'state'=>$franchise_state,
              'district'=>$franchise_district,
              'user_id'=>$user_id,
            );
            return $this->db->insert('franchise_holder',$data);
        }

        public function get_district_detailsbystate_id($state_id){
            return $this->db->select('cn.continent, co.country, co.code as country_code, st.code as state_code, st.id as state_id, co.id as country_id, st.state, dt.district, dt.id, dt.order, dt.status, dt.code')
            ->from('district_tbl dt')
            ->join('state_tbl st','dt.state_id=st.id')
            ->where('st.id',$state_id)
            ->join('country_tbl co','st.country_id=co.id')
            ->join('continent_tbl cn','co.continent_id=cn.id')
            ->get()->result();
        }

        public function get_all_my_aps_sub(){
            $result = $this->db->select('gc.id, gc.apps_name, gc.name, cd.icon, cd.url')
            ->from('group_create gc')
            ->join('create_details cd','gc.id=cd.create_id')
            ->where('gc.apps_name','My Apps')
            ->order_by('gc.id')
            ->get()->result();

            $totalApps = [];
            foreach ($result as $key => $val) {
                switch ($val->name) {
                    case 'Mychat':
                           $val->sub_group = ['Mychat'];
                            break;
                    case 'Mydiary':
                           $val->sub_group = ['Qk Note','Day Plan','My Docs','Quotes','Accounts','Home'];
                            break;
                    case 'Mymedia':
                           $val->sub_group = ['Tv','Radio','E Paper','Magazine','Web','Youtube'];
                            break;
                    case 'Myjoy':
                           $val->sub_group = ['Myvideo','Myaudio','Mybooks','Mypage','Mytok','Mygames'];
                            break;
                    case 'Mybank':
                           $val->sub_group = ['Mypay','Mybank','Mycard','Myloans','Insurance','Myinvest'];
                            break;
                    case 'Myshop':
                           $val->sub_group = ['Shop','Local','Resale','Brands','Wholesale','Ecoshop'];
                            break;
                    case 'Myfriend':
                           $val->sub_group = ['Myfriend','Mymarry','Myjobs','Health','Travel','Booking'];
                            break;
                    case 'Myunions':
                           $val->sub_group = ['News','Unions','Federation','IDs','Notice','Me'];
                            break;
                    case 'Mybiz':
                           $val->sub_group = ['Production','Finance','Advertise','Franchises','Trading','Services'];
                            break;
                    case 'Mytv':
                           $val->sub_group = ['Mytv','Myradio','Mypaper','Reporter','Gallery','Public'];
                            break;
                    case 'Myneedy':
                           $val->sub_group = ['Doorstep','Centers','Manpower','Online','Myhelp','Myorders'];
                            break;
                    
                    default:
                            // code...
                            break;
                }
            }
            return $result;
        }

        public function get_franchies_holder_details(){
                $userid = $this->ion_auth->user()->row()->id;
                return $this->db->select('fh.id as franchise_holder_id')
                ->from('franchise_holder fh')
                ->where('fh.user_id',$userid)
                ->get()->row();
        }

        public function upload_franchise_details($input){
                $data = array(
                  'franchise_holder_id' => $input['franchiseHolderId'],
                  'my_app_name' => $input['myappName'],
                  'my_app_sub_name' =>$input['mysubAppName'],
                  'image_path' => $input['imagepath'],
                  'image_url' => $input['adsURl'],
                );
                $this->db->where('franchise_holder_id',$input['franchiseHolderId']);
                $this->db->where('my_app_name',$input['myappName']);
                $this->db->where('my_app_sub_name',$input['mysubAppName']);
                $query = $this->db->get('franchise_advertise');
                if ($query->num_rows() > 0) {
                    $this->db->where('franchise_holder_id',$input['franchiseHolderId']);
                    $this->db->where('my_app_name',$input['myappName']);
                    $this->db->where('my_app_sub_name',$input['mysubAppName']);
                    return $this->db->update('franchise_advertise',$data);
                }
                return $this->db->insert('franchise_advertise',$data);
        }

        public function get_uploaded_ads_bydata_details($myaps, $subAds, $franchHolderId){
            $this->db->where('franchise_holder_id',$franchHolderId);
            $this->db->where('my_app_name',$myaps);
            $this->db->where('my_app_sub_name',$subAds);
            return $this->db->get('franchise_advertise')->row();
        }

         public function get_uploaded_ads_branch_bydata_details($myaps, $subAds, $franchHolderId){
            $this->db->where('franchise_holder_id',$franchHolderId);
            $this->db->where('my_app_name',$myaps);
            $this->db->where('my_app_sub_name',$subAds);
            return $this->db->get('franchise_advertise1')->row();
        }


        
        public function upload_franchise_branch_details($input){
            $data = array(
              'franchise_holder_id' => $input['franchiseHolderId'],
              'my_app_name' => $input['myappName'],
              'my_app_sub_name' =>$input['mysubAppName'],
              'image_path' => $input['imagepath'],
              'image_url' => $input['adsURl'],
            );
            $this->db->where('franchise_holder_id',$input['franchiseHolderId']);
            $this->db->where('my_app_name',$input['myappName']);
            $this->db->where('my_app_sub_name',$input['mysubAppName']);
            $query = $this->db->get('franchise_advertise1');
            if ($query->num_rows() > 0) {
                $this->db->where('franchise_holder_id',$input['franchiseHolderId']);
                $this->db->where('my_app_name',$input['myappName']);
                $this->db->where('my_app_sub_name',$input['mysubAppName']);
                return $this->db->update('franchise_advertise1',$data);
            }
            return $this->db->insert('franchise_advertise1',$data);
        }

        public function get_ads_uploaded_data(){
                $userid = $this->ion_auth->user()->row()->id;
                $holderId =  $this->db->select('fh.id as franchise_holder_id')
                ->from('franchise_holder fh')
                ->where('fh.user_id',$userid)
                ->get()->row();

                $result =  $this->db->select('*')
                ->from('franchise_advertise')
                ->where('franchise_holder_id',$holderId->franchise_holder_id)
                ->get()->result();
                $adsUploaded = [];
                foreach ($result as $key => $value) {
                      $adsUploaded[$value->my_app_name][$value->my_app_sub_name] = $value;
                }
                return $adsUploaded;
        }

        public function get_ads_uploaded_data_corporate(){
            $userid = $this->ion_auth->user()->row()->id;
            $holderId =  $this->db->select('fh.id as franchise_holder_id')
            ->from('franchise_holder fh')
            ->where('fh.user_id',$userid)
            ->get()->row();
                
            $result =  $this->db->select('*')
            ->from('franchise_advertise')
            ->where('franchise_holder_id',$holderId->franchise_holder_id)
            ->get()->result();
            $adsUploaded = [];
            foreach ($result as $key => $value) {
                  $adsUploaded[$value->my_app_name][$value->my_app_sub_name] = $value;
            }
            return $adsUploaded;
        }

        public function get_branch_ads_uploaded_data(){
            $userid = $this->ion_auth->user()->row()->id;
            $holderId =  $this->db->select('fh.id as franchise_holder_id')
            ->from('franchise_holder fh')
            ->where('fh.user_id',$userid)
            ->get()->row();

            $result =  $this->db->select('*')
            ->from('franchise_advertise1')
            ->where('franchise_holder_id',$holderId->franchise_holder_id)
            ->get()->result();
            $adsUploaded = [];
            foreach ($result as $key => $value) {
                  $adsUploaded[$value->my_app_name][$value->my_app_sub_name] = $value;
            }
            return $adsUploaded;
        }

        public function upload_franchise_offer_image($input){
            $data = array(
                'imagepath' => $input['imagepath'],
                'ads_name' => $input['ads_name'],
            );
            return $this->db->insert('franchise_ads',$data);
        }

        public function franchise_offer_ads_data(){
            return $this->db->select('*')
            ->from('franchise_ads')
            ->get()->result();
        }

        public function delete_franchise_offer_deletebyid($id){
            $this->db->where('id',$id);
            return $this->db->delete('franchise_ads');
        }

        public function update_user_active_status_by_id($stngId,$value){
            $data = array(
                'active' => $value,
            );
            $this->db->where('id',$stngId);
            return $this->db->update('users', $data);
        }

        public function upload_franchise_staff_data_details($user_id){
            $input = $this->input->post();
            $data = array(
                'first_name' => $input['first_name'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'address' => $input['address'],
                'created_on' => date('Y-m-d h:i'),
                'user_id'=>$user_id
            );
            return $this->db->insert('franchise_staff',$data);
        }

        public function update_franchise_staff_data_details($id){
            $input = $this->input->post();
            $data = array(
                'first_name' => $input['first_name'],
                'phone' => $input['phone'],
                'email' => $input['email'],
                'address' => $input['address'],
                'created_on' => date('Y-m-d h:i')
            );
            $this->db->where('id',$id);
            return $this->db->update('franchise_staff',$data);
        }
        public function get_franchise_staff_all(){
             return $this->db->select('*')
            ->from('franchise_staff')
            ->get()->result();
        }

        public function upload_franchise_staff_document_data($input){
            $data = array(
                'imagepath' => $input['imagepath'],
                'franchise_staff_id' => $input['franchise_staff_id'],
                'document_name' => $input['document_name'],
            );
            return $this->db->insert('franchise_staff_document',$data);
        }

        public function get_franchise_staff_documents($user_id){
            return $this->db->select('fsd.*')
            ->from('franchise_staff fs')
            ->where('fs.user_id',$user_id)
            ->join('franchise_staff_document fsd','fsd.franchise_staff_id=fs.id')
            ->get()->result();
        }

        public function delete_franchise_staff_document_deletebyid($id){
            $this->db->where('id',$id);
            return $this->db->delete('franchise_staff_document');
        }

        public function delete_franchise_staff_by_id($id){
            $this->db->where('id',$id);
            return $this->db->delete('franchise_staff');
        }

        public function get_franchise_staff_by_user_id($user_id){
            return $this->db->select('*')
            ->from('franchise_staff')
            ->where('user_id',$user_id)
            ->get()->row();
        }

        public function franchise_terms_conditions(){
            return $this->db->get('franchise_terms_conditions')->row();
        }

        public function terms_conditions_insert_details(){
            $data = array(
                'content' => $this->input->post('franchise_content')
            );
            return $this->db->insert('franchise_terms_conditions',$data); 
        }

        public function terms_conditions_update_details($id){
            $data = array(
                'content' => $this->input->post('franchise_content')
            );
             $this->db->where('id',$id);
            return $this->db->update('franchise_terms_conditions',$data); 
        }
}