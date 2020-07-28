<? require_once 'header.php'; 
?>
<head>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" />
</head>
<h2 class="text-center text-danger">Raporlar</h2>
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
<button class="col-md-3 justify-content-center p-5 goster"><h3><b class="text-primary">Raporla</b></h3></button>

<div class="col-md-12 mt-3">
<table id="example" class="display" style="width:100%">
<thead>
<tr>
<th>İsim</th>
<th>Hesap no</th>
<th>Tutar</th>
<th>Son ödeme</th>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<tbody>
<? $fcek=DB::baglan()->tum('faturaler','id','DESC'); 
foreach($fcek as $f){
?>
<tr>
<td><?=$f->fatura_ad ?></td>
<td><?=$f->fatura_no ?></td>
<td><?=$f->fatura_tutar ?></td>
<td><?=$f->fatura_sonodeme ?></td>
<td><button id="<?=$f->id ?>" onclick="yazdir(<?=$f->id?>)" class="btn btn-success">Yazdir</button></td>
<td><button id="<?=$f->id ?>" onclick="duzenle('<?=$f->id?>','<?=$f->fatura_ad?>','<?=$f->fatura_no?>','<?=$f->fatura_tutar?>','<?=$f->fatura_sonodeme?>')" class="btn btn-primary">Düzenle</button></td>
<td><button id="<?=$f->id ?>" onclick="sil(<?=$f->id?>)" class="btn btn-danger">Sil</button></td>
</tr>
<? } ?>
</tbody>
</table>
</div>
<? require_once 'footer.php'; ?>
<script  src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable({
		"ordering": false,
        "info":     false
	});
} );
</script>
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
				data:{"rapor":sec,"deger":deger,"deger2":deger2},
			success:function(response){
$("tbody").html("");
$("tbody").append(response);
$(".dataTables_paginate").hide();
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
<script type="text/javascript">
function sil(a){
	var id=a;
	Swal.fire({
		type:"warning",
		title:"Emin misin",
		text:"Fatura Silinecek",
		showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Sil!',
  cancelButtonText: 'İptal!'
	}).then((result)=>{
		 if (result.value) {
		$.ajax({
			type:"post",
			url:"islem.php",
			data:{"fsil":id},
			success:function(response){
				g=JSON.parse(response);
				Swal.fire({
					type:g.status,
					title:g.title,
					text:g.message
				});
				if(g.status=="success"){
					window.location.reload();
				}
				
			},
			error:function(){
				Swal.fire({
  type: "error",
  title: "Hata oluştu",
  text: "İşlem başarısız",
  showConfirmButton:false
});
			}
		})
		 }
	});
}
</script>
<script type="text/javascript">
function yazdir(a){
	window.open('dekont.php?id='+a, '_blank');
}
function duzenle(a,ad,no,tutar,son){
Swal.mixin({
  input: 'text',
  confirmButtonText: 'Next &rarr;',
  showCancelButton: true,
  progressSteps: ['1', '2', '3','4']
}).queue([
  {
    title: 'Fatura Adi',
	inputValue:ad
  },
  {
	 title: 'Hesap Numarası',
	inputValue:no 
  },
  {
	 title: 'Tutar',
	inputValue:tutar   
  },
  {
	 title: 'Son Ödeme',
	inputValue:son    
  }
]).then((result) => {
  if (result.value) {
	var yad=result.value[0];
	var yno=result.value[1];
	var ytut=result.value[2];
	var yson=result.value[3];
  $.ajax({
	  type:"post",
	  url:"islem.php",
	  data:{"duzen":"duzen","id":a,"ad":yad,"no":yno,"tutar":ytut,"son":yson},
	  success:function(response){
		  g=JSON.parse(response);
				Swal.fire({
					type:g.status,
					title:g.title,
					text:g.message
				});
				if(g.status=="success"){
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
})	
}
	
</script>