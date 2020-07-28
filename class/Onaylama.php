<?php 
class Onaylama{
	private $_tamam=false,
	$_hatalar=array(),
	$_db=null;
	public function __construct(){
		$this->_db=DB::baglan();
	}
	public function kontrol($kaynak,$bolumler=array()){
		foreach($bolumler as $bolum=>$kurallar){
			foreach($kurallar as $kural=>$kural_deger){
				$deger=trim($kaynak[$bolum]);
				$bolum=filtrele($bolum);
				if($kural ==='zorunlu' && empty($deger)){
					$this->hataEkle("{$bolum} zorunlu alandır");
				}else if(!empty($deger)){
					switch($kural){
						case 'min':
						if(strlen($deger)< $kural_deger){
							$this->hataEkle("{$bolum} en az {$kural_deger} karakter olmalı!");
						}
						break;
						case 'max':
						if(strlen($deger) > $kural_deger){
							$this->hataEkle("{$bolum} en az {$kural_deger} karakter olmalı!");
						}
						break;
						case 'eslesme':
						if($deger != $kaynak[$kural_deger]){
							$this->hataEkle("{$kural_deger} ile {$bolum} eşleşmedi");
						}
						break;
						case 'benzersiz':
						$kontrol=$this->_db->getir($kural_deger,array("$bolum","=","$deger"));
						if($kontrol->sayac()){
							$this->hataEkle("{$bolum} kullanılmaktadır");
						}
						break;
						default:
						
						break;
					}
				}
			
			}
		}
		if(empty($this->_hatalar)){
			$this->_tamam=true;
		}
		return $this;
	}
	public function hataEkle($hatalar){
		$this->_hatalar[] = $hatalar;
	}
	public function hatalar(){
		return $this->_hatalar;
	}
	public function tamam(){
		return $this->_tamam;
	}
}

?>