<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="res/favicon.ico">
		
		<title>{TITLE}</title>
		
		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom styles for this template -->
		<link href="css/starter-template.css" rel="stylesheet">
			
		<!-- SendPulse
		<script charset="UTF-8" src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/eded9601f5e3de62adfa863d705cc1a0_0.js" async></script> -->
	</head>
	
	<body>
		
		<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
			<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<a class="navbar-brand" href="{SITEPATH}">Главная</a>
			
			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">
					{GROUPS}
				</ul>
				<a class="navbar-brand" href="index.php?action=logout">Выход</a>
				<!--<form class="form-inline my-2 my-lg-0">
					<input class="form-control mr-sm-2" type="text" placeholder="Поиск">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Найти</button>
				</form>-->
			</div>
		</nav>
		
		<div class="container">	
			{PAGEDATA}
		</div><!-- /.container -->
		
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-3.1.1.js"></script>
		<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- Подключения скрипта control-modal.min.js к странице -->
		<script src="js/control-modal.js"></script>
		<script>		
			var myModal = new ModalApp.ModalProcess({ id: 'myModal', title: '' });
			myModal.init();
			function SelectData(name, title, ref)
			{			
				myModal.changeTitle('Данные загружаются...');
				myModal.changeBody('<img src="res/anim_laoding.gif" class="rounded mx-auto d-block">');
				myModal.showModal();
				$.get('data.php?action=datalist&ref=' + ref, function(data) {
					myModal.changeTitle(title);
					myModal.changeBody(data);
					
					$('#search_in_datalist').keyup(function(){
						_this = this;						
						$.each($('.datalist_item'), function() {
							if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) == -1) {
								$(this).hide();
								} else {
								$(this).show();                
							};
						});						
					});
					$('.datalist_item').click(function(){
						description = $(this).text();
						ref = $(this).attr("value");	
						$('input[name=vie_'+name+']').val(description);
						$('input[name=val_'+name+']').val(ref);
						myModal.hideModal();
					});
				});
			}
			function SelectMultiData(name, title, ref)
			{			
				myModal.changeTitle('Данные загружаются...');
				myModal.changeBody('<img src="res/anim_laoding.gif" class="rounded mx-auto d-block">');
				myModal.showModal();
				$.get('data.php?action=multidatalist&ref=' + ref, function(data) {
					myModal.changeTitle(title);
					myModal.changeBody(data);
					
					$("#mmu_1").attr("onclick","accept_multi_elems(\""+name+"\");");
					
					var curWorkVal = $("input[name=val_"+name+"]").val();
					
					var curWorkVal_array = curWorkVal.split(",");
					
					var curMultiElemsCount = parseInt($("#mmu_00").val());
					
					
					
					for (var i=0;i<curMultiElemsCount;i++){
						var work_i = i+2;
						for (var ii=0;ii<curWorkVal_array.length;ii++){
							valCortage_array = curWorkVal_array[ii].split(":");
							
							if (valCortage_array.length > 1){
								if (valCortage_array[0] == $("#code_mmu_"+work_i.toString()).val()){
									$("#mmu_"+work_i.toString()).html( valCortage_array[1] );
									break;
								}
							
								
							}
							
						
						}

					}					
					
					
					
					
					
				});
			}
			
			function increase_element_count(p){
				
				
				var cur_num = parseInt( $("#mmu_"+p.toString() ).html() );
				cur_num++;
				$("#mmu_"+p.toString() ).html(cur_num.toString() );
				
			}
			
			function decrease_element_count(p){
				
				
				var cur_num = parseInt( $("#mmu_"+p.toString()).html() );
				if (cur_num > 0){
					cur_num--;
					$("#mmu_"+p.toString() ).html(cur_num.toString() );
				}
				
			}
			
			function clear_multi_elems(elems_cnt){
				
				var i = 0;
				for (i = 0;i<elems_cnt;i++){
					var cur_i = i+2;
					$("#mmu_"+cur_i.toString() ).html("0");
				}
				
			}
			
			function accept_multi_elems(name){
				var curMultiElemsCount = parseInt($("#mmu_00").val());
				var totalStr = "";
				var viewTotalStr = "";
				for (var i=0;i<curMultiElemsCount;i++){
					var work_i = i+2;
					if ($("#mmu_"+work_i.toString()).html() != "0"){
						totalStr += ","+$("#code_mmu_"+work_i.toString()).val()+":"+$("#mmu_"+work_i.toString()).html();
						viewTotalStr += ", "+$("#odines_name_mmu_"+work_i.toString()).val()+": "+$("#mmu_"+work_i.toString()).html();
					}
				}
				if (totalStr.length >=1)
					totalStr = totalStr.substring(1);
				if (viewTotalStr.length >=2)
					viewTotalStr = viewTotalStr.substring(2);
				
				
				
				$('input[name=vie_'+name+']').val(viewTotalStr);
				$('input[name=val_'+name+']').val(totalStr);
				myModal.hideModal();
				
			}
			
			
		</script>
		
		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<script src="js/ie10-viewport-bug-workaround.js"></script>
	</body>
</html>
