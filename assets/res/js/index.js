jQuery(function($){
    $('.media-storage-library .thumbnail .btn-danger').click(function(event){
        event.preventDefault();

        var id = $(this).data('id');
        var $thumb = $(this).parents('.thumbnail');

        $.ajax({
            url: 'media/delete/'+id,
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
