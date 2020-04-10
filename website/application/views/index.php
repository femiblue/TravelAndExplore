<?php
$my_base_url = base_url()."index.php/"; //echo $my_base_url;
$rbase_url   = base_url();

$this->session->set_userdata('username', "You"); // Must be already set
/*
$date_var = '28/03/2018';
$date = str_replace('/', '-', $date_var);
echo date('Y-m-d', strtotime($date));
*/
?>
<!DOCTYPE html>

<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Travel and Explore Now</title>
        
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="icon" href="<?php echo $rbase_url; ?>images/favicon.png" type="image/x-icon">
        
        <!-- Google Fonts -->	
        <!--<link href="<?php //echo $rbase_url; ?>css/css" rel="stylesheet">-->
        
        <!-- Bootstrap Stylesheet -->	
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/bootstrap.min.css">
        
        <!-- Font Awesome Stylesheet -->
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/font-awesome.min.css">
            
        <!-- Custom Stylesheets -->	
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/style.css">
        <link rel="stylesheet" id="cpswitch" href="<?php echo $rbase_url; ?>css/orange.css">
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/responsive.css">
    
        <!-- Owl Carousel Stylesheet -->
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/owl.carousel.css">
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/owl.theme.css">
        
        <!-- Flex Slider Stylesheet -->
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/flexslider.css" type="text/css">
        
        <!--Date-Picker Stylesheet-->
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/datepicker.css">
        
        <!-- Magnific Gallery -->
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/magnific-popup.css">
        
        <!-- Color Panel -->
        <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/jquery.colorpanel.css">
        
        <link type="text/css" rel="stylesheet" media="all" href="<?php echo $rbase_url; ?>css/chat.css" />
       
    
       
    </head>
    
        <!--====== LOADER =====-->
        <div class="loader" style="display: none;"></div>
    
    
    	<!--======== SEARCH-OVERLAY =========-->       
        <!--<div class="overlay">
            <a href="javascript:void(0)" id="close-button" class="closebtn">×</a>
            <div class="overlay-content">
                <div class="form-center">
                    <form>
                    	<div class="form-group">
                        	<div class="input-group">
                        		<input type="text" class="form-control" placeholder="Search..." required="">
                            	<span class="input-group-btn"><button type="submit" class="btn"><span><i class="fa fa-search"></i></span></button></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>-->
        
        
        <!--============= TOP-BAR ===========-->
       <div id="top-bar" class="tb-text-white">
            <div class="container">
                <div class="row">          
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div id="info">
                            <ul class="list-unstyled list-inline">
                               
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div id="links">
                            <ul class="list-unstyled list-inline">
                                
                                	<form>
                                    	<ul class="list-inline">
                                        	<li>
                                                <div class="form-group currency">
                                                    
                                                    
                                                </div>
											</li>
                                            
                                            
										</ul>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>			
                </div>
            </div>
        </div>
		
       <nav class="navbar navbar-default main-navbar navbar-custom navbar-white affix" id="mynavbar-1"  style="padding-bottom: 20px; z-index: 300;">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" id="menu-button">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>                        
                    </button>
                    <div class="header-search hidden-lg">
                    	<a href="javascript:void(0)" class="search-button"><span><i class="fa fa-search"></i></span></a>
                    </div>
                    <a href="<?php echo $rbase_url; ?>" class="navbar-brand" ><span><i class="fa fa-plane"></i>TRAVEL AND </span> EXPLORE NOW</a>
                </div> 
                
                <div class="collapse navbar-collapse" id="myNavbar1">
                    
                </div>
            </div>
        </nav>       
        
        
        
		<!--========================= FLEX SLIDER =====================-->
        <section class="flexslider-container" id="flexslider-container-1">

            <div class="flexslider slider" id="slider-1" style="border: 0px solid; height: 440px; ">
           
                <ul class="slides" >
                
                    
                    <li class="item-1" style="background:linear-gradient(rgba(0,0,0,0.0),rgba(0,0,0,0.0)),url(<?php echo $rbase_url; ?>images/homepage-slider-1.jpg) 50% 0%;	background-size:cover;height:100%;">
                    	
                              
                             
                       <!-- <div class=" meta">    
                             
                            <div class="container">
                                 
                                <h2>Travel and Explore Now</h2>
                            </div 
                        </div> -->
                    </li><!-- end item-1 -->
                    
                    <!-- end item-2 -->
                   
                </ul>
                <?php 
                   
                   //$search_type     = "all";
                   //If result does not yield anything
                   if(($this->session->flashdata('result_error')))
                    {
                        $message_error = $this->session->flashdata('result_error');
                        $search_type   = $this->session->flashdata('search_type');
                    }
                
                     //used to control where error displays
                     
                     $err_all         = "active";
                     $err_all_body    = " in active";
                     $err_flight      = "";
                     $err_flight_body = "";
                     $err_hotel       = "";
                     $err_hotel_body  = "";
                     $err_nlp         = "";
                     $err_nlp_body    = "";
           
                     if ( isset($error_message) || isset($message_error) ) 
                     {  //echo "SEARCH TYPE...".$search_type;
                        if(isset($search_type) && ($search_type == "flight"))
                        {
                            $err_all         = "";
                            $err_all_body    = "";
                            $err_flight      = "active";
                            $err_flight_body = " in active";
                            $err_hotel       = "";
                            $err_hotel_body  = "";
                            $err_nlp         = "";
                            $err_nlp_body    = "";
                        }
                        if(isset($search_type) && ($search_type == "hotel"))
                        {
                            $err_all         = "";
                            $err_all_body    = "";
                            $err_flight      = "";
                            $err_flight_body = "";
                            $err_hotel       = "active";
                            $err_hotel_body  = " in active";
                            $err_nlp         = "";
                            $err_nlp_body    = "";
                        }
                        if(isset($search_type) && ($search_type == "nlp"))
                        {
                            $err_all         = "";
                            $err_all_body    = "";
                            $err_flight      = "";
                            $err_flight_body = "";
                            $err_hotel       = "";
                            $err_hotel_body  = "";
                            $err_nlp         = "active";
                            $err_nlp_body    = " in active";
                        }
                     
                     }
                                
                ?>
                <div class="search-tabs" id="search-tabs-1" style="border: 0px solid; z-index: 200; margin-top: -100px; ">
            	<div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                        
                            <ul class="nav nav-tabs center-tabs">
                                <li class="<?php echo $err_all; ?>"><a href="#home" data-toggle="tab"><span><i class="fa fa-home"></i></span><span class="st-text">Home</span></a></li>
                                <li class="<?php echo $err_flight; ?>"><a href="#flights" data-toggle="tab"><span><i class="fa fa-plane"></i></span><span class="st-text">Flights</span></a></li>
                                <li class="<?php echo $err_hotel; ?>"><a href="#hotels" data-toggle="tab"><span><i class="fa fa-building"></i></span><span class="st-text">Hotels</span></a></li>
                                <li class="<?php echo $err_nlp; ?>"><a href="#tours" data-toggle="tab"><span><i class="fa fa-suitcase"></i></span><span class="st-text">Natural Language</span></a></li>
                            </ul>   
        
                            <div class="tab-content" style="height: 210px; border:0px solid;">

                                
                                       
                                <div id="home" class="tab-pane<?php echo $err_all_body; ?>">
                                 <?php
                                   
                                    if (isset($message_error) && ($search_type == "all")) {
                                    echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                    echo '<i class="fa fa-times-circle"></i>'; 
                                    echo $message_error;
                                    echo "</div>";
                                    }
                                ?>
                            
                                <?php
                                    //If there is validation error
                                    //print_r($error_message);
                                    /*
                                    if(($this->session->flashdata['validate_error']))
                                    {
                                        $error_message = $this->session->flashdata['validation_error'];
                                    }*/
                                    if (isset($error_message) && ($search_type == "all")) {
                                    echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                    echo '<i class="fa fa-times-circle"></i>';     
                                    echo $error_message;
                                    echo '</div>'; 
                                    }
                                    //echo validation_errors();
                                  
                                ?>
                                <?php
                                if ( isset($error_message) || isset($message_error) ) {
                                
                                ?>
                                
                                <hr  style="margin-top: -20px;" />
                                
                                <?php
                                
                                    }
                                ?>
                                
                                    <?php echo form_open(''.$my_base_url.'search/search_all'); ?>
                                    <div class="row" style="margin-top: -10px;">
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                                                <div class="row">
                                                
                                                  <div class="col-xs-12 col-sm-6 col-md-6" style="border: 0px solid; width: auto;padding:0px;padding-right: 3px; padding-left: 10px; margin-left:5px;">
                                                        <div class="form-group left-icon">
                                                         
                                                            <input type="radio" name="trip_type" value="1" checked="checked"  />
                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6" style="border: 0px solid; width: auto;padding:0px;padding-top: 8px;">
                                                        <div class="form-group left-icon">
              
                                                            <span style="color: black;border:0px;  " class="form-control">One Way</span>
              
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-12 col-sm-6 col-md-6" style="border: 0px solid; width: auto;padding:0px;padding-right: 3px; padding-left: 10px; margin-left:80px;">
                                                        <div class="form-group left-icon">
                                                         
                                                            <input type="radio" name="trip_type" value="2"  />
                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-6" style="border: 0px solid; width: auto;padding:0px;padding-top: 8px;">
                                                        <div class="form-group left-icon">
              
                                                            <span style="color: black; border:0px; " class="form-control">Return</span>
              
                                                        </div>
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                                <div class="form-group right-icon">
                                                    <select class="form-control" name="destination_continent">
                                                    <option selected value="">Continent</option>
                                                    <option value="Africa">Africa</option>
                                                    <option value="Antarctica">Antarctica</option>
                                                    <option value="Asia">Asia</option>
                                                    <option value="Europe">Europe</option>
                                                    <option value="North America">North America</option>
                                                    <option value="Oceania">Oceania</option>
                                                    <option value="South America">South America</option>
                                                    </select>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-2 col-md-2">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="Environment" name="environment" >
                                                            
                                                        </div>
                                            </div><!-- end columns -->
                                            <div class="col-xs-12 col-sm-2 col-md-2 ">
                                                        <div class="form-group right-icon">
                                                            <select class="form-control" name="season">
                                                            <option selected value="">Season</option>
                                                            <option value="Spring">Spring</option>
                                                            <option value="Autumn">Autumn</option>
                                                            <option value="Summer">Summer</option>
                                                            <option value="Winter">Winter</option>
                                                            
                                                            </select>
                                                            <i class="fa fa-angle-down"></i>
                                                        </div>
                                            </div><!-- end columns -->
                                            <div class="col-xs-12 col-sm-2 col-md-2">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="Weather" name="weather" >

                                                        </div>
                                            </div><!-- end columns -->
                                            
           
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                                                <div class="row">
                                                
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="From" name="origin_city" >
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="To" name="destination_city" >
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                                <div class="form-group right-icon">
                                                    <select name="age_group" class="form-control">
                                                    <option selected value="">Age</option>
                                                    <option value="Infant">Infant</option>
                                                    <option value="Toddler">Toddler</option>
                                                    <option value="Play-age">Play-age</option>
                                                    <option value="Primary-school-age">Primary-school-age</option>
                                                    <option value="Adolescence">Adolescence</option>
                                                    <option value="Young-adult">Young-adult</option>
                                                    <option value="Middle-adult">Middle-adult</option>
                                                    <option value="Senior">Senior</option>
                                                    </select>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                                                <div class="row">
                                                
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="Activity" name="activities" >
                                                           
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="Price From" name="price_from" style="padding-left: 2px; padding-right: 2px;font-size: 9px;" >
                                                            
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-6 col-sm-3 col-md-3">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="Price To"  name="price_to" style="padding-left: 2px; padding-right: 2px;font-size: 9px;" >
                                                           
                                                        </div>
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 search-btn">
                                                <button class="btn btn-orange">Search</button>
                                            </div><!-- end columns -->
                                            
                                        </div>
                                        <div class="row">
                                            
                                           
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                                                <div class="row">
                                                
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control dpd1" placeholder="Departure" name="departure_date" >
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control dpd2" placeholder="Return" name="return_date" >
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            
                                            
                                           
                                            
                                        </div><!-- end columns -->
                                    <?php echo form_close(); ?>
                                </div><!-- end home -->
                                
                                
                                <div id="flights" class="tab-pane<?php echo $err_flight_body; ?>">
                                
                                     <?php
                                       //If result does not yield anything
                                       if(($this->session->flashdata('result_error')))
                                        {
                                            $message_error = $this->session->flashdata('result_error');
                                        }
                                        if (isset($message_error) && ($search_type == "flight")) {
                                        echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                        echo '<i class="fa fa-times-circle"></i>'; 
                                        echo $message_error;
                                        echo "</div>";
                                        }
                                    ?>
                                
                                    <?php
                                        //If there is validation error
                                        //print_r($error_message);
                                        /*
                                        if(($this->session->flashdata['validate_error']))
                                        {
                                            $error_message = $this->session->flashdata['validation_error'];
                                        }*/
                                        if (isset($error_message) && ($search_type == "flight")) {
                                        echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                        echo '<i class="fa fa-times-circle"></i>';     
                                        echo $error_message;
                                        echo '</div>'; 
                                        }
                                        //echo validation_errors();
                                      
                                    ?>
                                    <?php
                                    if ( isset($error_message) || isset($message_error) ) {
                                    
                                    ?>
                                    
                                    <hr  style="margin-top: -20px;" />
                                    
                                    <?php
                                    
                                        }
                                    ?>
                                    <?php echo form_open(''.$my_base_url.'search/search_flights'); ?> 
                                        <div class="row">

                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                                                <div class="row">
                                                
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="From" name="origin_city" >
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-12 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control" placeholder="To" name="destination_city" >
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                                                <div class="row">
                                                
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control dpd1" placeholder="Departure " name="departure_date" >
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <div class="form-group left-icon">
                                                            <input type="text" class="form-control dpd2" placeholder="Return Date" name="return_date" >
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                                <div class="form-group right-icon">
                                                    <select class="form-control" name="persons">
                                                        <option selected value="1">No of Persons</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                    </select>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 search-btn">
                                                <button class="btn btn-orange">Search</button>
                                            </div><!-- end columns -->
                                            
                                        </div><!-- end row -->
                                    <?php echo form_close(); ?>
                                </div><!-- end flights -->
                                
                                <div id="hotels" class="tab-pane<?php echo $err_hotel_body; ?>">
                                  
                                    <?php
                                   //If result does not yield anything
                                   if(($this->session->flashdata('result_error')))
                                    {
                                        $message_error = $this->session->flashdata('result_error');
                                    }
                                    if (isset($message_error) && ($search_type == "hotel")) {
                                    echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                    echo '<i class="fa fa-times-circle"></i>'; 
                                    echo $message_error;
                                    echo "</div>";
                                    }
                                ?>
                            
                                <?php
                                    //If there is validation error
                                    //print_r($error_message);
                                    /*
                                    if(($this->session->flashdata['validate_error']))
                                    {
                                        $error_message = $this->session->flashdata['validation_error'];
                                    }*/
                                    if (isset($error_message) && ($search_type == "hotel")) {
                                    echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                    echo '<i class="fa fa-times-circle"></i>';     
                                    echo $error_message;
                                    echo '</div>'; 
                                    }
                                    //echo validation_errors();
                                  
                                ?>
                                <?php
                                if ( isset($error_message) || isset($message_error) ) {
                                 
                                
                                ?>
                                
                                <hr  style="margin-top: -20px;" />
                                
                                <?php
                                
                                    }
                                ?>
                                   <?php echo form_open(''.$my_base_url.'search/search_hotels'); ?> 
                                        <div class="row">
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
                                                <div class="row">
                                                <div class="col-xs-12 col-sm-12">
                                                        <div class="form-group right-icon">
                                                            <input type="text" class="form-control" placeholder="Location" name="city_or_hotel" >
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                
                                                    
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
                                                <div class="row">
                                                
                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                        <div class="form-group right-icon">
                                                            <select class="form-control" name="rooms">
                                                                <option selected value="1">No of Rooms</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                            </select>
                                                            <i class="fa fa-angle-down"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                        <div class="form-group right-icon">
                                                            <select class="form-control" name="persons">
                                                                <option selected value="1">No of Persons</option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                            </select>
                                                            <i class="fa fa-angle-down"></i>
                                                        </div>
                                                    </div><!-- end columns -->
                                                    
                                                    
                                                    
                                                </div><!-- end row -->
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
                                                <div class="row">
                                            
                                                    <div class="col-xs-12 col-sm-6">
                                                        <!--<div class="form-group left-icon">
                                                            <input type="text" class="form-control dpd1" placeholder="Check In" name="check_in_date" >
                                                            <i class="fa fa-calendar"></i>
                                                        </div>-->
                                                    </div><!-- end columns -->
                                                    
                                                    <div class="col-xs-12 col-sm-6">
                                                        <!--<div class="form-group left-icon">
                                                            <input type="text" class="form-control dpd2" placeholder="Check Out" name="check_out_date" >
                                                            <i class="fa fa-calendar"></i>
                                                        </div>-->
                                                    </div><!-- end columns -->
        
                                                </div><!-- end row -->								
                                            </div><!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 search-btn">
                                                <button class="btn btn-orange">Search</button>
                                            </div><!-- end columns -->
                                            
                                        </div><!-- end row -->
                                    <?php echo form_close(); ?>
                                </div><!-- end hotels -->

                                <div id="tours" class="tab-pane<?php echo $err_nlp_body; ?>">
                                
                                    <?php
                                   //If result does not yield anything
                                   if(($this->session->flashdata('result_error')))
                                    {
                                        $message_error = $this->session->flashdata('result_error');
                                    }
                                    if (isset($message_error) && ($search_type == "nlp")) {
                                    echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                    echo '<i class="fa fa-times-circle"></i>'; 
                                    echo $message_error;
                                    echo "</div>";
                                    }
                                ?>
                            
                                <?php
                                    //If there is validation error
                                    //print_r($error_message);
                                    /*
                                    if(($this->session->flashdata['validate_error']))
                                    {
                                        $error_message = $this->session->flashdata['validation_error'];
                                    }*/
                                    if (isset($error_message) && ($search_type == "nlp")) {
                                    echo '<div class="isa_error" style="margin-bottom:30px; margin-top:-20px;">';
                                    echo '<i class="fa fa-times-circle"></i>';     
                                    echo $error_message;
                                    echo '</div>'; 
                                    }
                                    //echo validation_errors();
                                  
                                ?>
                                <?php
                                if ( isset($error_message) || isset($message_error) ) {
                                
                                ?>
                                
                                <hr  style="margin-top: -20px;" />
                                
                                <?php
                                
                                    }
                                ?>
                                    <?php echo form_open(''.$my_base_url.'search/natural_language_search'); ?>
                                        <div class="row">
                                        
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group left-icon">
                                                    <input type="text" name="activities" class="form-control" placeholder="Activity you want from your holiday" />
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                            </div><!-- end columns -->
                                            
                                            
                                            
                                            <!-- end columns -->
                                            
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2 search-btn">
                                                <button class="btn btn-orange">Search</button>
                                            </div><!-- end columns -->
                                            
                                        </div><!-- end row -->
                                    <?php echo form_close(); ?>
                                </div><!-- end tours -->
                         

                                
                                
                            </div><!-- end tab-content -->
                            
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div>
            </div><!-- end slider -->
            
            <!-- end search-tabs -->
            
        </section><!-- end flexslider-container -->
        
        
        <!--=============== All OFFERS ===============-->
        <?php  
          if(!empty($a_activities_result) || !empty($a_flight_result) || !empty($a_hotel_result))
          { //Activities related search
           if(isset($a_flight_result))
           {
             //print "<pre>"; print_r($a_flight_result); print "</pre>";
           }
           if(isset($a_hotel_result))
           {
             //print "<pre>"; print_r($a_hotel_result); print "</pre>";
           }
           if(isset($a_activities_result))
           {
            //print "<pre>"; print_r($a_activities_result); print "</pre>";//die();
            }
        ?>
        <section id="hotel-offers" class="section-padding" style="margin-top: -310px;">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    	<div class="page-heading" style="border: 0px solid; margin-top:-80px;">
                         <!--<h3><?php //echo $result_page_title ?>......</h3>-->
                         <?php
                            if (isset($message_success)) {
                            echo '<div class="isa_success">';
                            echo '<i class="fa fa-check"></i>'; 
                            echo $message_success;
                            echo "</div>";
                            }
                            
                           ?>
                        
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
                        <div id="tabs" style="border: 1px solid #009900; padding:10px; background-color: #009900;">
                          <ul style="border: 1px solid #009900; background-color: #009900 ;border-bottom: 1px solid #F6F6F6; padding-left: 10px;">
                            <?php if(!empty($a_flight_result)) { ?>
                            <li><a href="#tabs-1" style="border: 0px solid #fff; "><span class="fa fa-plane" style="color:#008100 ;"></span> Flights (<?php if(!empty($a_flight_result)) {echo count($a_flight_result);}else{ echo "0";} ?>)</a></li>
                            <?php
                             }
                           if(!empty($a_activities_result)){
                           ?> 
                            <li><a href="#tabs-3" style="border: 0px solid #fff;"><span class="fa fa-thumbs-o-up" style="color:#008100 ;"></span> Activities (<?php echo count($a_activities_result) ?>)</a></li>
                            
                           <?php
                             }
                           if(!empty($a_hotel_result)){
                           ?>  
                            <li><a href="#tabs-2" style="border: 0px solid #fff;"><span class="fa fa-hotel" style="color:#008100 ;"></span> Hotels (<?php if(!empty($a_hotel_result)) { echo count($a_hotel_result);}else{ echo "0";} ?>)</a></li>
                          <?php
                             }
                           
                           ?> 
                          
                          </ul>
                          <div id="tabs-3" style="background-color: #FFFFFF;">
                           <?php
                            if(!empty($a_activities_result))
                            { 
                           ?>
                           <?php
                              $activity_view_counter = 0;
                              foreach($a_activities_result as $activities_val)
                              {
                             
                           ?>
                            <div style="padding: 10px; border-bottom: 1px solid #DDDDDD;">
                           <!-- <div style=" display: inline-table; margin-right: 12px;width: 20%; vertical-align: top;">
                            <img src="../../images/flight_default.jpg" style="width: 280px; height: 190px;"/>
                            </div>-->
                            
                            <div style=" display: inline-block; margin-right: 12px; border-left: 1px solid #DDD; padding: 10px; width: 70%;">
                            <h4><?php echo $a_activities_result[$activity_view_counter]->City; ?></h4>
                            <p style="font-size: 12px; word-wrap: normal; color:red;"><i><?php echo $a_activities_result[$activity_view_counter]->Age; ?>,
                            <?php echo $a_activities_result[$activity_view_counter]->Country; ?>,<?php echo $a_activities_result[$activity_view_counter]->Continent; ?> | 
                            <span style="color: blue;">Event Duration:<?php echo date("Y-m-d",strtotime($a_activities_result[$activity_view_counter]->Date1)); ?> - <?php echo date("Y-m-d",strtotime($a_activities_result[$activity_view_counter]->Date2)); ?> </span></i></p>
                            <p style="font-size: 12px;" >
                            <?php echo substr($a_activities_result[$activity_view_counter]->Activities,0,300) ?>...
                            
                            </p>
                            <hr />
                            <h4>Weathe Condition (<?php echo $a_activities_result[$activity_view_counter]->Season; ?>)</h4>
                            <p style="font-size: 12px;" >
                            <?php echo $a_activities_result[$activity_view_counter]->Weather; ?>
                            
                            
                            </p>
                            </div>
                      </div>
                      
                      <?php
                           $activity_view_counter++;
                          }
                        }
                      ?>
                          </div>
                          <div id="tabs-1" style="background-color: #FFFFFF;">
                           <?php
                            if(!empty($a_flight_result))
                            {
                           ?>
                           <?php
                              $flight_view_counter = 0;
                              foreach($a_flight_result as $flight_val)
                              {
                             
                           ?>
                              <table style="width: 100%; margin-bottom: 20px;">
                                <tr>
                                	<!--<td style="padding: 10px;width: 15%;"><img src="../../images/flight_default.jpg" style="width: 120px; height: 100px; border: 1px solid #000;"/></td>-->
                                    <td style="text-align: center;width: 20%;background-color: #eee; padding:10px;">
                                    <p style="color: blue; font-weight: bold; font-size: 12px;"><?php echo $a_flight_result[$flight_view_counter]->Company ?></p>
                                    <p style="color: #BBBBBB; font-weight: bold; font-size: 16px;"><span class="fa fa-plane"></span></p>
                                    <p style="color: #000; font-weight: bold; font-size: 12px;"><?php echo date("Y-m-d",strtotime($a_flight_result[$flight_view_counter]->DepartureDate)); ?></p>
                                    </td>
                                    <td style="padding: 10px;width: 20%; text-align: right;vertical-align: middle;"> 
                                    <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($a_flight_result[$flight_view_counter]->DepartureTime)); ?></p>
                                    <p style="color: blue;"><?php echo $a_flight_result[$flight_view_counter]->DepartureAirport ?></p>
                                    </td>
                                    
                                    <td><p style="color: #BBBBBB; font-weight: bold; font-size: 12px;text-align: center;">&nbsp;&nbsp;&rarr;&nbsp;&nbsp;<!--&nbsp;&nbsp;o----&nbsp;No Stops&nbsp;----o&nbsp;&nbsp;--></p></td>
                                    
                                    <td  style="padding: 10px;width:20%; text-align: left;vertical-align: middle;">
                                    <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($a_flight_result[$flight_view_counter]->ArrivalTime)); ?></p>
                                    <p style="color: blue;"><?php echo $a_flight_result[$flight_view_counter]->ArrivalLocation ?></p>
                                    </td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td>
                                    <p style="color: #ff0000; font-weight: bold; font-size: 14px;">&nbsp;&pound;<?php echo $a_flight_result[$flight_view_counter]->Price ?></p>
                                    
                                    </td>
                                    <td style="width: 20%; border: 0px solid; vertical-align: middle;">
                                    <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                                    <input type="hidden" name="p_id" value="<?php echo $a_flight_result[$flight_view_counter]->FlightID ?>" />
                                    <input type="hidden" name="capacity" value="<?php echo $a_flight_result[$flight_view_counter]->Capacity ?>" />
                                    <input type="hidden" name="amt" value="<?php echo $a_flight_result[$flight_view_counter]->Price ?>" />
                                    <input type="hidden" name="product" value="Flight from <?php echo $a_flight_result[$flight_view_counter]->DepartureAirport ?> to <?php echo $a_flight_result[$flight_view_counter]->ArrivalLocation ?> with <?php echo $a_flight_result[$flight_view_counter]->Company ?>" />
                                    <input type="submit" value="Book Now" style="padding: 6px; background-color: red; color: white; border:0px; font-size: 12px; font-weight: bold;"/>
                                    <?php echo form_close(); ?>
                                    
                                    </td>
                                </tr>
                               </table>
                           <?php 
                       
                       
                           $flight_view_counter++;
                            }
                         
                              
                             }
                             
                           ?>
                          </div>
                          <div id="tabs-2" style="background-color: #FFFFFF;">
                           <?php
                            if(!empty($a_hotel_result))
                            {
                           ?>
                           <?php
                              $hotel_view_counter = 0;
                              foreach($a_hotel_result as $hotel_val)
                              {
                             
                           ?>
                            <div style="padding: 10px; border-bottom: 1px solid #DDDDDD;">
                           <!-- <div style=" display: inline-table; margin-right: 12px;width: 20%; vertical-align: top;">
                            <img src="../../images/flight_default.jpg" style="width: 240px; height: 200px;"/>
                            </div>-->
                            <div style=" display: inline-block; margin-right: 12px; vertical-align: top; padding: 4px; width: 25%;">
                            <h4><?php echo $a_hotel_result[$hotel_view_counter]->Type ?></h4>
      
                            <h5 style="font-size: 24px; margin-top: 5px; text-align: left; font-family: Arial; font-weight: bold; ">&pound;<?php echo $a_hotel_result[$hotel_view_counter]->Price ?></h5>
                             <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                                <input type="hidden" name="capacity" value="<?php echo $a_hotel_result[$hotel_view_counter]->NumberOfRooms ?>" />
                                <input type="hidden" name="p_id" value="<?php echo $a_hotel_result[$hotel_view_counter]->HotelID ?>" />
                                <input type="hidden" name="amt" value="<?php echo $a_hotel_result[$hotel_view_counter]->Price ?>" />
                                <input type="hidden" name="product" value="Hotel Room in <?php echo $a_hotel_result[$hotel_view_counter]->HotelCity  ?> with <?php echo $a_hotel_result[$hotel_view_counter]->Title ?>" />
                                <input type="submit" value="Book Now" style="padding: 10px; background-color: red; color: white; border:0px; font-size: 16px; font-weight: bold; width: 100%;"/>
                                <?php echo form_close(); ?>
                            </div>
                            <div style=" display: inline-block; margin-right: 12px; border-left: 1px solid #DDD; padding: 10px; width: 30%;">
                            <h4><?php echo $a_hotel_result[$hotel_view_counter]->Title ?></h4>
                            <p style="font-size: 12px; word-wrap: normal; color:red;"><i><?php echo $a_hotel_result[$hotel_view_counter]->Address ?></i></p>
                            <p style="font-size: 12px;" >
                            <?php echo substr($a_hotel_result[$hotel_view_counter]->HotelCity,0,300) ?>...
                            
                            </p>
                            </div>
                          </div>
                            
                           <?php
                              $hotel_view_counter++;
                                  }
                                }
                           ?>
                          </div>
                          
                          
                        </div>
                        <!-- end owl-hotel-offers -->
                        
                        
                    </div><!-- end columns -->
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end hotel-offers -->
        
        <?php
        
          }
          
        ?>

        <!--=============== FLIGHT OFFERS ===============-->
        <?php  
          if(!empty($flight_data_by_price))
          { //Activities related search
           if(isset($flight_data_by_price))
           {
             //print "<pre>"; print_r($flight_data_by_price); print "</pre>";
           }
           if(isset($flight_data_by_duration))
           {
             //print "<pre>"; print_r($flight_data_by_duration); print "</pre>";
           }
        ?> 
        <section id="hotel-offers" class="section-padding" style="margin-top: -310px;">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    	<div class="page-heading" style="margin-top: -80px;">
                    	<h3><?php echo $result_page_title ?>......</h3>
                         <?php
                            if (isset($message_success)) {
                            echo '<div class="isa_success">';
                            echo '<i class="fa fa-check"></i>'; 
                            echo $message_success;
                            echo "</div>";
                            }
                           ?>
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
        
            <div id="tabs" style="border: 1px solid #009900; padding:10px; background-color: #009900">
              <ul style="border: 1px solid #009900; background-color: #009900 ;border-bottom: 1px solid #F6F6F6; padding-left: 10px;">
                <li><a href="#tabs-1"><span class="glyphicon glyphicon-plane" style="color:#008100 ;"></span> Flights Sorted by Duration(<?php echo count($flight_data_by_duration); ?>)</a></li>
                <li><a href="#tabs-2"><span class="glyphicon glyphicon-plane" style="color:#008100 ;"></span> Flight Sorted by Price (<?php echo count($flight_data_by_price) ?>)</a></li>
                
              </ul>
              <div id="tabs-1" style="background-color: #FFFFFF;">
                       <?php
                        if(!empty($flight_data_by_duration))
                        {
                       ?>
                       <?php
                          $flight_view_counter_d = 0;
                          foreach($flight_data_by_duration as $flight_val_d)
                          {
                         
                       ?>
                      
                       <div style="padding: 6px; border: 1px solid #ddd; width: 100%;">
                       <table style="width: 100%;">
                        <tr>
                        	<!--<td style="padding: 10px;width: 15%;"><img src="../../images/flight_default.jpg" style="width: 120px; height: 100px; border: 1px solid #000;"/></td>-->
                            <td style="text-align: center;width: 20%; background-color: #eee; padding:10px;">
                            <p style="color: blue; font-weight: bold; font-size: 12px;"><?php echo $flight_data_by_duration[$flight_view_counter_d]->Company ?></p>
                            <p style="color: #BBBBBB; font-weight: bold; font-size: 14px;"><span class="fa fa-plane"></span></p>
                            <p style="color: #000; font-weight: bold; font-size: 12px;"><?php echo date("Y-m-d",strtotime($flight_data_by_duration[$flight_view_counter_d]->DepartureDate)); ?></p>
                            </td>
                            <td style="padding: 10px;width: 20%; text-align: right; vertical-align: middle;"> 
                            <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($flight_data_by_duration[$flight_view_counter_d]->DepartureTime)); ?></p>
                            <p style="color: blue;"><?php echo $flight_data_by_duration[$flight_view_counter_d]->DepartureAirport ?></p>
                            </td>
                            
                            <td><p style="color: #BBBBBB; font-weight: bold; font-size: 12px;text-align: center;">&nbsp;&nbsp;&rarr;&nbsp;&nbsp;<!--&nbsp;&nbsp;o----&nbsp;No Stops&nbsp;----o&nbsp;&nbsp;--></p></td>
                            
                            <td  style="padding: 10px;width: 20%; text-align: left; vertical-align: middle;">
                            <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($flight_data_by_duration[$flight_view_counter_d]->ArrivalTime)); ?></p>
                            <p style="color: blue;"><?php echo $flight_data_by_duration[$flight_view_counter_d]->ArrivalLocation ?></p>
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td>
                            <p style="color: #ff0000; font-weight: bold; font-size: 14px;">&nbsp;&pound;<?php echo $flight_data_by_duration[$flight_view_counter_d]->Price ?></p>
                            
                            </td>
                            <td style="width: 15%; border: 0px solid; vertical-align: middle;">
                            <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                             <input type="hidden" name="p_id" value="<?php echo $flight_data_by_duration[$flight_view_counter_d]->FlightID ?>" />
                             <input type="hidden" name="capacity" value="<?php echo $flight_data_by_duration[$flight_view_counter_d]->Capacity ?>" />
                            <input type="hidden" name="amt" value="<?php echo $flight_data_by_duration[$flight_view_counter_d]->Price ?>" />
                            <input type="hidden" name="product" value="Flight from <?php echo $flight_data_by_duration[$flight_view_counter_d]->DepartureAirport ?> to <?php echo $flight_data_by_duration[$flight_view_counter_d]->ArrivalLocation ?> with <?php echo $flight_data_by_duration[$flight_view_counter_d]->Company ?>" />
                            <input type="submit" value="Book Now" style="padding: 6px; background-color: red; color: white; border:0px; font-size: 12px; font-weight: bold;"/>
                            <?php echo form_close(); ?>
                            
                            </td>
                        </tr>
                       </table>
                       </div>
                       <?php 
                       
                       
                           $flight_view_counter_d++;
                            }
                         
                          
                         }
                         
                       ?>
                       
                      </div>
                      
                      <div id="tabs-2" style="background-color: #FFFFFF;">
                      <?php
                        if(!empty($flight_data_by_price))
                        {
                       ?>
                       <?php
                          $flight_view_counter_p = 0;
                          foreach($flight_data_by_price as $flight_val_p)
                          {
                         
                       ?>
                      <div style="padding: 6px; border: 1px solid #ddd; width: 100%;">
                       <table style="width: 100%;">
                        <tr>
                        	<!--<td style="padding: 10px;width: 15%;"><img src="../../images/flight_default.jpg" style="width: 120px; height: 100px; border: 1px solid #000;"/></td>-->
                            <td style="text-align: center;width: 20%;background-color: #eee; padding:10px;">
                            <p style="color: blue; font-weight: bold; font-size: 12px;"><?php echo $flight_data_by_price[$flight_view_counter_p]->Company ?></p>
                            <p style="color: #BBBBBB; font-weight: bold; font-size: 12px;"><span class="fa fa-plane"></span></p>
                            <p style="color: #000; font-weight: bold; font-size: 12px;"><?php echo date("Y-m-d",strtotime($flight_data_by_price[$flight_view_counter_p]->DepartureDate)); ?></p>
                            </td>
                            <td style="padding: 10px;width: 20%; text-align: right;vertical-align: middle;"> 
                            <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($flight_data_by_price[$flight_view_counter_p]->DepartureTime)); ?></p>
                            <p style="color: blue;"><?php echo $flight_data_by_price[$flight_view_counter_p]->DepartureAirport ?></p>
                            </td>
                            
                            <td><p style="color: #BBBBBB; font-weight: bold; font-size: 12px;text-align: center;">&nbsp;&nbsp;&rarr;&nbsp;&nbsp;<!--&nbsp;&nbsp;o----&nbsp;No Stops&nbsp;----o&nbsp;&nbsp;--></p></td>
                            
                            <td  style="padding: 10px;width: 20%; text-align: left;vertical-align: middle;">
                            <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($flight_data_by_price[$flight_view_counter_p]->ArrivalTime)); ?></p>
                            <p style="color: blue;"><?php echo $flight_data_by_price[$flight_view_counter_p]->ArrivalLocation ?></p>
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td>
                            <p style="color: #ff0000; font-weight: bold; font-size: 14px;">&nbsp;&pound;<?php echo $flight_data_by_price[$flight_view_counter_p]->Price ?></p>
                            
                            </td>
                            <td style="width: 15%; border: 0px solid; vertical-align: middle;">
                            <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                             <input type="hidden" name="p_id" value="<?php echo $flight_data_by_price[$flight_view_counter_p]->FlightID ?>" />
                             <input type="hidden" name="capacity" value="<?php echo $flight_data_by_price[$flight_view_counter_p]->Capacity ?>" />
                            <input type="hidden" name="amt" value="<?php echo $flight_data_by_price[$flight_view_counter_p]->Price ?>" />
                            <input type="hidden" name="product" value="Flight from <?php echo $flight_data_by_price[$flight_view_counter_p]->DepartureAirport ?> to <?php echo $flight_data_by_price[$flight_view_counter_p]->ArrivalLocation ?> with <?php echo $flight_data_by_price[$flight_view_counter_p]->Company ?>" />
                            <input type="submit" value="Book Now" style="padding: 6px; background-color: red; color: white; border:0px; font-size: 12px; font-weight: bold;"/>
                            <?php echo form_close(); ?>
                            
                            </td>
                        </tr>
                       </table>
                       </div>
                      <?php
                           $flight_view_counter_p++;
                          }
                        }
                      ?>
                      </div>

        </div>
        </div><!-- end columns -->
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end flights-offers -->
        <?php
        
          }
          
        ?>
        
        
         <!--=============== HOTEL OFFERS ===============-->
        <?php  
          if(!empty($hotel_result_by_ranking))
          { //Activities related search
           if(isset($hotel_result_by_ranking))
           {
             //print "<pre>"; print_r($hotel_result_by_ranking); print "</pre>";
           }
           if(isset($hotel_result_by_price))
           {
             //print "<pre>"; print_r($hotel_result_by_price); print "</pre>";
           }
        ?> 
        <section id="hotel-offers" class="section-padding" style="margin-top: -310px;">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    	<div class="page-heading" style="margin-top: -70px;">
                    	<h3><?php echo $result_page_title ?>......</h3>
                         <?php
                            if (isset($message_success)) {
                            echo '<div class="isa_success">';
                            echo '<i class="fa fa-check"></i>'; 
                            echo $message_success;
                            echo "</div>";
                            }
                           ?>
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
                     
                     <div id="tabs"  style="border: 1px solid #009900; padding:10px; background-color: #009900;">
                      <ul style="border: 1px solid #009900; background-color: #009900 ;border-bottom: 1px solid #F6F6F6; padding-left: 10px;">
                        <li><a href="#tabs-1"><span class="fa fa-hotel" style="color:#008100 ;"></span>  Hotels sorted by Rating (<?php echo count($hotel_result_by_ranking); ?>)</a></li>
                        <li><a href="#tabs-2"><span class="fa fa-hotel" style="color:#008100 ;"></span>  Hotels sorted by Price(<?php echo count($hotel_result_by_price) ?>)</a></li>
                        
                      </ul>
                      <div id="tabs-1" style="background: #FFFFFF">
                       <?php
                        if(!empty($hotel_result_by_ranking))
                        {
                       ?>
                       <?php
                          $hotel_view_counter_r = 0;
                          foreach($hotel_result_by_ranking as $hotel_val_r)
                          {
                         
                       ?>
                      
                       <div style="padding: 10px; border-bottom: 1px solid #DDDDDD;">
                        <!--<div style=" display: inline-table; margin-right: 12px;width: 20%; vertical-align: top;">
                        <img src="../../images/flight_default.jpg" style="width: 240px; height: 200px;"/>
                        </div>-->
                        <div style=" display: inline-block; margin-right: 12px; vertical-align: top; padding: 4px; width: 25%;">
                        <h4><?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->Type ?></h4>
  
                        <h5 style="font-size: 24px; margin-top: 5px; text-align: left; font-family: Arial; font-weight: bold; ">&pound;<?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->Price ?></h5>
                         <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                            <input type="hidden" name="capacity" value="<?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->NumberOfRooms ?>" />
                            <input type="hidden" name="p_id" value="<?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->HotelID ?>" />
                            <input type="hidden" name="amt" value="<?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->Price ?>" />
                            <input type="hidden" name="product" value="Hotel room in <?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->HotelCity ?> with <?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->Title ?>" />
                            <input type="submit" value="Book Now" style="padding: 10px; background-color: red; color: white; border:0px; font-size: 16px; font-weight: bold; width: 100%;"/>
                            <?php echo form_close(); ?>
                        </div>
                        <div style=" display: inline-block; margin-right: 12px; border-left: 1px solid #DDD; padding: 10px; width: 30%;">
                        <h4><?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->Title ?></h4>
                        <p style="font-size: 12px; word-wrap: normal; color:red;"><i><?php echo $hotel_result_by_ranking[$hotel_view_counter_r]->HotelCity ?></i></p>
                        <p style="font-size: 12px;" >
                        <?php echo substr($hotel_result_by_ranking[$hotel_view_counter_r]->Address,0,300) ?>...
                        
                        </p>
                        </div>
                      </div>
                       <?php 
                       
                       
                           $hotel_view_counter_r++;
                            }
                         
                          
                         }
                         
                       ?>
                       
                      </div>
                      <div id="tabs-2" style="background-color: #FFFFFF;">
                      <?php
                        if(!empty($hotel_result_by_price))
                        {
                       ?>
                       <?php
                          $hotel_view_counter_p = 0;
                          foreach($hotel_result_by_price as $hotel_val_p)
                          {
                         
                       ?>
                      <div style="padding: 10px; border-bottom: 1px solid #DDDDDD;">
                        <!--<div style=" display: inline-table; margin-right: 12px;width: 20%; vertical-align: top;">
                        <img src="../../images/flight_default.jpg" style="width: 240px; height: 200px;"/>
                        </div>-->
                        <div style=" display: inline-block; margin-right: 12px; vertical-align: top; padding: 4px; width: 25%;">
                        <h4><?php echo $hotel_result_by_price[$hotel_view_counter_p]->Type ?></h4>
  
                        <h5 style="font-size: 24px; margin-top: 5px; text-align: left; font-family: Arial; font-weight: bold; ">&pound;<?php echo $hotel_result_by_price[$hotel_view_counter_p]->Price ?></h5>
                         <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                            <input type="hidden" name="capacity" value="<?php echo $hotel_result_by_price[$hotel_view_counter_p]->NumberOfRooms ?>" />
                            <input type="hidden" name="p_id" value="<?php echo $hotel_result_by_price[$hotel_view_counter_p]->HotelID ?>" />
                            <input type="hidden" name="amt" value="<?php echo $hotel_result_by_price[$hotel_view_counter_p]->Price ?>" />
                            <input type="hidden" name="product" value="Hotel room in <?php echo $hotel_result_by_price[$hotel_view_counter_p]->HotelCity ?> with <?php echo $hotel_result_by_price[$hotel_view_counter_p]->Title ?>" />
                            <input type="submit" value="Book Now" style="padding: 10px; background-color: red; color: white; border:0px; font-size: 16px; font-weight: bold; width: 100%;"/>
                            <?php echo form_close(); ?>
                        </div>
                        <div style=" display: inline-block; margin-right: 12px; border-left: 1px solid #DDD; padding: 10px; width: 30%;">
                        <h4><?php echo $hotel_result_by_price[$hotel_view_counter_p]->Title ?></h4>
                        <p style="font-size: 12px; word-wrap: normal; color:red;"><i><?php echo $hotel_result_by_price[$hotel_view_counter_p]->HotelCity ?></i></p>
                        <p style="font-size: 12px;" >
                        <?php echo substr($hotel_result_by_price[$hotel_view_counter_p]->Address,0,300) ?>...
                        
                        </p>
                        </div>
                      </div>
                      <?php
                           $hotel_view_counter_p++;
                          }
                        }
                      ?>
                      </div>
                       
  
                    
                    
				</div>
                     
                        
                 </div><!-- end columns -->
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end flights-offers -->
        <?php
        
          }
          
        ?>
        
        
                <!--=============== NATURAL LANG OFFERS ===============-->
        <?php  
          if(!empty($activities_result))
          { //Activities related search
           if(isset($flight_result))
           {
            // print "<pre>"; print_r($flight_result); print "</pre>";
           }
           if(isset($hotel_result))
           {
             //print "<pre>"; print_r($a_hotel_result); print "</pre>";
           }
           if(isset($activities_result))
           {
            //print "<pre>"; print_r($activities_result); print "</pre>";//die();
            }
        ?>
        <section id="hotel-offers" class="section-padding" style="margin-top: -310px;">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    	<div class="page-heading" style="margin-top: -70px;">
                         <h3><?php echo $result_page_title ?>......</h3>
                         <?php
                            if (isset($message_success)) {
                            echo '<div class="isa_success">';
                            echo '<i class="fa fa-check"></i>'; 
                            echo $message_success;
                            echo "</div>";
                            }
                           ?>
                          <body id="main-homepage">
                           
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
                        <div id="tabs" style="border: 1px solid #009900; padding:10px; background-color: #009900;">
                          <ul style="border: 1px solid #009900; background-color: #009900 ;border-bottom: 1px solid #F6F6F6; padding-left: 10px;">
                            <li><a href="#tabs-1" style="border: 0px solid #fff; "><span class="fa fa-plane" style="color:#008100 ;"></span> Flights (<?php  if(!empty($flight_result)) {echo count($flight_result); }else{ echo "0";} ?>)</a></li>
                            <li><a href="#tabs-2" style="border: 0px solid #fff;"><span class="fa fa-hotel" style="color:#008100 ;"></span> Hotels (<?php if(!empty($hotel_result)) {echo count($hotel_result); }else{ echo "0";} ?>)</a></li>
                            <li><a href="#tabs-3" style="border: 0px solid #fff;"><span class="fa fa-thumbs-o-up" style="color:#008100 ;"></span> Activities (<?php echo count($activities_result) ?>)</a></li>
                          </ul>
                          <div id="tabs-1" style="background-color: #FFFFFF;">
                           <?php
                            if(!empty($flight_result))
                            {
                           ?>
                           <?php
                              $flight_view_counter = 0;
                              foreach($flight_result as $flight_val)
                              {
                             
                           ?>
                              <table style="width: 100%; margin-bottom: 20px;">
                                <tr>
                                	<!--<td style="padding: 10px;width: 15%;"><img src="../../images/flight_default.jpg" style="width: 120px; height: 100px; border: 1px solid #000;"/></td>-->
                                    <td style="text-align: center;width: 20%;background-color: #eee; padding:10px;">
                                    <p style="color: blue; font-weight: bold; font-size: 12px;"><?php echo $flight_result[$flight_view_counter]->Company ?></p>
                                    <p style="color: #BBBBBB; font-weight: bold; font-size: 16px;"><span class="fa fa-plane"></span></p>
                                    <p style="color: #000; font-weight: bold; font-size: 12px;"><?php echo date("Y-m-d",strtotime($flight_result[$flight_view_counter]->DepartureDate)); ?></p>
                                    </td>
                                    <td style="padding: 10px;width: 20%; text-align: right;vertical-align: middle;"> 
                                    <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($flight_result[$flight_view_counter]->DepartureTime)); ?></p>
                                    <p style="color: blue;"><?php echo $flight_result[$flight_view_counter]->DepartureAirport ?></p>
                                    </td>
                                    
                                    <td><p style="color: #BBBBBB; font-weight: bold; font-size: 12px;text-align: center;">&nbsp;&nbsp;&rarr;&nbsp;&nbsp;<!--&nbsp;&nbsp;o----&nbsp;No Stops&nbsp;----o&nbsp;&nbsp;--></p></td>
                                    
                                    <td  style="padding: 10px;width: 20%; text-align: left;vertical-align: middle;">
                                    <p style="color: #000000; font-weight: bold; font-size: 15px;"><?php echo date("H:i",strtotime($flight_result[$flight_view_counter]->ArrivalTime)); ?></p>
                                    <p style="color: blue;"><?php echo $flight_result[$flight_view_counter]->ArrivalLocation ?></p>
                                    </td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td>
                                    <p style="color: #ff0000; font-weight: bold; font-size: 14px;">&nbsp;&pound;<?php echo $flight_result[$flight_view_counter]->Price ?></p>
                                    
                                    </td>
                                    <td style="width: 15%; border: 0px solid; vertical-align: middle;">
                                    <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                                    <input type="hidden" name="p_id" value="<?php echo $flight_result[$flight_view_counter]->FlightID ?>" />
                                    <input type="hidden" name="capacity" value="<?php echo $flight_result[$flight_view_counter]->Capacity ?>" />
                                    <input type="hidden" name="amt" value="<?php echo $flight_result[$flight_view_counter]->Price ?>" />
                                    <input type="hidden" name="product" value="Flight from <?php echo $flight_result[$flight_view_counter]->DepartureAirport ?> to <?php echo $flight_result[$flight_view_counter]->ArrivalLocation ?> with <?php echo $flight_result[$flight_view_counter]->Company ?>" />
                                    <input type="submit" value="Book Now" style="padding: 6px; background-color: red; color: white; border:0px; font-size: 12px; font-weight: bold;"/>
                                    <?php echo form_close(); ?>
                                    
                                    </td>
                                </tr>
                               </table>
                           <?php 
                       
                       
                           $flight_view_counter++;
                            }
                         
                              
                             }
                             
                           ?>
                          </div>
                          <div id="tabs-2" style="background-color: #FFFFFF;">
                           <?php
                            if(!empty($hotel_result))
                            {
                           ?>
                           <?php
                              $hotel_view_counter = 0;
                              foreach($hotel_result as $hotel_val)
                              {
                             
                           ?>
                            <div style="padding: 10px; border-bottom: 1px solid #DDDDDD;">
                            <!--<div style=" display: inline-table; margin-right: 12px;width: 20%; vertical-align: top;">
                            <img src="../../images/flight_default.jpg" style="width: 240px; height: 200px;"/>
                            </div>-->
                            <div style=" display: inline-block; margin-right: 12px; vertical-align: top; padding: 4px; width: 25%;">
                            <h4><?php echo $hotel_result[$hotel_view_counter]->Type ?></h4>
      
                            <h5 style="font-size: 24px; margin-top: 5px; text-align: left; font-family: Arial; font-weight: bold; ">&pound;<?php echo $hotel_result[$hotel_view_counter]->Price ?></h5>
                             <?php echo form_open(''.$my_base_url.'order/payment'); ?>
                                <input type="hidden" name="capacity" value="<?php echo $hotel_result[$hotel_view_counter]->NumberOfRooms ?>" />
                                <input type="hidden" name="p_id" value="<?php echo $hotel_result[$hotel_view_counter]->HotelID ?>" />
                                <input type="hidden" name="amt" value="<?php echo $hotel_result[$hotel_view_counter]->Price ?>" />
                                <input type="hidden" name="product" value="Hotel Room in <?php echo $hotel_result[$hotel_view_counter]->HotelCity  ?> with <?php echo $hotel_result[$hotel_view_counter]->Title ?>" />
                                <input type="submit" value="Book Now" style="padding: 10px; background-color: red; color: white; border:0px; font-size: 16px; font-weight: bold; width: 100%;"/>
                                <?php echo form_close(); ?>
                            </div>
                            <div style=" display: inline-block; margin-right: 12px; border-left: 1px solid #DDD; padding: 10px; width: 30%;">
                            <h4><?php echo $hotel_result[$hotel_view_counter]->Title ?></h4>
                            <p style="font-size: 12px; word-wrap: normal; color:red;"><i><?php echo $hotel_result[$hotel_view_counter]->Address ?></i></p>
                            <p style="font-size: 12px;" >
                            <?php echo substr($hotel_result[$hotel_view_counter]->HotelCity,0,300) ?>...
                            
                            </p>
                            </div>
                          </div>
                            
                           <?php
                              $hotel_view_counter++;
                                  }
                                }
                           ?>
                          </div>
                          <div id="tabs-3" style="background-color: #FFFFFF;">
                           <?php
                            if(!empty($activities_result))
                            {
                           ?>
                           <?php
                              $activity_view_counter = 0;
                              foreach($activities_result as $activities_val)
                              {
                             
                           ?>
                            <div style="padding: 10px; border-bottom: 1px solid #DDDDDD;">
                            <!--<div style=" display: inline-table; margin-right: 12px;width: 20%; vertical-align: top;">
                            <img src="../../images/flight_default.jpg" style="width: 280px; height: 190px;"/>
                            </div>-->
                            
                            <div style=" display: inline-block; margin-right: 12px; border-left: 1px solid #DDD; padding: 10px; width: 70%;">
                            <h4><?php echo $activities_result[$activity_view_counter]->City; ?></h4>
                            <p style="font-size: 12px; word-wrap: normal; color:red;"><i><?php echo $activities_result[$activity_view_counter]->Age; ?>,
                            <?php echo $activities_result[$activity_view_counter]->Country; ?>,<?php echo $activities_result[$activity_view_counter]->Continent; ?> | 
                            <span style="color: blue;">Event Duration:<?php echo date("Y-m-d",strtotime($activities_result[$activity_view_counter]->Date1)); ?> - <?php echo date("Y-m-d",strtotime($activities_result[$activity_view_counter]->Date2)); ?> </span></i></p>
                            <p style="font-size: 12px;" >
                            <?php echo substr($activities_result[$activity_view_counter]->Activities,0,300) ?>...
                            
                            </p>
                            <hr />
                            <h4>Weathe Condition (<?php echo $activities_result[$activity_view_counter]->Season; ?>)</h4>
                            <p style="font-size: 12px;" >
                            <?php echo $activities_result[$activity_view_counter]->Weather; ?>
                            
                            
                            </p>
                            </div>
                      </div>
                      
                      <?php
                           $activity_view_counter++;
                          }
                        }
                      ?>
                          </div>
                          
                        </div>
                        <!-- end owl-hotel-offers -->
                     
                        
                    </div><!-- end columns -->
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end hotel-offers -->
        
        <?php
        
          }
          
        ?>
        <div id="va_result" >&nbsp;</div>  
        
        <!--======================= FOOTER =======================-->
        <section id="footer" class="ftr-heading-o ftr-heading-mgn-1">
        
            <!-- end footer-top -->

            <div id="footer-bottom" class="ftr-bot-black">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="copyright">
                            <p>© 2018 <a href="#">Travel and Explore Now</a>. All rights reserved.</p>
                        </div><!-- end columns -->
                        
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="copyright"> <!--id="terms"-->
                            <ul class="list-unstyled list-inline">
                            	<li><a href="#">Terms &amp; Condition</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                            </ul>
                        </div><!-- end columns -->
                    </div><!-- end row -->
                </div><!-- end container -->
            </div><!-- end footer-bottom -->
            
        </section><!-- end footer --> 
        
        <!-- Page Scripts Starts -->
        <script src="<?php echo $rbase_url; ?>js/jquery.min.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/jquery.colorpanel.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/jquery.magnific-popup.min.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/bootstrap.min.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/jquery.flexslider.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/bootstrap-datepicker.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/owl.carousel.min.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/custom-navigation.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/custom-flex.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/custom-owl.js.download"></script>
        <script src="<?php echo $rbase_url; ?>js/custom-date-picker.js.download"></script>
        
       
        <div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block; z-index: 9000; margin-top: 20px;">
        <table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day  active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day  active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day  active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day  active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day disabled active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day disabled active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day disabled active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div></div><div class="datepicker dropdown-menu"><div class="datepicker-days" style="display: block;"><table class=" table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">March 2018</th><th class="next">›</th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td class="day disabled old">25</td><td class="day disabled old">26</td><td class="day disabled old">27</td><td class="day disabled old">28</td><td class="day disabled">1</td><td class="day disabled">2</td><td class="day disabled">3</td></tr><tr><td class="day disabled">4</td><td class="day disabled">5</td><td class="day disabled">6</td><td class="day disabled">7</td><td class="day disabled">8</td><td class="day disabled active">9</td><td class="day ">10</td></tr><tr><td class="day ">11</td><td class="day ">12</td><td class="day ">13</td><td class="day ">14</td><td class="day ">15</td><td class="day ">16</td><td class="day ">17</td></tr><tr><td class="day ">18</td><td class="day ">19</td><td class="day ">20</td><td class="day ">21</td><td class="day ">22</td><td class="day ">23</td><td class="day ">24</td></tr><tr><td class="day ">25</td><td class="day ">26</td><td class="day ">27</td><td class="day ">28</td><td class="day ">29</td><td class="day ">30</td><td class="day ">31</td></tr><tr><td class="day  new">1</td><td class="day  new">2</td><td class="day  new">3</td><td class="day  new">4</td><td class="day  new">5</td><td class="day  new">6</td><td class="day  new">7</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2018</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span class="month active">Mar</span><span class="month">Apr</span><span class="month">May</span><span class="month">Jun</span><span class="month">Jul</span><span class="month">Aug</span><span class="month">Sep</span><span class="month">Oct</span><span class="month">Nov</span><span class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev">‹</th><th colspan="5" class="switch">2010-2019</th><th class="next">›</th></tr></thead><tbody><tr><td colspan="7"><span class="year old">2009</span><span class="year">2010</span><span class="year">2011</span><span class="year">2012</span><span class="year">2013</span><span class="year">2014</span><span class="year">2015</span><span class="year">2016</span><span class="year">2017</span><span class="year active">2018</span><span class="year">2019</span><span class="year old">2020</span></td></tr></tbody></table></div>
        </div>
        <div style=" bottom:0;position: fixed; text-align: right; border:0px solid; width: 100%;">

             <a href="javascript:void(0)" onclick="javascript:chatWith_noTextArea('TAEN-Agent')" title="Chat with a TAEN Agent"><img src="<?php echo $rbase_url; ?>images/chatr.png" alt="TAEN-Agent" width="112" height="112" alt="Chat with a TAEN Agent" /></a>
       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       </div>
        <script src="<?php echo $rbase_url; ?>js/custom-video.js.download"></script>
        
         <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/jquery-ui1.css">
       
       <!--<link rel="stylesheet" href="/resources/demos/style.css">-->
        <script src="<?php echo $rbase_url; ?>js/jquery-1.12.4.js"></script>
        <script src="<?php echo $rbase_url; ?>js/jquery-ui1.js"></script>
        <script type="text/javascript" src="<?php echo $rbase_url; ?>js/chat.js"></script>
        <?php 
         
         if(!empty($activities_result))
         {
        
        ?>  
        <script>
          $( function() {
           //$( "#tabs" ).tabs();
           $("#tabs").tabs({ active: 2 });
          } );
        </script>
        <?php
        
        }else
        {
        ?>
        <script>
          $( function() {
           $( "#tabs" ).tabs();
          } );
        </script>
        
        <?php
        
         }
        
        ?>
        <!-- Page Scripts Ends -->
        
    
</body></html>