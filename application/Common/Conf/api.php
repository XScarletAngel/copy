<?php
//api 配置文件
return [
    'API' =>  [
        'url'               =>  API_URL,
        'aes_key'           =>  API_AES_KEY,                    //api 加密key
        'password_key'      =>  PASSWORD_KEY,                   //密码加密key
        'security_key'      =>  API_SECURITY_KEY,               //api header security

        'uri'               =>  [
            //用户
            'member'        =>  [
                'login'     =>  ['post', '/user/login'],          //登录
                'register'  =>  ['post', '/user/register'],       //注册
                'password'  =>  ['post', '/user/forgetpwd'],      //修改密码
                'code'      =>  ['post', '/sms/send'],            //短信验证码
                'notice'    =>  ['post', '/sms/Notice'],          //短信通知

                //type	        是	int	0:全部 1:待支付 2:已支付 3:已关闭 4:未退款 5:已退款
                //page	        是	int	当前页
                //size	        是	int	每页条数
                'order_list'=>  ['get', '/order/lists'],

                //user_type	否	int	用户类型 1:学生（注册用户） 2:老师
                //msg_type	是	int	0：全部，1讨论消息 2:直播消息 3:课表消息 4:系统消息 5:班级公告 6:问答 7:通知消息’ 16:互动消息
                //page	是	int	当前页
                //size	是	int	每页条数
                'message'   =>  ['get', '/msg/lists'],
                'new_msg_count' => ['get', '/msg/unreadcount'],

                 //个人中心
                'center'    =>  ['get', '/user/center'],

                //个人信息
                'info'          =>  ['get', '/user/userinfo'],
                'stu_info'      =>  ['get', '/stu/info'],
                'teacher_info'  =>  ['get', '/teacher/info'],

                //修改资料
                //user_nicename	    否	string	昵称
                //user_realname	    否	string	真实姓名
                //sex	            否	int	性别
                //birthday	        否	date	生日
                //province	            string	省
                //city	                string	市
                //county	            string	县
                'edit_info'      =>  ['post', '/user/editinfo'],

                //修改手机号码
                'edit_mobile'   => ['post', '/user/changephone'],


            ],

            //班级
            'class'         => [
                //班级公告
                //class_id	    是	int	班级ID
                //user_type	    是	int	用户类型：1老师，2学生
                //page	        是	int	当前页
                //pagesize	    是	int	每页条数
                'notice'    => ['get', '/notice/classlist'],

                //班级课件列表
                //class_id	    是	int	班级ID
                'courseware'=> ['get', '/course/catalog'],
            ],

            //学生
            'stu'           =>  [
                //我的班级
                'classes'   =>  ['get', '/stu/classes'],

                //课表
                //start     否	date	开始日期，如2017-05-01 ；默认当天
                //end	    否	date	结束日期，如2017-05-03；默认当月最后一天或开始日期的月份最后一天
                //class_id	否	int	    班级id（pc用）
                //user_type	否	int	    用户类型 1-学员，2-老师，默认学生（pc用）
                'schedule'  =>  ['post', '/home/lesson'],

                //学生的班级成绩
                //class_id	是	string	班级ID
                'class_score' => ['get', '/stu/class_score'],

                //参数名	必选	类型	说明
                //class_id	是	int	班级id
                //page	否	int	页码，默认为1，第一页，第二页。。。
                //size	否	int	每次返回的数据量，默认10条
                //keyword	否	string	搜索关键字(PC用)
                //is_solve	否	int	是否解决,2-解决，1-未解决(PC用)
                'class_question' => ['get', '/classes/question'],

                //向老师提问
                //class_id	是	int	    班级id
                //title	    是	string	问题标题
                //content	是	string	问题描述
                //image	    否	array	图片地址，数组形式
                'class_question_add'  => ['post', '/classes/questionadd'],

                //问题详情
                //class_id	   是	int	    班级id
                //question_id  是	int  	问题id
                'class_question_detail'  => ['post', '/classes/questionview'],

                //采纳回复
                //reply_id	是	int	    回复id
                'class_discuss_accept'  => ['post', '/classes/questionaccept'],

                //问答评论回复
                //class_id	是	int	    班级id
                //reply_id  是	int 	回复id
                //content	是	string	内容
                'class_qa_comment'  => ['post', '/classes/questioncomment'],

                //user_type	否	int	用户类型 1:学生（注册用户） 2:老师
                //msg_type	是	int	0：全部， 1：互动消息 2:直播消息 3:课表消息 4:系统消息 5:班级公告
                //page	    是	int	当前页
                //size	    是	int	每页条数
                'class_message'      => ['get', '/msg/lists'],

                //话题列表
                //class_id	是	int	班级id
                //page	否	int	页码，默认为1，第一页，第二页。。。
                //size	否	int	每次返回的数据量，默认10条
                //keyword	否	string	搜索关键字(PC用)
                //is_my	否	int	是否我的话题,1-我的，0-全部(PC用)
                'class_discuss' => ['get', '/classes/topic'],

                //发起话题
                //class_id	是	int	    班级id
                //title	    是	string	标题
                //content	是	string	描述
                //image	    否	array	图片地址，数组形式
                'class_discuss_add'  => ['post', '/classes/topicadd'],

                //话题详情
                //class_id	是	int	    班级id
                //topic_id  是	int 	话题id
                'class_discuss_detail'  => ['post', '/classes/topicview'],

				//回复话题
                //class_id	是	int	    班级id
                //topic_id  是	int 	话题id
                //content	是	string	内容
                //image	    否	array	图片地址，数组形式
                'class_discuss_back'  => ['post', '/classes/topicnote'],

                //评论回复
                //class_id	是	int	    班级id
                //reply_id  是	int 	回复id
                //content	是	string	内容
                'class_discuss_comment'  => ['post', '/classes/topiccomment'],

                //点赞评论
                //reply_id	是	int	    回复id
                'class_discuss_like'  => ['post', '/classes/topiclike'],

				//我的成绩
                'learn_score'           => ['get', '/user/myscores'],
                //学习统计
                'learn_statistics'      => ['get', '/user/studystatistics'],

                //学习中心首页大接口
                'learn_index_page'      => ['get', '/learn/center'],


                //请假
                //lesson_id	是	string	课表id
                //leave_reason
                'leave'                 => ['post', '/learn/leave'],

                //确认上课
                //lesson_id	是	string	课表id
                'confirm'              => ['post', '/home/confirm'],
            ],

            //教师
            'tch'           =>  [
                //问答列表
                //class_id	是	int	班级id
                //page	否	int	页码，默认为1，第一页，第二页。。。
                //size	否	int	每次返回的数据量，默认10条
                //keyword	否	string	搜索关键字(PC用)
                //is_solve	否	int	是否解决,2-解决，1-未解决(PC用)
                'class_question' => ['get', '/classes/question'],

                //老师回答提问
                //class_id	 是	int	    班级id
                //question_id  是	int	问题id
                //content	是	string	问题描述
                //image	    否	array	图片地址，数组形式
                'class_question_back'  => ['post', '/classes/questionanswer'],

                //问题详情
                //class_id	   是	int	    班级id
                //question_id  是	int  	问题id
                'class_question_detail'  => ['post', '/classes/questionview'],

                //问答评论回复
                //class_id	是	int	    班级id
                //reply_id  是	int 	回复id
                //content	是	string	内容
                'class_qa_comment'  => ['post', '/classes/questioncomment'],

                //话题列表
                //class_id	是	int	班级id
                //page	否	int	页码，默认为1，第一页，第二页。。。
                //size	否	int	每次返回的数据量，默认10条
                //keyword	否	string	搜索关键字(PC用)
                //is_my	否	int	是否我的话题,1-我的，0-全部(PC用)
                'class_discuss' => ['get', '/classes/topic'],

                //发起话题
                //class_id	是	int	    班级id
                //title	    是	string	标题
                //content	是	string	描述
                //image	    否	array	图片地址，数组形式
                'class_discuss_add'  => ['post', '/classes/topicadd'],

                //话题详情
                //class_id	是	int	    班级id
                //topic_id  是	int 	话题id
                'class_discuss_detail'  => ['post', '/classes/topicview'],

                //回复话题
                //class_id	是	int	    班级id
                //topic_id  是	int 	话题id
                //content	是	string	内容
                //image	    否	array	图片地址，数组形式
                'class_discuss_back'  => ['post', '/classes/topicnote'],

                //评论回复
                //class_id	是	int	    班级id
                //reply_id  是	int 	回复id
                //content	是	string	内容
                'class_discuss_comment'  => ['post', '/classes/topiccomment'],

                //点赞评论
                //reply_id	是	int	    回复id
                'class_discuss_like'  => ['post', '/classes/topiclike'],
            ],

            //tool
            'tool'          => [
                //获取七牛云接口
                'qiniu_token'   => ['get', '/common/qiniutoken'],
            ],

            'push'          => [
                //个推标签
                'autosettag'   => ['post', '/push/autosettag'],
            ]
        ],

    ]
];
