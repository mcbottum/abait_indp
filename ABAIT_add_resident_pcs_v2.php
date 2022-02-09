<?
include("ABAIT_function_file.php");session_start();
//session_start();
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

    if(document.form.devapikey.value=="")
    {
        alertstring=alertstring+"\n-Enter DevApikey-";
        document.form.devapikey.style.background = "Yellow";
        valid=false;
    }else{
        document.form.devapikey.style.background = "Lightgrey";
    }

    if(document.form.apikey.value=="")
    {
        alertstring=alertstring+"\n-Enter Apikey-";
        document.form.apikey.style.background = "Yellow";
        valid=false;
    }else{
        document.form.apikey.style.background = "Lightgrey";
    }

    if(document.form.organization.value=="")
    {
        alertstring=alertstring+"\n-Enter OrganizationID-";
        document.form.organization.style.background = "Yellow";
        valid=false;
    }else{
        document.form.organization.style.background = "Lightgrey";
    }

    if(document.form.organization_db_key.value=="")
    {
        alertstring=alertstring+"\n-Enter Organization DB Key-";
        document.form.organization_db_key.style.background = "Yellow";
        valid=false;
    }else{
        document.form.organization_db_key.style.background = "Lightgrey";
    }


    if(valid==false){
        alert("Please enter the following data;" + alertstring);
    }//generate the conncanated alert message

    return valid;
}


window.onload = function() {
  document.form.devapikey.focus();
};

</script>

<?
    set_css()
?>
<style>
.space { 
    margin:0; padding:0; height:25px; 
}
input {
    width:250px;
}
</style>

</head>

<?  
    $names = build_page_pg();

$sql="SELECT * FROM scale_table";
$conn = make_msqli_connection();
$session=mysqli_query($conn,$sql);

if(isset($_POST["ak"])){
        $key=$_POST["ak"];
        $action="Update";
        $sql1=mysqli_query($conn,"SELECT * FROM personaldata WHERE personaldatakey=$key");
        $data=mysqli_fetch_assoc($sql1);
    }else{
        $action="Enroll";
        $data='';
    }
?>

        <form
                name="form"
                onsubmit="return validate_form()"
                action = "ABAIT_add_member_log_pcs_v2.php"
                method = "post">            
            
    
        
<?

    print"<h3 class='m-3 p-2 footer_div' align='center'>$action  Residents</h3>";


        print"<h4 align='center'><label id='formlabel'> Resident Data Form</label></h4>";
        print"<div id = 'dataform'>";

            print"<input type='hidden' name='action' value='$action'>"; 
            print"<input type='hidden' name='member' value='resident'>";

    print"<table class='form' align='center'>";
        print"<div id ='name'>";

            print"<tr>";
                print"<td align='center' colspan=2>";
                        print"<h4><label>Enter API TOKEN DATA<span style='color:red'>*</span></label></h4>";
                print"</td>";
            print"</tr>";

            print"<tr>";
                print"<td align='center'>";
                        print"<input type = 'text'

                                placeholder = 'DevApikey*'
                                name = 'devapikey'/>";                      

                print"</td>";
            print"</tr>";
            print"<tr>";
                print"<td align='center'>";

                        print"<input    type = 'text'
                                placeholder = 'Apikey*'
                                name = 'apikey'/>";   

                print"</td>";
            print"</tr>";
        print"</div>";


        print"<div id ='house'>";

            print"<tr>";
                print"<td align='center' colspan=2>";
                        print"<h4><label>Enter Organization ID<span style='color:red'>*</span></label></h4>";
                print"</td>";
            print"</tr>";

            print"<tr>";
                print"<td align='center'>";
                        print"<input    type = 'text'
                                placeholder = 'OrganizationID*'
                                name = 'organization'/>";   
                print"</td>";
            print"</tr>";

        print"</div>";

        print"<div id ='organization_db_key'>";

            print"<tr>";
                print"<td align='center' colspan=2>";
                        print"<h4><label>Enter Organization Database Key<span style='color:red'>*</span></label></h4>";
                print"</td>";
            print"</tr>";

            print"<tr>";
                print"<td align='center'>";
                        print"<input    type = 'text'
                                placeholder = 'Organization DB Key*'
                                name = 'organization_db_key'/>";   
                print"</td>";
            print"</tr>";

        print"</div>";

        print "<tr>";
            print "<td>";
            print "<div class='space'></div>";
            print"</td>";
        print "</tr>";

    print"</table>";
?>  

</div>
<p><div id = "greyline""></div></p>                         
        <div id = "submit">
                <input  type = "submit"
                        name = "submit"
                        value = "Submit API INFO"/>
        </div>

    </form>
<?build_footer_pg()?>
</body>
</html>