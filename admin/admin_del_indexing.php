<?php
  $_zb_path="../";

  include_once "../lib.php";
  include_once "../xmlrpc.inc.php";
  include_once "../search.inc.php";

  $connect=dbconn();

  $member=member_info();

  if(!$member[no]||$member[is_admin]>1||$member[level]>1) Error("최고 관리자만이 사용할수 있습니다");

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
  alert("검색서버와의 연결을 실패하였습니다");
  self.close();
</script>
<?php
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
<?php
    }
  }
?>
