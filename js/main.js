$(document).ready( function(){

// Add Tags Initialize

$('#tags').tagsInput();

// Media Uploader
	
	$(".captureuploader").on("tap",function(){
	  $('input.captureinput[type=file]').click();
	});
	
	$('.galleryuploader').on("tap",function(){
		$('input.galleryinput[type=file]').click();
	});
	
// Flex Slider

   $('.flexslider').flexslider({
	  
	  animation: "fade",
	  controlNav: false,
	  animationLoop: true,
	  slideshow: true,
	  directionNav: true
	  
	  });	
	
});


