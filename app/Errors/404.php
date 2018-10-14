404 not found
<br>
<?php if (DEBUG) : ?>
Error: <br><pre>
<?php
echo 'line:'. $e->getLine()."<br>";
echo 'File:'. $e->getFile() . "<br>";
echo $e->getMessage()."<br>";
echo $e->getTraceAsString();

endif;
