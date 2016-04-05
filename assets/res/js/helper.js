jQuery(function($){
    $('.js-link').click(function(event){
        event.preventDefault();
    });

    $('.media-storage-view .dropdown .dropdown-menu a').click(function(){
        $dropdown = $(this).parents('.dropdown');

        $dropdown.find('button span:first-child').text( $(this).text() );
        $( $dropdown.data('input') ).val( $(this).data('val') );
    });

    $('.media-storage-view .dropdown').each(function(){
        if ( $(this).data('current') ) {
            $(this).find('.dropdown-menu a[data-val="' + $(this).data('current') + '"]').click();
        }
    });
});
