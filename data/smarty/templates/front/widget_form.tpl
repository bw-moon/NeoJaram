{include file='front/widget_menu.tpl'}
<form action="./?page=widget&amp;action=add" method="post" enctype="multipart/form-data" >
<dl>
<dt><label for="widget_icon">위젯 아이콘</label> : <a href="{$context_root}/images/icons/readme.html" target="_blank">사용할 수 있는 아이콘 목록</a> - 확장자를 제외한 아이콘 이름만 적어주세요</dt>
<dd><input type="text" size="30" id="widget_icon" name="widget_icon"/>
<div id="icon2update" style="display:none;border:1px solid black;background-color:white;"></div>
</dd>
<dt><label for="widget_file">위젯 파일(php, xml만 가능합니다. php의 경우 관리자의 점검 후 등록)</label></dt>
<dd><input type="file" size="40" id="widget_file" name="widget_file"/></dd>
<dt><label for="widget_screenshot">스크린샷 (optional)</label></dt>
<dd><input type="file" size="40" id="widget_screenshot" name="widget_screenshot"/></dd>
<dt><label for="widget_desc">위젯 설명</label></dt>
<dd><textarea size="30" class="input_box" id="widget_desc" cols="80" rows="10" name="widget_desc"></textarea></dd>
</dl>
<input type="submit" value="위젯추가"/>
</form>

<script type="text/javascript" language="javascript" charset="utf-8">
// <![CDATA[
  new Ajax.Autocompleter('widget_icon','icon2update',server_url,{$smarty.ldelim}parameters:'action=get_icon_list'{$smarty.rdelim});
// ]]>
</script>