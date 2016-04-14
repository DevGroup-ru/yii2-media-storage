jQuery(function($){
    $('.media-storage-widget a').click(function(){
        $('.media-storage-widget a').removeClass('selected-block');
        $(this).addClass('selected-block');

        $('.media-storage-widget input[type="hidden"]').val( $(this).data('id') );
    });

    $('.media-choise-confirm').click(function(){
        $('#media-storage-library-widget').modal('hide');
    });
});
