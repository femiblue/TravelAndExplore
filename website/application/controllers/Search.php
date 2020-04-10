<?php

//session_start(); //we need to start session in order to access it through CI
defined('BASEPATH') OR exit('No direct script access allowed');
Class Search extends CI_Controller {

public function __construct() {
parent::__construct();

// Load form helper library
$this->load->helper('form');

// Load form validation library
$this->load->library('form_validation');

// Load session library
$this->load->library('session');

// Load model
$this->load->model('Search_model');

$this->load->helper('url');
}

// Show show_search page
public function show_result($msg = array()) {
 //$this->load->helper('url');
 $this->load->view('index',$msg);
}


// search from all angles. Flights, Hotels and Filters
public function search_all() {
    
    $data = array();
    // Check validation 
    $this->form_validation->set_rules('trip_type', 'Trip Type', 'trim');
    $this->form_validation->set_rules('destination_continent', 'Continent', 'trim');
    $this->form_validation->set_rules('environment', 'Environment','trim');
    $this->form_validation->set_rules('season', 'Season', 'trim');
    $this->form_validation->set_rules('weather', 'Weather','trim');
    $this->form_validation->set_rules('origin_city', 'Origin City', 'trim');
    $this->form_validation->set_rules('destination_city', 'Destination City', 'trim');
    $this->form_validation->set_rules('age_group', 'Age Group','trim');
    $this->form_validation->set_rules('activities', 'Activities','trim');
    $this->form_validation->set_rules('price_from', 'Price From','trim');
    $this->form_validation->set_rules('price_to', 'Price To','trim');
    $this->form_validation->set_rules('departure_date', 'Departure Date','trim');
    $this->form_validation->set_rules('return_date', 'Return Date','trim');
    
    $origin_city        = $this->input->post('origin_city');
    $destination_city   = $this->input->post('destination_city');
    $trip_type          = $this->input->post('trip_type');
    $environment        = $this->input->post('environment');
    $season             = $this->input->post('season');
    $weather            = $this->input->post('weather');
    $age_group          = $this->input->post('age_group');
    $activities         = $this->input->post('activities');
    $price_from         = $this->input->post('price_from');
    $price_to           = $this->input->post('price_to');
    $departure_date     = $this->input->post('departure_date');
    if($departure_date != "")
    {
     $departure_date     = str_replace('/', '-', $departure_date);
     $departure_date     = date('Y-m-d', strtotime($departure_date));
    }
    $return_date        = $this->input->post('return_date');
    if($return_date != "")
    {
     $return_date        = str_replace('/', '-', $return_date);
     $return_date        = date('Y-m-d', strtotime($return_date));
    }
    $destination_continent = $this->input->post('destination_continent');

    //General check
    if( $destination_city == "" && $origin_city == "" && $departure_date == "" && $return_date == "" &&
        $environment == "" && $season == "" && $weather == "" && $age_group == "" && $activities == "" && 
        $price_from == "" && $price_to == "" && $destination_continent == ""  )
    {
        $data = array();
        $data['error_message'] = "You did not search for anything. Please enter a search criteria";
        $data['search_type']   = "all";
        $this->show_result($data);
        return false;
    }
    
    //Flight related check
    if($departure_date != "" && ($origin_city == "" || $destination_city == ""))
    {   
        $data = array();
        $data['error_message'] = "You cannot search for Departure date without an Originating and Destination City";
        $data['search_type']   = "all";
        $this->show_result($data);
        return false;
    }
    if($departure_date == "" && ($origin_city != "" || $destination_city != ""))
    {   
        $data = array();
        $data['error_message'] = "You cannot search for an Originating or Destination City without a Departure date";
        $data['search_type']   = "all";
        $this->show_result($data);
        return false;
    }
    if(($origin_city == "" && $destination_city != "") || ($origin_city != "" && $destination_city == ""))
    {   
        $data = array();
        $data['error_message'] = "You cannot search for an Originating City with out a Destination";
        $data['search_type']   = "all";
        $this->show_result($data);
        return false;
    }
    if( ($trip_type == "2") && ($destination_city == "" || $origin_city == "" || $departure_date == "" || $return_date == "") )
    {   
        $data = array();
        $data['error_message'] = "For a Round trip, you must supply Origin, Destination, Departure date and Return date";
        $data['search_type']   = "all";
        $this->show_result($data);
        return false;
    }
    /*
    if( ($trip_type == "1") && ($destination_city == "" || $origin_city == "" || $departure_date == "" ) )
    {   
        $data = array();
        $data['error_message'] = "For a One way trip, you must supply Origin, Destination, Departure date";
        $this->show_result($data);
        return false;
    }
    */
    
    if ($this->form_validation->run() == FALSE) {
        
       //go back home
        $this->load->library('session');
        $error_message = validation_errors(); //print_r($error_message);die();
        //$this->session->set_flashdata('validation_error',$error_message);
        $data['error_message'] = $error_message;
        $this->load->view('index',$data);
        //header("location: ".base_url()."");
        
    } else {
        $data = array(
        'trip_type' => $this->input->post('trip_type'),
        'destination_continent' => $this->input->post('destination_continent'),
        'environment' => $this->input->post('environment'),
        'season' => $this->input->post('season'),
        'weather' => $this->input->post('weather'),
        'origin_city' => $this->input->post('origin_city'),
        'destination_city' => $this->input->post('destination_city'),
        'age_group' => $this->input->post('age_group'),
        'activities' => $this->input->post('activities'),
        'price_from' => $this->input->post('price_from'),
        'price_to' => $this->input->post('price_to'),
        'departure_date' => $departure_date,
        'return_date' => $return_date
        );
        $this->session->set_userdata('qty', 1);
         
         $filters_result = $this->Search_model->get_filters($data);
        
            
            $filters_result_count    = count($filters_result);
            $filters_loop_counter    = 0;
            $rating_array               = array();
            $price_array                = array();
            $city_array                 = array();
            $hotel_result_by_price      = array();
            $flight_result_by_price     = array();
            $flight_result_count        = 0;
            $hotel_result_count         = 0;
            $in_clause                  = "";
           if(!empty($filters_result))
           { 
            foreach($filters_result as $this_filter)
            {   
                if(!in_array($filters_result[$filters_loop_counter]->City,$city_array))
                { 
                   //store all cities in activities search result 
                  $city_array[]  = $filters_result[$filters_loop_counter]->City;
                  $in_clause    .= "'".$filters_result[$filters_loop_counter]->City."'".",";
                }
                
                $filters_loop_counter++;
            }
            $in_clause = substr($in_clause,0,strlen($in_clause)-1); //remove last comma
           }
            //rtrim($in_clause,",");
            //echo "<br/><br/>";
            //echo "IN CLAUSE...".$in_clause;
            //echo "<br/><br/>";
            //print_r($city_array); //die();
            //echo "<br/><br/>";
            /*$data = array(
            'filters_flight' => $in_clause,
            'filters_flight' => $in_clause,
            'filters_hotel' => $in_clause
            );*/
            $h_data = array(
            'filters_hotel' => $in_clause,
            'city_or_hotel' => $this->input->post('destination_city'),
            'persons' => $this->input->post('persons')
            );
            $f_data = array(
            'origin_city' => $this->input->post('origin_city'),
            'destination_city' => $this->input->post('destination_city'),
            'trip_type' => $this->input->post('trip_type'),
            'departure_date' => $departure_date,
            'return_date' => $return_date,
            'persons' => $this->input->post('persons'),
            );
            $hotel_result = $this->Search_model->get_hotel_rooms($h_data);
            if (!empty($hotel_result)) { 
                //echo "<br/><br/>";
                //print_r($hotel_result); die();
                $hotel_result_count    = count($hotel_result);
        
            }
            
            $flight_data = $this->Search_model->get_flights($f_data);
            if (!empty($flight_data)) { 
                //echo "<br/><br/>";
                //print_r($flight_data);die();
                $flight_result_count    = count($flight_data);
            
            }
        if (!empty($filters_result) || !empty($hotel_result) || !empty($flight_data)) { //print_r($filters_result); die();
            //clear some of the keys to prevent confusion on presentation
            $data['flight_data_by_price']      = "";
            $data['flight_data_by_duration']   = "";   
            $data['hotel_result_by_price']     = "";  
            $data['hotel_result_by_ranking']   = ""; 
            $data['a_hotel_result']            = "";
            $data['a_flight_result']           = "";
            $data['a_activities_result']       = "";  
            $data['hotel_result']              = "";
            $data['flight_result']             = "";
            $data['activities_result']         = "";                
            //Store return value
            //$data['result_page_title']   = "Searched for flights from ".$origin_city. " to ".$destination_city." and hotels in ".$destination_city;
            $data['result_page_title']   = "";
            $data['a_hotel_result']      = $hotel_result;
            $data['a_flight_result']     = $flight_data; 
            $data['a_activities_result'] = $filters_result;            
            $data['message_success']     = 'We found '.$filters_result_count.' Activities, '.$flight_result_count.' Flights and '.$hotel_result_count.' Hotels  items matching your search!';
            //print "<pre>"; print_r($data); print "</pre>";die();
            //Go Display result
            $this->show_result($data);
        } else {
            if (empty($filters_result) && empty($hotel_result) && empty($flight_data)) {
                $data = array(
                'message_error' => 'We could not get any result match for your search at the moment. Please change the criteria or check back later',
                'search_type'   => 'all'
                );  
            }
            //Return no result message
            $this->session->set_flashdata('result_error',$data['message_error']);
            $this->session->set_flashdata('search_type',$data['search_type']);
            //$this->session->set_error('result_error', $data['message_error']);
            //header("location: ".base_url()."");
            $this->show_result($data);
        }
        
    }
}

// search for flights
public function search_flights() { 
    
    $data = array();
    // Check validation 
    $this->form_validation->set_rules('origin_city', 'Origin City', 'trim|required');
    $this->form_validation->set_rules('destination_city', 'Destination City', 'trim|required');
    //$this->form_validation->set_rules('trip_type', 'Trip Type', 'trim');
    $this->form_validation->set_rules('departure_date', 'Departure Date','trim|required');
    $this->form_validation->set_rules('return_date', 'Return Date','trim');
    $this->form_validation->set_rules('persons', 'Persons','trim|required');
    
    $origin_city        = $this->input->post('origin_city');
    $destination_city   = $this->input->post('destination_city');
    $departure_date     = $this->input->post('departure_date');
    if($departure_date != "")
    {
     $departure_date     = str_replace('/', '-', $departure_date);
     $departure_date     = date('Y-m-d', strtotime($departure_date));
    }
    $return_date        = $this->input->post('return_date');
    if($return_date != "")
    {
     $return_date        = str_replace('/', '-', $return_date);
     $return_date        = date('Y-m-d', strtotime($return_date));
    }
    
    $trip_type          = 1;
    if($return_date != "")
    {
        $trip_type = 2;
    }
    //general check
    if( $destination_city == "" && $origin_city == "" && $departure_date == "" && $return_date == "" )
    {
        $data = array();
        $data['error_message'] = "You did not search for anything. Please enter a search criteria";
        $data['search_type']   = "flight";
        $this->show_result($data);
        return false;
    }
    //Flight related check
    if($departure_date != "" && ($origin_city == "" || $destination_city == ""))
    {   
        $data = array();
        $data['error_message'] = "You cannot search for Departure date without an Originating and Destination City";
        $data['search_type']   = "flight";
        $this->show_result($data);
        return false;
    }
    if(($origin_city == "" && $destination_city != "") || ($origin_city != "" && $destination_city == ""))
    {   
        $data = array();
        $data['error_message'] = "You cannot search for an Originating City with out a Destination";
        $data['search_type']   = "flight";
        $this->show_result($data);
        return false;
    }
    
    if ($this->form_validation->run() == FALSE) { 
        
        //go back home
        $error_message = validation_errors(); 
        $data['error_message'] = $error_message;
        $this->load->view('index',$data);
        
    } else { 
        $persons       = $this->input->post('persons');
        $data = array(
        'origin_city' => $this->input->post('origin_city'),
        'destination_city' => $this->input->post('destination_city'),
        'trip_type' => $trip_type,
        'departure_date' => $departure_date,
        'return_date' => $return_date,
        'persons' => $this->input->post('persons'),
        );
        
        $this->session->set_userdata('qty', $persons);
        $origin_city      = $this->input->post('origin_city');
        $destination_city = $this->input->post('destination_city');
  
        $flight_data = $this->Search_model->get_flights($data);
        if (!empty($flight_data)) { //print_r($flight_data);//die();
            
            $flight_data_count      = count($flight_data);
            $flight_loop_counter    = 0;
            $duration_array         = array();
            $flight_data_by_price   = array();
            $flight_data_by_duration=array();
            
             foreach($flight_data as $this_data)
            {   
                //try calculate flight dduration
                $current_duration = abs(strtotime($flight_data[$flight_loop_counter]->ArrivalTime) - strtotime($flight_data[$flight_loop_counter]->DepartureTime));
                
                $flight_data[$flight_loop_counter]->duration = $current_duration;
            
                 if(!in_array($current_duration,$duration_array))
                 {
                   $duration_array[] = $current_duration;
                 }
                $flight_loop_counter++;
            }
            
            sort($duration_array);
            //echo "<br/><br/>";
            //print_r($duration_array); 
            //echo "<br/><br/>";
            $flight_data_by_price   = $flight_data;
            $sort_counter           =0;
            //$res_counter            =0;
            foreach($duration_array as $duration){
                $res_counter            =0;
                foreach($flight_data as $this_data)
                {  
                   if($flight_data[$res_counter]->duration == $duration){
                   
                      $flight_data_by_duration[$sort_counter] = $flight_data[$res_counter];
                      $sort_counter++;
                    
                   } 
                   $res_counter++;
                }
                
            }
            //clear some of the keys to prevent confusion on presentation
            $data['flight_data_by_price']      = "";
            $data['flight_data_by_duration']   = "";   
            $data['hotel_result_by_price']     = "";  
            $data['hotel_result_by_ranking']   = ""; 
            $data['a_hotel_result']            = "";
            $data['a_flight_result']           = "";
            $data['a_activities_result']       = "";  
            $data['hotel_result']              = "";
            $data['flight_result']             = "";
            $data['activities_result']         = "";                
            //Store return value
            $data['result_page_title']         = "Searched for flights from ".$origin_city. " to ".$destination_city." ";
            $data['flight_data_by_price']      = $flight_data_by_price;
            $data['flight_data_by_duration']   = $flight_data_by_duration;            
            $data['message_success'] = 'We found '.$flight_data_count.' Flight items matching your search!';
            //print_r($data);die();
            //Go Display result
            $this->show_result($data);
            } else {
            $data = array(
            'message_error' => 'We could not get any flight match for your search at the moment. Please change the criteria or check back later',
            'search_type' => 'flight'
            );  
            //Return no result message
            $this->session->set_flashdata('result_error',$data['message_error']);
            $this->session->set_flashdata('search_type',$data['search_type']);
            //$this->session->set_error('result_error', $data['message_error']);
            header("location: ".base_url()."");
            //$this->show_search($data);
        }
    }
}

// Search for hotel rooms
public function search_hotels() { 
    
    $data = array();
    //print_r($this->input->post());die();
    
    // Check validation for user input in Search hotel form
    $this->form_validation->set_rules('city_or_hotel', 'City or Hotel', 'trim|required');
    //$this->form_validation->set_rules('check_in_date', 'Check in Date', 'trim');
    //$this->form_validation->set_rules('check_out_date', 'Check out Date', 'trim');
    $this->form_validation->set_rules('persons', 'Persons','trim|required');
    $this->form_validation->set_rules('rooms', 'Rooms','trim');
    

    if ($this->form_validation->run() == FALSE) { 
        //go back home
        //$this->show_search();
        // Add error in session
        $error_message = validation_errors();
        $data['error_message'] = $error_message;
        $data['search_type']   = "hotel";
        $this->load->view('index',$data);
        
    } else { 
        $city_or_hotel = $this->input->post('city_or_hotel');
        $rooms         = $this->input->post('rooms');
        $data = array(
        'city_or_hotel' => $this->input->post('city_or_hotel'),
        'check_in_date' => $this->input->post('check_in_date'),
        'check_out_date' => $this->input->post('check_out_date'),
        'persons' => $this->input->post('persons'),
        'rooms' => $this->input->post('rooms')
        );
        $this->session->set_userdata('qty', $rooms);
        $hotel_result = $this->Search_model->get_hotel_rooms($data);
        if (!empty($hotel_result)) { //print_r($hotel_result); die();
            
            $hotel_result_count    = count($hotel_result);
            $hotel_loop_counter    = 0;
            $ranking_array         = array();
            $price_array           = array();
            $hotel_result_by_price = array();
            $hotel_result_by_ranking=array();
            
            foreach($hotel_result as $this_hotel)
            {    //populate ranking array
                 if(!in_array($hotel_result[$hotel_loop_counter]->Ranking,$ranking_array))
                 {
                   $ranking_array[] = $hotel_result[$hotel_loop_counter]->Ranking;
                 }
                 
                 $hotel_loop_counter++;
            }
            
            rsort($ranking_array);
            //print_r($ranking_array);
            //echo "<br/><br/>";
           
            $hotel_result_by_price   = $hotel_result;
            //$hotel_result_by_ranking = $hotel_result;
            $sort_counter           =0;
            //$res_counter            =0;
            foreach($ranking_array as $rank){
                $res_counter            =0;
                foreach($hotel_result as $this_hotel)
                {  
                   if($hotel_result[$res_counter]->Ranking == $rank){
                   
                      $hotel_result_by_ranking[$sort_counter] = $hotel_result[$res_counter];
                      $sort_counter++;
                    
                   } 
                   $res_counter++;
                }
                
            }
            //clear some of the keys to prevent confusion on presentation
            $data['flight_data_by_price']      = "";
            $data['flight_data_by_duration']   = "";   
            $data['hotel_result_by_price']     = "";  
            $data['hotel_result_by_ranking']   = ""; 
            $data['a_hotel_result']            = "";
            $data['a_flight_result']           = "";
            $data['a_activities_result']       = "";  
            $data['hotel_result']              = "";
            $data['flight_result']             = "";
            $data['activities_result']         = "";                
            //Store return value
            $data['result_page_title']       = "Searched for Cities or Hotels related to ".$city_or_hotel;
            $data['hotel_result_by_price']      = $hotel_result_by_price;
            $data['hotel_result_by_ranking']    = $hotel_result_by_ranking;            
            $data['message_success'] = 'We found '.$hotel_result_count.' Hotel items matching your search!';
            //print_r($data);die();
            //Go Display result
            $this->show_result($data);
        } else {
            $data = array(
            'message_error' => 'We could not get any hotel match for your search at the moment. Please change the criteria or check back later',
            'search_type' => 'hotel'
            );  
            //Return no result message
            $this->session->set_flashdata('result_error',$data['message_error']);
            $this->session->set_flashdata('search_type',$data['search_type']);
            //$this->session->set_error('result_error', $data['message_error']);
            header("location: ".base_url()."");
            //$this->show_search($data);
        }
        
    }
    
    
    
}

// Search using natural language
public function natural_language_search() {
    
    
    $data = array();
    // Check validation for user input in Search anything form
    $this->form_validation->set_rules('activities', 'Activities', 'trim|required');
    if ($this->form_validation->run() == FALSE) { 
        //go back home
        //$this->show_search();
        // Add error in session
        $error_message = validation_errors();
        $data['error_message'] = $error_message;
        $data['search_type']   = "nlp";
        $this->load->view('index',$data);
        
    }else { 
        $this->session->set_userdata('qty', 1);
        $activities = $this->input->post('activities');
        $data = array(
        'activities' => $this->input->post('activities')
        );
        $filters_result = $this->Search_model->get_filters($data);
        if (!empty($filters_result)) { //print_r($filters_result); //die();
            
            $filters_result_count    = count($filters_result);
            $filters_loop_counter    = 0;
            $ranking_array              = array();
            $price_array                = array();
            $city_array                 = array();
            $hotel_result_by_price      = array();
            $flight_result_by_price     = array();
            $flight_result_count        = 0;
            $hotel_result_count         = 0;
            $in_clause                  = "";
            
            foreach($filters_result as $this_filter)
            {   
                if(!in_array($filters_result[$filters_loop_counter]->City,$city_array))
                { 
                   //store all cities in filters search result 
                  $city_array[]  = $filters_result[$filters_loop_counter]->City;
                  $in_clause    .= "'".$filters_result[$filters_loop_counter]->City."'".",";
                }
                
                $filters_loop_counter++;
            }
            $in_clause = substr($in_clause,0,strlen($in_clause)-1); //remove last comma
            //rtrim($in_clause,",");
            //echo "<br/><br/>";
            //echo "IN CLAUSE...".$in_clause;
            //echo "<br/><br/>";
            //print_r($city_array); //die();
            //echo "<br/><br/>";
            $data = array(
            'filters_flight' => $in_clause,
            'filters_hotel' => $in_clause
            );
            $hotel_result = $this->Search_model->get_hotel_rooms($data);
            if (!empty($hotel_result)) { 
                //echo "<br/><br/>";
                //print_r($hotel_result);
                $hotel_result_count    = count($hotel_result);
        
            }
            
            $flight_data = $this->Search_model->get_flights($data);
            if (!empty($flight_data)) { 
                //echo "<br/><br/>";
                //print_r($flight_data);die();
                $flight_result_count    = count($flight_data);
            
            }
            //clear some of the keys to prevent confusion on presentation
            $data['flight_data_by_price']      = "";
            $data['flight_data_by_duration']   = "";   
            $data['hotel_result_by_price']     = "";  
            $data['hotel_result_by_ranking']   = ""; 
            $data['a_hotel_result']            = "";
            $data['a_flight_result']           = "";
            $data['a_activities_result']       = "";  
            $data['hotel_result']              = "";
            $data['flight_result']             = "";
            $data['activities_result']         = "";                
            //Store return value
            $data['result_page_title']    = "Searched for anything related to ".$activities;
            $data['hotel_result']         = $hotel_result;
            $data['flight_result']        = $flight_data; 
            $data['activities_result']    = $filters_result;            
            $data['message_success'] = 'We found '.$filters_result_count.' Activities, '.$flight_result_count.' Flights and '.$hotel_result_count.' Hotels  items matching your search!';
            //print "<pre>"; print_r($data); print "</pre>";die();
            //Go Display result
            $this->show_result($data);
        } else {
            $data = array(
            'message_error' => 'We could not get any activity match for your search at the moment. Please change the criteria or check back later',
            'search_type' => 'nlp'
            );  
            //Return no result message
            $this->session->set_flashdata('result_error',$data['message_error']);
            $this->session->set_flashdata('search_type',$data['search_type']);
            //$this->session->set_error('result_error', $data['message_error']);
            header("location: ".base_url()."");
            //$this->show_search($data);
        }
        
    }
}
    
}

?>