<?
/***************************************************************************
 * ���� ���� include
 **************************************************************************/
	include "_head.php";

	if(!eregi($HTTP_HOST,$HTTP_REFERER)) die();

/***************************************************************************
 * �Խ��� ���� üũ
 **************************************************************************/

// ������ üũ
	if($setup[grant_view]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

// ������� Download ���� �ø�;;
    if($filenum==1) {
        mysql_query("update `$t_board"."_$id` set download1=download1+1 where no='$no'");
    } else {
        mysql_query("update `$t_board"."_$id` set download2=download2+1 where no='$no'");
    }

	$data=mysql_fetch_array(mysql_query("select * from  `$t_board"."_$id` where no='$no'"));
  
// �ٿ�ε�;;
	$filename="file_name".$filenum;
	header("location:$data[$filename]");

	if($connect) {
		@mysql_close($connect);
		unset($connect);
	}
?>
