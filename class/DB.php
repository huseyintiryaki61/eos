<?php 
class DB{
	private static $_baglan = null;
	private $_pdo,$_query,$hatalar=false,$sonuc,$sayac=0;
	private function __construct(){
		try{
		$this->_pdo = new PDO('mysql:host='.Config::getir('mysql/host').';dbname='.Config::getir('mysql/db'),Config::getir('mysql/kullanici_adi'),Config::getir('mysql/sifre'));
		
		}
		catch(PDOException $e){
			die($e->getMessage());
		}
	}
	public static function baglan(){
		if(!isset(self::$_baglan)){
			self::$_baglan=new DB();
		}return self::$_baglan;
	}
	public  function query($sql,$parametre =array()){
		$this->hatalar=false;
		
		if($this->_query=$this->_pdo->prepare($sql)){
			$x=1;
			if(count($parametre)){
		foreach($parametre as $param){
			$this->_query->bindValue($x,$param);
			$x++;
			
		}
		}
		if($this->_query->execute()){
		$this->sonuc=$this->_query->fetchAll(PDO::FETCH_OBJ);	
		$this->_sayac=$this->_query->rowCount();
			}
			else{
				$this->hatalar=true;
			}
		}
		return $this;
	}
	
	public function eylem($eylem,$tablo,$where=array()){
		if(count($where)===3){
			$operatorler = array('=','<','>','<=','=>');
			$alan=$where[0];
			$operator=$where[1];
			$deger=$where[2];
			if(in_array($operator,$operatorler)){
				$sql="{$eylem} FROM {$tablo} WHERE {$alan} {$operator} ?";
				if(!$this->query($sql,array($deger))->hatalar()){
					return $this;
				}
			}
		}
		return false;
	}
	public function guncelle($tablo,$id,$alanlar){
		$set='';
		$x=1;
		foreach($alanlar as $anahtar=>$deger){
			$set .= "{$anahtar} = ?";
			if($x <count($alanlar)){
				$set .= ', ';
			}
			$x++;
		}
		
	$sql= "UPDATE {$tablo} SET {$set} WHERE id=$id";
	if(!$this->query($sql,$alanlar)->hatalar()){
			return true;
		}
		return false;
	}
	public function getir($tablo,$where){
		return $this->eylem('SELECT *',$tablo,$where);
	}
	public function sil($tablo,$where){
		return $this->eylem('DELETE',$tablo,$where);
	}
	public function ekle($tablo,$alanlar =array()){
		$anahtar=array_keys($alanlar);
		$degerler='';
		$x=1;
		foreach($alanlar as $alan){
			$degerler.="?";
			if($x <count($alanlar)){
				$degerler .= ',';
			}
			$x++;
		}
		
		$sql="INSERT INTO {$tablo} (`".implode('`,`',$anahtar)."`) VALUES ({$degerler})";
		if(!$this->query($sql,$alanlar)->hatalar()){
			return true;
		}
		return false;
	}
	public function ilk(){
		return $this->sonuc[0];
	}
	public function hatalar(){
		return $this->hatalar;
	}
	public function sayac(){
		return $this->_sayac;
	}
	public function sonuc(){
		return $this->sonuc;
	}
	public function son($deger=null,$tablo=null){
		
		$sql="SELECT MAX({$deger}) FROM {$tablo} ";
		$a=$this->query($sql)->sonuc();
		$array = json_decode(json_encode($a), True);
$yeni=json_decode(json_encode($array[0]), True);
		$maxim=$yeni["MAX({$deger})"];
		return $maxim;
		
	}
	public function tum($tablo,$id=null,$sira=null){
		$sql="SELECT * FROM {$tablo} ORDER BY {$id} {$sira} ";
		$sorgu=$this->query($sql)->sonuc();
		
		return $sorgu;
	}
	public function tumlimit($tablo,$id=null,$sira=null,$limit=null){
		$sql="SELECT * FROM {$tablo} ORDER BY {$id} {$sira} LIMIT {$limit} ";
		$sorgu=$this->query($sql)->sonuc();
		
		return $sorgu;
	}
	
	public function ikili($tur,$sira2){
		$sql="SELECT * FROM galeri WHERE tur_id={$sira2} AND tur='{$tur}'";
		$sorgu=$this->query($sql)->sonuc();
		
		return $sorgu;
	}
	public function ikilit($tablo,$tur,$sira2){
		$sql="SELECT * FROM {$tablo} WHERE turid={$sira2} AND tur='{$tur}'";
		$sorgu=$this->query($sql)->sonuc();
		
		return $sorgu;
	}

	public function toplam($deger,$tablo){
		$sql="SELECT SUM({$deger}) FROM {$tablo} ";
		$sorgu=$this->query($sql)->sonuc();
		$array = json_decode(json_encode($sorgu), True);
		
		return $array[0];
	}
	public function encok($tablo,$deger){
		$sql="SELECT * FROM {$tablo} WHERE {$deger}=(SELECT MAX({$deger}) FROM {$tablo})";
		$sorgu=$this->query($sql)->sonuc();
		return $sorgu;
	}
	public function benzer($id,$baslik,$tablo){
	$benzer=array();
$sorgubenzer=self::tum('blog','Id','DESC');
foreach($sorgubenzer as $sorgubenzerdonen){
	
	$basliksimilar=$sorgubenzerdonen->baslik;
	similar_text($baslik,$basliksimilar,$benzerdurumu);
	array_push($benzer,$benzerdurumu."|||".$basliksimilar);
}

	return $benzer;
	}
	
	public function cokyapan($tablo1,$tablo2,$tablo3,$deger){
		$sql="SELECT admin.ad_soyad,COUNT(*) FROM `{$tablo1}` INNER JOIN admin ON admin.id={$tablo1}.{$deger} GROUP BY  {$deger} ORDER BY COUNT(*) DESC ";
			$sorgu=$this->query($sql)->sonuc();
			$sql1="SELECT admin.ad_soyad,COUNT(*) FROM `{$tablo2}` INNER JOIN admin ON admin.id={$tablo2}.{$deger} GROUP BY  {$deger} ORDER BY COUNT(*) DESC ";
			$sorgu1=$this->query($sql1)->sonuc();
			$sql2="SELECT admin.ad_soyad,COUNT(*) FROM `{$tablo3}` INNER JOIN admin ON admin.id={$tablo3}.{$deger} GROUP BY  {$deger} ORDER BY COUNT(*) DESC ";	
			$sorgu2=$this->query($sql2)->sonuc();
			
		$a = json_decode(json_encode($sorgu), True);
		$c = json_decode(json_encode($sorgu1), True);
		$b = json_decode(json_encode($sorgu2), True);
		$d=array($a[0],$b[0],$c[0]);
		return $d;
	}
	public function cokkim(){
		$sql="SELECT id,ad_soyad FROM admin";
		$sorgu=$this->query($sql)->sonuc();
		$x=1;
		$deger="";
		foreach($sorgu as $sor){
			if($x < count($sorgu)){
				$deger=",";
				$adlar.=$sor->ad_soyad.$deger;
			}else{
				$adlar.=$sor->ad_soyad;
			}
			if($x < count($sorgu)){
				$deger=",";
				$idler.=$sor->id.$deger;
			}else{
				$idler.=$sor->id;
			}
			$x++;
		}
		$iayir=explode(",",$idler);
		$aayir=explode(",",$adlar);
		$idsayi=count($iayir);
		$adsayi=count($aayir);
		$p=1;
		$u=1;
		$qw=1;
		for($a=0;$a<$idsayi;$a++){
			$sid=$iayir[$a];
			$sqz="SELECT  * FROM konut WHERE kullanici_id={$sid} ";	
			
			if($p<$idsayi){
				$deger=",";
				$kgirilen.=$this->query($sqz)->sayac().$deger;
			}else{
				$deger="";
				$kgirilen.=$this->query($sqz)->sayac().$deger;
			}
			$p++;
		}
		for($a=0;$a<$idsayi;$a++){
			$sid=$iayir[$a];
			$sqz="SELECT  * FROM isyeri WHERE kullanici_id={$sid} ";	
			
			if($u<$idsayi){
				$deger=",";
				$igirilen.=$this->query($sqz)->sayac().$deger;
			}else{
				$deger="";
				$igirilen.=$this->query($sqz)->sayac().$deger;
			}
			$u++;
		}
		for($a=0;$a<$idsayi;$a++){
			$sid=$iayir[$a];
			$sqz="SELECT  * FROM arsa WHERE kullanici_id={$sid} ";	
			
			if($qw<$idsayi){
				$deger=",";
				$agirilen.=$this->query($sqz)->sayac().$deger;
			}else{
				$deger="";
				$agirilen.=$this->query($sqz)->sayac().$deger;
			}
			$qw++;
		}
		$toplamgirilen=array($iayir,$agirilen,$igirilen,$kgirilen,$aayir);
		
		return $toplamgirilen;
	}
	public function getirlimit($tablo=null,$id=null,$sart=null,$deger=null,$limit=null){
	$sql="SELECT * FROM {$tablo} WHERE {$id} {$sart} {$deger} ORDER BY {$id} DESC LIMIT {$limit} ";
	$sorgu=$this->query($sql)->sonuc();
	return $sorgu;
	}
	public function ilanara($where){
		$sql="{$where}";
	$sorgu=$this->query($sql)->sonuc();
	return $sorgu;
		
	}
	
}
?>