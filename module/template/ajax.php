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


switch ($_REQUEST['action']) {
    case 'save_temp':
            $template = templates::getOneWhere(['id' => $_REQUEST['id']]);
            
            file_put_contents(ROOT.'debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || test 1 ", 8);
            if($template->id == ''){
                file_put_contents('../../debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || templates", 8);
                $template = new templates();
                $template->created_at = date('Y-m-d');
            }
            $template->updated_at = date('Y-m-d');
            $template->color = $_REQUEST['color'];
            $template->affiliate = $_REQUEST['affiliate'];
            $template->affiliate_id = $_REQUEST['affiliate_id'];
            $template->url = $_REQUEST['url'];
            $template->api = $_REQUEST['api'];
            $template->body = $_REQUEST['temp'];
            $template->del = 0;
            $template->affiliate_active = $_REQUEST['affiliate_active'];
            $template->save();

        break;
        case 'active_temp':
    
            $temps = new templates();
            $template = $temps->getOneWhere(['id' => $_REQUEST['id']]);
            $template->affiliate_active = $_REQUEST['affiliate_active'];
            $template->save();
    
            jsonout(['success' => $template->affiliate]);
        break;
        case 'delete_temp':

            $temps = new templates();
            $template = $temps->getOneWhere(['id' => $_REQUEST['id']]);
            $template->del = 1;
            $template->save();

            jsonout(['success' => $template->affiliate]);
        break;

}
?>