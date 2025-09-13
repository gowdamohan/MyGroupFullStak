<?php

class Myads_model extends CI_Model{
  
  	public function __construct(){
    	parent::__construct();
  	}

  	public function get_myads_about_all(){
	    return $this->db->get('myads_about')->result();
  	}

 	public function edit_about_us_by_id($id){
	    $this->db->where('id',$id);
	    return $this->db->get('myads_about')->row();
  	}

 	public function get_gallery_info($gallery_id, $group_id){
      $query = $this->db->where('group_id',$group_id)->get_where('myads_gallery_list', array('gallery_id' => $gallery_id))->row();
      return $query;
    }

  	public function get_images_info($gallery_id, $group_id){
      $this->db->order_by('myads_gallery_images_master.image_id','DESC');
      $query = $this->db->where('group_id',$group_id)->get_where('myads_gallery_images_master', array('gallery_id' => $gallery_id));
      return $query->result_array();;
    }

 	public function get_all_galleries($group_id){

      $masterCount =  $this->db->select("gallery_id, count(gallery_id) as img_count, image_name")
      ->from('myads_gallery_images_master')
      ->where('group_id',$group_id)
      ->group_by('gallery_id')
      ->get()->result();
      $this->db->select('gl.*')
            ->from('myads_gallery_list gl');
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
      $status = $this->db->insert('myads_gallery_list',$data);

      if($status) {
        $gallery['id'] = $this->db->insert_id();
        $gallery['name'] = $data['gallery_name'];
        return $gallery;
      } else {
          return 0;
      }
    }

    public function delete_album_db($gId){
      $this->db->where('gallery_id',$gId);
      $this->db->delete('myads_gallery_list');

      $this->db->where('gallery_id', $gId);
      return $this->db->delete('myads_gallery_images_master');
      
    }


    public function delete_image_db($image_id){
      $this->db->where('image_id', $image_id);
      $res1 = $this->db->delete('myads_gallery_images_master');
      return $res1;
    }

  	public function save_gallery_images($images){
        return $this->db->insert_batch('myads_gallery_images_master',$images);
    }

 	public function get_contact_details(){
	  return $this->db->get('myads_contact')->row();
	}

 	public function update_contact_byId($id){
      $data = array( 
        'address' => $this->input->post('address'),
        'email' =>$this->input->post('emaiil_id'),
        'contact_number' => $this->input->post('contact_number'),
        'group_id'=> $this->ion_auth->user()->row()->group_id
      );
      $this->db->where('id',$id);
      return $this->db->update('myads_contact',$data);
    }

 	public function insert_update_contact(){
      $data = array( 
        'address' => $this->input->post('address'),
        'email' =>$this->input->post('emaiil_id'),
        'contact_number' => $this->input->post('contact_number'),
        'group_id'=> $this->ion_auth->user()->row()->group_id
      );
      return $this->db->insert('myads_contact',$data);
  }

  public function insert_category(){
    $data = array(
      'category'=> $this->input->post('category'),
    );
    return $this->db->insert('myads_category',$data);
  }

  public function insert_sub_category(){
    $data = array(
      'category_id'=> $this->input->post('category'),
      'sub_category'=> $this->input->post('sub_cat'),
    );
    return $this->db->insert('myads_sub_category',$data);
  }

  public function update_category($id){
    $data = array(
      'category'=> $this->input->post('category'),
    );
    $this->db->where('id',$id);
    return $this->db->update('myads_category',$data);
  }

  public function getCategoriesList(){
     return $this->db->get('myads_category')->result(); 
  }

  public function getCategoriesListbyid($id){
      $this->db->where('id',$id);
    return $this->db->get('myads_category')->row(); 
  }

  public function get_sub_categories(){
    return $this->db->select('msc.*, mc.category')
    ->from('myads_category mc')
    ->join('myads_sub_category msc','mc.id=msc.category_id')
    ->get()->result();
  }

  public function get_sub_categories_data($sub_id){
    return $this->db->select('msc.*, mc.category')
    ->from('myads_category mc')
    ->join('myads_sub_category msc','mc.id=msc.category_id')
    ->where('msc.id',$sub_id)
    ->get()->result();
  }

  public function update_sub_category($productdata, $table, $sub_id){
    $this->db->where('id',$sub_id);
    return $this->db->update($table,$productdata);
  }
  public function delete_sub_categorybyid($id){
    $this->db->where('id',$id);
    return $this->db->delete('myads_sub_category');
  }
}