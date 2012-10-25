{include file='front/widget_menu.tpl'}
<p>위젯을 추가 하거나 삭제할 수 있습니다.</p>
<div id="widget_factory">
{section name=widget loop=$widgets}
<div class="widget_box">
<span class="widget_snapshot"><img src="./temp/widget_snap.gif" alt="스넵사진"/></span>
{* 위젯을 표시하기 위한 틀 *}
<p><img src="./images/icons/{$widgets[widget].widget_icon}" alt="icon"/> {$widgets[widget].widget_name} <span class="sub">by <a href="{$context_root}/jaram/memberinfo.php?gid={$widgets[widget].uid}">{$widgets[widget].user_name}</a></span></p>
<p>
{$widgets[widget].widget_desc} 
</p>
{if $widgets[widget].widget_status eq 'allow'}
	<a href="javascript:insert_widget('{$widgets[widget].widget_id}')"><img src="./images/icons/shape_square_add.gif" alt="+"/>내 위젯에 추가</a> 
	<a href="#"><img src="./images/icons/shape_square_delete.gif" alt="+"/>삭제</a> 
{elseif $widgets[widget].widget_status eq 'wait'}
	등록대기중입니다.<br/>
	<a href="./?page=widget&action=enable&id={$widgets[widget].widget_id}">위젯등록</a>

{else}
	차단되었습니다
{/if}
</div>
{/section}
</div>
