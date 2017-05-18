jQuery( function() {
    var element = $('.sonata-ba-list tbody');

    element.sortable({
        'handle': '.js-sortable-move',
        'axis': 'y',
        'cancel': 'input,textarea,select,option,button:not(.js-sortable-move)',
        'tolerance': 'pointer',
        'revert': 100,
        'cursor': 'move',
        'zIndex': 1,
        'helper': function(e, ui) {
            ui.css('width','100%');
            ui.children().each(function() {
                var item = $(this);
                item.width(item.width());
            });
            return ui;
        },
        'update': function(event, ui) {
            var moved = $(ui.item).find('.js-sortable-move');
            var newPosition = ui.item.index();

            $.ajax({
                'type': 'GET',
                'url': moved.data('url').replace('NEW_POSITION', newPosition),
                'dataType': 'json',
                'success': function(data) {
                    console.log('success');
                },
                'error': function(data {
                    console.log('error');
                }
            });

        }
    }).disableSelection();
});


