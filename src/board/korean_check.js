function urimalSpellCheck(str) {	
	if (str.length == 0) {
		alert("내용이 입력되지 않았습니다."); 
		return;
	}
	width = 450;
	height = 530;
	left = (screen.width-width)/2;
	itop = (screen.height-height)/2;
	window.open("http://164.125.36.47/urimal-spellcheck.html", "spellCheckPopup", "width="+width+", height="+height+", left="+left+",top="+itop+", scrollbars=yes,status=yes");

	var formObj = document.createElement('form');
	formObj.setAttribute('name','spellCheckForm');
	formObj.setAttribute('action','http://164.125.36.47/WebSpell_ISAPI.dll?Check');
	formObj.setAttribute('method','post');
	formObj.setAttribute('target','spellCheckPopup');
	
	var spellText = document.createElement('input');
	spellText.setAttribute('type', 'hidden');
	spellText.setAttribute('name', 'text1');
	spellText.setAttribute('value', str);
	
	formObj.appendChild(spellText);
	
	document.getElementsByTagName("body")[0].appendChild(formObj);

	formObj.submit();
}
