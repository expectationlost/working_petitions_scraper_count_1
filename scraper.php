<?php
require 'scraperwiki/simple_html_dom.php';
$councillors = array();
#copied alot from john Handelaar's https://classic.scraperwiki.com/scrapers/iecouncillorsphptemplate/
$Received = 0;
$NonAdmissible = 0;
$PetitionClosed = 0;
$ComplianceStandOrder = 0;
$DecisionComm = 0;
$ConsiderationComm = 0;
$ReferDeptStakeOmbuds = 0;
$DeliberationsComm = 0;
$status = "";


for ($i = 72; $i <= 74; $i++) {
 #   $html = scraperWiki::scrape("https://wx.toronto.ca/inter/pmmd/callawards.nsf/posted+awards?OpenView&Start=1&Count=1000&Expand=3.".$i);
$html = scraperWiki::scrape("http://petitions.oireachtas.ie/online_petitions.nsf/Published_Petitions_EN?openview&start=" . $i . "&count=1"); #&start=30&count=15
    $dom = new simple_html_dom();
    $dom->load($html);

   # foreach ($dom->find('a[href*=OpenDocument]') as $a) {
      $rows=$dom->find("div[id=viewbody] table tr");
unset($rows[0]);
foreach($rows as $row) {
$refnumbercell = $row->find("td",0);
$urlcell = $row->find("td a",0)->href;
#print $urlcell;
$urlcell = strip_tags($urlcell);
$urlcell = ltrim($urlcell,'.');

#print "\n" . "urlcell" . $urlcell;
    $url = "http://petitions.oireachtas.ie/online_petitions.nsf" . $urlcell; # ->href;
#print "\n" . "url" . $url;

#http://petitions.oireachtas.ie/online_petitions.nsf./Published_Petitions_EN/4B1C5516D35CB1A880257AE9005FC246?OpenDocument&type=published+petition&lang=EN&r=0.651346608682383
#http://petitions.oireachtas.ie/online_petitions.nsf/Published_Petitions_EN/70244C3CADEE670880257ABE00553093?OpenDocument&type=published+petition&lang=EN&r=0.221820401312276
    $refnumber = strip_tags($refnumbercell);
    $namecellcontents = $row->find("td",1);
    $namecell = trim(strip_tags($namecellcontents->innertext));
    $name = trim(str_replace(" / ","/",trim($namecell)));
    $titlecell = $row->find("td",2);
    $title = trim($titlecell->plaintext);
    $title = str_replace("&amp;","&",$title);
print "titlep" . $title . "trimcheck" . "\n";
    $laststatuscell = $row->find("td",3);
    $laststatus = trim($laststatuscell->plaintext);
print "mainstatus" . $laststatus . "trimcheck". "\n";
#$status = "boo";
        //var_dump($thisrecord['description']);
        //print mb_detect_encoding($thisrecord['description'])."\n";

#print "name" . $name . "trimcheck". "\n";
#print "mainstatus" . $status . "trimcheck". "\n";


    $moredetails = get_extras($url, $laststatus);


}
$councillors["$refnumber"] = array(
              "Url"   => "$url",
              "Name"   => "$name",
                    "Title"   => "$title",
                  "LastStatus"   => "$laststatus",
                 #   "Email"   => "councillor@example.com",
                #    "Phone"   => "01 100 1000",
                #    "Mobile"  => "085 000 0000",
                #    "Image"   => "http://URI",
                "Submittedby" => $moredetails["submittedby"],
     "Petitiontext" => $moredetails["petitiontext"],
     "Date1" => $moredetails["date1"],
     "Date2" => $moredetails["date2"],
 "Date3" => $moredetails["date3"],
     "Date4" => $moredetails["date4"],
     "Date5" => $moredetails["date5"],
     "Date6" => $moredetails["date6"],
     "Date7" => $moredetails["date7"],
 "Date8" => $moredetails["date8"],
    "Date9" => $moredetails["date9"],
 "Date10" => $moredetails["date10"],
    "Date11" => $moredetails["date11"],
 "Date12" => $moredetails["date12"],
"Status1" => $moredetails["status1"],
"Status2" => $moredetails["status2"],
"Status3" => $moredetails["status3"],
"Status4" => $moredetails["status4"],
"Status5" => $moredetails["status5"],
"Status6" => $moredetails["status6"],
"Status7" => $moredetails["status7"],
"Status8" => $moredetails["status8"],
"Status9" => $moredetails["status9"],
"Status10" => $moredetails["status10"],
"Status11" => $moredetails["status11"],
"Status12" => $moredetails["status12"],
     "Corpname" => $moredetails["corpname"],
"Published" => $moredetails["published"],
"Received" => $moredetails["Received"],
"NonAdmissible" => $moredetails["NonAdmissible"],
"PetitionClosed" => $moredetails["PetitionClosed"],
"ComplianceStandOrder" => $moredetails["ComplianceStandOrder"],
"ConsiderationComm" => $moredetails["ConsiderationComm"],
"DeliberationsComm" => $moredetails["DeliberationsComm"],
"ReferDeptStakeOmbuds" => $moredetails["ReferDeptStakeOmbuds"],
"DecisionComm" => $moredetails["DecisionComm"]



                    );




}
#}

scraperwiki::sqliteexecute("drop table councillors");
scraperwiki::sqliteexecute("create table if not exists councillors (`url` string,`name` string, `refnumber` string, `title` string, `laststatus` string, `submittedby` string, `petitiontext` string, `status1` string, `status2` string, `status3` string, `status4` string, `status5` string, `status6` string, `status7` string, `status8` string, `status9` string, `status10` string, `status11` string, `status12` string, `date1` string, `date2` string, `date3` string, `date4` string, `date5` string, `date6` string, `date7` string, `date8` string, `date9` string, `date10` string, `date11` string, `date12` string, `corpname` string, `published` string, `Received` string, `NonAdmissible` string, `PetitionClosed` string, `ComplianceStandOrder` string, `ConsiderationComm` string, `DeliberationsComm` string, `ReferDeptStakeOmbuds` string, `DecisionComm` string)"); #, `email` string, `phone` string, `mobile` string, `image` string,  `address` string)");
scraperwiki::sqlitecommit(); 

#Received
#NonAdmissible
#PetitionClosed
#ComplianceStandOrder
#ConsiderationComm
#DeliberationsComm
#DecisionComm
#ReferDeptStakeOmbuds
#


foreach ($councillors as $refnumber => $values) {
    scraperwiki::sqliteexecute("insert or replace into councillors values (:url, :name, :refnumber, :title, :laststatus, :submittedby, :petitiontext, :status1, :status2, :status3, :status4, :status5, :status6, :status7, :status8, :status9, :status10, :status11, :status12, :date1, :date2, :date3, :date4, :date5, :date6, :date7, :date8, :date9, :date10, :date11, :date12, :corpname, :published, :Received, :NonAdmissible, :PetitionClosed, :ComplianceStandOrder, :ConsiderationComm, :DeliberationsComm, :ReferDeptStakeOmbuds, :DecisionComm)",  #, :email, :phone, :mobile, :image, :address)", 
            array( 
 "url"     => $values["Url"],
                    "name"     => $values["Name"],
                    "refnumber"    => $refnumber,
                    "title"   => $values["Title"],
                    "laststatus"   => $values["LastStatus"],
                    "submittedby"   => $values["Submittedby"],
                    "petitiontext"  => $values["Petitiontext"],
                     "status1"  => $values["Status1"],
      "status2"  => $values["Status2"],
 "status3"  => $values["Status3"],
   "status4"  => $values["Status4"],
 "status5"  => $values["Status5"],
   "status6"  => $values["Status6"],
 "status7"  => $values["Status7"],
   "status8"  => $values["Status8"],
 "status9"  => $values["Status9"],
   "status10"  => $values["Status10"],
 "status11"  => $values["Status11"],
   "status12"  => $values["Status12"],
                     "date1"  => $values["Date1"],
     "date2"  => $values["Date2"],
 "date3"  => $values["Date3"],
     "date4"  => $values["Date4"],
 "date5"  => $values["Date5"],                
     "date6"  => $values["Date6"],
 "date7"  => $values["Date7"],                 
     "date8"  => $values["Date8"],
 "date9"  => $values["Date9"],                 
     "date10"  => $values["Date10"],
 "date11"  => $values["Date11"],                 
     "date12"  => $values["Date12"],
                    "corpname"   => $values["Corpname"],
                    "published" => $values["Published"],
"Received" => $values["Received"],
"NonAdmissible" => $values["NonAdmissible"],
"PetitionClosed" => $values["PetitionClosed"],
"ComplianceStandOrder" => $values["ComplianceStandOrder"],
"ConsiderationComm" => $values["ConsiderationComm"],
"DeliberationsComm" => $values["DeliberationsComm"],
"ReferDeptStakeOmbuds" => $values["ReferDeptStakeOmbuds"],
 "DecisionComm" => $values["DecisionComm"]
    )
    );
#}
}
scraperwiki::sqlitecommit();



function get_extras($url, $laststatus) {
    $localhtml = scraperwiki::scrape($url);
    $localdom = new simple_html_dom();
    $localdom->load($localhtml);
$Received = 0;
$NonAdmissible = 0;
$PetitionClosed = 0;
$ComplianceStandOrder = 0;
$DecisionComm = 0;
$ConsiderationComm = 0;
$ReferDeptStakeOmbuds = 0;
$DeliberationsComm = 0;
print "statustransfer" . $laststatus;
#$status = $laststatus;

$status = "";


/*
$status1 = "";
$status2 = "";
$status3 = "";
 $status4 = "";
 $status5 = "";
 $status6 = "";
 $status7 = "";
 $status8 = "";
 $status9 = "";
 $status10 = "";
 $status11 = "";
$status12 = "";
*/
   $date = "";
   $date1 = "";
 $date2 = "";
 $date3 = "";
 $date4 = "";
 $date5 = "";
 $date6= "";
 $date7 = "";
 $date8 = "";
 $date9 = "";
 $date10 = "";
 $date11 = "";
 $date12 = "";
$corpname  = "n/a";
$petitiontext   = "notavailableyet";
    $column = $localdom->find("div[class=column-center2]");
print $url;
 #   $trows=$column->find("div[class=column-center-inner] table tr");
#print_r($column[2]);
 #  $contents = explode("</tr>",$column[0]); 
$contents = explode("</h2>",$column[0]); 
print "\n" . "contents";
print_r($contents);
 $contentbs = explode("</h2>",$contents[2]); 
print "contentbs";
print_r($contentbs);
$publishedcell = explode("<br />",$contentbs[0]); 
print "publishedcell";
print_r($publishedcell);
if (
$published = $publishedcell[1] > 0) {
$published = $publishedcell[1];
}
else {
$published = $publishedcell[1];
}
$published = trim(strip_tags($published));

$published = substr($published, 10); 
print "\n" . "published" . $published;
 $historical = explode("</tr>",$contentbs[0]); 
# $contentbs = explode("</h2>",$contents[2]); 
print "historical";
print_r($historical);
 #  $details = explode("</h2>",$contents); 
 $subtext = explode("</tr>",$contents[1]); 
#print "subtext";
#print_r($subtext);

   # foreach($rows as $row) {
#    $submittedby = $column->find("td",1);

 #   $petitiontext = $column->find("td",1); 
$namestrip = array("Corporate Name:","Unincorporated Name:","Unincorporated Association Name:");
$namestripb = array("Reference Number:","Status:","Name of Petitioner:","Petition Title:","Petition Text:","Unincorporated Association Name:","Corporate Name:","Text to be Published:","Submitted By:"); #,"Text to be Published:"
$subtextname1 = $subtext[1];
$subtextname1 = trim(strip_tags($subtextname1));
$subtextname1 = substr($subtextname1,7);
print "subtextname1" . $subtextname1 . "trimcheck" . "\n";


if ($laststatus == "Received" ) {

$Received++;

}
else if ($laststatus == "Decision Of The Committee" ) {

$DecisionComm++;
print "DecisionComm" . $DecisionComm . "trimcheck" . "\n";

}
else if ($laststatus == "For Consideration By Committee" ) {

$ConsiderationComm++;

}

else if ($laststatus == "Non-Admissible" ) {

$NonAdmissible++;

}

else if ($laststatus == "Petition Closed" ) {

$PetitionClosed++;

}

else if ($laststatus == "Being Examined For Compliance With Standing Orders" ) {

$ComplianceStandOrder++;

}
else if ($laststatus == "Referral to Department/Stakeholders/Ombudsman" ) {

$ReferDeptStakeOmbuds++;

}

else if ($laststatus == "Deliberations By Committee" ) {

$DeliberationsComm++;

}

else {
print "no++";
}



$subtextname4 = $subtext[4];
$subtextname4 = trim(strip_tags($subtextname4));

$subtextname5 = $subtext[5];
$subtextname5 = trim(strip_tags($subtextname5));
#print "\n" . "subtextname5" . $subtextname5;

$subtextname6 = $subtext[6];
$subtextname6 = trim(strip_tags($subtextname6));
#print "\n" . "subtextname6" . $subtextname6;

if ( isset($subtext[7])) {
$subtextname7 = $subtext[7];
$subtextname7 = trim(strip_tags($subtextname7));
#print "\n" . "subtextname7" . $subtextname7;
}
else {
print "not";

}
$subtextnamearray = array();

for ($i=5; $i < 8; $i++ ) 
{
if (substr(${subtextname.$i},0,9) == "Corporate" || substr(${subtextname.$i},0,14) == "Unincorporated" )  {
print "substrsubtextnamei014" . substr(${subtextname.$i},0,14) . "trimcheck" . "\n";

print "subtextnamearray". $i . $subtextnamearray[$i] . "\n";

${subtextname.$i} = trim(strip_tags(${subtextname.$i}));
print "{subtextname.$i}" . ${subtextname.$i} . "\n";
${subtextname.$i} = str_replace($namestrip,"",${subtextname.$i});
print "subtextnamearrayb". $i . $subtextnamearray[$i] . "\n";

$corpname = str_replace($namestrip,"",${subtextname.$i});

print "corpnameab" . $i . $corpname . "\n";
}
/*
else  {
$corpname  = "n/a";
 }*/

}
print "corpnamec" . $i . $corpname . "\n";


#print "corpnamefinal" . $corpname . "\n";
/*
for ($i = 1; $i <= 5; $i++) {
  ${a.$i} = "value";
}    
foreach ($myarray as $arr){
  $myVars[$arr] = 1;
}$status

Extract ( $myVars );

else if(substr($subtextname5,0,14) == "Unincorporated") {
$corpname = $subtext[5];
$corpname = trim(strip_tags($corpname));
}
*/
#print "subtextnamearrayb4". $i . $subtextnamearray[$i] . "\n";
for ($i=4; $i < 8; $i++ ) 
{

#print "Text to be Published20b4" . $i . substr(${subtextname.$i},0,20) . "\n";

if ((substr(${subtextname.$i},0,13) == "Petition Text"  )||( substr(${subtextname.$i},0,20) == "Text to be Published" ))  
 {



#print "{subtextnameb4.$i}" . ${subtextname.$i} . "trimcheck" . "\n";

${subtextname.$i} = trim(strip_tags(${subtextname.$i}));
#print "{subtextnameaf.$i}" . ${subtextname.$i} . "trimcheck" . "\n";
#${subtextname.$i} = str_replace($namestripb,"",${subtextname.$i});
${subtextname.$i} = str_replace($namestripb,"",${subtextname.$i});
#print "$subtextnamefin". $i . ${subtextname.$i} . "trimcheck" . "\n";

$petitiontext = str_replace($namestripb,"",${subtextname.$i});

print "petition text" . $petitiontext . "trimcheck" . "\n";

}
/*
 else {
# $corpname  = "n/a";

 $petitiontext   = "notavailableyet";
}
*/

}
print "petition textaf" . $petitiontext . "\n";


#print "\n" . "corpname" . $corpname;
$subtextname4 = $subtext[4];
$subtextname4 = strip_tags($subtextname4);
$submittedby = $subtextname4;
#print "\n" . "subtextname4" . $subtextname4 . "\n";
#$submittedby = substr($submittedby, 13);
$submittedby = substr($submittedby, 13);
$submittedby = trim(str_replace("Submitted By:","",$submittedby));
#print "\n" . "submittedby" . $submittedby . "\n";
#$str = substr($str, 1);
  #  2)Status:



print "$historical)";
print_r($historical);
$howmanyupdates = strip_tags(trim($historical[0]));
print "howmanyupdatesbf" . $howmanyupdates . "\n";
$howmanyupdates = substr($howmanyupdates,0,1);
print "howmanyupdatesaf" . $howmanyupdates . "\n";


$datearray = array();
$statusarray = array();

for($i=1; $i < $howmanyupdates; $i++) {
    ${'status' . $i} = "";
#$status[i] = preg_replace('/\d/', '', $status[$i] );
${'status' . $i} = preg_replace('/\d/', '', ${'status' . $i} );
}

$x = 0;
#$b = $howmanyupdates - 1;
$b = $howmanyupdates;
for ($x = 0; $x < $howmanyupdates; $x++) {
print "howmanyupdates" . $howmanyupdates . "\n";
#for ($d = $howmanyupdates; $d >= 1; $d--) {
print "start" . "\n";
$c = $x * 2;
#print "\n" . "b" . $b . "c" . $c . "\n";
$temphiststat = $historical[$c];
print "temphiststat" . $temphiststat . "\n";
$d = $c+1;
$temphistdate = $historical[$d];

print "temphistdate" . $temphistdate . "\n";
#}
#for ($b = $howmanyupdates; $b >= 0; $b--) {
#for ($b = 0; $b <= $howmanyupdates; $b++) {
#$b--;
#$b = $howmanyupdates;
#$d $b +1;
#$b = $d - 1;
     #  $statusarray["status$c"] = $statusaray[$c];
/*
for($i=1; $i < $howmanyupdates; $i++) {
    ${'status' . $i} = $i;
#$status[i] = preg_replace('/\d/', '', $status[$i] );
${'status' . $i} = preg_replace('/\d/', '', ${'status' . $i} );
}
*/
#$statusarray["status$x"] = "statusxsetup";
#$status1 = $statusarray["status1"];
#$statusarray["status$i"] = "value set in loop";


#http://stackoverflow.com/questions/6234864/how-to-change-php-variable-name-in-a-loop


$statusarray[$b] = $temphiststat;
$datearray[$b] = $temphistdate;
#$status[$b] = $historical[$c];
#$date[$b] = $historical[$c+1];
#$b =  $i/2; 
print "statusarray"; 
print_r($statusarray);
print "\n";
$statusarray[$b] = strip_tags($statusarray[$b]);
$statusarray[$b] = trim(str_replace("&nbsp;Status","",$statusarray[$b]));

$statusarray[$b] = substr($statusarray[$b],9);
print "statusnumb" . $c . $statusarray[$b] . "\n";

#${status.$b} = $statusarray[$b]; 
${status.$b} = $statusarray[$b];
#${$status.$b} = $statusarray[$b];

print "statusnumbcheck" . $status1 . "trimcheck" . "\n";
print "\n" . "b" . $b . "c" . $c . "d" . $d . "x" . $x . "\n";
print "date" . $d . $datearray[$b] . "\n";
$datearray[$b] = strip_tags($datearray[$b]);
$datearray[$b] = trim(str_replace("&nbsp;Date:","",$datearray[$b]));
print "dateclean" . $d . $datearray[$b] . "\n";
#   $date2 = $date[1];
#$date2 = strip_tags($date2);
#$date2 = trim(str_replace("&nbsp;Date:","",$date2));
${date.$b} = $datearray[$b];
#${$date.$b} = $datearray[$b];

#Non-Admissible
#Petition Closed
#Being Examined For Compliance With Standing Orders
#Decision Of The Committee
#For Consideration By Committee
#Referral to Department/Stakeholders/Ombudsman
#Deliberations By Committee
#Received
#NonAdmissible
#PetitionClosed
#ComplianceStandOrder
#DecisionComm
#ConsiderationComm
#ReferDeptStakeOmbuds
#DeliberationsComm


print "statuscheck" . $statusarray[$b] . "\n";

if ($statusarray[$b] == "Received" ) {
$Received++;
print "Received" . $Received . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "Decision Of The Committee" ) {
$DecisionComm++;
print "DecisionComm" . $DecisionComm . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "For Consideration By Committee" ) {
$ConsiderationComm++;
print "ConsiderationComm" . $ConsiderationComm . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "Non-Admissible" ) {
$NonAdmissible++;
print "NonAdmissible" . $NonAdmissible . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "Petition Closed" ) {
$PetitionClosed++;
print "PetitionClosed" . $PetitionClosed . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "Being Examined For Compliance With Standing Orders" ) {
$ComplianceStandOrder++;
print "ComplianceStandOrder" . $ComplianceStandOrder . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "Referral to Department/Stakeholders/Ombudsman" ) {

$ReferDeptStakeOmbuds++;
print "ReferDeptStakeOmbuds" . $ReferDeptStakeOmbuds . "trimcheck" . "\n";

}

else if ($statusarray[$b] == "Deliberations By Committee" ) {
$DeliberationsComm++;
print "DeliberationsComm" . $DeliberationsComm . "trimcheck" . "\n";

}

else {
print "none the same?";
}
print "is subtextname1 " . $subtextname1 . "the same as statusarray[b] " . $statusarray[$b] . "trimcheck" . "\n";
print "is statusarray[howmanyupdates] " . $statusarray[$howmanyupdates] . "the same as laststatus " . $laststatus . "trimcheck" . "\n";



/*
if (is_numeric($statusarray[$b]) || ($statusarray[$b] == 0 )) {
$statusarray[$b] = "";
}
if (is_numeric($date[$b]) || ($statusarray[$b] == 0 )) {
$date[$b] = "";
}

*/
$b--;
}


print "statusarray"; 
print_r($statusarray);
print "\n";

if (($statusarray[$howmanyupdates] == $laststatus ) && ($Received > 0)) {
$Received--;
print "samecheck" . $statusarray[$howmanyupdates] ==  $laststatus . "\n";
}
if (($DecisionComm > 0) && ($statusarray[$howmanyupdates] == $laststatus )) {
$DecisionComm--;
print "samecheck" . $statusarray[$howmanyupdates] .  $laststatus . "\n";

}
if (($statusarray[$howmanyupdates] == $laststatus ) && ($ConsiderationComm > 0)) {
$ConsiderationComm--;
print "samecheck" . $statusarray[$howmanyupdates] ==  $laststatus . "\n";

}
if (($NonAdmissible > 0) && ($statusarray[$howmanyupdates] == $laststatus)) {
$NonAdmissible--;
print "samecheck" . $statusarray[$howmanyupdates] .  $laststatus . "\n";

}
if (($PetitionClosed > 0) && ($statusarray[$howmanyupdates] == $laststatus )) {
$PetitionClosed--;
print "samecheck" . $statusarray[$howmanyupdates] .  $laststatus . "\n";

}
if (($ComplianceStandOrder > 0) && ($statusarray[$howmanyupdates] == $laststatus)) {
$ComplianceStandOrder--;
print "samecheck" . $statusarray[$howmanyupdates] .  $laststatus . "\n";

}
if (($ReferDeptStakeOmbuds > 0) && ($statusarray[$howmanyupdates] == $laststatus )) {
$ReferDeptStakeOmbuds--;
print "samecheck" . $statusarray[$howmanyupdates] . $laststatus . "\n";

}
if (($DeliberationsComm > 0) && ($statusarray[$howmanyupdates] == $laststatus )) {
$DeliberationsComm--;
print "samecheck" . $statusarray[$howmanyupdates] .  $laststatus . "\n";

}



$moredetails = array();    
    $moredetails["submittedby"] = $submittedby;
    $moredetails["petitiontext"] = $petitiontext;
    $moredetails["corpname"] = $corpname;
   $moredetails["status1"] = $status1;
   $moredetails["status2"] = $status2;
 $moredetails["status3"] = $status3;
 $moredetails["status4"] = $status4;
 $moredetails["status5"] = $status5;
 $moredetails["status6"] = $status6;
 $moredetails["status7"] = $status7;
 $moredetails["status8"] = $status8;
 $moredetails["status9"] = $status9;
 $moredetails["status10"] = $status10;
 $moredetails["status11"] = $status11;
 $moredetails["status12"] = $status12;
    $moredetails["published"] = $published;
    $moredetails["date1"] = $date1;
 $moredetails["date2"] = $date2;
 $moredetails["date3"] = $date3;
 $moredetails["date4"] = $date4;
 $moredetails["date5"] = $date5;
 $moredetails["date6"] = $date6;
 $moredetails["date7"] = $date7;
 $moredetails["date8"] = $date8;
 $moredetails["date9"] = $date9;
 $moredetails["date10"] = $date10;
 $moredetails["date11"] = $date11;
 $moredetails["date12"] = $date12;

 $moredetails["Received"] = $Received;
 $moredetails["NonAdmissible"] = $NonAdmissible;
 $moredetails["PetitionClosed"] = $PetitionClosed;
 $moredetails["ComplianceStandOrder"] = $ComplianceStandOrder;

 $moredetails["ConsiderationComm"] = $ConsiderationComm;
 $moredetails["DeliberationsComm"] = $DeliberationsComm;
 $moredetails["ReferDeptStakeOmbuds"] = $ReferDeptStakeOmbuds;

 $moredetails["DecisionComm"] = $DecisionComm;

#    unset($addressbits,$address,$emailbits,$email,$phonebits,$phone,$mobilebits,$mobile,$faxbits,$fax);
  
    return($moredetails);

}




?>
