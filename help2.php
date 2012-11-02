
<? 
$p = "man/man-$_GET[p].png";
$next = (int)$_GET[p] + 1;
?>
<a class=btn href="?t=<?=$_GET[t]?>&p=<?=$next?>"  >下一页</a>
<br>
<img src=<?=$p?> style="width: 800px" > 
