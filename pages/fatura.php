<? require_once 'header.php'; ?>

	<h2 class=" text-success mt-3">Fatura Ekle </h2>

<form class="mt-5" id="fatura">

<div class="col-md-12">
	<div class="form-group col-md-5">
       <label>Hesap NO</label>
    <input class="form-control"  type="text"   required="" name="fatura_no" placeholder="Abone No Girin" autocomplete="off">
            </div></div>
			
			<div class="col-md-12">
	<div class="form-group col-md-5">
       <label>Fatura Adı</label>
    <input class="form-control" type="text" required=""  name="fatura_ad" placeholder="Fatura adını girin" autocomplete="off">
            </div></div>
			<div class="col-md-12">
			<div class="row">
			<div class="col-md-3 col-sm-6 col-6">
	<div class="form-group col-md-12">
       <label>Fatura Tutarı</label>
    <input class="form-control" type="number" min=0 step=0.01  required="" name="fatura_tutar" >
            </div></div>
			<div class="col-md-2 col-sm-6 col-6">
	<div class="form-group col-md-12">
       <label>Komisyon</label>
    <input class="form-control" type="text"  value="<?=$ayar->komisyon?> ₺" required="" name="fatura_kar" readonly>
            </div></div>
			
			</div>
			</div>
			
			
			<div class="col-md-12">
	<div class="form-group col-md-5">
       <label>Fatura Son Ödeme</label>
<input type="text" name="fatura_sonodeme" value="<?php echo date("d/m/Y") ?>" class="form-control" data-inputmask="'mask': '99/99/9999'">            </div></div>
	</form>		
		<div class="col-md-12">
	<div class="form-group col-md-5" >
    <button style="width:100%" class="btn btn-success"  id="faturakaydet" >Sepete ekle</button>
            </div></div>

<? require_once 'footer.php'; ?>
<script src="../dist/js/inputfilter.js"></script>
<script type="text/javascript">
$("#faturakaydet").click(function(){
	var ad=$("input[name='fatura_ad']").val();
	var no=$("input[name='fatura_no']").val();
	var tutar=$("input[name='fatura_tutar']").val();
	if(ad == '' || no ==''){
		Swal.fire({
			type:"warning",
			title:"Gerekli alanlar doldurulmadı"
		});
		
	}else{
Swal.fire({
  title: "Ad:<b class='text-danger'>"+ad+"</b>",
  text: 'Hesap No:'+no+' Tutar:'+tutar+' ₺',
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Ekle!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {
	  	var form = $('#fatura')[0];
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
  text: "Sepete Ekleniyor",
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
	setTimeout(function(){window.location.reload();},1000);
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
	}
});
</script>

<script type="text/javascript">
 var options = {allowNumeric: false, allowText: true, allowCustom:['ç','ş','ğ','ü','Ş','Ü','Ç','Ö','Ğ','ö','i','İ',' ','.',',']}
    $("input[name='fatura_no']").inputfilter({
		allowNumeric:true,
		allowText:false
	});
    $("input[name='fatura_ad']").inputfilter(options);
</script>