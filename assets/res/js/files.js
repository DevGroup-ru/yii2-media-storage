jQuery(function($){
    /////////
    // Add //
    /////////

    $('.media-item-add').click(function(){
        $('.media-storage-library .thumbnail').removeClass('selected-block');
        $('.media-item-new').removeClass('hidden');
        $('.media-item-edit').addClass('hidden');

        $('#media-title-input').focus();
    });

    // Dropzone {
    var MediaDropzone = new Dropzone('.media-item-new form', {
        uploadMultiple: false,
        maxFiles: 1,
        paramName: 'media-file',
        addRemoveLinks: true,
        autoProcessQueue: false,
        thumbnailWidth: 150,
        thumbnailHeight: 200
    });
    MediaDropzone.on('maxfilesexceeded', function(file){ this.removeFile(file); });
    MediaDropzone.on('complete', function(file){
        location.reload( $('.media-item-new form').data('redirect') );
    });

    var MediaDropzoneList = [];

    MediaDropzone.on('addedfile', function(file){
        MediaDropzoneList.push(file.name);
        $('.media-item-new form').css('borderColor', '#0087f7');
    });

    MediaDropzone.on('removedfile', function(file){
        var tmp = [];

        $.each(MediaDropzoneList, function(){
            if ( this != file.name ) {
                tmp.push(file.name);
            }
        });

        MediaDropzoneList = tmp;
    });

    $('#media-title-input').keyup(function(){
        $('#media-title-hidden').val( $(this).val() );
    });

    $('.media-item-save').click(function(){
        if ( !MediaDropzoneList.length ) {
            $('.media-item-new form').css('borderColor', 'red');
            return false;
        }

        MediaDropzone.processQueue();
        $(this).text('Sending ...');
    });

    ////////////
    // Update //
    ////////////

    $('.media-storage-library .thumbnail .btn-default').click(function(){
        var $thumb = $(this).parents('.thumbnail');
        var data = $thumb.data('all');

        $('.media-storage-library .thumbnail').removeClass('selected-block');
        $thumb.addClass('selected-block');

        // Set thumb {
        $lightbox = $('.media-item-edit .lightbox-link');
        $lightbox.prop('href', $lightbox.data('url').replace(0, data.id));

        $('.media-item-edit img').prop('src', $thumb.find('img.media-item').prop('src'));
        // }

        $('.media-item-edit .dropdown-menu a[data-val="' + data.group_id + '"]').click();
        $('#media-id-edit-hidden').val( data.id );
        $('#media-title-edit-input').val( data.title );

        $('.media-item-new').addClass('hidden');
        $('.media-item-edit').removeClass('hidden');
    });

    $('.media-item-update').click(function(){
        var $form = $(this).parents('form');

        $.ajax({
            url: $form.data('url').replace(0, $('#media-id-edit-hidden').val()),
            type: 'POST',
            data: {
                'media-title': $('#media-title-edit-input').val(),
                'media-group': $('#media-group-edit-hidden').val()
            },
            dataType: 'json',
            success: function(data) {
                location.reload( data.redirect );
            }
        });
    });

    ////////////
    // Delete //
    ////////////

    $('.media-storage-library .thumbnail .btn-danger').click(function(event){
        var $link = $(this);
        var $thumb = $(this).parents('.thumbnail');

        $.ajax({
            url: $link.attr('href'),
            type: 'DELETE',
            dataType: 'json',
            success: function(data) {
                if (data.result) {
                    $thumb.fadeOut('slow', function(){
                        $thumb.remove();
                    });
                }
            }
        });
    });
});
