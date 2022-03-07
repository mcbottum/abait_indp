<?
include("ABAIT_function_file.php");
ob_start()?>
<?session_start();
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
print $_SESSION['SITE'];
//Make sure sessions are clean
unset($_SESSION['com']);
unset($_SESSION['residentkey']);

?>

</title>

<?
    set_css()
?>

<style>
/*local styles go here*/


</style>
</head>
<body class="container">

    <?  
        $names = build_page_pg();
        if($_SESSION['country_location']=='UK'){
            $behavior_spelling = 'Behaviour';
            $vocalization_spelling = 'Vocalisation';
            $characterization_spelling = 'Characterization';
            $date_format = 'dd-mm-yyyy';
            $catalog_spelling  = 'Catalogue';
        }else{
            $behavior_spelling = 'Behavior';
            $vocalization_spelling = 'Vocalization';
            $characterization_spelling = 'Characterisation';
            $date_format = 'mm-dd-yyyy';
            $catalog_spelling  = 'Catalog';
        }
    ?>


    <h2 class='m-3 p-2 footer_div' align='center'>
        Administrator Home Page
    </h2>

    <div class="row justify-content-md-center">
        <div class='col col-lg-5 mt-4'>
            <div class="dropdown">
<?
                print" <button  class='btn  btn-lg btn-block  dropdown-toggle' id='dropdownMenuButton1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$behavior_spelling Plan Set-Up</button>";
?>
                        
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                        <a class="dropdown-item" href='ABAIT_tutorials_v2.php'>ABAIT Education Module</a>
<?
                        if($_SESSION['privilege']=='globaladmin'){
                            print "<a class='dropdown-item' href='ABAIT_Scale_Create_v2.php'>Create New ABAIT $behavior_spelling Plans</a>";
                        }
                        
                        print "<a class='dropdown-item' href='ABAIT_trigger_v2.php'>Edit or Delete ABAIT $behavior_spelling Plans</a>";
?>

                    </div>
            </div>
        </div>
    </div>






    <div class="row justify-content-md-center ">
        <div class='col col-lg-5 mt-3'>
            <div class="dropdown">
                <button  class="btn  btn-lg btn-block dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Member Enrollment</button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">

                        <?
                        //print "<a class='dropdown-item' href='ABAIT_add_admin_pcs_v2.php'>Enroll Carers and Admins</a>";
                        print "<a class='dropdown-item' href='ABAIT_add_member_pcs_v2.php'>Enroll Members</a>";


                        if($_SESSION['privilege']=='globaladmin'){
                            // print "<a class='dropdown-item' href='ABAIT_add_resident_v2.php'>Enroll New Resident</a>";
                            // print "<a class='dropdown-item' href='ABAIT_add_care_v2.php'>Enroll New Care Provider</a>";
                            // print "<a class='dropdown-item' href='ABAIT_add_admin_v2.php'>Enroll New Administrator</a>";
                            print "<div class='dropdown-divider'></div>";
                            // print "<a class='dropdown-item' href='ABAIT_updateMembers_v2.php'>Update Admins, Caregivers or Residents</a>";
                            print "<a class='dropdown-item' href='ABAIT_remove_members_v2.php'>Remove Admins, Caregivers or Residents</a>";
                            print "<a class='dropdown-item' href='ABAIT_add_admin_pwd_v2.php'>ADD Admin Password</a>";
                            // print "<a class='dropdown-item' href='ABAIT_bulk_enroll_v2.php'>Bulk Enroll Members</a>";
                        }else{
                            // print "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Please contact PCS admin for member enrollment and updates.'>";
                            //     print "<a class='disabled dropdown-item' href='ABAIT_add_resident_v2.php'>Enroll New Resident</a>";
                            //     print "<a class='disabled dropdown-item' href='ABAIT_add_care_v2.php'>Enroll New Care Provider</a>";
                            //     print "<a class='disabled dropdown-item' href='ABAIT_add_admin_v2.php'>Enroll New Administrator</a>";
                            //     print "<div class='dropdown-divider'></div>";
                            //     print "<a class='disabled dropdown-item' href='ABAIT_updateMembers_v2.php'>Update Admins, Caregivers or Residents</a>";
                            //     print "<a class='disabled dropdown-item' href='ABAIT_remove_members_v2.php'>Remove Admins, Caregivers or Residents</a>";
                            // print "</span>";
                            print "<a class='dropdown-item' href='ABAIT_add_admin_pwd_v2.php'>ADD Admin Password</a>";
                            // print "<a class='dropdown-item' href='ABAIT_bulk_enroll_v2.php'>Bulk Enroll Members</a>";

                        }
                        ?>

                    </div>
            </div>



        </div>
    </div>

    <div class="row justify-content-md-center ">
        <div class='col col-lg-5 mt-3'>

            <div class="dropdown">
                <button  class="btn  btn-lg btn-block dropdown-toggle" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Plan Creation and Maintenance</button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
<?
                        print "<a class='dropdown-item' href='ABAIT_ti_catalog_v2.php'>$catalog_spelling of $behavior_spelling Triggers and Interventions</a>";
                        print "<a class='dropdown-item' href='ABAIT_choose_resident_for_map_review_v2.php'>Create and Review Residents' $behavior_spelling Plans</a>";
                        print "<a class='dropdown-item' href='ABAIT_scale_select_pcs_v2.php'>Record New $behavior_spelling</a>";
?>
                        <a class="dropdown-item" href='ABAIT_prn_effect_v2.php'>Record Medication Effect</a>
                    </div>
            </div>

        </div>
    </div>

    <div class="row justify-content-md-center pb-2">
        <div class='col col-lg-5 mt-3'>
            <div class="dropdown">
                <button  class="btn  btn-lg btn-block dropdown-toggle" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Analysis and Education</button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
                        <a class="dropdown-item" href="ABAIT_episode_historical_review_v2.php">Resident Episode Historical Review</a>
                        <a class="dropdown-item" href="ABAIT_resident_for_prn_v2.php">Medication Intervention Review</a>
                        <a class="dropdown-item" href="ABAIT_resident_fact_sheet_v2.php">Resident Fact Sheet</a>
                        <a class="dropdown-item" href="ABAIT_careprovider_review_v2.php">Care Provider Analysis</a>
                        <a class="dropdown-item" href='ABAIT_tutorials_v2.php'>Tutorials</a>
                    </div>
            </div>
        </div>
    </div>



    <? build_footer_pg() ?>


</body>
</html>