var oUploadUpdater = null;
var currentUploadFile = new Date().getTime();
var status_url = 'http://'+location.host+context_root+'/board/get_upload_status.php';


function doWriteComment() {
	if(FCKeditorAPI.GetInstance('s_note')) {
		FCKeditorAPI.GetInstance('s_note').UpdateLinkedField();
	}
	
	var url = 'http://'+location.host+context_root+'/board/write_comment.php';
	
	// IE6에서 작동 안해서 일단 블록
	//var params = Form.serialize($('comment_form'));
	var params_hash = serializeForm('comment_form', true);
	var commentAjax = new Ajax.Request(
        url,
        {
			method : 'post',
			parameters : Hash.toQueryString(params_hash),
			onComplete : showComment
        });
	$('comment_form').disable();
}

function showComment(request) {
	Effect.Appear('boardComments');
	new Insertion.Bottom('comments', request.responseText);
	$('comment_form').enable();
	$('comment_form').reset();
	if(FCKeditorAPI.GetInstance('s_note')) {
		FCKeditorAPI.GetInstance('s_note').SetHTML('');
	}
}

function deleteComment(comment_id) {
	if (confirm('정말 코멘트를 삭제합니까?'))
	{
		$('widget_loading').update(msg_deleting);
		new Ajax.Request(
			server_url,
			{
				method : 'get',
				parameters : 'action=delete_comment&comment_id='+comment_id,
				onComplete : function() {	Effect.Fade($('comment_dd_'+comment_id));	Effect.Fade($('comment_dt_'+comment_id)); }
			});
	}
}

function doSubmitArticle() {
	doAutoSaveArticle(function() {
		if(checkWriteValidation('board_form')) {
			$('board_form').submit();
		}
	});
	
}

function doCancelPost() {
	if (confirm('임시로 저장되어 있는 글을 삭제합니까?')) {
		$('widget_loading').update(msg_deleting);
		new Ajax.Request(
			server_url,
			{
				method : 'post',
				parameters : 'action=delete_draft_article&draft_id='+$('draft_id').value,
				onSuccess : function(transport) {
					//alert(transport.responseXML.getElementsByTagName('msg')[0].firstChild.nodeValue);
					history.go(-1);
				}
			});
	}
	else {
		history.go(-1);
	}
}


function doAutoSaveArticle(procComplete) {
	var editor = FCKeditorAPI.GetInstance('note');
	
//	editor.UpdateLinkedField();
	//
	var params_hash = serializeForm('board_form', true);
	params_hash['note'] = editor.GetXHTML();

	if (editor.IsDirty() && params_hash['note'].length > 0 && params_hash['title'].length > 0)
	{
		editor.ResetIsDirty();

		params_hash['id'] = $('draft_id').value;
		params_hash['action'] = 'save_draft_article';

//		$('btn_save_article').disable();
		$('widget_loading').update(msg_saving);
		
		// IE6에서 작동 안해서 일단 블록
		//var params = Form.serialize($('comment_form'));
		_Log(params_hash['note']);

		var commentAjax = new Ajax.Request(
			server_url,
			{
				method : 'post',
				parameters : Hash.toQueryString(params_hash),
				onSuccess : function(transport) {
					var xdoc = transport.responseXML;
					var current_draft_id = xdoc.getElementsByTagName('id')[0].firstChild.nodeValue;
					$('draft_id').value = current_draft_id;
					$('auto_save_status').update(xdoc.getElementsByTagName('msg')[0].firstChild.nodeValue);
					//$('btn_save_article').enable();
					if (procComplete != null)
					{
						procComplete();
					}
				}
			}
		);

	}
	else {
		if (procComplete != null)
		{
//			_Log(typeof procComplete);
//			_Log(procComplete.inspect());
//			procComplete();
		}
	}
	//editor.Focus();
}

function checkWriteValidation(form) {
	$('update_file_list').value = $('file_list').serializeSelect();
	_Log('update_file_list : '+$('update_file_list').value);
	return (checkRequireValidation(form));
}

// 임시 파일 업로드
function doFileUpload()
{
	// 새롭게 업로드될 파일이름
	currentUploadFile = new Date().getTime();

	$('file_upload_form').target = 'hiddenFrame';
	$('file_upload_current_file_name').value = currentUploadFile;
	_Log('file_upload_current_file_name : '+$('file_upload_current_file_name').value);
	$('upload_status').update('now uploading');

	$('file_upload_form').submit();
	$('file_upload_form').disable();
	//$('btn_upload_action').disabled=true;

	_Log(currentUploadFile+ ' 업로드');

	// 업로드 상황보기 시작
	showUploadStatus();

	return true;
}

// 파일 삭제
function doFileDelete() {
	changeFileList('delete');
}

function checkUploadComplete() {
	if ($('upload_status').innerHTML.match(/complete/)) {
		return true;
	}
	return false;
}


function updateFileList() {
	Ajax.Responders.register(jaramLoaderHandler)

	var pars = {
				temp_file_name: currentUploadFile, 
				get_only_filesize: true
	};

	var filesizeAjax = new Ajax.Request(
			status_url,
			{
				method:'get',
				parameters: pars, 
				onComplete: updateFileListComplete
			});	
}

function updateFileListComplete(request) {
	_Log('updateFileListComplete : ' + parseInt(request.responseText));
	$('file_upload_current_file_size').value = parseInt(request.responseText);
	changeFileList('insert', $('file_upload_current_file_name').value, $('file_upload_file').value, $('file_upload_current_file_size').value);
	$('file_upload_form').reset();
	$('file_upload_form').enable();
}

function updateTotalFilesize() {
	var fileOptions = $$('select#file_list option');
	_Log(fileOptions);
	var sizes = fileOptions.map(function(opt) {return (opt.value.split('-')[1] | 0)});
	_Log(sizes);
	$('total_file_size').update(getFancySize(sizes.sum()));
}

function changeFileList(action, server_file, file_name, file_size) {
	var hash_key = '';
	if (action == 'insert') {
		hash_key = server_file+'-'+file_size;
		var oOption = document.createElement("option");
		oOption.appendChild(document.createTextNode('('+getFancySize(file_size)+') '+file_name.split('\\').last()));
		oOption.setAttribute("value", hash_key);
		$('file_list').appendChild(oOption);
	} else {
		hash_key = $F('file_list');
		if ($('file_list').selectedIndex > -1 && hash_key.length > 0) {
			$('file_list').remove($('file_list').selectedIndex);
		} else {
			alert('파일을 선택해야 합니다.');
			return;
		}
	}

	// 실제 DB에 저장될 파일 목록을 업데이트
	updateFileChangeList(hash_key, action);

	// 전체 파일 용량을 업데이트
	updateTotalFilesize();
}

function updateFileChangeList (key, value) {
	if (key.length > 0) {
		var file_token = $('files_to_change').value.toDataHash();
		file_token[key] = value;
		$('files_to_change').value = file_token.toDataStr();
		_Log($('files_to_change').value);	
	}
}

function showUploadStatus() {
	$('widget_loading').update(msg_uploading);

	Ajax.Responders.unregister(jaramLoaderHandler)
	
	var pars = {
		temp_file_name: currentUploadFile
		};

	new Ajax.PeriodicalUpdater2(
		'upload_status', 
		status_url, 
		{
			method: 'get', 
			frequency: 1,
			check: checkUploadComplete,
			parameters: $H(pars),
			onComplete: updateFileList
		});
}