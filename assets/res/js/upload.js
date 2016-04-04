jQuery(function($){
    // Dropzone {
    var MediaDropzone = new Dropzone('.media-storage-upload-form', {
        uploadMultiple: false,
        maxFilesize: 7,
        maxFiles: 1,
        paramName: 'media-file',
        addRemoveLinks: true,
        autoProcessQueue: false,
        thumbnailWidth: 300,
        thumbnailHeight: 200,
    });
    MediaDropzone.on('maxfilesexceeded', function(file){ this.removeFile(file); });
    //MediaDropzone.on('complete', function(file){ location.reload(); });

    var MediaDropzoneList = [];

    MediaDropzone.on('addedfile', function(file){
        MediaDropzoneList.push(file.name);
        $('.media-storage-upload-form').css('borderColor', '#0087f7');
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

    //MediaDropzone.on('success', function(){
        //$('#post-separate .form-container').fadeOut('slow', function(){
            //$('#post-separate .alert').removeClass('hidden');
        //});
    //});
    // }

    $('#media-title-input').keyup(function(){
        $('.media-storage-upload-form input[name="media-title"]').val( $(this).val() );
    });

    $('.media-storage-submit-item').click(function(){
        if ( !MediaDropzoneList.length ) {
            $('.media-storage-upload-form').css('borderColor', 'red');
            return false;
        }

        MediaDropzone.processQueue();
        $(this).text('Отправка ...');
    });

    $('.media-storage-items-fields .dropdown-menu a').click(function(event){
        event.preventDefault();

        $('.media-storage-items-fields .dropdown > button span:first-child').text( $(this).text() );
        $('.media-storage-upload-form input[name="media-group"]').val( $(this).data('id') );
    });
});
