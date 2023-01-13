<script type="text/javascript">
	window.onload = function(){
		$('.page').eq(0).addClass('paginate-active');		
	}

	$('.page').click(function(e){
		e.preventDefault();

		$('.page').removeClass('paginate-active');
		var number = $(this).text();
		var index = $('.page').index(this);
		$('.page').eq(index).addClass('paginate-active');

		var controller = '<?php echo $controller; ?>';
		var itemShow = '<?php echo $itemShow; ?>';
		
		var windowWidth = $(window).width();
		
		var request = new XMLHttpRequest;
		
		html = '';
		var second = parseInt(number)+1;
		var third = parseInt(number)+2;

		request.onreadystatechange = function(){
			if(this.readyState==4 && this.status==200)
			{
				var content = request.responseText.trim();
				var result = '';
				var html = '';
				var baseUrl = '<?php echo base_url(); ?>';
				
				try
				{
					result = JSON.parse(content);
				}
				catch(e){}

				if(result!='')
				{
					var count = result.length;
					for(var i=0;i<count;i++)
					{
						var image = baseUrl+'images/apps/'+result[i].app_graphic;
						var package = baseUrl+controller+'details/'+result[i].app_package;
						
						if(windowWidth>500)
						{
							$(".webLeftAppHeading").eq(i).text(result[i].app_name);
							$(".webLeftAppDetail").eq(i).text(result[i].app_detail);
							$(".webLeftAppImage").eq(i).attr('src',image);
							$(".webLeftAppDownload").eq(i).attr('href',result[i].app_location);
							$(".webLeftAppViewDetail").eq(i).attr('href',result[i].app_location);
							$(".items").eq(i).show();
							
						}
						else
						{
							$(".mobileHeading").eq(i).text(result[i].app_name);
							$(".mobileDetail").eq(i).text(result[i].app_detail);
							$(".mobileImage").eq(i).attr('src',image);
							$(".mobileDownload").eq(i).attr('href',result[i].app_location);
							$(".mobileViewDetail").eq(i).attr('href',result[i].app_location);
							$(".mobileItems").eq(i).show();
							
						}
						
					}
					if(count<itemShow)
					{
						var newLength = parseInt(itemShow-count);
						
						for(var j=count;j<=newLength;j++)
						{
							if(windowWidth>500)
							{
								$(".items").eq(j).hide();
							}
							else
							{
								$(".mobileItems").eq(j).hide();
							}
							$(".hrstyle").eq(j).hide();
						}
					}
					
					$('html,body').animate({
					    scrollTop: $('#about').offset().top - 5 // or 10
					}, 'slow');
					$("#first").text(number);
					$("#second").text(second);
					$("#third").text(third);
				}
			}
		}

		request.open('post','<?php echo base_url(); ?>Apps/applicationList/'+number,true);
		request.send();
	});
</script>