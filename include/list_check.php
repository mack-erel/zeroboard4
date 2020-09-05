<?

	if($_list_check_included) return;
	$_list_check_included = true;

/*********************************************************************************************
 * �Ѱ����� ����Ÿ�� ���� �ϰ� ����
 ********************************************************************************************/

function list_check(&$data,$view_check=0) {

	global 	$keyword, $sn, $ss, $sc, $setup, $member, $href, $id, $dir, $category_data, $is_admin, $_zbResizeCheck,
			$name,$email,$subject, $sort, $prev_no, $no,$homepage, $memo, $hit, $vote, $ip, $comment_num, $sitelink1, $sitelink2,
			$file_name1, $file_name2, $file_download1, $file_download2, $file_size1, $file_size2,
			$upload_image1, $upload_image2, $category_name, $date, $reg_date, $insert, $icon, $face_image,$number,$loop_number,
			$a_file_link1, $a_file_link2, $a_reply, $a_delete, $a_modify, $zbLayer,  $_zbCheckNum,
			$_listCheckTime;

	$_listCheckTimeStart = getmicrotime();

	if($view_check) $setup[only_board]=0;

	// ���� 5�ٷ� ���� ����
	if($setup[use_status]) {
		$tmpData = explode("\n",stripslashes($data[memo]));
		$totalCommentLineNum = count($tmpData);
		if($totalCommentLineNum>10) {
			$showCommentStr_tail.="\n".($totalCommentLineNum - 10)." lines more... (total : $totalCommentLineNum lines)";
			$tmpData_Count = 10;
		} else $tmpData_Count = $totalCommentLineNum;
		$showCommentStr = "";
		for($i=0;$i<$tmpData_Count;$i++) {
			$tmpStr = trim($tmpData[$i]);
			if($tmpStr) $showCommentStr .= $tmpStr."\n";
		}
		$showCommentStr = str_replace("'","",$showCommentStr);
		$showCommentStr = str_replace("\"","",$showCommentStr);
		$showCommentStr .= $showCommentStr_tail;
	}	

	$_zbCount = check_zbLayer($data);
	
	// HTML ����� ��� ���� ȸ���� html ������ ���ų� �����ڰ� �ƴ϶�� style �Ӽ��� ����
	if($data[use_html]&&$data[islevel]>$setup[grant_html]) {
		$style_pattern = "/(\<.*?)style=(.*?)(\>?)/i";
		$data[memo]=preg_replace($style_pattern,"\\1\\3",$data[memo]);
	}
	


	// ' ���� Ư�����ڶ����� ���� \(��������)�� �����
	$name=$data[name]=stripslashes($data[name]); 
	$temp_name = get_private_icon($data[ismember], "2");
	if($temp_name) $name="<img src='$temp_name' border=0 align=absmiddle>"; 

	$subject=$data[subject]=stripslashes($data[subject]); // ����
	$subject=cut_str($subject,$setup[cut_length]); // ���� �ڸ��� �κ�

	// �˻�� �ش��ϴ� ���ڸ� ����;; ������ �ٲپ���;;
	if($keyword) {
		$keyword_pattern = "/".str_replace("\0","\\0",preg_quote($keyword,"/"))."/i";
		if($sn=="on") $name = preg_replace($keyword_pattern, "<span style='color:#FF001E;background-color:#FFF000;'>$keyword</span>", $name);
		if($ss=="on") $subject = preg_replace($keyword_pattern, "<span color='FF001E' style='color:#FF001E;background-color:#FFF000;'>$keyword</span>", $subject);
	}

	$hit=$data[hit];  // ��ȸ��
	$vote=$data[vote];  // ��ǥ��
	$comment_num="[".$data[total_comment]."]"; // ������ ��� ��
	if($data[total_comment]==0) $comment_num="";
	if($setup[use_alllist]) $view_file="zboard.php"; else $view_file="view.php";
	// ���� ��ũ �Ŵ� �κ�;
	if($member[level]<=$setup[grant_view]||$is_admin) {
		//if($setup[use_status]&&!$data[is_secret]) $addShowComment = " onMouseOver=\"showComment('$showCommentStr',true)\" onMouseOut=\"showComment('',false)\" ";
		if($setup[use_status]&&!$data[is_secret]) $addShowComment = " title=\"$showCommentStr\" ";
		$subject="<a href=\"".$view_file."?$href$sort&no=$data[no]\" $addShowComment >".$subject."</a>"; 
	}

	if(!$setup[only_board]) {
		$homepage=$data[homepage]=stripslashes($data[homepage]);
		if($homepage) $homepage="<a href='$homepage' target=_blank>$homepage</a>";

		// �̹��� �ڽ� ����� ���ؼ� ����ǥ���� ���
		if($data[ismember]) {
			$imageBoxPattern = "/\[img\:(.+?)\.(jpg|gif)\,align\=([a-z]){0,}\,width\=([0-9]+)\,height\=([0-9]+)\,vspace\=([0-9]+)\,hspace\=([0-9]+)\,border\=([0-9]+)\]/i";
			$data[memo]=preg_replace($imageBoxPattern,"<img src='icon/member_image_box/$data[ismember]/\\1.\\2' align='\\3' width='\\4' height='\\5' vspace='\\6' hspace='\\7' border='\\8'>", stripslashes($data[memo]));
		} else {
			$data[memo]=stripslashes($data[memo]); 
		}

		if($data[use_html]<2) $memo=$data[memo]=nl2br($data[memo]);
		$memo=$data[memo];

		// �ڵ���ũ �Ŵ� �κ�;;
		if($setup[use_autolink]) $memo=autolink($memo); 

		$memo .= "<!--\"<-->";

		// �˻�� ������� ������ Ű���带 ����
		if($sc=="on" && $keyword) {
			$keyword_pattern = "/".str_replace("\0","\\0",preg_quote($keyword,"/"))."/i";
			$memo = preg_replace($keyword_pattern, "<span style='color:#FF001E;background-color:#FFF000;'>$keyword</span>", $memo);
		}

		// �̹��� ������� ���ؼ� ó���ϴ� �κ�
		$memo = preg_replace("/(\<img)(.*)(\>?)/i","\\1 name=zb_target_resize style=\"cursor:hand\" onclick=window.open(this.src) \\2 \\3", $memo);
		$memo = "<table border=0 cellspacing=0 cellpadding=0 width=100% style=\"table-layout:fixed;\"><col width=100%></col><tr><td valign=top>".$memo."</table>";
		$_zbResizeCheck = true;

		// ������
		if($is_admin) $ip="IP Address : ".$data[ip]."&nbsp;";  

		$sitelink1=$data[sitelink1]=stripslashes($data[sitelink1]);
		$sitelink2=$data[sitelink2]=stripslashes($data[sitelink2]);
		if($sitelink1)$sitelink1="<a href='$sitelink1' target=_blank>$sitelink1</a>";
		if($sitelink2)$sitelink2="<a href='$sitelink2' target=_blank>$sitelink2</a>";
		$file_name1=$data[s_file_name1];
		$file_name2=$data[s_file_name2];
		$file_download1=$data[download1];
		$file_download2=$data[download2];
		if($file_name1) {
			$file_size1=@GetFileSize(filesize($data[file_name1]));
			$a_file_link1="<a href='download.php?$href$sort&no=$data[no]&filenum=1'>";
		} else {
			$file_size1=0;
			$a_file_link1="<Zeroboard";
		}
		if($file_name2) {
			$file_size2=@GetFileSize(filesize($data[file_name2]));
			$a_file_link2="<a href='download.php?$href$sort&no=$data[no]&filenum=2'>";
		} else {
			$file_size2=0;
			$a_file_link2="<Zeroboard";
		}
  
		$upload_image1=$upload_image2="";

		if(eregi("\.jpg",$file_name1)||eregi("\.gif",$file_name1)||eregi("\.png",$file_name1)) $upload_image1="<img src=$data[file_name1] border=0 name=zb_target_resize style=\"cursor:hand\" onclick=window.open(this.src)><br>";
		if(eregi("\.jpg",$file_name2)||eregi("\.gif",$file_name2)||eregi("\.png",$file_name2)) $upload_image2="<img src=$data[file_name2] border=0 name=zb_target_resize style=\"cursor:hand\" onclick=window.open(this.src)><br>";
	}

	// ī�װ��� �̸��� ����
	if($data[category]&&$setup[use_category]) $category_name=$category_data[$data[category]];
	else $category_name="&nbsp;";

	// �۾� �ð��� ����� �ú��� �� ��ȯ��
	$reg_date="<span title='".date("Y�� m�� d�� H�� i�� s��", $data[reg_date])."'>".date("Y/m/d", $data[reg_date])."</span>";
	$date=date("Y-m-d H:i:s", $data[reg_date]);
	
	// �������� ����ϰ� ���ø޴��� ������ �Ǹ� ���̾����
	if($_zbCount&&$setup[use_formmail]) {
		$name = "<span onMousedown=\"ZB_layerAction('zbLayer$_zbCheckNum','visible')\" style=cursor:hand>$name</span>";
	// �������� ������ ��� ������ ���ϸ�ũ
	} else {
		if($data[email]) $name="<a href=\"javascript:void(window.open('open_window.php?mode=m&str=".urlencode(base64_encode($data[email]))."','ZBremote','width=1,height=1,left=1,top=1'))\">$name</a>";
		//$name="<a href=\"javascript:void(window.open('view_info.php?to=$email&id=$id&member_no=$data[ismember]','mailform','width=400,height=510,statusbar=no,scrollbars=yes,toolbar=no'))\">$name</a>";
	}

	// Depth�� ���� ���Ӱ��� ����
	$insert="";
	if($data[depth]>15) $data[depth]=15;
	for($z=0;$z<$data[depth];$z++) $insert .="&nbsp; ";

	$icon=get_icon($data);

	// �̸��տ� �ٴ� ������ ����;;
	$face_image=get_face($data);

	$number=$loop_number;

	// �ٷ� ���� �� ���� ��� ��ȣ�� ���������� �ٲ�
	if($prev_no==$data[no]) $number="<img src=$dir/arrow.gif border=0 align=absmiddle>"; elseif($number!="&nbsp;") $number=$loop_number;

	// ��� ��ư
	if(($is_admin||$member[level]<=$setup[grant_reply])&&$data[headnum]>-2000000000&&$data[headnum]!=-1) $a_reply="<a href='write.php?$href$sort&no=$data[no]&mode=reply'>"; 
	else $a_reply="<Zeroboard";

	// ������ư
	if(($is_admin||$member[level]<=$setup[grant_delete]||$data[ismember]==$member[no]||!$data[ismember])&&!$data[child]) $a_delete="<a href='delete.php?$href$sort&no=$data[no]'>"; 
	else $a_delete="<Zeroboard";

	// ������ư
	if(($is_admin||$member[level]<=$setup[grant_delete]||$data[ismember]==$member[no]||!$data[ismember])) $a_modify="<a href='write.php?$href$sort&no=$data[no]&mode=modify'>"; 
	else $a_modify="<Zeroboard";

	// ���Ը��Ϸ� ����
	$mail=$data[email]="";

	$_listCheckTime += getmicrotime() - $_listCheckTimeStart;
}
?>
