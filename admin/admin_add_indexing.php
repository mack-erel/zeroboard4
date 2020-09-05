<?php

  $_zb_path="../";

  include_once "../lib.php";

  $connect=dbconn();

  $member=member_info();

  if(!$member[no]||$member[is_admin]>1||$member[level]>1) Error("최고 관리자만이 사용할수 있습니다");

  if($exec=="add" && $table_id) {
      $table_id = addslashes($table_id);
      $max_crawling = (int)$max_crawling;
      if(!$max_crawling) $max_crawling = 100;

      $query = "select count(*) from zetyx_admin_table where name = '{$table_id}' and grant_view = 10 and grant_list = 10";
      $tmp = mysql_fetch_array(mysql_query($query));
      if(!$tmp[0]) $errMsg = "잘못된 게시판 지정입니다";

      if(!$errMsg) {

        include_once "../xmlrpc.inc.php";
        include_once "../search.inc.php";

        $query = "select count(*) from zetyx_board_{$table_id}";
        $tmp = mysql_fetch_array(mysql_query($query));
        $total_item = $tmp[0];

        $query = "select max(no) as stop_point from zetyx_board_{$table_id}";
        $tmp = mysql_fetch_array(mysql_query($query));
        $stop_point = $tmp[0];

        $oSearch = new zSearch();

        $url = $_SERVER['HTTP_HOST'].str_replace("/admin/admin_add_indexing.php","",$PHP_SELF);

        $obj->url = $url;
        $obj->board_id = $table_id;
        $obj->total_item = $total_item;
        $obj->search_enable_url = $url."/se.php";
        $obj->crawling_url = $url."/crawler.php";
        $obj->max_crawling = $max_crawling;
        $obj->crawling_stime = $reserve_stime;
        $obj->crawling_etime = $reserve_etime;

        $oSearch->addBoard($obj);

        if(!$oSearch->return_code) $errMsg = "검색서버와 연결을 하지 못했습니다";
        else {
          $indexing_url = $oSearch->item_url;
          $dw_board_id = $oSearch->dw_board_id;
          $dw_url_id = $oSearch->dw_url_id;
        }
      }
      

      if(!$errMsg && $dw_url_id && $dw_board_id) {
        // db insert
        $query = "
          insert into zetyx_indexing (
            table_id,
            max_crawling,
            reserve_stime,
            reserve_etime,
            stop_point,
            point,
            indexing_url,
            dw_url_id,
            dw_board_id
          ) values ( 
            '{$table_id}',
            '{$max_crawling}',
            '{$reserve_stime}',
            '{$reserve_etime}',
            '{$stop_point}',
            0,
            '{$indexing_url}',
            '{$dw_url_id}',
            '{$dw_board_id}'
          )
        ";
        mysql_query($query);
      }

      if($errMsg) {
        ?>
          <script>
            alert("<?=$errMsg?>");
            history.back();
          </script>
        <?php
      } else {
        ?>
          <script>
            try {
              opener.history.go(0);
            } catch(e) {}
            self.close();
          </script>
        <?php
      }

    exit();
  }

  $result = mysql_query("select name from zetyx_admin_table where grant_view = 10 and grant_list = 10 order by name");
  while($tmp = mysql_fetch_array($result)) {
    $table_list[] = $tmp['name'];
  }

  $result = mysql_query("select table_id from zetyx_indexing");
  while($tmp = mysql_fetch_array($result)) {
      $indexing_list[] = $tmp['table_id'];
  }

  head();
?>

<script>
  function checkSubject(obj) {
    var boardName = obj.table_id.value; 
    var maxCrawling = parseInt(obj.max_crawling.value);

    if(!maxCrawling) {
      alert("Idexing 단위가 잘못되었습니다");
      obj.max_crawling.focus();
      return false;
    }

    return confirm( boardName+" 게시판을 검색서버에 추가하시겠습니까?" );
  }
</script>

<form style='display:inline' onSubmit="return checkSubject(this)" method=post action="./admin_add_indexing.php">
<input type=hidden name=exec value=add>

<table border=0 cellspacing=0 cellpadding=10 bgcolor=999999 width=100% height=100%>
<tr>
  <td valign=top>
	<table border=0 width=100%>
	<col width=50%></col><col width=50%></col>
	<tr>
		<td nowrap><font color=white size=4 face=tahoma><b>Zeroboard & DreamWiz 검색서버 추가</b></font></td>
	</tr>
	</table>
	<br>

    <table border=0 cellspacing=1 cellpadding=0 width=98% bgcolor=#000000>
    <tr>
      <td bgcolor=#FFFFFF>
        <table border=0 width=100%>
        <col width=120 align=right style='padding-right:10px;color:#AAAAAA;font-weight:bold'></col>
        <col></col>
        <tr>
          <td>게시판</td>
          <td><select name=table_id>
    <?php
      for($i=0;$i<count($table_list);$i++) {
        $board_id = $table_list[$i];
        if(in_array($board_id, $indexing_list)) continue;
        print "<option value='{$board_id}'>{$board_id}</option>";
      }
    ?>
          </select></td>
        </tr>
        <tr>
          <td>Indexing 단위</td>
          <td><input type=text name=max_crawling value="100" maxlength=4 size=4> 개</td>
        </tr>
        <tr>
          <td>예약 시작 시간</td>
          <td><select name=reserve_stime>
    <?php
      for($i=0;$i<=24;$i++) print "<option value={$i} ".($i==0?"selected":"").">".sprintf("%02d",$i)."</option>";
    ?>
          </select> 시</td>
        </tr>
        <tr>
          <td>예약 종료 시간</td>
          <td><select name=reserve_etime>
    <?php
      for($i=0;$i<=24;$i++) print "<option value={$i} ".($i==0?"selected":"").">".sprintf("%02d",$i)."</option>";
    ?>
          </select> 시</td>
        </tr>
        <tr>
          <td colspan=2>
            <input type=submit value='검색 서버에 추가' style="width:100%;border-color:#b0b0b0;background-color:#3d3d3d;color:#ffffff;font-size:8pt;font-family:Tahoma;height:20px;">
          </td>
        </tr>
        </table>
      </td>
    </tr>
    </table>

  </td>
</tr>
</table>

</form>

</body>
</html>
