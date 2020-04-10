<?php
$my_base_url = base_url()."index.php/"; //echo $my_base_url;
$rbase_url   = base_url();
?>

<!DOCTYPE html>

<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Travel and Explore Now</title>
        
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="icon" href="<?php echo $rbase_url; ?>images/favicon.png" type="image/x-icon">
        
        <!-- Google Fonts -->	
        <link href="<?php echo $rbase_url; ?>css/css" rel="stylesheet">
        
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
    
    
       
    </head>
    
        <!--====== LOADER =====-->
        <div class="loader" style="display: none;"></div>
    
    
    	<!--======== SEARCH-OVERLAY =========-->       
        <div class="overlay">
            <a href="javascript:void(0)" id="close-button" class="closebtn">×</a>
            <div class="overlay-content">
                <div class="form-center">
                    <form>
                    	<div class="form-group">
                        	<div class="input-group">
                        		<input type="text" class="form-control" placeholder="Search..." required="">
                            	<span class="input-group-btn"><button type="submit" class="btn"><span><i class="fa fa-search"></i></span></button></span>
                            </div><!-- end input-group -->
                        </div><!-- end form-group -->
                    </form>
                </div><!-- end form-center -->
            </div><!-- end overlay-content -->
        </div><!-- end overlay -->
        
        
        <!--============= TOP-BAR ===========-->
        <div id="top-bar" class="tb-text-white">
            <div class="container">
                <div class="row">          
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div id="info">
                            <ul class="list-unstyled list-inline">
                               
                            </ul>
                        </div><!-- end info -->
                    </div><!-- end columns -->
                    
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div id="links">
                            <ul class="list-unstyled list-inline">
                                
                                	<form>
                                    	<ul class="list-inline">
                                        	<li>
                                                <div class="form-group currency">
                                                    
                                                    
                                                </div><!-- end form-group -->
											</li>
                                            
                                            
										</ul>
                                    </form>
                                </li>
                            </ul>
                        </div><!-- end links -->
                    </div><!-- end columns -->				
                </div><!-- end row -->
            </div><!-- end container -->
        </div><!-- end top-bar -->
		
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
                </div><!-- end navbar-header -->
                
                <div class="collapse navbar-collapse" id="myNavbar1">
                    
                </div><!-- end navbar collapse -->
            </div><!-- end container -->
        </nav><!-- end navbar -->        
        
        
       
      
        <section id="hotel-offers" class="section-padding" style="margin-top: 0px;">
        	<div class="container">
        		<div class="row">
        			<div class="col-sm-12">
                    <div style="padding: 10px; background-color: #ddd; border-radius: 5px;">
                     <?php echo form_open(''.$my_base_url.''); ?>
                        <input  type="submit" value="<< BACK TO HOME" style="padding: 6px; background-color: red; color: white; border:0px; font-size: 12px; font-weight: bold; border-radius: 5px;"/>
                     <?php echo form_close(); ?>
                     </div>
                    	<div class="page-heading">
                         <h3>Payments</h3>
                         <?php
                            if (isset($message_success)) {
                            echo '<div class="isa_success">';
                            echo '<i class="fa fa-check"></i>'; 
                            echo $message_success;
                            echo "</div>";
                            }
                            
                            $paydata = array(
                                               'product'  => $product,
                                               'amt'     => $amt
                                           );
                            $this->session->set_userdata($paydata);
                           ?>
                           <?php
                                if (isset($error_message)) {
                                echo '<div class="isa_error">';
                                echo '<i class="fa fa-times-circle"></i>';     
                                echo $error_message;
                                echo '</div>'; 
                                }
                                //echo validation_errors();
                              
                            ?>
                        
                            <hr class="heading-line" />
                        </div><!-- end page-heading -->
                      <?php
                      
                      if(!isset($message_success))
                      {
                      
                      ?>
                        <h2 style="text-align: center;"><?php echo $product; ?><br />
                        
                        Quatity/No of Units: <span style="color: red; font-weight: bold;"><?php echo $qty; ?></span><br />
                        Amount: &pound;<span style="color: red; font-weight: bold;"><?php echo $amt; ?></span></h2>
                        
                        <div style="text-align: center;" >
                          <div style=" display: inline-table; margin-right: 12px; border: 1px solid #ddd; padding:5px;">
                            <?php //echo form_open(''.$my_base_url.'order/pay_with_paypal'); ?>
                             <input type="hidden" name="amt" value="<?php echo $amt; ?>" />
                             <input type="hidden" name="product" value="<?php echo $product; ?>" />
                           
                           <!--Pay with Paypal<br />-->
                                <input type="image" name="submit" src="../../images/pay_with_paypal.jpg" border="0" alt="Submit" style="width: 60%; height: 60%;" />
                                <!--<img src="../../images/pay_with_paypal.jpg" style="width: 60%; height: 60%;"/>-->
                            <?php //echo form_close(); ?>
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                             
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="business" value="yourpaypalbusinessaccount@email.com">
                                <input type="hidden" name="item_name" value="<?php echo $product; ?>">
                                <input type="hidden" name="item_number" value="<?php echo $productid; ?>">
                                <input type="hidden" name="amount" value="<?php echo $amt; ?>">
                                <input type="hidden" name="no_shipping" value="0">
                                <input type="hidden" name="no_note" value="1">
                                <input type="hidden" name="currency_code" value="GBP">
                                <input type="hidden" name="lc" value="AU">
                                <input type="hidden" name="bn" value="PP-BuyNowBF">
                                <input type="hidden" name="return" value="<?php echo $my_base_url; ?>/order/payment">
                             
                                <br />
                                <input type="submit" value="Pay with PayPal"  style="border: 1px solid #289FE4; border-radius: 5px; color:white; font-weight:bold; padding:5px; background-image: url('../../images/btn.png');background-repeat: repeat-x;">
                             
                            </form>
                           </div>
                          
                           <div style=" display: inline-table; margin-right: 12px; border: 1px solid #ddd; padding:5px;">
                           <?php //echo form_open(''.$my_base_url.'order/pay_with_cards'); ?>
                             <input type="hidden" name="amt" value="<?php echo $amt; ?>" />
                             <input type="hidden" name="product" value="<?php echo $product; ?>" />
                           
                           <!--Pay with Cards<br />-->
                                <input type="image" name="submit" src="../../images/pay_with_cards.jpg" border="0" alt="Submit" style="width: 60%; height: 60%;" />
                                <!--<img src="../../images/pay_with_cards.jpg" style="width: 60%; height: 60%;"/>-->
                           <?php //echo form_close(); ?><br /><br />
                           <form action="<?php echo $my_base_url.'order/payment'; ?>" method="POST">
                              <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="pk_test_6pRNASCoBOKtIshFeQd4XMUh"
                                data-amount="<?php echo $amt*100; ?>"
                                data-currency="GBP"
                                data-name="<?php echo $product; ?>"
                                data-description="<?php echo $product; ?>"
                                data-image=""
                                data-locale="auto"
                                data-zip-code="true">
                              </script>
                            </form>
                           </div>
                           
                        </div>
                        <!-- end owl-hotel-offers -->
                    <?php
                    
                     }
                    
                    ?>    
                        
                    </div><!-- end columns -->
                </div><!-- end row -->
        	</div><!-- end container -->
        </section><!-- end hotel-offers -->
        <!--
        <section id="footer" class="ftr-heading-o ftr-heading-mgn-1">
        <?php //echo form_open(''.$my_base_url.'VirtualAssistant/chatHeartbeat'); ?>
        <input type="submit" value="Test Virtual Assistant" style="padding: 6px; background-color: red; color: white; border:0px; font-size: 12px; font-weight: bold;"/>
        <?php //echo form_close(); ?>
        </section>
        -->

        
        
        
        <!--======================= FOOTER =======================-->
        <section id="footer" class="ftr-heading-o ftr-heading-mgn-1">
        
            <!-- end footer-top -->

            <div id="footer-bottom" class="ftr-bot-black">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="copyright">
                            <p>&copy; 2018 <a href="#">Travel and Explore Now</a>. All rights reserved.</p>
                        </div><!-- end columns -->
                        
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="terms">
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
        <script src="<?php echo $rbase_url; ?>js/custom-video.js.download"></script>
        
         <link rel="stylesheet" href="<?php echo $rbase_url; ?>css/jquery-ui1.css">
       
       <!--<link rel="stylesheet" href="/resources/demos/style.css">-->
        <script src="<?php echo $rbase_url; ?>js/jquery-1.12.4.js"></script>
        <script src="<?php echo $rbase_url; ?>js/jquery-ui1.js"></script>
        <script>
          $( function() {
           $( "#tabs" ).tabs();
          } );
        </script>
        <!-- Page Scripts Ends -->
        
    
</body></html>