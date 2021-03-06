function check_all(){
	$('input[class="item"]:checkbox').each(function(){
		if($('input[class="check_all"]:checkbox:checked').length == 0){
		  	$(this).prop('checked', false);
		}
		else{
		  	$(this).prop('checked', true);
		}
	});
};

$(document).ready(function(){

	let simplepicker = new SimplePicker({
      zIndex: 10
    });

    var input = null;

    $(document).on('click', '.datepicker', function(){
    	simplepicker.open();
    	input = $(this);
    });

    simplepicker.on('submit', (date, readableDate) => {
      input.val(readableDate);
      if(!input.parent('div').hasClass('is-filled')) input.parent('div').addClass('is-filled');
    });



	$('.delete_all').on('click', function(){
		$('#data_form').submit();
	});

	$(document).on('click', '.confirm', function(){
		var recordsCount = $('input[class="item"]:checkbox:checked').length;
		if(recordsCount == 0){
			$('.records-count').html('');
			$('.not_empty').prop('hidden', true);
			$('.empty_record').prop('hidden', false);
			$('.not_empty_button').prop('type', 'hidden');
		}
		else{
			$('.records-count').html(recordsCount);
			$('.not_empty').prop('hidden', false);
			$('.empty_record').prop('hidden', true);
			$('.not_empty_button').prop('type', 'submit');
		}
		$('#multipleDelete').modal('show');
	});
});