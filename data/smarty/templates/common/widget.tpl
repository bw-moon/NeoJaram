{* 위젯을 표시하기 위한 틀 *}
<!-- start of {$widget.widget_shortcut}  -->
<div id="widget_{$widget.widget_shortcut}" class="widget">
	<div class="widget_title" >
		<h3 class="widget_head"><img src="{$smarty.const.WEB_ABS_PATH}/images/icons/{$widget.widget_icon}"/> {$widget.widget_title}</h3>
		<span class="widget_tool" onclick="javascript:widget_toggle('{$widget.widget_shortcut}')"><img src="{$smarty.const.WEB_ABS_PATH}/images/icons/control_up.gif" alt="hide" class="button"/><img src="{$smarty.const.WEB_ABS_PATH}/images/icons/control_down.gif" alt="show" style="display:none" class="button"/></span>
	</div>
	<div class="widget_pref">{$widget.widget_pref}</div>
	<div class="widget_content">{$widget.widget_content}</div>
</div>
<!-- end of {$widget.widget_shortcut} -->