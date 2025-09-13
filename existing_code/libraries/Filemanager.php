<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 Usage
 Add the following lines in your controller    
    
 $this->load->library('filemanager');
 $this->filemanager->uploadFile($_FILES['userfile']['tmp_name'],$_FILES['userfile']['name']);
*/

class Filemanager {

    protected $bucket;
    protected $CI;
               
	public function __construct()
	{
        $this->CI =& get_instance();
        $this->CI->load->library('s3');
        $this->CI->config->load('s3');
        $this->bucket = $this->CI->config->item('s3_bucket');
	}

    /* Upload File to S3 Bucket
    *
    * @params FILE $file contents to be uploaded
    * @params String $name Uploaded file name
    * @params String $subdirectory Example 'student','student/profile'
    *
    * Returns Array 
    */

    public function uploadFile($file, $name, $subdirectory = '', $actual_name = '')
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        
        if($actual_name == '') {
            $actual_name = uniqid().'-'.time().".".$ext;
            if($subdirectory != ''){
                $actual_name = $subdirectory.'/'.$actual_name;
            } else {
                $actual_name = $actual_name;
            }
        }

        if (S3::putObject(S3::inputFile($file), $this->bucket, $actual_name, S3::ACL_PUBLIC_READ)) {
            return ['status' => 'success', 'file_name' => $actual_name];
        } else {
            return ['status' => 'error', 'file_name' => ''];
        }
    }   

    public function uploadFileToS3($file, $folder='') {
        if($file['tmp_name'] == '' || $file['name'] == '') {
          return ['status' => 'empty', 'file_name' => ''];
        }        
        return $this->uploadFile($file['tmp_name'],$file['name'], $folder);
    }

    public function getFilePath($path) {
        $pa = $this->CI->config->item('s3_base_url').'/'.$path;
        // trigger_error($pa);
        return $pa;
        // return $this->CI->config->item('s3_base_url').'/'.$this->bucket.'/'.$path;
    }
}

?>