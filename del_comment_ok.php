<?

/***************************************************************************
 * ���� ���� include
 **************************************************************************/
	include "_head.php";

	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("���������� ���� �����Ͽ� �ֽñ� �ٶ��ϴ�.");

/***************************************************************************
* �ڸ�Ʈ ���� ����
**************************************************************************/

// �н����带 ��ȣȭ
	if($password) {
		$temp=mysql_fetch_array(mysql_query("select password('$password')"));
		$password=$temp[0];   
	}

// �������� ������
	$s_data=mysql_fetch_array(mysql_query("select * from $t_comment"."_$id where no='$c_no'"));

// ȸ���϶��� Ȯ��;;
	if(!$is_admin&&$member[level]>$setup[grant_delete]) {
		if(!$s_data[ismember]) {
			if($s_data[password]!=$password) Error("��й�ȣ�� �ùٸ��� �ʽ��ϴ�");
		} else {
			if($s_data[ismember]!=$member[no]) Error("��й�ȣ�� �Է��Ͽ� �ֽʽÿ�");
		}
	}

// �ڸ�Ʈ ����
	mysql_query("delete from $t_comment"."_$id where no='$c_no'") or error(mysql_error());

// �ڸ�Ʈ ���� ����
	$total=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where parent='$no'"));
	mysql_query("update $t_board"."_$id set total_comment='$total[0]' where no='$no'")  or error(mysql_error()); 

// ȸ���� ��� �ش� �ؿ��� ���� �ֱ�
	if($member[no]==$s_data[ismember]) @mysql_query("update $member_table set point2=point2-1 where no='$member[no]'",$connect) or error(mysql_error());

	@mysql_close($connect);

// ������ �̵�
	if($setup[use_alllist]) movepage("zboard.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no");
	else movepage("view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no");
?>
