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

<script>
function providerList(date, residentkey=null) {
	// var checkedValue = document.getElementById(id).value;

	self.location='ABAIT_careprovider_review_analysis_v2.php?bk_ds='+date;
}  

</script>

<style>

    .table th {
    background-color: lightgrey;
    }

    p.backButton {
      float:right;
    }
    .border-spacing {
        border-spacing: 5px;
    }

</style>
</head>

<? 
$names = build_page_pg();

if($_SESSION['cgfirst']!=""){
    $cgfirst=$_SESSION['cgfirst'];
    $cglast=$_SESSION['cglast'];
    }else{
    $cgfirst=$_SESSION['adminfirst'];
    $cglast=$_SESSION['adminlast'];
    }


if($_SESSION['country_location']=='UK'){
    $behavior_spelling = 'Behaviour';
    $vocalization_spelling = 'Vocalisation';
    $characterization_spelling = 'Characterization';
    $date_format = 'dd-mm-yyyy';
}else{
    $behavior_spelling = 'Behavior';
    $vocalization_spelling = 'Vocalization';
    $characterization_spelling = 'Characterisation';
    $date_format = 'mm-dd-yyyy';
}

$date=date('Y-m-d');
$date_stop = $date;

if(isset($_REQUEST['scale_totals'])){
    $scale_totals=$_REQUEST['scale_totals'];
}else{
    $scale_totals=Null;
}
if(isset($_REQUEST['behavior_units'])){
    $behavior_units=$_REQUEST['behavior_units'];
}else{
    $behavior_units=Null;
}
if(isset($_REQUEST['behavior_units_per_time'])){
    $behavior_units_per_time=$_REQUEST['behavior_units_per_time'];
}else{
    $behavior_units_per_time=Null;
}
if(isset($_REQUEST['intervention_effect'])){
    $intervention_effect=$_REQUEST['intervention_effect'];
}else{
    $intervention_effect=Null;
}

$trigger_breakdown = 1;
$all_episode = 1;
$episode_time_of_day = 1;
$scale_totals = 1;

$conn = make_msqli_connection();

if(isset($_REQUEST['id'])){
    $personaldatakey=$_REQUEST['id'];
}else{
    $personaldatakey=Null;
}

if(isset($_REQUEST['date_start'])){
    $date_start = $_REQUEST['date_start'];
}else{
    $date_start=Null;
}

$sql="SELECT * FROM personaldata WHERE personaldatakey='$personaldatakey'";
$session=mysqli_query($conn,$sql);
$provider=mysqli_fetch_assoc($session);

if($date_start < '1991-1-1'){
    $title="ALL Episode Analysis for Residents of  $provider[first] $provider[last]";
}else{
    $title="Episode Analysis for Residents of  $provider[first] $provider[last] during interval: $date_start - $date_stop";
}

$scale_array=$_SESSION['scale_array'];

foreach($scale_array as $value){
    $sum_behaviorarray[$value]=0;
}//end foreach


$sql="SELECT * FROM behavior_map_data WHERE personaldatakey='$personaldatakey' AND date > '$date_start' ";

$session=mysqli_query($conn,$sql);
while($row=mysqli_fetch_assoc($session)){
    $residentkey_array[]=$row['residentkey'];
}
if($residentkey_array){
    $sql="SELECT * FROM residentpersonaldata WHERE residentkey IN ('".implode("', '", $residentkey_array)."')";
}else{
    $sql="SELECT null FROM residentpersonaldata";
}

 print"<h4 class='m-3 p-2 footer_div' align='center'> $title </h4>";


print "<table  width='99%'>";
    print "<tr class='border-spacing'><td  align='right'>";
        print "<form><INPUT TYPE=\"button\" value=\"Print Page\" onClick=\"window.print()\"></form>";
    print "</td></tr>";

    print "<tr class='m-1'>";
      print "<td align='right'>";
        print "<input	type = 'button'
              name = 'add_none'
              id = 'add_none1'
              value = 'Return to Provider List'
              onClick=\"providerList('$date_start')\"/>\n";
        print "</td>";
      print "</tr>";
print "</table>";



if($all_episode){//////////////////////////////////////////all_episode/////////////////////////////////////////

    $sql="SELECT * FROM behavior_map_data WHERE personaldatakey=$personaldatakey AND date > '$date_start'";
    $behavior_map_data_session=mysqli_query($conn,$sql);

print"<div><h4 align='center'>Episode List</h4></div>\n";

    print "<table class='table' width='100%'>";//
        print "<tr><td>";//table in table data for more info
            print "<table class='table' border='1' bgcolor='white'>";

                    print"<tr  align='center'>";
                        print"<th>Resident</th>";
                        print"<th>Date</th>";
                        print"<th>Time of Day</th>";
                        print"<th>$behavior_spelling Classification</th>";
                        print"<th>Trigger</th>\n";
                        print"<th>Episode Duration</th>";
                        if($_SESSION['population_type']==='behavioral'){
                            print"<th>Police Int</th>";
                        }else{
                            print"<th>Medication</th>";
                        }
                    print"</tr>";
                if($residentkey_array){
                    while($behavior_map_data_row=mysqli_fetch_assoc($behavior_map_data_session)){
                        $sql1="SELECT trig FROM behavior_maps WHERE mapkey='$behavior_map_data_row[mapkey]'";
                        $sql2="SELECT * FROM residentpersonaldata WHERE residentkey='$behavior_map_data_row[residentkey]'";
                        $behavior_maps_session=mysqli_query($conn,$sql1);
                        $residentpersonaldata_session=mysqli_query($conn,$sql2);
                        $behavior_maps_row=mysqli_fetch_assoc($behavior_maps_session);
                        $residentpersonaldata_row=mysqli_fetch_assoc($residentpersonaldata_session);


                        print"<tr align='center'>";
                            print"<td >$residentpersonaldata_row[first] $residentpersonaldata_row[last]</td>";
                            print"<td>$behavior_map_data_row[date]</td>";
                            print"<td>$behavior_map_data_row[time]</td>";
                            print"<td>$behavior_map_data_row[behavior]</td>";
                            print"<td>$behavior_maps_row[trig]</td>";
                            print"<td>$behavior_map_data_row[duration]</td>";
                            if($row['PRN']==1){
                                print"<td>Yes</td>";
                            }else{
                                print"<td>None</td>";
                            }
                        print"</tr>";

                    } // end while
                }else{
                    print"<tr align='center'>";
                        print"<td> No Data Recorded</td>";
                    print "</tr>";
                }
            print "</table>";
        print "</td></tr>";
    print "</table>";

}//end all_epsisode if

if($trigger_breakdown){ ////////////////////////////////////////trigger breakdown//////////////////////////////////////

    print"<div id='head'><h4 align='center'>Trigger and Intervention Analysis</h4></div>\n";
    $r=0;
    foreach($scale_array as $behavior){

        $trigger_count=0;
        $trigger_duration=NULL;
        if($residentkey_array){
            $sql2="SELECT * FROM behavior_maps WHERE behavior='$behavior' AND residentkey IN ('".implode("', '", $residentkey_array)."')";
        }else{
            $sql2="SELECT null FROM behavior_maps";
        }

        $session2=mysqli_query($conn,$sql2);

        print "<table class='table' width='100%'>";
            print "<tr><td>";
                print "<table min-width='800px' class='table' border='1'>";

                        print"<tr align='center'><th colspan='5'>$behavior Behavior Episodes</th></tr>\n";
                        print"<tr align='center'>";
                            print"<th>----Trigger----</th>";
                            print"<th>Number of Episodes</th>";
                            print"<th>Duration of Episodes</th>";
                            print"<th>Most Effective Intervention</th>";
                            print"<th>Graph</th>";
                        print"</tr>";

                    if (!mysqli_fetch_assoc($session2)){
                        print "<tr align='center'><td colspan='5'>$behavior Episodes have not been recorded</td></tr>";
                    }
                    if($residentkey_array){
                        while($row2=mysqli_fetch_assoc($session2)){
                            $intervention_array=null;
                            $trigger_array[]=$row2['trig'];
                            $episodes=0;
                            $duration=0;
                            $intv=0;
                            $intv1=0;
                            $intv2=0;
                            $intv3=0;
                            $intv4=0;
                            $intv5=0;
                            $intv6=0;
                            print"<tr align='center'>";
                                print "<td> $row2[trig] </td>";

                                $sql3="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND behavior='$behavior' AND personaldatakey=$personaldatakey";

                                $session3=mysqli_query($conn,$sql3);
                                $best=Null;
                                while($row3=mysqli_fetch_assoc($session3)){
                                    if($row2['mapkey']==$row3['mapkey']){
                                        $episodes=$episodes+1;
                                        $duration=$duration+$row3['duration'];
                                        $intv1=$intv1+$row3['intervention_score_1'];
                                        $intv2=$intv2+$row3['intervention_score_2'];
                                        $intv3=$intv3+$row3['intervention_score_3'];
                                        $intv4=$intv4+$row3['intervention_score_4'];
                                        $intv5=$intv5+$row3['intervention_score_5'];
                                        $intv6=$intv6+$row3['intervention_score_6'];
                                    }
                                }//end invtervention while

                                $trigger_duration[$row2['trig']]=$duration;
                                $intv=0;
                                for ($s=1;$s<7;$s++){
                                    if(${'intv'.$s}<0){
                                        ${'intv'.$s}=0;
                                    }
                                    if($intv<${'intv'.$s}){
                                        $intv=${'intv'.$s};
                                        $best=$s;
                                    }
                                    if($row2['intervention_'.$s]){
                                        $intervention_array[$row2['intervention_'.$s]]=${'intv'.$s};
                                    }
                                }
                                $values[]=$intervention_array;

                                print"<td>$episodes</td>";
                                print"<td>$duration</td>";

                                $best_int='intervention_'.$best;
                                if(isset($best)){
                                    print"<td>$row2[$best_int]</td>";
                                    print"<td align=center><INPUT class='icon' height='35' type=\"image\" src=\"Images/pie_icon.png\"  onClick=\"window.open('behaviorgraph'+$r+'.png','','width=700px,height=400px')\"></td>";
                                    $graphTitle='Relative Effectiveness of '.$trigger_array[$r].' Interventions';
                                    $yLabel='Relative Effectiveness';
                                    ABAIT_pie_graph($values[$r], $graphTitle, $yLabel,$r);
                                }else{
                                    print"<td></td>";
                                    print"<td align=\"center\">No Interventions Logged</td>";
                                }


                                print"</td>";
                            print "</tr>";

                            $trigger_count=$trigger_count+1;
                            $r=$r+1;
                        }//end row2 while for each trigger
                    }else{
                    print"<tr align='center'>";
                        print"<td> No Data Recorded</td>";
                    print "</tr>";
                    }

                print "</table>";

            // print "<td align='center'><input type='submit' value='Tap for more Info' onClick=\"alert('This is the thirty day global analysis of care provider: .  The table provides information specifically related to the behaviors classifed as; $behavior.  Effectiveness is determined by the greatest reduction in behavor intensity during this period.  The graph will display all interventions applied, where slize size represents relative effectiveness.');return false\">";
            print "</td></tr>";
        print "</table>";
    }//end foreach

}// end trigger_breakdown if

if($episode_time_of_day){///////////////////////////////////////time of day//////////////////////////////////////////
        $i=0;
        $sum_duration = 0;
        unset($sql_array);

        if($residentkey_array){
            ${'sql_all'}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND personaldatakey=$personaldatakey AND residentkey IN ('".implode("', '", $residentkey_array)."')";
        }else{
            $sql="SELECT null FROM behavior_map_data";
        }

        $sql_array[]=$sql_all;

        $episode_start_array=array(7,10,13,16,19,22,1,4);//hours for shifts
                //$episode_end_array=array(10,13,19,22,1,4,7);
    if($residentkey_array){
        for($j=0;$j<count($sql_array);$j++){
                    foreach($episode_start_array as $i){
                        ${'episode_count'.$i}=0;
                        ${'sum_duration'.$i}=0;
                    }
                    $session=${'session'.$j};
                    $session=mysqli_query($conn,$sql_array[$j]);

                    while(${'row'.$j}=mysqli_fetch_assoc($session)){
                        $sum_duration=${'row'.$j}['duration']+$sum_duration;
                            foreach($episode_start_array as $i){
                                if($i*10001<=str_replace(':','',${'row'.$j}['time'])&&str_replace(':','',${'row'.$j}['time'])<=($i+3)*10000){
                                    ${'episode_count'.$i}=${'episode_count'.$i}+1;
                                    ${'sum_duration'.$i}=${'row'.$j}['duration']+${'sum_duration'.$i};
                                }
                                ${'episode_count_array'.$j}[$i]=${'episode_count'.$i};
                                ${'sum_duration_array'.$j}[$i]=${'sum_duration'.$i};
                            }
                    }
    // section for printing episode time of day table follows

        if($j==count($sql_array)-1&&in_array($sql_all,$sql_array)){
            print"<div id='head'><h5 align=center>Episode per Time of Day for <em>All</em> Triggers</h5></div>\n";
        }else{
            print"<div id='head'><h5 align=center>Episode per Time of Day for <em>${'behave_'.$j}</em> Triggers Since <em>$date_start</em></h5></div>\n";
        }
        print "<table class='table' width='100%'>";//table for more info copy this line
                print "<tr><td>";//table in table data for more info
                    print "<table class='table' border='1' >";

                            print "<tr>";
                                print "<th>Time Interval (Hr of Day)</th>";
                                foreach($episode_start_array as $i){
                                    $k=$i+3;
                                    if($k==25){
                                        $k=1;
                                    }
                                    print "<th align='center' width='50'>$i-$k</th>";
                                }
                                print "<th align='center'>Graph</th>";
                            print "</tr>";

                            print "<tr align='center'>";
                                print "<td>Total Episodes</td>";
                                foreach($episode_start_array as $i){
                                    print "<td>${'episode_count'.$i}</td>";
                                }
                                print"<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraph'+($j+50)+'.png','','width=700px,height=400')\"></td>";
                            print "</tr>";
                            print "<tr align='center'>";
                                print "<td>Total Episode Duration (min)</td>";
                                    foreach($episode_start_array as $i){
                                            print "<td>${'sum_duration'.$i}</td>";
                                    }

                                print "<td><INPUT class='icon' height='35' type=\"image\" src=\"Images/chart_icon.png\" onClick=\"window.open('behaviorgraph'+($j+100)+'.png','','width=700px,height=400')\"></td>";
                            print "</tr>";

                print"</table></td>";
            //call graph function
                $values_bar_e=${'episode_count_array'.$j};
                $graphTitle_bar='Count of Episodes per Three Hour Interval';
                $yLabel_bar=' Episode Count';
                $xLabel_bar='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|';
            if(count($values_bar_e)!=0){
            ABAIT_bar_graph($values_bar_e, $graphTitle_bar, $yLabel_bar,$xLabel_bar,$j+50);
            }
            //call graph function
                $values_bar_d=${'sum_duration_array'.$j};
                $graphTitle_bar='Duration of Behavior Episodes per Three Hour Interval';
                $yLabel_bar='Total Episode Duration (minutes)';
                $xLabel_bar='|-------Day Shift-------||------PM Shift------||-----Night Shift-----|';
            if(count($values_bar_d)!=0){
            ABAIT_bar_graph($values_bar_d, $graphTitle_bar, $yLabel_bar,$xLabel_bar,$j+100);
            }

                    // print "<td align=center><table><tr><td><input type='submit' value='Tap for more Info' onClick=\"alert('This table reports behavior episodes by time of day.  Time of day is broken down into three hour intervals.');return false\">";
                    // print "</td>";
                    print "</tr>";

                print "</table></td></tr></table>";

        }//end for
    }
}//end if

if($scale_totals){///////////////////////scale totals////////////////////////////
        $i=0;
        unset($sql_array);
        if($residentkey_array){
            ${'sql_all'}="SELECT * FROM behavior_map_data WHERE date > '$date_start' AND personaldatakey=$personaldatakey AND residentkey IN ('".implode("', '", $residentkey_array)."')";
        }else{
            $sql="SELECT null FROM behavior_map_data";
        }

    $sql_array[]=$sql_all;
    if($residentkey_array){
        for($j=0;$j<count($sql_array);$j++){

            $sum_duration=0;
            $sum_PRN=0;
            $sum_episodes=0;
            //$row=null;
            $session=${'session'.$j};
            $session=mysqli_query($conn,$sql_array[$j]);
            while(${'row'.$j}=mysqli_fetch_assoc($session)){
                $sum_duration=${'row'.$j}['duration']+$sum_duration;
                $sum_PRN=${'row'.$j}['PRN']+$sum_PRN;
                $sum_episodes=$sum_episodes+1;
                foreach($scale_array as $behavior){
                    if(${'row'.$j}['behavior']==$behavior){
                        $sum_behaviorarray[$behavior]=$sum_behaviorarray[$behavior]+${'row'.$j}['duration'];
                    }
                }//end behaviorarray foreach
            }
            if($j==count($sql_array)-1&&in_array($sql_all,$sql_array)){
                print"<div id='head'><h5 align=center>Scale Totals for <em>All</em> Triggers Since <em>$date_start</em></h5></div>\n";
            }else{
                print"<h3>Scale Totals for <em>${'behave_'.$j}</em> Triggers Since <em>$date_start</em></h3>\n";
            }
            print "<table class='table' width='100%'>";//
                print "<tr><td>";//table in table data for more info

                    print "<table class='table' border='1' >";

                            print"<tr align='center'>\n";
                                print"<th>Start Date</th>\n";
                                print"<th>End Date</th>\n";
                                print"<th>Total Episodes</th>\n";
                                print"<th>Total Duration of Episodes</th>\n";
                                if($_SESSION['population_type']==='behavioral'){
                                    print"<th>Police Int</th>";
                                }else{
                                    print"<th>Medication</th>";
                                }
                            print"</tr>\n";

                            print"<tr align='center'>\n";
                                print"<td>$date_start</td>\n";
                                print"<td>$date_stop</td>\n";
                                print"<td>$sum_episodes</td>\n";
                                print"<td>$sum_duration</td>\n";
                                print"<td>$sum_PRN</td>\n";
                            print"</tr>\n";

                    print "</table>";
                print "</td>";
            //table data for more info
            //call graph function

            $values_bar=$sum_behaviorarray;
            $graphTitle_bar='Duration of Behavior Episodes vs. Behavior';
            $yLabel_bar='Total Duration (minutes)';
            $xLabel_bar='Behaviors';

            ABAIT_bar_graph($values_bar, $graphTitle_bar, $yLabel_bar,$xLabel_bar,'bar');
                    // print "<td><input type='submit' value='Tap for more Info' onClick=\"alert('This is the thirty day global analysis of your resident selected.  The analysis provides information about total minutes of epsisodes and total minutes of episodes per trigger.  Additionally, the anlysis provides information about most effective interventions of each of the triggers.');return false\">";
                    // print "</td>";

                print "</tr>";
                print"<tr align='right'>";
                    print"<td colspan='2'>";
                      print "<input type = 'button'
                            name = 'add_none'
                            id = 'add_none1'
                            value = 'Return to Provider List'
                            onClick=\"providerList('$date_start')\"/>";

                    print"</td>";
                print"</tr>";
            print "</table>";

// print "<div class='mb-4'>";
// print "<p class='backButton'>";
//     print "<input   type = 'button'
//                 name = ''
//                 class='mb-3'
//                 id = 'backButton3'
//                 value = 'Return to Analysis Design'
//                 onClick=\"backButton1('$Population')\"/>\n";
// print "</p>";
// print "</div>";



        }//for($j=0;$j<count($sql_array);$j++){
    }
}

?>

<?build_footer_pg()?>

</body>
</html>
