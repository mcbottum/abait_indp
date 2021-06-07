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
print $_SESSION['SITE']
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
    ?>


    <h2 class='m-3 p-2 footer_div' align='center'>
        Administrator Home Page
    </h2>

    <div class="row justify-content-md-center">
        <div class='col col-lg-5 mt-4'>
            <div class="dropdown">
                <button  class="btn  btn-lg btn-block  dropdown-toggle" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Behavior Plan Set-Up</button>
                        
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">

                        <a class="dropdown-item" href='ABAIT_tutorials_v2.php'>ABAIT Education Module</a>
                        <?
                        if($_SESSION['privilege']=='globaladmin'){
                            print "<a class='dropdown-item' href='ABAIT_Scale_Create_v2.php'>Create New ABAIT Behavior Plans</a>";
                        }
                        ?>
                        <a class="dropdown-item" href="ABAIT_trigger_v2.php">Edit or Delete ABAIT Behavior Plans</a>

                    </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-md-center ">
        <div class='col col-lg-5 mt-3'>
            <div class="dropdown">
                <button  class="btn  btn-lg btn-block dropdown-toggle" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Member Enrollment</button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">

                        <a class="dropdown-item" href="ABAIT_add_resident_v2.php">Enroll New Resident</a>
                        <a class="dropdown-item" href="ABAIT_add_care_v2.php">Enroll New Care Provider</a>
                        <a class="dropdown-item" href="ABAIT_add_admin_v2.php">Enroll New Administrator</a>
                        <div class="dropdown-divider"></div>
                        <a class='dropdown-item' href='ABAIT_updateMembers_v2.php'>Update Admins, Caregivers or Residents</a>
                        <a class='dropdown-item' href='ABAIT_remove_members_v2.php'>Remove Admins, Caregivers or Residents</a>

                    </div>
            </div>



        </div>
    </div>

    <div class="row justify-content-md-center ">
        <div class='col col-lg-5 mt-3'>

            <div class="dropdown">
                <button  class="btn  btn-lg btn-block dropdown-toggle" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Plan Creation and Maintenance</button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                        <a class="dropdown-item" href='ABAIT_ti_catalog_v2.php'>Catalog of Behavior Triggers and Interventions</a>
                        <a class="dropdown-item" href="ABAIT_choose_resident_for_map_review_v2.php">Create and Review Residents' Behavior Plans</a>
                        <a class="dropdown-item" href="ABAIT_quick_scales_v2.php">Record New Behavior</a>
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
                    </div>
            </div>
        </div>
    </div>



    <? build_footer_pg() ?>


</body>
</html>