{* smarty *}
<div id="boardView">
<h3>No.{$id} : {$viewData.title}</h3>
<div id="boardViewHead" class="floatClear">
<div id="boardViewHeadImg" class="floatLeft">{if $viewData.user_having_image1 eq 'true'}<span onClick="javascript:window.open('image_view.php?uid1={$viewData.usrid}','','resizable=yes, scrollbars=no, width=screen.availWidth, height=screen.availHeight');" class="link"><img src="{$imageManager->getUserImg($viewData.usrid, 100,100)}" border="0"></span>{/if}</div></td>
<ul class="floatLeft">
	<li><strong>작성자</strong> : 
{* User와 비유저의 구분 혹은 User 정보 링크 *}
{if $viewData.usrid ne '1500'}
	<a href="../jaram/memberinfo/?gid={$viewData.usrid}">{$viewData.user_name}</a>
{else}
	{$viewData.name}
{/if}
</il>
<li><strong>작성일</strong> : {$viewData.date|date_format:"%Y년 %m월 %d일 %H:%M"}</li>
{if $fileData}
<li><strong>첨부파일</strong>
<ol style="margin: 0 0 0 30px;">
{* 파일 Download *}
{foreach item=fileList from=$fileData}
<li><a href="./download.php?tableID={$tableID}&amp;fileid={$fileList.file_id}">{$fileList.file_name}</a>
 <span class="sub">(다운로드:{$fileList.file_count}, 파일크기:{fancy_size byte=$fileList.file_size})</span></li>
{/foreach}
</ol>
</li>
{/if}
</ul>	
</div><!-- end of boardViewHead -->

<div id="boardContent" class="floatClear">
{if $imgData}
{* 업로드된 이미지 뿌려주기 *}
{foreach key=key item=imgList from=$imgData}
{if $imgList[0]>900 || $imgList[1]>900}
<span onClick="javascript:window.open('image_view.php?tableID={$tableID}&amp;fileid={$key}','','resizable=yes, scrollbars=yes, width=screen.availWidth, height=screen.availHeight');" style="cursor:hand"><img src="imgview.php?tableID={$tableID}&amp;fileid={$key}" width="400" alt="" border="0"/></span>
{else}
<span onClick="javascript:window.open('image_view.php?tableID={$tableID}&amp;fileid={$key}','','scrollbars=auto, width={$imgList[0]}, height={$imgList[1]}')" style="cursor:hand"><img src="imgview.php?tableID={$tableID}&amp;fileid={$key}" width="{$imgList[0]}" height="{$imgList[1]}" border="0" alt=""></span>
{/if}
{/foreach}
{/if}
<div id="boardContentText">
{$viewData.note|nl2br}
</div>
</div><!-- end of boardContent -->

<div id="boardComments" {if !$commentData}style="display:none"{/if}>
<h3>Comments</h3>
<dl id="comments">
{include file="board/$_template/view_comment.tpl" comments=$commentData tableID=$tableID id=$id pageID=$pageID}
</dl>
</div><!-- end of boardComments-->

<form name="comment_form" id="comment_form">
<input type="hidden" name="tableID" value="{$tableID}" />
<input type="hidden" name="s_id" value="{$id}" />
<input type="hidden" name="pageID" value="{$pageID}" />
<input type="hidden" name="sortData" value="{$sortData}" />
<dl>
{if !login_check}
	<dt><label for="s_name">작성자</label></dt>
	<dd><input type="text" id="s_name" name="s_name" value="{$name}" size="20" maxlength="20" title="작성자" required/></dd>
	<dt><label for="s_pass">비밀번호</label></dt>
	<dd><input type="password" name="s_pass" id="s_pass" size="20"/></dd>
{/if}
<dt><label for="comment_note">코멘트 작성</label></dt>
<dd>
<div  style="width:100%">
{if $oEditor}
	{$oEditor}
{else}
	<textarea name="s_note" id="comment_note" cols="100" rows="15" title="내용" style="width:95%"></textarea>
{/if}
</div>
</dd>
<dt></dt>
<dd>
<input type="button" id="btn_post_comment" name="btn_post_comment" onclick="doWriteComment()" value="코멘트 입력">
<input type="button" onclick="javascript:urimalSpellCheck(document.getElementById('comment_note').value);" value="맞춤법 검사"/> 
</dd>
</dl>
</form>

<div id="boardTool">
	<div id="articleMover">
	<form name="move" id="move_article" method="get" action="move_note.php">
	<input type="hidden" name="tableID" value="{$tableID}"/>
	<input type="hidden" name="superid" value="{$pageID}"/>
	<input type="hidden" name="id" value="{$id}"/>
	<input type="hidden" name="from" value="{$tableID}"/>
	<a href="javascript:$('move_article').submit();">Move to</a>
	<select name="to">
	{foreach item=optionData from=$boardData}
			<option  value="{$optionData.name}">{$optionData.title}</option>
	{/foreach}
	</select>
	</form>
	</div><!-- end of articleMover -->
<a href="./write.php?{query_string superid=$pageID replyID=$id}">Reply</a> | 
<a href="./write.php?{query_string superid=$pageID modID=$id}">Modify</a> | 
<a href="./delete_note.php?{query_string superid=$pageID}">Delete</a> | 
<a href="./delete_note.php?{query_string superid=$pageID spam="true"}" title="Spam 처리합니다">Spam</a> | 
<a href="./bbs.php?{query_string}">List</a>
</div><!-- end of boardTool -->
</div><!-- end of boardView-->