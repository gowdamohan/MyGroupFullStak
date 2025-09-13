<?php
defined('BASEPATH') OR exit('No direct script access allowed');
        
class Country_model extends CI_Model {


        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
        }


        public function get_continent()
        {
                $this->db->select();
                $this->db->from('continent_tbl');
                return $this->db->get()->result();
        }


       public function get_countrywise($country)
      {
          $this->db->select('*');
          $this->db->from('continent_tbl');
          $this->db->where('continent',$country);
          $this->db->join('country_tbl', 'country_tbl.continent_id=continent_tbl.id');
         return $this->db->get()->result();

        
      }

        public function get_state_by_districtby_id($state_id){
        $this->db->select('*');
        $this->db->from('district_tbl');
        $this->db->where('state_id',$state_id);
        return $this->db->get()->result();
      }

      public function get_statewise($state)
      {
          $this->db->select('*');
          $this->db->from('continent_tbl');
          $this->db->join('country_tbl', 'country_tbl.continent_id=continent_tbl.id');
          $this->db->join('state_tbl', 'state_tbl.country_id=country_tbl.id');
          $this->db->where('country',$state);
        return $this->db->get()->result();   
      }

      

      public function get_citywise($district)
      {
          $this->db->select('*');
          $this->db->from('continent_tbl');
          $this->db->where('state', $district);
          $this->db->join('country_tbl', 'country_tbl.continent_id=continent_tbl.id');
          $this->db->join('state_tbl', 'state_tbl.country_id=country_tbl.id');
          $this->db->join('district_tbl', 'district_tbl.state_id=state_tbl.id');
          $this->db->order_by('district_tbl.order');
          $this->db->order_by('district_tbl.district');
        return $this->db->get()->result();

        
      }

      public function insert_continent(){
        $data = array(
          'continent'=>$this->input->post('continent'),
          'code'=>$this->input->post('code'),
          'status'=>1
        );
        return  $this->db->insert('continent_tbl',$data);
      }

      public function get_continet(){
        return $this->db->get('continent_tbl')->result();
      }
      public function edit_continent_by_id($id){
        $this->db->where('id',$id);
        return $this->db->get('continent_tbl')->row();
      }

      public function update_continent($id){
        $data = array(
          'continent'=>$this->input->post('continent'),
          'code'=>$this->input->post('code')
        );
        $this->db->where('id',$id);
        return  $this->db->update('continent_tbl',$data);
      }

      public function update_order($cateId, $order_wise, $table){
        $data = array(
          'order'=>$order_wise
        );
        $this->db->where('id',$cateId);
        return $this->db->update($table,$data);
      }

      public function update_status($cId, $mode, $table){
        $data = array(
          'status'=>$mode
        );
        $this->db->where('id',$cId);
        return $this->db->update($table,$data);
      }

      public function delete_country_list($id, $table){
         $this->db->where('id',$id);
        return $this->db->delete($table);
      }


      // Country
      public function get_country_list(){
        return $this->db->select('cn.continent, co.country, co.id, co.order, co.status, co.code, co.currency, co.country_flag, co.phone_code, co.nationality')
        ->from('country_tbl co')
        ->join('continent_tbl cn','co.continent_id=cn.id')
        ->get()->result();
      }


      public function insert_country($file_data){
        if(!empty($file_data)){
          $cFlag="uploads/country_flag/".$file_data['orig_name'];
        } else{
          $cFlag="";
        }
        $data = array(
          'continent_id'=>$this->input->post('continent'),
          'country'=>$this->input->post('country'),
          'code'=>$this->input->post('code'),
          'currency'=>$this->input->post('currency'),
          'country_flag'=>$cFlag,
          'status'=>1,
          'phone_code'=>$this->input->post('phone_code'),
          'nationality'=>$this->input->post('nationality'),
        );
        return  $this->db->insert('country_tbl',$data);
      }

      public function update_country($file_data, $id){
        if(!empty($file_data)){
          $cFlag="uploads/country_flag/".$file_data['orig_name'];
        } else{
          $cFlag=$this->input->post('country_flag_1');;
        }
        $data = array(
          'continent_id'=>$this->input->post('continent'),
          'country'=>$this->input->post('country'),
          'code'=>$this->input->post('code'),
          'currency'=>$this->input->post('currency'),
          'country_flag'=>$cFlag,
          'phone_code'=>$this->input->post('phone_code'),
          'nationality'=>$this->input->post('nationality'),
        );
        $this->db->where('id',$id);
        return  $this->db->update('country_tbl',$data);
      }

      public function edit_country_by_id($id){
        return $this->db->select('*')
        ->from('country_tbl ')
        ->where('id',$id)
        ->get()->row();
      }


    public function get_country_by_conId($conId){
      $this->db->select('*');
      $this->db->from('country_tbl');
      $this->db->where('continent_id',$conId);
      return $this->db->get()->result();
    }

    public function get_all_state(){
       return $this->db->select('cn.continent,co.code as country_code, co.country, st.state, st.id, st.order, st.status, st.code, co.id as country_id')
        ->from('state_tbl st')
        ->join('country_tbl co','st.country_id=co.id')
        ->join('continent_tbl cn','co.continent_id=cn.id')
        ->get()->result();
    }

    public function edit_state_by_id($id){
       return $this->db->select('cn.id as conId, cn.continent, co.id as countryId, co.country, st.state, st.id, st.order, st.status, st.code')
        ->from('state_tbl st')
        ->where('st.id',$id)
        ->join('country_tbl co','st.country_id=co.id')
        ->join('continent_tbl cn','co.continent_id=cn.id')
        ->get()->row();
    }

    public function insert_state(){
       $data = array(
          'country_id'=>$this->input->post('country'),
          'state'=>$this->input->post('state'),
          'code'=>$this->input->post('code'),
          'status'=>1
        );
        return  $this->db->insert('state_tbl',$data);
    }

    public function update_state_byId($id){
      $data = array(
        'country_id'=>$this->input->post('country'),
        'state'=>$this->input->post('state'),
        'code'=>$this->input->post('code')
      );
      $this->db->where('id',$id);
      return  $this->db->update('state_tbl',$data);
    }

    public function get_state_by_countryId($countryId){
      $this->db->select('*');
      $this->db->from('state_tbl');
      $this->db->where('country_id',$countryId);
      return $this->db->get()->result();
    }

    public function get_district_details(){
      return $this->db->select('cn.continent, co.country, st.state, dt.district, dt.id, dt.order, dt.status, dt.code')
      ->from('district_tbl dt')
      ->join('state_tbl st','dt.state_id=st.id')
      ->join('country_tbl co','st.country_id=co.id')
      ->join('continent_tbl cn','co.continent_id=cn.id')
      ->get()->result();
    }

    public function insert_district(){
       $data = array(
        'state_id'=>$this->input->post('state'),
        'district'=>$this->input->post('district'),
        'code'=>$this->input->post('code'),
        'status'=>1
      );
      return  $this->db->insert('district_tbl',$data);
    }

    public function edit_district_by_id($id){
       return $this->db->select('cn.id as conId, cn.continent, co.id as countryId, co.country, st.state, st.id as stateId, dt.id, dt.district, dt.order, dt.status, dt.code')
        ->from('district_tbl dt')
        ->where('dt.id',$id)
        ->join('state_tbl st','dt.state_id=st.id')
        ->join('country_tbl co','st.country_id=co.id')
        ->join('continent_tbl cn','co.continent_id=cn.id')
        ->get()->row();
    }

    public function update_district_byId($id){
      $data = array(
        'state_id'=>$this->input->post('state'),
        'district'=>$this->input->post('district'),
        'code'=>$this->input->post('code')
      );
      $this->db->where('id',$id);
      return  $this->db->update('district_tbl',$data);
    }
     
}
