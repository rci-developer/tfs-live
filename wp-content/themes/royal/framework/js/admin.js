jQuery(document).ready(function(){

	/* Promo banner in admin panel */

	jQuery('.promo-text-wrapper .close-btn').click(function(){

		var confirmIt = confirm('Are you sure?');

		if(!confirmIt) return;

		var widgetBlock = jQuery(this).parent();

		var data =  {
			'action':'et_close_promo',
			'close': widgetBlock.attr('data-etag')
		};

		widgetBlock.hide();

		jQuery.ajax({
			url: ajaxurl,
			data: data,
			success: function(response){
				widgetBlock.remove();
			},
			error: function(data) {
				alert('Error while deleting');
				widgetBlock.show();
			}
		});
	});

	/* Theme versions masonry */

    $versions = jQuery('.et-theme-versions');


    $versions.each(function() {
        var version = jQuery(this);
        version.isotope({
            itemSelector: '.theme-ver'
        });
        jQuery(window).smartresize(function(){
            version.isotope({
                itemSelector: '.theme-ver'
            });
        });

        version.parent().find('.versions-filters a').click(function(){
            var selector = jQuery(this).attr('data-filter');
            version.parent().find('.versions-filters a').removeClass('active');
            if(!jQuery(this).hasClass('active')) {
                jQuery(this).addClass('active');
            }
            version.isotope({ filter: selector });

            return false;
        });
    });

    jQuery(window).resize();
    jQuery('.et-theme-versions').addClass('with-transition');
    jQuery('.theme-ver').addClass('with-transition');



	/* UNLIMITED SIDEBARS */

	var delSidebar = '<div class="delete-sidebar">delete</div>';

	jQuery('.sidebar-etheme_custom_sidebar').find('.handlediv').before(delSidebar);

	jQuery('.delete-sidebar').click(function(){

		var confirmIt = confirm('Are you sure?');

		if(!confirmIt) return;

		var widgetBlock = jQuery(this).closest('.sidebar-etheme_custom_sidebar');

		var data =  {
			'action':'etheme_delete_sidebar',
			'etheme_sidebar_name': jQuery(this).parent().find('h2').text()
		};

		widgetBlock.hide();

		jQuery.ajax({
			url: ajaxurl,
			data: data,
			success: function(response){
				console.log(response);
				widgetBlock.remove();
			},
			error: function(data) {
				alert('Error while deleting sidebar');
				widgetBlock.show();
			}
		});
	});


	/* end sidebars */


    jQuery('.importBtn').toggle(function(){
	    jQuery(this).next().show();
    },function(){
	    jQuery(this).next().hide();
    });

    // **********************************************************************//
	// ! Theme deactivating action
	// **********************************************************************//

	jQuery( '.etheme-deactivator' ).click( function(event) {
	event.preventDefault();

	var confirmIt = confirm( 'Are you sure?' );
	if( ! confirmIt ) return;

	var data =  {
		'action':'etheme_deactivate_theme',
	};

	var redirect = window.location.href;

	redirect = redirect.replace( 'ot-theme-options', 'etheme_activation_page' );

	jQuery.ajax({
		url: ajaxurl,
		data: data,
		success: function(data){
			console.log(data);
		},
		error: function(data) {
			alert('Error while deactivating');
		},
		complete: function(){
            window.location.href=redirect;
		}
	});
});


    /****************************************************/
    /* Import XML data */
    /****************************************************/

    var importBtn = jQuery('#install_demo_pages');

	importBtn.bind("click", (function(e){
		e.preventDefault();

        var style = jQuery('#demo_data_style').val();

		if(!confirm('Are you sure you want to install base demo data? (It will change all your theme configuration, menu etc.)')) {

			return false;

		}

		importBtn.after('<div id="floatingCirclesG" class="et-loading"><div class="f_circleG" id="frotateG_01"></div><div class="f_circleG" id="frotateG_02"></div><div class="f_circleG" id="frotateG_03"></div><div class="f_circleG" id="frotateG_04"></div><div class="f_circleG" id="frotateG_05"></div><div class="f_circleG" id="frotateG_06"></div><div class="f_circleG" id="frotateG_07"></div><div class="f_circleG" id="frotateG_08"></div></div>');
        importBtn.text('Installing demo data... Please wait...').addClass('disabled').attr('disabled', 'disabled').unbind('click');

		jQuery.ajax({
			method: "POST",
			url: ajaxurl,
			data: {
				'action':'etheme_import_ajax'
			},
			success: function(data){
				jQuery('#option-tree-sub-header').before('<div id="setting-error-settings_updated" class="updated settings-error">' + data + '</div>');
			},
			complete: function(){
                jQuery('#floatingCirclesG').remove();
                //jQuery('.installing-info').remove();
                importBtn.addClass('green');
                importBtn.text('Successfully installed!');
			}
		});

	}));

	var installProccess = false;

	jQuery('.install-ver').click(function(e) {
		e.preventDefault();
		jQuery('.ver-install-result').html('');
		if(installProccess) return;
		installProccess = true;
		var version = jQuery(this).data('ver');
		var home_id = jQuery(this).data('home_id');

		jQuery(this).after('<div id="floatingCirclesG" class="et-loading"><div class="f_circleG" id="frotateG_01"></div><div class="f_circleG" id="frotateG_02"></div><div class="f_circleG" id="frotateG_03"></div><div class="f_circleG" id="frotateG_04"></div><div class="f_circleG" id="frotateG_05"></div><div class="f_circleG" id="frotateG_06"></div><div class="f_circleG" id="frotateG_07"></div><div class="f_circleG" id="frotateG_08"></div></div>');

		jQuery.ajax({
			method: "POST",
			url: ajaxurl,
			data: {
				'action':'etheme_install_version',
				'ver': version,
				'home_id': home_id
			},
			success: function(data){
				jQuery('.ver-install-result').html('').html(data);
			},
			complete: function(){
                jQuery('#floatingCirclesG').remove();
                installProccess = false;
                location.reload();
			}
		});

	});

	var installPageProccess = false;
	jQuery( '.install-page' ).on('click', function(e){
		e.preventDefault();
		if( installPageProccess ) {
			return;
		}
		installPageProccess = true;

		var selectPage = jQuery('body').find('#demo_data_pages');
		var selectedPage = selectPage.find('option:selected');

		jQuery(this).after('<div id="floatingCirclesG" class="et-loading"><div class="f_circleG" id="frotateG_01"></div><div class="f_circleG" id="frotateG_02"></div><div class="f_circleG" id="frotateG_03"></div><div class="f_circleG" id="frotateG_04"></div><div class="f_circleG" id="frotateG_05"></div><div class="f_circleG" id="frotateG_06"></div><div class="f_circleG" id="frotateG_07"></div><div class="f_circleG" id="frotateG_08"></div></div>');

		jQuery.ajax({
			method:"POST",
			url: ajaxurl,
			data:{
				'action': 'etheme_install_version',
				'ver': selectedPage.attr('value')
			},
			success:function(response){
				jQuery( '.ver-install-result' ).html( '' ).html( response );
			},
			complete: function(){
                jQuery('#floatingCirclesG').remove();
				var verOffset = jQuery( '#setting_demo_data' ).offset();
				jQuery( 'body,html' ).animate( {scrollTop: verOffset.top}, 1000 );
                installPageProccess = false;
				setTimeout(function(){
					location.reload();
				},2000);
			}
		});
	});

	var selected = jQuery('#demo_data_pages option:selected'),
		url = jQuery( '#demo_data_pages' ).data( 'url' );

	if ( jQuery('#demo_data_pages option:selected').length ) {
		jQuery( '.demo-page-preview-wrapper img' ).attr( 'src', url + selected.attr('value') + '/screenshot.jpg' );
		jQuery( '.demo-page-preview-wrapper a' ).attr( 'href', selected.attr('data-preview') );
	}

	jQuery('#demo_data_pages').on('change', function(e){
		selected = jQuery('#demo_data_pages option:selected');
		jQuery( '.demo-page-preview-wrapper img' ).attr( 'src', url + selected.attr('value') + '/screenshot.jpg' );
		jQuery( '.demo-page-preview-wrapper a' ).attr( 'href', selected.attr('data-preview') );
	});

});
