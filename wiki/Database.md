故事接龙-数据库设计


#存用户最基本的信息===============ok
用户表（uid, openid, status, create_time）

#每条记录都是一个完整的故事====================ok
故事表（id, title, content, times, status, update_time, create_time）

#收录所有用户的消息数据========================ok
回复表（id, uid, msg_id, type, content, create_time）

#所有片段记录
片段表（）

#所有固定内容的回复============ok
固定回复表（id, key, value）

#所有消息类型===============ok
消息信息表（id, type）
