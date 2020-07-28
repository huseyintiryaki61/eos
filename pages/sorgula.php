<? 
require_once 'header.php';
date_default_timezone_set('Europe/Istanbul');
/*
$ch=curl_init("https://elektrikfaturaodeme.com/istanbul-elektrik-fatura-odeme/istanbul-avrupa-yakasi-elektrik-fatura-odeme");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_COOKIEJAR,'cookie.txt');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
$response=curl_exec($ch);
$no=1535625400;
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
curl_setopt($ch,CURLOPT_URL,"https://elektrikfaturaodeme.com/istanbul-elektrik-fatura-odeme/istanbul-avrupa-yakasi-elektrik-fatura-odeme");
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
$tutar=$html->find('.moreactive td[data-name="FaturaTutar"]',0)->plaintext;	
	$veri=explode(':',$ver);
$isim=trim($veri[3]);
$sayi=preg_replace('/\D/','',$veri);
echo $sayi[2]." ".$son." ".$tutar." ".$isim;
*/

?>

<form id="sorgula" class="mt-5" >
<div class="col-md-12 text-center">
<div class="col-md-12">
	<div class="form-group col-md-5">
       <label>Hesap NO</label>
    <input class="form-control" type="text"   required="" name="no" placeholder="Abone No Girin" autocomplete="off">
<input type="hidden" name="sorguyap" />           
		   </div></div>

</div>

</form>
<button type="button" id="sorgu">Sorgula</button>
<hr />
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
    <input class="form-control" type="text" step=0,01   required="" name="fatura_tutar" >
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
<input type="text" name="fatura_sonodeme"  class="form-control">            </div></div>
	</form>		
		<div class="col-md-12">
	<div class="form-group col-md-5" >
    <button style="width:100%" class="btn btn-success"  id="faturakaydet" >Sepete ekle</button>
            </div></div>

<? require_once 'footer.php'; ?>
<script src="../dist/js/inputfilter.js"></script>
<script>
setInterval(function(){
	var a=$("input[name='fatura_tutar']").val();
	var b=a.replace(",",".");
	$("input[name='fatura_tutar']").val(b);
},500);
</script>
<script type="text/javascript">
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      document.getElementById("sorgu").click();
    }
  });
});
</script>
<script type="text/javascript">
$("#sorgu").click(function(){
	var nu=$("input[name='no']").val();
	if(nu=='' || nu==null){
			Swal.fire({
			type:"warning",
			title:"Gerekli alanlar doldurulmadı"
		});
	}else{
			var form = $('#sorgula')[0];
			var formData = new FormData(form);
		$.ajax({
			url:"islem.php",
				type:"POST",
			data:formData,
			   processData: false,
    contentType: false,
				success:function(response){
	a=JSON.parse(response);
	console.log(response);
	console.log(a);
	$("input[name='fatura_sonodeme']").val(a.son);
	$("input[name='fatura_ad']").val(a.isim);
	$("input[name='fatura_tutar']").val(a.tutar);
	$("input[name='fatura_no']").val(a.no);
	},error:function(){
		alert("hata");
	}
		});
	}
});
</script>
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


