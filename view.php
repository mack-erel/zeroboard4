<?

/***************************************************************************
 * ���� ���� include
 **************************************************************************/
 	if(!$_view_included) {include "_head.php";}

/***************************************************************************
 * �Խ��� ���� üũ
 **************************************************************************/

// ������ üũ
	if($setup[grant_view]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&s_url=".urlencode($REQUEST_URI));


// ���� ���õ� ����Ÿ�� ������, �� $no �� ������ ����Ÿ ������
	unset($data);
	$_dbTimeStart = getmicrotime();
	$data=mysql_fetch_array(mysql_query("select * from  $t_board"."_$id  where no='$no'"));
	$_dbTime += getmicrotime()-$_dbTimeStart;

	if(!$data[no]) Error("�����Ͻ� �Խù��� �������� �ʽ��ϴ�","zboard.php?$href$sort");

// �����۰� ���ı��� ����Ÿ�� ����;
	if(!$setup[use_alllist]) {	
		$_dbTimeStart = getmicrotime();
		if($data[prev_no]) $prev_data=mysql_fetch_array(mysql_query("select * from  $t_board"."_$id  where no='$data[prev_no]'"));
		if($data[next_no]) $next_data=mysql_fetch_array(mysql_query("select * from  $t_board"."_$id  where no='$data[next_no]'"));
		$_dbTime += getmicrotime()-$_dbTimeStart;
	}

// ��� ��� ���Ⱑ �ƴҶ� ���ñ��� ��� �о��;;
	if(!$setup[use_alllist]) {	
		$_dbTimeStart = getmicrotime();
		$check_ref=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where division='$data[division]' and headnum='$data[headnum]'"));
		if($check_ref[0]>1) $view_result=mysql_query("select * from $t_board"."_$id  where division='$data[division]' and headnum='$data[headnum]' order by headnum desc,arrangenum");
		$_dbTime += getmicrotime()-$_dbTimeStart;
	}

// ������ ����� ����Ÿ�� �������;;
	$_dbTimeStart = getmicrotime();
	$view_comment_result=mysql_query("select * from $t_comment"."_$id where parent='$no' order by no asc");
	$_dbTime += getmicrotime()-$_dbTimeStart;

// zboard.php���� ��ũ���� ��� ��ġ�� zboard.php�� ����
	if(!$_view_included) $target="view.php";
	else $target="zboard.php";

// ��б��̰� �н����尡 Ʋ���� �����ڰ� �ƴϸ� ���� ǥ��
	if($data[is_secret]&&!$is_admin&&$data[ismember]!=$member[no]&&$member[level]>$setup[grant_view_secret]) {
		if($member[no]) {
			$secret_check=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where headnum='$data[headnum]' and ismember='$member[no]'"));
			if(!$secret_check[0]) error("��б��� ������ ������ �����ϴ�");
		} else {
			$secret_check=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where headnum='$data[headnum]' and password=password('$password')"));
			if(!$secret_check[0]) {
				head();
				$a_list="<a onfocus=blur() href='zboard.php?$href$sort'>";    
				$a_view="<Zeroboard ";
				$title="�� ���� ��б��Դϴ�.<br>��й�ȣ�� �Է��Ͽ� �ֽʽÿ�";
				$input_password="<input type=password name=password size=20 maxlength=20 class=input>";
				if(eregi(":\/\/",$dir)||eregi("\.\.",$dir)) $dir="./";
				include $dir."/ask_password.php";
				foot();
				exit();
			} else {
				$secret_str = $setup[no]."_".$no;
				@setcookie("zb_s_check",$secret_str);
			}
		}
	}

// ������� HIT���� �ø�;;
	if(!eregi($setup[no]."_".$no,$HTTP_SESSION_VARS["zb_hit"])) {
		$_dbTimeStart = getmicrotime();
		mysql_query("update $t_board"."_$id set hit=hit+1 where no='$no'");
		$_dbTime += getmicrotime()-$_dbTimeStart;
		$hitStr=",".$setup[no]."_".$no;
		
		// 4.0x �� ���� ó��
		$zb_hit=$HTTP_SESSION_VARS["zb_hit"].$hitStr;
		session_register("zb_hit");
	}

// ������ ����
	if($data[prev_no]&&!$setup[use_alllist]) {
		$prev_comment_num="[".$prev_data[total_comment]."]"; // ������ ��� ��
		if($prev_data[total_comment]==0) $prev_comment_num="";
		$a_prev="<a onfocus=blur() href='".$target."?".$href.$sort."&no=$data[prev_no]'>";
		$prev_subject=$prev_data[subject]=stripslashes($prev_data[subject])." ".$prev_comment_num;
		$prev_name=$prev_data[name]=stripslashes($prev_data[name]);
		$prev_data[email]=stripslashes($prev_data[email]);

		$temp_name = get_private_icon($prev_data[ismember], "2");
		if($temp_name) $prev_name="<img src='$temp_name' border=0 align=absmiddle>";

		if($setup[use_formmail]&&check_zbLayer($prev_data)) {
			$prev_name = "<span $show_ip onMousedown=\"ZB_layerAction('zbLayer$_zbCheckNum','visible')\" style=cursor:hand>$prev_name</span>";
		} else {
			if($prev_data[ismember]) $prev_name="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?id=$id&member_no=$prev_data[ismember]','mailform','width=400,height=510,statusbar=no,scrollbars=yes,toolbar=no'))\" $show_ip>$prev_name</a>";
			else $prev_name="<div $show_ip>$prev_name</div>";
		}

		$prev_hit=stripslashes($prev_data[hit]);
		$prev_vote=stripslashes($prev_data[vote]);
		$prev_reg_date="<span title='".date("Y/m/d H:i:d",$prev_data[reg_date])."'>".date("Y/m/d",$prev_data[reg_date])."</span>";

		if(!isBlank($prev_email)||$prev_data[ismember]) {
			if(!$setup[use_formmail]) $a_prev_email="<a onfocus=blur() href='mailto:$prev_email'>";
			else $a_prev_email="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?to=$prev_email&id=$id&member_no=$prev_data[ismember]','mailform','width=400,height=500,statusbar=no,scrollbars=yes,toolbar=no'))\">";
			$prev_name=$a_prev_email.$prev_name."</a>";
		} 

		$prev="";
		$prev_icon=get_icon($prev_data);

		// �̸��տ� �ٴ� ������ ����;;
		$prev_face_image=get_face($prev_data);

		// ���� ���Ϸ� ������
		$prev_mail=$prev_data[email]="";
		$a_prev_email="<Zeroboard ";
	} else {
		$hide_prev_start="<!--";
		$hide_prev_end="-->";
	}

// ������ ����
	if($data[next_no]&&!$setup[use_alllist]) {
		$a_next="<a onfocus=blur() href='".$target."?".$href.$sort."&no=$data[next_no]'>";
		$next_comment_num="[".$next_data[total_comment]."]"; // ������ ��� ��
		if($next_data[total_comment]==0) $next_comment_num="";
		$next_subject=$next_data[subject]=stripslashes($next_data[subject])." ".$next_comment_num;
		$next_name=$next_data[name]=stripslashes($next_data[name]);
		$next_data[email]=stripslashes($next_data[email]);

		$temp_name = get_private_icon($next_data[ismember], "2");
		if($temp_name) $next_name="<img src='$temp_name' border=0 align=absmiddle>";

		if($setup[use_formmail]&&check_zbLayer($next_data)) {
			$next_name = "<span $show_ip onMousedown=\"ZB_layerAction('zbLayer$_zbCheckNum','visible')\" style=cursor:hand>$next_name</span>";
		} else {
			if($next_data[ismember]) $next_name="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?id=$id&member_no=$next_data[ismember]','mailform','width=400,height=510,statusbar=no,scrollbars=yes,toolbar=no'))\" $show_ip>$next_name</a>";
			else $next_name="<div $show_ip>$next_name</div>";
		}
		
		$next_hit=stripslashes($next_data[hit]);
		$next_vote=stripslashes($next_data[vote]);
		$next_reg_date="<span title='".date("Y/m/d H:i:d",$next_data[reg_date])."'>".date("Y/m/d",$next_data[reg_date])."</span>";
		if(!isBlank($next_email)||$next_data[ismember]) {
			if(!$setup[use_formmail]) $a_next_email="<a onfocus=blur() href='mailto:$next_email'>";
			else $a_next_email="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?to=$next_email&id=$id&member_no=$next_data[ismember]','mailform','width=400,height=500,statusbar=noscrollbars=yes,toolbar=no'))\">";
			$next_name=$a_next_email.$next_name."</a>";
		}

		$next_icon=get_icon($next_data);

		// �̸��տ� �ٴ� ������ ����;;
		$next_face_image=get_face($next_data);

		// ���� ���Ϸ� ������
		$next_mail=$next_data[email]="";
		$a_next_email="<Zeroboard ";
	} else {
		$hide_next_start="<!--";
		$hide_next_end="-->";
	}


// ���� ���õ� ���� ������
	list_check($data,1);

/****************************************************************************************
 * ���� ����
 ***************************************************************************************/

// �ۺ��⿡�� ���� ���� ����
	$subject=$data[subject];
	if($data[homepage]) $a_homepage="<a onfocus=blur() href='$data[homepage]' target=_blank>"; else $a_homepage="<Zetx"; // Ȩ������ �ּ� ��ũ


/****************************************************************************************
 * ��ư ����
 ***************************************************************************************/

// �����ּҰ� ������ �̸��� ���� ��ũ
	if(!isBlank($email)||$data[ismember]) {
		if(!$setup[use_formmail]) $a_email="<a onfocus=blur() href='mailto:$email'>";
		else $a_email="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?to=$email&id=$id&member_no=$data[ismember]','mailform','width=400,height=500,statusbar=no,scrollbars=yes,toolbar=no'))\">";
	} else $a_email="<Zeroboard ";

// �۾����ư
	if($is_admin||$member[level]<=$setup[grant_write]) $a_write="<a onfocus=blur() href='write.php?$href$sort&no=$no&mode=write&sn1=$sn1'>"; else $a_write="<Zeroboard ";

// ��� ��ư
	if(($is_admin||$member[level]<=$setup[grant_reply])&&$no&&$data[headnum]>-2000000000) $a_reply="<a onfocus=blur() href='write.php?$href$sort&no=$no&mode=reply&sn1=$sn1'>"; else $a_reply="<Zeroboard ";

// ��� ��ư
	if($is_admin||$member[level]<=$setup[grant_list]) $a_list="<a onfocus=blur() href='zboard.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&prev_no=$no&sn1=$sn1&divpage=$divpage&select_arrange=$select_arrange&desc=$desc'>"; else $a_list="<Zeroboard  ";

// ��ҹ�ư
	$a_cancel="<a onfocus=blur() href='$PHP_SELF?id=$id'>";

// ������ư
	if(($is_admin||$member[level]<=$setup[grant_delete]||$data[ismember]==$member[no]||!$data[ismember])&&!$data[child]) $a_delete="<a onfocus=blur() href='delete.php?$href$sort&no=$no'>"; else $a_delete="<Zeroboard ";

// ������ư
	if(($is_admin||$member[level]<=$setup[grant_delete]||$data[ismember]==$member[no]||!$data[ismember])&&$no) $a_modify="<a onfocus=blur() href='write.php?$href$sort&no=$no&mode=modify'>"; else $a_modify="<Zeroboard ";

// ���ϸ�ũ
	if($file_name1) $a_download1="<a onfocus=blur() href='download.php?$href$sort&no=$no&file=1'>"; else $a_download1="<Zeroboard ";
	if($file_name2) $a_download2="<a onfocus=blur() href='download.php?$href$sort&no=$no&file=2'>"; else $a_download2="<Zeroboard ";

// ��õ��ư
	if(!eregi($setup[no]."_".$no,$HTTP_SESSION_VARS["zb_vote"])) $a_vote="<a onfocus=blur() href='vote.php?$href$sort&no=$no'>";
	else $a_vote = "<Zeroboard ";

// ����Ʈ ��ũ�� ��Ÿ���� �ϴ� ����;;
	if(!$sitelink1) {$hide_sitelink1_start="<!--";$hide_sitelink1_end="-->";}
	if(!$sitelink2) {$hide_sitelink2_start="<!--";$hide_sitelink2_end="-->";}

// ���� �ٿ�ε带 ��Ÿ���� �ϴ� ����;;
	if(!$file_name1) {$hide_download1_start="<!--";$hide_download1_end="-->";}
	if(!$file_name2) {$hide_download2_start="<!--";$hide_download2_end="-->";}
 
// Ȩ�������� ��Ÿ���� �ϴ� ����
	if(!$data[homepage]) {$hide_homepage_start="<!--";$hide_homepage_end="-->";}

// E-MAIL �� ��Ÿ���� �ϴ� ����
	if(!$data[email]) {$hide_email_start="<!--";$hide_email_end="-->";}
 
// �ڸ�Ʈ�� �� ���̰� �ϴ� ����;;
	if(!$setup[use_comment])
	{$hide_comment_start="<!--"; $hide_comment_end="-->";}

// ȸ���α����� �Ǿ� ������ �ڸ�Ʈ ��й�ȣ�� �� ��Ÿ����;;
	if($member[no]) {
		$c_name=$member[name]; $hide_c_password_start="<!--"; $hide_c_password_end="-->"; 
		$temp_name = get_private_icon($member[no], "2");
		if($temp_name) $c_name="<img src='$temp_name' border=0 align=absmiddle>";
		$temp_name = get_private_icon($member[no], "1");
		if($temp_name) $c_name="<img src='$temp_name' border=0 align=absmiddle>".$c_name;
	} else $c_name="<input type=text name=name size=8 maxlength=10 class=input value=\"".$HTTP_SESSION_VARS["zb_writer_name"]."\">";


/****************************************************************************************
 * ���� ��� �κ�
 ***************************************************************************************/
// ��� ���
	if(!$_view_included)head();

// ��� ��Ȳ �κ� ��� 
	if(!$_view_included) {
		$_skinTimeStart = getmicrotime();
		include "$dir/setup.php";
		$_skinTime += getmicrotime()-$_skinTimeStart;
	}


// ���뺸�� ���
	$_skinTimeStart = getmicrotime();
	include $dir."/view.php";
	$_skinTime += getmicrotime()-$_skinTimeStart;

// �ڸ�Ʈ ���;;
	if($setup[use_comment]) {
		while($c_data=mysql_fetch_array($view_comment_result)) {
			$comment_name=stripslashes($c_data[name]);
			$temp_name = get_private_icon($c_data[ismember], "2");
			if($temp_name) $comment_name="<img src='$temp_name' border=0 align=absmiddle>";
			$c_memo=trim(stripslashes($c_data[memo]));
			$c_reg_date="<span title='".date("Y�� m�� d�� H�� i�� s��",$c_data[reg_date])."'>".date("Y/m/d",$c_data[reg_date])."</span>";
			if($c_data[ismember]) {
				if($c_data[ismember]==$member[no]||$is_admin||$member[level]<=$setup[grant_delete]) $a_del="<a onfocus=blur() href='del_comment.php?$href$sort&no=$no&c_no=$c_data[no]'>";
				else $a_del="&nbsp;<Zeroboard ";
			} else $a_del="<a onfocus=blur() href='del_comment.php?$href$sort&no=$no&c_no=$c_data[no]'>";

			// �̸��տ� �ٴ� ������ ����;;
			$c_face_image=get_face($c_data);

			if($is_admin) $show_ip=" title='$c_data[ip]' "; else $show_ip="";    

			if($setup[use_formmail]&&check_zbLayer($c_data)) {
				$comment_name = "<span $show_ip onMousedown=\"ZB_layerAction('zbLayer$_zbCheckNum','visible')\" style=cursor:hand>$comment_name</span>";
			} else {
				if($c_data[ismember]) $comment_name="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?id=$id&member_no=$c_data[ismember]','mailform','width=400,height=510,statusbar=no,scrollbars=yes,toolbar=no'))\" $show_ip>$comment_name</a>";
				else $comment_name="<div $show_ip>$comment_name</div>";
			}

			$_skinTimeStart = getmicrotime();
			include $dir."/view_comment.php";
			$_skinTime += getmicrotime()-$_skinTimeStart;
			flush();
		}
		if($member[level]<=$setup[grant_comment]) {
			$_skinTimeStart = getmicrotime();
			include "$dir/view_write_comment.php";
			$_skinTime += getmicrotime()-$_skinTimeStart;
		}
	}

// ��, �Ʒ��� ���, �ڸ�Ʈ, ��ư ���
	$_skinTimeStart = getmicrotime();
	include $dir."/view_foot.php";
	$_skinTime += getmicrotime()-$_skinTimeStart;

// ���ñ��� ���
	if($check_ref[0]>1) {

		$_skinTimeStart = getmicrotime();
		include "$dir/view_list_head.php";
		$_skinTime += getmicrotime()-$_skinTimeStart;

		while($data=mysql_fetch_array($view_result)) {
			// ����Ÿ ����
			list_check($data);

			if($data[no]==$no) $number="<img src=$dir/arrow.gif border=0>"; else $number="&nbsp;";
	
			// ����� ����ϴ� �κ�
			$_skinTimeStart = getmicrotime();
			include $dir."/view_list_main.php";
			$_skinTime += getmicrotime()-$_skinTimeStart;
		}

		$_skinTimeStart = getmicrotime();
		include "$dir/view_list_foot.php";
		$_skinTime += getmicrotime()-$_skinTimeStart;
	}

	

// layer ���
 	if($zbLayer&&!$_view_included) {
		$_skinTimeStart = getmicrotime();
		echo "\n<script>".$zbLayer."\n</script>";
		$_skinTime += getmicrotime()-$_skinTimeStart;
		unset($zbLayer);
	}

// ������ �κ� ���
	if(!$_view_included) foot();

/***************************************************************************
 * ������ �κ� include
 **************************************************************************/
	if(!$_view_included) { 
		$_skinTimeStart = getmicrotime();
		include "_foot.php"; 
		$_skinTime += getmicrotime()-$_skinTimeStart;
	}

?>
