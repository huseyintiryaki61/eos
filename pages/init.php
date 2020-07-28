<?php 
ob_start();
session_start();
header('Content-Type: text/html; charset=utf-8');
$GLOBALS["config"]=array(
'mysql' => array(
'host' =>'127.0.0.1',
'kullanici_adi' =>'root',
'sifre' =>'emintiryaki61',
'db' =>'eos'
),
'hatirla' =>array(
'cookie_ismi' =>'hash',
'cookie_bitis' =>604800
),
'session' =>array(
'session_ismi' =>'kullanici',
'token_ismi' =>'token'
)
);

spl_autoload_register(function($class){
	require_once '../class/' .$class. '.php';
});
require_once '../fonksiyon/filtrele.php';
$db=DB::baglan();
if(Cookie::varsa(Config::getir('hatirla/cookie_ismi'))&& !Session::varsa(Config::getir('session/session_ismi'))){
	$hash=Cookie::getir(Config::getir('hatirla/cookie_ismi'));
	$hashKontrol=DB::baglan()->getir('uye_session',array('hash','=',$hash));
	if($hashKontrol->sayac()){
		$kullanici=new Kullanici($hashKontrol->ilk()->kullanici_id);
		$kullanici->giris();
	}
}
?>