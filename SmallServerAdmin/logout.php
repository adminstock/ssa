<?php
  require_once 'global.php';
  global $config;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
	<link rel="stylesheet" href="/Content/compiled.min.css?v=<?=filemtime(\Nemiro\Server::MapPath('~/Content/compiled.min.css'))?>" />
</head>
<body>

	<div style="position:absolute;top:50%;left:50%;text-align:center;width:150px;height:150px;margin-top:-150px;margin-left:-75px;">
		<span class="fa fa-cog fa-spin" aria-hidden="true" style="font-size:128px;"></span>
	</div>

	<input id="logout_redirect" type="hidden" value="<?=(isset($config['logout_redirect']) ? $config['logout_redirect'] : '')?>" />
	<script src="/Content/compiled.js?v=<?=filemtime(\Nemiro\Server::MapPath('~/Content/compiled.js'))?>" type="text/javascript"></script>

	<script type="text/javascript">
	  function LogoutRedirect() {
	    if ($('#logout_redirect').val() != '') {
	      window.location.href = $('#logout_redirect').val();
	    } else {
	      window.location.href = '/';
	    }
	  }

	  if (bowser.msie) {
	    document.execCommand('ClearAuthenticationCache', 'false');
	    window.setTimeout(LogoutRedirect, 250);
	  /*} else if (bowser.webkit) {
	    var xmlhttp = new XMLHttpRequest();
	    xmlhttp.open('GET', '/logout.php', true);
	    xmlhttp.setRequestHeader('Authorization', 'Basic logout');
	    xmlhttp.send();
      window.setTimeout(LogoutRedirect, 250);*/
	  } else {
	    $.ajax({
	      async: false,
	      url: '/logout.php',
	      type: 'GET',
	      username: 'logout',
	      complete: function(){
	        LogoutRedirect();
	      }
	    });
	  }

	  // window.location.href = '//log:out@'.$_SERVER['HTTP_HOST'];
	</script>
</body>
</html>