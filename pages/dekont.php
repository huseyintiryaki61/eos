<?php 
require_once 'init.php';
if(isset($_GET["tane"])){
	$ar=$_GET["tane"];
$dek=DB::baglan()->tumlimit('faturaler','id','DESC',$ar);
}else if($_GET["id"]){
	$id=$_GET["id"];
$dek=DB::baglan()->ilanara("select * from faturaler WHERE id=$id");	
}

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">	

		<title>Porto - Responsive HTML5 Template 4.9.2</title>	

		<meta name="keywords" content="HTML5 Template" />
		<meta name="description" content="Porto - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Favicon -->
		<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="img/apple-touch-icon.png">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">

		<script src="vendor/modernizr/modernizr.min.js"></script>

	</head>
<body onload="window.print();">
<? foreach($dek as $dcek){
	?>

<div style="margin-bottom:0px!important;" id="yazdirilacakBolge">

<span style="font-size:13px; font-family:calibri;display:block;margin-left:5px;"> <?php echo $dcek->fatura_zaman; ?></span>
<img style="float:left; margin-right:25px;" src="../5.png" alt="" />
<span style="font-size:18px;"><strong>ELEKTRİK  FATURA </strong></span><br /><br />
<span style="font-size:18px;"><strong>ÖDEME FİŞİ</strong></span><br /><br />
<div><span  style="font-size:16px; font-family:calibri;margin-bottom:5px;margin-left:5px; "><strong>HESAP NO:</strong><span class="hesapno"> <?php  echo $dcek->fatura_no; ?></span> </span></div>
<div><span style="font-size:16px; font-family:calibri;margin-bottom:5px;margin-left:5px;"><strong>İSİM:</strong> <?php  echo $dcek->fatura_ad; ?> </span></div>
<div><span style="font-size:16px; font-family:calibri;margin-bottom:5px;margin-left:5px;"> <strong>SON ÖDEME:</strong> <?php  echo $dcek->fatura_sonodeme; ?> </span></div>
<span style="font-size:18px; font-family:calibri;margin-left:5px;"><strong>TUTAR:</strong> <?php  echo $dcek->fatura_tutar; ?> TL</span>
<hr style="margin:0;border-style:dashed;" />
<p style="margin:0;font-size:11px;">Temsilci Adı:TİRYAKİ GIDA VE İNŞ.SAN <br />
Adres: ULUBATLI HASAN CAD.NO:33/A <br /> Yaptığınız Ödemeler Anlaşmalı Bankalar Güvencesinde Kurum Hesaplarına Aktarılmaktadır
</p>
</div>
<? } ?>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="../dist/js/barkod.js"></script>
<script>
setTimeout(function(){window.close()},1000);
$(".hesapno").each(function(){
	var hesapno=$(".hesapno").html();
	JsBarcode(".barcode2",hesapno,{
	displayValue:false,
	width:2,
	marginBottom:0,
	marginTop:0,
	height:30
	});
});

</script>
</body>
</html>