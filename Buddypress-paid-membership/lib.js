jQuery(document).ready(function($){
	$('#replicator').click(function(e){
		e.preventDefault();
		
		var new_item = $('#account-types li:first-child').clone();
		new_item.find('input.label').attr('name', new_item.find('input.label').attr('name').replace('0', $('#account-types li').size()));
		new_item.find('input.price').attr('name', new_item.find('input.price').attr('name').replace('0', $('#account-types li').size()));
		new_item.find('input').val('');
		
		$('#account-types').append(new_item);
	});
	
	$('.remove-replicator').live('click', function(){
		$(this).parents('li').remove();
	});
});