<?php
    /*
     * 文本类信息处理类
     */

    class TextController{

        private $content;//消息内容正文
        private $sender;//文本信息发送者openid
        private $create_time;//消息创捷时间
        private $msg_id;//消息id

        public function __construct(Wechat $wechat)
        {
            $this->content = $wechat->getRev()->getRevContent();
            $this->sender = $wechat->getRev()->getRevFrom();
            $this->create_time = $wechat->getRev()->getRevCtime();
            $this->msg_id = $wechat->getRev()->getRevID();
        }

        /*
         * 接口测试
         */
        public function replySamething()
        {
            $content = '';
            $content = $this->println($content, "消息ID：$this->msg_id");
            $content = $this->println($content, "消息内容：$this->content");
            $content = $this->println($content, "创建时间：$this->create_time");
            return $content;
        }

        /*
         * text index function
         */
        public function replyIndex()
        {
            $params = new Params();
            $paramArr = $params->index();
            if(isset($paramArr[$this->content]))
            {
                //固定回复
                $reply = $paramArr[$this->content];
            }
            else
            {
                $reply = "暂无数据";
            }

            return $reply;
        }

        /*
         * 返回值拼接
         */
        public function println($content, $more)
        {
            return $content.$more."\n";
        }
    }
