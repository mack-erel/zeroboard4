<?
/***************************************************************************
 * �������� include
 **************************************************************************/
	include "_head.php";

// ������ üũ
	if($setup[grant_view]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

// ������� Vote�� �ø�;;
	if(!eregi($setup[no]."_".$no,  $HTTP_SESSION_VARS[zb_vote])) {
		mysql_query("update $t_board"."_$id set vote=vote+1 where no='$sub_no'");
		mysql_query("update $t_board"."_$id set vote=vote+1 where no='$no'");

		// 4.0x �� ���� ó��
		$zb_vote = $HTTP_SESSION_VARS[zb_vote] . "," . $setup[no]."_".$no;
		session_register("zb_vote");

		// ���� ���� ó�� (4.0x�� ���� ó���� ���Ͽ� �ּ� ó��)
		//$HTTP_SESSION_VARS[zb_vote] = $HTTP_SESSION_VARS[zb_vote] . "," . $setup[no]."_".$no;
	}

	@mysql_close($connect);

// ������ �̵�
	if($setup[use_alllist]) movepage("zboard.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");
	else  movepage("view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");
  
?>
