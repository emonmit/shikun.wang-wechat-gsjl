<?php
    include "wechat.class.php";
    include "./controller/textController/TextController.php";
    include "./controller/eventController/EventController.php";
    include "./model/TextModel.php";
    include "./model/EventModel.php";
    include "./config/params.php";

    $options = array(//填入你的公众号信息
        'token' => '', 
        'encodingaeskey'=>'', 
        'appid'=>'', 
        'appsecret'=>'' 
    );
    $weObj = new Wechat($options);
    $weObj->valid();
    $type = $weObj->getRev()->getRevType();
    switch($type) {
               case Wechat::MSGTYPE_TEXT:
                       $textObj = new TextController($weObj);
                       $reply = $textObj->replyIndex();
                       $weObj->text($reply)->reply();
                       break;
               case Wechat::MSGTYPE_EVENT:
                       $eventObj = new EventController($weObj);
                       $reply = $eventObj->replyIndex();
                       //$weObj->text($reply)->reply();
                       $weObj->news($reply)->reply();
                       break;
               case Wechat::MSGTYPE_IMAGE:
                       
                       break;
               default:
                       $weObj->text("help info")->reply();
    }
