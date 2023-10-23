<?php

//include '../../lib/autoloader.php';

 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

//
// write Benzinga news to a local file 
//

$today = date("Y-m-d"); 
$curl = curl_init();
$ch = curl_init();

// channel names have to be encoded
$channels = "After-Hours%20Center%2CAnalyst%20Color%2CAnalyst%20Ratings%2CBinary%20Options%2CContracts%2CDividends%2CDowngrades%2CEarnings%2CEcon%20%23s%2CEconomics%2CEvents%2CExclusives%2CFederal%20Reserve%2CGeneral%2CGlobal%2CGuidance%2CHedge%20Funds%2CHot%2CInitiation%2CInsider%20Trades%2CInterview%2CIntraday%20Update%2CIPOs%2CLong%20Ideas%2CM%26A%2CMarket-Moving%20Exclusives%2CMarkets%2CMovers%2CMovers%20%26%20Shakers%2COpinion%2COptions%2CPolitics%2CPre-Market%20Outlook%2CPress%20Releases%2CPreviews%2CPrice%20Target%2CReiteration%2CRetail%20Sales%2CRumors%2CSEC%2CShort%20Ideas%2CShort%20Sellers%2CSignals%2CSmall%20Cap%20Analysis%2CTech%2CTechnicals%2CTop%20Stories%2CTrading%20Ideas%2CUpgrades%2CWIIM";

curl_setopt_array($ch, array(
  CURLOPT_URL => "https://api.benzinga.com/api/v2/news?pageSize=100&displayOutput=full&sort=created:desc&channels=" . $channels . "&date=" . $today . "&token=c9c6f8c9a7ee4429be5f719f80c8a66c",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => ""
));

$response = curl_exec($ch);

curl_close($ch);

$xmlfile = fopen("/home/dh_uey5n8/imscrm.com/crons/benzinga/benzinga-news.xml", "w") or die("Unable to open file!");
fwrite($xmlfile, $response);
fclose($xmlfile);

?>