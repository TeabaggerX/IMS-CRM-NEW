<?php
file_put_contents('/home/dh_uey5n8/imscrm.com/jonnydebugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || SQL Test Start", 8);

include("/home/dh_uey5n8/imscrm.com/lib/autoloader.php");

//$var = $mySQL->query("SELECT * FROM everflowOffer WHERE offer_id <> 46 ORDER BY offer_id ASC")->fetchAll();

$var = everflowOffer::getAllWhere( "offer_id", "!=", "46", "offer_id ASC"  );

//print_r($var);
foreach ($var as $offer) {
    // Access the offer_id property if it's a public property
    $offer_id = $offer->offer_id;
    echo $offer_id."<br>";
}
?>
