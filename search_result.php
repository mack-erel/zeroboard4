<?
  $isSearchPage = true;
  include "./_head.php";

  $sectopt = 0;
  $search_opt = "";
  if($_GET['ss']=="on") {
    $search_opt[] = "제목";
    $sectopt += 1;
  }
  if($_GET['sc']=="on") {
    $search_opt[] = "내용";
    $sectopt += 2;
  }
  if($_GET['sn']=="on") {
    $search_opt[] = "이름";
    $sectopt += 4;
  }

  //$f = fsockopen("zbsearch1.dreamwiz.com", 80);
  $f = fsockopen("220.73.212.138", 80);
  if(!$start) $start = 0;
  if(!isset($sortopt)) $sortopt = 1;
  fputs($f, "GET /cgi-bin/zb.cgi?rt=1&q=".urlencode($keyword)."&ru=0&rb={$dw_board_id}&sortopt={$sortopt}&st={$start}&sectopt={$sectopt}&sn={$sn}&sc={$sc}&ss={$ss} HTTP/1.0\r\n");
  fputs($f, "Host: zbsearch1.dreamwiz.com\r\n\r\n");
  $begin = false;
  unset($str);
  unset($result);
  while(!feof($f)){
    $str = fgets($f, 1024);
    if($begin) $result .= $str;
    if(!trim($str)) $begin = true;
  }
  fclose($f);

  $result = str_replace(array("&amp;nbsp;","&amp;lt;","&amp;gt;","\\\\","\\&quot;","\&#039;"), array(" ","&lt;","&gt;","","\"","'"), $result);

  head();
?>
<script>
  function result_move_page(start) {
    location.href="./search_result.php?dw_board_id=<?=$dw_board_id?>&dw_url_id=<?=$dw_url_id?>&id=<?=$id?>&keyword=<?=urlencode($keyword)?>&sortopt=<?=$sortopt?>&sn=<?=$sn?>&ss=<?=$ss?>&sc=<?=$sc?>&start="+start;
  }
  function result_move_next(start) {
    result_move_page(start);
  }
  function result_move_prev(start) {
    result_move_page(start);
  }
  function result_move_by_sort(order) {
    location.href="./search_result.php?dw_board_id=<?=$dw_board_id?>&dw_url_id=<?=$dw_url_id?>&id=<?=$id?>&keyword=<?=urlencode($keyword)?>&sn=<?=$sn?>&ss=<?=$ss?>&sc=<?=$sc?>&sortopt="+order;
  }
</script>
<link href=search_result.css rel=STYLESHEET title="text spacing" type=text/css>

<div class="ResultLayout">

<table border=0 width=100%>
<tr>
  <td>
    <form style="display:inline" method="get" action="./search_result.php">
      <font class="SearchOpt">
      <input type="hidden" name="dw_board_id" value="<?=$dw_board_id?>">
      <input type="hidden" name="dw_url_id" value="<?=$dw_url_id?>">
      <input type="hidden" name="id" value="<?=$id?>">
      <input type="hidden" name="sortopt" value="<?=$sortopt?>">
      <input type="checkbox" name="sn" value="on" <?=$sn=="on"?"checked":""?>> 이름
      <input type="checkbox" name="ss" value="on" <?=$ss=="on"?"checked":""?>> 제목
      <input type="checkbox" name="sc" value="on" <?=$sc=="on"?"checked":""?>> 내용
      &nbsp; &nbsp;
      <input type="text" name="keyword" value="<?=$keyword?>" class="SearchResultKeywordBox"><input type="submit" value="검색" class="SearchBtn">
      &nbsp; &nbsp;
      <input type="radio" name="sortopt" value="0" <?=!$sortopt?"checked":""?> onClick="result_move_by_sort(0)"> 정확도
      <input type="radio" name="sortopt" value="1" <?=$sortopt==1?"checked":""?> onClick="result_move_by_sort(1)"> 날짜순
      </font>
    </form>

  </td>
  <td align="right">
    <font class="BackBtn"><a href="./zboard.php?id=<?=$id?>">[게시판으로 돌아가기]</a></font>
  </td>
</tr>
</table>
<br>
<br>
<?
  $tmp_result = str_replace(array(" ","\n"),"",strip_tags($result));
  if(!$tmp_result) {
?>
  <div align="center" class="NoResult">
  입력하신 "<font class="bold"><?=$keyword?></font>" 에 대한 검색 결과가 없습니다.<br>
  검색어의 철자가 틀렸는지 또는 검색 범위가 잘못되었는지를 확인해 주세요.
  </div>
<?
  } else {
    print $result;
  }
?>

</div>

<?
  foot()
?>
