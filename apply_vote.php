<?
/***************************************************************************
 * 공통파일 include
 **************************************************************************/
	include "_head.php";

// 사용권한 체크
	if($setup[grant_view]<$member[level]&&!$is_admin) Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

// 현재글의 Vote수 올림;;
	if(!eregi($setup[no]."_".$no,  $HTTP_SESSION_VARS[zb_vote])) {
		mysql_query("update $t_board"."_$id set vote=vote+1 where no='$sub_no'");
		mysql_query("update $t_board"."_$id set vote=vote+1 where no='$no'");

		// 4.0x 용 세션 처리
		$zb_vote = $HTTP_SESSION_VARS[zb_vote] . "," . $setup[no]."_".$no;
		session_register("zb_vote");

		// 기존 세션 처리 (4.0x용 세션 처리로 인하여 주석 처리)
		//$HTTP_SESSION_VARS[zb_vote] = $HTTP_SESSION_VARS[zb_vote] . "," . $setup[no]."_".$no;
	}

	@mysql_close($connect);

// 페이지 이동
	if($setup[use_alllist]) movepage("zboard.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");
	else  movepage("view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");
  
?>
