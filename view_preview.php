<?
	include "lib.php";
	include "include/list_check.php";

	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("���������� ���� �ۼ��Ͽ� �ֽñ� �ٶ��ϴ�.","window.close");
	if(!eregi("write.php",$HTTP_REFERER)) Error("���������� ���� ���ñ� �ٶ��ϴ�","window.close");
	if(getenv("REQUEST_METHOD") == 'GET' ) Error("���������� ���� ���ñ� �ٶ��ϴ�","window.close");


	if(!$subject) Error("������ �Է��Ͽ� �ֽʽÿ�","window.close");
	if(!$memo) Error("������ �Է��Ͽ� �ֽʽÿ�","window.close");
	

	$connect=dbconn();

// �Խ��� ���� �о� ����
	$setup=get_table_attrib($id);

// �������� ���� �Խ���
	if(!$setup[name]) Error("�������� ���� �Խ����Դϴ�.<br><br>�Խ����� ������ ����Ͻʽÿ�","window.close()"); 

// ���� �Խ����� �׷��� ���� �о� ����
	$group=group_info($setup[group_no]);

// ȸ�� ����Ÿ �о� ����
	$member = member_info();

// ���� �α��εǾ� �ִ� ����� ��ü, �Ǵ� �׷���������� �˻�
	if($member[is_admin]==1||($member[is_admin]==2&&$member[group_no]==$setup[group_no])||check_board_master($member, $setup[no])) $is_admin=1; else $is_admin="";


// ������ �Խù� ����Ÿ ����

	if($use_html<2) {
		$memo=str_replace("  ","&nbsp;&nbsp;",$memo);
		$memo=str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$memo);
	}


	// ���� ����
 	if(!$is_admin&&$setup[grant_html]<$member[level]) {

		// ������ HTML ����;;
		if($use_html!=1||$setup[use_html]==0) $memo=del_html($memo);

		// HTML�� �κ�����϶�;;
		if($use_html==1&&$setup[use_html]==1) {
			$memo=str_replace("<","&lt;",$memo);
			$tag=explode(",",$setup[avoid_tag]);
			for($i=0;$i<count($tag);$i++) {
				if(!isblank($tag[$i])) {
					$memo=eregi_replace("&lt;".$tag[$i]." ","<".$tag[$i]." ",$memo);
					$memo=eregi_replace("&lt;".$tag[$i].">","<".$tag[$i].">",$memo);
					$memo=eregi_replace("&lt;/".$tag[$i],"</".$tag[$i],$memo);
				}
			}
		}
	} else {
		if(!$use_html) {
			$memo=del_html($memo);
		}
	}

	$data[memo]=$memo;

	// ���� ����
	if(($is_admin||$member[level]<=$setup[use_html])&&$use_html) $data[subject]=$subject;
	else $data[subject]=del_html($subject);

	// ��Ÿ ����Ÿ �ۼ�
	$data[use_html]=$use_html;
	$data[ismember]=$member[no];

// ����Ÿ ����
	list_check($data,1);
?>
<html>
<head>
	<title><?=$setup[title]?></title>
	<meta http-equiv=Content-Type content=text/html; charset=EUC-KR>
	<link rel=StyleSheet HREF=skin/<?=$setup[skinname]?>/style.css type=text/css title=style>
</head>
<body topmargin='10'  leftmargin='10' marginwidth='10' marginheight='10' <?
	if($setup[bg_color]) echo " bgcolor=".$setup[bg_color];
	if($setup[bg_image]) echo " background=".$setup[bg_image];?>>

<table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor=white>
<tr>
	<td align=left><img src=images/pv_title_left.gif border=0></td>
	<td width=100% background=images/pv_title_back.gif><img src=images/pv_title_back.gif></td>
	<td align=right><img src=images/pv_title_right.gif border=0></td>
</tr>
</table>

<table border=0 cellspacing=0 cellpadding=10 width=100% height=100% bgcolor=black>
<Tr bgcolor=white valign=top>
	<td height=20>
		<b><?=$data[subject]?></b><br>
	</td>
</tr>
<Tr bgcolor=white valign=top>
	<td>
		<?=$memo?>
	</td>
</tr>
</table>

</body>
</html>

<?
	@mysql_close($connect);
?>
