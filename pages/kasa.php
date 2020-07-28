<? require_once 'header.php';
/* Getir veri Çekimi
$g=DB::baglan()->getir("admin",array("Id",">",0))->sonuc();
*/
?>
<h2 class="text-center">Kasa</h2>
<div class="row">
<div class="col-md-9 p2 shadow-lg">
<div class="col-md-10 mb-2 mt-4 ">

<div class="input-group">
      <input type="radio" id="sec1" name="sec" value="1" style="width:2em;
height:2em" aria-label="Radio button for following text input">
<label class="h4 ml-3 mr-3" for="">Tarih</label><input style="margin-left:77px;" id="tarih1" name="tarih" type="date" value="<?=date('Y-m-d')?>" />
</div>
</div>
<div class="col-md-7 ">
<div class="input-group">
      <input type="radio" name="sec" id="sec2" value="2" style="width:2em;
height:2em" aria-label="Radio button for following text input">
<label class="h4 ml-3 mr-3" for="">Tarih Aralığı</label><input name="tarih" id="tarih2" type="date" value="<?=date("Y-m-d", strtotime("+1 day"));?>" />
</div>

</div>
</div>
<button class="col-md-3 justify-content-center p-5 goster"><b class="text-danger">Kasayı Göster</b></button>
<hr />
<div class="col-md-12">
<table class="table mt-4">
  <thead class="bg-success">
            <tr>
                <th></th>
                <th>İşlem Adeti</th>
                <th>Toplam Tutar</th>
                <th>Kar</th>
               
                
            </tr>
        </thead>
		<tbody>
		<tr>
		<td></td>
		<td class="adet"></td>
		<td class="tutar"> </td>
		<td class="kar"></td>
		
		</tr>
		</tbody>
</table>
</div>
</div>
<? require_once 'footer.php'; ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#sec1").prop("checked", true);
	$("#tarih2").hide();
	
});
$("input[name='sec']").on("click",function(){
		if($(this).val()==2){
			$("#tarih2").show();
		}else{
				$("#tarih2").hide();
		}
	});
	$(".goster").click(function(){
		var sec=$("input:checked").val();
		if(sec==1){
			var deger=$("#tarih1").val();
			var deger2="";
		}else if(sec==2){ 
		var deger=$("#tarih1").val();
		var	deger2=$("#tarih2").val();
		}
				$.ajax({
				url:"islem.php",
				type:"POST",
				data:{"kasa":sec,"deger":deger,"deger2":deger2},
			success:function(response){
datalar=JSON.parse(response);
					var tutar=parseFloat(datalar.tutar).toFixed(2);
					
var adet=datalar.adet; var hbedel=$(".hbedel").text();
var kar=datalar.kar;
if(datalar.status=="success"){

$(".kar").text(kar+" ₺");
$(".tutar").text(tutar+" ₺");
$(".adet").text(adet);
}
else if(datalar.status=="error"){
	Swal.fire({
		type:"error",
		title:datalar.title,
		text:datalar.message
	});
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
	});
</script>