
$('.checkbox input').on('change', function(){
    var id = $(this).data('id');
    $.post('do/'+id, {
        id: id,
        is_done: $(this).is(":checked")
    }, function () {
        console.log('ready', id);
    });
});

$('.checkbox .close').on('click', function(){
    var el = $(this);
    var id = el.data('id');
    $.ajax({
        url: 'todo/'+id,
        type: 'DELETE',
        success: function(result) {
            el.parents('.list-group-item').slideUp('slow', function(){
                $(this).remove();
            });
        }
    });
});

