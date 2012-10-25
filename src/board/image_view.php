<html>
<head>
	<title>Show Image</title>
	<meta http-equiv=Content-Type content=text/html; charset=UTF-8>
	<SCRIPT LANGUAGE="JavaScript">
var isNav4, isIE4;
var windowX, windowY;
var bLargeImage = 0;
var x,y;
if (parseInt(navigator.appVersion.charAt(0)) >= 4) 
{
	isNav4 = (navigator.appName == "Netscape") ? 1 : 0;
	isIE4 = (navigator.appName.indexOf("Microsoft") != -1) ? 1 : 0;
}
function fitWindowSize() 
{
	if (isIE4) {
		window.resizeTo(500, 500);
		width = 500 - (document.body.clientWidth -  document.images[0].width);
		height = 500 - (document.body.clientHeight -  document.images[0].height);
		windowX = (window.screen.width-width)/2;
		windowY = (window.screen.height-height)/2;
		if(width>screen.width){
			width = screen.width;
			windowX = 0;
			bLargeImage = 1;
		}
		if(height>screen.height-50){
			height = screen.height-50;
			windowY = 0;
			bLargeImage = 1;
		}
    	x = width/2;
    	y = height/2;
		window.moveTo(windowX,windowY);
		window.resizeTo(width, height);

	} else  {		
		window.resizeTo(document.getElementById('image').width, document.getElementById('image').height);
	}
}
function move() 
{
	if(bLargeImage){
		window.scroll(window.event.clientX - 50,window.event.clientY -50);
	}	
}

</script>

</head>

<body onLoad="fitWindowSize()" bgcolor=#FFFFFF link=#ffffff vlink=#ffffff alink=#ffffff onBlur="self.focus();" >
<div style="position:absolute; left:0px; top:0px;" id="image_layer">
<A href="javascript:self.close()" onmouseover="window.status=(''); return true;" onmouseout="window.status=(''); return true;" onfocus='this.blur()'>
<?
if ($_GET[uid1])
{
	echo "<img src=\"../member/profile/".$_GET[uid1]."\" border=\"0\" id=\"image\" style='cursor:crosshair;' onmousemove=\"move();\">";
}
else if($_GET[uid2])
{
	echo "<img src=\"../member/profile/".$_GET[uid2]."_sub\" border=\"0\" id=\"image\" style='cursor:crosshair;' onmousemove=\"move();\">";
}
else
{
?>
<img src="imgview.php?tableID=<?=$_GET["tableID"]?>&fileid=<?=$_GET["fileid"]?>" border=0 style='cursor:crosshair;' onmousemove="move();">
<?
}
?>
</a>
</div>
</body>

</html>