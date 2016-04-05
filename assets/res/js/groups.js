jQuery(function($){
    /////////
    // Add //
    /////////

    $('.media-group-add').click(function(){
        $('.list-group-item.selected-block').removeClass('selected-block');

        var $h3 = $('.media-group-form h3');
        $h3.text($h3.data('add'));

        $('#media-group-name').val('').focus();
        $('.media-group-form').data({
            update: false,
            id: 0
        });
    });

    ////////////
    // Update //
    ////////////

    $('.list-group-item').click(function(){
        $(this).siblings().removeClass('selected-block');
        $(this).addClass('selected-block');

        var $h3 = $('.media-group-form h3');
        $h3.text($h3.data('edit'));

        var data = $(this).data('all');
        $('#media-group-name').val(data.name);

        $('.media-group-form').data({
            update: true,
            id: data.id
        });
    });

    //////////
    // Save //
    //////////

    $('.media-group-save').click(function(){
        var $form = $(this).parents('form');
        var action = $('.media-group-form').data();

        var url = action.update ? $form.attr('action').replace(0, action.id) : $form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                'group-name': $('#media-group-name').val()
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

    $('.media-group-delete').click(function(event){
        var $link = $(this);
        var $item = $(this).parents('.list-group-item');

        $.ajax({
            url: $link.attr('href'),
            type: 'DELETE',
            dataType: 'json',
            success: function(data) {
                if (data.result) {
                    $item.fadeOut('slow', function(){
                        $item.remove();
                    });
                }
            }
        });
    });
});
