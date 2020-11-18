$(document).ready(function(){
$(".products").each(function(){
	if($(this).find(".row .product").length > 6)
		$(this).find(".row .product:gt(5)").hide();
});

var okRateStar ="star-24px.svg";
    var noRateStar ="star_border-24px.svg";
    $(document).on('click', '.options .rating > img', function(){
        var indexStar = $(this).index();
        var src = $(this).attr('src');
        let nameSplit = src.split("/");
        let lastSplit = nameSplit[nameSplit.length - 1];
        nameSplit.pop();
        var newSrc = nameSplit.join('/');
        //alert(newSrc);

        if(lastSplit==okRateStar){
            if(($(this).next("img").attr('src'))==newSrc.toString()+'/'+noRateStar || $(this).is(":last-of-type")){
                $(this).parent().find(".index-star").val(indexStar-1);
                $(this).attr('src',newSrc.toString()+'/'+noRateStar);
            }else{
                $(this).nextAll().filter("img").attr('src',newSrc.toString()+'/'+noRateStar);
                $(this).parent().find(".index-star").val(indexStar);
            }
            
        }else if(lastSplit==noRateStar){
            $(this).attr('src',newSrc.toString()+'/'+okRateStar);
            $(this).prevAll().filter("img").attr('src',newSrc.toString()+'/'+okRateStar);
            $(this).nextAll().filter("img").attr('src',newSrc.toString()+'/'+noRateStar);
            $(this).parent().find(".index-star").val(indexStar.toString());
        }
        var star = $(this).parent().find(".index-star").val();
        console.log($(this).parent().find(".index-star").val());

        var productId = $(this).parent().parent().parent().find(".product-id").val();
        var meta = $(this).parent().parent().parent().find('meta[name="_token"]').attr('content');
        var button = "evaluation";
        alert(productId);

        $.ajax({
          url: "/home",
          type: 'POST',
          data: {
            '_token' :meta,
            'productId': productId,
            'star' : star,
            'button': button,
          }, 
          dataType: 'json',
          success: function (response) {
            alert(response.operation);
            if(response.operation == 'login')location.href="/login";
            // if(response.operation == 'success'){
            //     thisVar.css("opacity","1");
            //     thisVar.removeClass("filter-fairouzi");
            //     thisVar.addClass("filter-orange");
            // }else{
            //     thisVar.css("opacity","0.5");
            //     thisVar.removeClass("filter-orange");
            //     thisVar.addClass("filter-fairouzi");
            // }
          },
          error: function (response) {
            alert("error ");
            //location.href="{{route('log')}}";
          },
        });

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