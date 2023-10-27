<?php
include '../../lib/autoloader.php';


function jsonout($out) {
    header("Content-Type: application/json");
    $result = json_encode($out);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            die($result);
            break;
        case JSON_ERROR_DEPTH:
            file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Maximum stack depth exceeded", 8);
            break;
        case JSON_ERROR_STATE_MISMATCH:
            file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Underflow or the modes mismatch", 8);
            break;
        case JSON_ERROR_CTRL_CHAR:
            file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Unexpected control character found", 8);
            break;
        case JSON_ERROR_SYNTAX:
            file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Syntax error, malformed JSON", 8);
            break;
        case JSON_ERROR_UTF8:
            file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Malformed UTF-8 characters, possibly incorrectly encoded", 8);
            break;
        default:
            file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Unknown error", 8);
            break;
    }
    file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror ".print_r(debug_backtrace(), true), 8);
    die;
}

function get_post_body($html){
    $post_html = $html;

    $dom = new \DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $dom->loadHTML($post_html);

    $para = $dom->getElementsByTagName('p');
    $content = '';
    $i = 0;
    if(!empty($para)) {
        foreach($para as $p) {
            if($p->getAttribute('class') == '') {
                if($i > 0) {
                    $content .= $p->textContent;
                }
                $i++;
            }
        }
    }

    $description = substr($content, 0, 310).' ';
 
    return $description;
}


switch ($_REQUEST['action']) {
    case 'get_offers':
        $affiliates = everflowAffiliateVisibility::getWhere(['affiliate_id' => $_REQUEST['id']]);
        $template = templates::getOneWhere(['id' => $_REQUEST['temp_id']]);
            
        $articleCount = substr_count($template->body, '{article.body}');

        foreach ($affiliates as $k) {
            $offer_ids[]= $k->offer_id;
        }

        $offers = everflowOffer::getWhereIN(['offer_id' => $offer_ids, 'category' => 'Newsletter'], 'offer_id DESC');
        
        foreach ($offers as $k) {
            $offers = everflowCreative::getOneWhere(['offer_id' => $k->offer_id]);
            if(!empty($offers)){
                $dropDown[$k->offer_id.' | '.$k->payout_type.' | '.$k->name] = $k->offer_id;
            }
        }

        for($i=1; $i <= $articleCount; $i++){
            $dd = new dropdown();
            $dd->blankFirst = '--Select AD--';
            $dd->setName('ad_'.$i);
            $dd->setId('ad_'.$i);
            $dd->setStyle('mb-3 form-control form-control-md templateDropdown search');
            $dd->setOptions($dropDown);
            $dropDownHTML .= $dd->draw(true);

        }

        jsonout($dropDownHTML);
    break;
    case 'get_drafts':
            $drafts = drafts::getWhere(['affiliate_id' => $_REQUEST['id']]);
            $template = templates::getOneWhere(['id' => $_REQUEST['temp_id']]);
                
            $adCount = substr_count($template->body, '{ad}');
            
            foreach ($drafts as $k) {
                $dropDown[$k->title]= $k->id;
            }

            for($i=1; $i <= $adCount; $i++){
                $dd = new dropdown();
                $dd->blankFirst = '--Select POST--';
                $dd->setName('post_'.$i);
                $dd->setStyle('mb-3 form-control form-control-md templateDropdown search');
                $dd->setOptions($dropDown);
                $dropDownHTML .= $dd->draw(true);

            }

            jsonout($dropDownHTML);
        break;

    case 'get_temp':
        // form_url=&template=1434%7C3B3TSR3&affiliate_id_encoded=3B3TSR3&post_1=&post_2=&post_3=&post_4=&ad_1=NFC9H&ad_2=&ad_3=&ad_4=&html=
        
        parse_str($_REQUEST['form'], $parsed_data);

        $the_from = $parsed_data;

        file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ".print_r($the_from,true), 8);
        $aff_array = explode('|',$the_from['template']);
        $temp_id = $aff_array[0];
        $affiliate_id = $aff_array[1];
        $affiliate_id_encoded = $aff_array[2];

        $template = templates::getOneWhere(['affiliate_id' => $affiliate_id]);

        $html = $template->body;
        $wordCount = substr_count($html, '{article.body}');
        
        for($i=1; $i <= $wordCount; $i++){
            if($the_from['post_'.$i] != ''){
                $html = get_post_html($html, $the_from['post_'.$i]);
            } else {
                $html = get_post_html_empty($html);
            }
        }
        for($i=1; $i <= $wordCount; $i++){
            if($the_from['ad_'.$i] != ''){
                $html = get_ad_html($html, $the_from['ad_'.$i], $affiliate_id_encoded);
            } else {
                $html = get_ad_html_empty($html);
            }
        }

        jsonout($html);
}

function get_ad_html($html, $ad, $affiliate_id_encoded){
    try{
        $pos5 = strpos($html, '{ad}');

        $this_creative = everflowCreative::getOneWhere(['offer_id' => $ad]);
        $this_offer = everflowOffer::getOneWhere(['offer_id' => $ad]);
        $offer_id_encoded = $this_offer->offer_id_encoded;
        $tracking_link = "https://www.imsjjk309.com/$affiliate_id_encoded/$offer_id_encoded/";

        $ad = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $this_creative->html_code);
        $ad = preg_replace('/{tracking_link}/',$tracking_link.'network_channel_id='.$this_offer->channel_id,$ad);

        $ad  = mb_convert_encoding($ad , 'HTML-ENTITIES', 'UTF-8');
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($ad);
        libxml_use_internal_errors($internalErrors);

        $nodeA = $dom->getElementsByTagName('a')[0];
        if(empty($nodeA)){
            return 'ERROR: Malformed HTML. Missing link elements.';
        }
        $linkA = $nodeA->getAttribute('href');

        $nodeSpan = $dom->getElementsByTagName('span')[0];
        if(empty($nodeSpan)){
            return 'ERROR: Malformed HTML. Missing span elements.';
        }
        $styleSpan = $nodeSpan->getAttribute('style');
        $styleSpan .= 'font-weight:bold;';
        $nodeSpan->setAttribute('style', $styleSpan);

        $nodeNewA = $nodeSpan->parentNode->insertBefore( $dom->createElement('a'), $nodeSpan );
        $nodeNewA->setAttribute('href', $linkA);
        $nodeNewA->appendChild( $nodeSpan );

        $nodeAs = $dom->getElementsByTagName('a');
        if($nodeAs->length > 0) {
            $n = 1;
            foreach($nodeAs as $nodeA) {
                $styleA = $nodeA->getAttribute('style');
                if($n == $nodeAs->length) {
                    $styleA .= 'font-weight:bold; text-decoration: underline;';
                } else {
                    $styleA .= 'font-weight:bold; text-decoration: none;';
                }
                $nodeA->setAttribute('style', $styleA);
                $n++;
            }
        }

        $ad = $dom->saveHtml();
        $ad = str_replace('<br><br>', ' ', $ad);
        $ad = str_replace('<br /><br />', ' ', $ad);
        $ad = str_replace('<br ><br >', ' ', $ad);
        $ad = str_replace('</span>', '&nbsp;<span style="font-size: 75%;">[sponsor]</span></span><br/>', $ad);
        $ad = '<div class="ims_ad">'.$ad.'</div>';
        $html = substr_replace($html, $ad, $pos5, strlen('{ad}'));
        return $html;
    } catch (\Exception $e) {
        file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ERROR: ".$e->getMessage(), 8);
        return 'ERROR: '.$e->getMessage();
    }
}

function get_ad_html_empty($html){
    $pos5 = strpos($html, '{ad}');
    if ($pos5 !== false) {
        $html = substr_replace($html, '', $pos5, strlen('{ad}'));
    }
    return $html;
}

function get_post_html($html, $post){
    $this_draft = drafts::getOneWhere(['id' => $post]);

    $body = get_post_body($this_draft->html);

    $pos1 = strpos($html, '{article.title}');
    if ($pos1 !== false) {
        $html = substr_replace($html, $this_draft->title, $pos1, strlen('{article.title}'));
    }
    $pos2 = strpos($html, '{article.body}');
    if ($pos2 !== false) {
        $html = substr_replace($html, $body, $pos2, strlen('{article.body}'));
    }
    $pos3 = strpos($html, '{article.URL}');
    if ($pos3 !== false) {
        $html = substr_replace($html, $this_draft->url, $pos3, strlen('{article.URL}'));
    }
    $pos4 = strpos($html, '{article.URL}');
    if ($pos4 !== false) {
        $html = substr_replace($html, $this_draft->url, $pos4, strlen('{article.URL}'));
    }
    return $html;
}

function get_post_html_empty($html){
    $pos1 = strpos($html, '{article.title}');
    if ($pos1 !== false) {
        $html = substr_replace($html, '', $pos1, strlen('{article.title}'));
    }
    $pos2 = strpos($html, '{article.body}');
    if ($pos2 !== false) {
        $html = substr_replace($html, '', $pos2, strlen('{article.body}'));
    }
    $pos3 = strpos($html, '{article.URL}');
    if ($pos3 !== false) {
        $html = substr_replace($html, '', $pos3, strlen('{article.URL}'));
    }
    $pos4 = strpos($html, '{article.URL}');
    if ($pos4 !== false) {
        $html = substr_replace($html, '', $pos4, strlen('{article.URL}'));
    }
    return $html;
}