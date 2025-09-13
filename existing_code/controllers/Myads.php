<?php

class Myads extends CI_Controller {
               
	function __construct(){
 		parent::__construct();
	 	$this->load->model('needy_model');
	 	$this->load->model('home_model'); 
        $this->load->model('admin_model'); 
    	$this->load->model('Mygroup_model');
	  	$this->load->model('country_model');
	  	$this->load->model('myads_model');
	  	$this->load->library('filemanager');
	}

	public function index($groupname){
		$data['groupname'] = $groupname;
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
	 	$data['country'] = $this->admin_model->get_country_flag();
	 	$data['category'] = $this->home_model->get_all_unions_category();
	 	if ($this->mobile_detect->isMobile()) {
        	$data['main_content']    = 'home/my_ads';
	 	}else{
	 		$data['main_content']    = 'home/my_ads';
	 	}
        $this->load->view('front/template_group', $data);
	}

	public function admin_dashboard(){
	 	$data['main_content']    = 'admin/pages/myads/index';
        $this->load->view('admin/inc/template',$data);
	}

	public function about_us(){
        $data['about'] = $this->myads_model->get_myads_about_all();
		$data['main_content']    = 'admin/pages/myads/about_us';
        $this->load->view('admin/inc/template',$data);
	}

	public function add_about(){
		$data['main_content']    = 'admin/pages/myads/add_about';
        $this->load->view('admin/inc/template',$data);
	}

	public function edit_about_us($id){
		$data['edit_about'] = $this->myads_model->edit_about_us_by_id($id);
		$data['main_content']    = 'admin/pages/myads/edit_about';
        $this->load->view('admin/inc/template',$data);
	}

	public function deleted_about_us($id){
        $this->db->where('id',$id);
        $result =  $this->db->delete('myads_about');
        if ($result) {
            redirect('myads/about_us');
        }
    }

    public function upload_about(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['about_title'];
        $summernote = $input['summernote'];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $save_news[] = array(
                    'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'group_id'=> $this->ion_auth->user()->row()->group_id
                );
            }
        }else{
            $save_news[] = array(
                'image' => $img['file_name'],
                'title' => $title,
                'content' => $summernote,
                'group_id'=> $this->ion_auth->user()->row()->group_id
            );
        }
        if(!empty($save_news)) {
            $this->admin_model->save_news($save_news,'myads_about');
        }
        echo 1;
    }

  	public function s3FileUpload($file){
        if ($file['tmp_name'] == '' || $file['name'] == '') {
            return ['status' => 'empty', 'file_name' => ''];
        }
        $uploadResult = $this->filemanager->uploadFile($file['tmp_name'], $file['name'], 'Logo');
        //print_r($uploadResult); die();
        return $uploadResult;
    }


 	public function upload_about_by_id(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        $title = $input['about_title'];
        $summernote = $input['summernote'];
        $exit_fie_url = $input['exit_fie_url'];
        $about_id = $input['about_id'];
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $update_about[] = array(
                    'id' => $about_id,
                     'image' => $img['file_name'],
                    'title' => $title,
                    'content' => $summernote,
                    'group_id'=> $this->ion_auth->user()->row()->group_id
                );
            }
        }else{
            $update_about[] = array(
                'id' => $about_id,
                'image' => $exit_fie_url,
                'title' => $title,
                'content' => $summernote,
                'group_id'=> $this->ion_auth->user()->row()->group_id
            );
        }
        if(!empty($update_about)) {
            $this->admin_model->update_news($update_about,'myads_about');
        }
        echo 1;
    }

 	public function create_gallery(){
        $data['main_content']    = 'admin/pages/myads/galleries/create_gallery';
        $this->load->view('admin/inc/template', $data); 
    }


 	public function delete_gallery_by_id(){
        $gId = $_POST['gId'];
        $result = $this->myads_model->delete_album_db($gId);
        echo $result;
    }

    public function gallery(){
        $gallery_info['image_count'] = 0;
        $group_id = $this->ion_auth->user()->row()->group_id;
        $gallery_info = $this->myads_model->get_all_galleries($group_id);
        $data['gallery_info'] = $gallery_info;
        $data['main_content']    = 'admin/pages/myads/galleries/index';
        $this->load->view('admin/inc/template', $data); 
    }

 	public function gallery_created(){
        if (!(isset($_POST['gallery_name']))) {
            redirect('myads/gallery');
        }
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['gallery'] = $this->myads_model->addGallery($group_id);
        redirect('myads/view_gallery/' . $data['gallery']['id']);
    }

 	public function view_gallery($gallery_id){
        $group_id = $this->ion_auth->user()->row()->group_id;
        $data['gallery_info'] = $this->myads_model->get_gallery_info($gallery_id, $group_id);
        $data['image_info'] = $this->myads_model->get_images_info($gallery_id, $group_id);
        $data['main_content'] = 'admin/pages/myads/galleries/view_gallery';
        $this->load->view('admin/inc/template', $data); 
    }

 	public function delete_image(){
        $image_id = $_POST['image_id'];
        $result = $this->myads_model->delete_image_db($image_id);
        echo $result;
    }

 	public function upload_multiple_images(){
        $files = $_FILES['file_name'];
        $input = $this->input->post();
        $gid = $input['gal_id'];
        $img_desc = $input['desc'];
        $failed = 0;
        $group_id = $this->ion_auth->user()->row()->group_id;
        // echo '<pre>'; print_r($files); die();
        foreach($files['tmp_name'] as $i => $file_name) {
            $file = array(
                'tmp_name' => $file_name,
                'name' => 'img'.$i.'.png'
            );
            $img = $this->s3FileUpload($file, $gid);
            trigger_error(json_encode($img));
            if($img['file_name'] == null || $img['file_name'] == ''){
                $failed++;
            }else {
                $save_images[] = array(
                    'gallery_id' => $gid,
                    'image_name' => $img['file_name'],
                    'image_description' => $img_desc,
                    'group_id'=>$group_id
                );
            }
        }
        if(!empty($save_images)) {
            $this->myads_model->save_gallery_images($save_images);
        }

        echo $failed;
    }

	public function contact_us(){
		$data['contact'] = $this->myads_model->get_contact_details();
		$data['main_content']    = 'admin/pages/myads/contact_us';
        $this->load->view('admin/inc/template',$data);
	}

 	public function update_contact_by_id($id){
        $result = $this->myads_model->update_contact_byId($id);
        redirect('myads/contact_us');
    }

  	public function update_contact(){
        $result = $this->myads_model->insert_update_contact();
        redirect('myads/contact_us');
    }

    public function product_category(){
    	$data['category'] = $this->myads_model->getCategoriesList();
    	$data['main_content']    = 'admin/pages/myads/product_category';
        $this->load->view('admin/inc/template',$data);
    }

    public function update_category($id){
    	$result = $this->myads_model->update_category($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('myads/product_category');
    }

    public function submit_category(){
	 	$result = $this->myads_model->insert_category();
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('myads/product_category');
    }

    public function edit_category($id){
    	$data['category'] = $this->myads_model->getCategoriesList();
    	$data['edit_category'] = $this->myads_model->getCategoriesListbyid($id);
    	$data['main_content']    = 'admin/pages/myads/product_category';
        $this->load->view('admin/inc/template',$data);
    }

    public function sub_category(){
        $data['category'] = $this->myads_model->getCategoriesList();
        $data['sub_category'] = $this->myads_model->get_sub_categories();
        $data['main_content']    = 'admin/pages/myads/product_sub_category';
        $this->load->view('admin/inc/template',$data); 
    }

    public function submit_sub_category(){
        $result = $this->myads_model->insert_sub_category();
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Insert Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('myads/sub_category');
    }

    public function product_sub_category($sub_id){
        $data['sub_id'] = $sub_id;
    	$data['sub_category'] = $this->myads_model->get_sub_categories_data($sub_id);
    	$data['main_content']    = 'admin/pages/myads/product';
        $this->load->view('admin/inc/template',$data);
    }

    public function upload_myads_sub_category(){
        $files = '';
        if (!empty($_FILES['file_name'])) {
            $files = $_FILES['file_name'];
        }
        $input = $this->input->post();
        if (!empty($files)) {
            foreach($files['tmp_name'] as $i => $file_name) {
                $file = array(
                    'tmp_name' => $file_name,
                    'name' => 'img'.$i.'.png'
                );
                $img = $this->s3FileUpload($file);

                $save_data = array( 
                  'image' =>$img['file_name'],
                  'title' => $this->input->post('myads_title'),
                  'descriptions'=> $this->input->post('summernote'),
                );
            }
        }else{
            $save_data = array( 
              'image' =>'',
              'title' => $this->input->post('myads_title'),
              'descriptions'=> $this->input->post('summernote'),
            );
        }
        if(!empty($save_data)) {
            $this->myads_model->update_sub_category($save_data,'myads_sub_category',$this->input->post('sub_id'));
        }
        echo 1;        
    }

    public function delete_sub_category($id){
        $result = $this->myads_model->delete_sub_categorybyid($id);
        if ($result){
            $this->session->set_flashdata('flashSuccess', 'Delete Successfully.');
        }else{
            $this->session->set_flashdata('flashError', 'Something Went Wrong!'); 
        }
        redirect('myads/sub_category');
    }
	public function products(){
		$data['main_content']    = 'admin/pages/myads/products';
        $this->load->view('admin/inc/template',$data);
	}


}