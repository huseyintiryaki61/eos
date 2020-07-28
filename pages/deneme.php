<? require_once 'header.php';
 ?>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title></title>
	<style type="text/css">
	form[name='for'] input::-webkit-outer-spin-button,
 form[name='for'] input::-webkit-inner-spin-button {
    /* display: none; <- Crashes Chrome on hover */
    -webkit-appearance: none;
    margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
	
}

form[name='for'] input[type=number] {
    -moz-appearance:textfield; /* Firefox */
	text-align: right; 
}
	</style>
</head>
<body>
<div class="row">
	<div class="col-md-12">
	<form name="for" action="islem.php" class="float-left col-md-6" method="post">
	<input type="number" style="width:100%;height:40px;" name="no" />
	</form>
	<button class="btn btn-primary col-md-4" id="fsorgula">Sorgula</button>
	
	<table  class="table col-md-10 mt-2 mx-auto table-borderless ">
	<thead>
	<tr>
	<th></th>
	<th style="font-size:20px;text-align:center;">İsim</th>
	<th style="font-size:20px;text-align:center;">Hesap No</th>
	<th style="font-size:20px;text-align:center;">Tutar</th>
	<th style="font-size:20px;text-align:center;">Son ödeme</th>
	</tr>
	</thead>
	
	
	</table>
	<button   class="btn btn-success p-3 float-right mr-5 sepet">Sepete Ekle</button>
	</div>
	
	<div class="sonuc"></div>
	</div>
	<? require_once 'footer.php' ?>
	<script type="text/javascript">
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      document.getElementById("fsorgula").click();
    }
  });
}); 
	function sepetaktif(th){
	var a=0;
	var ts=0;
	var tt=0;
	var b=0;
	$("tbody input[name='fid']").each(function(){
		tt+= parseFloat($(this).parents("tr").find("input[name='fatura_tutar']").val());
		ts++;
		var x=$(this).is(":checked");
		if(x==false){
			$(".sepet").prop('disabled',true);
		}else{
			a+= parseFloat($(this).parents("tr").find("input[name='fatura_tutar']").val());
			b++;
		}
		});
		console.log(tt+":"+ts+":"+a);
		if(b==0){
			$("tfoot td").text(ts+" fatura - Toplam Tutar: "+tt+" ₺");
		}else{
		$("tfoot td").text("Seçili:"+b+" fatura - Toplam: "+a.toFixed(2));	
		}
		
	var x=$("tbody input[name='fid'] ").is(":checked");
		if(x==false){
			$(".sepet").prop('disabled',true);
		}else{
			$(".sepet").prop('disabled',false);
		}
}
	setInterval(function(){
	if(document.querySelector("input[name='fatura_tutar']")){
		$("input[name='fatura_tutar']").each(function() {
			var a=$(this).val();
	var b=a.replace(",",".");
	$(this).val(b);
		});
	}
	},500);
	
	$("button.sepet").on("click",function(){
			var basarisiz,eklenen;
		eklenen=0;
		basarisiz=0;
		var x=$("tbody input[name='fid'] ").is(":checked");
		if(x==false){
			Swal.fire({
	type: 'warning',
	title: "Fatura seçin",
	showConfirmButton:false
	});
			return;
		}
		$("input[name='fid']:checked").each(function() {
			ust=$(this).parents("tr");
		 var ad=$(this).parents("tr").find("input[name='fatura_ad']").val();
		 var no=$(this).parents("tr").find("input[name='fatura_no']").val();
		 var son=$(this).parents("tr").find("input[name='fatura_sonodeme']").val();
		 var tutar=$(this).parents("tr").find("input[name='fatura_tutar']").val();
		
   		$.ajax({
			url:"islem.php",
			type:"POST",
			data:{"fatura_no":no,"fatura_ad":ad,"fatura_sonodeme":son,"fatura_tutar":tutar},
			async: false,
		success:function(response){
			
		e=JSON.parse(response);
		if(e.status='success'){
			ust.remove();
			eklenen++;
			sepetkac();
			
		}else{
			basarisiz++;
		}

},error:function(){
alert("bir hata oldu");
}
		}); 
	});
	Swal.fire({
	title: eklenen+" işlem başarılı",
	text: basarisiz+" işlem başarısız",
	showConfirmButton:false
	});
	});
	
	$(document).ready(function(){
		$("button.sepet").prop('disabled', true);
	});
	$("button.btn-primary").on("click",function(){
		$.ajax({
			type:"post",
			url:"islem.php",
			data:{"no":$("input[name='no']").val(),"sorguyap2":"1"},
			success:function(e){
				if(e == "Sorgu Başarısız"){
				Swal.fire({
				type: 'error',
				title: "Sorgu Yapılamadı",
				title: "Sistemsel bir hata oluştu",
				showConfirmButton:false
				});	
				}
				else if(e==null || e==""){
				Swal.fire({
				type:"error",
				title: "Borç Bulunamadı",
				text: "Abonenin borcu bulunamadı",
				showConfirmButton:false
				});
				}else{
				var btn = document.createElement("tbody");
			btn.innerHTML = e;  
			var c=$("table").append(btn);
			document.body.append(c);	
			setTimeout('toplamf()',1500);
				}
			
			},error:function(){
				alert("hata")
			}
		});
	});
	function toplamf(){
		var a=0;
		var b=0;
		$("input[name='fatura_tutar']").each(function() {
			a+= parseFloat($(this).val());
			b++;
		});
		var e ="<tfoot><tr class='text-center'><td style='font-weight:bold;' colspan='5'>"+b+" fatura Toplam: "+a.toFixed(2)+" </td></tr></tfoot>"
		var btn = document.createElement("tfoot");
			btn.innerHTML = e;  
			var c=$("table").append(btn);
			document.body.append(c);
	}
		
	
	</script>
</body>
</html>
