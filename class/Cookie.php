<?php 
class Cookie{
	public static function varsa($isim){
		return (isset($_COOKIE["{$isim}"])) ? true : false;
	}
	public static function getir($isim){
		return $_COOKIE["{$isim}"];
	}
	public static function yerlestir($isim,$deger,$bitis){ 
		if(setcookie($isim, $deger, time() + $bitis, "/")){
			
			return true;
		}else{
			return false;
		}
			
	}
	public static function sil($isim){
//setcookie($isim, null, -1, '/');
		self::yerlestir($isim,'',- 1);
	}
}


?>