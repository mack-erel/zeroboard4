
<?
if(!eregi("Zeroboard",$a_list)) $a_list = str_replace(">","><font class=list_eng>",$a_list)."&nbsp;&nbsp;";
if(!eregi("Zeroboard",$a_reply)) $a_reply = str_replace(">","><font class=list_eng>",$a_reply)."&nbsp;&nbsp;";
if(!eregi("Zeroboard",$a_modify)) $a_modify = str_replace(">","><font class=list_eng>",$a_modify)."&nbsp;&nbsp;";
if(!eregi("Zeroboard",$a_delete)) $a_delete = str_replace(">","><font class=list_eng>",$a_delete)."&nbsp;&nbsp;";
if(!eregi("Zeroboard",$a_write)) $a_write = str_replace(">","><font class=list_eng>",$a_write)."&nbsp;&nbsp;";
if(!eregi("Zeroboard",$a_vote)) $a_vote = str_replace(">","><font class=list_eng>",$a_vote)."&nbsp;&nbsp;";
?>

<table border=0 cellspacing=0 cellpadding=0 height=1 width=<?=$width?>>
<tr><td height=1 class=line1 style=height:1px><img src=<?=$dir?>/t.gif border=0 height=1></td></tr>
</table>
<img src=/images/t.gif border=0 height=8><br>

<table width=<?=$width?> cellspacing=0 cellpadding=0>
<tr>
 <td height=30>
    <?=$a_reply?>��۴ޱ�</a>
    <?=$a_modify?>�����ϱ�</a>
    <?=$a_delete?>�����ϱ�</a>
    <?=$a_vote?>��õ�ϱ�</a>
 </td>
 <td align=right>
    <?=$a_list?>��Ϻ���</a>
    <?=$a_write?>�۾���</a>
 </td>
</tr>
</table>

<br>
