<?php header("content-type:text/html; charset=gb2312"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>小区带宽OA系统</title>
<style type="text/css" >
body{
	font-size:12px;
}
.dvi_top{
border-bottom:solid 1px #000000; 
position:relative; 
height:150px;
}
.div_tool{
height:30px;
position:relative;
}
.div_tool #div_search{
width:400px;
position:absolute;
left:8px;
top:8px;
}
.dvi_menu{
	position:absolute;
	height:30px; 
	width:30px; 
	padding:5px;
	border:dotted 1px #333333;
	margin:2px;
	vertical-align:middle;
	text-align:center;
	bottom:3px;
	font-weight:bold;
	color:#330000;
	
}
.windows{
position:absolute;
display:none;
background-color:#999999;
border:solid 1px #000000;
overflow:hidden;

}
.windows_title{
float:left;
}
.windows_ctr{
float:right;
}
.if{
border:0px;
width:400px;
height:400px;
}
.div_admin{
position:absolute;
left:5px;
}
</style>
<script language="javascript">
var ox, oy, nx, ny, dy, dx;
var oDrag = "";
function drag(e, obj) {
var e = e ? e : event;
var mouseD = document.all ? 1 : 0;
 
if (e.button == mouseD) {
oDrag = obj.parentNode;
//alert(oDrag.id);
ox = e.clientX;
oy = e.clientY;
obj.style.cursor = "move";
if (obj.setCapture)
obj.setCapture();
else if (window.captureEvents)
window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
}
}
function dragPro(e) {
if (oDrag != "") {
var e = e ? e : event;
dx = parseInt(oDrag.style.left);
dy = parseInt(oDrag.style.top);
nx = e.clientX;
ny = e.clientY;
oDrag.style.left = (dx + (nx - ox)) + "px";
oDrag.style.top = (dy + (ny - oy)) + "px";
ox = nx;
oy = ny;
 
}
}
function StopDrag(obj) {
if (obj.releaseCapture) {
obj.releaseCapture();
}
else if (window.captureEvents) {
window.captureEvents(Event.MOUSEMOVE | Event.MOUSEUP);
}
oDrag = "";
}

function open_w(page_url){
document.all['if_bo'].src = page_url;
//document.all['windows_00'].style.display="block";
}

</script>
</head>
<body>
<?php
require_once("sql.php"); 
require_once("fun.php");
if(login_check()==0) return;
?>
<div>
  <div class="dvi_top"> <img src="user_img.jpg" style="top:5px; height:110px; width:108px;" />
    <div class="div_admin" style="top:115px;">职工姓名：张三李</div>
    <div class="div_admin" style="top:132px;">职称：内务主管</div>
    <div class="dvi_menu" style=" right:360px;">发布通知</div>
    <div class="dvi_menu" style=" right:310px;">派发工单</div>
    <div class="dvi_menu" style=" right:260px;">综合统计</div>
    <div class="dvi_menu" style=" right:210px;"><a href="system.php">系统管理</a></div>
    <div class="dvi_menu" style=" right:160px;"><a href="org.php">组织管理</a></div>
    <img style="position:absolute; right:0px; bottom:-25px; border:0px; width:150px; height:75px;" src="book.gif" /> 
	<img style="position:absolute; right:3px; top:200px; width:64px; height:64px;" src="contacts-alt.png"/>
	<div style="position:absolute; right:3px; top:200px; width:400px; height:500px;"></div>
	
	</div>
</div>
<div class="div_tool">
  <div id="div_search"><b>快搜</b>
    <input type="text" id="search" style="width:300px; height:15px; margin-left:15px;"  />
  </div>
  <div style="position:absolute; left:420px; top:8px; font-weight:bold;"><a href="#" onclick="open_w('business_management.php')" >业务办理</a> . <a href="#" onclick="open_w('business_list.php')">业务查询</a> . <a href="#">业务审批</a></div>
</div>
<iframe width="%100"  id="if_bo"    frameborder="0"  src="about:blank"></iframe>
<div id="windows_00" align="left" class="windows" style="top:300px; left:100px; display:none; width:400px; height:400px;">
  <div style="height:15px; padding:5px;" onmousedown="drag(event,this)" onmousemove="dragPro(event);" onmouseup="StopDrag(this);">
    <div id="w00_00" class="windows_title" >标题</div>
    <div class="windows_ctr"> 
	<a href="#" onclick="javascript:this.parentNode.parentNode.parentNode.style.height='400px'">最大</a>. 
	<a href="#" onclick="javascript:this.parentNode.parentNode.parentNode.style.height='25px'">最小</a>. 
	<a href="#" onclick="javascript:this.parentNode.parentNode.parentNode.style.display='none'">关闭</a></div>
  </div>
  <iframe id="if_00"  class="if" scrolling="no" frameborder="0"  src="about:blank"></iframe>
</div>

</div>
</body>
</html>
