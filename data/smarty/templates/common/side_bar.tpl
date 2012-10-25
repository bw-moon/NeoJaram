<!-- Side tools start! -->
<button onclick="javascript:show_widget_manage()">위젯관리</button>
<ul id="widget_container" class="{$widget_container_class}">
{section name=widget loop=$widgets}
<li id="widget_container_{$widgets[widget].widget_user_id}">
{* 위젯을 표시하기 위한 틀 *}
<div id="widget_{$widgets[widget].widget_user_id}" class="widget">
	<div class="widget_manage" style="display:none">
	<span onclick="javascript:widget_pref_toggle('{$widgets[widget].widget_user_id}')" class="link" id="widget_pref_btn_{$widgets[widget].widget_user_id}"><img src="{$context_root}/images/icons/cog.gif" alt="preference" title="위젯 설정" class="button"/><img src="{$context_root}/images/icons/cog_edit.gif" style="display:none" alt="preference" title="위젯 설정"  class="button"/></span> <span onclick="javascript:delete_widget('{$widgets[widget].widget_user_id}')" class="link"><img src="{$context_root}/images/icons/delete.gif" alt="delete" title="위젯 삭제"/></span>
	</div>
	<div class="widget_title_{$widgets[widget].widget_obj->widget_color}" >
		<h3 class="widget_head"><img src="{$context_root}/images/icons/{$widgets[widget].widget_icon}" alt=""/> <span>{$widgets[widget].widget_obj->widget_name}</span></h3>
		<span class="widget_tool" onclick="javascript:widget_toggle('{$widgets[widget].widget_user_id}')">
		<img src="{$context_root}/images/icons/control_up.gif" alt="hide" {if $widgets[widget].widget_hide}style="display:none"{/if} class="button" title="숨기기"/>
		<img src="{$context_root}/images/icons/control_down.gif" alt="show" {if not $widgets[widget].widget_hide}style="display:none"{/if} class="button" title="보이기"/>
		</span>
	</div>
	<div class="widget_pref" style="display:none">{$widgets[widget].widget_pref}</div>
	<div class="widget_content" id="widget_content_{$widgets[widget].widget_user_id}" {if $widgets[widget].widget_hide}style="display:none"{/if}>
	<center><img src="{$context_root}/images/loading.gif" alt="loading.." style="margin-bottom:5px"/><br/>Loading...</center>
	</div>
</div>
</li>
{/section}
</ul>
<input type="hidden" name="widget_id_list_name" id="widget_id_list" value="{$widget_id_list}"/>
<!-- Side tools end! -->
