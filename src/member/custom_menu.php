<?
login_check();
dbconn();
?>
<script language="javascript" type="text/javascript">
<!--
function moveRight() {
	var list1 = document.f_custom_menu.all_menu;
	var list2 = document.f_custom_menu.custom_menu;

	if (list1.selectedIndex == -1) {
		alert("메뉴를 선택해주세요!");
		return;
	}

	for (var i = 0; i < list1.length; i++) {
		if (list1[i].selected) {
			var newOption = document.createElement("OPTION")

			newOption.text = list1[i].text;
			newOption.value = list1[i].value;

			list2.options.add(newOption);
			list2.selectedIndex = list2.length - 1;
		}
	}

/*	while (list1.selectedIndex >= 0)
		list1.remove(list1.selectedIndex); */
}

// move selected item from right multi select form to other form
function moveLeft() {
	var list1 = document.f_custom_menu.all_menu;
	var list2 = document.f_custom_menu.custom_menu;

	if (list2.selectedIndex == -1) {
		alert("메뉴를 선택해주세요!");
		return;
	}

	while (list2.selectedIndex >= 0)
		list2.remove(list2.selectedIndex);
}

// move up selected item 
function moveUp() {
	var list = document.f_custom_menu.custom_menu;
	var length = list.length;

    for (var i = 0; i < length; i++) {
		if (list[i].selected) {
			for (var j = 0; j < length; j++) {
				if (j == i) {
					if (j > 0) 
						swapListItems(list, j, (j - 1));
				}
			} // end of for j
		}
    } // end of for i
}

// move down selected item
function moveDown() {
	var list = document.f_custom_menu.custom_menu;
	var length = list.length;

	for (var i = (length - 1); i > -1; i--) {
		if (list[i].selected) {
			for (var j = 0; j < length; j++) {
				if (j == i) {
					if (j < (length - 1))
						swapListItems(list, j, (j + 1));
				}
			} // end of for j
		}
	} // end of for i
}

// swaps item a and b in the given form
function swapListItems(list, a, b) { 
  var temp = new Object();

  temp.value = list[b].value;
  temp.text = list[b].text;  
  temp.selected = list[b].selected;

  list[b].value = list[a].value;
  list[b].text = list[a].text;  
  list[b].selected = list[a].selected;

  list[a].value = temp.value;
  list[a].text = temp.text;  
  list[a].selected = temp.selected;
}

function saveCustomMenu() {
	var list = document.f_custom_menu.custom_menu;

	var str = "";

	for (var i = 0; i < list.length; i++) {
		str += list[i].value;
		if (i + 1 != list.length) str += ",";
	}

	document.f_custom_menu.custom_menus.value = str;

	document.f_custom_menu.submit();
}

function goMainPage()
{
	document.location=context_root+"/";
}
-->
</script>
<?=jaram_info("맨 위에 선택된 메뉴가 로그인 할 경우 기본적으로 가는 메뉴로 설정됩니다.")?>

<p>
커스텀메뉴는 필요한 메뉴를 선택하여 <img src="./images/icons/arrow_right.gif" border="0" alt="추가"/> 버튼을 클릭하면 추가할 수 있습니다.<br/>
일반적인 선택과 같이 Ctrl을 누르면서 선택하면 중복 선택할 수 있고, Shift의 경우 범위 선택을 할 수 있습니다.<br/>
<img src="./images/icons/arrow_up.gif" border="0" alt="↑"/>, <img src="./images/icons/arrow_down.gif" border="0" alt="↓" title="아래로"/> 버튼을 이용해서 메뉴의 상하를 조절할 수 있습니다.<br/>
</p>
<p>
&lt;, &gt;로 둘러쌓여 있는 메뉴는 메인메뉴입니다.
</p>

<form name="f_custom_menu" method="post" action="./?page=custom_add">
<table width="100%" cellpadding="3" cellspacing="0" border="0">
	<tr>
		<td width="50%" align="center">
			<select name="all_menu" size="10" multiple style="width:100%;height:200" class="input_box">
<?
dbconn();

$result = mysql_query("SELECT pid, main_menu FROM jaram_programs WHERE order_num = 0 ORDER BY pid");
while ($rows = mysql_fetch_array($result)) {
	print "<optgroup label=\"".$rows["main_menu"]."\">\n";
	print "\t<option label=\"".$rows["main_menu"]."\" value=\"".$rows["pid"].":\">&lt;".$rows["main_menu"]."&gt;</option>\n";

	$result_sub = mysql_query("SELECT pid, bid, sub_menu FROM jaram_programs WHERE main_menu='".$rows["main_menu"]."' AND order_num != 0 ORDER BY order_num ASC");
	while ($rows_sub = mysql_fetch_array($result_sub)) {
		print "\t<option label=\"".$rows_sub["sub_menu"]."\" value=\"".$rows_sub["pid"].":".$rows_sub["bid"]."\">".$rows_sub["sub_menu"]."</option>\n";
	}

	print "</optgroup>\n";
}
?>
			</select>
		</td>
		<td width="30" align="center" valign="middle">
			<a href="javascript:moveRight();" class="button"><img src="./images/icons/arrow_right.gif" border="0" alt="추가"/></a><br/><br/>
			<a href="javascript:moveLeft();" class="button"><img src="./images/icons/arrow_left.gif" border="0" alt="제거"/></a>
		</td>
		<td width="50%" align="center">
			<select name="custom_menu" size="10" multiple style="width:100%;height:200" class="input_box">
<?
// need to query optimize
$result_menu = mysql_query("SELECT p.main_menu, p.sub_menu, p.pid, p.bid, p.order_num FROM jaram_programs AS p LEFT JOIN jaram_custom_menu AS c ON (p.pid = c.pid AND p.bid = c.bid) WHERE uid = '".$_SESSION["jaram_user_uid"]."' ORDER BY c.order_num ASC");

if (@mysql_num_rows($result_menu) > 0) {
	while ($rows = mysql_fetch_array($result_menu)) {
		if ($rows["order_num"] == 0) {
			print "<option label=\"".$rows["main_menu"]."\" value=\"".$rows["pid"].":\">&lt;".$rows["main_menu"]."&gt;</option>\n";
		} else {
			print "\t<option label=\"".$rows["sub_menu"]."\" value=\"".$rows["pid"].":".$rows["bid"]."\">".$rows["sub_menu"]."</option>\n";
		}
	}
}

?>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td align="center">
			<a href="javascript:moveUp();" class="button"><img src="./images/icons/arrow_up.gif" border="0" alt="↑" title="위로"/></a>&nbsp;
			<a href="javascript:moveDown();" class="button"><img src="./images/icons/arrow_down.gif" border="0" alt="↓" title="아래로"/></a>
			<br/><br/>
		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input name="save" type="image" src="./images/button/btn_save.gif" value="Save Custom Menu" onclick="saveCustomMenu();"/>
			<input name="custom_menus" type="hidden" value=""/>
			<a href="javascript:goMainPage();"><img src="./images/button/btn_back.gif" border="0"/></a>
		</td>
	</tr>
</table>
</form>