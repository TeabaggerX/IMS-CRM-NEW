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
}

if($_REQUEST['saveType'] == 'SAVE'){
    $template->affiliate_id = $_REQUEST['affiliate_id'];
    $template->url = $_REQUEST['url'];
    $template->api = $_REQUEST['api'];
    $template->body = $_REQUEST['body'];
    $template->affiliate_active = $_REQUEST['affiliate_active'];
    $template->save();
    $active = '';
    if($template->affiliate_active == 0 || $template->affiliate_active == ''){
        $active = '-not';
    }
}