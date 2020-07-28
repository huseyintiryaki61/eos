<?php 
class Yonlendir{
	public static function yon($konum=null){
		if($konum){
			if(is_numeric($konum)){
				switch($konum){
					case 404:
					header('HTTP/1.0 04 Not Found');
					include('include/hatalar/404.php');
					exit();
					break;
				}
				
			}
		}
		header('Location:'.$konum);
		
	}
}

?>