<div class="collapse navbar-collapse panel-nav">
	<nav class="navmenu navmenu-default" role="navigation">
		<ul class="nav navmenu-nav">
			<?php
      global $config;
      $modules = explode(',', $config['modules']);
      foreach ($modules as $module) {
        if (is_file(\Nemiro\Server::MapPath('~/'.trim($module).'/menu.php')))
        {
          include \Nemiro\Server::MapPath('~/'.trim($module).'/menu.php');
        }
        else
        {
          echo '<li><a href="/'.$module.'"><span class="glyphicon glyphicon-th-large"></span> '.ucfirst(trim($module)).'</a></li>';
        }
      }
			?>
			<li class="nav-divider"></li>
			<?php
        if (stripos($this->Parent->Culture, 'ru') !== FALSE)
        {
			?>
			<li>
				<a href="http://www.reg.ru/?rid=76963">
					Регистрация доменов
				</a>
			</li>
			<li>
				<a href="https://www.ihor.ru/?from=112887">
					VDS хостинг
				</a>
			</li>
			<li>
				<a href="http://vk.com/board120230803" rel="noreferrer">Техническая поддержка</a>
			</li>
			<?php
        } else {
			?>
			<li>
				<a href="http://www.reg.com/?rid=76963">Domain registration</a>
			</li>
			<li>
				<a href="https://en.ihor.ru/vds?from=112887">VDS hosting</a>
			</li>
			<?php
        }
			?>
			<li>
				<a href="https://github.com/adminstock">@adminstock</a>
			</li>
		</ul>

		<div class="hidden-xs">
			<?php
        if (stripos($this->Parent->Culture, 'ru') !== FALSE)
        {
			?>
			  <script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>
			  <!-- VK Widget -->
			  <div id="vk_groups"></div>
			  <script type="text/javascript">
          function VKWidget_Init(){
            $('#vk_groups').html('');
            VK.Widgets.Group('vk_groups', { mode: 2, width: $('#vk_groups').width(), height: '400', color1: 'f8f8f8', color2: '777777', color3: '777777' }, 120230803);
          };
          window.addEventListener('load', VKWidget_Init, false);
          window.addEventListener('resize', VKWidget_Init, false);
			    //VK.Widgets.Group("vk_groups", { mode: 2, width: "auto", height: "400" }, 120230803);
			  </script>
			  <!--a class="twitter-timeline" href="https://twitter.com/AdmStockRussia" data-widget-id="725319651723513856" data-tweet-limit="1" data-chrome="nofooter noscrollbar noborders transparent">Новости AdminStock (Russia)</a>
			  <script>!function (d, s, id) { var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https'; if (!d.getElementById(id)) { js = d.createElement(s); js.id = id; js.src = p + "://platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs); } }(document, "script", "twitter-wjs");</script-->
			<?php
        } else {
			?>
			  <div id="fb-root"></div>
			  <script>
          (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId=201442196917562";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
        </script>
  			<div class="fb-page" data-href="https://www.facebook.com/adminstock.net/" data-tabs="timeline" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/adminstock.net/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/adminstock.net/">AdminStock</a></blockquote></div>
			  <!--hr />
			  <a class="twitter-timeline" href="https://twitter.com/AdmStockNet" data-widget-id="725326952903614464" data-tweet-limit="1" data-chrome="nofooter noscrollbar noborders transparent">News of AdminStock</a>
			  <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script-->
			<?php
        }
			?>
		</div>
	</nav>
</div>