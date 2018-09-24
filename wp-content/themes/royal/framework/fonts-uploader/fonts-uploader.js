jQuery(function($){

    function readURL( file ) {
        if ( file ) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var out = '<div class=\"et_pre-file\">';
                out += '<p class=\"et_pre-name\">' + file.name + '</p>';
                out += '</div>';
                $('.et-file-zone').after(out);
            }
            reader.readAsDataURL(file);
        }
    }

    $('#et-fonts').change(function(){
        $.each( this.files, function( e, t ) {
           readURL(t);
        });
    });

    var form = '\
            <form id="et-fonts-uploader" enctype="multipart/form-data" method="post">\
                <div class="et-file-zone">\
                    <label for="et-fonts">Choose fonts for upload</label>\
                    <input name="et-fonts" type="file" id="et-fonts" accept=".eot, .woff2, .woff, .ttf, .otf">\
                </div>\
                <p><input id="et-upload" name="et-upload" type="submit" value="upload"></p>\
            </form>\
        ';

    $(document).on( 'click', '.add-form', function(e) {
        e.preventDefault();
        if ( $( '#et-fonts-uploader' ).length > 0 ) return;
        $(this).after(form);
        $(this).remove();
    });


    $(document).on( 'click', '.et_font-remover', function(e) {
        e.preventDefault();
        if ( ! confirm( 'Are you sure?' ) ) return;

        var this_btn = $(this);
        var switch_args = [];
        var loader = '<div class="et-loader"></div>';

        this_btn.parents( 'li' ).prepend( loader );
        this_btn.parents( 'li' ).addClass( 'et_font-removing' );

        $.ajax({
            type: 'POST',
           	dataType: 'JSON',
              url: ajaxurl,
              data: {
                'action' : 'et_ajax_fonts_remove',
                'id' : this_btn.data( 'id' )
              },
              success: function(response){
                if ( response['status'] == 'success' ){
                    switch_args['add'] = 'et_font-removed';
                    switch_args['remove'] = 'et_font-removing';

                    class_switch( this_btn.parents( 'li' ), switch_args );
                    this_btn.parents( 'li' ).remove();
                } else {
                    switch_args['add'] = 'et_font-removed-fail';
                    switch_args['remove'] = 'et_font-removing';

                    class_switch( this_btn.parents( 'li' ), switch_args );

                    $.each( response['messages'], function( i, t ) {
                        this_btn.after( '<span class="et_font-error"> * ' + t + '</span>' );
                    });
                }
             },
              error: function(response) {
                alert('Error while deleting');
              },
              complete: function(){
              	$( '.etheme-fonts-section .et-loader' ).remove();
              }
          });
    });

    // ! Switch element classes
    function class_switch( element, args ){
        element.addClass( args['add'] ).removeClass( args['remove'] );
    }

});