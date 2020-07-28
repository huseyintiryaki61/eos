<? require_once 'header.php' ?>

<form id="sorgula" action="islem.php?a=odemelisin" method="post" class="mt-5" >
<div class="col-md-12 text-center">
<div class="col-md-12">
	<div class="form-group col-md-5">
       <label>Hesap NO</label>
    <input class="form-control" type="text"   required="" name="no" placeholder="Abone No Girin" autocomplete="off">        
		   </div></div>

</div>
<input type="submit" />
</form>
<button type="button" id="sorgu">Sorgula</button>
<? require_once 'footer.php' ?>
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
			url:"islem.php?a=odemelisin",
				type:"POST",
			data:formData,
			   processData: false,
    contentType: false,
				success:function(response){
	//a=JSON.parse(response);
	console.log(response);
	},error:function(){
		alert("hata");
	}
		});
	}
});
</script>

