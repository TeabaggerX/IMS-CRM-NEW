<?php
include '../lib/autoloader.php';
include '../Everflow/functions.php';

$template = templates::getWhere(['url' => '', 'del' => 0, 'affiliate_active' => 1]);
$i=0;
foreach ($template as $temp) {
    try {
        $payload = '';
        $response = wordpressAPI($temp->url, $temp->api);
        $baseUrl = 'https://'.$temp->url;
        file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ***************************************************** URL: ".$temp->url, 8);

        foreach ($response as $key => $value) {
            // file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ".print_r($value,true), 8);
            $i++;
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || $i)", 8);
            if($temp->affiliate_id != 1271){
                $url_date = '/'.date('Y/m/d', strtotime($value['post_date']));
            }
            if($temp->affiliate_id == 1596){
                $url_index = '/index.php';
            }
            if($temp->affiliate_id == 1554){
                $link_date = $baseUrl.'/?p='.$value['ID'].'|'.$value['ID'];
            } else {
                $link_date = $baseUrl.$url_index.$url_date.'/'.$value['post_name'];
            }

            
            $template = drafts::getOneWhere(['post_id' => $value['ID']]);

            if($template->id == ''){
                $template = new drafts();
            }
            $template->url = $link_date;
            $template->title = $value['post_title'];
            $template->post_name = $value['post_name'];
            $template->post_id = $value['ID'];
            $template->affiliate_id = $temp->affiliate_id;
            $template->html = $value['post_content'];
            $template->save();
 
        }
    } catch (\Exception $e) {
        file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ".$e->getMessage(), 8);
    }
}