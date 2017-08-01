<!-- compliance_report.php
        Written in 2012 by SOH, JM, VF
        Revised in July 2017, VF
        Modifications: Utilizes web services from IEDA partner systems & NSF

    When testing this code - the url parameter test=t should be used to ensure that it's not logged in the stats
-->
<script type="text/javascript" src="/inc/sorttable.js"></script> 
<script>var pfHeaderImgUrl = "http://dev-app.iedadata.org/dcr/imgs/ieda_logo_200x88.png";var pfHeaderTagline = '';
        var pfdisableClickToDel = 0;
        var pfHideImages = 0;
        var pfImageDisplayStyle = 'left';
        var pfDisablePDF = 0;
        var pfDisableEmail = 1;
        var pfDisablePrint = 0;
        var pfCustomCSS = "http://dev-app.iedadata.org/dcr/inc/default.css";
        var pfBtVersion='1';
        (function(){var js,pf;pf=document.createElement('script');pf.type='text/javascript';pf.src='//cdn.printfriendly.com/printfriendly.js';document.getElementsByTagName('head')[0].appendChild(pf)})();
</script>

<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$data_found=0; 
$table="";
$grey="#E8E8E8";
$white="#FFFFFF";
$test='f';
if (!$award_id) $award_id=$_REQUEST['award_id'];
if (isset($award_id)) {
    echo "<br /><div><div style=\"clear:both\">";
    echo "<div style=\"clear:both\"></div></div>";

//FASTLANE AWARD QUERY
//New! gather and display award information leveraging NSF Fastlane API:         	
    $fastlane_text= file_get_contents("http://api.nsf.gov/services/v1/awards/$award_id.xml") or $fastlane_text=NULL;
        $fastlane_xml=simplexml_load_string($fastlane_text);
        $lead_pi=$fastlane_xml->award->piFirstName." ".$fastlane_xml->award->piLastName;
        $award_title=$fastlane_xml->award->title;
        $agency=$fastlane_xml->award->agency;
        $non_nsf=$fastlane_xml->serviceNotification->notificationType;          
    if($non_nsf =="ERROR") { 
            echo "<br /><b>Award ID</b>: $award_id<br/>";           
    } else {
            echo "<br /><b>Award Title</b>: <a href=\"http://www.nsf.gov/awardsearch/showAward.do?AwardNumber=".$award_id."\" target=\"_blank\">".$award_title."</a><br>";
            echo "<b>Principal Investigator</b>: ".$lead_pi."<br/>";
            echo "<b>$agency Award ID:</b> $award_id<br/>";
    }
        //echo "<div style=\"float:left\"><i>URL to this dynamic report: http://{$_SERVER['SERVER_NAME']}/dcr/report?award_id=$award_id</i></div><br />";
        //echo "<div style=\"float:right\"><button type=\"button\" onclick=\"window.open('compliance_report_pdf.php?award_id=$award_id','_blank');return false;\">Download as PDF</button></div>";
        echo "<br /> <div style=\"float:left\"><i>Report URL: http://{$_SERVER['SERVER_NAME']}/dcr/report.php?award_id=$award_id</i></div><br />";         
        echo file_get_contents("http://{$_SERVER['SERVER_NAME']}/dcr/dcr_table.php?award_id=$award_id");         
        
}
?>	
<!-- PDF-->
<a href="https://www.printfriendly.com" style="color:#6D9F00;text-decoration:none;" class="printfriendly" onclick="window.print();return false;" title="Printer Friendly and PDF"><img style="border:none;-webkit-box-shadow:none;box-shadow:none;" src="//cdn.printfriendly.com/buttons/printfriendly-pdf-button.png" alt="Print Friendly and PDF"/></a>