 <?php  
 $my_base_url = base_url()."index.php/"; //echo $my_base_url;
$rbase_url   = base_url();

?>
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
 <?php  
 $my_base_url = base_url()."index.php/"; //echo $my_base_url;
$rbase_url   = base_url();
 //print_r($this->session->userdata['va']['flight_data']);
 $flight_result     = isset($this->session->userdata['va']['flight_data'])   ? $this->session->userdata['va']['flight_data'] : array();
 $hotel_result      = isset($this->session->userdata['va']['hotel_data'])    ?$this->session->userdata['va']['hotel_data']   : array();
 $activities_result = isset($this->session->userdata['va']['activity_data']) ?$this->session->userdata['va']['activity_data']: array();
 
 if(!empty($flight_result) || !empty($hotel_result) || !empty($activities_result))

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
                         <h3><?php //echo $result_page_title ?>......</h3>
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
                           <?php if(!empty($flight_result)) { ?>
                            <li><a href="#tabs-1" style="border: 0px solid #fff; "><span class="fa fa-plane" style="color:#008100 ;"></span> Flights (<?php  if(!empty($flight_result)) {echo count($flight_result); }else{ echo "0";} ?>)</a></li>
                           <?php
                             }
                           if(!empty($hotel_result)){
                           ?> 
                            <li><a href="#tabs-2" style="border: 0px solid #fff;"><span class="fa fa-hotel" style="color:#008100 ;"></span> Hotels (<?php if(!empty($hotel_result)) {echo count($hotel_result); }else{ echo "0";} ?>)</a></li>
                            <?php
                             }
                           if(!empty($activities_result)){
                           ?> 
                            <li><a href="#tabs-3" style="border: 0px solid #fff;"><span class="fa fa-thumbs-o-up" style="color:#008100 ;"></span> Activities (<?php if(!empty($activities_result)) { echo count($activities_result); }else{ echo "0";} ?>)</a></li>
                           <?php
                             }
                           
                           ?> 
                         
                          </ul>
                          <div id="tabs-1" style="background: #FFFFFF">
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
                                	<!--<td style="padding: 10px;width: 15%;"><img src="<?php echo $rbase_url; ?>images/flight_default.jpg" style="width: 120px; height: 100px; border: 1px solid #000;"/></td>-->
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
                                    <td style="width: 20%; border: 0px solid; vertical-align: middle;">
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
                          <div id="tabs-2" style="background: #FFFFFF">
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
                            <img src="<?php echo $rbase_url; ?>images/flight_default.jpg" style="width: 240px; height: 200px;"/>
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
                          <div id="tabs-3" style="background: #FFFFFF">
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
                            <img src="<?php echo $rbase_url; ?>images/flight_default.jpg" style="width: 280px; height: 190px;"/>
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
        
        <script src="<?php echo $rbase_url; ?>js/jquery-1.12.4.js"></script>
        <script src="<?php echo $rbase_url; ?>js/jquery-ui1.js"></script>
         <script type="text/javascript" src="<?php echo $rbase_url; ?>js/chat.js"></script>
        <script>
          $( function() {
           $( "#tabs" ).tabs();
          } );
        </script>