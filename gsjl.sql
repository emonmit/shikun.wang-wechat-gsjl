-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-10-22 16:09:29
-- 服务器版本： 5.5.45
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gsjl`
--
CREATE DATABASE IF NOT EXISTS `gsjl` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `gsjl`;

-- --------------------------------------------------------

--
-- 表的结构 `msg_type`
--

CREATE TABLE IF NOT EXISTS `msg_type` (
  `id` int(2) NOT NULL COMMENT 'id',
  `name` varchar(16) NOT NULL COMMENT '类型名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息类型表';

-- --------------------------------------------------------

--
-- 表的结构 `reply`
--

CREATE TABLE IF NOT EXISTS `reply` (
  `id` int(12) NOT NULL COMMENT '消息表id',
  `uid` int(4) NOT NULL COMMENT '发消息人uid',
  `msg_id` varchar(20) NOT NULL COMMENT '消息id',
  `type` int(2) NOT NULL COMMENT '消息类型',
  `content` text NOT NULL COMMENT '消息正文',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '消息创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息表';

-- --------------------------------------------------------

--
-- 表的结构 `static_reply`
--

CREATE TABLE IF NOT EXISTS `static_reply` (
  `id` int(4) NOT NULL COMMENT 'id',
  `keywd` varchar(160) NOT NULL COMMENT '关键字',
  `key_reply` text NOT NULL COMMENT '对应回复'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='固定回复表';

-- --------------------------------------------------------

--
-- 表的结构 `story`
--

CREATE TABLE IF NOT EXISTS `story` (
  `id` int(8) NOT NULL COMMENT '故事id',
  `title` varchar(100) NOT NULL COMMENT '故事标题',
  `content` text NOT NULL COMMENT '故事内容',
  `times` int(8) NOT NULL DEFAULT '1' COMMENT '故事接龙次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '故事状态',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '故事最新更新时间',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '故事创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='故事表';

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(8) NOT NULL COMMENT '用户编号',
  `openid` varchar(28) NOT NULL COMMENT 'openid',
  `write_name` varchar(40) DEFAULT NULL COMMENT '用户署名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `msg_type`
--
ALTER TABLE `msg_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static_reply`
--
ALTER TABLE `static_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `msg_type`
--
ALTER TABLE `msg_type`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT COMMENT '消息表id';
--
-- AUTO_INCREMENT for table `static_reply`
--
ALTER TABLE `static_reply`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'id';
--
-- AUTO_INCREMENT for table `story`
--
ALTER TABLE `story`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT COMMENT '故事id';
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(8) NOT NULL AUTO_INCREMENT COMMENT '用户编号';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
