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

		<?php $str = get_nav_name('/index.php/Admin/Question/index'); ?>
		<h3 class="page-title"><?php if($str == ''): ?>星空海天<?php else: echo ($str); endif; ?></h3>

		<ul class="breadcrumb">

			<li>

				<i class="icon-home"></i>

				<a href="<?php echo U('admin/index/index');?>" class="brand">
					星空海天<?php echo L('ADMIN_CENTER');?></a>
			</li>
			<li>
				>
					<?php echo get_bread_nav('/index.php/Admin/Question/index');?>
			</li>


		</ul>

		<!-- END PAGE TITLE & BREADCRUMB-->

	</div>

</div>


				<!-- END PAGE HEADER-->

				<!-- 右边主页面开始 -->
				<div class="wrap js-check-wrap">
					<form class="well form-search" method="post" action="<?php echo U('Question/index');?>">
						<ul class="fromUl clearfix">
							<li>
								分校：
								<select name="zone_id" id="zone_id" style="width: 120px;">
									<option value=''>全部</option>
									<?php if(is_array($school)): foreach($school as $key=>$vo): ?><option value='<?php echo ($vo["id"]); ?>' name-value="<?php echo ($vo["sz_name"]); ?>"><?php echo ($vo["sz_name"]); ?></option><?php endforeach; endif; ?>
								</select> &nbsp;&nbsp;
							</li>
							<li>
								<!-- <input type="hidden" id="zone_name"/> -->
								分区：
								<select name="zone_sub_id" id="zone_sub_id" style="width: 120px;">
									<option value=''>全部</option>
								</select> &nbsp;&nbsp;
								<!-- <input type="hidden" id="zone_sub_name"/> -->
							</li>
							<li>
								班级：
								<select name="class_id" id="class_id" style="width: 120px;">
									<option value=''>全部</option>
								</select> &nbsp;&nbsp;
							</li>
							<li>
								<select name="que_type" style="width: 120px;">
									<option value='que_name' <?php if($_REQUEST['que_type']== 'que_name'): ?>selected<?php endif; ?>>问题标题</option>
									<option value='content' <?php if($_REQUEST['que_type']== 'content'): ?>selected<?php endif; ?>>问题内容</option>
								</select> &nbsp;&nbsp;
							</li>
							<li>
								<input type="text" name="que_con" style="width: 200px;" value="<?php echo ($_REQUEST['que_con']); ?>" placeholder="请输入查询内容...">
							</li>
							<li>
								问题状态：
								<select name="accept_id" style="width: 120px;">
									<option value=''>全部</option>
									<option value='2' <?php if($_REQUEST['accept_id']== 2): ?>selected<?php endif; ?>>待回答</option>
									<option value='1' <?php if($_REQUEST['accept_id']== 1): ?>selected<?php endif; ?>>已回答</option>
								</select> &nbsp;&nbsp;
							</li>
							<li>
								<select name="user_type" style="width: 120px;">
									<option value='user_login' <?php if($_REQUEST['user_type']== 'user_login'): ?>selected<?php endif; ?>>提问人账号</option>
									<option value='user_nicename' <?php if($_REQUEST['user_type']== 'user_nicename'): ?>selected<?php endif; ?>>提问人姓名</option>
								</select> &nbsp;&nbsp;
							</li>
							<li>
								<input type="text" name="user" style="width: 200px;" value="<?php echo ($_REQUEST['user']); ?>" placeholder="请输入查询内容...">
								提问时间：
								<input type="text" name="start_time" class="js-datetime" value="<?php echo ($_REQUEST['start_time']); ?>" style="width: 120px;" autocomplete="off">-
								<input autocomplete="off" type="text" class="js-datetime" name="end_time" value="<?php echo ($_REQUEST['end_time']); ?>" style="width: 120px;">
							</li>
							<li>
								屏蔽状态：
								<select name="is_ban" style="width: 120px;">
									<option value=''>全部</option>
									<option value='1' <?php if($_REQUEST['is_ban']== 1): ?>selected<?php endif; ?>>屏蔽</option>
									<option value='2' <?php if($_REQUEST['is_ban']== 2): ?>selected<?php endif; ?>>正常</option>
								</select> &nbsp;&nbsp;
							</li>
						</ul>
						<div class="frombtn">
							<input type="submit" class="btn btn-primary" value="搜索" />
							<input type="reset" class="btn btn-primary" value="清空" />
						</div>
					</form>
						<input type="button" class="btn btn-primary" id="shield" value="批量屏蔽" />
						<input type="button" class="btn btn-primary" id="recovery" value="批量恢复" />
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th width="30"><input type="checkbox" id="allChk" class="js-check-all" data-direction="x" data-checklist="js-check-x"></th>
									<th width="800">内容</th>
									<th width="70">操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($question)): foreach($question as $key=>$vo): ?><tr>
									<th><input type="checkbox" class="cbk" data-yid="js-check-y" data-xid="js-check-x" name="ids" value="<?php echo ($vo["id"]); ?>"></th>
									<td height="50px"><a href="<?php echo U('Question/details?id='.$vo['id']);?>"><?php echo ($vo["que_name"]); ?></a><br/><?php if($vo['con'] != '' ): echo ($vo["con"]); else: ?><font color="#FF0000">暂时没有回答</font><?php endif; ?></td>
									<td height="50px">
										<?php if($vo['is_ban'] == 0): ?><a href="javascript:void(0)" onclick="shield(<?php echo ($vo["id"]); ?>)">屏蔽</a><?php elseif($vo['is_ban'] != 0): ?><a href="javascript:void(0)"  id-value="<?php echo ($vo["id"]); ?>" id="que_huifu">恢复</a><?php endif; ?>
									</td>
								</tr><?php endforeach; endif; ?>
							</tbody>
						</table>
						<div class="page clearfix"><?php echo ($page); ?></div>
				</div>
				<!-- 右边主页面结束 -->
				<script src="/public/js/common.js"></script>
				<script type="text/javascript" src="/public/js/datePicker/datePicker.js?v="></script>
				<script>
					// 问题恢复数据提交
	                $(function(){
	                    $('#que_huifu').on('click', function(){
												var id = $(this).attr('id-value');
												var url = "index.php?g=Admin&m=Question&a=que_reply_shield";
													var index = layer.confirm('确认要恢复吗?',function(index){
		                        $.ajax({
		                            type:'POST',
		                            url:url,
		                            data:{id:id},
		                            success:function(data){
		                                var msg = JSON.parse(data);
		                                layer.msg(msg.msg)
		                                location.reload();
		                            },
		                            error:function(){
		                                layer.msg('请求失败')
		                            }
		                        });
													  layer.close(index);
													})
	                    });
	                });

					// 问题屏蔽弹层
	                function shield(id) {
	                    var html = "/index.php?g=Admin&m=Question&a=shield_layer&id="+id;

	                    layer.open({
	                        type: 2,
	                        title: '屏蔽',
	                        area: ['30%', '35%'], //宽高
	                        content: html
	                    });
	                }


					// 获取分校
					$("#zone_id").delegate("option", "click", function(e){  // 点击校区
						var zone_id = $(this).attr('value'); // 获取校区的id值
						var zone_name = $(this).attr('name-value'); // 获取校区的name值
						// $('#zone_name').val(zone_name);
						$("#zone_sub_id").find("option").remove();

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
					          $("#zone_sub_id").html(str);
	                        }
	                    });
					})

					// 获取班级
					$("#zone_sub_id").delegate("option", "click", function(e){  // 点击分校
						var zone_id = $(this).attr('value'); // 获取分校的id值
						var zone_name = $(this).attr('name-value'); // 获取分校的name值
						// $('#zone_name').val(zone_name);
						$("#class_id").find("option").remove();

						var url = '/index.php?g=Admin&m=Classnotice&a=getClass&fzone_id='+zone_id;
						$.ajax({
					        url:url,
					        type:'GET',
					        dataType:'json',
					        data:{},
					        success:function(msg){
					          var str = "<option value=''>--全部--</option>";
					          $(msg).each(function(i){
					          	var item = msg[i];
					          	str += '<option value="'+item['id']+'" name-value="'+item['class_name']+'">'+item['class_name']+'</option>';
					          });
					          $("#class_id").html(str);
	                        }
	                    });
					})


					$(document).ready(function() {
						// 全选 or 全不选
						$("#allChk").bind('click',function(){
							if ($('#allChk').is(':checked')) {
								$(".cbk").each(function(){
									$(this).parent().addClass('checked');
									$(this).attr('checked',true)
								})
							} else {
								$(".cbk").each(function(){
									$(this).parent().removeClass('checked');
									$(this).attr('checked',false);
								})
							}
						})
					});
					/* 批量屏蔽 */
					$("#shield").click(function() {
						var ids = '';
						$(".cbk").each(function() {
							if ($(this).is(':checked')) {
								ids += ',' + $(this).val(); //逐个获取id
							}
						});
						ids = ids.substring(1); // 对id进行处理，去除第一个逗号
						if (ids.length == 0) {
							layer.msg('请选择要编辑的选项')
						} else {
								layer.confirm('确定屏蔽？如果是请点击确定。',function(){
									var url = "index.php?g=Admin&m=Question&a=shield&ids="+ids;
									$.ajax({
										type: "post",
										url: url,
										success: function(msg) {
											if (msg.code == 1) {
												layer.msg(msg.msg);
												location.reload();
											} else {
												layer.msg(msg.msg);
											}
										},
										error: function() {
											layer.msg("页面请求错误，请检查重试或联系管理员！");
										}
									});
									layer.close(index);
								})
						}
					})

					/* 批量恢复 */
					$("#recovery").click(function() {
						var ids = '';
						$(".cbk").each(function() {
							if ($(this).is(':checked')) {
								ids += ',' + $(this).val(); //逐个获取id
							}
						});
						ids = ids.substring(1); // 对id进行处理，去除第一个逗号
						if (ids.length == 0) {
							layer.msg('请选择要编辑的选项')
						} else {
							var index = layer.confirm('确定屏蔽？如果是请点击确定。',function(){
								var url = "index.php?g=Admin&m=Question&a=recovery&ids="+ids;
								$.ajax({
									type: "post",
									url: url,
									success: function(msg) {
										if (msg.code == 1) {
											layer.msg(msg.msg);
											location.reload();
										} else {
											layer.msg(msg.msg);
										}
									},
									error: function() {
										layer.msg("页面请求错误，请检查重试或联系管理员！");
									}
								});
							})
						}
					})
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