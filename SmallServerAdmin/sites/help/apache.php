<pre>
&lt;VirtualHost 127.0.0.1:8080&gt;
  DocumentRoot "/home/www/example"
  ServerName example.org
  ServerAlias www.example.org
  
  &lt;Location /&gt;
    Options FollowSymLinks Indexes
    AllowOverride All
    Order Allow,Deny
    Allow from all
    Require all granted
    DirectoryIndex index.html index.htm index.php
  &lt;/Location&gt;

  # ...
&lt;/VirtualHost&gt;
</pre>

<ul>
  <li><a href="http://httpd.apache.org/docs/current/mod/core.html#virtualhost" target="_blank">&lt;VirtualHost&gt;</a></li>
  <li><a href="http://httpd.apache.org/docs/current/mod/core.html#directory" target="_blank">&lt;Directory&gt;</a></li>
  <li><a href="http://httpd.apache.org/docs/current/mod/core.html#location" target="_blank">&lt;Location&gt;</a></li>
  <li><a href="http://httpd.apache.org/docs/current/mod/core.html#files" target="_blank">&lt;Files&gt;</a></li>
  <li><a href="http://httpd.apache.org/docs/current/mod/mod_rewrite.html" target="_blank">mod_rewrite</a></li>
  <li><a href="http://httpd.apache.org/docs/current/mod/mod_auth_basic.html" target="_blank">mod_auth_basic</a></li>
  <li><a href="http://httpd.apache.org/docs/current/mod/" target="_blank">All modules</a></li>
  <li><a href="http://httpd.apache.org/docs/current/" target="_blank">Documentation</a></li>
</ul>

<h3>Mono</h3>
<table class="table">
  <thead>
    <tr>
      <th>Directive</th>
      <th>Context</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>MonoApplications</td>
      <td>VirtualHost</td>
      <td>
        Adds applications from a comma separated list of virtual and physical directory pairs.<br />
        For example: <code>MonoApplications example.org "/:/home/example/www"</code>.
      </td>
    </tr>
    <tr>
      <td>MonoServerPath</td>
      <td>VirtualHost</td>
      <td>
        Path to mono server.<br />
        For example: <code>MonoServerPath example.org "/usr/bin/mod-mono-server4"</code>
      </td>
    </tr>
    <tr>
      <td>MonoSetServerAlias</td>
      <td>Directory, Location</td>
      <td>
        Tells <strong>mod_mono</strong> which instance of mod-mono-server will be used to process the requests for this <code>Location</code> or <code>Directory</code>.<br />
        For example:<br />
        <pre>MonoApplications testing "/test:/home/example/test"
&lt;Location /test&gt;
  MonoSetServerAlias testing
&lt;/Location&gt;

MonoApplications personal "/personal:/home/example/personal"
&lt;Location /personal&gt;
  MonoSetServerAlias personal
&lt;/Location&gt;</pre>
      </td>
    </tr>
    <tr>
      <td>MonoSetEnv</td>
      <td>VirtualHost</td>
      <td>
        Allows specify the mono server settings.<br />
        For example: <code>MonoSetEnv example.org MONO_IOMAP=all</code>.
      </td>
    </tr>
  </tbody>
</table>
<h3>mono-ctrl</h3>
<p>
  <strong>mod_mono</strong> provides a simple web-based control panel for restarting the mod-mono-server, 
  which is useful when assemblies need to be reloaded from disk after they have been changed. 
  To activate the control panel, place the following code:
</p>
<pre>
&lt;Location /mono&gt;
  SetHandler mono-ctrl
  AllowOverride All
  Order Allow,Deny
  Allow from all
  Require all granted
&lt;/Location&gt;
</pre>