
<link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url();?>js/jquery-1.11.0.min.js"></script>
<!-- Custom Theme files -->
<link href="<?php echo base_url();?>css/style.css" rel="stylesheet" type="text/css" media="all"/>
<!-- Custom Theme files -->
 
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--Google Fonts-->
<link href='//fonts.googleapis.com/css?family=Aladin' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
<!--google fonts-->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="<?php echo base_url();?>js/move-top.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/easing.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
</script>
<!-- //end-smoth-scrolling -->
<!-- animated-css -->
		<link href="<?php echo base_url();?>css/animate.css" rel="stylesheet" type="text/css" media="all">
		<script src="<?php echo base_url();?>js/wow.min.js"></script>
		<script>
		 new WOW().init();
		</script>
<!-- animated-css --> 
</head>
<body>
<!--banner start here-->

<!--banner end here-->
<!--about start here-->
<div class="container">
 		<div class="top-nav-w3layouts">
				<div class="menu">
					<a href="#" id="m_nav_menu" class="navicon"></a> 
					<div class="toggle"> 
						<ul id="m_nav_list" class="toggle-menu">
							<li class="m_nav_item"><a href="<?php echo base_url(); ?>" class="active"> Home</a></li>
							<li class="m_nav_item"><a href="#about" class="navicon1 scroll"> About</a></li> 
							<li class="m_nav_item"><a href="#contact" class="navicon1 scroll"> Contact</a></li>
						</ul>
					</div> 
				</div> 	 
				<!-- menu-js -->
				<script>
					$('.navicon').on('click', function (e) {
					  e.preventDefault();
					  $(this).toggleClass('navicon--active');
					  $('.toggle').toggleClass('toggle--active');
					});
					
				</script>
				<script>
					$('.navicon1').on('click', function (e) {  
						e.preventDefault();  
						$('.toggle').toggleClass('toggle--active');
						$('.navicon').toggleClass('navicon--active');
					});
				</script> 
				<!-- //menu-js -->
 			</div>
 	</div>
<!--about end here-->
<!--services strat here-->
<div class="services" id="services">
	<div class="container">
		<div class="service-main">
			 <div class="w3ser-top w3-agileits">
			 	<h3><?php echo $apps[0]['app_name'];?></h3>
			 </div>
			  <div class="service-bottom agileits-w3layouts-bottom">
				 <div class="col-md-5 ser-left wow bounceInLeft" data-wow-delay="0.1s"> 
				 	<img src="<?php echo base_url()."images/apps/".$apps[0]['app_image'];?>" alt="AppsFeature">
				 </div>
				 <div class="col-md-7 ser-right wow bounceInRight" data-wow-delay="0.1s">
				 	<p><?php echo $apps[0]['app_detail'];?></p>
				 	<a href="<?php echo $apps[0]['app_location'];?>" target="_blank">
							     	<button class="btn btn-success">Download</button>
							     </a> 
				 </div>
				<div class="clearfix"> </div>
			 </div>
		</div>
	</div>
</div>
<!--services end here-->
<!--gallery start here-->
<div class="gallery" id="gallery">
	<div class="container">
		<div class="gallery-main w3-agileits">
			<div class="gallery-top-w3ls">
				<h3>Screenshot</h3>
			</div>
			<div class="gallery-bottom agileits-w3layouts">
				<!---->
				 <ul id="flexiselDemo3">
				 		<?php foreach ($appsScreens as $key => $value) {?> 
				 		
						<li><img src="<?php echo base_url()."images/screenshots/".$value['app_screens'];?>" class="img-responsive" alt="AppsFeature apps"/></li> 

						<?php }?>
					 </ul>
				</div>
				 <script type="text/javascript">
					$(window).load(function() {
						
						$("#flexiselDemo3").flexisel({
							visibleItems: 3,
							animationSpeed: 1000,
    						itemsToScroll: 1,
							autoPlay: true,
							autoPlaySpeed: 3000,    		
							pauseOnHover: true,
							enableResponsiveBreakpoints: true,
							responsiveBreakpoints: { 
								portrait: { 
									changePoint:480,
									visibleItems: 1
								}, 
								landscape: { 
									changePoint:640,
									visibleItems: 2
								},
								tablet: { 
									changePoint:768,
									visibleItems: 2
								}
							}
						});
						
					});
				    </script>
				    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexisel.js"></script>
		</div>
	</div>
</div>
<!--gallery end here-->

<!--features start here-->
<div class="features" id="features">
	<div class="container">
		<div class="features-main">
			<div class="features-top w3-agile">
				<h2>Features</h2>
			</div>
			<div class="features-bottom">

						<?php 
						 for ($i=0;$i<count($appsDetails);$i++){
                      		 ?>
                      		<div class="col-md-6 features-left wthree">
							  <div class="fea-agileits wow bounceInLeft" data-wow-delay="0.1s">
								<div class="fea-left-top">
									 <h3><?php echo $appsDetails[$i]['feature_name'];?></h3> 
									 <p><?php echo $appsDetails[$i]['feature_detail'];?></p>
								</div>
								
							   </div>
							</div> 
						<?php }?> 
				 <div class="clearfix"> </div>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>
<!--features end here-->
<!--quotation start here-->

<!--auotation end here-->


<script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
			};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
	</script>