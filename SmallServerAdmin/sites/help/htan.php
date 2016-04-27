<h3>fastCGI</h3>
<p>The <code>fastCGI</code> node contains a list of addresses to start.</p>
<table class="table">
  <thead>
    <tr>
      <th class="col-xs-6 col-sm-4 col-md-4 col-lg-3">Parameter</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>address</td>
      <td>
        Specifies the address to listen on.<br />
        Valid values are "pipe", "unix", and "tcp".<br />
        For example: <code>unix:/tmp/example.org</code>, <code>tcp:127.0.0.1:9100</code>.<br />
        The address will be replaced by substituted for the marker <code>{socket}</code> in the specified command.
      </td>
    </tr>
    <tr>
      <td>command</td>
      <td>
        Command or command name (<code>&lt;commands /&gt;</code>) which should be run via <code>start-stop-daemon</code>.<br />
        For example:<br />
        <ul class="no-padding">
          <li><code>myCommandName</code></li>
          <li><code>/usr/bin/fastcgi-mono-server4 /applications=/:/home/example/www/ /socket={socket}</code></li>
        </ul> 
      </td>
    </tr>
    <tr>
      <td>
        beforeStartingCommand<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Command, command name or URL to be executed before executing the <code>command</code>.<br />
        For example: <br />
        <ul class="no-padding">
          <li><code>myCommandName</code></li>
          <li><code>echo "Starting..." >> custom.log</code></li>
          <li><code>http://api.foxtools.ru/v2/QR.html?mode=Auto&text=Hello+world%21&details=1</code></li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>
        afterStartingCommand<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Command, command name or URL to be executed after starting the <code>command</code>.<br />
        For example: <br />
        <ul class="no-padding">
          <li><code>myCommandName</code></li>
          <li><code>echo "Started" >> custom.log</code></li>
          <li><code>http://example.org/</code></li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>
        beforeStoppingCommand<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Command, command name or URL to be executed before stopping.
      </td>
    </tr>
    <tr>
      <td>
        afterStoppingCommand<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Command, command name or URL to be executed after stopping.
      </td>
    </tr>
    <tr>
      <td>
        stoppingTimeout<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Maximum waiting time stopping the process (in seconds). Default: <code>10</code> seconds.
      </td>
    </tr>
  </tbody>
</table>

<h3>commands</h3>
The <code>commands</code> node contains a list of available commands.
<table class="table">
  <thead>
    <tr>
      <th class="col-xs-6 col-sm-4 col-md-4 col-lg-3">Parameter</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>name</td>
      <td>
        Command name. Any convenient set of characters.<br />
        For example: <code>myCommandName</code>.
      </td>
    </tr>
    <tr>
      <td>exec</td>
      <td>
        Command line to be executed.<br />
        For example:<br />
        <ul class="no-padding">
          <li><code>service nginx reload</code></li>
          <li><code>echo "Hello world!" | mail -s "Test message" -t "example@example.org"</code></li>
          <li><code>/usr/bin/fastcgi-mono-server4</code></li>
          <li><code>/usr/bin/fastcgi-mono-server4 /applications=/:/home/example/www/ /socket={socket}</code></li>
          <li><code>echo "Any command"</code></li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>
        arguments<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Additional arguments that will be passed to the command.<br />
        For example:<br />
        <ul class="no-padding">
          <li><code>-n -e</code></li>
          <li><code>/applications=/:/home/example/www/ /socket={socket}</code></li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>
        user<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        User name under which the command is executed.<br />
        Default: <code>root</code>.
      </td>
    </tr>
    <tr>
      <td>
        group<br />
        <small><em>(optional)</em></small>
      </td>
      <td>
        Group name under which the command is executed.<br />
        Default: <code>root</code>.
      </td>
    </tr>
  </tbody>
</table>

<h3>Example</h3>
<pre>&lt;configuration&gt;
  &lt;fastCGI&gt;
    &lt;add address="unix:/tmp/example-1.org" command="fastcgi-mono-server4" /&gt;
    &lt;add address="unix:/tmp/example-2.org" command="fastcgi-mono-server4" /&gt;
    &lt;add address="unix:/tmp/old.example.org" command="fastcgi-mono-server2" /&gt;
  &lt;/fastCGI&gt;
  &lt;commands&gt;
    &lt;add
      name="fastcgi-mono-server4"
      user="www-data"
      group="www-data"
      exec="/usr/bin/fastcgi-mono-server4"
      arguments="/applications=/:/home/example/www/ /socket={socket} /multiplex=True /verbose=True /printlog=True"
    /&gt;
    &lt;add
      name="fastcgi-mono-server2"
      exec="/usr/bin/fastcgi-mono-server2"
      arguments="/applications=/:/home/example/old/ /socket={socket} /multiplex=True"
    /&gt;
  &lt;/commands&gt;
&lt;/configuration&gt;</pre>

<h3>fastcgi-mono-server</h3>
<table class="table">
  <thead>
    <tr>
      <th class="col-xs-6 col-sm-4 col-md-4 col-lg-3">Parameter</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>verbose</td>
      <td>
        Prints extra messages. Mainly useful for debugging.
      </td>
    </tr>
    <tr>
      <td>printlog</td>
      <td>
        Prints log messages to the console.
      </td>
    </tr>
    <tr>
      <td>logfile=VALUE</td>
      <td>
        Specifies a file to log events to.
      </td>
    </tr>
    <tr>
      <td>config-file=VALUE</td>
      <td>
        Specifies a file containing configuration options, identical to those available in the command line.
      </td>
    </tr>
    <tr>
      <td>name=VALUE</td>
      <td>
        Specifies a name to print in the log.
      </td>
    </tr>
    <tr>
      <td>loglevels=VALUE</td>
      <td>
        Specifies what log levels to log. 
        It can be any of the following values, or multiple if comma separated:
        <ul class="no-padding">
          <li>Debug</li>
          <li>Notice</li>
          <li>Warning</li>
          <li>Error</li>
          <li>Standard (Notice,Warning,Error)</li>
          <li>All (Debug,Standard)</li>
        </ul>
        This value is only used when "logfile" or "printlog" are set.
      </td>
    </tr>
    <tr>
      <td>root=VALUE</td>
      <td>
        Specifies the root directory the server changes to before doing performing any operations. 
        This value is only used when "appconfigfile", "appconfigdir", or "applications" is set, to provide a relative base path.
      </td>
    </tr>
    <tr>
      <td>applications=VALUE</td>
      <td>
        Adds applications from a comma separated list of virtual and physical directory pairs.<br />
        The pairs are separated by colons and optionally include the virtual host name and port to use: 
        <code>[hostname:[port:]]VPath:realpath,...</code><br />
        Samples:<br />
        <ul class="no-padding">
          <li>
            <code>/:.</code><br />
            The virtual root directory, "/", is mapped to the current directory or "root" if specified.
          </li>
          <li>
            <code>/blog:../myblog</code><br />
            The virtual /blog is mapped to ../myblog myhost.someprovider.net:/blog:../myblog The virtual /blog at myhost.someprovider.net is mapped to ../myblog.<br />
            This means that other host names, like "localhost" will not be mapped.
          </li>
          <li>
            <code>/:.,/blog:../myblog</code><br />
            Two applications like the above ones are handled.
          </li>
          <li>
            <code>*:80:/:standard,*:433:/:secure</code><br />
            The server uses different applications on the unsecure and secure ports.
          </li>
        </ul>
      </td>
    </tr>
    <tr>
      <td>appconfigfile=VALUE</td>
      <td>
        Adds application definitions from an XML configuration file, typically with the ".webapp" extension.<br />
        See sample configuration file that comes with the server.
      </td>
    </tr>
    <tr>
      <td>appconfigdir=VALUE</td>
      <td>
        Adds application definitions from all XML files found in the specified directory.<br />
        Files must have the ".webapp" extension.
      </td>
    </tr>
    <tr>
      <td>backlog=VALUE</td>
      <td>
        The listen backlog.
      </td>
    </tr>
    <tr>
      <td>address=VALUE</td>
      <td>
        Specifies the IP address to listen on.
      </td>
    </tr>
    <tr>
      <td>multiplex</td>
      <td>
        Allows multiple requests to be send over a single connection.
      </td>
    </tr>
    <tr>
      <td>ondemand</td>
      <td>
        Listen on the socket specified via <code>/ondemandsock</code> and accepts via sendmsg(2).<br />
        Terminates after it receives no requests for some time.
      </td>
    </tr>
    <tr>
      <td>maxconns=VALUE</td>
      <td>
        Specifies the maximum number of concurrent connections the server should accept.
      </td>
    </tr>
    <tr>
      <td>maxreqs=VALUE</td>
      <td>
        Specifies the maximum number of concurrent requests the server should accept.
      </td>
    </tr>
    <tr>
      <td>port=VALUE</td>
      <td>
        Specifies the TCP port number to listen on.
      </td>
    </tr>
    <tr>
      <td>idle-time=VALUE</td>
      <td>
        Time to wait (in seconds) before stopping if <code>ondemand</code> is set.
      </td>
    </tr>
    <tr>
      <td>filename=VALUE</td>
      <td>
        Specifies a unix socket filename to listen on. To use this argument, "socket" must be set to "unix".
      </td>
    </tr>
    <tr>
      <td>socket=VALUE</td>
      <td>
        Specifies the type of socket to listen on.<br />
        Valid values are "pipe", "unix", and "tcp".<br />
        <strong>pipe</strong> indicates to use piped socket opened by the web server when it spawns the application.<br />
        <strong>unix</strong> indicates that a standard unix socket should be opened.<br />
        The file name can be specified in the "filename" argument or appended to this argument with a colon, eg:<br />
        <code>unix</code><br />
        <code>unix:/tmp/fastcgi-mono-socket</code><br />
        <strong>tcp</strong> indicates that a TCP socket should be opened.<br />
        The address and port can be specified in the "port" and "address" arguments or appended to this argument with a colon, eg:<br />
        <code>tcp</code><br />
        <code>tcp:8081</code><br />
        <code>tcp:127.0.0.1:8081</code><br />
        <code>tcp:0.0.0.0:8081</code>.<br /><br />
        <strong>NOTE:</strong> Use marker <code>{socket}</code> for <code>&lt;commands /&gt;</code>.
      </td>
    </tr>
    <tr>
      <td>ondemandsock=VALUE</td>
      <td>
        The socket to listen on for ondemand service.
      </td>
    </tr>
  </tbody>
</table>