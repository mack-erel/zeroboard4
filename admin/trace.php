<?php
$_zb_path = "../";

include_once "../lib.php";

$connect = dbconn();

$member = member_info();

$s_keyword = $keyword;

if (!$member["no"] || $member["is_admin"] > 1 || $member["level"] > 1) Error("최고 관리자만이 사용할수 있습니다");

if ($keykind[5]) {
	$userno = mysql_Fetch_array(mysql_query("select no from zetyx_member_table where user_id='$keyword'", $connect));
	$userno = $userno[0];
}

// 실제 검색부분
if ($keyword) {
	$comment_search = 1;
	$s_que = "";
	for ($i = 0; $i < 6; $i++) {
		if ($keykind[$i]) {
			if ($keykind[$i] != "ismember") {
				if (!$s_que) $s_que .= " where $keykind[$i] like '%$keyword%' ";
				else $s_que .= " and $keykind[$i] like '%$keyword%' ";
			} else {
				if ($userno) {
					if (!$s_que) $s_que .= " where $keykind[$i] = '$userno' ";
					else $s_que .= " and $keykind[$i] = '$userno' ";
				}
			}

			if ($keykind[$i] == "email" || $keykind[$i] == "subject") $comment_search = 0;
		}

		$table_name_result = mysql_query("select name, use_alllist from $admin_table order by name", $connect) or error(mysql_error());
	}
}

head(" bgcolor=white");
?>
<div align=center>
	<br>
	<table border=0 cellspacing=0 cellpadding=0 width=98%>
		<tr>
			<td><img src=../images/trace_left.gif border=0></td>
			<td width=100% background=../images/trace_back.gif> <img src=../images/trace_back.gif border=0></td>
			<td><img src=../images/trace_right.gif border=0></td>
		</tr>
		<form action=<?= $_SERVER["PHP_SELF"] ?> method=post>
			<tr>
				<td colspan=3 align=right>

					<Table border=0>
						<tr>
							<td style=line-height:180% height=40 align=right>
								<input type=checkbox name=keykind[0] value="name" <?php if ($keykind[0]) echo "checked"; ?>> 이름 &nbsp;
								<input type=checkbox name=keykind[1] value="email" <?php if ($keykind[1]) echo "checked"; ?>> E-Mail &nbsp;
								<input type=checkbox name=keykind[2] value="ip" <?php if ($keykind[2]) echo "checked"; ?>> 아이피 &nbsp;
								<input type=checkbox name=keykind[3] value="subject" <?php if ($keykind[3]) echo "checked"; ?>> 제목 &nbsp;
								<input type=checkbox name=keykind[4] value="memo" <?php if ($keykind[4]) echo "checked"; ?>> 내용 &nbsp;
								<input type=checkbox name=keykind[5] value="ismember" <?php if ($keykind[5]) echo "checked"; ?>> 아이디 &nbsp;
							</td>
							<td><input type=text name=keyword value="<?= $s_keyword ?>" size=20 class=input>&nbsp;</td>
							<td><input type=image src=../images/trace_search.gif border=0 valign=absmiddle></td>
						</tr>
						<tr>
							<td colspan=3 align=right>
								<font color=darkred>* 체크된 항목은 AND 연산됩니다, 즉 선택된 항목이 모두 해당될때입니다.</font>
							</td>
						</tr>
		</form>
	</table>
	</td>
	</tr>
	</table>
</div>

<?php
if ($keyword && $s_que) {
	while ($table_data = mysql_fetch_array($table_name_result)) {

		$table_name = $table_data["name"];
		if ($table_data["use_alllist"]) $file = "zboard.php";
		else $file = "view.php";

		// 본문
		$result = mysql_query("select * from $t_board" . "_$table_name $s_que", $connect) or error(mysql_error());
?>

		<br><br><br>

		&nbsp;&nbsp;<a href=../zboard.php?id=<?= $table_name ?> target=_blank>
			<font size=4 style=font-family:tahoma; color=black><?= $table_name ?>&nbsp;<b>게시판</b></font>
		</a><br>
		<?php
		while ($data = mysql_fetch_array($result)) {
			flush();
			$data["subject"] = eregi_replace($keyword, "<font color=red>$keyword</font>", del_html(stripslashes($data["subject"])));
		?>

			&nbsp;&nbsp; [<?= stripslashes($data["name"]) ?>] &nbsp;
			<a href=../<?= $file ?>?id=<?= $table_name ?>&no=<?= $data["no"] ?> target=_blank><?= $data["subject"] ?></a></b>
			&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
			<font color=666666>(<font color=blue><?= date("Y-m-d H:i:s", $data["reg_date"]) ?></font> / <font color=green><?= $data["ip"] ?></font>)</font>

			<img src=../images/t.gif border=0 height=20><Br>

		<?php
		}

		mysql_free_result($result);

		/// 코멘트
		if ($comment_search) {
			$result = mysql_query("select * from $t_comment" . "_$table_name $s_que", $connect) or error(mysql_error());
		?>

			<br><Br><br>
			&nbsp;&nbsp;&nbsp;&nbsp;<a href=../zboard.php?id=<?= $table_name ?> target=_blank>
				<font size=3 style=font-family:tahoma;><?= $table_name ?><b>게시판</b> 의 간단한 답글</font>
			</a>
			<br>
			<?php
			while ($data = mysql_fetch_array($result)) {
				flush();
				$data["memo"] = eregi_replace($keyword, "<font color=red>$keyword</font>", del_html(stripslashes($data["memo"])));
			?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ <?= stripslashes($data["name"]) ?> ]
				&nbsp;<a href=../<?= $file ?>?id=<?= $table_name ?>&no=<?= $data["parent"] ?> target=_blank><?= $data["memo"] ?></a> &nbsp;&nbsp;
				<font color=666666>(<font color=blue><?= date("Y-m-d H:i:s", $data["reg_date"]) ?></font> / <font color=green><?= $data["ip"] ?></font>)</font>
				<img src=../images/t.gif border=0 height=20><Br>

<?php
			}
		}
	}
}

mysql_close($connect);
$connect = "";
?>

<br><Br><Br>

<?php
foot();
?>