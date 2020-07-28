<? require_once 'init.php'; 
date_default_timezone_set('Europe/Istanbul');
session_start();
ob_start();
if(isset($_POST["ayar"])){
	$d=degerler($_POST);
	$ekle=DB::baglan()->guncelle('admin',$_SESSION["giris"],array(
	'komisyon' => $_POST["komisyon"],
	'saat' => $_POST["saat"],
	'kredi' => $_POST["kredi"]
	));
	if($ekle){
					$response['status']="success";
$response['title']="Başarılı";
$response['message']="Ayarlar değişti";
echo json_encode($response);
	}else{
	$response['status']="error";
$response['title']="Başarısız";
$response['message']="Ayarlar değişmedi";
echo json_encode($response);
	}		

	
}
if(isset($_POST["login"])){
	$ad=$_POST["admin_kadi"];
	$sifre=$_POST["admin_sifre"];
	$varad=DB::baglan()->getir("admin",array('admin_kadi','=',$ad))->sayac();
	if($varad<1){
		$response['status']="error";
$response['title']="Hata";
$response['message']="Kullanici bulunamadi";
echo json_encode($response);
	}else{
		$k=DB::baglan()->getir("admin",array('admin_kadi','=',$ad))->ilk();
		if($sifre != $k->admin_sifre){
					$response['status']="error";
$response['title']="Hata";
$response['message']="Yanlış şifre girildi";
echo json_encode($response);
		}else{
			$_SESSION["giris"]=$k->Id;
$response['status']="success";
$response['title']="Giriş Başarılı";
$response['message']="Lütfen bekleyiniz";
echo json_encode($response);
		}
	}
}
if(isset($_POST["fatura_sonodeme"])){
	$ka=$db->getir('admin',array('Id','=',$_SESSION["giris"]))->ilk();
$tarih=date("H:i:s");
if(!isset($_SESSION["fatura"])){
	$urun["no"]=$_POST["fatura_no"];
	$urun["kar"]=$ka->komisyon;
	$urun["sonodeme"]=$_POST["fatura_sonodeme"];
	$urun["ad"]=$_POST["fatura_ad"];
	$urun["tutar"]=$_POST["fatura_tutar"];
	$urun["zaman"]=date("d/m/Y H:i:s");
	$urun["sira"]=1;
	$sepet[] = $urun;
	$_SESSION["fatura"][1]=$sepet;
	$_SESSION["sayi"]=1;
}
else{
	$sayi=$_SESSION["sayi"]+1;
	$urun["no"]=$_POST["fatura_no"];
	$urun["kar"]=$ka->komisyon;
	$urun["sonodeme"]=$_POST["fatura_sonodeme"];
	$urun["ad"]=$_POST["fatura_ad"];
	$urun["tutar"]=$_POST["fatura_tutar"];
	$urun["zaman"]=date("d/m/Y H:i:s");
	$urun["sira"]=count($_SESSION["fatura"])+1;
	$sepet[] = $urun;
	$_SESSION["fatura"][$sayi]=$sepet;
$_SESSION["sayi"]++;
}
	

		$response['status']="success";
$response['title']="Sepete Eklendi";
$response['message']="Fatura sepete eklendi";
echo json_encode($response);
		
}
if(isset($_POST["hepsi"])){
	unset($_SESSION["fatura"]);
	unset($_SESSION["sayi"]);
$response['status']="success";
$response['title']="İşlem Başarılı";
$response['message']="Sepete Temizlendi";
echo json_encode($response);
}
if(isset($_POST["sil"])){
	$s=explode(",",$_POST["sil"]);
	foreach($s as $b){
		unset($_SESSION["fatura"][$b]);
	}
$response['status']="success";
$response['title']="Silindi";
$response['message']="Seçililer Silindi";
echo json_encode($response);
}
if(isset($_POST["kayit"])){
$ys=json_decode(json_encode($_SESSION["fatura"]), True);
$s=json_decode(json_encode($ys), False);
$ayar=DB::baglan()->getir("admin",array("Id",'=',$_SESSION["giris"]))->ilk();
$say=0;
$hata="";
$hsay=0;
foreach($s as $d){
	$toplam=($d[0]->tutar+$ayar->komisyon);
	$fekle=DB::baglan()->ekle('faturaler',array(
	'fatura_ad'=>$d[0]->ad,
	'fatura_no'=>$d[0]->no,
	'fatura_tutar'=>$toplam,
	'fatura_sonodeme'=>$d[0]->sonodeme,
	'fatura_kar'=>$ayar->komisyon
	));
	if($fekle){
		$r=$d[0]->sira;
		$say++;
		unset($_SESSION["fatura"][$r]);
	}else{
		$hata.=$d[0]->sira; 
		$hsay++;
	}
}
if($hsay>0){
		$response['status']="error";
$response['title']="İşlem Başarısız";
$response['message']=$hsay." Fatura Ödenemedi";
echo json_encode($response);
}else{
		$response['status']="success";
$response['title']="İşlem Başarılı";
$response['message']=$say." Fatura Ödendi ".$r;
$response['sayi']=$say;
echo json_encode($response);
}

}
if(isset($_POST["kasa"])){
	$a=$_POST["kasa"];
	$d=$_POST["deger"];
	if(!empty($_POST["deger2"])){
		$deger2=$_POST["deger2"];
	}
	if($a==1){
		$ara=DB::baglan()->ilanara("select count(*) AS adet,sum(fatura_tutar) AS tutar,sum(fatura_kar) as kar  from faturaler  WHERE fatura_zaman like '%".$d."%'");
if($ara[0]->adet ==0){
	$response['status']="error";
$response['title']="Fatura Yok";
$response['message']="Seçilen tarihte fatura ödenmemiş";
echo json_encode($response);
exit;
}
		$response['status']="success";
$response['adet']=$ara[0]->adet;
$response['tutar']=$ara[0]->tutar;
$response['kar']=$ara[0]->kar;
echo json_encode($response);
	}else if($a==2){
		$date = date('d-m-Y', strtotime("+1 day", strtotime($deger2)));
		$ara=DB::baglan()->ilanara("SELECT count(*) as adet, sum(fatura_tutar) as tutar,sum(fatura_kar) as kar
FROM `faturaler`
WHERE (fatura_zaman BETWEEN '".$d."'  AND '".$deger2."' )");
if($ara[0]->adet ==0){
	$response['status']="error";
$response['title']="Fatura Yok";
$response['message']="Seçilen tarihte fatura ödenmemiş";
echo json_encode($response);
exit;
}
$response['status']="success";
$response['adet']=$ara[0]->adet;
$response['tutar']=$ara[0]->tutar;
$response['kar']=$ara[0]->kar;
echo json_encode($response);
	}

}
if(isset($_POST["fsil"])){
	$varmi=DB::baglan()->getir('faturaler',array('id','=',$_POST["fsil"]))->sayac();
	if($varmi<1){
			$response['status']="error";
	$response['title']="İşlem başarılı";
	$response['message']="Bir hata oluştu.Sayfayı yenileyin";
	echo json_encode($response);
	exit;
	}
	$sil=DB::baglan()->sil("faturaler",array('id','=',$_POST["fsil"]));
	if($sil){
	$response['status']="success";
	$response['title']="İşlem başarılı";
	$response['message']="Fatura silindi";
	echo json_encode($response);	
	}else{
		$response['status']="error";
	$response['title']="İşlem başarısız";
	$response['message']="Fatura silinemedi";
	echo json_encode($response);
	}
	
}
if(isset($_POST["rapor"])){
	$d1=$_POST["deger"];
	$d2=$_POST["deger2"];
	if($d2){
		$ara=DB::baglan()->ilanara("select * from faturaler where  fatura_zaman between '$d1 00:00:00' AND '$d2 23:59:59' ");
	}else{
		$ara=DB::baglan()->ilanara("select * from faturaler where  fatura_zaman between '$d1 00:00:00' AND '$d1 23:59:59' ");
	}
	if(!$ara){
		echo "<h6 class='text-center'>Hiç bir veri bulunamadı</h6>";
	}
	
foreach($ara as $f){
$v.='<tr>
<td>'.$f->fatura_ad.'</td>
<td>'.$f->fatura_no .'</td>
<td>'.$f->fatura_tutar .'</td>
<td>'.$f->fatura_sonodeme.' </td>
<td><button id="'.$f->id.'" onclick="yazdir('.$f->id.')" class="btn btn-success">Yazdır</button></td>
<td><button id="'.$f->id.'" onclick="duzenle('.$f->id.')" class="btn btn-primary">Düzenle</button></td>
<td><button id="'.$f->id.'" onclick="sil('.$f->id.')" class="btn btn-danger">Sil</button></td>
</tr>';
} 
echo $v;
} 
if(isset($_POST["duzen"])){
	$ad=$_POST["ad"];
	$no=$_POST["no"];
	$tutar=$_POST["tutar"];
	$son=$_POST["son"];
	$duzenle=DB::baglan()->guncelle("faturaler",$_POST["id"],array(
	"fatura_ad" =>$ad,
	"fatura_no" =>$no,
	"fatura_tutar" =>$tutar,
	"fatura_sonodeme" =>$son
	));
	if($duzenle){
$response['status']="success";
$response['title']="Başarılı";
$response['message']="Fatura değişti";
echo json_encode($response);
	}else{
	$response['status']="error";
$response['title']=$ad.' '.$no;
$response['message']=$tutar.' '.$son;
echo json_encode($response);
	}	
}
if(isset($_POST["sorguyap"])){
$no=$_POST["no"];
$ch=curl_init("https://elektrikfaturaodeme.com/elektrik/istanbul-avrupa-yakasi-elektrik-fatura-odeme");
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
	if($grab->getAttribute('name')=='__RequestVerificationToken'){
		$token=$grab->getAttribute('value');
	}
}
$data=array(
'__RequestVerificationToken'=>$token,
'aboneno1' =>$no
);
curl_setopt($ch,CURLOPT_URL,"https://elektrikfaturaodeme.com/elektrik/istanbul-avrupa-yakasi-elektrik-fatura-odeme");
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
$response=curl_exec($ch);

require_once 'simple_html_dom.php';
$html=str_get_html($response);
$ver=$html->find('.alert-success ',0)->plaintext;		
$son=$html->find('.moreactive td[data-name="SonOdeme"]',0)->plaintext;
$tut=$html->find('.moreactive td[data-name="FaturaTutar"]',0)->plaintext;
$tutar=explode(" ",$tut);	
	$veri=explode(':',$ver);
$isim=trim($veri[3]);
$sayi=preg_replace('/\D/','',$veri);
$sonuc["isim"]=$isim;
$sonuc["no"]=$sayi[2];
$sonuc["tutar"]=$tutar[0];
$sonuc["son"]=$son;
echo json_encode($sonuc);
}
if(isset($_POST["sorguyap2"])){
		$no=$_POST["no"];
$ch=curl_init("https://elektrikfaturaodeme.com/elektrik/istanbul-avrupa-yakasi-elektrik-fatura-odeme");
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
	if($grab->getAttribute('name')=='__RequestVerificationToken'){
		$token=$grab->getAttribute('value');
	}
}
$data=array(
'__RequestVerificationToken'=>$token,
'aboneno1' =>$no
);
curl_setopt($ch,CURLOPT_URL,"https://elektrikfaturaodeme.com/elektrik/istanbul-avrupa-yakasi-elektrik-fatura-odeme");
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
$response=curl_exec($ch);

require_once 'simple_html_dom.php';
$html=str_get_html($response);
$title = $html->find('title',0)->plaintext;
if(g($title)=='Sorgu Başarısız'){
	echo "Sorgu Başarısız";
}else{
	$ver=$html->find('.alert-success ',0)->plaintext;	
$fb=$html->find('.moreactive tr');
	$veri=explode(':',$ver);
$isim=trim($veri[3]);
$sayi=preg_replace('/\D/','',$veri);
$x=0;
foreach($fb as $f){
	$son=$html->find('.moreactive td[data-name="SonOdeme"]',$x)->plaintext;
	$tut=$html->find('.moreactive td[data-name="FaturaTutar"]',$x)->plaintext;
	$tutar=explode(" ",$tut);
	$v.='<tr>
	<td><input onclick="sepetaktif(this)" type="checkbox" name="fid" /></td>
	<td><input type="text" name="fatura_ad"  style="border:none;text-align:center;" value="'.$isim.'" /></td>
	<td><input type="text" name="fatura_no"  style="border:none;text-align:center;" value="'.$sayi[2].'" /></td>
	<td><input type="text" step=0,01   name="fatura_tutar"  style="border:none;text-align:center;" value="'.$tutar[0].'" /></td>
	<td><input type="text" name="fatura_sonodeme"  style="border:none;text-align:center;" value="'.$son.'" /></td>
	</tr>';	
	$x++;
}	

echo $v;
}

}
if($_GET["a"]){
	if($_GET["a"]=="sepetkac"){
	$a.=count($_SESSION["fatura"]);
	echo $a;
	}
	if($_GET["a"]=="asd"){
	$sonuc["sayi"]=count($_SESSION["fatura"]);
	echo json_encode($sonuc);
	}
	
	
}
?>