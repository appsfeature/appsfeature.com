

<!--contact start here-->
<div class="contact wthree" id="contact">
	<div class="container">
		<div class="contact-main">
			<div class="contact-top">
				<h3>Contact Us</h3>
			</div>
			<div class="contact-bottom">
				<div class="contact-left agileinfo wow bounceInLeft" data-wow-delay="0.1s">
					<h4>Contact Info</h4>
					<form action="<?php echo base_url(); ?>Apps/sendContactDetails" method="post"> 
						<input type="text" name="Name" placeholder="Name" required="">
						<input type="text" name="Email" placeholder="Email" required="">
						<input type="text" name="Subject" placeholder="Subject" required="">
						<textarea name="Message" placeholder="Message" required=""></textarea>
						<input type="submit" value="Send">
					</form>
				</div>
				<div class="contact-right agileinfo wow bounceInRight" data-wow-delay="0.1s">
					<div class="contact-rit-info wthree">
						<div class="row">
							<div class="col-sm-7">
							<h4>Contact Detail</h4> 
							<p>Mobile :+91 9891983694</p>
							<p>Email : appsfeature@gmail.com</p>
							</div>
						
							<div class="col-sm-5"> 
								<img id="ajlogo" src="<?php echo base_url()."images/aj_logo.png";?>" alt="AppsFeature developer Abhijit Rao" class="img-circle">
							</div>

						</div>
					</div>
					<div class="social-icons">
						<h4>Follow us :</h4>
						<ul>
							<li><a title="AppsFeature" href="http://fb.com/appsfeature" target="_blank"><span class="fb"> </span> </a></li>
							<li><a title="AppsFeature" href="http://twitter.com/appsfeature" target="_blank"><span class="twit"> </span></a></li>
							<li><a title="AppsFeature" href="https://plus.google.com/u/0/+AppsFeature" target="_blank"><span class="gmail"> </span></a></li>
							<li class="no-mar"><a title="AppsFeature" href="http://in.pinterest.com/appsfeature" target="_blank"><span class="pin"> </span></a></li>
						</ul>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
</div>
<!--contact end here-->
<!--copy rights start here-->
<div class="copy-right">
	<div class="container">
		 <div class="copy-rights-main wow zoomIn" data-wow-delay="0.1s">
    	    <div class="copy-rights-main wow zoomIn" data-wow-delay="0.1s" align='center'>&copy; 2017 AppsFeature. All Rights Reserved | Designed and managed by AJsoft</a>
    	 </div>
    </div>
    
	<a title="AppsFeature Home " href="#" id="toTop"> <span id="toTopHover"> </span></a>
</div> 
<!--copy rights end here-->
</body>
</html>