<?php
/**
 * Created by PhpStorm.
 * User: Emits.wang
 * Date: 2015/8/24
 * Time: 16:46
 */

class EventModel{
    private $db_username;
    private $db_passwd;
    private $db_host;
    private $db_name;

    //配置数据库连接
    public function __construct()
    {
        $this->db_username = '';    //这里输入你登陆数据库使用的用户名
        $this->db_passwd = '';    //这里输入对应的密码
        $this->db_host = '';     //这里设置数据库所在主机
        $this->db_name = '';    //这里设置数据库名称

        $db_connect=mysql_connect($this->db_host,$this->db_username,$this->db_passwd) or die("Unable to connect to the MySQL!");
        mysql_select_db($this->db_name,$db_connect);
    }

    /*
     * 当用户关注时
     */
    public function actionSubscribe($openid)
    {
        //如果不存在该id，则新建，否则标记状态为1
        $sql = "select * from `user` where `openid` = '$openid'";
        $result = mysql_query($sql);
        $rows = mysql_fetch_array($result);
        if(empty($rows))
        {
            $sql = "insert into `user` (`openid`)values('$openid')";
            mysql_query($sql);
        }else
        {
            $sql = "update `user` set `status` = ".Params::STATUS_OK." where `openid` = '$openid'";
            mysql_query($sql);

        }
        return true;
    }

    /*
     * 当用户取消关注时
     */
    public function actionUnsubscribe($openid)
    {
        //set 0
        $sql = "update `user` set `status` = ".Params::STATUS_ERROR." where `openid` = '$openid'";
        mysql_query($sql);
        return true;
    }
}
