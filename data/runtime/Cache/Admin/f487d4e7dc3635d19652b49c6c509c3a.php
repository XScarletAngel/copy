<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->

<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	<title>星空海天教育</title>

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="" name="author" />

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
    	$(function(){
    		$("[data-toggle='tooltip']").tooltip();
    	});
    </script>
	<style>.expander{margin-left: -20px;}</style>

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

		<?php $str = get_nav_name('/index.php/Admin/School/index'); ?>
		<h3 class="page-title"><?php if($str == ''): ?>星空海天<?php else: echo ($str); endif; ?></h3>

		<ul class="breadcrumb">

			<li>

				<i class="icon-home"></i>

				<a href="<?php echo U('admin/index/index');?>" class="brand">
					星空海天<?php echo L('ADMIN_CENTER');?></a>
			</li>
			<li>
				>
					<?php echo get_bread_nav('/index.php/Admin/School/index');?>
			</li>


		</ul>

		<!-- END PAGE TITLE & BREADCRUMB-->

	</div>

</div>


				<!-- END PAGE HEADER-->

				<!-- 右边主页面开始 -->
				<div class="wrap js-check-wrap">
					<form class="well form-search" method="post" action="<?php echo U('School/index');?>">
						<ul class="fromUl clearfix">
							<li>
								院校编号：
								<input type="text" name="code" style="width: 200px;" value="<?php echo ($_REQUEST['code']); ?>" placeholder="请输入院校编号...">
							</li>
							<li>
								院校名称：
								<input type="text" name="school_name" style="width: 200px;" value="<?php echo ($_REQUEST['school_name']); ?>" placeholder="请输入院校名称...">
							</li>
							<li>
								211：
								<select name="flag_211" style="width: 120px;">
									<option value=''>全部</option>
									<option value='1' <?php if($_REQUEST['flag_211']== 1): ?>selected<?php endif; ?>>是</option>
									<option value='0' <?php if($_REQUEST['flag_211']== 0): ?>selected<?php endif; ?>>否</option>
								</select> &nbsp;&nbsp;
								985：
								<select name="flag_985" style="width: 120px;">
									<option value=''>全部</option>
									<option value='1' <?php if($_REQUEST['flag_985']== 1): ?>selected<?php endif; ?>>是</option>
									<option value='0' <?php if($_REQUEST['flag_985']== 0): ?>selected<?php endif; ?>>否</option>
								</select> &nbsp;&nbsp;
								自主划线：
								<select name="flag_score" style="width: 120px;">
									<option value=''>全部</option>
									<option value='1' <?php if($_REQUEST['flag_score']== 1): ?>selected<?php endif; ?>>是</option>
									<option value='0' <?php if($_REQUEST['flag_score']== 0): ?>selected<?php endif; ?>>否</option>
								</select> &nbsp;&nbsp;
								研究生院校：
								<select name="flag_master" style="width: 120px;">
									<option value=''>全部</option>
									<option value='1' <?php if($_REQUEST['flag_211']== 1): ?>selected<?php endif; ?>>是</option>
									<option value='0' <?php if($_REQUEST['flag_211']== 0): ?>selected<?php endif; ?>>否</option>
								</select> &nbsp;&nbsp;
								所在城市：
								<select name="area_id" style="width: 120px;">
									<option value=''>全部</option>
									<?php if(is_array($area)): foreach($area as $key=>$vo): ?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["area"]); ?></option><?php endforeach; endif; ?>
								</select> &nbsp;&nbsp;
							</li>
						</ul>
						<div class="frombtn">
							<input type="submit" class="btn btn-primary" value="搜索" />
							<input type="reset" class="btn btn-primary" value="清空" />
						</div>
					</form>
						<a href="<?php echo U('School/add');?>"><input type="button" class="btn btn-primary" value="添加院校" /></a>
						<table class="table table-hover table-bordered table-list">
							<thead>
								<tr>
									<th width="50">ID</th>
									<th width="50">院校编号</th>
									<th width="140">院校名称</th>
									<th width="50">院校所在城市</th>
									<th width="90">院校隶属</th>
									<th width="50">是否211</th>
									<th width="50">是否985</th>
									<th width="50">是否自主划线院校</th>
									<th width="50">是否是研究生院校</th>
									<th width="70"><?php echo L('ACTIONS');?></th>
								</tr>
							</thead>
							<?php if(is_array($lists)): foreach($lists as $key=>$vo): ?><tr>
			                    <td><?php echo ($vo["id"]); ?></td>
								<td><?php echo ($vo["code"]); ?></td>
								<td><?php echo ($vo["school_name"]); ?></td>
								<td><?php echo ($vo["area"]); ?></td>
								<td><?php echo ($vo["belong"]); ?></td>
								<td>
									<?php if($vo["flag_211"] == 1 ): ?>是
							        	<?php else: ?> 否<?php endif; ?>
							    </td>
								<td>
									<?php if($vo["flag_985"] == 1 ): ?>是
							        	<?php else: ?> 否<?php endif; ?>
								</td>
								<td>
									<?php if($vo["flag_score"] == 1 ): ?>是
							        	<?php else: ?> 否<?php endif; ?>
								</td>
								<td>
									<?php if($vo["flag_master"] == 1 ): ?>是
							        	<?php else: ?> 否<?php endif; ?>
							    </td>
								<td>
									<a href="<?php echo U('School/edit',array('id'=>$vo['id']));?>"><?php echo L('EDIT');?></a> |
									<a href="javascript:void(0);" id-value="<?php echo ($vo["id"]); ?>" class="delete"><?php echo L('DELETE');?></a>
								</td>
							</tr><?php endforeach; endif; ?>
						</table>
						<div class="page clearfix"><?php echo ($page); ?></div>
				</div>
				<!-- 右边主页面结束 -->
				<script>
					$(function(){
						$(".delete").click(function(){
							var id = $(this).attr('id-value');
							var url = "index.php?g=Admin&m=School&a=delete";
							deleteFun(id,url)//删除功能公共函数在common.js中引用
						});
					});
				</script>
			</div>
			<!-- END PAGE CONTAINER-->

		</div>

		<!-- END PAGE -->

	</div>
<!-- 加载的右边框架 -->


	<!-- END CONTAINER -->

	<!-- 引入底部开始 -->

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