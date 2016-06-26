<?php global $config; ?>
<nav class="navbar navbar-default">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".panel-nav">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" ng-click="SelectServer()" title="${All servers}"><span class="fa fa-server"></span></a>
			<a href="/" class="navbar-brand" title="${Dashboard}: <?=(isset($config['server_name']) && $config['server_name'] != '' ? $config['server_name'].' ('.$config['ssh_host'].')' : $config['ssh_host'])?>">
				<?=(isset($config['server_name']) && $config['server_name'] != '' ? $config['server_name'] : $config['ssh_host'])?>
			</a>
			<span class="navbar-brand hidden-xs">/</span>
			<span class="navbar-brand hidden-xs">
				<?=$this->Parent->Title?>
			</span>
		</div>
		<ul class="nav navbar-nav navbar-right collapse navbar-collapse panel-nav lang">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<?php
          if(file_exists(\Nemiro\Server::MapPath('~/Content/images/'.substr($this->Parent->Culture, 0, 2).'.png')))
          {
					?>
					<img src="/Content/images/<?=substr($this->Parent->Culture, 0, 2)?>.png" alt="<?=$this->Parent->Culture?>" title="" width="22" height="22" />
					<?php
          }else {
					?>
					<img src="/Content/images/globe.png" alt="" title="" width="22" height="22" />
					<?php } ?>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="?lang=en"><img src="/Content/images/en.png" alt="EN" title="" width="22" height="22" /></a></li>
					<li><a href="?lang=ru"><img src="/Content/images/ru.png" alt="RU" title="" width="22" height="22" /></a></li>
					<li><a href="?lang=de"><img src="/Content/images/de.png" alt="DE" title="" width="22" height="22" /></a></li>
				</ul>
			</li>
			<?php
      if (isset($_SERVER['PHP_AUTH_USER']))
      {
			?>
			<li><a href="/logout.php"><span class="glyphicon glyphicon-log-out"></span> ${Logout}</a></li>
			<?php
      }
			?>
		</ul>
	</div>
</nav>