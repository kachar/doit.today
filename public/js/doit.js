
$('.checkbox input').on('change', function(){
    var el = $(this);
    var id = el.data('id');
    var is_done = el.is(":checked");

    
    // Mark row
    el.parents('.list-group-item').toggleClass('list-group-item-success');
    // Send the request
    $.post('do/'+id, {
        id: id,
        is_done: is_done
    }, function () {
        console.log('ready', id);
        
        // Relad page after change 
        location.reload();
    });
});

$('.checkbox .close').on('click', function(){
    var el = $(this);
    var id = el.data('id');
    $.ajax({
        url: 'todo/'+id,
        type: 'DELETE',
        success: function(result) {
            var parent = el.parents('.list-group-item');

            parent.toggleClass('list-group-item-danger');
            parent.slideUp('normal', function(){
                $(this).remove();
            });
        }
    });
});

$('button.btn-clear').on('click', function(){
    var el = $(this);
    var type = el.data('type');
    $.ajax({
        url: 'todo/clear/'+type,
        type: 'DELETE',
        success: function(result) {
            location.reload();
        }
    });
});
