$(document).ready(function(){
	$(window).resize(function(){
	var l1=$(".slides .thumb-image").width();
		$(".slides .thumb-image").height(l1);
	});
	$(".buynow").click(function(){
		$(".buyynow").modal('show');
		
	});

$(".products").each(function(){
	if($(this).find(".row .product").length > 6)
		$(this).find(".row .product:gt(5)").hide();
});
    $("#products-sale .main-container .showall").click(function(){
        $(this).parent(".main-container").find(".products .row .product").slice(12).slideToggle(200);
        
    });
    $('.flexslider').flexslider({
							animation: "slide",
							controlNav: "thumbnails"
						  });
	$(".thumb-image img").imagezoomsl({
		zoomrange:[3,3]
	});
	$(".colorpic span").click(function(){
		$(this).toggleClass("radio");
	});
	
	$(".options .colorpic button").each(function(){
		 var v=$(this).attr("class");
		$(this).css({"background-color":v.toString(),"border-color":v.toString(),"border-width":"2px","border-style":"solid"});
	});

	
	$(".sizeoption button").click(function(e){
		e.preventDefault();
		$(this).toggleClass("s");
		$(this).find("input[type='radio']").prop("checked",true);
		if($(this).siblings("button").hasClass("s"))
		$(this).siblings("button").removeClass("s");
	});
    
		$(".colorpic button").click(function(e){
		e.preventDefault();
		$(this).toggleClass("radio");
		$(this).find("input[type='radio']").prop("checked",true);
		if($(this).siblings("button").hasClass("radio"))
		$(this).siblings("button").removeClass("radio");
	});


});