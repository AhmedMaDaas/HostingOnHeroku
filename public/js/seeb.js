$(document).ready(function(){
    /**** Start Home Page *******/
    $("[data-toggle=popover]").popover();
    /** Lang select in navbar **/
    // Maximize number of elements in each section
    if($(".products-sale .main-container .products .row .product").length > 6){
        $(".products-sale .main-container .products .row .product:gt(5)").hide();
    }
    if($(".stores-section .stores-div .container .sub-container .stores .row .store").length > 6){
        $(".stores-section .stores-div .container .sub-container .stores .row .store:gt(9)").hide();
    }
    if($(".best-selling .main-container .products .row .product").length > 6){
        $(".best-selling .main-container .products .row .product:gt(5)").hide();
    }
    if($(".for-you .main-container .products .row .product").length > 6){
        $(".for-you .main-container .products .row .product:gt(5)").hide();
    }

    $(".lang-ch").find("a").click(function(){
               if(!($(this).hasClass("selected-lang"))){
                   $(this).siblings().removeClass("selected-lang");
                   $(this).addClass("selected-lang");
               } 
    });

    //Start change of product stars
    var okRateStar ="star-24px.svg";
    var noRateStar ="star_border-24px.svg";
    $(document).on('click', '.product .product-details .rating > img', function(){
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

    /** Change color of search icon **/
    $(".input-group-append .search-btn").hover(function(){
        $(this).find("img").removeClass("filter-fairouzi").addClass("filter-orange");
    },function(){
        $(this).find("img").removeClass("filter-orange").addClass("filter-fairouzi");
    });
    /** Change color of card icon **/
    $(".shopping-card").hover(function(){
        $(this).removeClass("filter-fairouzi").addClass("filter-orange");
    },function(){
        $(this).removeClass("filter-orange").addClass("filter-fairouzi");
    });
    $(".shopping-card").click(function(){

        var thisVar = $(this);
        var productId = $(this).parent().parent().find(".product-id").val();
        var meta = $(this).parent().parent().find('meta[name="_token"]').attr('content');
        var button = "buyProduct";

        var src = $(this).attr('src');
        let nameSplit = src.split("/");
        let lastSplit = nameSplit[nameSplit.length - 1];
        nameSplit.pop();
        var newSrc = nameSplit.join('/');

        $.ajax({
          url: "/home",
          type: 'POST',
          context:this,
          data: {
            '_token' :meta,
            'productId': productId,
            'button': button,
          }, 
          dataType: 'json',
          success: function (response) {
            //if(response.operation == 'login')//location.href="/login";
            if(response.opertaion == 'failed'){
                // thisVar.css("opacity","1");
                // thisVar.removeClass("filter-fairouzi");
                // thisVar.addClass("filter-orange");
            }else{
                $('#sumQuantity').text(response.sumQuantityAndTotalCost[1]);
                thisVar.attr("src",newSrc.toString()+'/'+"check_circle-24px.svg").addClass("added");
            }
            //alert("suc ");
          },
          error: function (response) {
            alert("error ");
            //location.href="{{route('log')}}";
          },
        });

        // if($(this).hasClass("added")){
        //     $(this).attr("src","./icons/shopping_cart-24px.svg").removeClass("added");
        // }else{
        //      $(this).attr("src","./icons/check_circle-24px.svg").addClass("added");
        // }
    });
    $(document).on('click', '.love', function(){

        var thisVar = $(this);
        var productId = $(this).parent().find(".product-id").val();
        var meta = $(this).parent().find('meta[name="_token"]').attr('content');
        var button = "love";

        $.ajax({
          url: "/home",
          type: 'POST',
          context:this,
          data: {
            '_token' :meta,
            'productId': productId,
            'button': button,
          }, 
          dataType: 'json',
          success: function (response) {
            //alert(response.operation);
            //if(response.operation == 'login')location.href="/login";
            if(response.statusLove == 1){
                thisVar.css("opacity","1");
                thisVar.removeClass("filter-fairouzi");
                thisVar.addClass("filter-orange");
            }else{
                thisVar.css("opacity","0.5");
                thisVar.removeClass("filter-orange");
                thisVar.addClass("filter-fairouzi");
            }
            //alert("suc ");
          },
          error: function (response) {
            alert("error ");
            //location.href="{{route('log')}}";
          },
        });
        // if($(this).css("opacity")==0.5){
        //     $(this).css("opacity","1");
        //     $(this).removeClass("filter-fairouzi");
        //     $(this).addClass("filter-orange");
        // }else{
        //     $(this).css("opacity","0.5");
        //     $(this).removeClass("filter-orange");
        //     $(this).addClass("filter-fairouzi");
        // }
        
    });
    

    $(document).on('click', '#modal-log', function(){
        var email = $('#log_email').val();
        var password = $('#log_pass').val();
        var remember_me = $('#remember_me').prop("checked");
        var meta = $(this).parent().find('meta[name="_token"]').attr('content');
        

        $.ajax({
          url: "/login",
          type: 'POST',
          context:this,
          data: {
            '_token' :meta,
            'email': email,
            'password': password,
            'remember_me' : remember_me,
          }, 
          dataType: 'json',
          success: function (response) {
            //alert(response.operation);
            if(response.operation == 'failed'){
                if(response.errors.length<=1){
                    $('#errors_log').css("display","");
                    $('#errors_log').text(response.errors);
                }else{
                    $('#errors_log').text('');
                    $.each(response.errors,function(i,error){
                        $('#errors_log').css("display","");
                        $('#errors_log').append(error[0]+'<br>');
                    });
                }
                
                //$('#errors').text()
            }else{
                $('#login-modal').modal('hide');
                //$('#login-modal').replaceWith("display","none");
                // var oldUrl = $('#log-li').attr("href"); // Get current url
                // var newUrl = oldUrl.replace(oldUrl, "/logout");
                $('#log-li').attr("href", "/logout");
                $('#log-li').text("logout");
                $('#reg-li').text("");
                $(".product").find(".shopping-card").attr("data-target","");
                $(".product").find(".love").attr("data-target","");
            }
          },
          error: function (response) {
            alert("error ");
            //location.href="{{route('log')}}";
          },
        });
        
    });

    $(document).on('click', '#modal-reg', function(){

            var fname = $('#reg_fname').val();
            var lname = $('#reg_lname').val();
            var email = $('#reg_email').val();
            var password = $('#reg_password').val();
            var confirmpassword = $('#reg_confirmpassword').val();
            var phone = $('#reg_phone').val();
            var meta = $(this).parent().find('meta[name="_token"]').attr('content');
            alert(phone);

            $.ajax({
              url: "/register",
              type: 'POST',
              context:this,
              data: {
                '_token' :meta,
                'fname' : fname,
                'lname' : lname,
                'email': email,
                'password': password,
                'confirmpassword' : confirmpassword,
                'phone' : phone,
              }, 
              dataType: 'json',
              success: function (response) {
                alert(response.operation);
                if(response.operation == 'failed'){
                    if(response.errors.length<=1){
                        $('#errors_reg').css("display","");
                        $('#errors_reg').text(response.errors);
                    }else{
                        $.each(response.errors,function(i,error){
                            $('#errors_log').text('');
                            $('#errors_reg').css("display","");
                            $('#errors_reg').append(error[0]+'<br>');
                        });
                    }
                    
                    //$('#errors').text()
                }else{
                    $('#login-modal').modal('hide');
                    $('#log-li').attr("href", "/logout");
                    $('#log-li').text("logout");
                    $('#reg-li').text("");
                    $(".product").find(".shopping-card").attr("data-target","");
                    $(".product").find(".love").attr("data-target","");
                }
              },
              error: function (response) {
                alert("error ");
                //location.href="{{route('log')}}";
              },
            });
            
        });

    // control select search change and input hidden
    $(".nav-bottom").find(".dropdown-menu .dropdown-item").click(function(){
        var txt = $(this).text();
        $(".select-search").text(txt);
        if($(this).hasClass("drop-all")){
            $(".select-search-val").val("all");
        }else if($(this).hasClass("drop-mall")){
            $(".select-search-val").val("malls");
        }else if($(this).hasClass("drop-product")){
            $(".select-search-val").val("products");
        }else if($(this).hasClass("drop-department")){
            $(".select-search-val").val("departments");
        }
    });
    // category click
    $(".swiper-slide > img").click(function(){
       $(this).data('clicked', true);
        $(this).css("opacity","100%");
        $(this).parent().find("span").css("display","block");
        $(this).parent().siblings().find("img").css("opacity","65%");
        $(this).parent().siblings().find("span").css("display","none");
        $(this).parent().siblings().find("img").data('clicked', false);
    });
    $(".swiper-slide > img").mouseenter(function(){
        $(this).css("opacity","100%");
        $(this).parent().find("span").css("display","block");
    });
    $(".swiper-slide > img").mouseleave(function(){
        if(!($(this).data('clicked'))){
            $(this).css("opacity","65%");
            $(this).parent().find("span").css("display","none");
        }
        
    });
    // side bar hover
    $(".links-bar > a,.social-bar > a").hover(function(){
        $(this).find("img").removeClass("filter-fairouzi").addClass("filter-orange");
    },function(){
        $(this).find("img").removeClass("filter-orange").addClass("filter-fairouzi");
    });
    // hide side bar on footer
    if ($(".links-bar").length > 0){
        $(window).scroll(function(){
            if($(window).scrollTop() > $(".collections-bottom").offset().top){
                $(".links-bar,.social-bar").fadeOut(1000);
            }else{
                $(".links-bar,.social-bar").fadeIn(700);
            }
        });
    }
    // hide the flow products
    // if($(".products-sale .main-container .products .row .product").offset().top > $(".products-sale .main-container").height){
    //     $(".products-sale .main-container .products .row .product").css("display","none");
    // }
    // Get product id when click on heart
    $(".product .love").click(function(){
       //var product_id = $(this).parent().find(".product-id").val();
       // alert(product_id);
       //  console.log(product_id);
    });
    // Get product id when click on card
    $(".product .product-details .shopping-card").click(function(){
       var product_id = $(this).parent().parent().find(".product-id").val();
        console.log(product_id);
    });
    /**** End Home Page *******/
    /**** Start Best Selling Page *******/
    $(".cats-side-bar > a").hover(function(){
        $(this).find("img").removeClass("filter-gray").addClass("filter-orange");
    },function(){
        $(this).find("img").removeClass("filter-orange").addClass("filter-gray");
    });
    // hide cats side bar on footer
    if ($(".cats-side-bar").length > 0){
        $(window).scroll(function(){
            if($(window).scrollTop() >= $(".for-you").offset().top){
                $(".cats-side-bar").fadeOut(1000);
            }else{
                $(".cats-side-bar").fadeIn(700);
            }
        });
    }
    /**** End Best Selling Page *******/
    /**** Start Just For You Page *******/
    // Show More
    $('.just-for-you-section').find(".showall").click(function () {
        $('.just-for-you-section').find(".product:hidden").slice(0, 15).show();
        if ($('.just-for-you-section').find(".product").length == $('.just-for-you-section').find(".product:visible").length) {
            $('.just-for-you-section').find(".showall").hide();
        }
    });
    /**** End Just For You Page *******/

    /**** Start About Us Page *******/
    // Swiper General Demo
    var swiper = new Swiper('.general-demo-swiper', {
      effect: 'coverflow',
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 'auto',
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
      },
      
    });
    /**** End About Us Page *******/

    /**** Start Log In Page *******/
    $(".log-in-container .log-in-form-container .log-in-form .input-container input[type='checkbox']").click(function(){
        $(this).parent().toggleClass("checked");
    });
    $(".log-in-container .log-in-form-container .log-in-form .input-container input").val("remember");
    /**** End Log In Page *******/
    
    
    // $("body").niceScroll({
    //   cursorwidth:"7px",
    //     cursorcolor: "#0D958B",
    //     cursoropacitymin: 0.3,
    //     background: "#F6F6F6",
    //     cursorborder: "0",
    //     autohidemode: false,
    //     horizrailenabled: false,
    //     zindex: 99999
    // });
    $(".navbar .navbar-nav .dropdown .dropdown-menu").niceScroll({
      cursorwidth:"7px",
        cursorcolor: "#0D958B",
        cursoropacitymin: 0.3,
        background: "#F6F6F6",
        cursorborder: "0",
        autohidemode: false,
        horizrailenabled: false,
        zindex: 99999
    });
    $(".nav-bottom .input-group .input-group-append .dropdown-menu,.sub-container .stores").niceScroll({
      cursorwidth:"7px",
        cursorcolor: "#0D958B",
        cursoropacitymin: 0.3,
        background: "#F6F6F6",
        cursorborder: "0",
        horizrailenabled: false,
        zindex: 99999
    });


});
    var swiper = new Swiper('.swiper-container', {
      slidesPerView: 'auto',
      direction: getDirection(),
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      on: {
        resize: function () {
        
        }
      }
    });

    function getDirection() {
      var windowWidth = window.innerWidth;
      var direction = window.innerWidth <= 760 ? 'horizontal' : 'horizontal';

      return direction;
    }