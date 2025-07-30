<?php
if ($_GET['run']) {
  # This code will run if ?run=true is set.
  shell_exec("sudo /var/www/html/move/tien2.py");
shell_exec("/var/www/html/test.sh");
echo ("test 123 php");
}
?>

<!-- This link will add ?run=true to your URL, myfilename.php?run=true -->
<a href="?run=true">Click Me!</a>

