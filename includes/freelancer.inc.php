<?php
if(!isset($_SESSION['userType']) || !$_SESSION['userType'] == 'client') exit;
?>

<div class="container" id="register-container">
	<h1>List All Jobs</h1>
<!-- Propeller textbox -->
<link href="http://propeller.in/components/textfield/css/textfield.css" type="text/css" rel="stylesheet"/>
<link href="assets/css/select2.min.css" rel="stylesheet">
<link href="assets/css/select2-bootstrap.css" rel="stylesheet">
<link href="assets/css/pmd-select2.css" rel="stylesheet">
<!-- Select2 js-->
<script type="text/javascript" src="assets/js/select2.full.js"></script>
<script type="text/javascript" src="assets/js/pmd-select2.js"></script>
<script src="assets/js/bootstrap-datepicker.min.js"></script>

		<form method="POST" id="listAllJobsForm" action="index.php">
			
			<div class="form-group">
				<div class="col-md-8">
					<label>List by</label>
					<input type="search" class="form-control" name="search"  id="search" style="margin-bottom: 20px;">
					
				</div>
				<div class="col-md-2">
					<label>List by</label>
					<select class="form-control" name="List_By"  id="List_By">
						<option value="Price">Price</option>
						<option value="CreationDate">Creation Date</option>
					</select>
				</div>
				<div class="col-md-2">
					<label>Sort by</label>
					<select class="form-control" name="Sort_By" id="Sort_By">
						<option value="ASC">ASC</option>
						<option value="DESC">DESC</option>
					</select>
				</div>

			</div>
			<div class="clearfix"></div>
		</form>
		<div id = "BodyList">	
		</div>
</div>

<script>
	$( document ).ready(function() {
$("#listAllJobsForm").on('change load ready', function(){
	// alert("test");
	 $.ajax({
        url: "includes/ajax/showJob-backend.php",
        type: "GET",
        data: {
        	typeshow: 'specialShowall',
        	list: $("#List_By").val(),
        	sort: $("#Sort_By").val(),
        	handle: $("#search").val()
        } ,
        success: function (response) {
           $("#BodyList").html(response);        

        }
    });
});
$("#listAllJobsForm ").trigger("change");
});

/*
function getjob(typeshow){
	if (typeshow === 'showall' ){
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200)
			{document.getElementById("BodyList").innerHTML = xhr.responseText;}
		}			
		xhr.open("GET","includes/ajax/showJob-backend.php?typeshow="+ typeshow, "true");
		xhr.send();
	}else if(typeshow === 'specialShowall'){
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200)
			{document.getElementById("BodyList").innerHTML = xhr.responseText;} 								
		}			
		xhr.open("GET","includes/ajax/showJob-backend.php?typeshow="+ typeshow +"&list="+ document.getElementById('List_By').value+"&sort="+document.getElementById('Sort_By').value, "true");
		xhr.send();
	}else if(typeshow === 'searchShow' && document.getElementById('search').value != ""){

		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function(){
			if(xhr.readyState == 4 && xhr.status == 200)
			{document.getElementById("BodyList").innerHTML = xhr.responseText;} 	
		}			
		xhr.open("GET","includes/ajax/showJob-backend.php?typeshow="+ typeshow +"&handle="+ document.getElementById('search').value, "true");
		xhr.send();
	}	
}
*/
</script>
