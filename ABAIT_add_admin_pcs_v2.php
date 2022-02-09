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

    if(document.form.first.value=="")
    {
        alertstring=alertstring+"\n-First Name-";
        document.form.first.style.background = "Yellow";
        valid=false;
    }else{
        document.form.first.style.background = "white";
    }//end first name
    
    if(document.form.last.value=="")
    {
        alertstring=alertstring+"\n-Last Name-";
        document.form.last.style.background = "Yellow";
        valid=false;
    }else{
        document.form.last.style.background = "white";
    }//end first name
    var Target_Population = document.getElementById("Target_Population")
    if(Target_Population.options[Target_Population.selectedIndex].value=="")
    {
        alertstring=alertstring+"\n-Target Population-";
        document.form.Target_Population.style.background = "Yellow";
        valid=false;
    
    }else{
        document.form.Target_Population.background = "white";
    }//end Target Population check  
    if(document.form.password1.value.length<4){
        alertstring=alertstring+"\n-Login ID must contain at least 4 characters-";
        document.form.password1.style.background = "Yellow";
        valid=false;
    } else if (document.form.password2.value.length==0)
    {
        alertstring=alertString+"\n-Please confirm your password";
        document.form.password1.style.background = "Yellow";
        valid=false;
    } else if(document.form.password1.value != document.form.password2.value)
    {
        alertstring=alertstring+"\n-Password one must match password two";
        document.form.password1.style.background = "Yellow";
        document.form.password2.style.background = "Yellow";

        valid=false;
    }else{
        document.form.password1.background = "white";
        document.form.password2.background = "white";

    }//passwordcheck
    
    if (valid==false){
        alert("Please enter the following data;" + alertstring);
    }
    return valid;
}

function check(selTag) {
    if(selTag.checked == true){
        document.getElementById("email").style.display = "block";
        document.getElementById("email").focus();
    }else{
        document.getElementById("email").value = "";
        document.getElementById("email").style.display = "none";
        document.getElementById('email_message').innerHTML = "";
    }
}

function check_email(selTag){
    var emailExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (selTag.value.length > 0 && selTag.value.match(emailExp)){
        document.getElementById('email_message').innerHTML = "";
        selTag.style.background = 'lightgreen';
    }else if (selTag.value.length > 0){
        document.getElementById('email_message').innerHTML = "Please check that email is valid.";
        selTag.style.background = 'white';
    }else{
        selTag.style.background = 'white';
    }

}

function check_pass(){
    if (document.form.password1.value == document.form.password2.value){
        if (document.form.password1.value.length > 0 && document.form.password2.value.length > 0){
            document.getElementById('message').innerHTML = ""
        }
    } else {
        if (document.form.password1.value.length > 0 && document.form.password2.value.length > 0){
            document.getElementById('message').innerHTML = "These passwords don't match. Try again?";
        } else {
            document.getElementById('message').innerHTML = "";
        }
    }
}

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

    print"<h3 class='m-3 p-2 footer_div' align='center'>$action  Administrator</h3>";


        print"<h4 align='center'><label id='formlabel'> Administrator Data Form<span style='color:red'>*</span></label></h4>";
        print"<div id = 'dataform'>";

            print"<input type='hidden' name='action' value='$action'>"; 
            print"<input type='hidden' name='member' value='staff'>";

    print"<table class='form' align='center'>";
        print"<div id ='name'>";

            print"<tr>";
                print"<td align='center' colspan=2>";

                        print"<h4><label>Enter API TOKEN DATA</label></h4>";
                    
                print"</td>";
            print"</tr>";

            print"<tr>";
                print"<td align='center'>";
                        print"<input type = 'text'
                                placeholder = 'DevApikey*'
                                id = 'devapikey'
                                name = 'devapikey'/>";                      

                print"</td>";
            print"</tr>";
            print"<tr>";
                print"<td align='center'>";

                        print"<input    type = 'text'
                                placeholder = 'Apikey*'
                                id = 'apikey'
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
                                id = 'organization'
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
                                id = 'organization_db_key'
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