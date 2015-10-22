<?php
    /*
     * 文本类信息处理类
     */

    class TextController{

        private $content;//消息内容正文
        private $sender;//文本信息发送者openid
        private $create_time;//消息创捷时间
        private $msg_id;//消息id
        private $models;//model实例

        /*
         * 构造方法：接收用户输入，并链接数据库
         * */
        public function __construct(Wechat $wechat)
        {
            $this->content = $wechat->getRev()->getRevContent();
            $this->sender = $wechat->getRev()->getRevFrom();
            $this->create_time = $wechat->getRev()->getRevCtime();
            $this->msg_id = $wechat->getRev()->getRevID();
            $this->models = new TextModel();
            $this->models->actionIndex($this->content, $this->sender, $this->msg_id);
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
         *
         * 【逻辑描述】
         * 0、将用户输入信息存入数据库
         * 1、判断用户输入是否处于游戏关键逻辑上，是的话执行游戏逻辑
         * 2、若不是游戏逻辑关键词，则查找固定内容回复，有的话回复相应内容
         * 3、如果不存在对应关键词，则回复相应引导内容
         *
         */
        public function replyIndex()
        {
            $content = $this->content;
            $openid = $this->sender;
            //逻辑关键字数组-----应该移植为全局变量
            $hotstory = array('热门故事', '热门', 'remen', 'hot');
            $newstory = array('最新故事', '最新', 'zuixin', 'new');
            $randlist = array('换','随机推荐','随机'.'换一批');
            $writename = array('笔名', 'name', '署名', '查看笔名');
            //系统关键逻辑
            if (in_array($content, $hotstory)) {//热门故事
                $reply = $this->getHotStoryList();
            }elseif(in_array($content, $newstory)) {//新故事
                $reply = $this->getNewStoryList();
            }elseif(in_array($content, $randlist)) {
                $reply = $this->getRandStoryList();
            }elseif(in_array($content, $writename)){
                $reply = $this->getWName($openid);
            }elseif(is_numeric($content)) {//如果回复的是故事id，则返回故事链接
                $reply = $this->getLinkByID($content);
            }elseif($this->isSetWName($this->sender, $content)){//判断是否是设置笔名
//                $flag = $this->isSetWName($this->sender, $content);
                switch($_COOKIE['flag'])
                {
                    case 1:$reply = Params::SETWNSUC;break;
                    case 2:$reply = Params::ALREADYSETWNMAE;break;
                    case 3:$reply = Params::TOOLONGWNAME;break;
                    case 4:$reply = Params::USEDWNAME;break;
                    default: break;
                }
            }elseif($this->isConnect($content)){//这里判断用户输入是否是续接
                $reply = Params::NEWCONNECTWORDS;
                if(!$this->models->issetWName($this->sender))//判断是否存在笔名
                {
                    $reply = Params::SETWRITENAME;
                }
            }elseif($this->isBegin($content)) {//这里判断用户输入是否是开头
                $reply = Params::NEWBEGINWORDS;
                if(!$this->models->issetWName($openid))//判断是否存在笔名
                {
                    $reply = Params::SETWRITENAME;
                }
            }elseif(is_numeric(strpos($content, "刘政"))){
                $reply = "汪汪汪！！";
            //}elseif(is_numeric(strpos($content, "林涛"))){
            //    $reply = "林涛好丑！找不到女朋友！";
            }else{//判断是否在静态回复中
                $reply = $this->models->actionAutoReply($content);
                if(!isset($reply))
                {
                    $reply = Params::NONEREPLY;
                }
            }

            return $reply;
        }

        /*
         * 判断用户输入是否是续接，返回状态码
         * @flag
         */
        public function isConnect($content)
        {
            //先正则判断是否是标准格式，且字数大于200
            $pattern = "/#[\d]#(.*)/";
            $flag = preg_match($pattern, $content, $mathes);

            return 0 == $flag?false:true;
        }

        /*
         * 判断用户输入是否是开头，返回状态码
         * @flag
         */
        public function isBegin($content)
        {
            //正则判断是否是标准格式，且字数大于200
            $pattern = "/#(.*)#(.*)/";
            $flag = preg_match($pattern, $content, $mathes);

            return 0 == $flag?false:true;
        }

        /*
         * 判断用户输入是否是设置笔名，返回flag
         * @flag
         */
        public function isSetWName($openid, $content)
        {
            $name = substr($content, 7);
            //正则判断是否是标准格式，且字数大于200
            $pattern = "/笔名#(.*)/";
            $flag = preg_match($pattern, $content, $mathes);

            if($flag != 0)
            {
                if($this->models->issetWName($openid)) {
                    $_COOKIE['flag'] = 2;
                    return 2;//已经绑定了笔名
                }elseif($this->models->isusedWName($name)){
                    $_COOKIE['flag'] = 4;
                    return 4;//已存在这个笔名
                }elseif(strlen($content) > 40){
                    $_COOKIE['flag'] = 3;
                    return 3;//名字超长
                }
                //写入数据库
                $this->models->setWName($openid, $name);
                $_COOKIE['flag'] = 1;
                return 1;//成功
            }
            else
            {
                return false;
            }
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

        /*
         * 返回热门故事列表
         * @var list
         */
        public function getHotStoryList()
        {
            $hotList = $this->models->actionGetHotList();
            return $hotList;
        }

        /*
         * 返回热门故事列表
         * @var list
         */
        public function getNewStoryList()
        {
            $hotList = $this->models->actionGetNewList();
            return $hotList;
        }

        /*
         * 返回随机故事列表
         * @var list
         */
        public function getRandStoryList()
        {
            $randList = $this->models->actionGetRandList();
            return $randList;
        }

        /*
         * 返回故事链接
         * @var link
         */
        public function getLinkByID($id)
        {
            $reply = $this->models->actionGetStoryByID($id);
            return $reply;
        }

        /*
         * 返回用户笔名
         */
        public function getWName($openid)
        {
            $reply = $this->models->getWriteName($openid);
            return $reply;
        }
    }
