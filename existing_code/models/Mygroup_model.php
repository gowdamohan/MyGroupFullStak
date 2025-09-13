<?php

class Mygroup_model extends CI_Model{
  
  	public function __construct(){
    	parent::__construct();
  	}

  	public function get_data_content($table, $groupName){
  		
  		if ($table == 'create') {
		 	return $this->db->select('cd.*')
			->from('group_create gc')
			->where('gc.db_name',$groupName)
			->join('create_details cd','gc.id=cd.create_id')
   			->get()->row();
  		}

  		if ($table == 'aderttise') {
		 	 return $this->db->select('ad.*')
		    ->from('group_create gc')
		    ->where('gc.db_name',$groupName)
		    ->join('aderttise ad','gc.id=ad.create_id')
		    ->get()->row();
  		}

  		if ($table == 'continent') {
	 	 	$this->db->select('*');
	        $this->db->from('continent_tbl');
	        return $this->db->get()->result();
  		}

  		if ($table == 'country') {

 	 	 	return $this->db->select('cn.continent, cn.id as continent_id, co.country, co.id, co.order, co.status, co.code, co.currency, co.country_flag, co.phone_code, co.nationality')
	        ->from('country_tbl co')
	        ->join('continent_tbl cn','co.continent_id=cn.id')
	        ->get()->result();
  		}

  		if ($table == 'state') {

	 	 	return $this->db->select('cn.continent, cn.id as continent_id,co.id as country_id, co.country, st.state, st.id, st.order, st.status, st.code')
	        ->from('state_tbl st')
	        ->join('country_tbl co','st.country_id=co.id')
	        ->join('continent_tbl cn','co.continent_id=cn.id')
	        ->get()->result();
  		}

  		if ($table == 'district') {

 	 		return $this->db->select('cn.continent, cn.id as continent_id, co.id as country_id,  st.id as state_id, co.country, st.state, dt.district, dt.id, dt.order, dt.status, dt.code')
		    ->from('district_tbl dt')
		    ->join('state_tbl st','dt.state_id=st.id')
		    ->join('country_tbl co','st.country_id=co.id')
		    ->join('continent_tbl cn','co.continent_id=cn.id')
		    ->get()->result();
  		}

  		if ($table == 'language') {
			return $this->db->get('language')->result();
  		}

  		if ($table == 'education') {
		 	return $this->db->get('education')->result();
  		}

  		if ($table == 'profession') {
		 	return $this->db->get('profession')->result();
  		}
  	}

  	public function get_data_top_icon(){
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
	    return array('myapps' => $myAps, 'myCompany' => $myCompany);
  	}

  	public function check_user_name_with_mygroup($number){
  		$this->db->or_where('username',$number);
  		$this->db->or_where('alter_number',$number);
  		$query = $this->db->get('users');
  		if ($query->num_rows() > 0) {
  			return $this->db->select('u.username,u.password, u.email, u.first_name, u.last_name, u.company, u.company, u.phone, u.profile_img, u.display_name, u.alter_number, urf.*')
  			->from('users u ')
  			->where('u.id',$query->row()->id)
  			->join('user_registration_form urf','u.id=urf.user_id')
  			->get()->row();
  		}else{
  			return 0;
  		}
  	}

  	public function insert_user_registration_mygroup($input){
  		$this->db->or_where('username',$input['mobile_number']);
  		$this->db->or_where('alter_number',$input['mobile_number_alter']);
  		$query = $this->db->get('users');
  		if ($query->num_rows() > 0) {
  			return 0;
  		}else{
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
		    	$this->_insert_user_registration_aps($result, $input);
		    }
		    return 1;
  		}

    	
  	}

  	public function _insert_user_registration_aps($user_id ,$input){

	    $dob = $input['from_year'].'-'.$input['from_month'].'-'.$input['from_date'];
	    $data = array(
	      'country_flag' => $input['country_flag'], 
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
	      'user_id' => $user_id,
	      'dob_date' =>$input['from_date'],
	      'dob_month' => $input['from_month'],
	      'dob_year' => $input['from_year'],
	      'country_flag_alter' => $input['country_flag_alter'],
	      'country_code_alter' => $input['country_code_alter'],
	      'mobile_number_alter' => $input['mobile_number_alter'],
	      'marital' => $input['marital'],
	    );	    
	    return $this->db->insert('user_registration_form',$data);
  	}
}
?>