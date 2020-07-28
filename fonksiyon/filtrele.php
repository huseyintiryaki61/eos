<?php 
function filtrele($string){
	$k1=addslashes($string);
	$k2=strip_tags($k1);
	$k3=rawurlencode($k2);
	return htmlspecialchars($k3);
}
function degerler($pst){
	$hepsi=array();
	foreach($pst as $post => $value ){
		if(!empty($value)){
			if(is_array($value)){
			$value=implode($value,",");
			}
		$hepsi[$post]=filtrele($value);
		}	
	}
return $hepsi;
	
}
function input($tur,$name,$baslik){	
$izinli=implode(",",Config::getir("file/uzanti"));	
	for($i=0;$i<count($tur);$i++){
		if($tur[$i]=="file"){ 
echo '<div class="form-group">
    <input type="'.$tur[$i].'" accept="'.$izinli.'"  name="'.$name[$i].'" class="form-control"  placeholder="'.$baslik[$i].'">
  </div>';	
		}else{
			echo '<div class="form-group">

    <input type="'.$tur[$i].'"  name="'.$name[$i].'" class="form-control"  placeholder="'.$baslik[$i].'">
  </div>';	
		} 
	
	}
}
function filekontrol($file){
	$hatalar=[];
	$ext2=explode(".",$file["name"]);
	$ext="image/".strtolower($ext2[count(explode(".",$file["name"]))-1]);
if(in_array($ext,Config::getir("file/uzanti"))===false){
	$hatalar[]="Uzanti tercih edilmiyor";
}
list($width, $height, $type, $attr) = getimagesize($file["tmp_name"]);
if($width<1 || $height <1 ){
	$hatalar[]="Resim boyutlarında hata var";
}
if(in_array($file["type"],Config::getir("file/uzanti"))===false){
	$hatalar[]="Uzanti tercih edilmiyor 2";
}
$path=pathinfo($file["name"]);
$pathinfo="image/".$path["extension"];
if(in_array($pathinfo,Config::getir("file/uzanti"))===false){
	$hatalar[]="Uzanti tercih edilmiyor-3";
}

return $hatalar;
}
function olumlu(){
	$response['status']="success";
$response['title']="Başarılı";
$response['message']="İşlem başarılı";
echo json_encode($response);
exit;
}
function olumsuz($a=null){
	if($a)
		$response['message']=$a;
	else
		$response['message']="İşlem Başarısız";
	$response['status']="error";
$response['title']="Olmadı";
echo json_encode($response);
exit;
}
function imgkontrol($f){
	if((!is_uploaded_file($f["tmp_name"])) || $f["size"]==0 || $f["error"]>0 ){
			return false;
		}else{
			return true;
		}
}

function imgsave($f){
	 $uploads_dir = Config::getir("file/save");
	@$tmp_name = $f["tmp_name"];
	@$name = $f["name"];
	$benzersizad=uniqid();
	$refimgyol=$uploads_dir."/".$benzersizad.$name;
@move_uploaded_file($tmp_name, "$uploads_dir/$benzersizad$name");
return $refimgyol;
}
function ekle($p,$tablo,$f=null){
	if(Token::kontrol($p["token"])){
		unset($p["token"]);
	$a=degerler($p);
	if(imgkontrol($_FILES[$f])){
	$c=filekontrol($_FILES[$f]);	
	if(count($c)>0){ olumsuz("Dosya kurallara uymuyor"); }
	else{ $r=imgsave($_FILES[$f]); 
	isset($r) ? $a[$f]=$r : olumsuz("resim kaydedilemedi");
	}
	}
		$e=DB::baglan()->ekle($tablo,$a);
		isset($e) ? olumlu()	: olumsuz(); 
	}else{
		olumsuz("Token kontrolu başarısız");
	}
}
function duzelt($p,$f=null,$tablo){
	$id=$p["id"];
	if(Token::kontrol($p["token"])){ 
		unset($p["token"]);
		$a=degerler($p);
		if(imgkontrol($_FILES[$f])){
	$c=filekontrol($_FILES[$f]);	
	if(count($c)>0){ olumsuz("Dosya kurallara uymuyor"); }
	else{ 
	$eski=DB::baglan()->getir($tablo,array('id','=',$id))->ilk();
	$r=imgsave($_FILES[$f]); 
	if(isset($r)){
$a[$f]=$r; $sil=unlink($eski->$f);
if(!$sil){ olumsuz("Eski resim silinemedi");  }
			}
	else{olumsuz("resim kaydedilemedi");} 
	}
	}
	$duzelt=DB::baglan()->guncelle($tablo,$id,$a);
	isset($duzelt) ? olumlu() : olumsuz();
	}else{
		olumsuz("Token kontrolu başarısız");
	}
	

}
function sil($p,$tablo){
	$id=$p["sil"];
	$ara=DB::baglan()->getir($tablo,array('id','=',$id))->sayac();
	if($ara<1)
		olumsuz('Id sorunu tespit edildi');
	else
		$sil=DB::baglan()->sil($tablo,array('id','=',$id));
	if($sil)
		olumlu();
	else
		olumsuz("Silme gerçekleşmedi");
}
function g($string){
	return htmlspecialchars_decode(rawurldecode(stripslashes($string)));
}
function trupper($text)
{
    $search=array("ç","i","ı","ğ","ö","ş","ü");
    $replace=array("Ç","İ","I","Ğ","Ö","Ş","Ü");
    $text=str_replace($search,$replace,$text);
    $text=strtoupper($text);
    return $text;
}
function multiselect($veri,$name,$placeholder){
	$donus.='<select data-placeholder="'.$placeholder.'" multiple name="'.$name.'[]" class="chosen-select-no-results" tabindex="11">';
	foreach($veri as $v){
		$donus.='<option value="'.$v.'">'.$v.'</option>';
	}
      
      $donus.='</select>';
	  echo $donus;
}
function starsrating($title,$yildizsayi,$name){
	$d.='<fieldset class="rating">';
	
	for($i=$yildizsayi;$i>-1;$i--){
		if($i==0){
$d.='<input type="radio" id="starhalf" name="'.$name.'" value="0.5" /><label class="half" for="starhalf" title="'.$title.' 0.5"></label>';			
		}else{
			if($i==$yildizsayi){
		$d.='<input type="radio" id="star'.$yildizsayi.'" name="'.$name.'" value="'.$yildizsayi.'" /><label class="full" for="star'.$yildizsayi.'" title="'.$title.' '.$yildizsayi.'"></label>';
			}else{
		$d.='<input type="radio" id="star'.$i.'half" name="'.$name.'" value="'.$i.'.5" /><label class="half" for="star'.$i.'half" title="'.$title.' '.$i.'.5"></label>';						
$d.='<input type="radio" id="star'.$i.'" name="'.$name.'" value="'.$i.'" /><label class = "full" for="star'.$i.'" title="'.$title.' '.$i.' "></label>';			
			}
	}
		}
		
	$d.='</fieldset>';
	echo $d;
}
function curlyolla($url,$no){
	$ch=curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
$response=curl_exec($ch);

$dom=new DOMDocument;
@$dom->loadHTML($response);
$tags=$dom->getElementsByTagName('input');
for($i=0;$i<$tags->length;$i++){
	$grab=$tags->item($i);
	if($grab->getAttribute('name')=='valcurrent'){
		$token=$grab->getAttribute('value');
	}
}
$data=array(
'valcurrent'=>$token,
'aboneno' =>$no
);
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
$response=curl_exec($ch);
return $response;
	
}
?>