<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!-->
<html lang="en" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

    <meta charset="utf-8"/>

    <title>Metronic | Admin Dashboard Template</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <meta content="" name="description"/>

    <meta content="" name="author"/>

    <!-- 引入顶部样式文件 -->
    <link href="/public/media/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/style-metro.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/style.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/style-responsive.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/default.css" rel="stylesheet" type="text/css" id="style_color"/>

<link href="/public/media/css/uniform.default.css" rel="stylesheet" type="text/css"/>

<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL STYLES -->

<link href="/public/media/css/jquery.gritter.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/daterangepicker.css" rel="stylesheet" type="text/css" />

<link href="/public/media/css/fullcalendar.css" rel="stylesheet" type="text/css"/>

<link href="/public/media/css/common.css" rel="stylesheet" type="text/css"/>
<link href="/public/media/css/jqvmap.css" rel="stylesheet" type="text/css" media="screen"/>

<link href="/public/media/css/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>

<!-- END PAGE LEVEL STYLES -->

<link rel="shortcut icon" href="/public/media/image/favicon.ico" />

<!--ajaxForm begin-->
<script>
//全局变量
var GV = {
	HOST:"<?php echo ($_SERVER['HTTP_HOST']); ?>",
    ROOT: "/",
    WEB_ROOT: "/",
    JS_ROOT: "public/js/"
};
</script>

<script src="/public/js/jquery.js"></script>
<script src="/public/js/wind.js"></script>
<script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<script src="/public/js/layer/layer.js"></script>
<script type="text/javascript" src="/public/js/datePicker/datePicker.js?v="></script>
<script>
    $(function(){
        $("[data-toggle='tooltip']").tooltip();
    });
</script>
<link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
<!--ajaxForm end-->

<style type="text/css">
div.pagination{padding:3px;<a href="https://www.baidu.com/s?wd=font-size&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1YdnjwWuHmYrjfYnHuWm1FW0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnWn3rH61PHmdnWfknHfdPWfY" target="_blank" class="baidu-highlight">font-size</a>:80%;margin:3px;color:#ff6500;<a href="https://www.baidu.com/s?wd=text-align&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1YdnjwWuHmYrjfYnHuWm1FW0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnWn3rH61PHmdnWfknHfdPWfY" target="_blank" class="baidu-highlight">text-align</a>:center;}
div.pagination a{border:#ff9600 1px solid;padding:5px 7px;background-position:50% bottom;margin:0 3px 0 0;<a href="https://www.baidu.com/s?wd=text-decoration&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1YdnjwWuHmYrjfYnHuWm1FW0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnWn3rH61PHmdnWfknHfdPWfY" target="_blank" class="baidu-highlight">text-decoration</a>:none;}
div.pagination a<a href="https://www.baidu.com/s?wd=%3Ahover&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1YdnjwWuHmYrjfYnHuWm1FW0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnWn3rH61PHmdnWfknHfdPWfY" target="_blank" class="baidu-highlight">:hover</a>{border:#ff9600 1px solid;background-image:none;color:#ff6500;background-color:#ffc794;}
div.pagination a:active{border:#ff9600 1px solid;background-image:none;color:#ff6500;background-color:#ffc794;}
div.pagination span.current{border:#ff6500 1px solid;padding:5px 7px;<a href="https://www.baidu.com/s?wd=font-weight&tn=44039180_cpr&fenlei=mv6quAkxTZn0IZRqIHckPjm4nH00T1YdnjwWuHmYrjfYnHuWm1FW0ZwV5Hcvrjm3rH6sPfKWUMw85HfYnjn4nH6sgvPsT6KdThsqpZwYTjCEQLGCpyw9Uz4Bmy-bIi4WUvYETgN-TLwGUv3EnWn3rH61PHmdnWfknHfdPWfY" target="_blank" class="baidu-highlight">font-weight</a>:bold;color:#ff6500;margin:0 3px 0 0;background-color:#ffbe94;}
div.pagination span.disabled{border:#ffe3c6 1px solid;padding:5px 7px;color:#ffe3c6;margin:0 3px 0 0;}
</style>

    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <!-- <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script> -->
    <script>
        $(function () {
            $("[data-toggle='tooltip']").tooltip();
        });
    </script>
    <style>
        .expander {
            margin-left: -20px;
        }

        .head-open {
            display: inline-block;
            width: 25%;
            height: 50px;
            line-height: 50px;
            border: 1px solid green;
            text-align: center;
            border-radius: 5px;
        }

        .h-div {
            text-align: center;
        }

        #se-head {
            background-color: greenyellow;
            color: inherit;
        }

        .se-head {
            background-color: darkseagreen;
            font-style: inherit;
        }

        .controls input[type="radio"] {
            margin-left: 0px;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .dropdown2 {
            display: inline-block;
            width: 220px;
            height: 20px;
            position: relative;
            margin-left: 20px;
        }

        .dropdown2 .editor {
            display: block;
            border: 0;
            padding: 0;
            width: 100%;
            box-shadow: inset 1px 2px 3px #ddd;
            height: inherit;
        }

        .dropdown2 .trigger {
            position: absolute;
            top: 0;
            right: 0;
            padding: 3px;
        }

        .dropdown2 ul {
            display: none;
            width: 98%;
            height: 140px;
            padding: 2px;
            position: absolute;
            top: 100%;
            background-color: #FFF;
            border: 1px solid #DDD;
            border-radius: 0 0 5px 5px;
            overflow-y: auto;
            overflow-x: hidden;
            margin: 0px;
            z-index: 10;
            margin-top: 8px;
        }

        .dropdown2 ul li {
            height: 20px;
            display: block;
            font-size: 12px;
            overflow: hidden;
            cursor: pointer;
        }

        .dropdown2 ul li .hot {
            color: red;
        }

        .dropdown2 ul li:hover {
            background-color: #EEE;
        }

        .dropdown2 ul .search {
            display: block;
            backgound: url(imgs/search.gif) no-repeat scroll center right;
            border-bottom: 1px solid #808080;
        }

        .dropdown2 ul .search:hover {
            background-color: #FFF;
        }

        .dropdown2 ul .search input {
            padding: 2px;
            width: 100%;
            border: 0;
            font-size: 14px;
            background: none;
        }

    </style>
</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

<!-- 引入顶部开始 -->

<div class="header navbar navbar-inverse navbar-fixed-top">

	<!-- BEGIN TOP NAVIGATION BAR -->

	<div class="navbar-inner">

		<div class="container-fluid">

			<!-- BEGIN LOGO -->

			<a class="brand" href="<?php echo U('admin/index/index');?>">

			<img src="/public/media/image/logo.png" alt="logo"/>

			</a>

			<!-- END LOGO -->

			<!-- BEGIN RESPONSIVE MENU TOGGLER -->

			<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">

			<img src="/public/media/image/menu-toggler.png" alt="" />

			</a>          

			<!-- END RESPONSIVE MENU TOGGLER -->            

			<!-- BEGIN TOP NAVIGATION MENU -->              

			<ul class="nav pull-right">

				<!-- BEGIN NOTIFICATION DROPDOWN -->   

				<!-- <li class="dropdown" id="header_notification_bar">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<i class="icon-warning-sign"></i>

					<span class="badge">6</span>

					</a>

					<ul class="dropdown-menu extended notification">

						<li>

							<p>You have 14 new notifications</p>

						</li>

						<li>

							<a href="#">

							<span class="label label-success"><i class="icon-plus"></i></span>

							New user registered. 

							<span class="time">Just now</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="label label-important"><i class="icon-bolt"></i></span>

							Server #12 overloaded. 

							<span class="time">15 mins</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="label label-warning"><i class="icon-bell"></i></span>

							Server #2 not respoding.

							<span class="time">22 mins</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="label label-info"><i class="icon-bullhorn"></i></span>

							Application error.

							<span class="time">40 mins</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="label label-important"><i class="icon-bolt"></i></span>

							Database overloaded 68%. 

							<span class="time">2 hrs</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="label label-important"><i class="icon-bolt"></i></span>

							2 user IP blocked.

							<span class="time">5 hrs</span>

							</a>

						</li>

						<li class="external">

							<a href="#">See all notifications <i class="m-icon-swapright"></i></a>

						</li>

					</ul>

				</li> -->

				<!-- END NOTIFICATION DROPDOWN -->

				<!-- BEGIN INBOX DROPDOWN -->

				<!-- <li class="dropdown" id="header_inbox_bar">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<i class="icon-envelope"></i>

					<span class="badge">5</span>

					</a>

					<ul class="dropdown-menu extended inbox">

						<li>

							<p>You have 12 new messages</p>

						</li>

						<li>

							<a href="inbox.html?a=view">

							<span class="photo"><img src="/public/media/image/avatar2.jpg" alt="" /></span>

							<span class="subject">

							<span class="from">Lisa Wong</span>

							<span class="time">Just Now</span>

							</span>

							<span class="message">

							Vivamus sed auctor nibh congue nibh. auctor nibh

							auctor nibh...

							</span>  

							</a>

						</li>

						<li>

							<a href="inbox.html?a=view">

							<span class="photo"><img src="./media/image/avatar3.jpg" alt="" /></span>

							<span class="subject">

							<span class="from">Richard Doe</span>

							<span class="time">16 mins</span>

							</span>

							<span class="message">

							Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh

							auctor nibh...

							</span>  

							</a>

						</li>

						<li>

							<a href="inbox.html?a=view">

							<span class="photo"><img src="./media/image/avatar1.jpg" alt="" /></span>

							<span class="subject">

							<span class="from">Bob Nilson</span>

							<span class="time">2 hrs</span>

							</span>

							<span class="message">

							Vivamus sed nibh auctor nibh congue nibh. auctor nibh

							auctor nibh...

							</span>  

							</a>

						</li>

						<li class="external">

							<a href="inbox.html">See all messages <i class="m-icon-swapright"></i></a>

						</li>

					</ul>

				</li> -->

				<!-- END INBOX DROPDOWN -->

				<!-- BEGIN TODO DROPDOWN -->

				<!-- <li class="dropdown" id="header_task_bar">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">

					<i class="icon-tasks"></i>

					<span class="badge">5</span>

					</a>

					<ul class="dropdown-menu extended tasks">

						<li>

							<p>You have 12 pending tasks</p>

						</li>

						<li>

							<a href="#">

							<span class="task">

							<span class="desc">New release v1.2</span>

							<span class="percent">30%</span>

							</span>

							<span class="progress progress-success ">

							<span style="width: 30%;" class="bar"></span>

							</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="task">

							<span class="desc">Application deployment</span>

							<span class="percent">65%</span>

							</span>

							<span class="progress progress-danger progress-striped active">

							<span style="width: 65%;" class="bar"></span>

							</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="task">

							<span class="desc">Mobile app release</span>

							<span class="percent">98%</span>

							</span>

							<span class="progress progress-success">

							<span style="width: 98%;" class="bar"></span>

							</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="task">

							<span class="desc">Database migration</span>

							<span class="percent">10%</span>

							</span>

							<span class="progress progress-warning progress-striped">

							<span style="width: 10%;" class="bar"></span>

							</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="task">

							<span class="desc">Web server upgrade</span>

							<span class="percent">58%</span>

							</span>

							<span class="progress progress-info">

							<span style="width: 58%;" class="bar"></span>

							</span>

							</a>

						</li>

						<li>

							<a href="#">

							<span class="task">

							<span class="desc">Mobile development</span>

							<span class="percent">85%</span>

							</span>

							<span class="progress progress-success">

							<span style="width: 85%;" class="bar"></span>

							</span>

							</a>

						</li>

						<li class="external">

							<a href="#">See all tasks <i class="m-icon-swapright"></i></a>

						</li>

					</ul>

				</li> -->

				<!-- END TODO DROPDOWN -->

				<!-- BEGIN USER LOGIN DROPDOWN 用户登录后信息开始-->

				<li class="dropdown user">

					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="" src="/public/media/image/avatar1_small.jpg" />
						<span class="username"><?php echo ($_SESSION['name']); ?></span>
						<i class="icon-angle-down"></i>
					</a>

					<ul class="dropdown-menu">

						<!-- <li><a href="extra_profile.html"><i class="icon-user"></i> My Profile</a></li>

						<li><a href="page_calendar.html"><i class="icon-calendar"></i> My Calendar</a></li>

						<li><a href="inbox.html"><i class="icon-envelope"></i> My Inbox(3)</a></li>

						<li><a href="#"><i class="icon-tasks"></i> My Tasks</a></li>

						<li class="divider"></li>

						<li><a href="extra_lock.html"><i class="icon-lock"></i> Lock Screen</a></li> -->

						<li><a href="<?php echo U('admin/Public/logout');?>"><i class="icon-key"></i>退出</a></li>

					</ul>

				</li>

				<!-- END USER LOGIN DROPDOWN 用户登录后信息结束-->

			</ul>

			<!-- END TOP NAVIGATION MENU --> 

		</div>

	</div>

	<!-- END TOP NAVIGATION BAR -->

</div>

<!-- 引入顶部结束 -->

<!-- BEGIN CONTAINER -->

<div class="page-container">

    <!-- 引入左侧边栏开始 -->

    <div class="page-sidebar nav-collapse collapse">

			<!-- BEGIN SIDEBAR MENU -->        

			<ul class="page-sidebar-menu">

				<li>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

					<div class="sidebar-toggler hidden-phone"></div>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

				</li>

				<li>



				</li>

				<!-- 加载的左边菜单 -->
				
				<?php echo getsubmenu($submenus);?>

			</ul>

			<!-- END SIDEBAR MENU -->

		</div>

    <!-- 引入左侧边栏结束 -->

    <!-- BEGIN PAGE -->

    <div class="page-content">

        <!-- BEGIN PAGE CONTAINER-->

        <div class="container-fluid">

            <!-- BEGIN PAGE HEADER-->

<div class="row-fluid">

	<div class="span12">

		<!-- 右上角切换样式开始 -->

		<!-- <div class="color-panel hidden-phone">

			<div class="color-mode-icons icon-color"></div>

			<div class="color-mode-icons icon-color-close"></div>

			<div class="color-mode">

				<p>THEME COLOR</p>

				<ul class="inline">

					<li class="color-black current color-default" data-style="default"></li>

					<li class="color-blue" data-style="blue"></li>

					<li class="color-brown" data-style="brown"></li>

					<li class="color-purple" data-style="purple"></li>

					<li class="color-grey" data-style="grey"></li>

					<li class="color-white color-light" data-style="light"></li>

				</ul>

				<label>

					<span>Layout</span>

					<select class="layout-option m-wrap small">

						<option value="fluid" selected>Fluid</option>

						<option value="boxed">Boxed</option>

					</select>

				</label>

				<label>

					<span>Header</span>

					<select class="header-option m-wrap small">

						<option value="fixed" selected>Fixed</option>

						<option value="default">Default</option>

					</select>

				</label>

				<label>

					<span>Sidebar</span>

					<select class="sidebar-option m-wrap small">

						<option value="fixed">Fixed</option>

						<option value="default" selected>Default</option>

					</select>

				</label>

				<label>

					<span>Footer</span>

					<select class="footer-option m-wrap small">

						<option value="fixed">Fixed</option>

						<option value="default" selected>Default</option>

					</select>

				</label>

			</div>

		</div> -->

		<!-- 右上角切换样式结束 -->

		<!-- BEGIN PAGE TITLE & BREADCRUMB-->


		<!-- <h3 class="page-title">

			星空海天 <small>statistics and more</small>

		</h3> -->

		<?php $str = get_nav_name('/index.php/SaleOnline/Deal/index'); ?>
		<h3 class="page-title"><?php if($str == ''): ?>星空海天<?php else: echo ($str); endif; ?></h3>

		<ul class="breadcrumb">

			<li>

				<i class="icon-home"></i>

				<a href="<?php echo U('admin/index/index');?>" class="brand">
					星空海天<?php echo L('ADMIN_CENTER');?></a>
			</li>
			<li>
				>
					<?php echo get_bread_nav('/index.php/SaleOnline/Deal/index');?>
			</li>


		</ul>

		<!-- END PAGE TITLE & BREADCRUMB-->

	</div>

</div>


            <!-- END PAGE HEADER-->

            <!-- 右边主页面开始 -->

            <div class="wrap js-check-wrap">
                <form class="well form-search" action="<?php echo U('Deal/index');?>" method="post">
                    订单号：<input type="text" name="order_no" value="<?php echo ($param["order_no"]); ?>">

                    分校：
                    <!--<div class="dropdown2" id="dropdown_sel">  &lt;!&ndash; 模拟 select 的标记， &ndash;&gt;-->
                        <!--<input class="editor2" type="text" name="zone_id" disabled="disabled"-->
                               <!--value="<?php echo ($param['zone_name']); ?>"/> &lt;!&ndash; 显示当前值的输入框 &ndash;&gt;-->
                        <!--<input class="zone_id" type="hidden" name="zone_id" id="zone_id" value="<?php echo ($param['zone_id']); ?>"/>-->
                        <!--&lt;!&ndash; 学校id &ndash;&gt;-->
                        <!--<input class="trigger" type="button" value="↓"/> &lt;!&ndash; 用于显示下拉列表的按钮 &ndash;&gt;-->
                        <!--<ul>   &lt;!&ndash; 列表菜单 &ndash;&gt;-->
                            <!--<li class="search"><input type="text"/></li>   &lt;!&ndash; 过滤输入的列表， &ndash;&gt;-->
                            <!--&lt;!&ndash; 添加数据的时候，都添加在这个地方 &ndash;&gt;-->
                            <!--<?php if(is_array($zones)): foreach($zones as $key=>$vo): ?>-->
                                <!--<li data-value="<?php echo ($vo["sz_name"]); ?>" id-value="<?php echo ($vo["id"]); ?>" code-value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["sz_name"]); ?>-->
                                <!--</li>-->
                            <!--<?php endforeach; endif; ?>-->
                        <!--</ul>-->
                    <!--</div>-->
                    <select name="zone_id" id="zone_id" style="width: 120px;">
                        <option value=''>全部</option>
                        <?php if(is_array($school)): foreach($school as $key=>$vo): ?><option value='<?php echo ($vo["id"]); ?>' name-value="<?php echo ($vo["sz_name"]); ?>"
                            <?php if($vo['id'] == $param['zone_id']){ ?> selected="selected"<?php } ?>
                            ><?php echo ($vo["sz_name"]); ?></option><?php endforeach; endif; ?>
                    </select>


                    <input name="zone_name" type="hidden" id="zone_name" value="<?php echo ($param['zone_name']); ?>"/>
                    <input name="sub_zone_name" type="hidden" id="sub_zone_name" value="<?php echo ($param['sub_zone_name']); ?>"/>

                    校区：<select name="sub_zone_id" id="sub_zone_id">
                    <option value="<?php echo ($param['sub_zone_id']); ?>"><?php echo ($param['sub_zone_name']); ?></option>
                </select>
                    <br><br>
                    状态：
                    <select name="pay_status_type">
                        <option value="">全部</option>
                        <option value="1"
                        <?php if($param["pay_status_type"] == '1'): ?>selected="selected"<?php endif; ?>
                        >待支付</option>
                        <option value="2"
                        <?php if($param["pay_status_type"] == '2'): ?>selected="selected"<?php endif; ?>
                        >已支付</option>
                        <option value="3"
                        <?php if($param["pay_status_type"] == '3'): ?>selected="selected"<?php endif; ?>
                        >交易关闭</option>
                        <option value="4"
                        <?php if($param["pay_status_type"] == '4'): ?>selected="selected"<?php endif; ?>
                        >未退款</option>
                        <option value="5"
                        <?php if($param["pay_status_type"] == '5'): ?>selected="selected"<?php endif; ?>
                        >已退款</option>
                    </select>
                    创建方式：
                    <select name="order_type">
                        <option value="">全部</option>
                        <option value="1"
                        <?php if($param["order_type"] == '1'): ?>selected="selected"<?php endif; ?>
                        >系统创建</option>
                        <option value="2,3"
                        <?php if($param["order_type"] == '2,3'): ?>selected="selected"<?php endif; ?>
                        >人工创建</option>
                    </select>
                    交易类型：
                    <select name="pay_status">
                        <option value="">全部</option>
                        <option value="1,2,3"
                        <?php if($param["pay_status"] == '1,2,3'): ?>selected="selected"<?php endif; ?>
                        >支付交易</option>
                        <option value="4,5"
                        <?php if($param["pay_status"] == '4,5'): ?>selected="selected"<?php endif; ?>
                        >退款交易</option>
                    </select>
                    <br><br>
                    支付方式：
                    <select name="pay_way">
                        <option value="">全部</option>
                        <option value="1"
                        <?php if($param["pay_way"] == '1'): ?>selected="selected"<?php endif; ?>
                        >支付宝</option>
                        <option value="2"
                        <?php if($param["pay_way"] == '2'): ?>selected="selected"<?php endif; ?>
                        >微信</option>
                        <option value="3"
                        <?php if($param["pay_way"] == '3'): ?>selected="selected"<?php endif; ?>
                        >现金</option>
                    </select>
                    查询条件：
                    <select name="s1">
                        <option value="user_login"
                        <?php if($param["s1"] == 'user_login'): ?>selected="selected"<?php endif; ?>
                        >学员帐号</option>
                        <option value="user_no"
                        <?php if($param["s1"] == 'user_no'): ?>selected="selected"<?php endif; ?>
                        >学员编号</option>
                        <option value="user_name"
                        <?php if($param["s1"] == 'user_name'): ?>selected="selected"<?php endif; ?>
                        >学员姓名</option>
                    </select>

                    <input type="text" name="sc1" class="s1" value="<?php echo ($param["sc1"]); ?>" placeholder="请输入筛选内容...">
                    <br><br>
                    下单时间：
                    <input type="text" name="start_time" class="js-date" value="<?php echo ($param["start_time"]); ?>"> -
                    <input type="text" name="end_time" class="js-date" value="<?php echo ($param["end_time"]); ?>">
                    <br><br>
                    <div class="frombtn">
                        <input type="submit" class="btn btn-primary" value="搜索"/>
                    </div>
                </form>
            </div>

            <table class="table table-hover table-bordered table-list">
                <tr>
                    <td>订单号</td>
                    <td>用户账号</td>
                    <td>分校</td>
                    <td>校区</td>
                    <td>创建方式</td>

                    <td>交易类型</td>
                    <td>交易名称</td>
                    <td>商品</td>
                    <td>备注</td>
                    <td>关联交易编号（退款交易填）</td>

                    <td>收据编号</td>
                    <td>成交金额</td>
                    <td>支付方式</td>
                    <td>下单时间</td>
                    <td>成交时间</td>

                    <td>状态</td>
                    <td>创建人</td>
                </tr>
                <?php if(is_array($datas)): foreach($datas as $key=>$data): ?><tr>
                        <td><?php echo ($data["order_no"]); ?></td>
                        <td><?php echo ($data["user_login"]); ?></td>
                        <td><?php echo ($data["zone_name"]); ?></td>
                        <td><?php echo ($data["sub_zone_name"]); ?></td>
                        <td>
                            <?php if( empty($data.goods_id)): ?>人工创建<?php endif; ?>
                            <?php if(!empty($data.goods_id)): ?>系统创建<?php endif; ?>
                        </td>

                        <td>
                            <?php if($data["order_type"] == 1 ): ?>线上支付<?php endif; ?>
                            <?php if($data["order_type"] == 2 ): ?>已收款账单<?php endif; ?>
                            <?php if($data["order_type"] == 3 ): ?>已退款账单<?php endif; ?>
                        </td>
                        <td><?php echo ($data["trans_title"]); ?></td>
                        <td><?php echo ($data["goods_name"]); ?></td>
                        <td><?php echo ($data["remark"]); ?></td>
                        <td></td>

                        <td><?php echo ($data["receipt_no"]); ?></td>
                        <td><?php echo ($data["pay_price"]); ?></td>
                        <td>
                            <?php if($data["pay_way"] == 1 ): ?>支付宝<?php endif; ?>
                            <?php if($data["pay_way"] == 2 ): ?>微信<?php endif; ?>
                            <?php if($data["pay_way"] == 3 ): ?>现金<?php endif; ?>
                        </td>
                        <td><?php echo ($data["add_time"]); ?></td>
                        <td><?php echo ($data["pay_time"]); ?></td>

                        <td>
                            <?php if($data["pay_status"] == 1 ): ?>待支付<?php endif; ?>
                            <?php if($data["pay_status"] == 2 ): ?>已支付<?php endif; ?>
                            <?php if($data["pay_status"] == 3 ): ?>已关闭<?php endif; ?>
                            <?php if($data["pay_status"] == 4 ): ?>已退款<?php endif; ?>
                        </td>
                        <td><?php echo ($data["create_username"]); ?></td>
                    </tr><?php endforeach; endif; ?>
            </table>

            <div class="page clearfix"><?php echo ($page); ?></div>

            <!-- 右边主页面结束 -->
            <script src="/public/js/common.js"></script>
            <script type="text/javascript" src="/public/js/datePicker/datePicker.js?v="></script>
            <script>
                $(function () {
                    $("#zone_id").delegate("option", "click", function(e){  // 点击校区
                        var zone_id = $(this).attr('value'); // 获取校区的id值
                        var zone_name = $(this).attr('name-value'); // 获取校区的name值
                        $('#zone_name').val(zone_name);
                        $("#sub_zone_id").find("option").remove();

                        var url = '/index.php?g=Admin&m=Classnotice&a=getZone&zone_id='+zone_id;
                        $.ajax({
                            url:url,
                            type:'GET',
                            dataType:'json',
                            data:{},
                            success:function(msg){
                                var str = "<option value=''>--全部--</option>";
                                $(msg).each(function(i){
                                    var item = msg[i];
                                    str += '<option value="'+item['id']+'" name-value="'+item['sz_name']+'">'+item['sz_name']+'</option>';
                                });
                                $("#sub_zone_id").html(str).val('');
                                $('#sub_zone_name').val('');
                            }
                        });
                    });

                    $(this).click(function () {   //当数据列表在显示时，如果点击了页面其它区域，则隐藏列表
                        $(".dropdown2 ul:visible").hide();
                    });


                    $("#sub_zone_id").delegate("option", "click", function () {
                        var v = $(this).attr("name-value");
                        $('#sub_zone_name').val(v);
                    });

                });
            </script>
        </div>
        <!-- END PAGE CONTAINER-->

    </div>

    <!-- END PAGE -->

</div>
<!-- 加载的右边框架 -->


<div class="footer">

	<div class="footer-inner">

		2017 &copy; Copyright© <a href="JavaScript:;" title="星空海天" target="_blank">星空海天</a>

	</div>

	<div class="footer-tools">

		<span class="go-top">

		<i class="icon-angle-up"></i>

		</span>

	</div>

</div>

<!-- 引入底部结束 -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->

	<!-- BEGIN CORE PLUGINS -->

<!-- 	<script src="/public/media/js/jquery-1.10.1.min.js" type="text/javascript"></script> -->
<script src="/public/js/common.js"></script>
	<script src="/public/media/js/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="/public/media/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>

	<!-- <script src="/public/media/js/bootstrap.min.js" type="text/javascript"></script> -->

	<!--[if lt IE 9]>

	<script src="media/js/excanvas.min.js"></script>

	<script src="media/js/respond.min.js"></script>

	<![endif]-->

	<script src="/public/media/js/jquery.slimscroll.min.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.blockui.min.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.cookie.min.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.uniform.min.js" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="/public/media/js/jquery.vmap.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.vmap.russia.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.vmap.world.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.vmap.europe.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.vmap.germany.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.vmap.usa.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.vmap.sampledata.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.flot.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.flot.resize.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.pulsate.min.js" type="text/javascript"></script>

	<script src="/public/media/js/date.js" type="text/javascript"></script>

	<script src="/public/media/js/daterangepicker.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.gritter.js" type="text/javascript"></script>

	<script src="/public/media/js/fullcalendar.min.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.easy-pie-chart.js" type="text/javascript"></script>

	<script src="/public/media/js/jquery.sparkline.min.js" type="text/javascript"></script>

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="/public/media/js/app.js" type="text/javascript"></script>

	<script src="/public/media/js/index.js" type="text/javascript"></script>
<!-- <script src="/admin/themes/simplebootx/Public/assets/js/index.js"></script> -->
<!-- <script src="/public/js/common.js"></script> -->
	<!-- END PAGE LEVEL SCRIPTS -->

	<script>

		jQuery(document).ready(function() {

		   App.init(); // initlayout and core plugins

		   Index.init();

		   Index.initJQVMAP(); // init index page's custom scripts

		   Index.initCalendar(); // init index page's custom scripts

		   Index.initCharts(); // init index page's custom scripts

		   Index.initChat();

		   Index.initMiniCharts();

		   Index.initDashboardDaterange();

		   Index.initIntro();

		});

	</script>

	<!-- END JAVASCRIPTS -->


</body>

<!-- END BODY -->

</html>