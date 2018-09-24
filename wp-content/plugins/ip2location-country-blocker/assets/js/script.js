jQuery(document).ready(function($){
	var regex = /^((?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d|\*)(\.|$)){4})|(([0-9a-f]|:){1,4}(:([0-9a-f]{0,4})*){1,7})$/;

	$('#frontend_ip_blacklist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '400px',
		pattern: regex,
		onChange: function(obj, tag){
			if($('#frontend_ip_whitelist').tagExist(tag)){
				$('#frontend_ip_blacklist').removeTag(tag);
			}
		}
	});

	$('#frontend_ip_whitelist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '400px',
		pattern: regex,
		onChange: function(obj, tag){
			if($('#frontend_ip_blacklist').tagExist(tag)){
				$('#frontend_ip_whitelist').removeTag(tag);
			}
		}
	});

	$('#backend_ip_blacklist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '400px',
		pattern: regex,
		onChange: function(obj, tag){
			if($('#backend_ip_whitelist').tagExist(tag)){
				$('#backend_ip_blacklist').removeTag(tag);
			}
		}
	});

	$('#backend_ip_whitelist').tagsInput({
		defaultText: '',
		delimiter: ';',
		width: '400px',
		pattern: regex,
		onChange: function(obj, tag){
			if($('#backend_ip_blacklist').tagExist(tag)){
				$('#backend_ip_whitelist').removeTag(tag);
			}
		}
	});

	refresh_frontend_settings();
	refresh_backend_settings();
	refresh_settings();

	$('.chosen').chosen();

	$('#enable_frontend,input[name=frontend_option]').on('change', function(){
		refresh_frontend_settings();
	});

	$('#enable_backend,input[name=backend_option]').on('change', function(){
		refresh_backend_settings();
	});

	$('input[name=lookup_mode]').on('change', function(){
		refresh_settings();
	});

	$('input[name=px_lookup_mode]').on('change', function(){
		refresh_settings();
	});

	$('#form_backend_settings').on('submit', function(e){
		if($('#enable_backend').is(':checked')){
			if($('#bypass_code').val().length == 0){
				if(($.inArray($('#my_country_code').val(), $('#backend_ban_list').val()) >= 0 && $('input[name=backend_block_mode]:checked').val() == 1) || ($.inArray($('#my_country_code').val(), $('#backend_ban_list').val()) < 0 && $('input[name=backend_block_mode]:checked').val() == 2)){
					alert("==========\n WARNING \n==========\n\nYou are about to block your own country, " + $('#my_country_name').val() + ".\nThis can locked yourself and prevent you from login to admin area.\n\nPlease set a bypass code to avoid this.");
					$('#bypass_code').focus();
					e.preventDefault();
				}
			}
		}
	});

	$('#download').on('click', function(e){
		e.preventDefault();

		if ($('#database_name').val().length == 0 || $('#token').val().length == 0){
			$('#download_status').html('<div id="message" class="error"><p><strong>ERROR</strong>: Please make sure you have entered the login crendential.</p></div>');
			return;
		}

		$('#download_status').html('');
		$('#database_name,#token,#download').prop('disabled', true);
		$('#ip2location-download-progress').show();

		$.post(ajaxurl, { action: 'update_ip2location_country_blocker_database', database: $('#database_name').val(), token: $('#token').val() }, function(response) {
			if (response == 'SUCCESS') {
				alert('Download completed.');

				$('#download_status').html('<div id="message" class="updated"><p>Successfully downloaded the ' + $('#database_name :selected').text() + ' BIN database. Please refresh information by <a href="javascript:;" id="reload">reloading</a> the page.</p></div>');

				$('#reload').on('click', function(){
					window.location = window.location.href.split('#')[0];
				});
			}
			else {
				alert('Download process aborted.');

				$('#download_status').html('<div id="message" class="error"><p><strong>ERROR</strong>: Failed to download ' + $('#database_name :selected').text() + ' BIN database. Please make sure you correctly enter the login crendential.</p></div>');
			}
		}).always(function() {
			$('#database_name').val('');
			
			$('#database_name,#token,#download').prop('disabled', false);
			$('#ip2location-download-progress').hide();
		});
	});

	$('#btn-purge').on('click', function(e) {
		if (!confirm('WARNING: All data will be permanently deleted from the storage. Are you sure you want to proceed with the deletion?')) {
			e.preventDefault();
		}
	});

	function refresh_frontend_settings(){
		if($('#enable_frontend').length == 0)
			return;

		if($('#enable_frontend').is(':checked')){
			$('.input-field,.tagsinput input').prop('disabled', false);

			if($('input[name=frontend_option]:checked').val() != '2'){
				$('#frontend_error_page').prop('disabled', true);
			}

			if($('input[name=frontend_option]:checked').val() != '3'){
				$('#frontend_redirect_url').prop('disabled', true);
			}

			$('.disabled').prop('disabled', true);
			toogleTagsInput(true);
		}
		else{
			$('.input-field').prop('disabled', true);
			toogleTagsInput(false);
		}

		$('.chosen').trigger('chosen:updated');
	}

	function refresh_backend_settings(){
		if($('#enable_backend').length == 0)
			return;

		if($('#enable_backend').is(':checked')){
			$('.input-field,.tagsinput input').prop('disabled', false);

			if($('input[name=backend_option]:checked').val() != '2'){
				$('#backend_error_page').prop('disabled', true);
			}

			if($('input[name=backend_option]:checked').val() != '3'){
				$('#backend_redirect_url').prop('disabled', true);
			}

			$('.disabled').prop('disabled', true);
			toogleTagsInput(true);
		}
		else{
			$('.input-field').prop('disabled', true);
			toogleTagsInput(false);
		}

		$('.chosen').trigger('chosen:updated');
	}

	function refresh_settings(){
		if($('#lookup_mode_bin').is(':checked')){
			$('#bin_database').show();
			$('#bin_download').show();
			$('#ws_access').hide();
		}
		else if($('#lookup_mode_ws').is(':checked')){
			$('#bin_database').hide();
			$('#bin_download').hide();
			$('#ws_access').show();
		}

		if($('#px_lookup_mode_bin').is(':checked')){
			$('#px_bin_database').show();
			$('#bin_download').show();
			$('#px_ws_access').hide();
		}
		else if($('#px_lookup_mode_ws').is(':checked')){
			$('#px_bin_database').hide();
			$('#bin_download').hide();
			$('#px_ws_access').show();
		}
		else{
			$('#px_bin_database').hide();
			$('#px_ws_access').hide();
		}

		if($('#lookup_mode_bin').is(':checked') || $('#px_lookup_mode_bin').is(':checked')){
			$('#bin_download').show();
		}
	}

	function toogleTagsInput(state){
		if(!state){
			$.each($('.tagsinput'), function(i, obj){
				var $div = $('<div class="tagsinput-disabled" style="display:block;position:absolute;z-index:99999;opacity:0.1;background:#808080";top:' + $(obj).offset().top + ';left:' + $(obj).offset().left + '" />').css({
					width: $(obj).outerWidth() + 'px',
					height: $(obj).outerHeight() + 'px'
				});

				$(obj).parent().prepend($div);
			});
		}
		else{
			$('.tagsinput-disabled').remove();
		}
	}
});