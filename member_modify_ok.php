<?
// ���̺귯�� �Լ� ���� ��ũ���
	include "lib.php";

	if(getenv("REQUEST_METHOD") == 'GET' ) Error("���������� ���� ���ñ� �ٶ��ϴ�","");

// DB ����
	if(!$connect) $connect=dbConn();

// ��� ���� ���ؿ���;;; ����� ������
	$member=member_info();
	if(!$member[no]) Error("ȸ�������� �������� �ʽ��ϴ�");
	$group=group_info($member[group_no]);

	$name = str_replace("��","",$name);

	if(isblank($name)) Error("�̸��� �Է��ϼž� �մϴ�");
	if(eregi("<",$name)||eregi(">",$name)) Error("�̸����� �±׸� ����ϽǼ� �����ϴ�.");
	if($password&&$password1&&$password!=$password1) Error("��й�ȣ�� ��ġ���� �ʽ��ϴ�");
	$birth=mktime(0,0,0,$birth_2,$birth_3,$birth_1);

	$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where email='$email' and no <> ".$member[no],$connect));
	if($check[0]>0) Error("�̹� ��ϵǾ� �ִ� E-Mail�Դϴ�");


	$name = addslashes(del_html($name));
	$job = addslashes(del_html($job));
	$email = addslashes(del_html($email));
	if($_zbDefaultSetup[check_email]=="true"&&!mail_mx_check($email)) Error("�Է��Ͻ� $email �� �������� �ʴ� �����ּ��Դϴ�.<br>�ٽ� �ѹ� Ȯ���Ͽ� �ֽñ� �ٶ��ϴ�.");
	if(!eregi("http://",$homepage)&&$homepage) $homepage="http://$homepage";
	$homepage = addslashes(del_html($homepage));
	$birth = addslashes(del_html($birth));
	$hobby = addslashes(del_html($hobby));
	$icq = addslashes(del_html($icq));
	$msn = addslashes(del_html($msn));
	$home_address = addslashes(del_html($home_address));
	$home_tel = addslashes(del_html($home_tel));
	$office_address = addslashes(del_html($office_address));
	$office_tel = addslashes(del_html($office_tel));
	$handphone = addslashes(del_html($handphone));
	$comment = addslashes(del_html($comment));

	$que="update $member_table set name='$name'";
	if($password&&$password1&&$password==$password) $que.=" ,password=password('$password') ";
	if($birth_1&&$birth_2&&birth_3&&$group[use_birth]) $que.=",birth='$birth'";
	if($email) $que.=",email='$email'";
	$que.=",homepage='$homepage'";
	if($group[use_job]) $que.=",job='$job'";
	if($group[use_hobby]) $que.=",hobby='$hobby'";
	if($group[use_icq]) $que.=",icq='$icq'";
	if($group[use_aol]) $que.=",aol='$aol'";
	if($group[use_msn]) $que.=",msn='$msn'";
	if($group[use_home_address]) $que.=",home_address='$home_address'";
	if($group[use_home_tel]) $que.=",home_tel='$home_tel'";
	if($group[use_office_address]) $que.=",office_address='$office_address'";
	if($group[use_office_tel]) $que.=",office_tel='$office_tel'";
	if($group[use_handphone]) $que.=",handphone='$handphone'";
	if($group[use_mailing]) $que.=",mailing='$mailing'";
	$que.=",openinfo='$openinfo'";
	if($group[use_comment]) $que.=",comment='$comment'";
	$que.=",openinfo='$openinfo',open_email='$open_email',open_homepage='$open_homepage',open_icq='$open_icq',open_msn='$open_msn',open_comment='$open_comment',open_job='$open_job',open_hobby='$open_hobby',open_home_address='$open_home_address',open_home_tel='$open_home_tel',open_office_address='$open_office_address',open_office_tel='$open_office_tel',open_handphone='$open_handphone',open_birth='$open_birth',open_picture='$open_picture',open_aol='$open_aol' ";
	$que.=" where no='$member[no]'";

	@mysql_query($que) or Error("ȸ������ �����ÿ� ������ �߻��Ͽ����ϴ� ".mysql_error());

	if($del_picture) {
		@mysql_query("update $member_table set picture='' where no='$member[no]'") or Error("���� �ڷ� ���ε�� ������ �߻��Ͽ����ϴ�");
	}

    if($HTTP_POST_FILES[picture]) {
        $picture = $HTTP_POST_FILES[picture][tmp_name];
        $picture_name = $HTTP_POST_FILES[picture][name];
        $picture_type = $HTTP_POST_FILES[picture][type];
        $picture_size = $HTTP_POST_FILES[picture][size];
    }

	if($picture_name) {
		if(!is_uploaded_file($picture)) Error("�������� ������� ���ε� ���ּ���");
		if(!eregi(".gif\$",$picture_name)&&!eregi(".jpg\$",$picture_name)) Error("������ gif �Ǵ� jpg ������ �÷��ּ���");
		$size=GetImageSize($picture);
		if($size[0]>200||$size[1]>200) Error("������ ũ��� 200*200���Ͽ��� �մϴ�");
		$kind=array("","gif","jpg");
		$n=$size[2];
		$path="icon/member_".time().".".$kind[$n];
		if(!move_uploaded_file($picture,$path)) Error("���� ���ε尡 ����� ���� �ʾҽ��ϴ�");
		@mysql_query("update $member_table set picture='$path' where no='$member[no]'") or Error("���� �ڷ� ���ε�� ������ �߻��Ͽ����ϴ�");
	}

	mysql_close($connect);
?>
<script>
alert("ȸ������ ���� ������ ����� ó���Ǿ����ϴ�.");
opener.window.history.go(0);
window.close();
</script>
