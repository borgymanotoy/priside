var setExpandCollapseTables = function(tableHeaderClassSelector, preExpandTableName){
	if(tableHeaderClassSelector && preExpandTableName){
		$(document).off('click', '.'+tableHeaderClassSelector);
		$(document).on('click', '.'+tableHeaderClassSelector, function() {
			var id = $(this).attr('id');
			if (id && id.split('-').length > 1) {
				var attr_id = id.split('-')[1];
				var arrow_span = $("#"+id+ " span");
				var className = arrow_span.attr('class');
				arrow_span.removeClass(className);
				var hiddenObj = $('#'+preExpandTableName+'-'+attr_id);

				if (hiddenObj.is(":hidden")) {
					hiddenObj.show();
					arrow_span.addClass("expand-arrow-down");
				} else {
					hiddenObj.hide();
					arrow_span.addClass("expand-arrow-right");
				}
			}
		});
	}
};

setExpandCollapseTables('serviceRequestHeader', 'requestTableRowExpand');