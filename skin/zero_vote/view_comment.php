<?
  /* ������ ����� ����ϴ� �κ��Դϴ�.
   view.php��Ų���Ͽ� ������ ����� �����ϴ� <table>���� �±װ� ���۵Ǿ� �ֽ��ϴ�.
   �׸���view_foot.php ���Ͽ� </table>�±װ� ������ ��� ���� ���� ���� �ֽ��ϴ�

  <?=$comment_name?> : �۾���
  <?=$c_memo?> : ����
  <?=$c_reg_date?> : ���� �� ����;;
  <?=$a_del?> : �ڸ�Ʈ ���� ��ư��ũ
  <?=$c_face_image?> : ����� ������;;
 */
?>

<tr>
   <td align=center>

  <table border=0 width=90%>
  <tr>
   <td style='word-break:break-all;font-size:9pt;font-color:444444' width=98%>
   <?=$c_face_image?> <?=$comment_name?> </b> <font color=888888 size=1>:::</font> <?=$c_memo?> <?=$c_reg_date?> </td>
   <td align=right size=2%><font color=red><?=$a_del?>x</a>
  </tr>
  </table>

   </td>
</tr>

