<?
 /* ������ ��� ���� ǥ��

  -- ������ ��� ����
  <?=$hide_comment_start?> <?=$hide_comment_end?> : ������ ��� ���� �����ֱ�/ �����
  <?=$hide_c_password_start?> <?=$hide_c_password_end?> : ������ ��۽� ��й�ȣ �Է� �����ֱ�/ �����;;

  <?=$c_name?> : �ڸ�Ʈ�� �̸� �Է��ϴ� ��;;

  ** view.php ���� �Ʒ��ʿ� ������ ����� �����ϴ� <table>�±� ���ۺκ��� �ֽ��ϴ�.
     �׸��� ������ ����� ������ view_comment_view.php ���Ͽ��� ����� �մϴ�.

 */
?>


<!-- ������ �亯�� ���� -->
<tr>
<td width=100%>
<table border=0 width=100% cellspacing=0 cellpadding=0 height=30>
<tr>
<td width=0>
<form method=post name=write action=comment_ok.php>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>">
</td>
<td align=center>
   <font color=444444 >�̸� : </b></font><b> <?=$c_name?> &nbsp;</b>
   <font color=444444 >�ǰ� : </b></font> <input type=text name=memo <?=size(40)?> maxlength=100 class=input>
   <?=$hide_c_password_start?> &nbsp;
   <font color=444444 >��й�ȣ : </b></font>  <input type=password name=password <?=size(10)?> maxlength=20 class=input>
   <?=$hide_c_password_end?>
   <input type=submit value="�Է�" class=submit>
 </td>
</tr>
</table>
</form>
</table>
