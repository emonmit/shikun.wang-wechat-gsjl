<?php
    include "wechat.class.php"; 
    include "./controller/textController/TextController.php";
    include "./config/params.php";

    $options = array(
        'token' => 'gushijielong3', 
        'encodingaeskey'=>'6YzjLJANnaIc1MLl3WqRefiyZzzmjIrtEdteci0dQw4', 
        'appid'=>'wx7350afd886ccd9b6', 
        'appsecret'=>'629a95d1fd1760a16c5be198981e1c45' 
    );
    $weObj = new Wechat($options);
    $weObj->valid();
    $type = $weObj->getRev()->getRevType();
    switch($type) {
               case Wechat::MSGTYPE_TEXT:
                       $textObj = new TextController($weObj);
                       $reply = $textObj->replyIndex();
                       $weObj->text($reply)->reply();
                       exit;
                       break;
               case Wechat::MSGTYPE_EVENT:
                    
                       break;
               case Wechat::MSGTYPE_IMAGE:
                       
                       break;
               default:
                       $weObj->text("help info")->reply();
    }
