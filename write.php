<?
/***************************************************************************
 * ���� ���� include
 **************************************************************************/
	include "_head.php";

/***************************************************************************
 * �Խ��� ���� üũ
 **************************************************************************/

 	$mode = $HTTP_GET_VARS[mode];

 	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("���������� ���� �ۼ��Ͽ� �ֽñ� �ٶ��ϴ�.");

  if(eregi(":\/\/",$dir)) $dir=".";

// ���� üũ
	if(!$mode||$mode=="write") {
		$mode = "write";
		unset($no);
	}

// ������ üũ
	if($mode=="reply"&&$setup[grant_reply]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");
	elseif($setup[grant_write]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");
	if($mode=="reply"&&$setup[grant_view]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

// ����̳� �����϶� �������� ������;;
	if(($mode=="reply"||$mode=="modify")&&$no) {
		$result=@mysql_query("select * from $t_board"."_$id where no='$no'") or error(mysql_error());
		unset($data);
		$data=mysql_fetch_array($result);
		if(!$data[no]) Error("�������� �������� �ʽ��ϴ�");
	}

// ���� ���϶� ���� üũ
	if($mode=="modify"&&$data[ismember]) {
		if($data[ismember]!=$member[no]&&!$is_admin&&$member[level]>$setup[grant_delete]) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");
	}

// �����ۿ��� ����� �� �޸��� ó��
	if($mode=="reply"&&$data[headnum]<=-2000000000) Error("�����ۿ��� ����� �޼� �����ϴ�");


// ī�װ� ����Ÿ ������;;
	$category_result=mysql_query("select * from $t_category"."_$id order by no");

// ī�װ� ����Ÿ ���� ����;;
	if($setup[use_category]) {
		$category_kind="<select name=category><option>Category</option>";

		while($category_data=mysql_fetch_array($category_result)) {
			if($data[category]==$category_data[no]) $category_kind.="<option value=$category_data[no] selected>$category_data[name]</option>";
			else $category_kind.="<option value=$category_data[no]>$category_data[name]</option>";
		}

		$category_kind.="</select>";
	}
  
	if($mode=="modify") $title = " �� �����ϱ� ";
	elseif($mode=="reply") $title = " ��� �ޱ� ";
	else $title = " �ű� �۾��� "; 

// ��Ű���� �̿�;;
	$name=$HTTP_SESSION_VARS["zb_writer_name"];
	$email=$HTTP_SESSION_VARS["zb_writer_email"];
	$homepage=$HTTP_SESSION_VARS["zb_writer_homepage"];

/******************************************************************************************
 * �۾��� ��忡 ���� ���� üũ
 *****************************************************************************************/

	if($mode=="modify") {

		// ��б��̰� �н����尡 Ʋ���� �����ڰ� �ƴϸ� ����
		if($data[is_secret]&&!$is_admin&&$data[ismember]!=$member[no]&&$HTTP_COOKIE_VARS[zb_s_check]!=$setup[no]."_".$no) error("�������� ������� �����ϼ���");

			$name=stripslashes($data[name]); // �̸�
			$email=stripslashes($data[email]); // ����
			$homepage=stripslashes($data[homepage]); // Ȩ������ 
			$subject=$data[subject]=stripslashes($data[subject]); // ����
			$subject=str_replace("\"","&quot;",$subject);
			$homepage=str_replace("\"","&quot;",$homepage);
			$name=str_replace("\"","&quot;",$name);
			$sitelink1=str_replace("\"","&quot;",$sitelink1);
			$sitelink2=str_replace("\"","&quot;",$sitelink2);
			$memo=stripslashes($data[memo]); // ����
			$sitelink1=$data[sitelink1]=stripslashes($data[sitelink1]);
			$sitelink2=$data[sitelink2]=stripslashes($data[sitelink2]);
			if($data[file_name1])$file_name1="<br>&nbsp;".$data[s_file_name1]."�� ��ϵǾ� �ֽ��ϴ�. <input type=checkbox name=del_file1 value=1> ����";
			if($data[file_name2])$file_name2="<br>&nbsp;".$data[s_file_name2]."�� ��ϵǾ� �ֽ��ϴ�. <input type=checkbox name=del_file2 value=1> ����";

			if($data[use_html]) $use_html=" checked ";

			if($data[reply_mail]) $reply_mail=" checked ";
			if($data[is_secret]) $secret=" checked ";
			if($data[headnum]<=-2000000000) $notice=" checked ";

		// ����϶� ����� ���� ����;;
		} elseif($mode=="reply") {

   			// ��б��̰� �н����尡 Ʋ���� �����ڰ� �ƴϸ� ����
			if($data[is_secret]&&!$is_admin&&$data[ismember]!=$member[no]&&$HTTP_COOKIE_VARS[zb_s_check]!=$setup[no]."_".$no) error("�������� ������� ����� �ټ���");

			if($data[is_secret]) $secret=" checked ";

			$subject=$data[subject]=stripslashes($data[subject]); // ����
			$subject=str_replace("\"","&quot;",$subject);
			$sitelink1=str_replace("\"","&quot;",$sitelink1);
			$sitelink2=str_replace("\"","&quot;",$sitelink2);
			$memo=stripslashes($data[memo]); // ����
			if(!eregi("\[re\]",$subject)) $subject="[re] ".$subject; // ����϶��� �տ� [re] ����;;
			$memo=str_replace("\n","\n>",$memo);
			$memo="\n\n>".$memo."\n";
			$title="$name���� �ۿ� ���� ��۾���";
		}


// ȸ���϶��� �⺻ �Է»��� �Ⱥ��̰�;;
	if($member[no]) { $hide_start="<!--"; $hide_end="-->"; }

// ����Ʈ ��ũ ����� ������ ��ũ ����� ǥ��;;
	if(!$setup[use_homelink]) { $hide_sitelink1_start="<!--";$hide_sitelink1_end="-->";}
	if(!$setup[use_filelink]) { $hide_sitelink2_start="<!--";$hide_sitelink2_end="-->";}

// �ڷ�� ����� ����ϴ��� ���ϴ��� ǥ��;;
	if(!$setup[use_pds]) { $hide_pds_start="<!--";$hide_pds_end="-->";}

// HTML��� üũ��ư 
	if($setup[use_html]==0) {
		if(!$is_admin&&$member[level]>$setup[grant_html]) { 
			$hide_html_start="<!--";
			$hide_html_end="-->"; 
		}
	}

// HTML ��� üũ�� Ȯ���Ŵ
	if($mode!="reply") {
		if(!$data[use_html]) $value_use_html = 1;
		else $value_use_html=$data[use_html];
	} else {
		$value_use_html=1;
	}
	$use_html .= " value='$value_use_html' onclick='check_use_html(this)'><ZeroBoard";


// ��б� ���;;
	if(!$setup[use_secret]) { $hide_secret_start="<!--"; $hide_secret_end="-->"; }

// ������� ����ϴ��� ���ϴ��� ǥ��;;
	if((!$is_admin&&$member[level]>$setup[grant_notice])||$mode=="reply") { $hide_notice_start="<!--";$hide_notice_end="-->"; }

// �ְ� ���ε� ���� �뷮
	if($setup[use_pds]) $upload_limit=GetFileSize($setup[max_upload_size]);

// �̹��� â�� ��ư
	if($member[no]&&$setup[grant_imagebox]>=$member[level]) $a_imagebox="<a onfocus=blur() href='javascript:showImageBox(\"$id\")'>"; else $a_imagebox="<Zeroboard ";
	if($mode=="modify"&&$data[ismember]!=$member[no]) $a_imagebox = "<Zeroboard";

// �̸����� ��ư
	$a_preview="<a onfocus=blur() href='javascript:view_preview()'>";


// HTML ��� 

	head(" onload=unlock() onunload=hideImageBox() ","script_write.php");

	include $dir."/write.php";

	foot();

	include "_foot.php";

?>
