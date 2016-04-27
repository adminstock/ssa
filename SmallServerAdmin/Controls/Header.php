<?php global $config; ?>
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a href="/" class="navbar-brand">
        <span class="fa fa-server"></span> <?=$config['ssh_host']?>
      </a>
      <span class="navbar-brand hidden-xs">/</span>
      <span class="navbar-brand hidden-xs">
        <?=$this->Parent->Title?>
      </span>
    </div>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="?lang=ru"><img src="/Content/images/ru.png" alt="RU" title="" width="22" height="22" /></a></li>
			<li><a href="?lang=en"><img src="/Content/images/en.png" alt="EN" title="" width="22" height="22" /></a></li>
		</ul>
  </div>
</nav>