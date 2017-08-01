
<?php
//dcr_table.php
//        Written in 2012 by SOH, JM, VF
//        Revised in July 2017, VF
//        Modifications: Utilizes web services from IEDA partner systems & NSF
//
////stats tracking:		
    $gettest=$_REQUEST['internal'];
    if ($gettest!='true') {	
        file_get_contents("http://app.iedadata.org/dcr_stats.php?ip={$_SERVER['REMOTE_ADDR']}&award={$_GET['award_id']}");  
    } else {
        echo "test!! no stats tracked";
    }
$data_found=0; 
$table="";
$grey="#E8E8E8";
$white="#FFFFFF";
$test='f';
if (!$award_id) $award_id=$_REQUEST['award_id'];
if (isset($award_id)) {
// MGDS
    $mgds_text=@file_get_contents("http://www.marine-geo.org/services/xml/datasetpageservice.php?award_id=$award_id");
        if($mgds_text) {
        $mgds_xml=simplexml_load_string($mgds_text);
        $content=$mgds_xml->xpath(data_sets);
        if (count($content)) {
             $data_found=1;
            foreach($mgds_xml->data_sets->data_set as $mgdsdset) {
            $repository=$mgdsdset->repositories->repository['name'];
            if ($repository == 'MGDS'){
                $mgds_datatype=$mgdsdset['data_type'];
                $mgds_creator=$mgdsdset['investigators'];
                $mgds_url=$mgdsdset['url'];
                $mgds_link="<a href=\"$mgds_url\">View Data</a>";
                $mgds_title=$mgdsdset['title'];
                    if (isset($mgds_title)==0) {
                        $platform_type=$mgdsdset['platform_type'];
                        $platform_id=$mgdsdset['platform_id'];
                        $cruise=$mgdsdset->ds_entry['id'];                
                        $mgds_title="Acquired during $cruise";
                    }
                $row_color=($row_color==$grey)?$white:$grey; 
                $table.="<tr bgcolor=\"$row_color\"><td id=\"c1\">$repository</td><td id=\"c2\">$mgds_datatype</td><td id=\"c3\">$mgds_creator</td><td id=\"c4\">$mgds_title</td><td id=\"c5\">$mgds_link</td></tr>";
                }
            }
        }
    }
//USAP
    $usap_text=@file_get_contents("http://api.usap-dc.org/get_datasets?award_id=$award_id") or $usap_xml=NULL;
    if($usap_text && strpos($usap_text,"ERROR:")===FALSE) {
        $usap_xml=simplexml_load_string($usap_text);   
        $data_found=1;
        $usap_array=$usap_xml->dataset;
        for ($d=0;$d<count($usap_array);$d++){
                $usap_id=$usap_array[$d]->id;
                $usap_title=$usap_array[$d]->title;
                $usap_creator=$usap_array[$d]->creator;
                $usap_url=$usap_array[$d]->url;
                $usap_link="<a href=\"$usap_url\">View Data</a>";
            $row_color=($row_color==$grey)?$white:$grey; 
            $table.="<tr bgcolor=\"$row_color\"><td id=\"c1\">USAP-DC</td><td id=\"c2\">Polar</td><td id=\"c3\">$usap_creator</td><td id=\"c4\">$usap_title</td><td id=\"c5\">$usap_link</td></tr>";
        }
    }
//EARTHCHEM
    $sample_url="http://app.iedadata.org/ws/gfgDataLinkList.php?num=$award_id&format=json";
    $samples_json=@file_get_contents($sample_url);
    if($samples_json) {
	$samples = json_decode($samples_json,true);
	if (count($samples)) 
        $data_found=1;
        for ($d=0;$d<count($samples);$d++) {
            $ec_data_collection = $samples[$d]['Data Collection']['text'];
            $ec_data_type = $samples[$d]['Data Type']['text'];
            $repo_link_text = $samples[$d]['Repository Data Link']['text'];
            $repo_link_url = $samples[$d]['Repository Data Link']['url'];
            $ec_url= "<a href=\"$repo_link_url\">View Data</a>";
            //This line may need to be 'Title\/Description' with the backslash. 
            //Not sure how json_decode will decode an escaped forward slash
            //Also, special characters and forward slashes should not be included in json attribute names?
            $ec_title = $samples[$d]['Title/Description']['text'];
            $row_color=($row_color==$grey)?$white:$grey; 
            $table.="<tr bgcolor=\"$row_color\"><td id=\"c1\" >$ec_data_collection</td><td id=\"c2\">$ec_data_type</td><td id=\"c3\">$repo_link_text</td><td id=\"c4\">$ec_title</td><td id=\"c5\">$ec_url</td></tr>";
        }
    }               
        if($data_found==1) {
            echo "<table><tr><th id=\"c1\">Data Collection</th><th id=\"c2\">Data Type(s)</th><th id=\"c3\">Investigator(s)</th><th id=\"c4\">Title/Description</th><th id=\"c5\">Link</th></tr><tr>";
            echo $table;
            echo "</table>";            
        } elseif($data_found==0) {
            print "<br />No data currently in IEDA Systems currently linked to Award $award_id. Please contact us if you believe this is an error.";
        }
}
?>	
