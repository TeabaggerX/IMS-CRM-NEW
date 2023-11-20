<?php

include '/home/dh_uey5n8/imscrm.com/lib/autoloader.php';
include '/home/dh_uey5n8/imscrm.com/Everflow/functions.php';

$template = templates::getWhere(['url' => '', 'del' => 0, 'affiliate_active' => 1]);
$query = drafts::startQuery()->where("id", ">", 0);
drafts::updateWithConditions($query, array("del" => 1));
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
            $url_index='';
            if($temp->affiliate_id != 1271){
                $url_date = '/'.date('Y/m/d', strtotime($value['post_date']));
            }
            if($temp->affiliate_id == 1596){
                $url_index = '/index.php';
            }
            if($temp->affiliate_id == 1554){
                $post_title = clean_title($value['post_title']);
                $link_date = $baseUrl.'/'.date('Y', strtotime($value['post_date'])).'/'.date('m', strtotime($value['post_date'])).'/'.$post_title;
            } else {
                $link_date = $baseUrl.$url_index.$url_date.'/'.$value['post_name'];
            }

            
            $template = drafts::getOneWhere(['post_id' => $value['ID']]);

            if(empty($template->id)){
                $template = new drafts();
            }
            $template->url = $link_date;
            $template->title = $value['post_title'];
            $template->post_name = $value['post_name'];
            $template->post_id = $value['ID'];
            $template->affiliate_id = $temp->affiliate_id;
            $template->html = $value['post_content'];
            $template->del = 0;
            $template->timestamp = date("Y-m-d h:i:s");
            $template->save();
 
        }
    } catch (\Exception $e) {
        file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ".$e->getMessage(), 8);
    }
}
$query = drafts::startQuery()->where("del", "=", 1);
drafts::deleteWithConditions($query, array("del" => 1));
