<?php

//session_start(); //we need to start session in order to access it through CI
defined('BASEPATH') OR exit('No direct script access allowed');
Class Order extends CI_Controller {

public function __construct() {
parent::__construct();

// Load form helper library
$this->load->helper('form');

// Load form validation library
$this->load->library('form_validation');

// Load session library
$this->load->library('session');

// Load database
$this->load->model('Search_model');

$this->load->helper('url');
//$this->load->helper('Paypal_pro');
}

// Show show_search result page
public function payment($msg = array()) {  //print_r($this->input->post()); //die();
    
 $qty     = isset($this->session->userdata['qty']) ? $this->session->userdata['qty'] : 1;
   
   $amt     = $qty * $this->input->post('amt');
   $product = $this->input->post('product');
   if(($this->input->post('capacity')))
   {
    $capacity= $this->input->post('capacity'); 
    $this->session->set_userdata('capacity', $capacity);
   }
   if(($this->input->post('p_id')))
   {
    $p_id    = $this->input->post('p_id'); 
    $this->session->set_userdata('p_id', $p_id);
   }
   

 
 
 $this->session->set_userdata('qty', $qty);
   
 $productid        = $this->generateRandomString(10);
 $data['amt']      = $amt;
 $data['product']  = $product;
 $data['productid']= $productid;
 $data['qty']      = $qty;
 
 if (strpos($product, 'Flight') !== false) {
   $this->session->set_userdata('deduct_cap', 1);
 }elseif(strpos($product, 'Hotel') !== false){
   $this->session->set_userdata('deduct_cap', 2); 
 }

 //$this->load->helper('url');
  if(($this->input->post()) && ($this->input->post('stripeToken')))
{   
    $apiKey       = "sk_test_BQokikJOvBiI2HlWgH4olfQ2";
    $curl_product = $this->session->userdata('product');
    $curl_amt     = round($this->session->userdata('amt')) *100;//Stripe uses lower currency denomination (eg cent) and accepts only intergers
    //echo $curl_amt."<br/>";
    //print_r($_POST);die();
;   $token = $this->input->post('stripeToken');
    //echo $token;
   
   $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://api.stripe.com/v1/charges");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $apiKey) );
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "amount=".$curl_amt."&currency=gbp&description=".$curl_product."&source=".$token."");
    
    // in real life you should use something like:
    // curl_setopt($ch, CURLOPT_POSTFIELDS, 
    //          http_build_query(array('postvar1' => 'value1')));
    
    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $server_output = curl_exec ($ch);
    
    curl_close ($ch);
    $server_output = json_decode($server_output, true);
    //var_dump($server_output);
    //echo "<br/><br/>";
    //print_r($server_output); 
    //echo $server_output['status'];
   // die();
    if(!empty($server_output) && ($server_output['status'] == 'succeeded'))
    {
        $data['message_success'] = 'Payment was Successful';
        //Update capacity
        if(isset($this->session->userdata['deduct_cap']))
        {
            $deduct_cap = $this->session->userdata['deduct_cap'];
            $capacity   = $this->session->userdata['capacity'];
            $p_id       = $this->session->userdata['p_id'];
            $qty        = $this->session->userdata['qty'];
            
            $new_cap    = $capacity - $qty;
            
            $data['p_id']         = $p_id;
            $data['new_capacity'] = $new_cap;
            
            
            //print_r($data); die();
            if($deduct_cap == 1)
            {
                //deduct flight capacity
                $up_res = $this->Search_model->update_flight_capacity($data);
            }
            if($deduct_cap == 2)
            {
                //deduct hotel capacity
                $up_res = $this->Search_model->update_hotel_capacity($data);
            }
        }
        $this->load->view('payment',$data);
    }else
    {
        $data['error_message'] = "Payment Failed. Please try another card or payment method";
        $this->load->view('payment',$data);
    }
}
 
 
 $this->load->view('payment',$data);
}

// Pay with paypal
public function pay_with_paypal($msg = array()) {
    
 $amt       = $this->input->post('amt');
 $product   = $this->input->post('product');
 $productid = $this->input->post('productid');
 $data['amt']      = $amt;
 $data['product']  = $product;
 $data['productid']= $productid;
 //$this->load->helper('url');
 $this->load->view('pay_with_paypal',$data);
}

// Pay with cards
public function pay_with_cards($msg = array()) {
  
// print_r($_POST());   
// print_r($this->input->post());
 $amt       = $this->input->post('amt');
 $product   = $this->input->post('product');
 $productid = $this->input->post('productid');
 $data['amt']    = $amt;
 $data['product']= $product;
 $data['productid']= $productid;
 //$this->load->helper('url');
 
 if(($this->input->post()) && ($this->input->post('stripeToken')))
{   
    $apiKey       = "sk_test_BQokikJOvBiI2HlWgH4olfQ2";
    $curl_product = $this->session->userdata('product');
    $curl_amt     = round($this->session->userdata('amt')) *100;//Stripe uses lower currency denomination (eg cent) and accepts only intergers
    //echo $curl_amt."<br/>";
    //print_r($_POST);die();
;   $token = $this->input->post('stripeToken');
    //echo $token;
   
   $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"https://api.stripe.com/v1/charges");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $apiKey) );
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "amount=".$curl_amt."&currency=gbp&description=".$curl_product."&source=".$token."");
    
    // in real life you should use something like:
    // curl_setopt($ch, CURLOPT_POSTFIELDS, 
    //          http_build_query(array('postvar1' => 'value1')));
    
    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $server_output = curl_exec ($ch);
    
    curl_close ($ch);
    $server_output = json_decode($server_output, true);
    //var_dump($server_output);
    //echo "<br/><br/>";
    //print_r($server_output); 
    //echo $server_output['status'];
   // die();
    if(!empty($server_output) && ($server_output['status'] == 'succeeded'))
    {
        $data['message_success'] = 'Payment was Successful';
        $this->load->view('payment',$data);
    }else
    {
        $data['error_message'] = "Payment Failed. Please try another card or payment method";
        $this->load->view('payment',$data);
    }
}
 
 $this->load->view('pay_with_cards',$data);
}




function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomNumber($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomNumber = '';
    for ($i = 0; $i < $length; $i++) {
        $randomNumber .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomNumber;
}

}

?>