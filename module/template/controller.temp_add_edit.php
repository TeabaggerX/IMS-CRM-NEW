<?php
if(!empty($_GET['id'])){
    $temps = new templates();
    $template = $temps->getOneWhere(['id' => $_GET['id']]);
    $saveType = 'SAVE';
    $active = '';
    if($template->affiliate_active == 0 || $template->affiliate_active == ''){
        $active = '-not';
    }
} else {
    $saveType = 'ADD';
    $template = new templates();
    $template->affiliate_active = 1;
}

$aff = new everflowAffiliate();
$aff_all = $aff->getWhere(['accountStatus' => 'active']);

foreach ($aff_all as $k) {
    $dropDown['('.$k->affiliate_id.') '.$k->name]= $k->affiliate_id;
}