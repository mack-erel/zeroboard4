<?php
  if(!isTable("zetyx_indexing")) {
    $query = "
      create table zetyx_indexing (
         no int(10) not null auto_increment primary key ,
         table_id char(40) not null,
         max_crawling int(10) not null default '1000',
         reserve_stime int(2) null,
         reserve_etime int(2) null,
         stop_point int(10) not null default '0',
         point int(10) not null default '0',
         indexing_url varchar(255) not null,
         dw_url_id int(10) not null,
         dw_board_id int(10) not null
      )
    ";
    mysql_query($query);
  }

  $result = mysql_query("select * from zetyx_indexing order by table_id");
  while($tmp = mysql_fetch_array($result)) {
    $indexing_list[] = $tmp;
  }
?>
<table border=0 cellspacing=0 cellpadding=10 bgcolor=999999 width=100% height=100%>
<form name=showdb>
<tr>
	<td valign=top>
	<br>
	<table border=0 width=100%>
	<col width=50%></col><col width=50%></col>
	<tr>
		<td nowrap><font color=white size=4 face=tahoma><b>Zeroboard & DreamWiz 검색서버 관리</b></font></td>
	</tr>
	</table>
	<br>

	<table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=999999>
    <col width=40></col>
    <col width=*></col>
    <col width=90></col>
    <col width=85></col>
    <col width=80></col>
    <col width=40></col>
	<tr bgcolor=444444 align=center>
		<td style=color:white;font-size:8pt;font-family:tahoma>No</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>게시판 ID</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>검색률</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>indexing 단위</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>예약 시간</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>삭제</td>
	</tr>
<?php
  for($i=0;$i<count($indexing_list);$i++) {
?>
  <tr bgcolor=#FFFFFF align=center height=25>
    <td><?=($i+1)?></td>
    <td><?=$indexing_list[$i]['table_id']?></td>
    <td><?=sprintf("%0.2f",$indexing_list[$i]['point']/$indexing_list[$i]['stop_point']*100)?>%</td>
    <td><?=$indexing_list[$i]['max_crawling']?>개</td>
    <td>
      <?php if(!$indexing_list[$i]['reserve_stime']&&!$indexing_list[$i]['reserve_etime']){?>
      없음
      <?php }else{?>
      <?=sprintf("%02d",$indexing_list[$i]['reserve_stime'])?>시 ~
      <?=sprintf("%02d",$indexing_list[$i]['reserve_etime'])?>시
      <?php }?>
    </td>
    <td><a href="javascript:void(window.open('./admin/admin_del_indexing.php?no=<?=$indexing_list[$i]['no']?>','_DelBoard','width=400,height=200,noresizable,noscroll'))" onClick="return confirm('검색서버에서 삭제하시겠습니까?')">삭제</a></td>
  </tr>
<?php
  }
?>
	</table>

    <div align=right>
      <input type=button value='게시판 추가' style=border-color:#b0b0b0;background-color:#3d3d3d;color:#ffffff;font-size:8pt;font-family:Tahoma;height:20px; onClick="window.open('./admin/admin_add_indexing.php','_AddIndexBoard','width=500,height=200,noresizable,noscroll')">
    </div>

	</td>
</tr>
</form>
</table>
