<? include_once(realpath(dirname(__FILE__).'/../../lib/includes')."/library.inc.php"); ?>

// 메시지 보내기
function Send_msg()
{
	window.open('<?=WEB_ABS_PATH?>/memberzone/messenger/message_write_form.php','jaram_messenger_send','width=300 height=350 toolbar=no location=no directories=no scrollbars=no');
}

// 보낸 메시지 확인 
function Receipt_Certify_msg()
{
	window.open('<?=WEB_ABS_PATH?>/memberzone/messenger/message_send_list.php','jaram_messenger_check','width=350 height=350 toolbar=no location=no directories=no scrollbars=yes resizable=no');
}

// 전체 선택 함수
function Select_all()
{
	var i;

	if(!document.sms_list.sms)
		return;

	if(!document.sms_list.sms.length)
	{
		document.sms_list.sms.checked = true;
		return;
	}
	if(document.sms_list.sms[0].checked == true)
	{
		for( i = 0; i < document.sms_list.sms.length; i++)
		{
			document.sms_list.sms[i].checked = false;
		} 
	}
	else
	{
		for( i = 0; i < document.sms_list.sms.length; i++)
		{
			document.sms_list.sms[i].checked = true;
		}
	}
} // end of Select_all

// 선택된 값의 value를 리턴해주는 함수
function Select_value()
{
	var i;
	var result;

	if(!document.sms_list.sms)
		return;
	if(document.sms_list.sms.length) {
		for( i = 0 ; i < document.sms_list.sms.length; i++)
		{
			if(document.sms_list.sms[i].checked == true) 
				if(!result)
					result=document.sms_list.sms[i].value;
				else
					result=result + "," + document.sms_list.sms[i].value;
		} 
	}
	else
		if(document.sms_list.sms.checked ==true)
			result=document.sms_list.sms.value;
	
	return result;
} // end of Select_value

// 선택삭제 버튼을 눌렀을때 
function On_delete()
{
	var index;
	index = Select_value();

	if(!index) {
		alert('삭제할 쪽지를 선택해주세요');
		return;
	}
	else 
	{
		hidden_message_frame.location.href='<?=WEB_ABS_PATH?>/memberzone/messenger/message_delete.php?id='+index+'&amp;type=receive';
	}
} // end of On_delete

// 읽지않음 표시 버튼을 눌렀을때 
function Not_Read()
{
	var index;
	index = Select_value();

	if(!index) {
		alert('읽지않음 표시할 쪽지를 선택해주세요');
		return;
	}
	else
	{
		hidden_message_frame.location.href='<?=WEB_ABS_PATH?>/memberzone/messenger/message_not_read.php?id='+index;
	}
} // end of Not_Read

// 선택한 쪽지 보여주기
function Msg_read(msg_id)
{
	if(!msg_id)
	{
		alert('메시지가 지워졌거나 오류가 있습니다.');
		return;
	}
	else
		window.open('<?=WEB_ABS_PATH?>/memberzone/messenger/message_view.php?id='+msg_id+'&type=receive&from=list','jaram_messenger','width=250, height=300, toolbar=no, location=no, directories=no, status=no, scrollbars=yes, resizable=no');
}
