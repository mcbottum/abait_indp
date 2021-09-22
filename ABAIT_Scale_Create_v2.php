<?
include("ABAIT_function_file.php");
session_start();
if($_SESSION['passwordcheck']!='pass'){
	header("Location:logout.php");
	print $_SESSION['passwordcheck'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? print"<link rel='shortcut icon' href='$_SESSION[favicon]' type='image/x-icon'>";?>
<meta http-equiv="Content-Type" content="text/html;
	charset=utf-8" />
<title>
<?
print $_SESSION['SITE']
?>
</title>
<script type='text/javascript'>
function validate_form()
{
	valid=true;
	var alertstring=new String("");
	
	if(document.form1.Target_Population.value=="")
	{
		alertstring="\nTarget Population;";
		document.form1.Target_Population.style.background = "Yellow";
		valid=false;
	}else{
		document.form1.Target_Population.style.background = "white";
	}
	if(document.form1.scale_number.selectedIndex=="0"){
		alertstring=alertstring+"\nnumber of Scales.";
		document.form1.scale_number.style.background = "Yellow";
		valid=false;
	}
	if(valid==false){
		alert("Please enter the following data;" + alertstring);
	}
	return valid;
}
</script>
<?
set_css()
?>
<style>
	input[type="text"]{
		background-color: lightgreen;
	}
    .table th {
    background-color: lightgrey;
    }

</style>


</head>
<body class="container">

<?	
	$names = build_page_pg();
?>

<form 	name = 'form1'
		action = "ABAIT_Scale_Create_Log_v2.php"
		method = "post"
		onsubmit="return validate_form ( );">
<h2 class='m-3 p-2 footer_div' align='center'>ABAIT Scale Creation Page</h2>
<h4 align="center"><em>This page will allow the creation of Scales for new target populations.</em></h4>
<h4 align='center'><em>Existing Scale information will not be overwritten.</em></h4>

<table class='table center hover' border='1' bgcolor='white'>
<tr><th> Existing Target Populations</th>
<th> Existing Scales</th></tr>
<?
	$sql="SELECT * FROM scale_table";
	
	$conn=make_msqli_connection();

	$session=mysqli_query($conn,$sql);
	$Target_Population="";
while($row=mysqli_fetch_assoc($session)){
	print"<tr><td>";
	if($row['Target_Population']!=$Target_Population){
		print$row['Target_Population'];
		$Target_Population=$row['Target_Population'];
	}//end if
	print"</td>";
	print"<td>$row[scale_name]</td></tr>";
}//end while
?>
</table>
<p>
<table  class='table center hover' border='1' bgcolor='white'>
<tr><th>Please Enter the Following Information</th></tr>
<tr><td>New Target Population
<input type = 'text' width='7' name =Target_Population size='50'/>
</td></tr>
<tr><td>

			<div class='form-group '>
				<label for='scale_number'>Number of Scales (4 recommended)</label>
				<select class='form-control w-auto'  name = 'scale_number' id = 'scale_number'>
					<option value = "1">1</option>
					<option value = "2">2</option>
					<option value = "3">3</option>
					<option value = "4">4</option>
					<option value = "5">5</option>
					<option value = "6">6</option>
				</select>
			</div>
</td></tr>
</table>
<p>

			<div id = "submit">
				<input 	type = "submit"
						name = "submit"
						value = "Submit New Scale Information"/>
			</div>

</form>



<? build_footer_pg() ?>
	</body>
</html>
