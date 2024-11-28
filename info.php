<?php
phpinfo();
?>

find: php.ini
open: php.ini
find: ;extension=gd (for php 8 +)
Remove: the semicolon (;) at the beginning
open: C:\xampp\php\ext
check: php_gd.dll

open: php.ini
check: ;extension=php_gd2.dll (lower version)
Remove: the semicolon (;) at the beginning

For Ubuntu/Debian: Run the following command: sudo apt-get install php-gd
For CentOS: Use: sudo yum install php-gd

restart Web Server||||

