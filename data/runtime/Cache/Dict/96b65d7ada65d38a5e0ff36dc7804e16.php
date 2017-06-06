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
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>

    <script>
    	$(function(){
    		$("[data-toggle='tooltip']").tooltip();
    	});
    </script>
	<style>
		.expander{margin-left: -20px;}
		.controls input[type="radio"] {margin-left:0px;}
	</style>

</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- 引入顶部开始 -->


	<!-- 引入顶部结束 -->

	<!-- BEGIN CONTAINER -->

	<div class="page-container">

		<!-- 引入左侧边栏开始 -->
		<!-- 引入左侧边栏结束 -->

		<!-- BEGIN PAGE -->

		<div class="page-content">

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- END PAGE HEADER-->

				<!-- 右边主页面开始 -->
				<div class="wrap">
					<form method="post" class="form-horizontal js-ajax-forms" action="<?php echo U('Srv/editPost');?>">
						<fieldset>
							<div class="control-group">
								<label class="control-label">服务名称:</label>
								<div class="controls">
									<input type="text" name="srv_name" value="<?php echo ($item["srv_name"]); ?>">
									<span class="form-required">*</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">服务说明:</label>
								<div class="controls">
									<textarea name="srv_info" id="" cols="30" rows="5"><?php echo ($item["srv_info"]); ?></textarea>
									<span class="form-required">*</span>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">是否启用:</label>
								<div class="controls mt8">
									<label class="radio"><input type="radio" name="srv_status" value="1" <?php if($item[srv_status] == 1): ?>checked="checked"<?php endif; ?>><em>是</em></label>
									<label class="radio"><input type="radio" name="srv_status" value="0" <?php if($item[srv_status] == 0): ?>checked="checked"<?php endif; ?>><em>否</em></label>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<input type="hidden" name="id" value="<?php echo ($item[id]); ?>"/>
									<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('EDIT');?></button>
									<a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
				<script src="/public/js/common.js"></script>
				<script src="/public/js/layer/layer.js"></script>
				<!-- 右边主页面结束 -->
				<script>
					$(function () {

						/////---------------------
						Wind.use('validate','ajaxForm','artDialog',function() {
							var form = $('form.js-ajax-forms');
							//ie处理placeholder提交问题
							if ($.browser && $.browser.msie) {
								form.find('[placeholder]').each(function() {
									var input = $(this);
									if (input.val() == input
													.attr('placeholder')) {
										input.val('');
									}
								});
							}
							//表单验证开始
							form.validate({
								//是否在获取焦点时验证
								onfocusout : false,
								//是否在敲击键盘时验证
								onkeyup : false,
								//当鼠标掉级时验证
								onclick : false,
								//验证错误
								showErrors : function(errorMap,errorArr) {
									//errorMap {'name':'错误信息'}
									//errorArr [{'message':'错误信息',element:({})}]
									try {
										$(errorArr[0].element).focus();
										art.dialog({
											id : 'error',
											icon : 'error',
											lock : true,
											fixed : true,
											background : "#CCCCCC",
											opacity : 0,
											content : errorArr[0].message,
											cancelVal : '确定',
											cancel : function() {
												$(errorArr[0].element).focus();
											}
										});
									} catch (err) {}
								},
								//验证规则
								rules : {
									'srv_name' : {required : 1},
									'srv_info' : {required : 1},
								},
								//验证未通过提示消息
								messages : {
									'srv_name' : {required : '请填写服务名称'},
									'srv_info' : {required : '请填写服务说明'},
								},
								//给未通过验证的元素加效果,闪烁等
								highlight : false,
								//是否在获取焦点时验证
								onfocusout : false,
								//验证通过，提交表单
								submitHandler : function(forms) {
									$(forms).ajaxSubmit({
										url : form.attr('action'), //按钮上是否自定义提交地址(多按钮情况)
										dataType : 'json',
										beforeSubmit : function(arr,$form,options) {

										},
										success : function(data,statusText,xhr,$form) {
											if (data.status) {
												setCookie("refersh_time",1);
												//添加成功
												Wind.use("artDialog",function() {
													art.dialog({
														id : "succeed",
														icon : "succeed",
														fixed : true,
														lock : true,
														background : "#CCCCCC",
														opacity : 0,
														content : data.info,
														button : [
															{
																name : '继续添加？',
																callback : function() {
																	reloadPage(window);
																	return true;
																},
																focus : true
															},
															{
																name : '返回列表',
																callback : function() {
																	parent.location.reload();
																	//location.href = "<?php echo U('Srv/index');?>";
																	return true;
																}
															}
														]
													});
												});
											} else {
												alert(data.info);
											}
										}
									});
								}
							});
						});
						////-------------------------
					});
				</script>
			</div>
			<!-- END PAGE CONTAINER-->    
			
		</div>

		<!-- END PAGE -->

	</div>
<!-- 加载的右边框架 -->


</body>

<!-- END BODY -->

</html>