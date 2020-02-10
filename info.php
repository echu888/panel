<?php
require_once 'menu.php';
require_once 'debug.php';
?>


<div style='background:#fff; padding: 20px 20px 80px;'>

<a href='logs/?C=N;O=D'>Debug Logs</a> 
<hr/>
Session :
<pre>
<?php print_r( $_SESSION  ); ?>
</pre>

</div>
