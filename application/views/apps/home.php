

<link href="<?php echo base_url();?>css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo base_url();?>js/jquery-1.11.0.min.js"></script>
<!-- Custom Theme files -->
<link href="<?php echo base_url();?>css/style.css" rel="stylesheet" type="text/css" media="all"/>
<!-- Custom Theme files -->
 
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--Google Fonts-->
<link href='<?php echo base_url();?>css/font_alladin.css' rel='stylesheet' type='text/css'>
<link href='<?php echo base_url();?>css/font_oxygin.css' rel='stylesheet' type='text/css'>
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
 <div class="banner">
 	<div class="container">
 		<div class="banner-main">
 			 <div class="logo wow bounceInLeft" data-wow-delay="0.0s">
 			 	<h1 id="h1logo"><a href="<?php echo base_url(); ?>">
 			 	<img id="logoimg" alt="AppsFeature" src="<?php echo base_url()."images/aj_logo1.png"?>">AppsFeature</a></h1>
 			 </div>
			<div class="top-nav-w3layouts">
				<div class="menu">
					<a href="#" id="m_nav_menu" class="navicon"></a> 
					<div class="toggle"> 
						<ul id="m_nav_list" class="toggle-menu">
							<li class="m_nav_item"><a href="<?php echo base_url(); ?>" class="active"> Home</a>
							</li>
							<li class="m_nav_item"><a href="<?php echo base_url(); ?>FeaturedApps" class="active"> Featured Apps</a>
							</li>
							<!-- <li class="m_nav_item"><a href="<?php echo base_url(); ?>FeaturedGames" class="active"> Featured Games</a>
							</li> -->
							<li class="m_nav_item"><a href="#about" class="navicon1 about scroll"> About</a></li> 
							<li class="m_nav_item"><a href="http://blogs.appsfeature.com/" class=""  > Blog</a></li> 
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
 		  <div class="clearfix"> </div>
 		</div>
 	</div>
 </div>
<!--banner end here-->
<!--About Us strat here-->
<div class="services" id="about">
	<div class="container">
		<div class="service-main">
			 <div class="w3ser-top w3-agileits">
			 	<h3>About Us</h3>
			 </div>
			  <div class="service-bottom agileits-w3layouts-bottom">
				 <div class="col-md-5 ser-left wow bounceInLeft" data-wow-delay="0.0s">
				 	<img src="<?php echo base_url()."images/home-icon.png";?>" alt="AppsFeature" title="AppsFeature banner">
				 </div>
				 <div class="col-md-7 ser-right wow bounceInRight" data-wow-delay="0.0s">
				 	<h2 id="aboutush2">Appsfeature is an Android Mobile App development company which can develop an app of your choice and requirements for you. Download useful apps and contact us for Android development.</h2>
				 </div>
				<div class="clearfix"> </div>
			 </div>
		</div>
	</div>
</div>
<!--About Us end here-->		
<!--about start here-->
<div class="about" id="services">
	<div class="container">
		<div class="about-main">
			
 			 <div class="changer-main div1">
		       <?php
		       if($appsList)
		          for ($i=0;$i<count($appsList);$i++){
                     ?>
                            <div class="about-block-snd mobileItems">
					<div class="changer-left-snd wow bounceInLeft" data-wow-delay="0.0s">
						 <img  src="<?php echo base_url()."images/apps/".$appsList[$i]['app_graphic'];?>" alt="AppsFeature <?php echo $appsList[$i]['app_name'];?>" class='mobileImage'>
					</div>
					<div class="changer-right-snd wow bounceInRight" data-wow-delay="0.1s">
						<h2 class="h2details mobileHeading"><?php echo $appsList[$i]['app_name'];?></h2>
						<p class="mobileDetail"><?php echo $appsList[$i]['app_detail'];?></p>
						 <a title="Appsfeature- <?php echo $appsList[$i]['app_name'];?>" class="mobileViewDetail" href="<?php echo base_url().$controller.'details/'.$appsList[$i]['app_package'];?>">
							     <button class="btn btn-info">View details</button></a>
					     <a title="Appsfeature Download- <?php echo $appsList[$i]['app_name'];?>" href="<?php echo $appsList[$i]['app_location'];?>" target="_blank" class="mobileDownload">
							     	<button class="btn btn-success">Download</button>
							     </a> 
					</div>
				      <div class="clearfix"> </div>
				      <div class="ch-bott1 agile"> 
				     </div>
				</div>
				<hr class="hrstyle">
                    
                    <?php  
				 
				  }?>




				
						


		     </div>	
		      <div class="changer-main div2"  id="webApp">
		       <?php
		       if($appsList)
		          for ($i=0;$i<count($appsList);$i++){
                      if($i%2==0){?>
                            <div class="about-block items">
							<div class="changer-left wow bounceInLeft" data-wow-delay="0.0s">
								<h2 class="h2details webLeftAppHeading"><?php echo $appsList[$i]['app_name'];?></h2>
							     <p class="webLeftAppDetail"><?php echo $appsList[$i]['app_detail'];?></p>
							     <a class="webLeftAppViewDetail" title="Appsfeature- <?php echo $appsList[$i]['app_name'];?>" href="<?php echo base_url().$controller.'details/'.$appsList[$i]['app_package'];?>">
							     <button class="btn btn-info">View details</button></a>
							     <a title="Appsfeature Download - <?php echo $appsList[$i]['app_name'];?>" href="<?php echo $appsList[$i]['app_location'];?>" target="_blank" class="webLeftAppDownload">
							     	<button class="btn btn-success">Download</button>
							     </a> 
							</div>
							<div class="changer-right wow bounceInRight" data-wow-delay="0.1s">
								<img  class="webLeftAppImage" src="<?php echo base_url()."images/apps/".$appsList[$i]['app_graphic'];?>" alt="AppsFeature <?php echo $appsList[$i]['app_name'];?>">
							</div>
						     <div class="clearfix"> </div>
						     <div class="ch-bott1 agile"> 
						     </div>
						     <hr class="hrstyle">
						 </div>
						 
                    <?php
                      }
                      else
                      {?>
                             <div class="about-block-snd items">
					<div class="changer-left-snd wow bounceInLeft" data-wow-delay="0.0s">
						 <img class="webLeftAppImage" src="<?php echo base_url()."images/apps/".$appsList[$i]['app_graphic'];?>" alt="Apps Feature <?php echo $appsList[$i]['app_name'];?>">
					</div>
					<div class="changer-right-snd wow bounceInRight" data-wow-delay="0.1s">
						<h2 class="h2details webLeftAppHeading"><?php echo $appsList[$i]['app_name'];?></h2>
						<p class="webLeftAppDetail"><?php echo $appsList[$i]['app_detail'];?></p>
						 <a class="webLeftAppViewDetail" title="Appsfeature- <?php echo $appsList[$i]['app_name'];?>" href="<?php echo base_url().$controller.'details/'.$appsList[$i]['app_package'];?>">
							     <button class="btn btn-info">View details</button></a>
					     <a class="webLeftAppDownload" title="Appsfeature Download- <?php echo $appsList[$i]['app_name'];?>" href="<?php echo $appsList[$i]['app_location'];?>" target="_blank">
							     	<button class="btn btn-success">Download</button>
							     </a> 
					</div>
				      <div class="clearfix"> </div>
				      <div class="ch-bott1 agile"> 
				     </div>
				     <hr class="hrstyle">
				</div>
				
                    <?php  }   
				 
				  }?>




				
						


		     </div>	

		</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-sm-12" align="center">
			<?php
				if(isset($pages))
				{
					for($i=1;$i<=$pages;$i++)
					{
			?>
						<a href="#" class="btn btn-default btn-xs page"><?php echo $i; ?></a>
			<?php
					}
				}
			?>
				
			</div>
		</div>
	</div>	
</div>


<!--about end here--> 


<!--gallery start here-->

<!--gallery end here-->

<!--features start here-->
 
<!--features end here-->
<!--quotation start here-->
 
<!--auotation end here-->


<script type="text/javascript">
	$(function(){
	if( $(window).width() > 500 ) {
     $(".div1").hide();
     $(".div2").show();
	}
	else{
		$(".div1").show();
		$(".div2").hide();
	}
	});
</script>

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
<style type="text/css">
	.paginate-active, .paginate-active:focus, .paginate-active:active
	{
		background: rgb(56, 138, 163);
    	color: white;
	}
</style>
