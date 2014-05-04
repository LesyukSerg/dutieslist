	function check_done_duty(item, status){
		var d_value = item.find('.duty_id').val();
			
		$.ajax({
			type: 'POST',
			url: 'call_method.php',
			data: {
				action: 'check_done_duty',
				id_duty: d_value,
				new_status: status,
			}
		}).success(function (data){
			if(data == "OK"){
				if(status == 'DONE'){
					item.removeClass('OLD').removeClass('NEW').addClass('DONE');
					item.find('.status').html('<span>статус:</span> виконано');
				}else{
					item.removeClass('OLD').removeClass('DONE').addClass('NEW');
					item.find('.status').html('<span>статус:</span> не виконано');
				}
			}
		}).error(function (){
			console.log('Ошибка при получении данных');
		});
	}
	
	function delete_duty(item, idd){
		$.ajax({
			type: 'POST',
			url: 'call_method.php',
			data: {
				action: 'delete_duty',
				id_duty: idd,
			}
		}).success(function (data){
			if(data == "DELETED"){
				item.slideUp().remove();
			}
		}).error(function (){
			console.log('Ошибка при получении данных');
		});
	}
	
	function check_text(elem){
		text = elem.val();
		if(text.length > 10){
			$('.message2').html('');
			elem.css('border-color', '#888888');
			return true;
		}else{
			$('.message2').html('Введіть текст завдання');
			elem.css('border-color', 'red');
			return false;
		}
	}
	
	function check_date(elem){
		date = elem.val();
		filter = /^\d{4,}-\d{2,}-\d{2,}$/;
		if(filter.test(date)){
			elem.css('border-color', '#888888');
			return true;
		}else{
			$('.message2').html($('.message2').html()+'<br />Не вірний формат дати');
			elem.css('border-color', 'red');
			return false;
		}
	}
	
	
	
	$(document).ready(function(){
		$('#add_duty').click(function(){
			$('#add_duty').slideUp();
			$('.add_duty_window').slideDown();
		});
		
		$('#cancel').click(function(){
			$('#add_duty').slideDown();
			$('.add_duty_window').slideUp();
			$('.duty_content').val('');
			$('.time_to_done').val('');
		});
		
		$('.done_or_not').click(function(){
			var item = $(this).closest('.duty_item');
			if($(this).is(':checked')){
				check_done_duty(item, 'DONE');
			}else{
				check_done_duty(item, 'NEW');
			}
		});
		
		$('.dell_duty').click(function(){
			if(confirm("Видалити цю справу")){
				var item = $(this).closest('.duty_item');
				var id = item.find('.duty_id').val();
				
				delete_duty(item, id);
			}
			return false;
		});
		
		$('.add_duty_form').submit(function(){
			var t = check_text($('.duty_content'));
			var d = check_date($('.time_to_done'));
			if(t && d){
				return true;
			}else{
				return false;
			}
		});
	});