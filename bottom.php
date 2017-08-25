        </div><!-- end div class="text_area" -->
      </div><!-- end div id="templatemo_right_column" -->
      

<!-- begin bottom -->
	<div id="templatemo_footer">
<!-- <img src="./images/red_butterfly.jpg" class="follow" style="position: absolute;"/> -->
    Copyright © 2017 Red Butterfly Books 
    <a href="http://www.iwebsitetemplate.com" target="_parent">Website</a> by <a href="http://www.templatemo.com" target="_blank">templatemo.com</a>    </div>
    
         <div id="sketch" style="position: relative;" ><!-- start sketch div -->
               <img src="images/red_butterfly_1_60x60.jpg" id="follower" style="position: absolute;" />
		</div><!-- end sketch div-->
<!--         
</div>
<div align=center>This template  wnloaded form <a href='http://all-free-download.com/free-website-templates/'>free website templates</a>
</div>
-->
<!-- end div for butterfly that follows mouse pointer -->
<!--</div> -->
<script>
// initialze vars
var frameRate    =  30;
var timeInterval = Math.round( 1000 / frameRate );
var relMouseX    = 0;
var relMouseY    = 0;
var mouse_offsetX = 20;
var mouse_offsetY = 20;

$(document).ready(function(){
  	$("#butterfly_no_show_div").show(4000);

	//prep stuff for image following mouse pointer
  // get the stage offset
  var offset = $('#sketch').offset();
  //textVar = 2;

  // start calling animateFollower at the 'timeInterval' we calculated above
//  atimer = setInterval( "animateFollower()", timeInterval );
  
  // show / hide divs
  
  // click show butterfly button
  $( "#butterfly_show_btn" ).click(function() {
//	  console.log ("bool_show_butterfly clicked ");
//		if $("#follower").load()
//		{
			$("#butterfly_no_show_div").hide(750);		  
			$("#butterfly_show_div").show(750);
		// start calling animateFollower at the 'timeInterval' we calculated above
			atimer = setInterval( "animateFollower()", timeInterval );
			$("#follower").show(1500);
//		}
//		else
//		{
//			$('.buttefly_txt').text("Image is loading. Please wait a second, then click again.");			
//		}
			
  });
  
  $( "#butterfly_hide_btn" ).click(function() {
//	  	console.log ("bool_hide_butterfly clicked");
		$("#butterfly_show_div").hide(750);
		$("#butterfly_no_show_div").show(750);
		if (atimer)
			{
		  		clearInterval(atimer);
		  		atimer = null;
		  	}
		$("#follower").hide(1500);

	});

// mouseover function - get relative mouse X and Y
$(document).mousemove( function(e) {
  mouseX = e.pageX + mouse_offsetX;
  mouseY = e.pageY + mouse_offsetY;
  relMouseX = mouseX - offset.left;
  relMouseY = mouseY - offset.top;
} );
}); // end on document ready

// move the image where the mouse is
// this function is called by the setInterval command above to run
// at a rate of 30 frames per second
function animateFollower() {

  $('#follower').css('left', relMouseX);
  $('#follower').css('top', relMouseY);
}

</script>
</body>
</html>
