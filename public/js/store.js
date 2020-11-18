$(document).ready(function(){
    /*StorePage*/
$(".products").each(function(){
	if($(this).find(".row .product").length > 12)
		$(this).find(".row .product:gt(11)").hide();
});
  
    $("#products-sale .main-container .showall").click(function(){
        $(this).parent(".main-container").find(".products .row .product").slice(12).slideToggle(200);
        
    });

    $(".category .chose .cate").click(function(){
        $(".category .chose .cate ul").slideToggle(500);  
    });
      /*StoreBrandPage*/
    $(".info .info1 .products-sale .main-container .products").each(function(){
	if($(this).find(".row .product").length > 10)
		$(this).find(".row .product:gt(9)").hide();
});
    
        $(".sort .sorting").click(function(){
        $(".sort .sorting ul").slideToggle(500);  
        
    });
   

    // $(".brands .brands1 input[type=checkbox]").click(function(){
    //     var sortBy = $('.sortByInput').val();
    //     var fromPrice = 0; //$('.sort').find('.fromPrice').val();
    //     var toPrice = 0 ; //$('.toPrice').val();
    //     //alert(sortBy);
    //     var stars = [];
    //     var colors = [];
    //     var sizes = [];
    //         $.each($("input[name='stars']:checked"), function(){
    //             stars.push($(this).val());
    //         });

    //         $.each($("input[name='colors']:checked"), function(){
    //             colors.push($(this).val());
    //         });

    //         $.each($("input[name='sizes']:checked"), function(){
    //             sizes.push($(this).val());
    //         });
        
    // });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
});