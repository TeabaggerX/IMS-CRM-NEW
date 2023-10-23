<?php

$temps = new templates();
$template = $temps->getWhere(['affiliate_active' => 1]);

if(!empty($template)){
    foreach ($template as $k) {
        if($k->affiliate_id != '' && $k->affiliate_id != 0){
            $affInfo = everflowAffiliate::getOneWhere(['affiliate_id' => $k->affiliate_id, 'affiliate_id_encoded' => '']);
            if(!empty($affInfo)){
                $dropDown[$k->affiliate] = $k->id.'|'.$k->affiliate_id.'|'.$affInfo->affiliate_id_encoded;
            }
        }
    }
}
