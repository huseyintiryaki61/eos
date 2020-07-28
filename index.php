<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<?php
            $publicip= $_SERVER["REMOTE_ADDR"];

            if (getenv('HTTP_CLIENT_IP')) {
                $localip = getenv('REMOTE_ADDR');
                $publicip = getenv('HTTP_CLIENT_IP'); 
            } else if(getenv('HTTP_X_FORWARDED_FOR')) {
                $localip = getenv('REMOTE_ADDR');
                $publicip = getenv('HTTP_X_FORWARDED_FOR');
            } else if(getenv('HTTP_X_FORWARDED')) {
                $localip = getenv('REMOTE_ADDR');
                $publicip = getenv('HTTP_X_FORWARDED');
            } else if(getenv('HTTP_FORWARDED_FOR')) {
                $localip = getenv('REMOTE_ADDR');
                $publicip = getenv('HTTP_FORWARDED_FOR');
            } else if(getenv('HTTP_FORWARDED')) {
                $localip = getenv('REMOTE_ADDR');
                $publicip = getenv('HTTP_FORWARDED');
            } else {
                $localip = getenv('REMOTE_ADDR');
            }
                
            if ($publicip != $_SERVER["REMOTE_ADDR"]) {
               header("Location:pages/index.php");
            } else {
                header("Location:pages/index.php");              
            }
        ?>  
</body>
</html>
