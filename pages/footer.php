	</div>
	</div>
	</div>
	</div>
	<footer>
<section id="footer">
<div class="container-fluid  pt-5">
<div class="row">
<div class="col-md-6 mt-5 mx-auto">
</div>
<div class="col-md-12 mt-5 bg-dark">
<div class="mt-1 col-md-6 col-lg-4 col-sm-8 mx-auto copyright " 
> <span class="text-white">&copy; Copyright - <script type="text/javascript">
var tarih=new Date();
	var yil=tarih.getFullYear();
	document.write(yil);
</script>  </span>
<a style="padding-left:10px;" href="https://www.facebook.com/profile.php?id=100010413432746"> YST Company-Hüseyin Tiryaki</a>
</div>
</div>
</div>
</div>
</section>

<input type="hidden" class="yedeksaati" id="<?=$ayar->saat?>" />
  </footer>
  <script src="../dist/js/jquery.js"></script>
  <script src="../fonksiyon/fonksiyon.js"></script>
  <script src="../dist/js/sweetalert2.js"></script>
		<script>
		var myVar = setInterval(myTimer, 1000);
function myTimer() {
  var d = new Date();
  document.getElementById("demo").innerHTML = d.toLocaleTimeString();
}
function gunlukyedek(){
			var yedeksaati=document.querySelector(".yedeksaati").getAttribute('id');
var zaman=document.getElementById("demo").innerHTML;
		var yeni=yedeksaati+":00";
	if(yeni == zaman){
 $.ajax({
				type:"POST",
				url:"yedekle.php",
				data:"veri",
			});	
}	
}
var calis = setInterval(gunlukyedek,1000);
</script>