<?
// ���̺귯�� �Լ� ���� ��ũ���
	include "lib.php";

	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("���������� �ۼ��Ͽ� �ֽñ� �ٶ��ϴ�.");
	if(!eregi("member_join.php",$HTTP_REFERER)) Error("���������� �ۼ��Ͽ� �ֽñ� �ٶ��ϴ�","");
	if(getenv("REQUEST_METHOD") == 'GET' ) Error("���������� ���� ���ñ� �ٶ��ϴ�","");

// DB ����
	if(!$connect) $connect=dbConn();

// ��� ���� ���ؿ���;;; ����� ������
	$member=member_info();
	if($mode=="admin"&&($member[is_admin]==1||($member[is_admin]==2&&$member[group_no]==$group_no))) $mode = "admin";
	else $mode = "";

	if($member[no]&&!$mode) Error("�̹� ������ �Ǿ� �ֽ��ϴ�.","window.close");


// ���� �Խ��� ���� �о� ����
	if($id) {
		$setup=get_table_attrib($id);

		// �������� ���� �Խ����϶� ���� ǥ��
		if(!$setup[name]) Error("�������� ���� �Խ����Դϴ�.<br><br>�Խ����� ������ ����Ͻʽÿ�");

		// ���� �Խ����� �׷��� ���� �о� ����
		$group_data=group_info($setup[group_no]);
		if(!$group_data[use_join]&&!$mode) Error("���� ������ �׷��� �߰� ȸ���� �������� �ʽ��ϴ�");

	} else {

		if(!$group_no) Error("ȸ���׷��� �����ּž� �մϴ�");
		$group_data=mysql_fetch_array(mysql_query("select * from $group_table where no='$group_no'"));
		if(!$group_data[no]) Error("������ �׷��� �������� �ʽ��ϴ�");
		if(!$group_data[use_join]&&!$mode) Error("���� ������ �׷��� �߰� ȸ���� �������� �ʽ��ϴ�");
	}


// ���ڿ������� �˻�
	$user_id = str_replace("��","",$user_id);
	$name = str_replace("��","",$name);

        if(!get_magic_quotes_gpc()) {
          $user_id = addslashes($user_id);
          $password = addslashes($password);
        }
	
	$user_id=trim($user_id);
	if(isBlank($user_id)) Error("ID�� �Է��ϼž� �մϴ�","");

	$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where user_id='$user_id'",$connect));
	if($check[0]>0) Error("�̹� ��ϵǾ� �ִ� ID�Դϴ�","");

	unset($check);
	$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where email='$email'",$connect));
	if($check[0]>0) Error("�̹� ��ϵǾ� �ִ� E-Mail�Դϴ�","");

	if(isBlank($password)) Error("��й�ȣ�� �Է��ϼž� �մϴ�","");

	if(isBlank($password1)) Error("��й�ȣ Ȯ���� �Է��ϼž� �մϴ�","");

	if($password!=$password1) Error("��й�ȣ�� ��й�ȣ Ȯ���� ��ġ���� �ʽ��ϴ�","");

	if(isBlank($name)) Error("�̸��� �Է��ϼž� �մϴ�","");
	if(eregi("<",$name)||eregi(">",$name)) Error("�̸��� ����, �ѱ�, ���ڵ����� �Է��Ͽ� �ֽʽÿ�");

	if($group_data[use_jumin]&&!$mode) {

		// �ֹε�� ��ȣ ��ƾ
		if(isBlank($jumin1)||isBlank($jumin2)||strlen($jumin1)!=6||strlen($jumin2)!=7) Error("�ֹε�Ϲ�ȣ�� �ùٸ��� �Է��Ͽ� �ֽʽÿ�","");

		if(!check_jumin($jumin1.$jumin2)) Error("�߸��� �ֹε�Ϲ�ȣ�Դϴ�","");

		$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where jumin=password('".$jumin1.$jumin2."')",$connect));
		if($check[0]>0) Error("�̹� ��ϵǾ� �ִ� �ֹε�Ϲ�ȣ�Դϴ�","");
		$jumin=$jumin1.$jumin2;
	}


	$name=addslashes($name);
	$email=addslashes($email);
	if($_zbDefaultSetup[check_email]=="true"&&!mail_mx_check($email)) Error("�Է��Ͻ� $email �� �������� �ʴ� �����ּ��Դϴ�.<br>�ٽ� �ѹ� Ȯ���Ͽ� �ֽñ� �ٶ��ϴ�.");
	$home_address=addslashes($home_address);
	$home_tel=addslashes($home_tel);
	$office_address=addslashes($office_address);
	$office_tel=addslashes($office_tel);
	$handphone=addslashes($handphone);
	$comment=addslashes($comment);
	$birth=mktime(0,0,0,$birth_2,$birth_3,$birth_1);
	if(!eregi("http://",$homepage)&&$homepage) $homepage="http://$homepage";
	$reg_date=time();
	$job = addslashes($job);
	$homepage = addslashes($homepage);
	$birth = addslashes($birth);
	$hobby = addslashes($hobby);
	$icq = addslashes($icq);
	$msn = addslashes($msn);

	if($HTTP_POST_FILES[picture]) {
		$picture = $HTTP_POST_FILES[picture][tmp_name];
		$picture_name = $HTTP_POST_FILES[picture][name];
		$picture_type = $HTTP_POST_FILES[picture][type];
		$picture_size = $HTTP_POST_FILES[picture][size];
	}

	if($picture_name) {
		if(!is_uploaded_file($picture)) Error("�������� ������� ���ε� ���ּ���");
		if(!eregi(".gif",$picture_name)&&!eregi(".jpg",$picture_name)) Error("������ gif �Ǵ� jpg ������ �÷��ּ���");
		$size=GetImageSize($picture);
		//if($size[0]>200||$size[1]>200) Error("������ ũ��� 200*200���Ͽ��� �մϴ�");
		$kind=array("","gif","jpg");
		$n=$size[2];
		$path="icon/member_".time().".".$kind[$n];
		if(!@move_uploaded_file($picture,$path)) Error("���� ���ε尡 ����� ���� �ʾҽ��ϴ�");
		$picture_name=$path;
	}


	mysql_query("insert into $member_table (level,group_no,user_id,password,name,email,homepage,icq,aol,msn,jumin,comment,job,hobby,home_address,home_tel,office_address,office_tel,handphone,mailing,birth,reg_date,openinfo,open_email,open_homepage,open_icq,open_msn,open_comment,open_job,open_hobby,open_home_address,open_home_tel,open_office_address,open_office_tel,open_handphone,open_birth,open_picture,picture,open_aol) values ('$group_data[join_level]','$group_data[no]','$user_id',password('$password'),'$name','$email','$homepage','$icq','$aol','$msn',password('$jumin'),'$comment','$job','$hobby','$home_address','$home_tel','$office_address','$office_tel','$handphone','$mailing','$birth','$reg_date','$openinfo','$open_email','$open_homepage','$open_icq','$open_msn','$open_comment','$open_job','$open_hobby','$open_home_address','$open_home_tel','$open_office_address','$open_office_tel','$open_handphone','$open_birth','$open_picture','$picture_name','$open_aol')") or error("ȸ�� ����Ÿ �Է½� ������ �߻��߽��ϴ�<br>".mysql_error());
	mysql_query("update $group_table set member_num=member_num+1 where no='$group_data[no]'");

	if(!$mode) {
		$member_data=mysql_fetch_array(mysql_query("select * from $member_table where user_id='$user_id' and password=password('$password')"));

		// 4.0x �� ���� ó��
		$zb_logged_no = $member_data[no];
		$zb_logged_time = time();
		$zb_logged_ip = $REMOTE_ADDR;
		$zb_last_connect_check = '0';

		session_register("zb_logged_no");
		session_register("zb_logged_time");
		session_register("zb_logged_ip");
		session_register("zb_last_connect_check");
	}


	mysql_close($connect);
?>

<script>
	alert("ȸ�������� ���������� ó�� �Ǿ����ϴ�\n\nȸ���� �ǽŰ��� �������� ���ϵ帳�ϴ�.");
	opener.window.history.go(0);
	window.close();
</script>
