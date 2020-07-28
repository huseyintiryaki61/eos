<? require_once 'header.php'; 
$ys=json_decode(json_encode($_SESSION["fatura"]), True);
$s=json_decode(json_encode($ys), False);
if(count($_SESSION["fatura"])==0){
	unset($_SESSION["fatura"]);
}

?>
<head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/sl-1.3.1/datatables.min.css"/>
</head>
<input type="hidden" id="kredi" value="<?=$ayar->kredi?>" />
<input type="hidden" id="fasayi" value="<?=count($_SESSION["fatura"])?>" />
<h2 class="text-center">Sepet</h2>
<div class="row">
<div class="col-md-10"><table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Ad Soyad</th>
                <th>Hesap No</th>
                <th>S.Ödeme</th>
                <th>Tutar</th>
                <th>H.Bedeli</th>
                <th></th>
            </tr>
        </thead>
		<tbody>
		<? if(count($_SESSION["fatura"])>0){
		$gtutar=0;
		foreach($s as $v){
		$gtutar+=$v[0]->tutar+$v[0]->kar;
		?>
		<tr>
                <td><?=$v[0]->ad?></td>
                <td><?=$v[0]->no?></td>
                <td><?=$v[0]->sonodeme?></td>
                <td><?=$v[0]->tutar?></td>
                <td><?=$v[0]->kar?></td>
                <td><input type="checkbox" name="sec" value="<?=$v[0]->sira?>"/></td>
		</tr>
		<? $x++; }}?>
		</tbody>
		</table></div>
<div class="col-md-2 order-first order-md-last">
<button class="btn btn-outline-info pt-2 pl-2 mt-5 bosalt">Sepeti Boşalt</button>
<button class="btn btn-outline-danger p-2 mt-2 cikar">Sepetten Çıkar</button>
</div>
</div>
		<hr />
		<div style="display:<? 
		$try=count($_SESSION["fatura"]);
		if($try< 1){ echo "none"; }else{ echo "block"; } ?>;" class="col-md-12">
		
		<div class="custom-control custom-radio custom-control-inline">
  <input type="radio" id="customRadioInline1" name="otur" value="0" class="custom-control-input">
  <label class="custom-control-label" for="customRadioInline1">NAKİT</label>
</div>
<div class="custom-control custom-radio custom-control-inline">
  <input type="radio" id="customRadioInline2" name="otur" value="1" class="custom-control-input">
  <label class="custom-control-label" for="customRadioInline2">KREDİ KARTI</label>
</div>
<p  class="text-primary font-weight-bold">Tutar: <span id="genelt"><?=$gtutar?></span> ₺</p>
		<div class="col-md-6 "><button class="btn btn-success col-md-12 yazdir">Öde ve Yazdır</button></div>
		</div>
<? require_once 'footer.php'; ?>
<script src="../dist/js/datatables.js"></script>
<script  src="../dist/js/print.js"></script>
<script src="../dist/js/barkod.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        "scrollY":        "200px",
        "scrollCollapse": true,
        "paging":         false
    });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
	$("input[name='otur']:first").prop("checked", true);
	var gtut=$("#genelt").text();
	var kkos=$("#kredi").val();
		var yenit=parseFloat(gtut)+parseFloat(gtut*(kkos/100));
	$("input[name='otur']").change(function(){
		if($(this).val()==1){
			$("#genelt").text(yenit.toFixed(2));
		}else if($(this).val()==0){
			$("#genelt").text(gtut);
		}
	});
});
</script>
<script type="text/javascript">
$(".bosalt").click(function(){
	Swal.fire({
  title: 'Sepet temizlenecek',
  text: "Yapılan değişiklikler kaydedilecek",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Değiştir!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {
	$.ajax({
		type:"POST",
		url:"islem.php",
		data:{"hepsi":"1"},
		beforeSend: function() {
					Swal.fire({
  title: "Lütfen bekleyiniz",
  text: "Sepet Temizleniyor",
  imageUrl: 'https://media3.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif'
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
	window.location.reload();
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
});
});
</script>
<script type="text/javascript">
$(".cikar").click(function(){
Swal.fire({
  title: "Emin misiniz?",
  text: 'Seçili faturalar silinecek',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Ekle!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {
var se = "";
	var x=1;
	$("input[name='sec']:checked").each(function() {
		 se += $(this).val() + ",";
	});
	var secililer=se.slice(0,-1);
	
	if(secililer.length==0){
		Swal.fire({
  type: "error",
  title: "Hata",
  text: "Lütfen Fatura Seçin",
  showConfirmButton:false
});
	}else{
   		$.ajax({
				url:"islem.php",
				type:"POST",
				data:{"sil":secililer},
				beforeSend: function() {
					Swal.fire({
  title: "Lütfen bekleyiniz",
  text: "Siliniyor",
  imageUrl: 'https://media3.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif'
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
	setTimeout(function(){window.location.reload();},500);
}
},error:function(){
	Swal.fire({
  type: "error",
  title: "Hata oluştu",
  text: "İşlem başarısız",
  showConfirmButton:false
});}});
  }
}})
;});
</script>
<script type="text/javascript">
$(".yazdir").click(function(){
		  if($("#fasayi").val()<1){
		 	Swal.fire({
  type: "error",
  title: "Sepette Fatura Yok",
  text: "Lütfen sepete fatura ekleyin",
  showConfirmButton:false
}); 
	  }else{
Swal.fire({
  title: "Toplam: "+$("#genelt").text()+" ₺ ",
  text: 'Ödeme Gerçekleştirilecek mi?',
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Ekle!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {

   		$.ajax({
				url:"islem.php",
				type:"POST",
				data:{"kayit":<?=count($_SESSION["fatura"]);?>},
				beforeSend: function() {
					Swal.fire({
  title: "Lütfen bekleyiniz",
  text: "Kaydediliyor",
  imageUrl: 'https://media3.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif'
});     
    },success:function(response){
datalar=JSON.parse(response);
		Swal.fire({
  type: datalar.status,
  title: datalar.title,
  text: datalar.message,
  showConfirmButton:false
});
if(datalar.status="success"){
	var sayi=datalar.sayi;
	window.open("dekont.php?tane="+sayi+"","_blank");
	setTimeout(function(){window.location.reload()},600);
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
});
}
});
</script>

