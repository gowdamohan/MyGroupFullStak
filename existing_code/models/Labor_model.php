<?php

class Labor_model extends CI_Model{
  
  	public function __construct(){
    	parent::__construct();
  	}

 	public function get_category_list(){
	    return $this->db->get('labor_category')->result();
  	}

  	public function get_category_by_id($id){
	    return $this->db->where('id',$id)->get('labor_category')->row();
  	}

  	public function insert_category(){
	    $data = array(
	      'name' => $this->input->post('category'), 
	    );
	    return $this->db->insert('labor_category',$data);
  	}

  	public function update_categorybyId($id){
	    $data = array(
	      'name' => $this->input->post('category'), 
	    );
	    $this->db->where('id',$id);
	    return $this->db->update('labor_category',$data);  
  	}

  	public function delete_categorybyId($id){
	    return $this->db->where('id',$id)->delete('labor_category');
  	}

  	public function get_labor_details(){
  		 return $this->db->select("lp.*, CONCAT_WS('-', from_date, from_month, from_year) AS date_of_birth,   CONCAT_WS(' ', address_line1, address_line2, dt.district, st.state,ct.country,lp.address_pincode) AS address")
  		 	->from('labor_profile lp')
	  		->join('country_tbl ct','lp.location_country=ct.id')
	        ->join('state_tbl st','lp.location_state=st.id')
	        ->join('district_tbl dt','lp.location_district=dt.id')
  		 	->get()->result();
  	}

  	public function get_labor_detailsviewbyId($id){
  		 return $this->db->select("lp.*, CONCAT_WS('-', from_date, from_month, from_year) AS date_of_birth,   CONCAT_WS(' ', address_line1, address_line2, dt.district, st.state,ct.country,lp.address_pincode) AS address")
  		 	->from('labor_profile lp')
  		 	->where('lp.id',$id)
	  		->join('country_tbl ct','lp.location_country=ct.id')
	        ->join('state_tbl st','lp.location_state=st.id')
	        ->join('district_tbl dt','lp.location_district=dt.id')
  		 	->get()->row();
  	}

  	public function get_labor_detailsbyid($id){
  		return $this->db->where('id',$id)->get('labor_profile')->row();
  	}

  	public function insert_labor_details(){
  		$input = $this->input->post();
	 	$data = array(
	        'labor_name' => (!isset($input['labor_name']) || $input['labor_name'] == '')? null : $input['labor_name'],
	        'father_husband_name' => (!isset($input['father_husband_name']) || $input['father_husband_name'] == '')? null : $input['father_husband_name'],
	        'mobile_number' =>(!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'],
	        'from_date' => (!isset($input['from_date']) || $input['from_date'] == '')? null : $input['from_date'],
	        'from_month' => (!isset($input['from_month']) || $input['from_month'] == '')? null : $input['from_month'],
	        'from_year' =>(!isset($input['from_year']) || $input['from_year'] == '')? null : $input['from_year'],
	        'blood_group' => (!isset($input['blood_group']) || $input['blood_group'] == '')? null : $input['blood_group'],
	        'category' => (!isset($input['category']) || $input['category'] == '')? null : $input['category'],
	        'category1' => (!isset($input['category1']) || $input['category1'] == '')? null : $input['category1'],
	        'contractor' => (!isset($input['contractor']) || $input['contractor'] == '')? null : $input['contractor'],
	        'category2' => (!isset($input['category2']) || $input['category2'] == '')? null : $input['category2'],
	        'aadhar_number'=>(!isset($input['aadhar_number']) || $input['aadhar_number'] == '')? null : $input['aadhar_number'],
	        'aadhar_front_photo'=>(!isset($input['aadhar_front_photo']) || $input['aadhar_front_photo'] == '')? null : $input['aadhar_front_photo'],
	        'aadhar_back_photo'=>(!isset($input['aadhar_back_photo']) || $input['aadhar_back_photo'] == '')? null : $input['aadhar_back_photo'],
	        'labor_photo'=>(!isset($input['labor_photo']) || $input['labor_photo'] == '')? null : $input['labor_photo'],
	        'address_line1'=>(!isset($input['address_line1']) || $input['address_line1'] == '')? null : $input['address_line1'],
	        'address_line2'=>(!isset($input['address_line2']) || $input['address_line2'] == '')? null : $input['address_line2'],
	        'location_country'=>(!isset($input['location_country']) || $input['location_country'] == '')? null : $input['location_country'],
	        'location_state'=>(!isset($input['location_state']) || $input['location_state'] == '')? null : $input['location_state'],
	        'location_district'=>(!isset($input['location_district']) || $input['location_district'] == '')? null : $input['location_district'],
	        'address_pincode'=>(!isset($input['address_pincode']) || $input['address_pincode'] == '')? null : $input['address_pincode'],
	        'labor_id_number'=>(!isset($input['labor_id_number']) || $input['labor_id_number'] == '')? null : $input['labor_id_number'],
	        'labor_amount'=>(!isset($input['labor_amount']) || $input['labor_amount'] == '')? null : $input['labor_amount'],
	        'billing_amount'=>(!isset($input['billing_amount']) || $input['billing_amount'] == '')? null : $input['billing_amount'],
        );
        return $this->db->insert('labor_profile',$data);
  	}

  	public function update_labor_details($id){
  		$input = $this->input->post();
	 	$updatedata = array(
	        'labor_name' => (!isset($input['labor_name']) || $input['labor_name'] == '')? null : $input['labor_name'],
	        'father_husband_name' => (!isset($input['father_husband_name']) || $input['father_husband_name'] == '')? null : $input['father_husband_name'],
	        'mobile_number' =>(!isset($input['mobile_number']) || $input['mobile_number'] == '')? null : $input['mobile_number'],
	        'from_date' => (!isset($input['from_date']) || $input['from_date'] == '')? null : $input['from_date'],
	        'from_month' => (!isset($input['from_month']) || $input['from_month'] == '')? null : $input['from_month'],
	        'from_year' =>(!isset($input['from_year']) || $input['from_year'] == '')? null : $input['from_year'],
	        'blood_group' => (!isset($input['blood_group']) || $input['blood_group'] == '')? null : $input['blood_group'],
	        'category' => (!isset($input['category']) || $input['category'] == '')? null : $input['category'],
	        'category1' => (!isset($input['category1']) || $input['category1'] == '')? null : $input['category1'],
	        'contractor' => (!isset($input['contractor']) || $input['contractor'] == '')? null : $input['contractor'],
	        'category2' => (!isset($input['category2']) || $input['category2'] == '')? null : $input['category2'],
	        'aadhar_number'=>(!isset($input['aadhar_number']) || $input['aadhar_number'] == '')? null : $input['aadhar_number'],
	       
	        'address_line1'=>(!isset($input['address_line1']) || $input['address_line1'] == '')? null : $input['address_line1'],
	        'address_line2'=>(!isset($input['address_line2']) || $input['address_line2'] == '')? null : $input['address_line2'],
	        'location_country'=>(!isset($input['location_country']) || $input['location_country'] == '')? null : $input['location_country'],
	        'location_state'=>(!isset($input['location_state']) || $input['location_state'] == '')? null : $input['location_state'],
	        'location_district'=>(!isset($input['location_district']) || $input['location_district'] == '')? null : $input['location_district'],
	        'address_pincode'=>(!isset($input['address_pincode']) || $input['address_pincode'] == '')? null : $input['address_pincode'],
	        'labor_id_number'=>(!isset($input['labor_id_number']) || $input['labor_id_number'] == '')? null : $input['labor_id_number'],
	        'labor_amount'=>(!isset($input['labor_amount']) || $input['labor_amount'] == '')? null : $input['labor_amount'],
	        'billing_amount'=>(!isset($input['billing_amount']) || $input['billing_amount'] == '')? null : $input['billing_amount'],
        );

	 	if ($input['aadhar_front_photo'] !='') {
 			$updatedata = array_merge($updatedata, array('aadhar_front_photo'=>$input['aadhar_front_photo']));
	 	}

	 	if ($input['aadhar_back_photo'] !='') {
 			$updatedata = array_merge($updatedata, array('aadhar_back_photo'=>$input['aadhar_back_photo']));
	 	}

	 	if ($input['labor_photo'] !='') {
 			$updatedata = array_merge($updatedata, array('labor_photo'=>$input['labor_photo']));
	 	}
 				$this->db->where('id',$id);
        return $this->db->update('labor_profile',$updatedata);
  	}



  	public function get_category_list1(){
	    return $this->db->get('labor_category1')->result();
  	}

  	public function get_category_by_id1($id){
	    return $this->db->where('id',$id)->get('labor_category1')->row();
  	}

  	public function insert_category1(){
	    $data = array(
	      'name' => $this->input->post('category'), 
	      'contractor' => $this->input->post('contractor'), 
	      'mobile_number' => $this->input->post('mobile_number'), 
	      'address' => $this->input->post('address'), 
	      'account_details' => $this->input->post('account_details'), 
	    );
	    return $this->db->insert('labor_category1',$data);
  	}

  	public function update_categorybyId1($id){
	    $data = array(
	      'name' => $this->input->post('category'),
	      'contractor' => $this->input->post('contractor'),
	      'mobile_number' => $this->input->post('mobile_number'), 
	      'address' => $this->input->post('address'), 
	      'account_details' => $this->input->post('account_details'), 
	    );
	    $this->db->where('id',$id);
	    return $this->db->update('labor_category1',$data);  
  	}

  	public function delete_categorybyId1($id){
	    return $this->db->where('id',$id)->delete('labor_category1');
  	}


	public function get_category_list2(){
	    return $this->db->get('labor_category2')->result();
  	}

  	public function get_category_by_id2($id){
	    return $this->db->where('id',$id)->get('labor_category2')->row();
  	}

  	public function insert_category2(){
	    $data = array(
	      'name' => $this->input->post('category'), 
	      'mobile_number' => $this->input->post('mobile_number'), 
	    );
	    return $this->db->insert('labor_category2',$data);
  	}

  	public function update_categorybyId2($id){
	    $data = array(
	      'name' => $this->input->post('category'), 
	      'mobile_number' => $this->input->post('mobile_number'), 
	    );
	    $this->db->where('id',$id);
	    return $this->db->update('labor_category2',$data);  
  	}

  	public function delete_categorybyId2($id){
	    return $this->db->where('id',$id)->delete('labor_category2');
  	}

  	public function get_labor_login_details(){
  		return $this->db->get('labor_account')->result();
  	}

  	public function get_labor_login_detailsbyId($id){
  		return $this->db->where('id',$id)->get('labor_account')->row();
  	}
  	public function labor_login_insert(){
  		$data = array(
	      'labor_name' => $this->input->post('labor_name'), 
	      'labor_mobile_number' => $this->input->post('labor_mobile_number'), 
	      'labor_designation' => $this->input->post('labor_designation'), 
	    );
	    return $this->db->insert('labor_account',$data);  
  	}

  	public function labor_permission_insert($id){
  		$input = $this->input->post();
  		$data = array(
	      'account_details' => json_encode($input['labor_permission'])
	    );
	    $this->db->where('id',$id);
	    return $this->db->update('labor_account',$data);  
  	}


  	public function get_contractor_list1(){
	    return $this->db->get('labor_contractor')->result();
  	}

  	public function get_contractor_by_id1($id){
	    return $this->db->where('id',$id)->get('labor_contractor')->row();
  	}

  	public function insert_contractor(){
	    $data = array(
	      'name' => $this->input->post('category'), 
	      'mobile_number' => $this->input->post('mobile_number'), 
	      'address' => $this->input->post('address'), 
	      'account_details' => $this->input->post('account_details'), 
	    );
	    return $this->db->insert('labor_contractor',$data);
  	}

  	public function update_contractorbyId1($id){
	    $data = array(
	      'name' => $this->input->post('category'), 
	      'mobile_number' => $this->input->post('mobile_number'), 
	      'address' => $this->input->post('address'), 
	      'account_details' => $this->input->post('account_details'), 
	    );
	    $this->db->where('id',$id);
	    return $this->db->update('labor_contractor',$data);  
  	}

  	public function delete_contractorbyId1($id){
	    return $this->db->where('id',$id)->delete('labor_contractor');
  	}
}
