cms.init.add(['datasources_data_index'], function () {
	
	var chbx_change = function() {
		var length = checkboxes.length;

		var filtered_length = checkboxes.filter(':checked').length;

		if(length == filtered_length) {
			chbx_all.check();
		}
		else {
			chbx_all.uncheck();
		}
	}
	
	var checkboxes = $('#headline table tbody .row-checkbox input').change(chbx_change);
	
	var chbx_all = $('#cb-all input').change(function() {
		if(!$(this).checked()){
			checkboxes.uncheck().change();
		}else {
			checkboxes.check();
		}
		
		chbx_change();
	});
	
	
	$('#apply-doc-action').click(function() {
		var actions = $('#doc-actions');
		var action = actions.val();
		
		var data = checkboxes.filter(':checked');
		
		if(action == 0 || data.length == 0) return;
		
		if(!confirm(__('Are you surre?')))
			return;

		$.post(BASE_URL + '/hybrid/document/' + action , data, function(response) {
			 window.location = CURRENT_URL;
		}, 'json')
		
	})
});

cms.init.add('hybrid_section_edit', function() {
	
	var $fields = $('#section-fields input'),
		$checked_fields = $fields.filter(':checked');
	
	$fields.change(function(){
		if($fields.filter(':checked').size() == 0) {
			$('#remove-fields').attr('disabled', 'disabled');
		} else {
			$('#remove-fields').removeAttr('disabled');
		}
	}).change()
	
	$('#remove-fields').click(function() {
		
		if($checked_fields.length < 1) return false;
		
		if(!confirm(__('Are you surre?')))
			return;
		
		$.post(BASE_URL + '/hybrid/field/remove/' + DS_ID, $checked_fields, function(resp) {
			if(resp.status) {
				for(i in resp.fields) {
					$('#field-' + resp.fields[i]).remove();
				}
			}
		}, 'json');
		
		return false;
	});
});

cms.init.add(['hybrid_document_view', 'hybrid_document_create'], function () {
	$('ul.nav-tabs').tabs();
	
	$('.input-date').datepicker({
        // options
        dateFormat:'yy-mm-dd'
    });
});

cms.init.add(['hybrid_field_add'], function () {

	$('#field-type-select').change(function() {
		var id = $(this).val();
		var fieldset = $('#field-options fieldset');
		
		fieldset
			.attr('disabled', 'disabled')
			.hide()
			.filter('fieldset#f-' + id)
			.show()
			.removeAttr('disabled')
			.end();

	}).change();
});