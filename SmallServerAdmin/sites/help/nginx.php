<pre>
server {
  root /home/example/www;
  server_name example.org;

  location / {
    index index.php index.html index.htm;
    # ...
  }
}
</pre>

<h3>PHP-FPM</h3>
<pre>
location ~ \.php\$ {
  # set socket path
  fastcgi_pass  unix:/var/run/php5-fpm.sock;
  # or use TCP
  # fastcgi_pass  127.0.0.1:9000;
  fastcgi_index index.php;

  include       fastcgi_params;

  fastcgi_split_path_info ^(.+\.php)(/.+)\$;
  fastcgi_param PATH_INFO       \$fastcgi_path_info;
  fastcgi_param PATH_TRANSLATED \$document_root\$fastcgi_path_info;
  fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
}
</pre>

<h3>ASP.NET FastCGI</h3>
<pre>
location / {
  # set socket path
  fastcgi_pass   unix:/tmp/asp-net.socket;
  # or use TCP
  # fastcgi_pass 127.0.0.1:9100;
  include        fastcgi_params;
}
</pre>

<h3>Proxy to Apache</h3>
<pre>
location / {
  proxy_set_header X-Real-IP  \$remote_addr;
  proxy_set_header X-Forwarded-For \$remote_addr;
  proxy_set_header Host \$host;
  # set address and port listen of your Apache
  proxy_pass http://127.0.0.1:8080;
}
</pre>

<h3>FastCGI</h3>
<pre>
location / {
  fastcgi_pass  localhost:9000;
  fastcgi_index index.php;

  fastcgi_param SCRIPT_FILENAME /home/example/www/php\$fastcgi_script_name;
  fastcgi_param QUERY_STRING    \$query_string;
  fastcgi_param REQUEST_METHOD  \$request_method;
  fastcgi_param CONTENT_TYPE    \$content_type;
  fastcgi_param CONTENT_LENGTH  \$content_length;
}
</pre>
<p><a href="http://nginx.org/${lang}/docs/http/ngx_http_fastcgi_module.html" target="_blank" rel="nofollow noreferrer noopener">More info</a>.</p>

<h3>Rewriting</h3>
<p>Syntax: <code>rewrite regex replacement [flag];</code></p>
<pre>
server {
  # www to non-www
  if (\$host ~* ^www\.(.*)\$) {
    rewrite / \$scheme://\$1 permanent;
  }
  # non-www to www
  # if (\$host ~* ^(?!www\.)(.*)\$) {
  #   rewrite / \$scheme://www.\$2 permanent;
  # }

  # non-www without rewrite
  # server_name www.example.org;
  # return 301 \$scheme://example.org$request_uri;
  # non-www to www without rewrite
  # server_name ~^(?!www\.)(?&lt;domain&gt;.+)\$;
  # return 301 \$scheme://www.\$domain\$request_uri;

  rewrite ^/content/(.*)$ /Content/ last;
  rewrite ^/scripts/(.*)$ /Scripts/ last;

  rewrite ^(/download/.*)/media/(.*)\..*\$ \$1/mp3/\$2.mp3 last;

  rewrite ^/profile/(.*)\$  /profile.php?user=\$1 last;

  # return  403;

  location /download/ {
    rewrite ^(/download/.*)/media/(.*)\..*\$ \$1/mp3/\$2.mp3 break;
    # return  403;
  }
}
</pre>
<p>An optional <code>flag</code> parameter can be one of:</p>
<ul>
  <li>
    <code>last</code>
    - stops processing the current set of ngx_http_rewrite_module directives and starts a search for a new location matching the changed URI;
  </li>
  <li>
    <code>break</code>
    - stops processing the current set of ngx_http_rewrite_module directives as with the break directive;
  </li>
  <li>
    <code>redirect</code>
    - returns a temporary redirect with the 302 code; used if a replacement string does not start with "http://" or "https://";
  </li>
  <li>
    <code>permanent</code>
    - returns a permanent redirect with the 301 code.
  </li>
</ul>
<p><a href="http://nginx.org/${lang}/docs/http/ngx_http_rewrite_module.html" target="_blank" rel="nofollow noreferrer noopener">More info</a>.</p>

<h3>Caching</h3>
<p>
  Syntax:  <code>expires [modified] time;</code> or <code>expires epoch | max | off;</code>
</p>
<pre>
location ~* .(js|css|png|jpg|jpeg|gif|ico)\$ {
  # time | epoch | max | off
  expires max;
  log_not_found off;
  access_log off;
}
</pre>
<p>
  <strong>expires</strong> - enables or disables adding or modifying the <code>Expires</code> and <code>Cache-Control</code> 
  response header fields provided that the response code equals 200, 201, 204, 206, 301, 302, 303, 304, or 307.<br />
  A parameter can be a positive or negative <a href="http://nginx.org/${lang}/docs/syntax.html" target="_blank" class="new-window" rel="nofollow noreferrer noopener">time</a>.
</p>
<p><a href="http://nginx.org/${lang}/docs/http/ngx_http_headers_module.html" target="_blank" rel="nofollow noreferrer noopener">More info</a>.</p>