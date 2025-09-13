<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once  APPPATH . '/third_party/aws/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class S3_controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		// $this->load->library('session');
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}
        $this->config->load('s3');
        $this->secret_key = $this->config->item('secret_key');
      	$this->access_key = $this->config->item('access_key');
      	$this->bucket = $this->config->item('s3_bucket');
      	$this->base_url = $this->config->item('s3_base_url');
      	$this->region = 'us-west-1';
      	$this->main_folder = 'MyGroup';

      	$this->s3 = new S3Client([
      				'endpoint' => 'https://s3.'.$this->region.'.wasabisys.com',
                    'version' => 'latest',
                    'region'  => $this->region,
                    'signature' => 'v4',
                    'credentials' => ['key' => $this->access_key, 'secret' => $this->secret_key]
                  ]);
	}

	private function _getExtensionFromFile($filename, $file_type) {
		$strs = explode(".", $filename);
		if(count($strs) > 1) {
			return $strs[count($strs) - 1];
		}
		$mimes = MIME_TYPE_EXTENSION;
		if(array_key_exists($file_type, $mimes)) {
			return $mimes[$file_type];
		}
		return '';
	}

	public function getSignedUrl() {
		$file_type = $_POST['file_type'];
		$ext = $this->_getExtensionFromFile($_POST['filename'], $file_type);
		$folder = isset($_POST['folder'])?$_POST['folder']:'';
		$actual_name = uniqid().'-'.time().".".$ext;
		if($folder != ''){
            $actual_name = $this->main_folder.'/'.$folder.'/'.$actual_name;
        } else {
            $actual_name = $this->main_folder.'/'.$actual_name;
        }
		//Creating a presigned URL
		$cmd = $this->s3->getCommand('PutObject', [
		    'Bucket' => $this->bucket,
		    'Key' => $actual_name,
		    'Content-Type' => $file_type,
		    // 'Content-Type' => 'multipart/form-data',
		    'ACL' => 'public-read'
		]);

		$response = $this->s3->createPresignedRequest($cmd, '+20 minutes');

		$data = [
			'path' => $actual_name,
			'signedUrl' => (string)$response->getUri()
		];

		echo json_encode($data);
		// echo "<pre>"; print_r((string)$response->getUri()); die();
	}

}