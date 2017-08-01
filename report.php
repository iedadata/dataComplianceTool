<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>IEDA: Data Compliance Report</title>
<meta name="Description" content="IEDA - Data Compliance Report. Invetigator support for demonstrating compliance with NSF Data Sharing Policies." />
<meta name="Keywords" content="Data Compliance, IEDA, Investigator Support, Earth Science, Ocean Science, Polar Science" />
<script type="text/javascript" src="inc/sorttable.js"></script>
<link type="text/css" rel="stylesheet" media="all" href="inc/defaults.css" />
<script src="http://code.jquery.com/jquery-1.5.1.js"></script>
<script type="text/javascript">
    var awrd = <?php echo (($_GET['award_id'])?"'{$_GET['award_id']}'":0); ?>;
    var internal = <?php echo (isset($_GET['internal']) && $_GET['internal']=='true')?'true':'false';?>;
    function getAward(award,internal) {
        $('#returncontent').html('<img style="text-align:center;margin:20px" src="imgs/ajax-loader.gif" alt="Loading" />');
        $.ajax({
            type: "POST",
            url: "report_page.php",
            data: 'award_id='+award+'&internal='+internal,
            success: function(msg){
                $('#returncontent').html(msg);
            }
        });
    }
    $(document).ready(function(){
        if (awrd) {
            getAward(awrd,internal)
        }
        $('#getAward').click(function(){
            getAward($('#award_id').val(),internal);
        });
        sorttable.makeSortable(document.getElementById('ieda_file'));
    });	
</script>
    <a href="http://www.iedadata.org">
        <img id="ieda_logo" href="http://www.iedadata.org" src="imgs/ieda_logo_200x88.png" alt="iedalogo"/>
    </a>
</head>
<body>


    
<div id="wrapper">
<div id="content">
     <h1>Data Compliance Reporting Tool</h1>	
         <div style="padding-left:20px"; "padding-right:20px"><b>Instructions</b>: 
             The IEDA Data Compliance Report Tool enables the easy preparation of 
             reports to demonstrate compliance with Data Policies.  Enter an award 
             ID and this service will provide a list of related data sets and their 
             release status. Note that data will only be returned for awards that are 
             currently cataloged within IEDA, and data sets returned are based on data 
             and metadata received to date. 
             Please <a href='http://www.iedadata.org/contact'>contact us</a> with 
             comments or questions, or to <a href='http://www.iedadata.org/contribute'>submit additional data or metadata</a>.</div>
            <form name='description_form'>
            <fieldset><legend><b>Enter Award ID</b></legend>
            <input type="text" name="award_id" id="award_id" /> <button type="button" id="getAward">Submit</button><br>
            <em>Locate an Award through the <a href=" http://nsf.gov/awardsearch/ " target="_blank">NSF Fastlane Award Search</a></em><br/></fieldset>
    <div id='returncontent' style="width:100%"></div>

</div>
<?php echo file_get_contents("http://{$_SERVER['SERVER_NAME']}/dcr/inc/ieda_footer.php"); ?>
</div>
