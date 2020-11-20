$(document).ready(
		function() {
        $(".add").click(function(){
        $(".newcomment").slideToggle(1000);
    });
var slider = $("#jquery-slideshow"), // cache the slider object for later use
            item_width = slider.parent().outerWidth(), // get the width of the container for later use
            timer = null; // will reference our autoplay() timer
 
        // Adjust the slider when/if the window gets resized
        $( window ).on( "resize", adjust );
        adjust();
 
        // We have more than one slide,
        // let's add the pagination buttons
        if ( slider.children("li").length > 1 ) {
 
            // Add previous/next buttons
            slider.parent().append("<a href=\"#\" id=\"btn-prev\"><i class=\"fa fa-angle-left\"></i><span>Previous</span></a><a href=\"#\" id=\"btn-next\"><i class=\"fa fa-angle-right\"></i><span>Next</span></a>");
 
            // Handle clicks on the next button
            slider.parent().on("click", "a#btn-prev", function(e){
                e.preventDefault();
 
                slider.children("li:last").prependTo( slider );
                slider.css("left", -item_width);
 
                slider.animate({
                    left: 0
                }, 300, "swing");
            });
 
            // Handle clicks on the previous button
            slider.parent().on("click", "a#btn-next", function(e){
                e.preventDefault();
 
                slider.animate({
                    left: -item_width
                }, 300, "swing", function(){
                    slider.children("li:first").appendTo( slider );
                    slider.css("left", 0);
                });
            });
        }
 
        // Autoplay
        autoplay();
 
        // Pause/resume autoplay on hover in/out
        slider.hover(function(){
            if ( timer ) {
                clearInterval(timer);
                timer = null;
            }
        }, function(){
            autoplay();
        });
 
        // Helpers
        function autoplay(){
            if ( $("a#btn-next").length ) {
                timer = setInterval(function(){
                    $("a#btn-next").trigger("click");
                }, 3000);
            }
        }
 
        function adjust(){
            item_width = slider.parent().outerWidth();
            slider.children("li").width( item_width ).parent().width( item_width * slider.children("li").length );
        }
            $(".colors > li").each(function(){
                var color = $(this).find(".color-qual").siblings().find(".color-label").text();
                console.log(color);
            });
            $(".color-label").each(function(){
                $(this).parent().find(".color-qual").css("background-color",$(this).text());
               
            });
            var audioElement = document.getElementById("like-sound");
            $(".like-btn").click(function(){
                if($(this).hasClass("active-like")){
                    $(this).removeClass("active-like");
                }
                else{
                     audioElement.play();
                    $(this).addClass("active-like");
                }
            });
          
            
             $(".product-item .heart-icon").click(function(){
            if($(this).hasClass("jam-heart-f")){
               $(this).removeClass("jam-heart-f").addClass("jam-heart");
               }else if($(this).hasClass("jam-heart")){
                         $(this).removeClass("jam-heart").addClass("jam-heart-f");
                         }
        });
                  var audioChoose = document.getElementById("choose-sound");
            $(".color-qual").click(function(){
            if(!($(this).hasClass("active-color"))){
               $(this).parent().siblings().find(".color-qual").removeClass("active-color");
                $(this).parent().siblings().find(".color-label .check-mark").css("display","none");
                $(this).addClass("active-color").parent().find(".color-label .check-mark").css("display","inline");
                audioChoose.play();
               }else{
                   $(this).removeClass("active-color").parent().find(".color-label .check-mark").css("display","none");
               }
        });
            $(".size-ch").click(function(){
                if(!($(this).hasClass("active-size"))){
                    $(this).addClass("active-size").find(".label-size").css("color","#FFF");
                    $(this).parent().siblings().find(".size-ch").removeClass("active-size").find(".label-size").css("color","#0d958c");
                    audioChoose.play();
                }else{
                    $(this).removeClass("active-size").find(".label-size").css("color","#0d958c");
                }
            });
            
   
});