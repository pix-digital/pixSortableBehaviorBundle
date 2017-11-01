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
    var element = $(node);
    var movers = $('.js-sortable-move');
    if (movers.length <= 1) return;

    var first = parseInt(movers.first().attr('data-current-position'));
    var last = parseInt(movers.last().attr('data-current-position'));
    var direction = first <= last ? 1 : -1;

    element.sortable({
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
            $('.js-sortable-move').each(function(index, item) {
                $(item).attr('data-current-position', first + (index * direction));
            });

            var moved = $(ui.item).find('.js-sortable-move');
            var newPosition = moved.attr('data-current-position');

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