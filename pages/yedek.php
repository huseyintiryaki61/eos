<? require_once 'header.php'; 
require 'class.php';
$backup = new Backup();
?>
<h2 class="text-center text-success">Yedekleme</h2>
	<div id="page-wrapper">
            <div id="page-inner">
	  <div class="row">
	
                    <div style="margin-bottom:20px;" class="col-md-12">
                  <div class="row">
				  <div  style="border:none; padding:10px; margin-left:15px; margin-right:40px; background-color:purple;cursor:pointer;" class="col-md-3 text-center" >
				  <form class="form" id="veri" action="">
	<input type="hidden" name="veri" />
	
	<h3 style="color:beige;">Veri yedeğini al</h3><i style="font-size:36px;" class="fas fa-file-invoice"></i>
	
	</form>
	</div>
		<div id="klasor" style="border:none; padding:10px; margin-right:40px; background-color:orange; cursor:pointer;" class="col-md-3 text-center">
	<form class="form" id="klasor" action="">
	<input type="hidden" name="klasor" />
	<h3 style="color:beige;">Klasör Yedeğini Al</h3><i style="font-size:36px;" class="fas fa-folder-plus"></i>
	
	
	</form></div>
		<div  style="border:none; padding:10px;cursor:pointer;" class="col-md-3 bg-primary text-center">

	<form class="form" id="komple" action="">
	<input type="hidden" name="komple" />
	<h3>Komple Yedek Al</h3><i style="font-size:36px;" class="fas fa-folder-plus"></i>
	
	
	</form></div>
				  </div>   
	
                    </div>
					
					
					</div>
                </div>
				  </div>
<? require_once 'footer.php'; ?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
				$(document).ready(function(e){

				$(".form").click(function(){
					var formad=$(this).attr("id");
				
				
			swal({
  title: "Emin misiniz?",
  text: formad+" yedeklemesi yapılmak üzere",
  icon: "info",
  buttons: true,
  dangerMode: true,
})
.then((basti) => {
  if (basti) {
	  const loadin=document.createElement('div');
		loadin.innerHTML="<i class='fa fa-spinner fa-5x'></i>";
		 swal({ 
		   title: 'İşlem yapılıyor',
          content: loadin,       
buttons: false	  
	  });
	  $.ajax({
				type:"POST",
				url:"yedekle.php",
				data:formad,
				success: function(data){
					veri=JSON.parse(data);	
		swal("İşlem Sonucu",veri.message,veri.status)
				}
			}); 
			} 
});
}); 	
});
				</script>