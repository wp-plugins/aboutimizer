jQuery(function($){

    function aboutimizer() {
        // Generate image
        window.aboutimizer_generate = function generate() {
            $('.aboutimizer-upload').each(function(){
                parent = $(this).parent();
                image = parent.siblings('input[type="hidden"]').data('url');
                if (image) {
                    parent.hide();
                    parent.siblings('.aboutimizer-upload-preview').html($('<img src="'+image+'"/>').css({'max-width':'100%',height:'auto'}));
                    if ($('.aboutimizer-upload-remove', parent.parent()).length == 0)
                        parent.siblings('.aboutimizer-upload-preview').after('<p><a href="#" class="aboutimizer-upload-remove">'+$(this).data('remove-text')+'</a></p>');
                    else
                        $('.aboutimizer-upload-remove', parent.parent()).parent().show();
                }
            });
        }

        $(document).on('click', '.aboutimizer-upload', function(e){
            e.preventDefault();
            upload_button = this;
            post_id = $(this).data('post-id');

            // Open a new media uploader every time
            wp.media.model.settings.post.id = post_id;

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: $( upload_button ).data( 'uploader-title' ),
                button: {
                    text: $( upload_button ).data( 'uploader-button-text' ),
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                $( upload_button ).parent().prev('input[type="hidden"]')
                    .val(attachment.id)
                    .data('url', attachment.url);
                window.aboutimizer_generate();

                // Restore the main post ID
                if (typeof wp_media_post_id != 'undefined')
                    wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Finally, open the modal
            file_frame.open();
        });

        $(document).on('click', '.aboutimizer-upload-remove', function(e){
            e.preventDefault();
            parent = $(this).parent();
            parent.siblings('input[type="hidden"]').val('');
            parent.siblings('.aboutimizer-upload-preview').html('');
            $('.aboutimizer-upload', parent.parent()).parent().show();
            parent.hide();
        });

        // Restore the main ID when the add media button is pressed
        $('a.add_media').on('click', function() {
            if (typeof wp_media_post_id != 'undefined')
                wp.media.model.settings.post.id = wp_media_post_id;
        });

        // Generate initial images
        window.aboutimizer_generate();
    }
    aboutimizer();
    
});