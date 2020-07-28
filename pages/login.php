<? 
require_once 'init.php';
if(isset($_SESSION["giris"])){
	header("Location:index.php");
} 
echo $_SESSION["giris"]; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EFKY Fatura kaydetme</title>

  <!-- BOOTSTRAP STYLES-->
  <link href="../dist/css/bootstrap.css" rel="stylesheet" />
  <!-- FONTAWESOME STYLES-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />

  <!-- GOOGLE FONTS-->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

  <style>
  body{
	  background-image:url("../logo.png");
	  background-attachment:fixed;
	  	  -webkit-background-size:100% 100%;

	  background-size:100% 100%;
  }
  
  </style>
</head>
<body >
    <div class="container">
        <div class="row text-center " style="padding-top:100px;">
           
        </div>
        <div class="row ">

            <div class=" shadow-lg bg-warning col-md-4 offset-md-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">

                <div style=" opacity: 0.75; margin-top:40px" class="panel-body">
                    <form id="giris" >
                        <hr />
                        <center><h3>ADMİN GİRİŞİ</h3></center>
                        <br />
						
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
                            <input type="text" class="form-control" name="admin_kadi" placeholder="Your Username " />
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"  ></i></span>
                            <input type="password" class="form-control"  name="admin_sifre" placeholder="Your Password" />
                        </div>
						<input type="hidden" name="login" />
                 </form> 
				 <button style="width:100%"  name="loggin" class="btn btn-primary">Giriş Yap</button>
<hr />
				 <? require_once 'footer.php'; ?>
<script type="text/javascript">
$("button[name='loggin']").click(function(){
	   		var form = $('#giris')[0];
			var formData = new FormData(form);
			$.ajax({
				url:"islem.php",
				type:"POST",
			data:formData,
			   processData: false,
    contentType: false,
				beforeSend: function() {
					Swal.fire({
  title: "Lütfen bekleyiniz",
  text: "Ayarlar değiştiriliyor",
  imageUrl: 'https://media3.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif',
});     
    },success:function(response){
datalar=JSON.parse(response);
		Swal.fire({
  type: datalar.status,
  title: datalar.title,
  text: datalar.message
});
if(datalar.status=="success"){
	location.href="index.php";
}
},error:function(){
	Swal.fire({
  type: "error",
  title: "Hata oluştu",
  text: "Lütfen tekrar deneyiniz",
  showConfirmButton:false
});
}		
			
});
});
</script>
