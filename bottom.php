        </div><!-- end div class="text_area" -->
      </div><!-- end div id="templatemo_right_column" -->


<!-- begin bottom -->
	<div id="templatemo_footer">
<!-- <img src="./images/red_butterfly.jpg" class="follow" style="position: absolute;"/> -->
    Copyright © 2017 Red Butterfly Books <a href="http://www.iwebsitetemplate.com" target="_parent">Website Templates</a> by <a href="http://www.templatemo.com" target="_blank">templatemo.com</a>    </div>
        
</div>
<div align=center>This template  downloaded form <a href='http://all-free-download.com/free-website-templates/'>free website templates</a>
</div>
<!-- end div for butterfly that follows mouse pointer -->
<!--</div> -->
<script>
// initialze vars
frameRate    =  30;
timeInterval = Math.round( 1000 / frameRate );
relMouseX    = 0;
relMouseY    = 0;
mouse_offsetX = 20;
mouse_offsetY = 20;

$(document).ready(function(){
  	$("#butterfly_no_show_div").show(1000);

	//prep stuff for image follwoing mouse pointer
  // get the stage offset
  offset = $('#sketch').offset();

  // start calling animateFollower at the 'timeInterval' we calculated above
//  atimer = setInterval( "animateFollower()", timeInterval );
  
  // show / hide divs
  
  // click show butterfly button
  $( "#butterfly_show_btn" ).click(function() {
	  console.log ("bool_show_butterfly clicked ");
		$("#butterfly_no_show_div").hide(500);		  
		$("#butterfly_show_div").show(500);
	// start calling animateFollower at the 'timeInterval' we calculated above
		atimer = setInterval( "animateFollower()", timeInterval );
		$("#follower").show(1500);
			
  });
  
  $( "#butterfly_hide_btn" ).click(function() {
	  	console.log ("bool_hide_butterfly clicked");
		$("#butterfly_show_div").hide(500);
		$("#butterfly_no_show_div").show(500);
		if (atimer)
			{
		  		clearInterval(atimer);
		  		atimer = null;
		  	}
		$("#follower").hide(1500);

	});

});
// mouseover function - get relative mouse X and Y
$(document).mousemove( function(e) {
  mouseX = e.pageX + mouse_offsetX;
  mouseY = e.pageY + mouse_offsetY;
  relMouseX = mouseX - offset.left;
  relMouseY = mouseY - offset.top;
} );

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
