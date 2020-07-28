<?php 
class Kullanici{
	private $_db,$_veri,$_sessionIsmi,$_cookieIsmi,$_girisYapti;
	public function __construct($kullanici=null){
		$this->_db=DB::baglan();
		$this->_sessionIsmi=Config::getir('session/session_ismi');
		$this->_cookieIsmi=Config::getir('hatirla/cookie_ismi');
		if(!$kullanici){
			if(Session::varsa($this->_sessionIsmi)){
				$kullanici=Session::getir($this->_sessionIsmi);
				if($this->bul($kullanici)){
					$this->_girisYapti =true;
				}else{ }
			}
		}else{
			$this->bul($kullanici);
		}
	}
	public function olustur($alanlar=array()){
		if(!$this->_db->ekle('admin',$alanlar)){
			throw new Exception('Hesap Oluşturulamadı');
		}
	}
	public function bul($kullanici=null){
		if($kullanici){
			$alan = (is_numeric($kullanici)) ? 'id' : 'kullanici_ad';
		$veri=$this->_db->getir('admin',array($alan,'=',$kullanici));
		if($veri->sayac()){
			$this->_veri=$veri->ilk();
			return true;
		}
		}
		return false;
	}
	public function giris($kullanici_adi=null,$sifre=null,$hatirla=false){
		if(!$kullanici_adi && !$sifre && $this->varsa()){
			Session::yerlestir($this->_sessionIsmi,$this->veri()->id);
		}else{
		
		$kullanici=$this->bul($kullanici_adi);
		if($kullanici){ 
			if($this->veri()->sifre === md5(Input::getir('sifre'))){
				Session::yerlestir($this->_sessionIsmi,$this->veri()->id);
				if($hatirla){
					$hash=Hash::unique();
$hashKontrol=$this->_db->getir('uye_session',array('kullanici_id','=',$this->veri()->id));
if(!$hashKontrol->sayac()){
	$this->_db->ekle('uye_session',array(
	'kullanici_id' => $this->veri()->id,
	'hash' =>$hash
	));
	
}else{
	$hash=$hashKontrol->ilk()->hash;
	
}
Cookie::yerlestir($this->_cookieIsmi,$hash,Config::getir('hatirla/cookie_bitis'));
				}
				return true;
			}else{
		echo "şifre yanlış";
				}
	}}
		return false;
	}
	public function veri(){
		return $this->_veri;
	}
	
	public function varsa(){
		return (!empty($this->_veri)) ? true : false;
	}
	public function girisyapti(){
		return $this->_girisYapti;
	}
	public function cikisYap(){
		$this->_db->sil('uye_session',array('kullanici_id','=',$this->veri()->id));
		Session::sil($this->_sessionIsmi);
		Cookie::sil($this->_cookieIsmi);
	}
	public function guncelle($alanlar=array(),$id=null){
		if(!$id && $this->girisYapti()){
			$id=$this->veri()->id;
		}
		if(!$this->_db->guncelle('admin',$id,$alanlar)){
			throw new Exception('Güncelleme başarısız');
		}
	}
	
	
}


?>