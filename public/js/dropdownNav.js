$(document).ready(
		function() {
            $(".dropdownNav").click(function(e){
                e.preventDefault();
                $(this).parent().find(".dropdown-m").slideToggle(200);
            });
        });