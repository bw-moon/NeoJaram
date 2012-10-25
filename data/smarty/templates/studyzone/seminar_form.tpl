<h3>세미나 정보 수정</h3>

<form id="jaram_seminar" name="jaram_seminar" enctype="multipart/form-data" action="./seminar_edit.php" method="POST">
<input type="hidden" name="seminar_id" value="{$smarty.get.seminar_id}"/>
<input type="hidden" name="schedule_start" value="{$data.schedule_start}"/>
<input type="hidden" name="file1_flag" value="{$file1_flag}"/>
<input type="hidden" name="file2_flag" value="{$file2_flag}"/>
<input type="hidden" name="instructor" value="{$data.group_name}"/>
<input type="hidden" name="mode" value="modify"/>
<input type="hidden" name="seminar_topics_type" value="{$seminar_topics_type}"/>
<h4>{"asterisk_orange"|icon} 세미나 주제</h4>
<p>
<input type="text" name="seminar_topic" size="50" class="fullSize" value="{$data.seminar_topic}"/>
</p>

<h4>{"sound"|icon} 발표자</h4>
<p>
<input type="hidden" id="seminar_group_id" name="seminar_group_id" value="{$data.seminar_group_id}"/>
<input type="text" id="seminar_group_name" name="seminar_group_name" size="20" value="{$data.seminar_group_name}"/> * 자동완성을 지원합니다
<div id="name2update" style="display:none;border:1px solid black;background-color:white;"></div>
</p>


<h4>{"disk_multiple"|icon} 세미나 자료</h4>
<dl>
<dt><label for="file_original"><font color="red">*</font> 자료 원본 파일</label></dt>
<dd><input type="file" name="file_upload1" id="file_original" size="40"/> {$files.original}</dd>
<dt><label for="file_printable">배포용 파일</label></dt>
<dd><input type="file" name="file_upload2" id="file_printable" size="40"/> {$files.printable}</dd>
</dl>

<h4>{"report"|icon} 개요</h4>
<p><textarea name="seminar_desc" rows="4" cols="80" class="fullSize">{$data.seminar_desc}</textarea></p>

<h4>{"folder_page"|icon} 세미나에 포함되어 있는 주제들</h4>
<!-- preview -->
<p>
{$editor}
</p>

<h4>{"lightbulb"|icon} 얻을 수 있는 이득</h4>
<p>
<textarea name="seminar_benefit" rows="4" cols="80"  class="fullSize">{$data.seminar_benefit}</textarea>
</p>

<center>
<input type="submit" value="저장하기"/>
</center>
</form>


<script type="text/javascript" language="javascript" charset="utf-8">
// <![CDATA[
  new Ajax.Autocompleter('seminar_group_name','name2update',server_url,{$smarty.ldelim}parameters:'action=get_group_list'{$smarty.rdelim});
// ]]>
</script>