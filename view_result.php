<?php
  include "_head.php";

  if($setup['use_alllist']) $ret_url = "./zboard.php?id={$id}&no={$no}";
  else $ret_url = "./view.php?id={$id}&no={$no}";
?>
<script type='text/javascript'>
  location.replace("<?=$ret_url?>");
</script>
