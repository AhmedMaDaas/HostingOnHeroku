$(document).ready(
		function() {
        
			/*var images = [ "crown-group-modern-motion-374894.jpg",
					"pexels-anna-shvets-4482896.jpg",
					"banner.jpg" ];*/
            $(".lang-ch").find("a").click(function(){
               if(!($(this).hasClass("selected-lang"))){
                   $(this).siblings().removeClass("selected-lang");
                   $(this).addClass("selected-lang");
               } 
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
        $(".product-item .heart-icon").click(function(){
            if($(this).hasClass("jam-heart-f")){
               $(this).removeClass("jam-heart-f").addClass("jam-heart");
               }else if($(this).hasClass("jam-heart")){
                         $(this).removeClass("jam-heart").addClass("jam-heart-f");
                         }
        });
            $(".lang-choose").click(function(){
                $(".langs").slideToggle(400);
            });
			
		});
var contents = [
                "Shopping Online",
                "Get Offers",
                "All Family Needs",
                "In One Place"
            ];
			$(function() {
				var i = 0;
				/*$(".image-im")
						.css("background-image", "url(./images/" + images[i] + ")");*/
                $(".contents").text(contents[i]);
				setInterval(function() {
					i++;
					if (i == contents.length) {
						i = 0;
					}
					/*$(".image-im").fadeOut("fast", function() {
						$(this).css("background-image", "url(./images/" + images[i] + ")");
						$(this).fadeIn("fast");
					});*/
                    $(".contents").fadeOut("fast", function() {
						$(this).text(contents[i]);
						$(this).fadeIn("fast");
					});
				}, 4000);
			});