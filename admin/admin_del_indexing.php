<?
  $_zb_path="../";

  include "../lib.php";
  include "../xmlrpc.inc.php";
  include "../search.inc.php";

  $connect=dbconn();

  $member=member_info();

  if(!$member[no]||$member[is_admin]>1||$member[level]>1) Error("�ְ� �����ڸ��� ����Ҽ� �ֽ��ϴ�");

  if($no) {

    $query = "select * from zetyx_indexing where no = '{$no}'";
    $data = mysql_fetch_array(mysql_query($query));

    $oSearch = new zSearch();
    $url = $_SERVER['HTTP_HOST'].str_replace("/admin/admin_del_indexing.php","",$PHP_SELF);
    $obj->url = $url;
    $obj->board_id = $data['table_id'];
    $oSearch->delBoard($obj);
    if($oSearch->return_code!=0) {
?>
<script>
  alert("�˻��������� ������ �����Ͽ����ϴ�");
  self.close();
</script>
<?
    } else {
      $query = "delete from zetyx_indexing where no = '{$no}'";
      mysql_query($query);
?>
<script>
  try{
    opener.history.go(0);
  } catch(e){}
  self.close();
</script>
<?
    }
  }
?>
