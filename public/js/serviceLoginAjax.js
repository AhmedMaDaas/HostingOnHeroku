//$(document).ready(function(){

//  start fb login //

     $(document).on('click', '#facebook-log', function(){
        var thisVar = $(this);
        $('#loading').css("display","");
        fbLogin(thisVar);
     });

    
        window.fbAsyncInit = function() {
            // FB JavaScript SDK configuration and setup
            FB.init({
              appId      : '3458622907563040', // FB App ID
              cookie     : true,  // enable cookies to allow the server to access the session
              xfbml      : true,  // parse social plugins on this page
              version    : 'v3.2' // use graph api version 2.8
            });
            
            // Check whether the user already logged in
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    //display user data
                    getFbUserData();
                }
            });
        };
        // Load the JavaScript SDK asynchronously
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
                
        // Facebook login with JavaScript SDK
        function fbLogin(thisVar) {
            FB.login(function (response) {
                if (response.authResponse) {
                    //alert('fblogin');
                    // Get and display the user profile data
                    getFbUserData(thisVar);
                } else {
                    //document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
                    alert('false');
                }
            }, {scope: 'email'});
        }

        // Fetch the user profile data from facebook
        function getFbUserData(thisVar){
            FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender,locale,picture'},
            function (response) {
                //alert(response.picture.data.url);
                saveUserData('facebook',response.id,response.first_name+' '+response.last_name,response.email,response.picture.data.url);
            });
        }

        // Save user data to the database
        // function saveUserData(userData,thisVar){

        //     var button = thisVar.attr('name');
        //     var meta = thisVar.parent().parent().find('meta[name="_token"]').attr('content');
        //     alert(meta);
        //     $.ajax({
        //       url: "/login",
        //       type: 'POST',
        //       context:this,
        //       data: {
        //         '_token' :meta,
        //         'button': button,
        //         //oauth_provider:'facebook',
        //         userData: JSON.stringify(userData),
        //       }, 
        //       dataType: 'json',
        //       success: function (response) {
        //         //alert(response.operation);
        //         if(response.operation == 'failed'){
        //             if(response.errors.length<=1){
        //                 $('#errors_div').css("display","");
        //                 $('#errors_div').append(response.errors+'<br>');
        //             }else{
        //                 $.each(response.errors,function(i,error){
        //                     $('#errors_div').css("display","");
        //                     $('#errors_div').append(error[0]+'<br>');
        //                 });
        //             }
                    
        //             //$('#errors').text()
        //         }else{
        //             $('#login-modal').modal('hide');
        //             $('#log-li').attr("href", "/logout");
        //             $('#log-li').text("logout");
        //             $('#reg-li').text("");
        //         }
        //       },
        //       error: function (response) {
        //         alert("error ");
        //         //location.href="{{route('log')}}";
        //       },
        //     });
        // }

    // end fb login //


    // start login gmail //

    $(document).on('click', '#google-log', function(){
        //var thisVar = $(this);
        $('#loading').css("display","");
        renderButton();
     });
        function renderButton() {
            gapi.signin2.render('g-signin2', {
                'scope': 'profile email',
                'width': 250,
                'height': 40,
                'longtitle': true,
                'theme': 'dark',
                'onsuccess': onSignIn,
                'onfailure': onFailure
            });
        }

        function onSignIn(googleUser) {
            var profile = googleUser.getBasicProfile();
            console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
            console.log('Name: ' + profile.getName());
            console.log('Image URL: ' + profile.getImageUrl());
            console.log('Email: ' + profile.getEmail());
            var googleTockenId = profile.getId();
            var name = profile.getName();
            var email = profile.getEmail();
            var profile = profile.getImageUrl();
            // $("#loaderIcon").show('fast');
            // $("#g-signin2").hide('fast');
            saveUserData('google',googleTockenId,name,email,profile); // save data to our database for reference
        }
        // Sign-in failure callback
        function onFailure(error) {
            alert(error);
        }

        function saveUserData(button,serviceId,name,email,profile) {

            var remember_me = $('#remember_me').prop("checked");
            //var button = thisVar.attr('name');
            var meta = $('#_token').attr('content');
            $.ajax({
              url: "/login",
              type: 'POST',
              context:this,
              data: {
                '_token' :meta,
                'button': button,
                //oauth_provider:'facebook',
                'id': serviceId,
                'name' : name,
                'email' : email,
                'profile' : profile,
                'remember_me' : remember_me,
              }, 
              dataType: 'json',
              success: function (response) {
                //alert(response.operation);
                if(response.operation == 'failed'){
                    if(response.errors.length==1){
                        $('#errors_div').css("display","");
                        $('#errors_div').text(response.errors);
                    }else if(response.errors.length>1){
                        $.each(response.errors,function(i,error){
                            $('#errors_log').text('');
                            $('#errors_div').css("display","");
                            $('#errors_div').append(error[0]+'<br>');
                        });
                    }
                }else{
                    $('#loading').css("display","none");
                    $(".product").find(".shopping-card").attr("data-target","");
                    $(".product").find(".love").attr("data-target","");
                    $('#login-modal').modal('hide');
                    $('#log-li').attr("href", "/logout");
                    $('#log-li').text("logout");
                    $('#reg-li').text("");
                }
              },
              error: function (response) {
                alert("error ");
                //location.href="{{route('log')}}";
              },
            });
        }

        
        // Sign out the user
        // function signOut() {
        //     if(confirm("Are you sure to signout?")){
        //         var auth2 = gapi.auth2.getAuthInstance();
        //         auth2.signOut().then(function () {
        //             $("#loginDetails").hide();
        //             $("#loaderIcon").hide('fast');
        //             $("#g-signin2").show('fast');
        //         });
        //         auth2.disconnect();
        //     }
        // }

// end login with gmail //