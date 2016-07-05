<?php
require_once __DIR__.'/config.php';

function isDir($dir,$connect) {
    $originalDirectory = ftp_pwd($connect);
    if ( @ftp_chdir($connect, $dir ) ) {
        ftp_chdir($connect, $originalDirectory);
        return true;
    } 
    else {
        return false;
    }        
} 

$connect = ftp_connect(FTP_SERVER);
$login = ftp_login($connect, FTP_USER, FTP_PASSWORD);
$files = ftp_nlist($connect, FTP_PATH_RECEIVE."/");
foreach ($files as $file) {
	if (isDir($file,$connect)) continue;
	$path_parts = pathinfo($file);
	$fileReceive = LOCAL_PATH_FILES_RECEIVE."/".$path_parts['basename'];
	$fileDone = LOCAL_PATH_FILES_DONE."/".$path_parts['basename'];

	if (file_exists($fileReceive)){
		unlink($fileReceive);
	}

	if (!file_exists($fileDone)) {
    	ftp_get($connect, $fileReceive, $file, FTP_BINARY);
    	 $line=[];
    	 $control = count($fileMapping);
        if (($handle = fopen($fileReceive, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $num = count($data);
                if ($control==$num){
                	if (preg_match($filter, $data[$filterFieldNumber])) {
	                	for ($c=0; $c < $num; $c++) {
	                    	$line[$fileMapping[$c]]=$data[$c];
	                	}

						$data_string = json_encode($line);
						$ch = curl_init(HOOK_ZAPIER);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						    'Content-Type: application/json',
						    'Content-Length: ' . strlen($data_string))
						);
						curl_setopt($ch, CURLOPT_TIMEOUT, 5);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

						$result = curl_exec($ch);

						curl_close($ch);
                	}

                }
            }
            fclose($handle);
        }

		rename($fileReceive, $fileDone);
	}
	
}
ftp_close($connect);