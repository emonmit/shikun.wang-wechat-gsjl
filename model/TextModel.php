<?php
/**
 * Created by PhpStorm.
 * User: Emits.wang
 * Date: 2015/8/24
 * Time: 16:46
 */

class TextModel{
    private $db_username;
    private $db_passwd;
    private $db_host;
    private $db_name;


    /*抽到basemodel*/
    //配置数据库连接
    public function __construct()
    {
        $this->db_username = '';
        $this->db_passwd = '';
        $this->db_host = '';
        $this->db_name = '';

        $db_connect=mysql_connect($this->db_host,$this->db_username,$this->db_passwd) or die("Unable to connect to the MySQL!");
        mysql_select_db($this->db_name,$db_connect);
    }

    //验证发消息用户是否在用户列表，否则添加
    //收集用户输入信息，存入数据库中
    public function actionIndex($content, $sender, $mid)
    {
        //验证发消息用户是否在用户列表
        $sql = "select `uid` from `user` where `openid` = '$sender'";
        $result = mysql_query($sql);
        $rows = mysql_fetch_array($result);
        $uid = $rows['uid'];
        //如果不存在openid则创建新纪录
        if(empty($rows))
        {
            $sql = "insert into `user` (`openid`)values('$sender')";
            mysql_query($sql);
            $sql = "select `uid` from `user` where `openid` = '$sender'";
            $result = mysql_query($sql);
            $rows = mysql_fetch_array($result);
            $uid = $rows['uid'];
        }

        $sql = "insert into `reply` (`uid`, `msg_id`, `type`, `content`)values('$uid', '$mid', ".Params::MSGTYPE.", '$content')";
        mysql_query($sql);

        return $uid;
    }

    //固定的回复内容
    public function actionAutoReply($content)
    {
        $sql = "select `key_reply` from `static_reply` where `keywd` = '$content' order by `id` DESC";
        $result = mysql_query($sql);
        $reply = mysql_fetch_array($result);
        return $reply['key_reply'];
    }

    //获取热门故事
    public function actionGetHotList()
    {
        $sql = "select `id`, `title` from `story` where `status` = ".Params::STATUS_OK." order by `times` DESC limit ".Params::ListLimitNum;
        $result = mysql_query($sql);
        $reply = '';
        while($row = mysql_fetch_row($result))
        {
            $more = "#$row[0]#<a href='".Params::STORYLINKBASE."$row[0]'>$row[1]</a>";
            $reply = $this->println($reply, $more);
        }
        return $reply;
    }

    //获取最新故事
    public function actionGetNewList()
    {
        $sql = "select `id`, `title` from `story` where `status` = ".Params::STATUS_OK." order by `update_time` DESC limit ".Params::ListLimitNum;
        $result = mysql_query($sql);
        $reply = '';
        while($row = mysql_fetch_row($result))
        {
            $more = "#$row[0]#<a href='".Params::STORYLINKBASE."$row[0]'>$row[1]</a>";
            $reply = $this->println($reply, $more);
        }
        return $reply;
    }

    //获取随机故事
    public function actionGetRandList()
    {
        $sql = "select `id`, `title` from `story` where `status` = ".Params::STATUS_OK." order by rand() DESC limit ".Params::ListLimitNum;
        $result = mysql_query($sql);
        $reply = '';
        while($row = mysql_fetch_row($result))
        {
            $more = "#$row[0]#<a href='".Params::STORYLINKBASE."$row[0]'>$row[1]</a>";
            $reply = $this->println($reply, $more);
        }
        return $reply;
    }

    //根据故事编号获取故事内容
    public function actionGetStoryByID($id)
    {
        $sql = "select `id`, `title` from `story` where `id` = $id and status = ".Params::STATUS_OK;
        $result = mysql_query($sql);
        $reply = mysql_fetch_array($result);
        if(empty($reply))
        {
            $reply = '找不到这篇故事';
        }else{
            $reply = "<a href='".Params::STORYLINKBASE.$reply['id']."'>".$reply['title']."</a>";
        }
        return $reply;
    }

    /*
     * 判断是否存在笔名
     */
    public function issetWName($openid)
    {
        $sql = "select `write_name` from `user` where `openid` = '$openid'";
        $result = mysql_query($sql);
        $name = mysql_fetch_array($result);
        if($name['write_name'] == null)
        {
            return false;
        }else{
            return true;
        }
    }

    /*
     * 笔名是否被使用
     */
    public function isusedWName($wname)
    {
        $sql = "select `write_name` from `user` where `write_name` = '$wname'";
        $result = mysql_query($sql);
        $name = mysql_fetch_array($result);
        if($name['write_name'] == null)
        {
            return false;
        }else{
            return true;
        }
    }

    /*
     * 获取笔名
     */
    public function getWriteName($openid)
    {
        $sql = "select `write_name` from `user` where `openid` = '$openid'";
        $result = mysql_query($sql);
        $name = mysql_fetch_array($result);
        if($name['write_name'] == null)
        {
            $reply = Params::SETWRITENAME;
        }else{
            $reply = $name['write_name'];
        }
        return $reply;
    }

    /*
     * 设置笔名,写入数据库
     */
    public function setWName($openid, $name)
    {
        $sql = "update `user` set `write_name` = '$name' where `openid` = '$openid'";
        mysql_query($sql);

        return true;
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
