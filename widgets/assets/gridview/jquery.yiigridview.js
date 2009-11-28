/**
 * jQuery Yii GridView plugin file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: jquery.yiitab.js 1474 2009-10-18 21:13:52Z qiang.xue $
 */

;(function($) {

	$.fn.yiiGridView = function(settings) {
		var settings = $.extend({}, $.fn.yiiGridView.defaults, settings || {});
		return this.each(function(){
			$this = $(this);
			var id = $this.attr('id');
			if(settings.updateSelector == undefined) {
				settings.updateSelector = '#'+id+' .'+settings.pagerClass+' a, #'+id+' .'+settings.tableClass+' thead th a';
			}
			$.fn.yiiGridView.settings[id] = settings;

			if(settings.ajaxUpdate.length > 0) {
				$(settings.updateSelector).live('click',function(){
					$.fn.yiiGridView.update(id, {url: $(this).attr('href')});
					return false;
				});
			}

			if(settings.selectableRows > 0) {
				$('#'+id+' .'+settings.tableClass+' > tbody > tr').live('click',function(){
					if(settings.selectableRows == 1)
						$(this).siblings().removeClass('selected');
					$(this).toggleClass('selected');
				});
			}
		});
	};

	$.fn.yiiGridView.defaults = {
		ajaxUpdate: [],
		pagerClass: 'pager',
		tableClass: 'table',
		selectableRows: 1,
		// updateSelector: '#id .pager a, '#id .table thead th a',
		// updateTargets: [id],
		// beforeUpdate: function(id) {},
		// afterUpdate: function(id, data) {},
	};

	$.fn.yiiGridView.settings = {};

	$.fn.yiiGridView.getKey = function(id, row) {
		return $('#'+id+' > div.keys > span:eq('+row+')').text();
	};

	$.fn.yiiGridView.getUrl = function(id) {
		return $('#'+id+' > div.keys').attr('title');
	};

	$.fn.yiiGridView.getRow = function(id, row) {
		var settings = $.fn.yiiGridView.settings[id];
		return $('#'+id+' .'+settings.tableClass+' > tbody > tr:eq('+row+') > td');
	};

	$.fn.yiiGridView.getColumn = function(id, column) {
		var settings = $.fn.yiiGridView.settings[id];
		return $('#'+id+' .'+settings.tableClass+' > tbody > tr > td:nth-child('+(column+1)+')');
	};

	$.fn.yiiGridView.update = function(id, options) {
		var settings = $.fn.yiiGridView.settings[id];
		options = $.extend({
			url: $('#'+id+' div.keys').attr('title'),
			success: function(data,status) {
				$.each(settings.ajaxUpdate, function() {
					$('#'+this).html($(data).find('#'+this));
				});
				if(settings.afterUpdate != undefined)
					settings.afterUpdate(id, data);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(XMLHttpRequest.responseText);
			},
		}, options || {});

		if(settings.beforeUpdate != undefined)
			settings.beforeUpdate(id);
		$.ajax(options);
	};

	$.fn.yiiGridView.getSelection = function(id) {
		var settings = $.fn.yiiGridView.settings[id];
		var keys = $('#'+id+' > div.keys > span');
		var selection = [];
		$('#'+id+' .'+settings.tableClass+' > tbody > tr').each(function(i){
			if($(this).hasClass('selected'))
				selection.push(keys.eq(i).text());
		});
		return selection;
	};

})(jQuery);