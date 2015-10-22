<?php
/**
 * Created by PhpStorm.
 * User: Emits.wang
 * Date: 2015/8/26
 * Time: 16:30
 */

class EventController {
    private $sender;//文本信息发送者openid
    private $event;//事件名

    /*
     * 构造方法：接收用户输入，并链接数据库
     * */
    public function __construct(Wechat $wechat)
    {
        $this->sender = $wechat->getRev()->getRevFrom();
        $this->event  = $wechat->getRev()->getRevEvent();
        $this->models = new EventModel();
    }


    /*
         * 接口测试
         */
    public function replySamething()
    {
        $event_name = $this->event['event'];
        $content = '';
        $content = $this->println($content, "消息发送人：$this->sender");
        $content = $this->println($content, "事件名：$event_name");
        return $content;
    }

    public function replyIndex()
    {
        $event_name = $this->event['event'];
        switch($event_name)
        {
            case 'subscribe':
                if($this->atSubscribe($this->sender))
                    //$reply = Params::WELCOMEWORDS;
                    $reply = array("0" => array(
                                                'Title'=>'再给我一个猜不到的结局！',
                                                'Description'=>'终于等到你！微信故事接龙上线啦！！！  我的故事里，是否会有你~',
                                                'PicUrl'=>'http://static.shikun.wang/img/img01.jpg',
                                                'Url' => 'http://mp.weixin.qq.com/s?__biz=MzI2MTAxNzIwOA==&mid=209106956&idx=1&sn=a75a94b39c6bc0cf15a07a9cd720fad8#rd'
                                           ),
                                    "1" => array(
                                                'Title'=>'【穿越】焜皇活不过第三集[故事编号9]',
                                                'Description'=>'终于等到你！微信故事接龙上线啦！！！  我的故事里，是否会有你~',
                                                'PicUrl'=>'http://static.shikun.wang/img/img03.jpg',
                                                'Url'=>'http://mp.weixin.qq.com/s?__biz=MzI2MTAxNzIwOA==&mid=209106956&idx=2&sn=0148043c9facc0e5b02b90cc411c070c#rd'
                                           ),
                                    "2" => array(
                                                'Title'=>'【玄幻】星辰变[故事编号8]',
                                                'Description'=>'终于等到你！微信故事接龙上线啦！！！  我的故事里，是否会有你~',
                                                'PicUrl'=>'http://static.shikun.wang/img/img02.jpg',
                                                'Url'=>'http://mp.weixin.qq.com/s?__biz=MzI2MTAxNzIwOA==&mid=209106956&idx=3&sn=7a7cfb305b692488d64a716541361d60#rd'
                                           ),
                                    "3" => array(
                                                'Title'=>'【都市】蓝白记[故事编号7]',
                                                'Description'=>'终于等到你！微信故事接龙上线啦！！！  我的故事里，是否会有你~',
                                                'PicUrl'=>'http://static.shikun.wang/img/img05.jpg',
                                                'Url'=>'http://mp.weixin.qq.com/s?__biz=MzI2MTAxNzIwOA==&mid=209106956&idx=4&sn=0d23deb6902325f3e0a16c9125432e67#rd'
                                           ),
                        );
                //接下来应该写入数据库，标记状态
                break;
            case 'unsubscribe':
                //接下来应该修改用户状态
                $this->atUnsubscribe($this->sender);
                break;
            default:
                $reply = '';
                break;
        }

        return $reply;
    }

    /*
     * 当用户关注时
     * @return bool
     */
    public function atSubscribe($openid)
    {
        return $this->models->actionSubscribe($openid);
    }

    /*
     * 当用户取消关注时
     * @return bool
     */
    public function atUnsubscribe($openid)
    {
        return $this->models->actionUnsubscribe($openid);
    }

    /*
     * 返回值拼接
     */
    public function println($content, $more)
    {
        if('' == $content){
            return $more;
        }else{
            return $content."\n".$more;
        }
    }
}