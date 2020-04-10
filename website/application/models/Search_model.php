<?php

Class Search_model extends CI_Model {


// Get hotel rooms
public function get_hotel_rooms($vars=array()) { 

    if(!empty($vars))
    {  
        $city_or_hotel  = (isset($vars['city_or_hotel']) && ($vars['city_or_hotel']!=""))   ? $vars['city_or_hotel'] : "";
        $check_in_date  = (isset($vars['check_in_date']) && ($vars['check_in_date']!=""))   ? $vars['check_in_date'] : date("Y-m-d");
        $check_out_date = (isset($vars['check_out_date'])&& ($vars['check_out_date']!=""))  ? $vars['check_out_date']: date("Y-m-d",strtotime("+1 day"));
        $persons        = (isset($vars['persons'])       && ($vars['persons']!=""))         ? $vars['persons']         : 1;
        $rooms          = (isset($vars['rooms'])         && ($vars['rooms']!=""))           ? $vars['rooms']    : 1;
        $filters_hotel  = (isset($vars['filters_hotel']) && ($vars['filters_hotel']!=""))   ? $vars['filters_hotel']    : "";
        $condition      = "";
        if($filters_hotel != "")
        {
         $condition .= "h.HotelCity IN ($filters_hotel) AND (h.NumberOfRooms > '0') "; //This is to search for hotel in cities similar to those enter in home   
        }else{
          if($city_or_hotel == "")
          {
            return false;
          }  
          //prepare condition
          $condition .= "(h.Title LIKE '%$city_or_hotel%' OR HotelCity LIKE '%$city_or_hotel%' OR h.Address LIKE '%$city_or_hotel%') AND
                      (h.NumberOfRooms >= '$rooms')"; 
        }
    }else
    {
       $condition = " 1='1' "; 
    }


    $this->db->select('*');
    $this->db->from('room r');
    $this->db->join('hotel h', 'h.HotelID = r.Hotel');
    $this->db->where($condition);
    $this->db->order_by('r.Price', "asc"); 
    $query = $this->db->get();
    //print_r($query->result());die();
    if ($query->num_rows() > 0) {
    return $query->result();
    } else {
    return false;
    }
}



// Get flight_tickets . use $vars to use condition
public function get_flights($vars=array()) {

    if(!empty($vars))
    {  
        $origin_city       = (isset($vars['origin_city'])       && ($vars['origin_city']!=""))      ? $vars['origin_city']      : "";
        $destination_city  = (isset($vars['destination_city'])  && ($vars['destination_city']!="")) ? $vars['destination_city'] : "";
        $trip_type         = (isset($vars['trip_type'])         && ($vars['trip_type']!=""))        ? $vars['trip_type']        : 1;
        $departure_date    = (isset($vars['departure_date'])    && ($vars['departure_date']!=""))   ? $vars['departure_date']   : "";//date("Y-m-d");
        $return_date       = (isset($vars['return_date'])       && ($vars['return_date']!=""))      ? $vars['return_date']      : "";//date("Y-m-d",strtotime("+1 day"));
        $persons           = (isset($vars['persons'])           && ($vars['persons']!=""))          ? $vars['persons']          : 1;
        
        $filters_flight    = (isset($vars['filters_flight'])    && ($vars['filters_flight']!=""))   ? $vars['filters_flight']    : "";
        $condition      = "";
        if($filters_flight != "")
        {
         $condition .= "arrivalLocation IN ($filters_flight) AND (Capacity > '0')"; //This is to search for flight heading in cities that matched the filter result   
        }else
        {
            //prepare condition
            if($trip_type == 2)
            { //return trip
                if(($origin_city == "") || ($destination_city == "") || ($departure_date == "") || ($return_date == "") )
                {
                    return false;
                }
                $condition = "( (  ( (DepartureAirport LIKE '%$origin_city%' AND ArrivalLocation LIKE '%$destination_city%') AND (DepartureDate = '$departure_date' ) ) 
                              OR ( (DepartureAirport LIKE '%$destination_city%' AND ArrivalLocation LIKE '%$origin_city%') AND (DepartureDate = '$return_date' ) )  )
                              AND (Capacity >= '$persons') )"; 
            }else
            { //One way trip
        
               if(($origin_city == "") || ($destination_city == "") || ($departure_date == "") )
                {
                    return false;
                }
                $condition = "(  ( (DepartureAirport LIKE '%$origin_city%' AND ArrivalLocation LIKE '%$destination_city%') AND (DepartureDate = '$departure_date' ) ) 
                              AND (Capacity >= '$persons') )"; 
            }
        }
    }else
    {
       $condition = " 1='1' "; 
    }
    //echo $condition;
    $this->db->select('*');
    $this->db->from('flight');
    $this->db->where($condition);
    $this->db->order_by('Price', "asc");
    $query = $this->db->get();
    
    if ($query->num_rows() > 0) {
    return $query->result();
    } else {
    return false;
    }
}


// Get 	filters . use $vars to use condition
public function get_filters($vars=array()) {

 if(!empty($vars))
    {  
        $activities     = (isset($vars['activities'])             && ($vars['activities']!=""))            ? $vars['activities'] : "";
        $environment    = (isset($vars['environment'])            && ($vars['environment']!=""))           ? $vars['environment'] : "";
        $weather        = (isset($vars['weather'])                && ($vars['weather']!=""))               ? $vars['weather'] : "";  
        $season         = (isset($vars['season'])                 && ($vars['season']!=""))                ? $vars['season'] : "";
        $continent      = (isset($vars['destination_continent'])  && ($vars['destination_continent']!="")) ? $vars['destination_continent'] : "";
        $destination    = (isset($vars['destination_city'])       && ($vars['destination_city']!=""))      ? $vars['destination_city'] : "";
        
        $age_group      = (isset($vars['age_group'])              && ($vars['age_group']!=""))             ? $vars['age_group'] : "";
        $price_from     = (isset($vars['price_from'])             && ($vars['price_from']!=""))            ? $vars['price_from'] : 0;
        $price_to       = (isset($vars['price_to'])               && ($vars['price_to']!=""))              ? $vars['price_to'] : 0;
        $departure_date = (isset($vars['departure_date'])         && ($vars['departure_date']!=""))        ? $vars['departure_date'] : "";
        $return_date    = (isset($vars['return_date'])            && ($vars['return_date']!=""))           ? $vars['return_date'] : "";
        
       if(($season != "") || ($destination != "") || ($environment != "") || ($continent != "") || 
         ($age_group != "") || ($price_from != "") || ($weather != "") || ($activities != ""))
       { 
        $condition      = "1=1";
        if($season != "")
        { 
          $condition   .=" AND (Season LIKE '%$season%')";
        }
        if($destination != "")
        { 
          $condition   .=" AND (City LIKE '%$destination%')";
        }
        if($environment != "")
        { 
          $condition   .=" AND (Environment LIKE '%$environment%')";
        }
        if($continent != "")
        { 
          $condition   .=" AND (Continent LIKE '%$continent%')";
        }
        if($age_group != "")
        { 
          $condition   .=" AND (Age = '$age_group')";
        }
        if( ($price_from >= 0) && ($price_to > 0) )
        { 
          $condition   .=" AND (ActivityPrice BETWEEN '$price_from' AND '$price_to' )";
        }
        
        if( ($departure_date != "" && $return_date != "") )
        { 
          $condition   .=" AND ( (date1 BETWEEN '$departure_date' AND '$return_date') )";
        }
        /* 
        if( ($return_date != "") )
        { 
          $condition   .=" AND (data2 <z= '$return_date') ";
        }
        
         if($activities != "")
        { 
          $condition   .=" OR (city LIKE '%$activities%') ";
        }*/
        if($weather != "")
        { 
            $condition .= "  AND (MATCH(Weather) AGAINST('$weather' IN BOOLEAN MODE))";
        }
        /*
        if($activities != "")
        { 
            $condition .= "(MATCH(weather) AGAINST('$activities' IN BOOLEAN MODE))  OR ";
        }
        if($activities != "")
        { 
            $condition .= "(MATCH(environment) AGAINST('$activities' IN BOOLEAN MODE))  OR ";
        }*/
        if($activities != "")
        { 
         //prepare condition using MYSQL NATURAL LANGUAGE MODE
         $condition .= " AND (MATCH(Activities) AGAINST('$activities' IN BOOLEAN MODE))  ";
        }
      }else
    {  
       return false;
       
    }   // end if(($season != "") || ($destination != "") || 
        
    }else
    {  
       return false;
       $condition = " 1='1' "; 
    }

    //echo $condition; //die();
    $this->db->select('*');
    $this->db->from('filter');
    $this->db->where($condition);
    $query = $this->db->get(); //print_r($this->db); die();
    if ($query->num_rows() > 0) {
    return $query->result();
    } else {
    return false;
    }
}


// Retrieve chats
public function get_dialogue_knowledge($vars=array()) { 

    if(!empty($vars))
    {  
        $ip_address  = (isset($vars['ip_address']) && ($vars['ip_address']!=""))   ? $vars['ip_address'] : "";
        
        $condition      = "";
        if($ip_address != "")
        {
         $condition .= "((c.DkTo = '$ip_address') OR (c.DkFrom = '$ip_address'))"; //This is to pick chat history of current visitor  
        }else
        {
            return false;
        }
    }else
    {
       $condition = " 1='1' "; 
    }

    //echo $condition;
    $this->db->select('*');
    $this->db->from('dialogue_knowledge c');
    $this->db->where($condition);
    $this->db->order_by('c.	Id,c.DkSent', "ASC");
    $query = $this->db->get();
    //print_r($query->result());die();
    if ($query->num_rows() > 0) {
    return $query->result();
    } else {
    return false;
    }
}


//  Update chat
public function update_dialogue_knowledge($vars=array()) {

    if(!empty($vars))
    {  
        $ip_address  = (isset($vars['ip_address']) && ($vars['ip_address']!=""))   ? $vars['ip_address'] : "";
          
        $condition      = "";
        if($ip_address != "")
        {
         $condition .= "((chat.DkTo = '$ip_address') OR (chat.DkFrom = '$ip_address'))  and DkRecd = '0'"; //This is to update chat history  
        }else
        {
            return false;
        }
    }else
    {
       return false;
    }

    $data = array(
        'DkRecd' => '1'
        );

// Query to update data in database
$this->db->where($condition);
$this->db->update('dialogue_knowledge',$data);
return true;
 
}

// Retrieve replies
public function get_dialogue_rules($vars=array()) { 

    if(!empty($vars))
    {  
        $trigger  = (isset($vars['trigger']) && ($vars['trigger']!=""))   ? $vars['trigger'] : "";
        $stage    = (isset($vars['stage'])   && ($vars['stage']!=""))     ? $vars['stage']   : "";
        $type     = (isset($vars['type'])    && ($vars['type']!=""))      ? $vars['type']    : "";
        
        $condition = " 1='1' "; 
        if($trigger != "")
        {
         $condition .= "AND (DrTrigger LIKE '%$trigger%')"; //This is to pick matching replies
        }
        if($stage != "")
        {
            $condition .= "AND (DrStage = '$stage')";
        }
        if($type != "")
        {
            $condition .= "AND (DrType = '$type')";
        }
    }else
    {
       return false; 
    }

    //echo $condition;
    $this->db->select('*');
    $this->db->from('dialogue_rules');
    $this->db->where($condition);
    $query = $this->db->get();
    //print_r($query->result());die();
    if ($query->num_rows() > 0) {
    return $query->result();
    } else {
    return false;
    }
}

public function save_dialogue_knowledge($data) {

// Query to insert data in database
$this->db->insert('dialogue_knowledge', $data);
if ($this->db->affected_rows() > 0) {
return true;
}
else {
return false;
}

}

//  Update flight capacity
public function update_flight_capacity($vars=array()) { //print_r($vars); die();

    if(!empty($vars))
    {  
        $flight_id    = (isset($vars['p_id'])        && ($vars['p_id']!=""))          ? $vars['p_id']        : "";
        $new_capcity  = (isset($vars['new_capacity']) && ($vars['new_capacity']!=""))   ? $vars['new_capacity'] : "";
          
        $condition      = "";
        if($flight_id != "")
        {
         $condition .= "((flight.FlightID = '$flight_id') )"; //This is to update flight capacity on order
        }else
        {
            return false;
        }
    }else
    {
       return false;
    }

    $data = array(
        'Capacity' => $new_capcity
        );

// Query to update data in database
$this->db->where($condition);
$this->db->update('flight',$data);
$sql = $this->db->last_query();
//print_r($sql); die();
return true;
 
}

//  Update hotel capacity
public function update_hotel_capacity($vars=array()) {

    if(!empty($vars))
    {  
        $hotel_id     = (isset($vars['p_id'])        && ($vars['p_id']!=""))          ? $vars['p_id']        : "";
        $new_capacity  = (isset($vars['new_capacity']) && ($vars['new_capacity']!=""))   ? $vars['new_capacity'] : "";
          
        $condition      = "";
        if($hotel_id != "")
        {
         $condition .= "((hotel.HotelID = '$hotel_id') )"; //This is to update flight capacity on order
        }else
        {
            return false;
        }
    }else
    {
       return false;
    }

    $data = array(
        'NumberOfRooms' => $new_capacity
        );

// Query to update data in database
$this->db->where($condition);
$this->db->update('hotel',$data);
return true;
 
}

// Get all 
public function get_all_cities() { 

    //echo $condition;
    $this->db->distinct('City');
    $this->db->select('City');
    $this->db->from('filter');
    $query_f = $this->db->get();
    $filter_city = $query_f->result();
    
    $this->db->distinct('ArrivalLocation');
    $this->db->select('ArrivalLocation');
    $this->db->from('flight');
    $query_fl = $this->db->get();
    $flight_city = $query_fl->result();
    //print_r(json_decode(json_encode($flight_city)));echo "<br/><br/>";
    
    $this->db->distinct('HotelCity');
    $this->db->select('HotelCity');
    $this->db->from('hotel');
    $query_h = $this->db->get();
    $hotel_city = $query_h->result();

    return array_merge($filter_city,$flight_city,$hotel_city);
    
}

}

?>