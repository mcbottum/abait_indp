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
    if(document.form.password1.value.length<5){
        alertstring=alertstring+"\n-Login ID must contain at least 5 characters-";
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
window.onload = function() {
  document.form.first.focus();
};

</script>

<?
    set_css()
?>
<style>
.space { 
    margin:0; padding:0; height:25px; 
}
</style>

</head>

<?  
    $names = build_page_pg();


// echo "HERE";
// echo $_SESSION['user'],$_SESSION['hostname'],$_SESSION['mysqlpassword'],$_SESSION['db'];
// echo "WTF";
$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());
$session=mysqli_query($conn,$sql);

$sql1=mysqli_query($conn,"SELECT * FROM personaldata WHERE first='$names[0]' AND last='$names[1]'");
$data=mysqli_fetch_assoc($sql1);

$action = "workstation_login";
$key = $data['personaldatakey'];

?>

        <form
                name="form"
                onsubmit="return validate_form()"
                action = "ABAIT_add_admin_log_v2.php"
                method = "post">            
            
    
        
<?

    print"<h3 class='m-3 p-2 footer_div' align='center'>Set/Update Workstation Administrator Login Password</h3>";

        print"<div id = 'dataform'>";

            print"<input type='hidden' name='action' value='$action'>"; 
            print"<input type='hidden' name='key' value=$key>";

    print"<table class='form' align='center'>";

        print "<tr>";
            print "<td>";
            print "<div class='space'></div>";
            print"</td>";
        print "</tr>";


        print"<div id ='clientpassword'>";
            print"<tr>";
                print"<td colspan=2>";
                    if($data){
                        print"<h3><label>Edit Administrator Login ID*</label></h3>";
                    }else{
                        print"<h3><label>Create Administrator Login ID*</label></h3>";
                    }
                print"</td>";
            print"</tr>";
            print"<tr>";
                print"<td>";
                    if($data){
                        print"<input    type = 'password'
                                placeholder=$data[password]
                                name = 'password1'
                                value = $data[password]
                                oninput='check_pass();'/>";
                    }else{
                        print"<input type = 'password'
                                placeholder='Login ID*'
                                name='password1'
                                oninput='check_pass();'/>";             
                    }
                print"</td>";
                print"<td>";
                    print"<input type='submit' value='Info' onClick=\'alert('Login ID must be at least 5 characters long');return false\'>";
                print"</td>";
            print"</tr>";
            print"<tr>";
                print"<td>";
                    if($data && $_SESSION['reset_password']){
                        print"<input    type = 'password'
                                placeholder=$data[password]
                                name = 'password2'
                                value = $data[password]
                                oninput='check_pass();'/>";
                    }else{
                        print"<input type = 'password'
                                placeholder='Re-enter Login ID*'
                                name='password2'
                                oninput='check_pass();'/>";             
                    }
                    print"<div style='color:red' id='message' style='display: inline-block'></div>";
                print"</td>";
            print"</tr>";
        print"</div>";
    print"</table>";
?>  

</div>
<p><div id = "greyline""></div></p>                         
        <div id = "submit">
                <input  type = "submit"
                        name = "submit"
                        value = "Submit Administrator Data"/>
        </div>

    </form>
<? build_footer_pg()?>
</body>
</html>