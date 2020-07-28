function sepetkac(){
		$.ajax({
			type:"post",
			url:"islem.php?a=sepetkac",
			data:{"so":"1"},
			success:function(e){
				var c = e.match(/\d/g);
				c = c.join("");
				if(c>0){
					$("a span.badge-danger").html(c);
				}
				
			},error:function(){
				
			}
		});
	}
function sil(a,urlphp){
	var id=a;
	Swal.fire({
		type:"warning",
		title:"Emin misin",
		text:"Silme işlemi başlasın mı",
		showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Sil!',
  cancelButtonText: 'İptal!'
	}).then((result)=>{
		 if (result.value) {
		$.ajax({
			type:"post",
			url:urlphp,
			data:{sil:id},
			success:function(response){
				g=JSON.parse(response);
				Swal.fire({
					type:g.status,
					title:g.title,
					text:g.message
				});
				if(g.status=="success"){
					setTimeout(function(){window.location.reload();},500);
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
function secilisil(name,url){
	Swal.fire({
  title: "Emin misiniz?",
  text: 'Seçililer  silinecek',
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
	$("input[name='"+name+"']:checked").each(function() {
		 se += $(this).val() + ",";
	});
	var secililer=se.slice(0,-1);
	
	if(secililer.length==0){
		Swal.fire({
  type: "error",
  title: "Hata",
  text: "Lütfen Seçim Yapın",
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
}});
}
function ekle(formad,urlphp){
	Swal.fire({
  title: "Emin misiniz?",
  text: 'Ekleme işlemi yapılsın mı?',
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Ekle!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {
	  	var form = $("#"+formad)[0];
			var formData = new FormData(form);
   		$.ajax({
				url:urlphp,
				type:"POST",
			data:formData,
			processData: false,
			contentType: false,
				beforeSend: function() {
					Swal.fire({
  title: "Lütfen bekleyiniz",
  text: "İşlem sürdürülüyor",
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
	setTimeout(function(){window.location.reload();},500);
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
function duzelt(formad,urlphp,id){
		Swal.fire({
  title: "Emin misiniz?",
  text: 'Düzeltme işlemi yapılsın mı?',
  type: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Evet Düzelt!',
  cancelButtonText: 'İptal!'
}).then((result) => {
  if (result.value) {
	  	var form = $("#"+formad)[0];
			var formData = new FormData(form);
			formData.append('id',id);
   		$.ajax({
				url:urlphp,
				type:"POST",
			data:formData,
			   processData: false,
    contentType: false,
				beforeSend: function() {
					Swal.fire({
  title: "Lütfen bekleyiniz",
  text: "İşlem sürdürülüyor",
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
	setTimeout(function(){window.location.reload();},500);
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
function gonder(formad,urlphp){
		var form = $("#"+formad)[0];
			var formData = new FormData(form);
	$.ajax({
				url:urlphp,
				type:"POST",
			data:formData,
			   processData: false,
    contentType: false,
success:function(response){
datalar=JSON.parse(response);

if(datalar.status=="success"){
	location.reload();
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