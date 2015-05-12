$('.checkbox input').on('change', function(){
    var el = $(this);
    var id = el.data('id');
    var parent = el.parents('.list-group-item');
    
    parent.toggleClass('list-group-item-success');
    parent.toggleClass('list-group-item-warning');
    
    $.post('do/'+id, {
        id: id,
        is_done: el.is(":checked")
    });
});

$('.checkbox .close').on('click', function(){
    var el = $(this);
    $.ajax({
        url: 'todo/'+el.data('id'),
        type: 'DELETE',
        success: function(result) {
            var parent = el.parents('.list-group-item');

            parent.addClass('list-group-item-danger');
            parent.slideUp('normal', function(){
                $(this).remove();
            });
        }
    });
});

$('.clear_buttons').on('click', function(){
    $.ajax({
        url: 'todo/clear/'+$(this).data('type'),
        type: 'DELETE',
        success: function(result) {
            location.reload();
        }
    });
});