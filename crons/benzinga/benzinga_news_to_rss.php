<?php

//include '../../lib/autoloader.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//
// read Benzinga news XML file and format into RSS
//

// start of RSS file
$rssout  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
$rssout .= "<rss version=\"2.0\">\r\n";
$rssout .= "<channel>\r\n";
$rssout .= "<title>Processed Benzinga News Feed</title>\r\n";
$rssout .= "<link>http://marketspectator.com/</link>\r\n";
$rssout .= "<description>Processed Benzinga News Feed</description>\r\n";
$rssout .= "<lastBuildDate>" . date('r', time()) . "</lastBuildDate>\r\n";
$rssout .= "<language>en-us</language>\r\n";

// open XML file and loop through each item 
$xml = simplexml_load_file('/home/dh_uey5n8/imscrm.com/crons/benzinga/benzinga-news.xml');

foreach($xml->children() as $items) {
  $rssout .= "<item>\r\n";
  $title   = $items->title;
  $title   = str_replace("&","&amp;",$title);
  $rssout .= "<title>" . $title . "</title>\r\n";
  $rssout .= "<link>" . $items->url . "</link>\r\n";
  $rssout .= "<guid>" . $items->url . "</guid>\r\n";
  $rssout .= "<pubDate>" . $items->updated . "</pubDate>\r\n";
  $content = $items->body;
//  $content = trim(strip_tags($content, '<p></p><img>' ));
  $content = trim($content);
  $rssout .= "<description><![CDATA[" . $content . "]]></description>\r\n";
  $rssout .= "</item>\r\n";
 }

// end of RSS file
$rssout .= "</channel>\r\n";
$rssout .= "</rss>";

// write RSS file
$rssfile = fopen('/home/dh_uey5n8/imscrm.com/crons/benzinga/benzinga-news.rss', "w") or die("Unable to open file!");
fwrite($rssfile, $rssout);
fclose($rssfile);

?>