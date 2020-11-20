$(function(){
   $(".continue").click(function(){
      $(".delivery").slideDown(1000);
   });
    
    var okRateStar ="star-24px.svg";
    var noRateStar ="star_border-24px.svg";
    $(document).on('click', '.media-body .rating > img', function(){
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


    $(".buy .select .selectall").click(function(){

  if($("[type=checkbox]").is(":checked"))
    $("[type=checkbox]").attr("checked",false);
  else
      $("[type=checkbox]").attr("checked",true);
});
  $(".store .check [type=checkbox]").click(function(){
    if($(this).parent(".check").parent(".store").find(".list li [type=checkbox]").is(":checked"))
    $(this).parent(".check").parent(".store").find(".list li [type=checkbox]").attr("checked",false);
  else
      $(this).parent(".check").parent(".store").find(".list li [type=checkbox]").attr("checked",true);  
    
  });
    
});