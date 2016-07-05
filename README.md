# FtpToGoogleSpreadsheets

### Configuration

* Create config file 

```php
//config.php

<?php

define('FTP_SERVER','ftp.logistican.com');
define('FTP_USER','user');
define('FTP_PASSWORD','password');
define('FTP_PATH_RECEIVE','/ftp/path/download');

define('LOCAL_PATH_FILES_RECEIVE','/var/www/FtpToGoogleSpreadsheets/receive');
define('LOCAL_PATH_FILES_DONE','/var/www/FtpToGoogleSpreadsheets/done');
define('HOOK_ZAPIER','https://hooks.zapier.com/hooks/catch/xxxxx')

$fileMapping = array(
					0=>'id',
					1=>'name',
					2=>'cp',
					3=>'city',
					4=>'status',
				);

$filterFieldNumber = 4;
$filter ='#OK#';

```