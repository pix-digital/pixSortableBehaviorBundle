$(function() {
    $('.sonata-ba-list tbody').draggableTable();
});

$.fn.draggableTable = function (settings) {
    $(this).each(function (index, item) {
        item = $(item);
        var instance = item.data('DraggableTable');
        if (!instance) {
            item.data('DraggableTable', new DraggableTable(this, settings));
        }
    });
};

var DraggableTable = function () {
    this.init.apply(this, arguments);
};

DraggableTable.prototype.init = function (node, settings) {
    $(node).sortable({
        'handle': '.js-sortable-move',
        'start': function() {
            $('body').addClass('is-dragging');
        },
        'stop': function() {
            setTimeout(function() {
                $('body').removeClass('is-dragging')
            }, 100);
        },
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
                    $(document).trigger("pixSortableBehaviorBundle.success", [data]);
                },
                'error': function(data) {
                    $(document).trigger("pixSortableBehaviorBundle.error",[data]);
                }
            });

        }
    }).disableSelection();
};