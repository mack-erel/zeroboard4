<?php
  include "./_head.php";
  include "xmlrpc.inc.php";
  include "xmlrpcs.inc.php";

  function get_item($params) {

	$xmlrpc_value = $params->getParam(0);

	$member = $xmlrpc_value->structmem("dw_board_id");
	$dw_board_id = $member->scalarval();

    $query = "select * from zetyx_indexing where dw_board_id = '{$dw_board_id}'";
    $tmp = mysql_fetch_array(mysql_query($query));

    if(!$tmp['indexing_url']) exit();

    $indexing_url = $tmp['indexing_url'];
    $dw_url_id = $tmp['dw_url_id'];

    $max_crawling = $tmp['max_crawling'];
    $point        = $tmp['point'];
    $id           = $tmp['table_id'];
    $url = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$url = str_replace("/crawler.php","",$url);

    //$query = "select * from zetyx_board_{$id} where no > '{$point}' limit {$max_crawling}";
    $query = "select a.*,b.user_id from zetyx_board_{$id} as a left join zetyx_member_table as b on a.ismember = b.no where a.no > '{$point}' limit {$max_crawling}";

    $result = mysql_query($query);

	$i=0;	
    $arrayValue = array();
    while($tmp = mysql_fetch_array($result)) {
      $no = $tmp['no'];
      $is_secret = $tmp['is_secret'];
      if($is_secret) continue;

      $now = date("Ymd", $tmp['reg_date'])."T".date("H:i:s", $tmp['reg_date']);
      $content = htmlspecialchars($tmp['memo'],ENT_QUOTES);
      $structArray =  array("url" => new xmlrpcval( $url, "base64"),
                      "board_id" => new xmlrpcval($id, "base64" ),
                      "dw_board_id" => new xmlrpcval( $dw_board_id, "int" ),
                      "dw_url_id" => new xmlrpcval( $dw_url_id , "int" ),
                      "item_id" => new xmlrpcval( $tmp['no'], "int"),
                      "user_id" => new xmlrpcval( $tmp['user_id'], "base64" ),
                      "user_name" => new xmlrpcval( $tmp['name'], "base64" ),
                      "title" => new xmlrpcval( $tmp['subject'], "base64" ),
                      "content" => new xmlrpcval( $content, "base64" ),
                      "item_time" => new xmlrpcval( $now, "dateTime.iso8601") );
      $structValue = new xmlrpcval($structArray, "struct");
      $arrayValue[] =  $structValue;
		$i++;
    }
	if($i>0){
		$query = "update zetyx_indexing set point = '{$no}' where dw_board_id = '{$dw_board_id}'";
		mysql_query($query);
	}

    return new xmlrpcresp(new xmlrpcval($arrayValue, "array"));
  }

  $server = new xmlrpc_server( array( "zb.get_item" => array("function" => "get_item")));
?>
