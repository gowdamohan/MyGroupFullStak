<?php

class Franchise extends CI_Controller {
               
	function __construct(){
 		parent::__construct();	
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
        $this->load->model('admin_model');
        $this->load->model('franchise_model');
        $this->load->model('country_model');
        $this->load->library('filemanager');
	}	

	public function corporate_login(){
		$data['users'] = $this->franchise_model->get_corporate_users(5)->result();
		$data['main_content']    = 'admin/franchise/corporate_login';
        $this->load->view('admin/inc/template',$data);
	}

	public function corporate_login_insert(){
		$input = $this->input->post();
    	$username =  $input['username'];
	    $password =  $input['password'];
	    $email = $input['franchise_email_id'];
	    $additional_data = array(
	                'first_name' => $input['franchise_name'],
	                'phone' => $input['franchise_mobile_number']
	                );
	    $group = array('5'); // Sets user to Corporate Login.
	    $result = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
    	$this->franchise_model->save_franchise_login_details(0,0,0,$result);
	    if ($result) {
        $this->session->set_flashdata('flashSuccess', "Successfully inserted");
      }else{
        $this->session->set_flashdata('flashError', "Something went wrong");
      }
      redirect('franchise/corporate_login');
	}

	public function reset_password($user_id){
		$data = array(
          'password' => '123456',
        );
		$result = $this->ion_auth_model->update($user_id, $data);
		if ($result) {
          $this->session->set_flashdata('flashSuccess', "Successfully Reset");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect('franchise/corporate_login');
	}

	public function create_head_office_login(){
		$result = $this->franchise_model->get_franhise_holder(6)->result();
		$franchise_holder = [];
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$franchise_holder[$val->country] = $val;
			}
		}
		$countrylist = $this->country_model->get_country_list();
		foreach ($countrylist as $key => $val) {
			$val->username = 'my_'.$val->country;
			$val->first_name = '';
			$val->email = '';
			$val->phone = '';
			$val->password = '';
			$val->user_id = '';
			$val->active = '';
			if (array_key_exists($val->id, $franchise_holder)) {
				$val->username = $franchise_holder[$val->id]->username;
				$val->first_name = $franchise_holder[$val->id]->first_name;
				$val->email = $franchise_holder[$val->id]->email;
				$val->phone = $franchise_holder[$val->id]->phone;
				$val->password = 'disabled';
				$val->user_id = $franchise_holder[$val->id]->id;
				$val->active = $franchise_holder[$val->id]->active;
			}
		}
		$data['franchise_holder'] = $countrylist;
    $data['main_content']    = 'admin/franchise/head_office_login';
    $this->load->view('admin/inc/template_head_franchise',$data);
	}
	public function save_franchise_login(){
    	$username =  $_POST['username'];
	    $password =  '123456';
	    $email = $_POST['franchise_email_id'];
	    $additional_data = array(
	                'first_name' => $_POST['franchise_name'],
	                'phone' => $_POST['franchise_mobile_number']
	                );
	    $group = array($_POST['group_id']); // Sets group id

	    $this->db->where('username',$username);
	    $query = $this->db->get('users');

	    if ($query->num_rows() > 0) {
    	 	$updateData = array(
          'first_name' => $_POST['franchise_name'],
          'phone' => $_POST['franchise_mobile_number'],
          'password' =>  '123456',
          'email' =>  $_POST['franchise_email_id']
        );
	    	$result = $this->ion_auth->update($query->row()->id, $updateData);
	    }else{
	    	$result = $this->ion_auth->register($username, $password, $email, $additional_data, $group);
	    	$this->franchise_model->save_franchise_login_details($_POST['franchise_country'],$_POST['franchise_state'],$_POST['franchise_district'],$result);
	    }

	    if ($result) {
	    		$message ='Welcome to My group of Companies. <br> Thank you for being a part of our comapny. We happy to grow together. <b>Franchise Login Details below <b>
	    		<br><b>Username:</b>'.$username.'<br><b>Password:</b> '.$password.' <br><b>Use below link to Login <b><br><a href="http://www.gomygroup.com/login">Click here to Login of GOMYGROUP</a>';
	    		$this->_send_otp_regsiter_email($email, $message);
          $this->session->set_flashdata('flashSuccess', "Successfully inserted");
        }else{
          $this->session->set_flashdata('flashError', "Something went wrong");
        }
        redirect($_POST['redirect_url']);
	}

	public function franchise_reset_password($user_id,$redirect_url){
		$data = array(
      'password' => '123456',
    );
		$result = $this->ion_auth_model->update($user_id, $data);
		if ($result) {
			// $message ='Welcome to My group of Companies. <br> Thank you for being a part of our comapny. We happy to grow together. <b>Franchise Login Details below <b>
   //  		<br><b>Username:</b>'.$username.'<br><b>Password:</b> '.$password.' <br><b>Use below link to Login <b><br><a href="http://www.gomygroup.com/login">Click here to Login of GOMYGROUP</a> '
   //  		$this->_send_otp_regsiter_email($email, $message);
      $this->session->set_flashdata('flashSuccess', "Successfully Reset");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    redirect('franchise'.'/'.$redirect_url);
	}

	  public function _send_otp_regsiter_email($email, $msg){
    	
    	$curl = curl_init();
    	curl_setopt_array($curl, array(
	      CURLOPT_URL => 'https://mypolicetv.com/otpmygroup.php',
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_ENCODING => "",
	      CURLOPT_MAXREDIRS => 10,
	      CURLOPT_TIMEOUT => 30,
	      CURLOPT_USERPWD => '',
	      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	      CURLOPT_CUSTOMREQUEST => "POST",
	      CURLOPT_POST => 1,
	      CURLOPT_POSTFIELDS =>'from=noreply@gomygroup.com&to='.$email.'&msg='.$msg.'&subject="Forgot Password One Time Password"',
	      CURLOPT_HTTPHEADER => array(
	        "Accept: application/json",
	        "Cache-Control: no-cache",
	        "Content-Type: application/x-www-form-urlencoded",
	        "Postman-Token: 090abdb9-b680-4492-b8b7-db81867b114e"
	      ),
	    ));

	    $response = curl_exec($curl);
	    $err = curl_error($curl);
	    curl_close($curl);
	    if ($err) {
	      // echo "cURL Error #:" . $err;
	      return 0;
	    } else {
	      return 1;
	    }

    }

	public function create_regional_office_login(){
		$result = $this->franchise_model->get_franhise_holder(7)->result();
		$franchise_holder = [];
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$franchise_holder[$val->state][$val->country] = $val;
			}
		}
		$statelist = $this->country_model->get_all_state();
		foreach ($statelist as $key => $val) {
			$val->username = strtolower($val->country_code).'_'.strtolower(str_replace(' ', '', $val->state));
			$val->first_name = '';
			$val->email = '';
			$val->phone = '';
			$val->password = '';
			$val->user_id = '';
			$val->active = '';
			if (array_key_exists($val->id, $franchise_holder)) {
				$val->username = $franchise_holder[$val->id][$val->country_id]->username;
				$val->first_name = $franchise_holder[$val->id][$val->country_id]->first_name;
				$val->email = $franchise_holder[$val->id][$val->country_id]->email;
				$val->phone = $franchise_holder[$val->id][$val->country_id]->phone;
				$val->password = 'disabled';
				$val->user_id = $franchise_holder[$val->id][$val->country_id]->id;
				$val->active = $franchise_holder[$val->id][$val->country_id]->active;
			}
		}
		$data['franchise_holder'] = $statelist;
    $data['main_content']    = 'admin/franchise/regional_office_login';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function create_branch_office_login(){
		$stateId = $this->input->post('state_id');

		$result = $this->franchise_model->get_franhise_holder(8)->result();
		$franchise_holder = [];
		if (!empty($result)) {
			foreach ($result as $key => $val) {
				$franchise_holder[$val->district][$val->state][$val->country] = $val;
			}
		}
		$data['statelist'] = $this->country_model->get_all_state();
		$districtList = [];
		$data['selected_state_id'] = '';
		if (!empty($stateId)) {
			$data['selected_state_id'] = $stateId;
			$districtList = $this->franchise_model->get_district_detailsbystate_id($stateId);
		}
	
		if (!empty($districtList)) {
			foreach ($districtList as $key => $val) {
				$val->username = strtolower($val->country_code).''.strtolower($val->state_code).'_'.strtolower(str_replace(' ', '', $val->district));;
				$val->first_name = '';
				$val->email = '';
				$val->phone = '';
				$val->password = '';
				$val->user_id = '';
				$val->active = '';
				if (array_key_exists($val->id, $franchise_holder)) {
					$val->username = $franchise_holder[$val->id][$val->state_id][$val->country_id]->username;
					$val->first_name = $franchise_holder[$val->id][$val->state_id][$val->country_id]->first_name;
					$val->email = $franchise_holder[$val->id][$val->state_id][$val->country_id]->email;
					$val->phone = $franchise_holder[$val->id][$val->state_id][$val->country_id]->phone;
					$val->password = 'disabled';
					$val->user_id = $franchise_holder[$val->id][$val->state_id][$val->country_id]->id;
					$val->active = $franchise_holder[$val->id][$val->state_id][$val->country_id]->active;
				}
			}
		}
		$data['franchise_holder'] = $districtList;
    $data['main_content']    = 'admin/franchise/branch_office_login';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function create_header_ads_head_office(){
		$data['myapps'] = $this->franchise_model->get_all_my_aps_sub();
		$data['franch'] = $this->franchise_model->get_franchies_holder_details();
		$data['uploaded_data'] = $this->franchise_model->get_ads_uploaded_data();
	 	$data['main_content']    = 'admin/franchise/create_ads_head_office';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function get_uploaded_ads_bydata(){
		$myaps = $_POST['myaps'];
		$subAds = $_POST['subAds'];
		$franchHolderId = $_POST['franchHolderId'];
		$result = $this->franchise_model->get_uploaded_ads_bydata_details($myaps, $subAds, $franchHolderId);
		echo json_encode($result);
	}

	public function get_uploaded_ads_branch_bydata_details(){
		$myaps = $_POST['myaps'];
		$subAds = $_POST['subAds'];
		$franchHolderId = $_POST['franchHolderId'];
		$result = $this->franchise_model->get_uploaded_ads_branch_bydata_details($myaps, $subAds, $franchHolderId);
		echo json_encode($result);
	}

	public function upload_franchise_data(){
		echo $this->franchise_model->upload_franchise_details($_POST);
	}

	public function create_header_ads_branch_office(){
		$data['myapps'] = $this->franchise_model->get_all_my_aps_sub();
		$data['franch'] = $this->franchise_model->get_franchies_holder_details();
		$data['uploaded_data'] = $this->franchise_model->get_branch_ads_uploaded_data();
	 	$data['main_content']    = 'admin/franchise/create_ads_branch_office';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function upload_franchise_branch_data(){
		echo $this->franchise_model->upload_franchise_branch_details($_POST);
	}

	public function franchise_offer_ads(){
		$data['offer_ads'] = $this->franchise_model->franchise_offer_ads_data();
		$data['main_content']    = 'admin/franchise/franchise_offer_ads';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function upload_franchise_offer_data(){
		echo $this->franchise_model->upload_franchise_offer_image($_POST);
	}

	public function delete_franchise_offer_delete($id){
		$result = $this->franchise_model->delete_franchise_offer_deletebyid($id);
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    redirect('franchise/franchise_offer_ads');
	}

	public function update_user_active_status(){
		$stngId = $_POST['stngId'];
    $value = $_POST['value'];
    echo $this->franchise_model->update_user_active_status_by_id($stngId,$value); 
	}

	public function franchise_staff_details(){
		$data['franchise_staff'] = $this->franchise_model->get_franchise_staff_all();
		$data['main_content']    = 'admin/franchise/staff_details';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function create_franchise_staff($user_id, $loginTransver){
		$data['loginTransver'] = $loginTransver;
		$data['user_id'] = $user_id;
		$data['staff_profile'] = $this->franchise_model->get_franchise_staff_by_user_id($user_id);
		$data['franchise_staff_docs'] = $this->franchise_model->get_franchise_staff_documents($user_id);
		$data['main_content']    = 'admin/franchise/create_staff_details';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function upload_franchise_staff_data($user_id, $loginTransver){
		$result = $this->franchise_model->upload_franchise_staff_data_details($user_id);
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully inserted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    redirect('franchise/create_franchise_staff/'.$user_id.'/'.$loginTransver);
	}

	public function update_franchise_staff_data($user_id, $id, $loginTransver){
		$result = $this->franchise_model->update_franchise_staff_data_details($id);
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully update");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }

   	redirect('franchise/create_franchise_staff/'.$user_id.'/'.$loginTransver);
	}

	public function delete_franchise_staff_by_id($id){
		$result = $this->franchise_model->delete_franchise_staff_by_id($id);
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    redirect('franchise/franchise_staff_details');
	}

	public function upload_franchise_staff_documents($id){
		$data['id'] = $id;
		// $data['franchise_staff_docs'] = $this->franchise_model->get_franchise_staff_documents($id);
		$data['main_content']    = 'admin/franchise/upload_document';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function upload_franchise_staff_document_data(){
		echo $this->franchise_model->upload_franchise_staff_document_data($_POST);
	}

	public function delete_franchise_staff_document_delete($user_id, $id, $loginTransver){
		$result = $this->franchise_model->delete_franchise_staff_document_deletebyid($id);
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully Deleted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
    	redirect('franchise/create_franchise_staff/'.$user_id.'/'.$loginTransver);
	}

	public function corporate_header_ads(){
		$data['myapps'] = $this->franchise_model->get_all_my_aps_sub();
		$data['franch'] = $this->franchise_model->get_franchies_holder_details();
		$data['uploaded_data'] = $this->franchise_model->get_ads_uploaded_data_corporate();
		$data['main_content']    = 'admin/franchise/corporate_header_ads';
    $this->load->view('admin/inc/template_head_franchise',$data);
	}

 	public function popup_add(){
    $data['popupads'] = $this->admin_model->get_popu_ads();
    $data['main_content'] = 'admin/pages/popup_ads';
   	$this->load->view('admin/inc/template_head_franchise',$data);
  }
  
	public function upload($filename = '', $upload_path){
    $config['upload_path'] = $upload_path;
    $config['allowed_types'] = '*';
    $config['remove_spaces'] = true;
    $config['overwrite'] = true;
    $this->load->library('upload', $config);
    $this->upload->initialize($config);
    if (!$this->upload->do_upload($filename)) {
        $error = array('status' => 'error', 'data' => $this->upload->display_errors());
        $this -> session -> set_flashdata('flashError', 'Failed to upload - ' . $filename);
        return $error;
    } else {
        $image = $this->upload->data();
        $success = array('status' => 'success', 'data' => $image);
        return $success;
    }
	}

  
  public function upload_side_ads(){
        $group = $this->db->get('popup_ads')->row();
        $creDir = 'uploads/files/';
        if (!is_dir($creDir)) {
            mkdir($creDir, 0777, TRUE);
        }

        if (!empty($_FILES['side_ads'])) {
            if ($_FILES['side_ads']['name'] != "") {
                $filename = $group->side_ads; 
                if (file_exists($filename)){
                  unlink($filename);
                }
                $ret_val = $this->upload('side_ads', $creDir);
                if ($ret_val['status'] == 'success') {
                    $sideAds = $ret_val['data'];
                }else{
                    $sideAds = $ret_val['data'];
                }
            }else{
                $sideAds ="";
            }
        }else{
            $sideAds ="";
        }


        $result = $this->admin_model->insert_upload_sides($sideAds);
        if ($result) {
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
        }
        redirect('franchise/popup_add');
    }
    
	public function terms_conditions(){
		$data['terms'] = $this->franchise_model->franchise_terms_conditions();
		$data['main_content']    = 'admin/franchise/terms_conditions';
    $this->load->view('admin/inc/template_head_franchise',$data);
	}

	public function terms_conditions_insert(){
		$result = $this->franchise_model->terms_conditions_insert_details();
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully inserted");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
  		redirect('franchise/terms_conditions');
	}

	public function terms_conditions_update($id){
		$result = $this->franchise_model->terms_conditions_update_details($id);
		if ($result) {
      $this->session->set_flashdata('flashSuccess', "Successfully Updated");
    }else{
      $this->session->set_flashdata('flashError', "Something went wrong");
    }
  		redirect('franchise/terms_conditions');
	}

	public function terms_conditions_view(){
		$data['terms'] = $this->franchise_model->franchise_terms_conditions();
		$data['main_content']    = 'admin/franchise/terms_conditions_view';
    $this->load->view('admin/inc/template_franchise',$data);
	}

	public function my_company_header_ads($value=''){
  	$data['advertise'] = $this->admin_model->advertise_group();
    $data['main_content']    = 'admin/pages/advertise';
   	$this->load->view('admin/inc/template_head_franchise',$data);
	}

	public function main_page_ads(){
  	$data['main_ads'] = $this->admin_model->main_ads_group();
  	// echo "<pre>"; print_r($data['main_ads']); die();
    $data['main_content']    = 'admin/pages/main_page_advertise';
   	$this->load->view('admin/inc/template_head_franchise',$data);
	}

	public function upload_group_main_adertise(){
    $creDir = 'uploads/adertise/';
    if (!is_dir($creDir)) {
        mkdir($creDir, 0777, TRUE);
    }
    if (!empty($_FILES['ads1'])) {
        if ($_FILES['ads1']['name'] != "") {
            $ret_val = $this->upload('ads1', $creDir);
            if ($ret_val['status'] == 'success') {
                $ads1 = $ret_val['data'];
            }else{
                $ads1 = $ret_val['data'];
            }
        }else{
            $ads1 ="";
        }
    }else{
        $ads1 ="";
    }
    
    if (!empty($_FILES['ads2'])) {
        if ($_FILES['ads2']['name'] != "") {
                $ret_val = $this->upload('ads2', $creDir);
            if ($ret_val['status'] == 'success') {
                $ads2 = $ret_val['data'];
            }else{
                $ads2 = $ret_val['data'];
            }
        }else{
            $ads2 ="";
        }  
    }else{
        $ads2 ="";
    }

    
    if (!empty($_FILES['ads3'])) {
        if ($_FILES['ads3']['name'] != "") {
            $ret_val = $this->upload('ads3', $creDir);
            if ($ret_val['status'] == 'success') {
                $ads3 = $ret_val['data'];
            }else{
                $ads3 = $ret_val['data'];
            }
        }else{
            $ads3 ="";
        }
    }else{
        $ads3 ="";
    }
    
   
    $result = $this->admin_model->insert_upload_group_main_adverise($ads1, $ads2, $ads3);

    if ($result) {
        $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
    }else{
        $this->session->set_flashdata('flashSuccess', 'Something went wrong.');
    }
    redirect('franchise/main_page_ads');
  }

}