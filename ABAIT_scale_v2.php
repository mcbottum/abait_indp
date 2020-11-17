<?session_start();
include("ABAIT_function_file.php");
if($_SESSION['passwordcheck']!='pass'){
    header("Location:".$_SESSION['logout']);
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

<script>
function validate_form()
{
    valid=true;
    var alertstring=new String("");

    if (document.form1.date.checked == false&&document.form1.datetimepicker.value=="" ) {
        alertstring=alertstring+"\n-either today or other date of episode-";
        document.getElementById("date_header").style.background = "yellow";
        document.form1.datetimepicker.style.background = "Yellow";

        valid=false;
    }else if (document.form1.date.checked ) {
        document.form1.date.style.background = "white";

    }//end date of episode check

    if(document.form1.duration.selectedIndex==""){
        alertstring=alertstring+"\n-Duration of the Behavior Episode-";
        document.form1.duration.style.background = "Yellow";
        valid=false;
    }else{
        document.form1.duration.style.background = "white";
    }//end ampm check

    var rb=radiobutton(document.form1.intensityB);
    if(rb==false){
        alertstring=alertstring+"\n-behavior intensity BEFORE interventions-";
        //document.form1.intensityB[0].style.background = "Yellow";
        valid=false
    }   //end call for intensity Before intervention radio button check

    var rb=radiobutton(document.form1.intensityA1);
    if(rb==false){
        alertstring=alertstring+"\n-behavior intensity After FIRST intervention-";
        //document.form1.intensityB.style.background = "Yellow";
        valid=false
    }   //end call for intensity Before intervention radio button check

    var rb=radiobutton(document.form1.intensityA2);
    if(rb==false&&document.form1.intervention2.selectedIndex!=''){
        alertstring=alertstring+"\n-behavior intensity After SECOND intervention-";
        //document.form1.intensityB.style.background = "Yellow";
        valid=false
    }   //end call for intensity Before intervention radio button check

    var rb=radiobutton(document.form1.intensityA3);
    if(rb==false&&document.form1.intervention3.selectedIndex!=''){
        alertstring=alertstring+"\n-behavior intensity After THIRD intervention-";
        //document.form1.intensityB.style.background = "Yellow";
        valid=false
    }   //end call for intensity Before intervention radio button check

    var rb=radiobutton(document.form1.intensityA4);
    if(rb==false&&document.form1.intervention4.selectedIndex!=''){
        alertstring=alertstring+"\n-behavior intensity After FOURTH intervention-";
        //document.form1.intensityB.style.background = "Yellow";
        valid=false
    }   //end call for intensity Before intervention radio button check

    var rb=radiobutton(document.form1.intensityA5);
    if(rb==false&&document.form1.intervention5.selectedIndex!=''){
        alertstring=alertstring+"\n-behavior intensity After FIFTH intervention-";
        //document.form1.intensityB.style.background = "Yellow";
        valid=false
    }   //end call for intensity Before intervention radio button check

    // checking the other way
    var rb=radiobutton(document.form1.intensityA2);
    if(rb==true&&document.form1.intervention2.selectedIndex==''){
        alertstring=alertstring+"\n-Select SECOND intervention-";
        document.form1.intervention2.style.background = "Yellow";
        valid=false
    }
    var rb=radiobutton(document.form1.intensityA3);
    if(rb==true&&document.form1.intervention3.selectedIndex==''){
        alertstring=alertstring+"\n-Select THIRD intervention-";
        document.form1.intervention3.style.background = "Yellow";
        valid=false
    }
    var rb=radiobutton(document.form1.intensityA4);
    if(rb==true&&document.form1.intervention4.selectedIndex==''){
        alertstring=alertstring+"\n-Select FOURTH intervention-";
        document.form1.intervention4.style.background = "Yellow";
        valid=false
    }
    var rb=radiobutton(document.form1.intensityA5);
    if(rb==true&&document.form1.intervention5.selectedIndex==''){
        alertstring=alertstring+"\n-Select FIFTH intervention-";
        document.form1.intervention5.style.background = "Yellow";
        valid=false
    }

    if(valid==false){
        alert("Please enter the following data;" + alertstring);
    }//generate the conncanated alert message

    function radiobutton(rb)
    {
        var count=-1;
        for(var i=rb.length-1;i>-1;i--){
            if(rb[i].checked){
                count=i;
                i=-1;
            }
        }
        if(count>-1){
            return true;
        }else{
            return false;
        }
    }//end radiobutton

    return valid;
}//end form validation function


function show( selTag ) {
    obj1 = document.getElementById("behavior_description_tag");
    obj = document.getElementById("behavior_description");
    if ( selTag.selectedIndex == 1 ) {
        obj1.style.display = "block";
        obj.style.display = "block";
        obj1.style.align="center";
    } else {
        obj1.style.display = "none";
        obj.style.display = "none";
    }
}

function show2( selTag, int ) {
    var targetIntervention=int+1;
    obj1 = document.getElementById("intervention_"+targetIntervention);
    obj2 = document.getElementById("behaviorIntensityAfterButtonHeader_"+int);
    obj3 = document.getElementById("behaviorIntensityAfterButton_"+int);
    obj4 = document.getElementById("intensityAfterHeader_"+int)
    obj5 = document.getElementById("intensityAfterHeader_"+targetIntervention)


    if ( selTag.selectedIndex == 1 ) {
        obj1.style.display = "block";
        obj2.style.display = "block";
        obj3.style.display = "block";

    } else {
        if(targetIntervention < 6){
            obj1.style.display = "inline-block";
        }
        document.getElementById("intervention_"+int).style.display = "block";
        obj2.style.display = "table-row";
        obj3.style.display = "table-row";
        obj4.style.display = "block";
        obj5.style.display = "block";
    }
    document.getElementById("intensityAfterHeader_3").style.display = "block";
}

function show3( selTag ) {
    obj1 = document.getElementById("pre_PRN_observation_tag");
    obj = document.getElementById("pre_PRN_observation_table");
    customTrig = document.getElementById("custom_trigger");
    customSlowTrig = document.getElementById("custom_slow_trigger");

    if ( selTag.value== 'other' ){
        customTrig.style.display = "block";
    }else if ( selTag.value== 'new' ){
        customSlowTrig.style.display = "block";
    } else if (selTag.id=="PRN_div" && selTag.selectedIndex == 1 ) {
        obj1.style.display = "block";
        obj.style.display = "block";
        obj1.style.align="center";
    } else {
        obj1.style.display = "none";
        obj.style.display = "none";
    }
    if(selTag.id=='staff_present_1' && selTag.value > 1){
        document.getElementById("staff_present_checkbox_div_1").style.display = "block";
        document.getElementById("staff_present_2").style.display = "block";
        document.getElementById("alternative_staff1").style.display = "none";
        document.getElementById("temp_staff_present_1").style.display = "none";

    }else if(selTag.id=='staff_present_1' && selTag.value==0){
        document.getElementById("staff_present_checkbox_div_1").style.display = "none";
        document.getElementById("alternative_staff1").style.display = "none";
        document.getElementById("staff_present_checkbox_div_1").style.display = "block";

    }else if(selTag.id=='staff_present_1' && selTag.value==-1){
        document.getElementById("alternative_staff1").style.display = "block";
        document.getElementById("staff_present_checkbox_div_1").style.display = "block";
        document.getElementById("staff_present_2").style.display = "block";
        document.getElementById("temp_staff_present_1").style.display = "none";

    }else if(selTag.id=='staff_present_1' && selTag.value==-2){
        document.getElementById("staff_present_checkbox_div_1").style.display = "block";
        document.getElementById("staff_present_2").style.display = "block";
        document.getElementById("alternative_staff1").style.display = "none";
        document.getElementById("temp_staff_present_1").style.display = "block";
    }

    if(selTag.id=='staff_present_2' && selTag.value > 1){
        document.getElementById("staff_present_checkbox_div_2").style.display = "block";
        document.getElementById("staff_present_3").style.display = "block";
        document.getElementById("alternative_staff2").style.display = "none";
        document.getElementById("temp_staff_present_2").style.display = "none";
    }else if(selTag.id=='staff_present_2' && selTag.value==0){
        document.getElementById("staff_present_checkbox_div_2").style.display = "none";
        document.getElementById("alternative_staff2").style.display = "none";
    }else if(selTag.id=='staff_present_2' && selTag.value==-1){
        document.getElementById("alternative_staff2").style.display = "block";
        document.getElementById("staff_present_checkbox_div_2").style.display = "block";
        document.getElementById("staff_present_3").style.display = "block";
        document.getElementById("temp_staff_present_2").style.display = "none";
    }else if(selTag.id=='staff_present_2' && selTag.value==-2){
        document.getElementById("staff_present_checkbox_div_2").style.display = "block";
        document.getElementById("staff_present_3").style.display = "block";
        document.getElementById("alternative_staff2").style.display = "none";
        document.getElementById("temp_staff_present_2").style.display = "block";
    }

    if(selTag.id=='staff_present_3' && selTag.value > 1){
        document.getElementById("staff_present_checkbox_div_3").style.display = "block";
        // document.getElementById("staff_present_4").style.display = "block";
        document.getElementById("alternative_staff3").style.display = "none";
        document.getElementById("temp_staff_present_3").style.display = "none";
    }else if(selTag.id=='staff_present_3' && selTag.value==0){
        document.getElementById("staff_present_checkbox_div_3").style.display = "none";
        document.getElementById("alternative_staff2").style.display = "none";

    }else if(selTag.id=='staff_present_3' && selTag.value==-1){
        document.getElementById("staff_present_checkbox_div_3").style.display = "none";
        document.getElementById("alternative_staff3").style.display = "block";
        document.getElementById("staff_present_checkbox_div_3").style.display = "block";
        document.getElementById("temp_staff_present_3").style.display = "none";
    }else if(selTag.id=='staff_present_3' && selTag.value==-2){
        document.getElementById("staff_present_checkbox_div_3").style.display = "block";
        // document.getElementById("staff_present_2").style.display = "block";
        document.getElementById("alternative_staff3").style.display = "none";
        document.getElementById("temp_staff_present_3").style.display = "block";
    }
if(document.getElementById("PRN_div").selectedIndex == 1){
        obj1.style.display = "block";
        obj.style.display = "block";
        obj1.style.align="center";
    }
}

function check(selTag) {
    if(document.getElementById("presentincident1").checked == true || document.getElementById("presentintervention1").checked == true){
        document.getElementById("onstaff1").checked = true;
    }else{
        document.getElementById("onstaff1").checked = false;
    }
    if(document.getElementById("presentincident2").checked == true || document.getElementById("presentintervention2").checked == true){
        document.getElementById("onstaff2").checked = true;
    }else{
        document.getElementById("onstaff2").checked = false;
    }
    if(document.getElementById("presentincident3").checked == true || document.getElementById("presentintervention3").checked == true){
        document.getElementById("onstaff3").checked = true;
    }else{
        document.getElementById("onstaff3").checked = false;
    }
}

function showIntervention( selTag,  ) {
    obj1 = document.getElementById("behavior_description_tag");
    obj = document.getElementById("behavior_description");
    if ( selTag.selectedIndex == 1 ) {
        obj1.style.display = "block";
        obj.style.display = "block";
        obj1.style.align="center";
    } else {
        obj1.style.display = "none";
        obj.style.display = "none";
    }
}

// function checked(id){
//     return document.getElementById(id).checked;
// }

function hide(id){
    var toggle=0;
    var hideObj = document.getElementsByName("datetimepicker")[0];
    var displayToday = document.getElementById("datetimepicker5_cell");
    // if(checked(id)){
        var today = new Date();
        hideObj.style.display = "none";
        displayToday.innerHTML = today.toString();
        // document.getElementById(id).className += "disabled";
        document.getElementById(id).style.display="none";
        document.getElementById("datetimepicker5_cell").style.background="lightgreen"
}

function reload(selTag) {
    if (selTag.value == 'new_intervention') {
        val2 = form1.residentkey.value;
        self.location='resident_map.php?k='+val2;
    }else{
        var num = selTag.id.split('_')[1] +1;
        var next_intervention_id = "intervention_"+num
        // obj1 = document.getElementById(selTag.id+"_tag");
        obj = document.getElementById(selTag.id);
        if ( selTag.selectedIndex == 1 ) {
            // obj1.style.display = "block";
            obj.style.display = "block";
            // obj1.style.align="center";
        } else {
            // obj1.style.display = "none";
            obj.style.display = "none";
        }
    }
}

function clicked(button){
    document.getElementById('PRN').value=1;
    document.getElementById('PRN').selectedIndex=1;
    show(document.getElementById('PRN'));
}

function checkDate(){
    var todaysDate = new Date();
    var selDate = new Date(form1.datetimepicker.value)
    if(selDate > todaysDate){
        alert("The Selected date may not be in the future (" + todaysDate +")");
        document.form1.datetimepicker.value = "";
    }else{
        document.form1.datetimepicker.style.background = "White";
    }
}

</script>
<style>
    .btn.btn-lg {
        background-color: #03DAC5;
        border-radius: 10px;
        font-size: 1.5em;
        color: black;
    }
    .btn-lg:hover {
        background-color: #1FC4B4;
        box-shadow: 1px 1px 15px #888888;
        border-style:solid;
        border-width:1px;
        color: black;
    }
    .custom-select {
        background: #03DAC5;
    }

    .custom-select-background {
        background: #03DAC5;
    }

    .custom-select:hover {
        background: #1FC4B4;
    }
    .form-control {
        background-color: #03DAC5;
    }
    .custom-red {
        background-color: red !important;
        border-radius: 10px !important;
    }
    .behaviorIntensityAfter {
        width:100%;
    }
    #submit input{
        color: "#A65100" !important;
    }
.input-wrapper {
    position: relative;
    width: 200px;
}
/*.input-wrapper:before {
    content: "\f073";
    font-family: FontAwesome;
    font-style: normal;
    font-weight: normal;
    text-decoration: inherit;
    color: #459ce7;
    font-size: 28px;
    padding-right: .5em;
    position: absolute;
    top: 10px;
    right: 0;
}
input {
  width: 100%;
  padding-right: 30px;
}*/

</style>
</head>
<body class="container">

    <?
        $names = build_page_pg();
    ?>
    <form   name="form1"
            onsubmit='return validate_form()'
            action = "ABAIT_scale_datalog_v2.php"
            method = "post">
    <?
    $residentkey=$_SESSION['residentkey'];
    $sn=str_replace('_',' ',$_REQUEST['scale_name']);
    if($sn==''){
        $scale_name=$_SESSION['scale_name'];
    }else{
    $_SESSION['scale_name']=$sn;
    $scale_name=$sn;
    }

    if(isset($_GET['trig'])){
        $trigger=$_GET['trig'];
    }elseif(isset($_REQUEST['trig'])){
        $trigger=$_REQUEST['trig'];
    }else{
        $trigger=$_SESSION['trigger'];
    }
    $_SESSION['trigger']=$trigger;
    $conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
    $sql1="SELECT * FROM behavior_maps WHERE mapkey='$trigger'";
    $sql2="SELECT SUM(intervention_score_1), SUM(intervention_score_2), SUM(intervention_score_3), SUM(intervention_score_4), SUM(intervention_score_5), SUM(intervention_score_6) FROM behavior_map_data WHERE mapkey='$trigger'";
    $sql3="SELECT * FROM scale_table WHERE scale_name LIKE '$_SESSION[scale_name]%'";
    $conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'], $_SESSION['db']) or die(mysqli_error());
    $scale=mysqli_query($conn,$sql1);
    $score_sum=mysqli_query($conn,$sql2);
    $intensity=mysqli_query($conn,$sql3);

    $Target_Population=$_SESSION['Target_Population'];
    $Population_strip=mysqli_real_escape_string($conn,$Target_Population);
    // For getting caregivers
    $sql_carer = "SELECT * from personaldata WHERE Target_Population='$Population_strip'";
    $session_carer = mysqli_query($conn,$sql_carer);
    $carer_data=$session_carer->fetch_all(MYSQLI_ASSOC);

    //get episode contact
    $sql_contact = "SELECT * from episode_contact WHERE Target_Population='$Population_strip' AND contact_category='during'";
    $session_contact = mysqli_query($conn,$sql_contact);
    $contact_data=$session_contact->fetch_all(MYSQLI_ASSOC);    

    //Get house Carer Names
    $Population_strip=mysqli_real_escape_string($conn,$Target_Population);
    $sql4="SELECT * FROM personaldata WHERE House='$_SESSION[house]'";
    $session4=mysqli_query($conn,$sql4);

    //Get slow triggers
    $sql5="SELECT * FROM scale_table WHERE  scale_name='Slow Trigger'";
    $session5=mysqli_query($conn,$sql5);
    if($session5){
        $row5 = mysqli_fetch_array($session5);
        $slow_triggers = explode(',',$row5['triggers']);
    } 

    // Get all Carer Names
    $Population_strip=mysqli_real_escape_string($conn,$Target_Population);
    $sql6="SELECT * FROM personaldata WHERE Target_Population='$Population_strip'";
    $session6=mysqli_query($conn,$sql6);








    $first=$_SESSION['first'];
    $last=$_SESSION['last'];

    print"<h2 class='m-3' align='center'>\n";
        print $scale_name." Support Plan for  ".$first."  ".$last;
    print"</h2>\n";
    print "<input type='hidden' id='residentkey', name='residentkey' value='".$residentkey."'>";
    print "<input type='hidden', name='trig' value='".$trig."'>";
        ?>

    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
            <h3 align='center'><label>Date and Time Information</label></h3>
        </div>
    </div>









    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
                   <h5>Select if episode is taking place now</h5>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
                        <input  
                                name = "date"
                                id = "now"
                                class="custom-red  btn-lg btn-danger "
                                style="width:29%"
                                onclick="hide('now')"
                                value = "NOW">
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
                    <h5>Or select date and time</h5>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
                        <div class="input-wrapper" id="datetimepicker5_cell">
                            <input onchange="checkDate()" class="form-control" id="datetimepicker5"  name="datetimepicker"  autocomplete="off" type="text" placeholder='Touch to enter'/>
                        </div>

        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
                   <h5>Duration of episode</h5>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col col-lg-auto d-flex justify-content-center">
                        <select class='selBox custom-select-background custom-select-lg mb-3' data-width='auto' name = "duration" id="durat">
                            <option value = "">Choose Minutes</option>
                            <?
                            // for($t = 1;$t <= 5;$t +=1){
                            //     print "<option value = $t>$t</option>";
                            // }
                            print "<option value = 5>less than 5</option>";
                            print "<option value = 10>6-10</option>";
                            print "<option value = 30>11-30</option>";
                            print "<option value = 60>30-60</option>";
                            print "<option value = 120>1-2 hrs</option>";
                            ?>
<!--                             <option value = "105">More than 5 minutes</option> -->
                        </select>
        </div>
    </div>








        <?
    $row=mysqli_fetch_assoc($scale);
    $row2=mysqli_fetch_assoc($score_sum);
    $row=array($row['intervention_1'], $row['intervention_2'], $row['intervention_3'], $row['intervention_4'], $row['intervention_5'],$row['intervention_6']);
    $intervention_rank=array(1,2,3,4,5,6);
    array_multisort($row2,$row,$intervention_rank);

    //FOR INTENSITY SELECTION
    $row3=mysqli_fetch_assoc($intensity);
    $color_array = ['#FF000','#00FF00','#ADFF2F','#FFD700','#FF7F50','#FF0000'];
    // $color_array = ['red','orange','yellow','lightgreen','green','blue'];
   print" <div class='row justify-content-md-center'>";
       print" <div class='col col-lg-auto'>";
            print"<h3 align='center'><label>Select Behavior Intensity</label></h3>\n";
        print"</div>";
    print"</div>";





// THESE ARE THE BEHAVIOR BEFORE 
        print"<table align='center' class='table-sm table-hover'>\n";
            print "<div id='behaviorIntensityBefore'>";
                print "<tr>";
                    print "<th align='center'>Behavior Intensity</th>";
                    print "<th align='center'><span style='color:red'>BEFORE</span> ANY  Intervention</th>";
                print "</tr>";
                for($i=1;$i<6;$i++){
                    print"<tr class='raised' style='background:$color_array[$i]'>";
                    $comment='comment_'.$i;
                        print"<td class='scaleIntensity' style='padding-left:4px'>$row3[$comment]</td>";
                        print"<td align='center'><label><input type='radio'
                                            name='intensityB'
                                            id='intensity$i'
                                            value=$i></label>";
                        print "</td>";
                    print "</tr>";
                }
            print "</div>";  
        print "</table>";
// END BEHAVIOR INTENSITY BEFORE


//HERE ARE THE INTERVENTIONS
    for($int=1; $int<7; $int++){
        if($int<3){  //allow five interventions
            print"<div class='row justify-content-md-center' align='center'  id='intensityAfterHeader_$int'>";
                if($int==1){
                    print "<div class='col col-lg-auto'><h3>Intervention $int</h3></div>";
                }else{
                    print "<div class='col col-lg-auto'><h3>Intervention $int <span style='color:Lime'>(optional)</span></h3></div>";
                }
            print"</div>";
        }elseif($int<6 && $int>2){  //allow five interventions
            print"<div class='row justify-content-md-center behaviorIntensityAfter' >";
                print "<div class='col col-lg-auto' id='intensityAfterHeader_$int' align='center' style='display:none'><h3>Intervention $int <span style='color:Lime'>(optional)</span></h3></div>";
            print"</div>";
        }


        if($int<3){
            print"<div class='row justify-content-md-center behaviorIntensityAfter' id='behaviorIntensityAfterSelect_$int' align='center' >\n";
            $display = "block";
        }elseif($int<6 && $int>2){
            print"<div class='row justify-content-md-center behaviorIntensityAfter' id='behaviorIntensityAfterSelect_$int' align='center' >\n";
            $display = "none";
        }else{
            print"<div class='row justify-content-md-center behaviorIntensityAfter' id='behaviorIntensityAfterSelect_$int' align='center' >\n";
        }


///
                print"<div class='col col-md-auto' style='padding:0px'>\n";
                    if($int<6){
                        $t_intervention = 'intervention'.$int;
                        print"<select  data-width='auto' class='selBox custom-select-background custom-select-lg mb-3' name ='intervention$int' id='intervention_$int' onchange='reload(this); show2(this, $int)' style='display:$display'>";
                       
                            if($int==1){
                                $s=5;
                            }else{$s=6;}
                            for($r=$s;  $r>-1;  $r--){
                                $intervention='intervention_'.$r;
                                if($row[$r]!="None Set"){
                                    print "<optGroup>";
                                        print "<option value=$intervention_rank[$r]>$row[$r]</option>\n";
                                    print "</optGroup>";
                                }
                            }
                        ?>
                        <optGroup><option class='red' style='color:blue; font-weight:bold' value='new_intervention' style='color:red'>New Idea</option>
                        </optGroup>
                        <?
                        print "</select>\n";
                    }
                        
                print"</div>\n";
            print"</div>\n";
        

//AFTER INTENSITY OBSERVATIONS
      
            print "<div class='row justify-content-md-center'>";
                print"<div class='col col-lg-auto m-2'>";

                        print "<table class='table-sm m-1 table-hover'>";
                                if($int==1){
                                    print "<tbody id='behaviorIntensityAfterButton_$int'>";
                                }else{
                                    print "<tbody id='behaviorIntensityAfterButton_$int' style='display:none'>";
                                }
                                        print "<tr id='behaviorIntensityAfterButtonHeader_$int' class='behaviorIntensityAfter' >";
                                                print "<th width='100%'   align='center'>Behavior Intensity</th>";
                                                if($int<6){
                                                    print "<th  align='center'><span style='color:red'>AFTER </span> $int  Intervention</th>";
                                                }
                                        print "</tr>";

                                        for($i=1;$i<6;$i++){
                                            print"<tr id='behaviorIntensityAfterButton_$int' class='raised behaviorIntensityAfter' style='background:$color_array[$i]'>";
                                            $comment='comment_'.$i;
                                                    print"<td class='scaleIntensity'  style='padding-left:4px'>$row3[$comment]</td>";
                                                    print"<td  align='center'><label><input type='radio'
                                                                name='intensityA$int'
                                                                id='intensity$i'
                                                                value=$i></label>";
                                                    print "</td>";
                                            print "</tr>";

                                        }
                                 print "</tbody>";
                        print "</table>";


                print "</div>";
            print"</div>";

    } // END $intervention FOR LOOP                        
// EMERGENCY CONTACT REQUIRED
?>

        <div class="row justify-content-md-center">
            <div class='col col-lg-auto d-flex justify-content-center'>
                    <h3><label>Emergency Intervention Required?</label></h3>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class='col col-lg-auto d-flex justify-content-center'>
                    <?
                    print"<select data-width='auto' id='PRN_div'class='selBox custom-select-background custom-select-lg mb-3'  name='PRN' onchange='show3(this)' >";
                        print "<optGroup>";
                            print"<option value='0' selected>NO</option>";
                            print"<option value='1'>YES</option>";
                        print "</optGroup>";
                    print"</select>";

                    ?>  
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class='col col-lg-auto d-flex justify-content-center'>
                <div id='pre_PRN_observation_tag' style='display: none; color: red;'>Select specific description of behavior which required Emergency Service.</div>
            </div>
        </div>
<!--         <div class="row justify-content-md-center">
            <div class='col col-lg-8 d-flex justify-content-center'> -->
                <div id='pre_PRN_observation_table' style='display: none'>
                    <!-- <textarea class="form-control form-control-ta" name='pre_PRN_observation' id='pre_PRN_observation'; style='display: none; background-color: yellow;  value=''/></textarea> -->
                    <div class="row justify-content-md-center">
                        <div class='col col-lg-8'>
                            <table align='center' class='table-md table-hover table-bordered'>
                                <tr>
                                    <th style='text-align: center'> Intervention </th>
                                <?
                                foreach ($contact_data as $row) {
                                    print"</tr>";
                                        print"<td>";
                                            print"<input type = 'checkbox'
                                                class='m-2'
                                                name = 'emergency_intervention[]'
                                                id = '$row[contact_type]'
                                                value = '$row[id]'/>";
                                                print"<label for='$row[contact_type]''>$row[contact_type]</label>";
                                        print"</td>";
                                    print"</tr>";
                                }
                                ?>

                            </table>
                        </div>
                    </div>

                </div>
<!--             </div>
        </div> -->
<?
// END EMERGENCY CONTACT REQUIRED


    print"<div class='row justify-content-md-center'>";
        print"<div class='col col-lg-auto d-flex justify-content-center'>";
             print"<h3> Other Staff Present?</h3>";
        print"</div>";
    print"</div>";


// SELECT DROPDOWNS FOR STAFF PRESENT for staff 1 present  id='staff_present_1' id='staff_present_checkbox_div_1'
                  

// for select dropdown for staff 1 present  id='staff_present_1' id='staff_present_checkbox_div_1'
        $i=0;
        print"<div class='row justify-content-lg-center'>";
            print"<div class='col col-lg-auto pr-0 d-flex justify-content-center'>";
                print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='staff_present_1' id='staff_present_1' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";
                    foreach ($carer_data as $row) {
                        if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                    print "<option value='-2'>Temporary Staff</option>";
                    print "<option value='-1'>Agency Staff</option>";
                print"</select>";

            print"</div>";

            print"<div class='col col-lg-auto pr-0'>";
                print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='temp_staff_present_1' id='temp_staff_present_1' style='display: none;' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";
                    foreach ($carer_data as $row) {
                        if( $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                print"</select>";
            print"</div>";

            print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff1' style='display: none;'>";
                print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_1_name' name='alternative_staff_1_name'/></textarea>";
            print"</div>";

            print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_1' style='display: none;'>";        
                print "<table class='table-sm table-hover table-bordered ' align='center'>";
                    print"<tr><th style='text-align: center'>Interaction</th></tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox'
                            name = 'onstaff1'
                            id = 'onstaff1'
                            value = '1'/>";
                            print"<label for='onstaff1'>On Staff</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox' onchange='check(this)'
                            name = 'presentincident1'
                            id = 'presentincident1'
                            value = '1'/>";
                            print"<label for='presentincident1'>Present during incident</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox' onchange='check(this)'
                            name = 'presentintervention1'
                            id = 'presentintervention1'
                            value = '1'/>";
                            print"<label for='presentintervention1'>Present during intervention</label>";
                        print "</td>";
                    print "</tr>";
                print "</table>";
            print"</div>";

        print"</div>";


// for select dropdown for staff 2 present  id='staff_present_2' id='staff_present_checkbox_div_2'
        $i=0;
        print"<div class='row justify-content-md-center'>";
            print"<div class='col col-lg-auto pr-0'>";
                print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='staff_present_2' id='staff_present_2' style='display: none;' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";
                    foreach ($carer_data as $row) {
                        if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                    print "<option value='-2'>Temporary Staff</option>";
                    print "<option value='-1'>Agency Staff</option>";
                print"</select>";
            print"</div>";


            print"<div class='col col-lg-auto pr-0'>";
                print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='temp_staff_present_2' id='temp_staff_present_2' style='display: none;' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";
                    foreach ($carer_data as $row) {
                        if( $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                print"</select>";
            print"</div>";

            print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff2' style='display: none;'>";
                print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_2_name' name='alternative_staff_2_name'/></textarea>";
            print"</div>";

            print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_2' style='display: none;'>";        
                print "<table class='table-sm table-hover table-bordered' align='center'>";
                    print"<tr><th style='text-align: center'>Interaction</th></tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox'
                            name = 'onstaff2'
                            id = 'onstaff2'
                            value = '1'/>";
                            print"<label for='onstaff2'>On Staff</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox' onchange='check(this)'
                            name = 'presentincident2'
                            id = 'presentincident2'
                            value = '1'/>";
                            print"<label for='presentincident2'>Present during incident</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox' onchange='check(this)'
                            name = 'presentintervention2'
                            id = 'presentintervention2'
                            value = '1'/>";
                            print"<label for='presentintervention2'>Present during intervention</label>";
                        print "</td>";
                    print "</tr>";
                print "</table>";
            print"</div>";

        print"</div>";


// for select dropdown for staff 3 present  id='staff_present_3' id='staff_present_checkbox_div_3'
        $i=0;
        print"<div class='row justify-content-md-center'>";
            print"<div class='col col-lg-auto pr-0'>";
                print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='staff_present_3' id='staff_present_3' style='display: none;' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";
                    foreach ($carer_data as $row) {
                        if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                    print "<option value='-2'>Temporary Staff</option>";
                    print "<option value='-1'>Agency Staff</option>";
                print"</select>";
            print"</div>";


            print"<div class='col col-lg-auto pr-0'>";
                print "<select class='custom-select custom-select-lg mb-3'  data-width='auto' name='temp_staff_present_3' id='temp_staff_present_3' style='display: none;' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";

                    foreach ($carer_data as $row) {
                        if( $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                print"</select>";
            print"</div>";

            print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff3' style='display: none;'>";
                print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_3_name' name='alternative_staff_3_name'/></textarea>";
            print"</div>";

            print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_3' style='display: none;'>";        
                print "<table class='table-sm table-hover table-bordered' align='center'>";
                    print"<tr><th style='text-align: center'>Interaction</th></tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox'
                            name = 'onstaff3'
                            id = 'onstaff3'
                            value = '1'/>";
                            print"<label for='onstaff3'>On Staff</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox' onchange='check(this)'
                            name = 'presentincident3'
                            id = 'presentincident3'
                            value = '1'/>";
                            print"<label for='presentincident3'>Present during incident</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox' 
                            onchange='check(this)'
                            name = 'presentintervention3'
                            id = 'presentintervention3'
                            value = '1'/>";
                            print"<label for='presentintervention3'>Present during intervention</label>";
                        print "</td>";
                    print "</tr>";
                print "</table>";
            print"</div>";

        print"</div>";

    // for select dropdown for staff 1 present  id='staff_present_4' id='staff_present_checkbox_div_4'
        $i=0;
        print"<div class='row justify-content-md-center'>";
            print"<div class='col col-lg-auto pr-0'>";
                print "<select class='custom-select custom-select-lg mb-3' data-width='auto' name='staff_present_4' id='staff_present_4' style='display: none;' onchange='show3(this)'>";
                    print "<option value=''>Select Staff Member</option>";
                    foreach ($carer_data as $row) {
                        if($row[house]==$_SESSION['house'] && $_SESSION['personaldatakey']!=$row['personaldatakey']){
                            print "<option value='$row[personaldatakey]'>$row[first] $row[last]</option>";
                        }
                    }
                    print "<option value='-1'>Temporary Staff</option>";
                print"</select>";

            print"</div>";
            print"<div class='col col-lg-auto ml-0 mb-2' id='staff_present_checkbox_div_4' style='display: none;'>";        
                print "<table class='table-sm table-hover table-bordered' align='center'>";
                    print"<tr><th style='text-align: center'>Interaction</th></tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox'
                            name = 'onstaff4'
                            id = 'onstaff4'
                            value = '1'/>";
                            print"<label for='onstaff4'>On Staff</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox'
                            onchange='check(this)'
                            name = 'presentincident4'
                            id = 'presentincident4'
                            value = '1'/>";
                            print"<label for='presentincident4'>Present during incident</label>";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print"<input type = 'checkbox'
                            onchange='check(this)'
                            name = 'presentintervention4'
                            id = 'presentintervention4'
                            value = '1'/>";
                            print"<label for='presentintervention4'>Present during intervention</label>";
                        print "</td>";
                    print "</tr>";
                print "</table>";
            print"</div>";
            print"<div class='col col-lg-auto ml-0 mb-2' id='alternative_staff4' style='display: none;'>";
                print"<textarea class='form-control form-control-ta' autofocus='autofocus' placeholder='Enter first and last name'  id='alternative_staff_4_name'/></textarea>";
            print"</div>";
        print"</div>";

// END SELECT DROPDOWNS FOR STAFF PRESENT

?>  
        <div id="submit">
            <input  style="color:#A65100"
                    type = "submit"
                    name = "submit"
                    value = "Submit Resident Plan Data"/>
        </div>
    </form>

    <?build_footer_pg()?>
</body>
<script type="text/javascript">jQuery('#datetimepicker5').datetimepicker({
 datepicker:true,
 formatTime:'g:i a',
  allowTimes:['00:00 am','00:30 am','01:00 am','01:30 am','02:00 am','01:30 am','02:30 am','03:00 am','03:30 am','04:00 am','04:30 am','05:00 am','05:30 am',
    '06:00 am','06:30 am','07:00 am','07:30 am','08:00 am','08:30 am','09:00 am','09:30 am','10:00 am','10:30 am','11:00 am','11:30 am',
    '12:00 pm','01:00 pm','01:30 pm','02:00 pm','01:30 pm','02:30 pm','03:00 pm','03:30 pm','04:00 pm','04:30 pm','05:00 pm','05:30 pm',
    '06:00 pm','06:30 pm','07:00 pm','07:30 pm','08:00 pm','08:30 pm','09:00 pm','09:30 pm','10:00 pm','10:30 pm','11:00 pm','11:30 pm']
});
</script>
<script type="text/javascript">$('#cal_button').click(function(){
  $('#datetimepicker5').datetimepicker('show'); //support hide,show and destroy command
});
</script>
</html>
