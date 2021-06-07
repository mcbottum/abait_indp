<?
session_start();
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
<script type="text/javascript">
    
function backButton1(target_population) {
    var url = 'ABAIT_episode_historical_review_v2.php?tp='+target_population;
    window.location.href='ABAIT_episode_historical_review_v2.php?tp='+target_population;
}

</script>
<style>

    .table th {
/*      border: 0px;
*/      background-color: lightgrey;
    }

    p.backButton {
      float:right;
    }

</style>
</head>
<body class="container">
<?          
$names = build_page_pg();
?>
    <form action="adminhome.php" method="post">
<?
$filename=$_REQUEST['submit'];
$Population=$_REQUEST['Target_Population'];
$Population=str_replace('_',' ',$_SESSION['pop']);
//print $Population;
//print $_SESSION[Target_Population];
$personaldatakey=$_REQUEST['personaldatakey'];
$date=date('Y-m-d');
$date_stop = $date;
//print $personaldatakey;
$all_careproviders=Null;
$scale_totals=Null;
$behavior_units=Null;
$behavior_units_per_time=Null;
$episode_time_of_day=Null;
$trigger_breakdown=Null;
$all_episode=Null;
$reviewtime=Null;
$back_clicked=False;
if($filename=="Submit Selection for Analysis"){
        if(isset($_REQUEST['all_careproviders'])){
            $all_careproviders=$_REQUEST['all_careproviders'];
        }
        $review_time=$_REQUEST['review_time'];
        //$scale_array=$_REQUEST[scale_array];
        if(isset($_REQUEST['scale_totals'])){
            $scale_totals=$_REQUEST['scale_totals'];
        }
        if(isset($_REQUEST['behavior_units'])){
            $behavior_units=$_REQUEST['behavior_units'];
        }
        if(isset($_REQUEST['behavior_units_per_time'])){
            $behavior_units_per_time=$_REQUEST['behavior_units_per_time'];
        }
        if(isset($_REQUEST['episode_time_of_day'])){
            $episode_time_of_day=$_REQUEST['episode_time_of_day'];
        }
        if(isset($_REQUEST['trigger_breakdown'])){
            $trigger_breakdown=$_REQUEST['trigger_breakdown'];
        }
        if(isset($_REQUEST['intervention_effect'])){
            $intervention_effect=$_REQUEST['intervention_effect'];
        }else{
            $intervention_effect=Null;
        }
        if(isset($_REQUEST['all_episode'])){
            $all_episode=$_REQUEST['all_episode'];
        }
        if(isset($_REQUEST['review_time'])){
            $reviewtime=$_REQUEST['review_time'];
        }

}
if(isset($_GET['bk_ds'])){
    $date_start = date($_GET['bk_ds']);
    $personaldatakey='all_careproviders';
    $back_clicked=True;
}elseif($reviewtime=='day'){
    $date_start=date('Y-m-d',(strtotime('- 1 days')));
}elseif($reviewtime=='week'){
    $date_start=date('Y-m-d',(strtotime('- 7 days')));
}elseif($reviewtime==1){
    $date_start = date('Y-m-01');
}elseif($reviewtime==3){
    $date_start=date('Y-m-d',(strtotime('- 90 days')));
}elseif($reviewtime==6){
    $date_start=date('Y-m-d',(strtotime('- 180 days')));
}elseif($reviewtime=='all'){
    $date_start=date('Y-m-d',(strtotime('- 10000 days')));
}elseif(empty($reviewtime)){
    $date_start=date('Y-m-d',(strtotime('- 30 days')));
}


$conn=mysqli_connect($_SESSION['hostname'],$_SESSION['user'],$_SESSION['mysqlpassword'],$_SESSION['db']) or die(mysqli_error());

$scale_array=$_SESSION['scale_array'];
foreach($scale_array as $value){
    $sum_behaviorarray[$value]=0;
}//end foreach


    // GET CAREGIVERS
    if($personaldatakey=='all_careproviders'){

        $Population_strip=mysqli_real_escape_string($conn,$Population);
        $sql="SELECT * FROM personaldata WHERE Target_Population='$Population_strip' order by first";
        $providers=mysqli_query($conn,$sql);
        $title='Episode Summary for <em>All</em> Carers: ';

    }elseif($personaldatakey&&$personaldatakey!='all_careproviders'){
        $sql="SELECT * FROM personaldata WHERE personaldatakey='$personaldatakey'";
        $provider_session=mysqli_query($conn,$sql);
        $providers=mysqli_query($conn,$sql);
        $title='Episode Summary: ';
    }else{

        print"<h4 align='center'>A Carer selection was not made, please return to the previous page.</h4>";
        print "<h4 align='center'><a href='ABAIT_careprovider_review.php'>Return to Provider Selection  form</a></h4>\n";
        die;
    }
    print"<h2 class='m-3 p-2 footer_div' align='center'> $title <em>$date_start to $date_stop</em></h2>";
    print"<h4 align='center'>Click on Carer Name to view episode analysis</h4>";
    print "<table width='100%'><tr align='right'><td>";
            ?>
                <FORM>
                    <INPUT TYPE="button" value="Print Page" onClick="window.print()">
                </FORM></td></tr>
            <?
    print "</td></tr>";
                        print "<tr align='right'><td>";
                ?>
                    <input type='submit' align='block' value='Tap for more Info' onClick="alert('Total Duration is minutes of episodes during reporting period. Total Intensity is the summed initial intensity units of all episodes.');return false">
                <?
                        print "</td></tr>";
    print "<tr align='center'><td>";



    // WHICH SCALE DATA TO COLLECT
    if(isset($_REQUEST['all_scales'])&&$_REQUEST['all_scales']=='all'||$back_clicked){

            print "<table class='table table-bordered'>";

   

                    print"<tr align='center'>\n";
                        print"<th>Carer</th>\n";
                        print"<th>Total Episodes</th>\n";
                        print"<th>Total Duration (min)</th>\n";
                        print"<th>Total Intensity Units</th>\n";
                        print"<th>Emergency Int</th>\n";
                        print"<th>Most Recent Scale Entry</th>\n";
                    print"</tr>\n";


                        print "<tr align='center'>";

                    // USE CAREGIVER DATA TO PULL APPROPRIATE SCALE DATA



                    while($provider=mysqli_fetch_assoc($providers)){
                        $duration = 0;
                        $episodes = 0;
                        $intervention_units = 0;
                        $total_intensity = 0;
                        $prn_count = 0;
                        $personaldatakey = $provider['personaldatakey'];
                        $last_entry = null;
                        // Maybe another day
                        // $sql = "SELECT SUM(duration) AS duration_sum FROM behavior_map_data WHERE date > '$date_start' AND personaldatakey='$personaldatakey'";
                        $sql = "SELECT * FROM behavior_map_data WHERE date > '$date_start' AND personaldatakey='$personaldatakey'";
                        // $sums = mysqli_query($sql, $conn);
                        // $scale_sums = mysqli_fetch_assoc($sums);
                        $map_data=mysqli_query($conn,$sql);
                        while($data_row=mysqli_fetch_assoc($map_data)){

                            if($data_row['date']>$last_entry){
                              $last_entry = $data_row['date'];
                            }
                            $duration += $data_row['duration'];
                            $episodes ++;
                            $total_intensity += $data_row['intensity'];
                            $prn_count += $data_row['PRN'];

                            // for($i=1; $i<=6; $i++){
                            //     $intervention_units += ($intensity-$data_row['intervention_score_'.$i]);
                            //     $intensity = $data_row['intervention_score_'.$i];
                            //     echo($intervention_units);
                            //     echo(',');
                            // }
                        }
                        print"<tr align='center'>\n";
                            // print "<td> $provider[first] $provider[last]</td>\n";
                            print "<td>";
                            print " <a href=\"ABAIT_carer_episode_analysis_v2.php?id=$provider[personaldatakey]&&date_start=$date_start\">$provider[first] $provider[last]</a>";
                            print "</td>\n";
                            print"<input type='hidden' name='date_start' id='date_start' value=$date_start>";
                            print"<input type='hidden' name='date_stop' value=$date_stop>";
                            print "<td> $episodes</td>\n";
                            print "<td> $duration </td>\n";
                            print "<td> $total_intensity </td>\n";
                            print "<td> $prn_count </td>\n";
                            print "<td> $last_entry </td>\n";
                        print "</tr>\n";
                    }

            print "</table>";

    }



    print "</td></tr>";
    print "</table>";

print "<div class='mb-4'>";
print "<p class='backButton'>";
    print "<input   type = 'button'
                name = ''
                class='mb-3'
                id = 'backButton3'
                value = 'Return to Analysis Design'
                onClick=\"backButton1('$Population')\"/>\n";
print "</p>";
print "</div>";

?>


    <div id = "submit">
        <input  type = "submit"
                name = "submit"
                value = "Back to Admin Home Page">
    </div>

    </form>
<?build_footer_pg()?>
</body>
</html>
