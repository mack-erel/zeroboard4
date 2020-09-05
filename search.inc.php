<?
  class zSearch {

    var $methodName;
    var $arrVal;
    var $result;

    var $return_code = 0;
    var $item_url = "";
    var $dw_board_id = "";
    var $dw_url_id = "";

    var $host = "zbsearch1.dreamwiz.com";
    var $port = 80;
    var $url  = "/cgi-bin/zb_index.cgi";

    function zSearch($item_url = "") {
      if($item_url) {
        $pos = strpos($item_url, "/");
        $this->host = substr($item_url, 0, $pos);
        $this->url = substr($item_url, $pos, strlen($item_url));
      }
    }

    function addBoard($obj) {
      $this->methodName = "zb.index_start";

      $this->arrVal = array(
        "url" => new xmlrpcval($obj->url, "base64"),
        "board_id" => new xmlrpcval($obj->board_id, "base64"),
        "search_enable_url" => new xmlrpcval($obj->search_enable_url, "base64"),
        "crawling_url" => new xmlrpcval($obj->crawling_url, "base64"),
        "total_item" => new xmlrpcval($obj->total_item, "int"),
        "max_crawling" => new xmlrpcval($obj->max_crawling, "int"),
        "crawling_stime" => new xmlrpcval($obj->crawling_stime, "int"),
        "crawling_etime" => new xmlrpcval($obj->crawling_etime, "int"),
      );

      $this->_call();

      if(!$this->_checkResult()) return false;
      else return htmlentities($this->result->serialize());
    }

    function delBoard($obj) {
      $this->methodName = "zb.index_stop"; 

      $this->arrVal = array(
        "url" => new xmlrpcval($obj->url, "base64"),
        "board_id" => new xmlrpcval($obj->board_id, "base64"),
      );

      $this->_call();

      return $this->_checkResult();
    }

    function addItem($obj) {
      $this->methodName = "zb.item_add";

      $obj->title = strip_tags($obj->title);
      $obj->content = htmlspecialchars($obj->content);
      $obj->item_time = date("Ymd")."T".date("H:i:s");

      $this->arrVal = array(
        "url" => new xmlrpcval($obj->url, "base64"),
        "board_id" => new xmlrpcval($obj->board_id, "base64"),
        "dw_board_id" => new xmlrpcval($obj->dw_board_id, "int"),
        "dw_url_id" => new xmlrpcval($obj->dw_url_id, "int"),
        "item_id" => new xmlrpcval($obj->item_id, "int"),
        "user_id" => new xmlrpcval($obj->user_id, "base64"),
        "user_name" => new xmlrpcval($obj->user_name, "base64"),
        "title" => new xmlrpcval($obj->title, "base64"),
        "content" => new xmlrpcval($obj->content, "base64"),
        "item_time" => new xmlrpcval($obj->item_time, "dateTime.iso8601"),
      );

      $this->_call();

      return $this->_checkResult();
    }

    function delItem($obj) {
      $this->methodName = "zb.item_del";

      $this->arrVal = array(
        "url" => new xmlrpcval($obj->url, "base64"),
        "board_id" => new xmlrpcval($obj->board_id, "base64"),
        "dw_board_id" => new xmlrpcval($obj->dw_board_id, "int"),
        "item_id" => new xmlrpcval($obj->item_id, "int"),
        "user_id" => new xmlrpcval($obj->user_id, "base64"),
      );

      $this->_call();

      return $this->_checkResult();
    }

    function modifyItem($obj) {

      $this->methodName = "zb.item_mod";

      $obj->title = strip_tags($obj->title);
      $obj->content = htmlspecialchars($obj->content);
      $obj->item_time = date("Ymd")."T".date("H:i:s");

      $this->arrVal = array(
        "url" => new xmlrpcval($obj->url, "base64"),
        "board_id" => new xmlrpcval($obj->board_id, "base64"),
        "dw_board_id" => new xmlrpcval($obj->dw_board_id, "int"),
        "item_id" => new xmlrpcval($obj->item_id, "int"),
        "user_id" => new xmlrpcval($obj->user_id, "base64"),
        "user_name" => new xmlrpcval($obj->user_name, "base64"),
        "title" => new xmlrpcval($obj->title, "base64"),
        "content" => new xmlrpcval($obj->content, "base64"),
        "item_time" => new xmlrpcval($obj->item_time, "dateTime.iso8601"),
      );

      $this->_call();

      return $this->_checkResult();
    }

    function _call() {
      $server = new xmlrpc_client($this->url, $this->host, $this->port);

      $message = new xmlrpcmsg($this->methodName);

      $param = new xmlrpcval($this->arrVal, "struct");

      $message->addParam($param);

      $this->result  = $server->send($message);

      $this->return_code = $this->result->val->me['struct']['return_code']->me['i4'];
      $this->item_url    = $this->result->val->me['struct']['item_url']->me['string'];
      $this->dw_board_id = $this->result->val->me['struct']['dw_board_id']->me['i4'];
      $this->dw_url_id   = $this->result->val->me['struct']['dw_url_id']->me['i4'];
    }

    function _checkResult() {
      if(!$this->result || $this->result->faultCode()) return false;
      return true;
    }

  }
?>
