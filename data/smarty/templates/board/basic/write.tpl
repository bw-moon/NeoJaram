{* smarty *}
<div id="boardForm">
<h3>{$pageTitle}</h3>
<div id="boardInputForm">
<form name="board_form" id="board_form" method="post" action="upload_result.php" onSubmit="return checkWriteValidation(this)">
<input type="hidden" name="tableID" id="tableID" value="{$tableID}" />
<input type="hidden" name="replyID" id="replyID" value="{$replyID}" />
<input type="hidden" name="modID" id="modID" value="{$modID}" />
<input type="hidden" name="mtime" id="mtime" value="{$mtime}" />
<input type="hidden" name="update_file_list" id="update_file_list" value=""/>
<input type="hidden" name="files_to_change" id="files_to_change" value=""/>
<input type="hidden" name="draft_id" id="draft_id" value="{$draft_id}"/>
<dl>
{if !login_check}
<dt><label for="name">작성자</label></dt>
<dd><input type="text" name="name" id="name" size="10"  maxlength="20" class="input" value="{$dataValue.name}" title="작성자" required='true' /></dd>
<dt><label for="passwd">비밀번호</label></dt>
<dd><input type="password" name="passwd" id="passwd" size="20" class="input" maxlength="20" title="비밀번호" required /></dd>
<dt><label for="email">이메일</label></dt>
<dd><input type="text" name="email" id="email" size="40" class="input" maxlength="200" value="{$dataValue.email}"/></dd>
<dt><label for="homepage">홈페이지</label></dt>
<dd><input type="text" name="homepage" id="homepage" size="40" class="input" maxlength="200" value="{$dataValue.homepage}"/></dd>
{/if}
{* 카테로그 선택시 뿌려주기 *}
{if $categoryData !=""}
<dt><label for="kind">분류</label></dt>
<dd>
<select name="kind" id="kind" class="input_box" title="분류">
{foreach item=optionData from=$categoryData}
			<option  value="{$optionData->id}">{$optionData->category_name}</option>
{/foreach}
</select>
</dd>
{/if}
<dt><label for="title">제목</label></dt>
<dd>
{* 답변글시 Title을 손보기 *}
{if $USING_REPLY !=""}
<input type="text" name="title" id="title" size="60" maxlength="200" class="input" value="Re : {$dataValue.title}" title="제목" required='true'/>
{else}
<input type="text" name="title" id="title" size="60" maxlength="200" class="input" value="{$dataValue.title}" title="제목" required='true'/>
{/if}
</dd>	
<dt><label for="note">내용</label></dt>
<dd>
{if $oEditor} 
{$oEditor}
{else}
	<textarea name="note" cols="40" id="note" rows="20" title="내용" style="width: 100%">{if $USING_REPLY !=""}
	---------- ---------- ----------
	{$dataValue.note|indent:5}
	{else}
	{$dataValue.note}
	{/if}
	</textarea>
{/if}
<div id="auto_save_status"></div>
</dd>
</dl>
<p>
<label for="set_notice">상주공지</label> 
{if $dataValue.depth<0}
	<input type="checkbox" checked name="always" id="set_notice" title="상주공지"/>
{else}
	<input type="checkbox" name="always" id="set_notice" title="상주공지"/>
{/if}
<input type="button" onclick="javascript:urimalSpellCheck(document.flistResult.note.value);" value="맞춤법 검사" /> 
<a href="http://urimal.cs.pusan.ac.kr/urimal_new/" target="_blank">[우리말 배움터]</a>
<input type="hidden" name="files_to_upload" id="files_to_upload" value=""/>
</form>
</p>
<dl>
<dt><label for="file_upload_file">파일검색</label> (최대 업로드용량 : {get_ini_info var='upload_max_filesize'})</dt>
<dd>
<iframe id="hiddenFrame" name="hiddenFrame" style="position:absolute;display:none;visibility:hidden;height:0px;width:0px"></iframe>
<form target="hiddenFrame" name="file_upload_form" id="file_upload_form" enctype="multipart/form-data" method="post" action="./upload.php?tableID={$tableID}">
<input type="hidden" name="file_upload_current_file_name" id="file_upload_current_file_name" value=""/>
<input type="hidden" name="file_upload_current_file_size" id="file_upload_current_file_size" value=""/>
<input type="hidden" name="file_upload_total_file_size" id="file_upload_total_file_size" value="0"/>
<input type="file" value="Browse" size="50" name="file_upload_file" id="file_upload_file"/>
<input type="button" name="btn_upload_action" id="btn_upload_action" onclick="javascript:doFileUpload()" value="업로드"/>
<input type="button" name="btn_upload_delete" id="btn_upload_delete" onclick="javascript:doFileDelete()" value="파일삭제"/>
</form>
<div id="upload_status"></div>
</dd>
<dt><label for="file_list">파일목록</label></dt>
<dd>
<select name="file_list" id="file_list" size="5" class="input_box">
{html_options options=$fileOptions}
</select> 전체용량 <span id="total_file_size">{fancy_size byte=$dataValue.file_size}</span>
</dd>
</dl>
<div id="boardTool">
<input type="button" id="btn_post_article" name="btn_post_article" onclick="javascript:doSubmitArticle()" value="글올리기"/> 
<input type="button" id="btn_save_article" name="btn_save_article" onclick="javascript:doAutoSaveArticle()" value="임시저장"/>
<input type="button" name="btn_post_cancel" onclick="javascript:doCancelPost()" value="취소">
</div>
</div>
</div>