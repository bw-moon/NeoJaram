<?xml version="1.0" encoding="euc-kr"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Jaram : Album</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="Content-Language" content="ko-KR" />
<meta name="Generator" content="EditPlus" />
<meta name="Author" content="Neo Jaram Makers" />
<meta name="Description" content="�Ѿ���б� ������ǻ�Ͱ��к� ����������ȸ �ڶ��Դϴ�." />
<!-- ���� ��Ÿ�� ��Ʈ -->
<link rel="stylesheet" type="text/css" href="/css/main.css" />
</head>
<body  bgcolor="#ffffff" link="#1166bb" vlink="#666666" alink="#ddeeff">
{* smarty *}
{* �Խ��� header *}

<table width="95%" cellpadding="4" cellspacing="0" border="0" align="center">
<tr><td><span class="a12b">Picture Thumbnail</span></td></tr>
<tr><td background="/images/div_grad.gif"><img src="/images/t.gif" alt="" border="0" width="1" height="4"></td></tr>
<tr><td align="center">
{* ���ε�� �̹��� �ѷ��ֱ� *}
{foreach key=key item=imgList from=$imgData}
<a href="#" onClick="javascript:window.open('image_view.php?tableID={$tableID}&amp;fileid={$key}','','resizable=yes, scrollbars=yes, width=screen.availWidth, height=screen.availHeight');"><img src="imgview.php?tableID={$tableID}&amp;fileid={$key}&amp;resize=true" alt="÷�� ���� ID �� : {$key}" border="0"/></a>
{/foreach}
</td></tr>
</table>
{*
<!-- ���۱� -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td colspan="2" bgcolor="#000000"><img src="/images/t.gif" height="1" width="1" alt="" /></td></tr>
<tr><td colspan="2" bgcolor="#BBBBBB"><img src="/images/t.gif" height="2" width="1" alt="" /></td></tr>
<tr><td colspan="2"><img src="/images/t.gif" height="5" width="1" alt="" /></td></tr>
<tr>
	<td class="copyright" style="line-height: 12pt;" height="50" valign="top">
		&nbsp;�� 2003 Jaram. All rights reserved.<br />
		&nbsp;Friday, August 8, 2003	</td>
	<td align="right" valign="top">
		<a href="http://jigsaw.w3.org/css-validator/validator?uri=http://neo.jaram.org/css/main.css"><img style="border:0;width:88px;height:31px" src="/images/valid/vcss.gif" alt="Valid CSS!" /></a> 
		<a href="http://validator.w3.org/check/referer"><img src="/images/valid/valid-xhtml10.png" alt="Valid XHTML 1.0!" border="0" height="31" width="88" /></a>&nbsp; 
	</td>
</tr>
</table>
<!-- ���۱� -->

</body>
</html>
*}