/*
 * 공통 자바 스크립트
 */

var context_root = '/jaram';
var server_url = 'http://'+window.location.host+context_root+'/tools/ajax/server.php';

var previous_link = '';
var next_link = '';

var msg_loading = '로딩 중입니다...';
var msg_saving = '저장 중입니다...';
var msg_updating = '갱신 중입니다...';
var msg_deleting = '삭제 중입니다...';
var msg_uploading = '업로드 중입니다...';

var widget_id_list = '';

Jaram = Class.create();

var jaramLoaderHandler = {
	onCreate: function(){
		Element.show('widget_loading');
	},

	onComplete: function() {
		if(Ajax.activeRequestCount == 0){
			Element.hide('widget_loading');
		}
	}
};

Ajax.Responders.register(jaramLoaderHandler);

Event.observe(window, 'load', page_loaded, false);

function page_loaded(evt) {
	widget_id_list = $H(decodeURIComponent($('widget_id_list').value).evalJSON());
	update_widgets();
	if ($('jaram_login'))
	{
		Form.focusFirstElement('jaram_login');
	}
}


function show_flash(id) {
	document.getElementById(id).outerHTML = document.getElementById(id).outerHTML;
}

// 이건 일단 문제가 getValue가 안된다는데 있는데 나중에 수정하자.
function serializeForm(form_name, getHash) {
	var elements = Form.getElements(form_name);

    var data = elements.inject({}, function(result, element) {
      if (!element.disabled && element.name) {
        var key = element.name;
		try
		{
			var value = $(element).getValue();	
		}
		catch (e)
		{
			var value = $(element).value;
		}

        if (value != undefined) {
          if (result[key]) {
            if (result[key].constructor != Array) result[key] = [result[key]];
            result[key].push(value);
          }
          else result[key] = value;
        }
      }
      return result;
    });
    return getHash ? data : Hash.toQueryString(data);
}


/**
 * String 객체 확장해서 toDataArray() 함수 추가.
 * key1:data1,key2:data2 형식의 스트링을 array(key1->data1, key2->data2) 형식의 해쉬로 리턴
 */
Object.extend(String.prototype, {
	toDataHash: function() {
		var result = new Hash(), source = this.split(',');
		for (var i = 0; i<source.length ; i++)
		{
			var file_token = source[i].split(':');
			if (file_token.length == 2)
			{
				result[file_token[0].strip()] = file_token[1].strip();
			}
		}
		return result;
	}
});

/**
 * Hash 확장해서 toDataStr 추가 
 * 위에 함수 반대방향으로 작용
 */
Object.extend(Hash.prototype, {
	toDataStr: function() {
		var result = '', source = this.keys();
		for (var i = 0; i < source.length; i++)
		{
			result += source[i]+':'+this[source[i]];
			if (i < source.length-1)
			{
				result += ',';
			}
		}
		return result;
	}
});

// 셀렉트 태그 전용 시리얼라이즈, 문병원 추가 (070228)
Form.Element.Methods = {
  serializeSelect: function (element) {
	element = $(element);
	var option = element.options;
	var result = {};
    for (var i = 0; i < option.length ;  i++)
    {
		result[option[i].value]=option[i].text;
    }
	return Hash.toQueryString(result);
  }
}

Object.extend(Form.Element, Form.Element.Methods);

/**
 * 배열의 데이터를 산술적 덧셈하여 결과를 리턴
 */
Object.extend(Array.prototype, {
	sum: function () {
		var result = 0, source = this;
		for (var i = 0; i < source.length; i++)
		{
			result += parseInt(source[i]);
		}
		return result;
	}
});



// Comstum perioricalUpdater
Ajax.PeriodicalUpdater2 = Class.create();
Ajax.PeriodicalUpdater2.prototype = Object.extend(new Ajax.Base(), {
  initialize: function(container, url, options) {
    this.setOptions(options);
    this.onComplete = this.options.onComplete;

    this.frequency = (this.options.frequency || 2);
    this.decay = (this.options.decay || 1);

    this.updater = {};
    this.container = container;
    this.url = url;
	
	this.check = (this.options.check || function(){return true});

	this.start();
  },

  start: function() {
    this.options.onComplete = this.updateComplete.bind(this);
    this.onTimerEvent();
  },

  stop: function() {
    this.updater.options.onComplete = undefined;
    clearTimeout(this.timer);
    (this.onComplete || Prototype.emptyFunction).apply(this, arguments);
  },

  updateComplete: function(request) {
    if (this.options.decay) {
      this.decay = (request.responseText == this.lastText ?
        this.decay * this.options.decay : 1);

      this.lastText = request.responseText;
    }
    this.timer = setTimeout(this.onTimerEvent.bind(this),
      this.decay * this.frequency * 1000);
  },

  onTimerEvent: function() {
	if (this.check()==true) {
		this.stop();
	} else {
		this.updater = new Ajax.Updater(this.container, this.url, this.options);
	}
  }
});


// 세자리마다 쉼표를 찍어줌
function number_format(numstr) {
	var numstr = String(numstr);
	var re0 = /(\d+)(\d{3})($|\..*)/;
	if (re0.test(numstr))
	{
		return numstr.replace(
			re0,
			function(str,p1,p2,p3) { return number_format(p1) + "," + p2 + p3;}
		);
	} else {
		return numstr;
	}
}

// 바이트로 파일 사이즈를 받아서 적당한 값을 리턴
function getFancySize(size) {
	var num = parseInt(size,10);
	if (num > 1024*1024*1024)
	{
		num = number_format((num/(1024*1024*1024)).toFixed(1));
		unit = "GB";
	} else if (num > 1024*1024) 
	{
		num = number_format((num/(1024*1024)).toFixed(1));
		unit = "MB";
	} else if (num > 1024) 
	{
		num = number_format((num/(1024)).toFixed(1));
		unit = "KB";
	} else {
		num = number_format(num);
		unit = "Byte";
	}
	return num + unit;
}


/**
 * javascript를 이용해서 로그를 남기기
 * AJAX이용해서 통신
 */
function _Log(obj) {
	$('widget_loading').update('디버깅 로그를 남기고 있습니다.');

	var priority = 'debug', log_str = obj;
	if (typeof obj != 'string')
	{
		log_str = obj.inspect();
	}
	var url = 'http://'+window.location.host+context_root+'/tools/dev/js_logger.php';
	var param = 'path=' + encodeURIComponent(window.location.href) + '&str=' + encodeURIComponent(log_str) + '&level=debug';
	var logAjax = new Ajax.Request(url, {method: 'get', parameters: param});
}


/**
 * 폼 정보를 체크해서 해당액션을 취함
 */
function checkRequireValidation(form) {
	var formObj = (typeof form == 'object') ? form : $(form);
	if (typeof formObj == 'object')
	{
		var elements = formObj.getElements();
		var invalid_form = elements.find(function (element) {return (element.getAttribute('required') != null && $F(element).strip().length <= 0) });
		if (typeof invalid_form == 'object')
		{
			alert(invalid_form.title + '를(을) 입력해야 합니다.');
			invalid_form.activate();
			return false;
		} else {
			return true;
		}
		
	} else {
		alert('폼 이름이 잘못되었습니다.');
		return false;
	}
}

/** Sidebar */
function widget_toggle (widget_id) {
	var widget_buttons = $$('div#widget_'+widget_id+' .widget_title .widget_tool .button');
	widget_buttons.each(function(button) { button.toggle()} );

	var widget_elements = $$('div#widget_'+widget_id+' .widget_content');
	widget_elements.each (function(widget) { widget.toggle() } );

	// 위젯이 업데이트 되어 있지 않을 경우 업데이트
	if (widget_id_list[widget_id].widget_hide == '1')
	{
		update_widget_content(widget_id);
		widget_id_list[widget_id].widget_hide = '0';
	}

	var status = {action:'widget_toggle',data:'widget_user_id='+widget_id};
	save_widget_status(status);
}

function widget_pref_toggle(widget_id) {
	var widget_elements = $$('div#widget_'+widget_id+' .widget_pref');
	widget_elements.each (function(widget) { widget.toggle() } );

	var widget_button = $$('#widget_pref_btn_'+widget_id+' .button');
	widget_button.each (function(widget) { widget.toggle() } );

	var pref_form = $('widget_pref_form_'+widget_id);
	if (pref_form && widget_elements[0].visible())
	{
		pref_form.focusFirstElement();
	}
}

function save_widget_pref(widget_user_id) {
	$('widget_loading').update(msg_saving);
	var params = serializeForm('widget_pref_form_'+widget_user_id, true);
	params['action'] = 'save_widget_pref';
	if (params['widget_name'])
	{
		$$('div#widget_'+widget_user_id+' div h3 span')[0].update(params['widget_name']);
	}

	new Ajax.Request(
		server_url,
		{
			method:'post',
			parameters:Hash.toQueryString(params),
			onSuccess:function() {update_widget_content(widget_user_id)}
		}
	);
}

function save_widget_status (status) {
	$('widget_loading').update(msg_saving);
	var pars = 'action='+status.action+'&'+status.data;
	new Ajax.Request(
		server_url,
		{
			method: 'get',
			parameters: pars
		});
}

function refresh_widgets() {
	$('widget_loading').update(msg_loading);
	var pars = 'action=show_widgets';
	new Ajax.Updater(
		'sideBar',
		server_url,
		{
			method: 'get',
			parameters: pars,
			onComplete: function() {	
				widget_id_list = $H(decodeURIComponent($('widget_id_list').value).evalJSON()); 
				update_widgets();
			}
		});
}

// onComplete 이벤트에 대응시켜야 시간차로 발생하는 문제를 줄일 수 있음
function update_widgets () {
	make_widget_dragable();
	widget_id_list.each( function (pair) {
		if (pair.value.widget_hide == '0')
		{
			update_widget_content(pair.key);
		}
	});
}

function make_widget_dragable () {
   Sortable.create("widget_container",
     {dropOnEmpty:true,handle:'widget_head',constraint:false,
     onUpdate:function(){ save_widget_status({action:'widget_position', data:Sortable.serialize('widget_container')}) } });
}

// widget_id_list를 업데이트 시키는 기능은 추가하지 않았음.
function delete_widget(widget_user_id) {
//	_Log('delete_widget : '+widget_user_id);
	$('widget_loading').update(msg_deleting);
	var pars = 'action=delete_widget&widget_user_id='+widget_user_id;
	new Ajax.Request(
		server_url,
		{
			method: 'get',
			parameters: pars,
			onSuccess: function() {
				$('widget_container_'+widget_user_id).remove();
				widget_id_list.remove(widget_user_id);
				make_widget_dragable();
			}
		});
}

function show_widget_manage () {
	$$('ul#widget_container .widget_manage').each(function(button) { button.toggle()} );
	$$('ul#widget_container .widget_pref').each(function(button) { button.hide()} );
}

function change_widget_color(widget_user_id, color, item) {
	$('widget_color_'+widget_user_id).value = color;
	$$('div#widget_'+widget_user_id+' div')[1].className='widget_title_'+color;
}

// 위젯의 내부 내용을 업데이트
function update_widget_content(widget_user_id) {
	$('widget_loading').update(msg_loading);
	new Ajax.Request(
		server_url, 
	   {
			method : 'get', 
			parameters : 'action=get_widget&widget_user_id='+widget_user_id,
			onSuccess:
			function(transport) { 
				var xdoc = transport.responseXML;
				$('widget_content_'+widget_user_id).update(xdoc.getElementsByTagName('content')[0].firstChild.nodeValue);
				var script = xdoc.getElementsByTagName('script')[0].firstChild.nodeValue;
//				alert(script);
				if (script.length > 0)
				{
					eval(script);
				}
			}
	   }
	);
	
}

// 인플레이스 에디터로 속성을 변경
function makeInplaceEditor(widget_user_id) {
	new Ajax.InPlaceEditor($('widget_note_'+widget_user_id), server_url+'?action=process_widget&method=setContent&widget_user_id='+widget_user_id, 
		{
			rows:5,
			paramName:'widget_note', 
			ajaxOptions: {method: 'get'} 
		}
	);
}

// 위젯을 사이드바에 추가
function insert_widget(widget_id) {
	var pars = 'action=insert_widget&widget_id='+widget_id;
	new Ajax.Request(
		server_url,
		{
			method: 'get',
			parameters: pars,
			onSuccess: refresh_widgets
		});
}

// 한줄 메시지 창을 닫고, DB에 닫았음을 Ajax를 통해 저장
function do_close_msg(msg_id) {
	$('widget_loading').update(msg_saving);

	var pars = 'action=delete_msg&msg_id='+msg_id;
	var msg_ajax = new Ajax.Request(
		server_url,
		{
			method: 'get',
			parameters: pars,
			onComplete : function() {$('jaram_one_line_msg').hide()}
		});
}


function add_tag(field_id, tag_type, tag_use_field, to_update) {
	$('widget_loading').update(msg_saving);

	var pars = 'action=save_tag&tag_type='+tag_type+'&tag_name='+encodeURIComponent($(field_id).value)+'&tag_use_field='+tag_use_field;

	var tag_ajax = new Ajax.Request(
		server_url,
	{
		method:'get',
		parameters: pars,
		onSuccess : function (request) {
			new Insertion.Bottom(to_update, request.responseText);
			$(field_id).value='';
		}
	});
}


function delete_tag(tag_use_field, tag_use_id) {
	if (confirm('정말 해당 항목을 삭제합니까?'))
	{
		$('widget_loading').update(msg_deleting);
		new Ajax.Request(
			server_url,
			{
				method : 'get',
				parameters : 'action=delete_tag&tag_use_id='+tag_use_id,
				onSuccess : function() { Effect.Fade(tag_use_field+'_'+tag_use_id); }
			});
	}
}