/**
 * @author kudenkovr (c) 2016
 * Исправляем тупой serializeArray
 * Необходим для создания объекта, передаваемого по ajax
 */
;(function($) {
	$.fn.serializeObject = $.fn.serializeArray;
	$.fn.serializeArray = function() {
		var serialize = this.serializeObject();
		var output = {};
		for (var i in serialize) {
			output[serialize[i].name] = serialize[i].value;
		}
		this.find('input[type=file]').each(function() {
			var name = $(this).attr('name');
			output[name] = this.files[0];
		});
		return output;
	}
	$.fn.getFormData = function() {
		var fd = new FormData();
		this.find('input').each(function(){
			let $this = $(this),
				name = $this.attr('name');
			if (!name) return false;
			switch($this.attr('type')) {
				case 'file':
					for (let i=0; i<this.files.length; i++) {
						fd.append(name+'['+i+']', this.files[i]);
					}
					break;
				default:
					fd.append(name, String($this.val()));
			}
		});
		this.find('textarea').each(function(){
			let $this = $(this),
				name = $this.attr('name');
			if (!name) return false;
			fd.append(name, $this.val());
		});
		this.find('select').each(function(){
			let $this = $(this),
				name = $this.attr('name');
			if (!name) return false;
			fd.append(name, $this.val());
		});
		
		return fd;
	}
}(jQuery));

/**
 * @jAXForm
 * @author Kudenkov R. © 2016
 * @about Управление ajax формами вместе с modx
 */
;(function($){
	$.jAXForm = {
		defaults: {
			validate: true,
			classError: '_error',
		},
		init: function(options) {
			this.options = $.extend(this.defaults, options);
		}
	};
	
	$.fn.jAXForm = function(actionURL, data, func) {
		// Автоинициализация плагина
		$.jAXForm.options || $.jAXForm.init();
		
		// Проверка параметров
		if (typeof actionURL != 'string') {
			func = data;
			data = actionURL;
			actionURL = null;
		}
		if (typeof data != 'object') {
			func = data;
			data = {};
		}
		func = func || function(data){};
		
		// Обработчик на отправку
		this.submit(function() {
			//if ($.jAXForm.defaults.validate && !this.valid) return false;
			
			let _this = this,
				$this = $(this),
				formdata = $(this).getFormData(),
				url = actionURL || $(this).attr('action') || window.location;
			
			// Add user data
			$.each(data, function(key, value) {formdata.append(key, value);});
			$this.trigger('axform.post');
			$this.find('[type=submit]').attr('disabled', 'disabled');
			
			// fd = formdata;
			// console.log(_this, formdata, url); return false;
			
			$.ajax({
				url: url,
				type: 'POST',
				data: formdata,
				cache: false,
				processData: false,
				contentType: false,
				success: function(data) {
						$this.find('[type=submit]').removeAttr('disabled');
						if (data[0]!='{' && data[0]!='[') {
							console.error('JSON parse error');
							console.info(data);
							return false;
						}
						data = JSON.parse(data);
						if (data.error) {
							$(this).trigger('axform.error');
						} else if (data.success) {
							$(this).trigger('axform.success');
							func.apply(_this, [data]);
						} else {
							$(this).trigger('axform.complete');
							func.apply(_this, [data]);
						}
					}
			});
			return false;
		});
		
		return this;
	}
}(jQuery));