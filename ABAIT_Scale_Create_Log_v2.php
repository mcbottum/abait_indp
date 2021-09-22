<?session_start();
include("ABAIT_function_file.php");
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
<?
set_css()
?>

<style>
    table.local thead th{
        /*width:130px;*/
        background-color: white;
        background-color: #F5F5F5;
    }
    table.local tbody th{
        /*width:130px;*/
        background-color: white;
        background-color: #F5F5F5;
    }
    table.local tbody{
        max-height: 400px;

    }
    table.local tbody td{
        /*width:130px;*/
        background-color: white;
    }

    table.hover tbody tr:hover{
        background-color: #D3D3D3;
    }
/*    label {
         whatever other styling you have applied 
        /*width: 100%;*/
        display: inline-block;
    }*/
	input {
	    /*width: 80%;*/
	    padding: 10px;
	    margin: 3px;
	}
	submit{
		margin-top: 10px;
		border-radius: 6px;
		font:19px/1.33 Verdana, sans-serif;
		font-weight:bold;
		color:#A65100;
	}
	.upper_border {
		border-top-style: solid;
	}
	.lower_border {
		border-bottom-style: solid;
	}
	.no_border {
		border-left-style: none;
		border-collapse: collapse;
	}
</style>

</head>
<body class="container">

<?	
$names = build_page_pg();

print"<form 	name = 'form1'
	action = 'ABAIT_Scale_Create_Log_Comments_v2.php'
	method = 'post'>";

$filename =$_REQUEST["submit"];
if($filename=='Submit New Scale Information'){

	$conn=make_msqli_connection();
	
	// Get All pre-existing scales for possible selection
	$sql = "SELECT * FROM scale_table";
	$session = mysqli_query($conn,$sql);


	// print pre-existing scales for reference or copy to new population
	print "<h2 class='m-3 p-2 footer_div' align='center'>ABAIT Scale Edit Tables</h2>";
	print"<h3 align='center'><br>Pre-Existing Scales for Reference or Import into new Population</br></h3>";
	print "<table width='100%' columns='6' class='table local' bgcolor='white'>";
		
		while($row=mysqli_fetch_assoc($session)){
			print "<thead>";
				print "<tr class='upper_border'>\n";
					print "<th colspan='2'>Scale Name (select to import)</th>";
					print "<th colspan='2'>Scale Description</th>";
					print "<th colspan='2'>Typical Triggers</th>";
				print "</tr>";
			print "</thead>";
			print "<tbody>";
		
				print "<tr >";
					$scale_name=str_replace(' ','_',$row['scale_name']);
					print "<td  colspan='2'>
						<div class='form-group form-check'>
					 		
					 		<input type='checkbox' class='form-check-input' name=$scale_name value='1' id='Check'>
					 			<label class='form-check-label' for='Check' align='right'>
					 				$row[scale_name]
			 					</label>
					 	</div>
					 </td>";
					print "<td colspan='2'>$row[scale_name_description]</td>";
					print "<td colspan='2'>$row[triggers]</td>";
				print "</tr>";
				print "<tr class='upper_border'>";
					print "<th>Intensity</th>";
					for($i=1;$i<6;$i++){
						$comment = "comment_".$i;
						print "<td>$row[$comment]</td>";
					}
				print "</tr>";
				print "<tr class='lower_border'>";
					print "<th>Behavior Description</th>";
					for($i=1;$i<6;$i++){
						$comment = "behave_class_".$i;
						print "<td>$row[$comment]</td>";
					}
				print "</tr>";
				print "<tr class='no_border'><td colspan='6'></td></tr>";
			print "</tbody>";
		};
		
	print "</table>";

		$Target_Population=$_REQUEST['Target_Population'];
		
		$scale_number=$_REQUEST['scale_number'];
		
		print"<h3 align='center'><br>Each scale requires FIVE behavior descriptions of INCREASING intensity</br></h3>";
		print"<h4><br>Enter scale names and brief behavior descriptions in the text boxes provided</br></h4>";

	print "<table width='50%' columns='6' class='table  table-responsive-lg local' bgcolor='white'>";

		for($i=0;$i<$scale_number;$i++){
			$scale_count = $i+1;

			print "<thead class='upper_border'>";
				print "<tr>";
					print "<th colspan='2'>Scale Name $scale_count </th>";
					print "<th colspan='2'>Scale Description</th>";
					print "<th colspan='2'>Typical Triggers</th>";
				print "</tr>";
			print "</thead>";
			print "<tbody>";

					
				$scale_name='scale_name_'.$i;
				$scale_name_description = 'scale_name_description_'.$i;
				$triggers = 'triggers_'.$i;
		
				print "<tr>";
					print "<td colspan='2'><input type = 'text' placeholder='required' name =$scale_name></td>\n";
					print "<td colspan='2'><input type = 'text' placeholder='required' name =$scale_name_description></td>\n";
					print "<td colspan='2'><input type = 'text' placeholder='optional' name =$triggers></td>\n";
				print "</tr>";
				print "<tr class='upper_border'>";
					print "<th>Intensity</th>";
					for($j=1;$j<6;$j++){
						$intensity='intensity_'.$i.'_'.$j;
						print "<td><input type = 'text' placeholder='required $j' name =$intensity></td>\n";
					}
				print "</tr>";
				print "<tr>";
					print "<th>Behavior Description</th>";
					for($j=1;$j<6;$j++){
						$behavior_description='behavior_description_'.$i.'_'.$j;
						print "<td ><input type = 'text' placeholder='required $j' name =$behavior_description></td>\n";
					}
				print "</tr>";
				print "<tr class='no_border upper_border'><td colspan='6'></td></tr>";
			print "</tbody>";
		}; // end for i in scale number
	print "</table>";
		
	print"<div id = 'submit'>";
		print"<input class='submit'	type = 'submit'
				name = 'submit'
				value = 'Submit New Scales'>";
	print"</div>";
}//end if filename=submit new scale info

$_SESSION['Target_Population_new']=$Target_Population;		
$_SESSION['scale_number']=$scale_number;	
?>
</form>


		<? build_footer_pg() ?>
	</body>
</html>