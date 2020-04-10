<?php

//session_start(); //we need to start session in order to access it through CI
defined('BASEPATH') OR exit('No direct script access allowed');
Class VirtualAssistant extends CI_Controller {

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

if (!isset($this->session->userdata['chatHistory'])) {
	$this->session->userdata['chatHistory'] = array();	
}

if (!isset($this->session->userdata['openChatBoxes'])) {
	$this->session->userdata['openChatBoxes'] = array();	
}
}


public function chatHeartbeat() {
    //$_SESSION = array(); 
    $data        = array();   
     //pick visitor IP
    $ip_address  = $this->getUserIP();
    $chat_result = array();
    $data = array(
        'ip_address' => $ip_address
        );
        
	$chat_result = $this->Search_model->get_dialogue_knowledge($data);
	$items = '';
    $chat_loop_counter = 0;
    
    //echo "$sql";
	$chatBoxes = array();
    $chat_result = json_decode(json_encode($chat_result),true);
    //print_r($chat_result); die();
    
    if(!empty($chat_result))
    { 
      foreach($chat_result as $this_chat)
      {    
	
        if($this_chat['DkFrom'] == $ip_address){ //ip address matches visitor ip make appear as YOU
		      $this_chat['DkFrom'] = "You";
        }
		if (!isset($this->session->userdata['openChatBoxes'][$this_chat['DkFrom']]) && isset($this->session->userdata['chatHistory'][$this_chat['DkFrom']])) {
		    
			$items = $this->session->userdata['chatHistory'][$this_chat['DkFrom']];
		}

		$this_chat['DkMessage'] = $this->sanitize($this_chat['DkMessage']);

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$this_chat['DkFrom']}",
			"m": "{$this_chat['DkMessage']}"
	   },
EOD;

	if (!isset($this->session->userdata['chatHistory'][$this_chat['DkFrom']])) {
		$this->session->userdata['chatHistory'][$this_chat['DkFrom']] = '';
	}

	$this->session->userdata['chatHistory'][$this_chat['DkFrom']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$this_chat['DkFrom']}",
			"m": "{$this_chat['DkMessage']}"
	   },
EOD;
		
		unset($this->session->userdata['tsChatBoxes'][$this_chat['DkFrom']]);
		$this->session->userdata['openChatBoxes'][$this_chat['DkFrom']] = $this_chat['DkFrom'];
        
        $chat_loop_counter++;
	}
    
    } //end if(!empty)
	if (!empty($this->session->userdata['openChatBoxes'])) {
	foreach ($this->session->userdata['openChatBoxes'] as $chatbox => $time) {
		if (!isset($this->session->userdata['tsChatBoxes'][$chatbox])) {
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "<span style='font-size:10px; color:#ccc'><i>$chatbox - Last Sent at $time</i></span>"; //print_r($message);
			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

	if (!isset($this->session->userdata['chatHistory'][$chatbox])) {
		$this->session->userdata['chatHistory'][$chatbox] = '';
	}

	$this->session->userdata['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
			$this->session->userdata['tsChatBoxes'][$chatbox] = 1;
		}
		}
	} //end for
}
    //Update chat //
    $chat_result     = $this->Search_model->update_chats($data);

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
//header('Content-type: application/json');
?>
{
		"items": [
			<?php echo html_entity_decode($items);?>
        ]
}

<?php
			exit(0);
}
//chat box session
public function chatBoxSession($chatbox) {
	
	$items = '';
	$_SESSION = array();
    if(!isset($this->session->userdata['username']))
    {
        $this->session->set_userdata('username', "You");
    }
	if (isset($this->session->userdata['chatHistory'][$chatbox])) {
		$items = $this->session->userdata['chatHistory'][$chatbox];
	}

	return $items;
}

// Start chat session
public function startchatsession($msg = array()) {

/*    
 $amt     = $this->input->post('amt');
 $product = $this->input->post('product');
 $data['amt']      = $amt;
 $data['product']  = $product;
 $data['productid']= $this->generateRandomString(10);
*/
 
 
 $items = '';
 if (!empty($this->session->userdata['openChatBoxes'])) {
	foreach ($this->session->userdata['openChatBoxes'] as $chatbox => $void) {
		$items .= $this->chatBoxSession($chatbox);
	}
 }


 if ($items != '') {
	$items = substr($items, 0, -1);
 }
 
 //header('Content-type: application/json');
 ?>
{
		"username": "<?php echo $this->session->userdata['username'];?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}



public function sendChat() {
    
    $data    = array();
    
    //Search type arrays
    $flight_search_type     =  array("Flight", "Flights","flight", "flights", "Fly", "fly", "Travel", "travel","travelling","flying");
    $hotel_search_type      =  array("Hotel", "hotel", "Hotels", "hotels", "Room", "room", "Rooms", "rooms", "Accommodation", "accommodation", "Guest house", "guest house"); 
    $activity_search_type   =  array("Activities", "activities", "Activity", "activity", "Events", "events", "Event", "event"); 
    $non_criteria_words     =  array("want","am","to","looking","I","with","for","a","and","low-cost","in","from","date","departure","return","on","at","during","price","price range","range","between","cheap","around","need","offers","offer","looking");  //mainly connecting and personifying words  
    $continents_search_type =  array("Africa", "Antarctica", "Asia", "Europe", "North America", "Oceania", "South America","africa", "antarctica", "asia", "europe", "north america", "oceania", "south america");
    $season_search_type     =  array("Spring", "spring", "Autumn", "autumn", "Summer", "summer", "Winter","winter"); 
    $cities_search_type     =  array("Bangkok", "London", "Paris", "Dubai", "New York", "Singapore", "Kuala Lumpur","Istanbul","Tokyo","Seoul","Hongkong","Hong Kong","Barcelona","Amsterdam","Milan","Taipei","Rome","Osaka","Vienna","Shanghai","Prague","Los Angeles","Madrid","Munich","Miami","Dublin","Lagos","Jakarta","Berlin"); 
    $countries_search_type  =  array("Thailand", "England", "UK", "France", "United Arab Emirates", "UAE", "USA", "United States of America", "Singapore","Malaysia","Turkey","South Korea","Hongkong","Hong Kong","Spain","Netherlands","The Netherlands","Holland","Italy","Taiwan","Japan","Austria","China","Czech Republic","Germany","Ireland","Nigeria","Indonesia"); 
    $age_search_type        =  array("Infant","infant", "Toddler", "toddler", "Play-age", "play-age", "Primary-school", "primary-school", "Adolescence", "adolescence", "Young-adult", "young-adult", "Middle-adult", "middle-adult","Senior","senior");
    
    
    if(!isset($this->session->userdata['dialogue_flow']['current']))
    {   
        unset($this->session->userdata['dialogue_flow']);
        $this->session->userdata['dialogue_flow']['current'] = 1;
    }
    
	$from    = $this->session->userdata['username'];
	$to      = $this->input->post('to');
	$message = $this->input->post('message');
    
    //pick visitor IP
    $ip_address = $this->getUserIP();

	$this->session->userdata['openChatBoxes'][$this->input->post('to')] = date('Y-m-d H:i:s', time());
	
	$messagesan = $this->sanitize($message);

	if (!isset($this->session->userdata['chatHistory'][$this->input->post('to')])) {
		$this->session->userdata['chatHistory'][$this->input->post('to')] = '';
	}

	$this->session->userdata['chatHistory'][$this->input->post('to')] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;
    
    //Search for Bot response and push
    if($messagesan == "result")
    {
        $this->session->userdata['dialogue_flow']['current'] = 1;
    }
    if($this->session->userdata['dialogue_flow']['current'] == 1)
    {
      $data = array(
        'type' => $messagesan,
        'stage' => $this->session->userdata['dialogue_flow']['current']
        );
    }else
    { 
      $data = array(
        'trigger' => "",
        'stage' => $this->session->userdata['dialogue_flow']['current'],
        'type' => $this->session->userdata['dialogue_flow']['type']
        );
        
    }    
    
    //if its holiday, Natural Language try split the sentence or phase and match with filters
    /*
    if( ($this->session->userdata['dialogue_flow']['current'] == 2) && 
        (isset($this->session->userdata['dialogue_flow']['type'])) &&
        ($this->session->userdata['dialogue_flow']['type'] == "holiday")  )
    {}
    */
    //create a dialogue flow session
    
	$replies_result    = $this->Search_model->get_dialogue_rules($data); 
    //search more...
    
    
    if(!empty($replies_result))
    { //prepare response
        $replies_result = json_decode(json_encode($replies_result),true); //print_r($replies_result);
        
        
        //Dialogue for Natural Language -- System is expecting a Natural Language from user.
        if($replies_result[0]['DrType'] == 'holiday')
        {   
            //set flight dialogue session
            if($replies_result[0]['DrStage'] == "1")
            {   
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type'] = $replies_result[0]['DrType'];
                //increament current
                $this->session->userdata['dialogue_flow']['current'] += 1;
                //echo "CURRENT1...".$this->session->userdata['dialogue_flow']['current']; echo "<br/>";
                
                $this->session->userdata['va']['flight_data']   = array();
                $this->session->userdata['va']['hotel_data']    = array();
                $this->session->userdata['va']['activity_data'] = array();
                $this->session->userdata['va']['trials']        = 0;
                
            }
            if($replies_result[0]['DrStage'] == "2")
            {
                
                //set natural language
                $this->session->userdata['dialogue_flow']['nlp']= $messagesan;
                
                //Try to interpret the Sentence or phase 
                $flight_search  = 0;
                $hotel_search   = 0;
                $activity_search= 0;
                $search_types   = array();
                $date_types     = array();
                $cities_types   = array();
                $continent_type = "";
                $season_type    = "";
                $country_type   = "";
                $age_type       = "";
                $price_types   = array();
                $child_tokens   = array();
                $f_trip_type    = 1;
                $tokens = $this->splitIt($messagesan);
                array_unique($tokens);
                //print_r($tokens); 
                
                $token_count = 0;
                $increament_flag = 1; 
                
                //Determine and remove Non Criteria words
                 foreach($tokens as $token)
                 {  
                    if(!in_array($token,$non_criteria_words))
                    { 
                       $child_tokens[] = $token; 
                    }
         
                     $token_count++;
                    
                 }
                 //generate child tokens
                 $tokens = array();
                 $tokens = $child_tokens;

             
                 
                //Determine if there should be flight search
                $token_count = 0;
                $child_tokens= array();
                foreach($tokens as $token)
                {
                    if(!in_array(strtolower($token),array_map("strtolower",$flight_search_type)))
                    {
                        $child_tokens[] = $token;
                    }
                    if(in_array(strtolower($token),array_map("strtolower",$flight_search_type)))
                    {
                        $flight_search = 1;
                        //echo "This is a Flight Search<br/>";
                    }
                    $token_count++;
                }
                //generate child tokens
                $tokens = array();
                $tokens = $child_tokens;

             
                //Determine if there should be hote search
                $token_count = 0;
                $child_tokens= array();
                foreach($tokens as $token)
                {   
                    if(!in_array(strtolower($token),array_map("strtolower",$hotel_search_type)))
                    {
                        $child_tokens[] = $token;
                    }
                    if(in_array(strtolower($token),array_map("strtolower",$hotel_search_type)))
                    {
                        $hotel_search = 1;
                        //echo "This is a Hotel Search<br/>";
                    }
                    $token_count++;
                }
                //generate child tokens
                $tokens = array();
                $tokens = $child_tokens;
                
                //Determine if there should be activity search
                $token_count = 0;
                $child_tokens= array();
                foreach($tokens as $token)
                {    
                    if(!in_array(strtolower($token),array_map("strtolower",$activity_search_type)))
                    {
                        $child_tokens[] = $token;
                    }
                    if(in_array(strtolower($token),array_map("strtolower",$activity_search_type)))
                    {
                        $activity_search = 1;
                        //echo "This is a Activity Search<br/>";
                    }
                    $token_count++;
                }
                //generate child tokens
                $tokens = array();
                $tokens = $child_tokens;
               
               
               //echo "<br/>CITIES: <br/>";
               //$all_cities = $this->Search_model->get_all_cities();
               //$all_cities = json_decode(json_encode($all_cities));
               //print_r($all_cities); 
              
              //Determine date types to be used with flight search
              $token_count = 0;
              $child_tokens= array();
              foreach($tokens as $token)
              {   //$date_var = '28/03/2018';
                  $date   = str_replace('/', '-', $token);
                  $date_r = date('Y-m-d', strtotime($date));
                  if ( (DateTime::createFromFormat('Y-m-d', $date_r) !== FALSE) && ($date_r != "1970-01-01") ) {
                    $date_types[] = $date_r;
                    //array_splice($tokens,$token_count,1); //remove from tokens
                    //$token_count = 0; //reset
                  }else
                  {
                    $child_tokens[] = $token;
                  }
                  
                  $token_count++;
             }
             //generate child tokens
            $tokens = array();
            $tokens = $child_tokens;

             $v_departure = "";
             $v_return    = "";
             if(!empty($date_types))
             {
              //check if date is more than one and sort
              
              if(count($date_types) > 1)
              { 
                $f_trip_type = 2;
                //try to sort in ascending order
                $comp_date = $this->compareByTimeStamp($date_types[0],$date_types[1]);
                if($comp_date == -1)
                {
                    //do a swap
                    $temp_date = $date_types[0];
                    $date_types[0] = $date_types[1];
                    $date_types[1] = $temp_date;
                }
              }  
              $v_departure = $date_types[0];
              $v_return    = isset($date_types[1])? $date_types[1]: "";
              //print_r($date_types); 
             }
           
             //Determine Continents
             $token_count = 0;
             $child_tokens= array();
             foreach($tokens as $token)
             {  
                $next_continent = isset($tokens[$token_count+1]) ? $tokens[$token_count+1]:"";
                if( (!in_array(strtolower($token),array_map("strtolower",$continents_search_type))) && (!in_array(strtolower($token." ".$next_continent),array_map("strtolower",$continents_search_type))) )
                //if(!in_array(strtolower($token),array_map("strtolower",$continents_search_type)))
                {
                   $child_tokens[] = $token;
                }
                if( (in_array(strtolower($token),array_map("strtolower",$continents_search_type))) || (in_array(strtolower($token." ".$next_continent),array_map("strtolower",$continents_search_type))) )
                //if(in_array(strtolower($token),array_map("strtolower",$continents_search_type)))
                {
                    $continent_type = $token;
                }
                $token_count++;
             }
             //generate child tokens
            $tokens = array();
            $tokens = $child_tokens;
      
             if(!empty($continent_type))
             {
              //print_r($continent_type); 
             }
             
             
             //Determine Season
             $token_count = 0;
             $child_tokens= array();
             foreach($tokens as $token)
             {  
                if(!in_array(strtolower($token),array_map("strtolower",$season_search_type)))
                {
                   $child_tokens[] = $token;
                }
                if(in_array(strtolower($token),array_map("strtolower",$season_search_type)))
                {
                    $season_type = $token;
                }
                $token_count++;
             }
             //generate child tokens
            $tokens = array();
            $tokens = $child_tokens;
             if(!empty($season_type))
             {
              //print_r($season_type); 
             }
             
             //Determine Cities
             $token_count = 0;
             //$tokens[count($tokens)] = ""; //use this as a place holder for scanning for city logic.. to avoid unknown offset error
             $child_tokens= array();
             //print_r($tokens);
             foreach($tokens as $token)
             {  
                $next_city = isset($tokens[$token_count+1]) ? $tokens[$token_count+1]:"";
                if( (!in_array(strtolower($token),array_map("strtolower",$cities_search_type))) && (!in_array(strtolower($token." ".$next_city),array_map("strtolower",$cities_search_type))) )
                {
                   $child_tokens[] = $token;
                }
                if( (in_array(strtolower($token),array_map("strtolower",$cities_search_type))) || (in_array(strtolower($token." ".$next_city),array_map("strtolower",$cities_search_type))) )
                {   if(!in_array($token,$cities_types))
                   {
                    $cities_types[] = $token;
                   }
                }
                $token_count++;
             }
             //generate child tokens
            $tokens = array();
            $tokens = $child_tokens;
            //echo "<br/>TOKENS...";print_r($tokens);echo "<br/>";
             $v_destination = "";
             $v_origin      = "";
             if(!empty($cities_types))
             {
                $v_origin      = $cities_types[0];
                $v_destination = isset($cities_types[1])? $cities_types[1]: $v_origin;
                //print_r($cities_types); 
             }
             
             
             //Determine Price range
             $token_count = 0;
             $child_tokens= array();
             foreach($tokens as $token)
             {  
                if(!is_numeric($token))
                {
                   $child_tokens[] = $token;
                }
                if(is_numeric($token))
                {   if(!in_array($token,$price_types))
                   {
                    $price_types[] = $token;
                   }
                }
                $token_count++;
             }
             //generate child tokens
            $tokens = array();
            $tokens = $child_tokens;
            
             $v_price_from = 0;
             $v_price_to   = 0;
             if(!empty($price_types))
             {  
                sort($price_types);
                $v_price_from  = $price_types[0];
                $v_price_to    = isset($price_types[1])? $price_types[1]: 0;
              //print_r($price_types); 
             }
             
        
             //Determine Age
             $token_count = 0;
             $child_tokens= array();
             foreach($tokens as $token)
             {  
                if(!in_array(strtolower($token),array_map("strtolower",$age_search_type)))
                {
                   $child_tokens[] = $token;
                }
                if(in_array(strtolower($token),array_map("strtolower",$age_search_type)))
                {   
                    $age_type = $token;
                }
                $token_count++;
             }
             //generate child tokens
             //print_r($child_tokens);
            $tokens = array();
            $tokens = $child_tokens;
       
             if(!empty($age_type))
             {
              //print_r($age_type); 
             }                          
          
            //Determine Countries
             $token_count = 0;
             $child_tokens= array();
             foreach($tokens as $token)
             {  
                if(!in_array(strtolower($token),array_map("strtolower",$countries_search_type)))
                {
                   $child_tokens[] = $token;
                }
                if(in_array(strtolower($token),array_map("strtolower",$countries_search_type)))
                {   
                    $country_type = $token;
                }
                $token_count++;
             }
             //generate child tokens
            $tokens = array();
            $tokens = $child_tokens;
           
             if(!empty($country_type))
             {
              //print_r($country_type); 
             }
          
               if(!empty($tokens))
               {
                  //echo "<br/><br/>Criteria Token<br/>";  
                  //print_r($tokens); 
               }else
               {
                 $tokens[0] = "";
               } 
             
             //die();
      
              //Finish interpretation
              
              //Try to search the deducted criteria
              
              //flight search -- for a flight search to happen, there must be at least origin, destination and departure
              //echo "Flight..".$flight_search."<br/>";
              if( ($flight_search == 1) && ($v_origin != "" ) && ($v_destination != "") && ($v_departure!= "") )
              {
                $flight_data = array(
                    'origin_city' => $v_origin,
                    'destination_city' => $v_destination,
                    'trip_type' => $f_trip_type,
                    'departure_date' => $v_departure,
                    'return_date' => $v_return,
                    'persons' => 1,
                    );
                //print_r($flight_data);
                $flight_result = $this->Search_model->get_flights($flight_data);
                $flight_result = json_decode(json_encode($flight_result));
                //print_r($flight_result);
                $this->session->userdata['va']['flight_data'] = $flight_result;
              }
              
              //hotel search
              if( ($hotel_search == 1) && ($v_destination != "") )
              {
                 $hotel_data = array(
                    'city_or_hotel' => $v_destination,
                    );
                $hotel_result = $this->Search_model->get_hotel_rooms($hotel_data);
                $hotel_result = json_decode(json_encode($hotel_result));
                //print_r($hotel_result);
                $this->session->userdata['va']['hotel_data'] = $hotel_result;
                
              }
              
              //activity search  
              if( ($activity_search == 1) && (($v_destination!="") || ($continent_type!=""))  )
              {
                $holiday_data = array(
                    'activities' => $tokens[0],
                    'destination_city' => $v_destination,
                    'season' => $season_type,
                    'destination_continent' => $continent_type,
                    'age_group' => $age_type,
                    'price_from' => $v_price_from,
                    'price_to' => $v_price_to,
                    );
                    
                //print_r($holiday_data);
                $holiday_result = $this->Search_model->get_filters($holiday_data);
                $holiday_result = json_decode(json_encode($holiday_result));
                //print_r($holiday_result);
                $this->session->userdata['va']['activity_data'] = $holiday_result;
              }
                
                //echo $replies_result[0]['DrResponse'];
                //$replies_result[0]['DrResponse']                .= " from ".$origin."?";
                //increament current
                //$this->session->userdata['dialogue_flow']['current'] += 1;
                if((empty($flight_result)) && (empty($hotel_result)) && (empty($holiday_result)))
                {
                  //Try 3 times then reset
                   $this->session->userdata['va']['trials']++;
                   if($this->session->userdata['va']['trials'] == 3)
                   {
                      //reset to end this dialogue
                      unset($this->session->userdata['dialogue_flow']['current']);
                   }  
                  $replies_result[0]['DrResponse']= $replies_result[0]['DrValidationResponse'];
                
                }else{ 
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                }
               
          }
            
            
          
            
        }
        
        if($replies_result[0]['DrType'] == 'result')
        {
            //set flight dialogue session
            if($replies_result[0]['DrStage'] == "1")
            {   //print_r($this->session->userdata['va']['flight_data']);
              //Trigger result
              if((empty($this->session->userdata['va']['flight_data'])) && (empty($this->session->userdata['va']['hotel_data'])) && (empty($this->session->userdata['va']['activity_data'])))
              {
                  $replies_result[0]['DrResponse']= $replies_result[0]['DrValidationResponse'];
              }
              
            }
        
        }
        //print_r($replies_result);
        if($replies_result[0]['DrType'] == 'help')
        {
            //echo "STAGE..";
            //print_r($replies_result[0]['DrStage']);
            //echo "<br/>";
            //set help dialogue session
            if($replies_result[0]['DrStage'] == "1")
            {   
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type'] = $replies_result[0]['DrType'];
                //increament current
                $this->session->userdata['dialogue_flow']['current'] += 1;
                //echo "CURRENT1...".$this->session->userdata['dialogue_flow']['current']; echo "<br/>";
                $this->session->userdata['va']['flight_data']   = array();
                $this->session->userdata['va']['hotel_data']    = array();
                $this->session->userdata['va']['activity_data'] = array();
                               
            }
            if($replies_result[0]['DrStage'] == "2")
            {   
                $is_valid = true;
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                //set continent
                if( ($messagesan != "help") && (!in_array(strtolower($messagesan),array_map("strtolower",$continents_search_type))))
                {
                    $is_valid = false;
                    $replies_result[0]['DrResponse']   = $replies_result[0]['DrValidationResponse'];
                }
                if(($messagesan != "help") && ($is_valid))
                {
                  $this->session->userdata['dialogue_flow']['continent'] = $messagesan;
                }
                
                if(($messagesan != "help") && ($is_valid))
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if(($messagesan == "help"))
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            if($replies_result[0]['DrStage'] == "3")
            {   
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']       = $replies_result[0]['DrType'];
                //set environment
                if($messagesan != "help")
                {
                  $this->session->userdata['dialogue_flow']['environment']= $messagesan;
                }
                if($messagesan != "help")
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            if($replies_result[0]['DrStage'] == "4")
            {   
                $is_valid = true;
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                if( ($messagesan != "help") && (!in_array(strtolower($messagesan),array_map("strtolower",$season_search_type))))
                {
                    $is_valid = false;
                    $replies_result[0]['DrResponse']   = $replies_result[0]['DrValidationResponse'];
                }
                //set season
                if(($messagesan != "help") && ($is_valid))
                {
                  $this->session->userdata['dialogue_flow']['season']    = $messagesan;
                }
                
                if(($messagesan != "help") && ($is_valid))
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            if($replies_result[0]['DrStage'] == "5")
            {   
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                //set Weather
                if($messagesan != "help")
                {
                 $this->session->userdata['dialogue_flow']['weather']   = $messagesan;
                }
                
                if($messagesan != "help")
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            if($replies_result[0]['DrStage'] == "6")
            {   
                $is_valid      = true;
                //$date_types[0] = "";
                //$date_types[1] = "";
                $date_types    = array();
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                //set Date from and to
                if(($messagesan != "help") && ($is_valid))
                {
                    $date_tokens = $this->splitIt($messagesan);
                    //print_r($date_tokens);die();
                    foreach($date_tokens as $date)
                    {   //$date_var = '28/03/2018';
                      $date   = str_replace('/', '-', $date);
                      $date_r = date('Y-m-d', strtotime($date));
                      if ( (DateTime::createFromFormat('Y-m-d', $date_r) !== FALSE) && ($date_r != "1970-01-01") ) {
                        $date_types[] = $date_r;
                        
                      }
                      
                    }
                    //print_r($date_types);die();
                    //check if date is more than one and sort
                  
                  if(count($date_types) > 1)
                  { 
                    $f_trip_type = 2;
                    //try to sort in ascending order
                    $comp_date = $this->compareByTimeStamp($date_types[0],$date_types[1]);
                    if($comp_date == -1)
                    {
                        //do a swap
                        $temp_date = $date_types[0];
                        $date_types[0] = $date_types[1];
                        $date_types[1] = $temp_date;
                    }
                    
                    $this->session->userdata['dialogue_flow']['date_from'] = $date_types[0];
                    $this->session->userdata['dialogue_flow']['date_to']   = $date_types[1];
                  }  
                }
                //validate
                //print_r($date_types);
                if ( ($messagesan != "help") && (empty($date_types))) {
                    $is_valid = false;
                    $replies_result[0]['DrResponse']   = $replies_result[0]['DrValidationResponse'];
                        
                }

                if(($messagesan != "help") && ($is_valid))
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            if($replies_result[0]['DrStage'] == "7")
            {   
                $is_valid      = true;
                $price_types   = array();
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                
                //Price range
                if($messagesan != "help")
                {
                    $price_range = $this->splitIt($messagesan);
                    foreach($price_range as $thisprice)
                     {  
                     
                        if(is_numeric($thisprice))
                        {  
                            $price_types[] = $thisprice;
                        }
                     
                     }                
                     $v_price_from = 0;
                     $v_price_to   = 0;
                     if(!empty($price_types))
                     {  
                        sort($price_types);
                        $v_price_from  = isset($price_types[0])? $price_types[0]: 0;
                        $v_price_to    = isset($price_types[1])? $price_types[1]: 0;
                      //print_r($price_types); 
                     }
                    //set price range
                    $this->session->userdata['dialogue_flow']['price_from'] = $v_price_from;
                    $this->session->userdata['dialogue_flow']['price_to']   = $v_price_to;
                    
                }
                //validate price range
                if ( ($messagesan != "help") && (empty($price_types))) {
                    $is_valid = false;
                    $replies_result[0]['DrResponse']   = $replies_result[0]['DrValidationResponse'];
                        
                }

                if(($messagesan != "help") && ($is_valid))
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            
            
            if($replies_result[0]['DrStage'] == "8")
            {   
                $is_valid = true;
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                //validate age group
                if( ($messagesan != "help") && (!in_array(strtolower($messagesan),array_map("strtolower",$age_search_type))))
                {
                    $is_valid = false;
                    $replies_result[0]['DrResponse']   = $replies_result[0]['DrValidationResponse'];
                }
                //set age group
                if(($messagesan != "help") && ($is_valid))
                {
                  $this->session->userdata['dialogue_flow']['age_group'] = $messagesan;
                }
                if(($messagesan != "help") && ($is_valid))
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>
                                                                          <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                
            }
            if($replies_result[0]['DrStage'] == "9")
            {   
                if($messagesan == "restart")
                {
                    //reset to end this dialogue
                    unset($this->session->userdata['dialogue_flow']['current']);
                    $replies_result[0]['DrResponse']                       = "<div style='color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-plane' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','holiday') style='text-decoration:none;'>WANTED HOLIDAY</a></div>
                                                                              <div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-anchor' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','help') style='text-decoration:none;'>HELP</a></div>";
                     
                    echo $replies_result[0]['DrResponse']; 
                    die();                                                         
                }
                //set dialogue type
                $this->session->userdata['dialogue_flow']['type']      = $replies_result[0]['DrType'];
                //set activity
                if($messagesan != "help")
                {
                  $this->session->userdata['dialogue_flow']['activity']  = $messagesan;
                }
                
                if($messagesan != "help")
                {
                $replies_result[0]['DrResponse']                       = "<div style='margin-top:4px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-spinner' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','restart') style='text-decoration:none;'>RESTART</a></div>
                                                                          <div style='margin-top:4px;margin-bottom:30px;color:#fff;background-color:#000;border:0px solid #CCC; padding:10px;width:100%;border-radius: 5px;'><span class='fa fa-info' style='color:#009900;'></span>&nbsp;&nbsp;<a href='javascript:void(0)' onclick=javascript:chatWith('TAEN-Agent','result') style='text-decoration:none;'>RESULTS</a></div>";
                }
                
                //increament current
                if($messagesan == "help")
                {
                  $this->session->userdata['dialogue_flow']['current'] += 1;
                }
                 //reset to end this dialogue
                 unset($this->session->userdata['dialogue_flow']['current']);
            }
            
            
            //store session variable in normal variable for search.
            $va_activity    = isset($this->session->userdata['dialogue_flow']['activity'])   ? $this->session->userdata['dialogue_flow']['activity']   : "";
            $va_age_group   = isset($this->session->userdata['dialogue_flow']['age_group'])  ? $this->session->userdata['dialogue_flow']['age_group']  : "";
            $va_price_from  = isset($this->session->userdata['dialogue_flow']['price_from']) ? $this->session->userdata['dialogue_flow']['price_from'] : 0;
            $va_price_to    = isset($this->session->userdata['dialogue_flow']['price_to'])   ? $this->session->userdata['dialogue_flow']['price_to']   : 0;
            $va_date_to     = isset($this->session->userdata['dialogue_flow']['date_to'])    ? $this->session->userdata['dialogue_flow']['date_to']    : "";
            $va_date_from   = isset($this->session->userdata['dialogue_flow']['date_from'])  ? $this->session->userdata['dialogue_flow']['date_from']  : "" ;
            $va_weather     = isset($this->session->userdata['dialogue_flow']['weather'])    ? $this->session->userdata['dialogue_flow']['weather']    : "";
            $va_Season      = isset($this->session->userdata['dialogue_flow']['season'])     ? $this->session->userdata['dialogue_flow']['season']     : "" ;
            $va_continent   = isset($this->session->userdata['dialogue_flow']['continent'])  ? $this->session->userdata['dialogue_flow']['continent']  : "";
            $va_environment = isset($this->session->userdata['dialogue_flow']['environment'])? $this->session->userdata['dialogue_flow']['environment']: "";
            
            $city_array     = array();
            $holiday_loop_counter = 0;
            $in_clause      = "";
            $holiday_data = array(
                    'activities' => $va_activity,
                    'destination_city' => "",
                    'season' => $va_Season,
                    'destination_continent' => $va_continent,
                    'age_group' => $va_age_group,
                    'price_from' => $va_price_from,
                    'price_to' => $va_price_to,
                    'environment' => $va_environment,
                    'weather' => $va_weather,
                    'departure_date' => $va_date_from,
                    'return_date' => $va_date_to,
                    );
            //print_r($holiday_data); //die();
            $holiday_result = $this->Search_model->get_filters($holiday_data);
            $holiday_result = json_decode(json_encode($holiday_result));
            //print_r($holiday_result);
            
            if(!empty($holiday_result))
            {
                $this->session->userdata['va']['activity_data'] = $holiday_result;
                //Try seasrch for others -- flight and hotel
                //Get city and use it to search for flight and Hotel
                foreach($holiday_result as $this_filter)
                {   
                    if(!in_array($holiday_result[$holiday_loop_counter]->City,$city_array))
                    { 
                       //store all cities in filters search result 
                      $city_array[]  = $holiday_result[$holiday_loop_counter]->City;
                      $in_clause    .= "'".$holiday_result[$holiday_loop_counter]->City."'".",";
                    }
                    
                    $holiday_loop_counter++;
                }
                $in_clause = substr($in_clause,0,strlen($in_clause)-1); //remove last comma
                //print_r($in_clause);
              if( ($in_clause != "") )
              {
                $flight_data = array(
                    'filters_flight'=> $in_clause,
                    );
                
                $flight_result = $this->Search_model->get_flights($flight_data);
                $flight_result = json_decode(json_encode($flight_result));
                //print_r($flight_result);
                $this->session->userdata['va']['flight_data'] = $flight_result;
              }
              
              //hotel search
              if( ($in_clause != ""))
              {
                 $hotel_data = array(
                    'filters_hotel' => $in_clause,
                    );
                $hotel_result = $this->Search_model->get_hotel_rooms($hotel_data);
                $hotel_result = json_decode(json_encode($hotel_result));
                //print_r($hotel_result);
                $this->session->userdata['va']['hotel_data'] = $hotel_result;
                
              }
            }
            
            
        }
       
            
            
        if($replies_result[0]['DrType'] == 'restart')
        {
            //
        }
        //print_r($replies_result); die();
        //prepare response
        $this->session->userdata['openChatBoxes'][$ip_address] = date('Y-m-d H:i:s', time());
        $messagesan_r = $replies_result[0]['DrResponse'];
        if (!isset($this->session->userdata['chatHistory'][$ip_address])) {
		$this->session->userdata['chatHistory'][$ip_address] = '';
        }
        $this->session->userdata['chatHistory'][$ip_address] .= <<<EOD
					   {
			"s": "1",
			"f": "You",
			"m": "{$messagesan_r}"
	   },
EOD;
    }else
    {
        //prepare response
        $this->session->userdata['openChatBoxes'][$ip_address] = date('Y-m-d H:i:s', time());
        //This is set if no dialogue type match
        $messagesan_r = "Hmm, I can not understand that one. Start all over by clicking Wanted Holiday or Help button. Then try stuffs like 'hotel room offers in London' or 'flying from New York to London on 29/03/2018' ";
        $this->session->userdata['dialogue_flow']['current'] = 1; //start dialogue allover
        
        //$messagesan_r = "Opss!. I guess i need to improve my vocabulary";
        if (!isset($this->session->userdata['chatHistory'][$ip_address])) {
		$this->session->userdata['chatHistory'][$ip_address] = '';
        }
        $this->session->userdata['chatHistory'][$ip_address] .= <<<EOD
					   {
			"s": "1",
			"f": "You",
			"m": "{$messagesan_r}"
	   },
EOD;
	}
    
	unset($this->session->userdata['tsChatBoxes'][$this->input->post('to')]);
    
    //insert visitos chat/ que/ enquiries
    $data = array(
        'DkFrom' => $ip_address,
        'DkTo' => $to,
        'DkMessage' => trim($message),
        'DkSent' => date('Y-m-d H:i:s')
        );
        
    $save_result    = $this->Search_model->save_dialogue_knowledge($data);
  
    //store response
    //insert VA resonse here
    $data = array(
        'DkFrom' => $to,
        'DkTo' => $ip_address,
        'DkMessage' => htmlspecialchars($messagesan_r),
        'DkSent' => date('Y-m-d H:i:s')
        );

    $save_result    = $this->Search_model->save_dialogue_knowledge($data);
    //print_r($messagesan_r); die();
    //$va_response = $messagesan_r;
    echo $messagesan_r; 
    //header('Content-type: application/json');
    
	exit(0);
}

function closeChat() {

	unset($this->session->userdata['openChatBoxes'][$this->input->post('chatbox')]);
	
	echo "1";
	exit(0);
}

public function sanitize($text) 
{
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}

public function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

public function splitIt($s){ //tokenize
    $arr = array('.',',');
    $s = str_replace($arr," ",$s);
    $s = preg_replace("/( )+/", " ", $s);
    $s = preg_replace("/^( )+/", "", $s);
    $s = preg_replace("/( )+$/", "", $s);
    $tokens = explode(" ",$s);
    return $tokens;
}

//used for sorting date
public function compareByTimeStamp($time1, $time2)
{
    if (strtotime($time1) < strtotime($time2))
        return 1;
    else if (strtotime($time1) > strtotime($time2)) 
        return -1;
    else
        return 0;
}


public function vaResults(){ //va_results
   $this->load->view('va_results');
}




}

?>