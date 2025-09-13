<?php
/**
 * Name:    Payment Gateway Library For Use In Application Form Only
 * Author:  Manjukiran
 * Company: NextElement
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment_application {
    protected $CI;
    public function __construct(){
        $this->CI =& get_instance(); 
    }

   
    public function init_payment_to_myneedy($insert_id, $amount){
        $product_price = (int) $amount;
        $gst = (int) $product_price*18/100;
        $services = (int) ($product_price + $gst)*2/100;
        $total_pay =(int) $product_price + $gst + $services;

        
       // $PAYU_BASE_URL = "https://sandboxsecure.payu.in";   // For Sandbox Mode
        //$PAYU_BASE_URL = "https://secure.payu.in";      // For Production Mode
        $order_id = $this->_getGUID();
        $payload = [
            'product_price'=>$product_price,
            'amount'=>ceil($total_pay),
            'firstname'=>'user',
            'email'=>'user@email.com',
            'phone'=>'9900000000',
            'productinfo'=>'Mask',
            'surl'=> base_url('Payment_application/success/'.$insert_id),
            'furl'=> base_url('Payment_application/failure/'.$insert_id),
            'udf1'=>'',
            'udf2'=>'',
            'udf4'=>'',
            'udf5'=>'',
        ];        
        $data['payload'] = $payload;
        $data['main_content']    = 'online_payment/index';        
        $this->CI->load->view('admin/user_inc/template', $data);
    }


    private function _getGUID() {
       return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    }


    public function success($last_id){
        echo "<pre>"; print_r($_POST); die();
        $status = $_POST['status'];
        $this->user_model->update_online_success($last_id, $status);
        redirect('user_controller/create_referel_id/'.$last_id);
    }

    public function failure($last_id){
        $status = $this->input->post('status');
        $this->user_model->update_online_success($last_id, $status);
        $this->session->set_flashdata('flashError', 'Online Transcation faild.. Please try once again..');
        redirect('user_controller/create_referel_id/'.$last_id);
    }


}
?>
