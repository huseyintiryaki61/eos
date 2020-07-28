<? require_once 'init.php';
session_start();
ob_start(); 
date_default_timezone_set('Europe/Istanbul');
$day=date("Y-m-d");
$buadet=DB::baglan()->ilanara("SELECT count(*) AS adet,sum(fatura_tutar) AS tutar FROM faturaler WHERE fatura_zaman BETWEEN '".$day." 00:00:00' AND '".$day." 23:59:59 '");
if(!$_SESSION["giris"]){
	header("Location:login.php");
}else{
	$ayar=DB::baglan()->getir("admin",array("Id",'=',$_SESSION["giris"]))->ilk();
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Elektrik Ödeme Sistemi</title>
	<link rel="stylesheet" href="../dist/css/bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
<link rel="stylesheet" href="../dist/css/sweetalert2.css" />

	<link rel="stylesheet" href="../dist/css/custom.css" />
	<link rel="Shortcut Icon"  href="../logo.png"  type="image/x-icon">

	</head>
<body >

<section class="mb-5 pt-3 pl-2 pr-2" id="header">
<div class="container">
<div class="row ">
<div class="col-md-4 col-12 col-sm-12"><div class="img-fluid">
<a href="index.php"><img height="100" width="100" src="../logo.png" alt="" /></a>
            <p style="color:red;font-size:20px;;z-index:222;position:absolute;" id="demo"></p>

</div>
</div>
<div class="col-md-4 col-12 col-sm-12"><h3 class="text-primary text-center">Bugün Ödenen</h3><h3 class="text-center"><span class="text-warning"><?=$buadet[0]->adet?> Fatura</span> | <span class="text-danger"><?=(float) $buadet[0]->tutar?> ₺</span> </h3></div>
<div class="col-md-4 col-12 col-sm-12 text-center">
<a href="sepet.php"><i class="fas fa-shopping-basket cart-icon"></i><span class="badge badge-danger cart-count"><?if(count($_SESSION["fatura"])>0){echo count($_SESSION["fatura"]); }?></span></a>
</div>

</div>
</div>
</section>

<!-- içerik  -->
	<div class="container-fluid ">
	<div class="row">
	<div class="col-md-3 pb-5">
	<ul class="mt-4" >
	<a href="deneme.php"><li>Fatura</li></a>
	<a href="sorgula.php"><li>Sorgula</li></a>
	<a href="kasa.php"><li>Kasa</li></a>
	<a href="rapor.php"><li>Raporlar</li></a>
	<a href="yedek.php"><li>Yedekleme</li></a>
	<a href="destek.php"><li>Destek</li></a>
	<a href="logout.php"><li class="bg-danger">Çıkış</li></a>
	
	</ul>
	</div>
		<div class="col-md-9 col-sm-12">
	<div class="container ">
	<!-- header son -->