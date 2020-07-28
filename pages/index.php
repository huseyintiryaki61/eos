<? require_once 'header.php' ?>
	<h2 class="text-center text-success mt-3">Çoklu Elektrik Sistemi </h2>
	<div class="row ">
	<a class="col-md-6 col-sm-12 col-12 mt-4" href="deneme.php"><div class="  text-center h3 p-5 rounded border border-primary">Fatura</div></a>
	<a class="col-md-6 col-sm-12 col-12 mt-4" href="rapor.php"><div class="  text-center h3 p-5 rounded border border-primary">Raporlar</div></a>
	<a class="col-md-6 col-sm-12 col-12 mt-4" href="kasa.php"><div class=" text-center h3 p-5 rounded border border-primary">Kasa</div></a>
	<a class="col-md-6 col-sm-12 col-12 mt-4" href="yedek.php"><div class="text-center h3 p-5 rounded border border-primary">Yedekle</div></a>
	<div class="col-md-6 mt-4">
	<form id="gayar">
  <div class="form-group mt-2">
    <label for="exampleInputPassword1">Sistem Yedekleme Saati</label>
    <input type="time" name="saat" class="form-control col-md-4" id="exampleInputPassword1" value="<?=$ayar->saat?>" >
  <small>Belirlenen saatte sistem açık olmazsa yedekleme gerçekleştirilmez...</small>
  </div>
	</div>
	<div class="col-md-3 mt-4">
	<div class="form-group">
	<label for="exampleInputPassword1">Hizmet Bedeli</label>
	<input type="number" name="komisyon" class="form-control col-md-6" step="0.01" value="<?=$ayar->komisyon?>" />
	</div>
	<input type="hidden" name="ayar" />
	
	</div>
	<div class="col-md-3 mt-4">
	<div class="form-group">
	<label for="exampleInputPassword1">Kart Komisyonu</label>
	<input type="number" name="kredi" class="form-control col-md-6" step="0.01" value="<?=$ayar->kredi?>" />
	</div>
	</div>
	</form>
	<button id="ayar" class="btn btn-primary mx-auto mt-2">Ayarları kaydet</button>
	</div>

		

	
	<!-- /içerik son -->
<? require_once 'footer.php'; ?>
<script type="text/javascript">
$("#ayar").click(function(){
Swal.fire({
  title: 'Ayarlar değiştiriliyor',
  text: "Yapılan değişiklikler kaydedilecek",
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Değiştir!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {
	  	var form = $('#gayar')[0];
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
  text: datalar.message,
  showConfirmButton:false
});
if(datalar.status=='success'){
	setTimeout(function(){window.location.reload();},3000);
}
},error:function(){
	Swal.fire({
  type: "error",
  title: "Hata oluştu",
  text: "İşlem başarısız",
  showConfirmButton:false
});
}		
			
});
  }
})
});
</script>