<?php
include 'lib/autoloader.php';
// "Please give us 24 to 48 hours to approve your accout."
function jsonout($out) {
    header("Content-Type: application/json");
    $result = json_encode($out);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            die($result);
            break;
        case JSON_ERROR_DEPTH:
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Maximum stack depth exceeded", 8);
            break;
        case JSON_ERROR_STATE_MISMATCH:
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Underflow or the modes mismatch", 8);
            break;
        case JSON_ERROR_CTRL_CHAR:
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Unexpected control character found", 8);
            break;
        case JSON_ERROR_SYNTAX:
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Syntax error, malformed JSON", 8);
            break;
        case JSON_ERROR_UTF8:
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Malformed UTF-8 characters, possibly incorrectly encoded", 8);
            break;
        default:
            file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror Unknown error", 8);
            break;
    }
    file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || jsonerror ".print_r(debug_backtrace(), true), 8);
    die;
}


switch ($_REQUEST['action']) {
    case 'register':
        file_put_contents(ROOT.'/debugfile.txt', "\n(".date('H:i:s').") ". basename(__FILE__).':'.__LINE__." || ".print_r($_REQUEST,true), 8);

        parse_str($_REQUEST['form'], $parsed_data);

        $the_from = $parsed_data;

        $affiliates = users::getOneWhere(['email' => $the_from['email']]);
           
        if(!empty($affiliates)){
            jsonout('That Email is already in use. Please login using that email.');
        } else {
            $pw = str_replace(' ','',$the_from['password']);
            $pw = password_hash($pw, PASSWORD_DEFAULT);
            $new_user = new users();
            $new_user->email = $the_from['email'];
            $new_user->first_name = $the_from['first_name'];
            $new_user->last_name = $the_from['last_name'];
            $new_user->username = str_replace(' ','',$the_from['username']);
            $new_user->password = $pw;
            $new_user->active = 0;
            $new_user->lastlogin = 0;
            $new_user->save();
        }

        jsonout("Please give us 24 to 48 hours to approve your accout.");
    break;
}


?>