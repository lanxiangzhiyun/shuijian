<?php if (!defined('THINK_PATH')) exit();?>	<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>网站后台系统管理</title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="/slackck/Public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="/slackck/Public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="/slackck/Public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="/slackck/Public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="/slackck/Public/assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="/slackck/Public/assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->
        <link rel="stylesheet" href="/slackck/Public/assets/css/slackck.css" />
		<!-- ace settings handler -->
		<script src="/slackck/Public/assets/js/ace-extra.js"></script>
		<script src="/slackck/Public/assets/js/jquery.leanModal.min.js"></script>
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="/slackck/Public/assets/js/html5shiv.js"></script>
		<script src="/slackck/Public/assets/js/respond.js"></script>
		<![endif]-->
	</head>

	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default    navbar-collapse">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="<?php echo U('Index/index');?>" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							Slakck System
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->
					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons">
						<span class="sr-only">Toggle user menu</span>

						<img src="/slackck/Public/assets/avatars/user.jpg" alt="Jason's Photo" />
					</button>

					<!-- /section:basics/navbar.toggle -->
				</div>

				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
					<ul class="nav ace-nav">
						<li class="transparent">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
							</a>

							<div class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
								<div class="tabbable">
									<ul class="nav nav-tabs">
										<li class="active">
											<a data-toggle="tab" href="#navbar-tasks">
												Tasks
												<span class="badge badge-danger">4</span>
											</a>
										</li>

										<li>
											<a data-toggle="tab" href="#navbar-messages">
												Messages
												<span class="badge badge-danger">5</span>
											</a>
										</li>
									</ul><!-- .nav-tabs -->

									<div class="tab-content">
										<div id="navbar-tasks" class="tab-pane in active">
											<ul class="dropdown-menu-right dropdown-navbar dropdown-menu">
												<li class="dropdown-content">
													<ul class="dropdown-menu dropdown-navbar">
														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Software Update</span>
																	<span class="pull-right">65%</span>
																</div>

																<div class="progress progress-mini">
																	<div style="width:65%" class="progress-bar"></div>
																</div>
															</a>
														</li>

														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Hardware Upgrade</span>
																	<span class="pull-right">35%</span>
																</div>

																<div class="progress progress-mini">
																	<div style="width:35%" class="progress-bar progress-bar-danger"></div>
																</div>
															</a>
														</li>

														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Unit Testing</span>
																	<span class="pull-right">15%</span>
																</div>

																<div class="progress progress-mini">
																	<div style="width:15%" class="progress-bar progress-bar-warning"></div>
																</div>
															</a>
														</li>

														<li>
															<a href="#">
																<div class="clearfix">
																	<span class="pull-left">Bug Fixes</span>
																	<span class="pull-right">90%</span>
																</div>

																<div class="progress progress-mini progress-striped active">
																	<div style="width:90%" class="progress-bar progress-bar-success"></div>
																</div>
															</a>
														</li>
													</ul>
												</li>

												<li class="dropdown-footer">
													<a href="#">
														See tasks with details
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</li>
											</ul>
										</div><!-- /.tab-pane -->

										<div id="navbar-messages" class="tab-pane">
											<ul class="dropdown-menu-right dropdown-navbar dropdown-menu">
												<li class="dropdown-content">
													<ul class="dropdown-menu dropdown-navbar">
														<li>
															<a href="#">
																<img src="/slackck/Public/assets/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Alex:</span>
																		Ciao sociis natoque penatibus et auctor ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>a moment ago</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="/slackck/Public/assets/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Susan:</span>
																		Vestibulum id ligula porta felis euismod ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>20 minutes ago</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="/slackck/Public/assets/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Bob:</span>
																		Nullam quis risus eget urna mollis ornare ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>3:15 pm</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="/slackck/Public/assets/avatars/avatar2.png" class="msg-photo" alt="Kate's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Kate:</span>
																		Ciao sociis natoque eget urna mollis ornare ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>1:33 pm</span>
																	</span>
																</span>
															</a>
														</li>

														<li>
															<a href="#">
																<img src="/slackck/Public/assets/avatars/avatar5.png" class="msg-photo" alt="Fred's Avatar" />
																<span class="msg-body">
																	<span class="msg-title">
																		<span class="blue">Fred:</span>
																		Vestibulum id penatibus et auctor  ...
																	</span>

																	<span class="msg-time">
																		<i class="ace-icon fa fa-clock-o"></i>
																		<span>10:09 am</span>
																	</span>
																</span>
															</a>
														</li>
													</ul>
												</li>

												<li class="dropdown-footer">
													<a href="inbox.html">
														See all messages
														<i class="ace-icon fa fa-arrow-right"></i>
													</a>
												</li>
											</ul>
										</div><!-- /.tab-pane -->
									</div><!-- /.tab-content -->
								</div><!-- /.tabbable -->
							</div><!-- /.dropdown-menu -->
						</li>

						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="nav-user-photo" src="/slackck/Public/assets/avatars/user.jpg" alt="Jason's Photo" />
								<span class="user-info">
									<small>Welcome,</small>
									<?php echo ($_COOKIE['admin_username']); ?>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li>
									<a href="#">
										<i class="ace-icon fa fa-cog"></i>
										个人设置
									</a>
								</li>

								<li>
									<a href="profile.html">
										<i class="ace-icon fa fa-user"></i>
										会员中心
									</a>
								</li>

								<li class="divider"></li>

								<li>
									<a href="javascript:;"  id="logout">
										<i class="ace-icon fa fa-power-off"></i>
										注销
									</a>
								</li>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>

				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>
<script src="/slackck/Public/assets/js/jquery.min.js"></script>
<script src="/slackck/Public/assets/js/jquery.form.js"></script>
<script src="/slackck/Public/layer/layer.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#logout").click(function(){
		layer.confirm('你确定要退出吗？', {icon: 3}, function(index){
	    layer.close(index);
	    window.location.href="<?php echo U('Login/logout');?>";
	});
	});
});
</script>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">

			<!-- #section:basics/sidebar -->

				<div id="sidebar" class="sidebar responsive">

				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<button class="btn btn-success">
							<i class="ace-icon fa fa-signal"></i>
						</button>

						<button class="btn btn-info">
							<i class="ace-icon fa fa-pencil"></i>
						</button>

						<!-- #section:basics/sidebar.layout.shortcuts -->
						<button class="btn btn-warning">
							<i class="ace-icon fa fa-users"></i>
						</button>

						<button class="btn btn-danger">
							<i class="ace-icon fa fa-cogs"></i>
						</button>

						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>

						<span class="btn btn-info"></span>

						<span class="btn btn-warning"></span>

						<span class="btn btn-danger"></span>
					</div>
				</div><!-- /.sidebar-shortcuts -->

				<ul class="nav nav-list">
<?php use Common\Controller\AuthController; use Think\Auth; $m = M('auth_rule'); $field = 'id,name,title,css'; $data = $m->field($field)->where('pid=0 AND status=1')->order('sort')->select(); $auth = new Auth(); foreach ($data as $k=>$v){ if(!$auth->check($v['name'], cookie('aid')) && cookie('aid') != 1){ unset($data[$k]); } } ?>

<?php if(is_array($data)): foreach($data as $key=>$v): ?><li class="<?php if(CONTROLLER_NAME == $v['name']): ?>active open<?php endif; ?>"><!--open代表打开状态-->
						<a href="#" class="dropdown-toggle">
							<i class="menu-icon fa <?php echo ($v["css"]); ?>"></i>
							<span class="menu-text">
								<?php echo ($v["title"]); ?>
							</span>

							<b class="arrow fa fa-angle-down"></b>
						</a>

						<b class="arrow"></b>

						<ul class="submenu">
    <?php $m = M('auth_rule'); $dataa = $m->where(array('pid'=>$v['id'],'status'=>1))->select(); foreach ($dataa as $kk=>$vv){ if(!$auth->check($vv['name'], cookie('aid')) && cookie('aid') != 1){ unset($dataa[$kk]); } } ?>
    <?php if(is_array($dataa)): foreach($dataa as $key=>$j): ?><li class="<?php if(($_COOKIE['s'] == $j['id'])): ?>active<?php endif; ?>">
								<a href="<?php echo U($j['name'],array('s'=>$j['id']));?>">
									<i class="menu-icon fa fa-caret-right"></i>
									<?php echo ($j["title"]); ?>
								</a>
								<b class="arrow"></b>
							</li><?php endforeach; endif; ?>
						</ul>
					</li><?php endforeach; endif; ?>
                    
				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>

			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
					
							<div class="row maintop">
							<div class="col-xs-12 col-sm-1">
<!-- 点击模态框（Modal） -->
								<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal">
									<i class="ace-icon fa fa-bolt bigger-110"></i>
										添加会员组
								</button>
								
							</div>
							</div>
	

							<div class="row">
							    <div class="col-xs-12">
										<div>
										<form id="member_group_order" name="member_group_order" method="post" action="<?php echo U('member_group_order');?>" >
											<table width="100%" class="table table-striped table-bordered table-hover" id="dynamic-table">
												<thead>
													<tr>
													  <th width="10%">会员组ID</th>
													  <th width="22%">会员组名称</th>
													  <th width="34%">满足积分条件</th>
													  <th width="10%" style="border-right:#CCC solid 1px;">状态</th>
													  <th width="13%" style="border-right:#CCC solid 1px;">排序</th>
													  <th width="11%" style="border-right:#CCC solid 1px;">操作</th>
												  </tr>
												</thead>

												<tbody>
                                                
                                                <?php if(is_array($member_group_list)): foreach($member_group_list as $key=>$v): ?><tr>
														<td height="28" ><?php echo ($v["member_group_id"]); ?></td>
														<td><?php echo ($v["member_group_name"]); ?></td>
														<td><?php echo ($v["member_group_bomlimit"]); ?> - <?php echo ($v["member_group_toplimit"]); ?> 积分</td>
														<td>
									<?php if($v[member_group_open] == 1): ?><a class="red" href="javascript:;" onclick="return stateyes(<?php echo ($v["member_group_id"]); ?>);" title="已开启">
											<div id="zt<?php echo ($v["member_group_id"]); ?>"><button class="btn btn-minier btn-yellow">状态开启</button></div>
										</a>
									<?php else: ?>
										<a class="red" href="javascript:;" onclick="return stateyes(<?php echo ($v["member_group_id"]); ?>);" title="已禁用">
											<div id="zt<?php echo ($v["member_group_id"]); ?>"><button class="btn btn-minier btn-danger">状态禁用</button></div>
										</a><?php endif; ?>														</td>
														<td><input name="<?php echo ($v["member_group_id"]); ?>" value=" <?php echo ($v["member_group_order"]); ?>" class="list_order"/></td>
														<td>
									<div class="hidden-sm hidden-xs action-buttons">
										<a class="green"  href="javascript:;" onclick="return member_group_edit(<?php echo ($v["member_group_id"]); ?>);"  title="修改">
											<i class="ace-icon fa fa-pencil bigger-130"></i>
										</a>
										<a class="red" href="javascript:;" onclick="return del(<?php echo ($v["member_group_id"]); ?>);" title="删除">
											<i class="ace-icon fa fa-trash-o bigger-130"></i>
										</a>
									</div>
													</td>
													</tr><?php endforeach; endif; ?>
                                                  <tr>
													  <td colspan="8" align="left"><button type="submit"  id="btnorder" class="btn btn-white btn-yellow btn-sm">排序</button></td>
												  </tr>

												</tbody>
										  </table>
										  </form>
							    		</div>
									</div>
								</div>

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="hidden">

									<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse">
										<ul class="nav nav-list">
                                        
    <?php $m = M('auth_rule'); $dataaa = $m->where(array('pid'=>$_COOKIE['s'],'status'=>1))->order('sort')->select(); foreach ($dataaa as $kkk=>$vvv){ if(!$auth->check($vvv['name'], cookie('aid')) && cookie('aid') != 1){ unset($dataaa[$kkk]); } } ?>
    <?php if(is_array($dataaa)): foreach($dataaa as $key=>$k): ?><li>
												<a href="<?php echo U(''.$k['name'].'');?>">
													<o class="font12 <?php if((CONTROLLER_NAME.'/'.ACTION_NAME == $k['name'])): ?>rigbg<?php endif; ?>"><?php echo ($k["title"]); ?></o>
												</a>

												<b class="arrow"></b>
											</li><?php endforeach; endif; ?>


										</ul><!-- /.nav-list -->
									</div><!-- .sidebar -->
								</div>

							</div><!-- /.col -->
						</div><!-- /.row -->

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

<!-- 显示模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" name="member_group_runadd" id="member_group_runadd" method="post" action="/slackck/index.php/Member/member_group_runadd">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" 
               aria-hidden="true">×
            </button>
            <h4 class="modal-title" id="myModalLabel">
               添加会员组
            </h4>
         </div>
         <div class="modal-body">
            
			
						<div class="row">
							<div class="col-xs-12">
																		
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 会员组名称：  </label>
										<div class="col-sm-10">
											<input type="text" name="member_group_name" id="member_group_name" placeholder="输入会员组名称" class="col-xs-10 col-sm-5" />
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 积分条件：  </label>
										<div class="col-sm-10">

											<span class="input-icon">
												<input type="text" id="form-field-icon-1" name="member_group_bomlimit" placeholder="输入下限积分" />
												<i class="ace-icon fa fa-leaf blue"></i>
											</span>

											<span class="input-icon input-icon-right">
												<input type="text" id="form-field-icon-2" name="member_group_toplimit" placeholder="输入上限积分" />
												<i class="ace-icon fa fa-leaf green"></i>
											</span>

											<!-- /section:elements.form.input-icon -->
										</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 排序：  </label>
										<div class="col-sm-10">
											<input type="text" name="member_group_order" id="member_group_order" value="50" class="col-xs-10 col-sm-3" />
											<span class="lbl">&nbsp;&nbsp;<span class="red">*</span>从小到大排序</span>
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 是否开启： </label>
										<div class="col-sm-10" style="padding-top:5px;">
                                            <input name="member_group_open" id="member_group_open" value="1" class="ace ace-switch ace-switch-4 btn-flat" type="checkbox" />
											<span class="lbl">&nbsp;&nbsp;默认关闭</span>
										</div>
									</div>
                                    <div class="space-4"></div>
									
								</div>
							</div>
			
			
			
			
         </div>
         <div class="modal-footer">
		 	<button type="submit" class="btn btn-primary">
            	提交保存
            </button>
		 	<button class="btn btn-info" type="reset">
				重置
			</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
				关闭
            </button>
         </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
   </form>
</div><!-- /.modal -->


<!-- 显示模态框（Modal） -->
<div class="modal fade in" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-backdrop fade in" id="gbbb" style="height: 100%;"></div>
<form class="form-horizontal" name="member_group_runedit" id="member_group_runedit" method="post" action="/slackck/index.php/Member/member_group_runedit">
<input type="hidden" name="member_group_id" id="editmember_group_id" value="" />
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" id="gb"  data-dismiss="modal" 
               aria-hidden="true">×
            </button>
            <h4 class="modal-title" id="myModalLabel">
               修改用户组
            </h4>
         </div>
         <div class="modal-body">
            
			
						<div class="row">
							<div class="col-xs-12">
																		
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 会员组名称：  </label>
										<div class="col-sm-10">
											<input type="text" name="member_group_name" id="editmember_group_name" value="" class="col-xs-10 col-sm-5" />
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 积分条件：  </label>
										<div class="col-sm-10">

											<span class="input-icon">
												<input type="text" id="editmember_group_bomlimit" name="member_group_bomlimit" placeholder="输入下限积分" />
												<i class="ace-icon fa fa-leaf blue"></i>
											</span>

											<span class="input-icon input-icon-right">
												<input type="text" id="editmember_group_toplimit" name="member_group_toplimit" placeholder="输入上限积分" />
												<i class="ace-icon fa fa-leaf green"></i>
											</span>

											<!-- /section:elements.form.input-icon -->
										</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 排序：  </label>
										<div class="col-sm-10">
											<input type="text" name="member_group_order" id="editmember_group_order" value="50" class="col-xs-10 col-sm-3" />
											<span class="lbl">&nbsp;&nbsp;<span class="red">*</span>从小到大排序</span>
										</div>
									</div>
                                    <div class="space-4"></div>
									
								</div>
							</div>			
			
			
			
         </div>
         <div class="modal-footer">
		 	<button type="submit" class="btn btn-primary">
            	提交保存
            </button>
            <button type="button" class="btn btn-default"  id="gbb" >
				关闭
            </button>
         </div>
      </div><!-- /.modal-content -->
   </div><!-- /.modal-dialog -->
   </form>
</div><!-- /.modal -->



<script>
function del(id){
	layer.confirm('你确定要删除吗？', {icon: 3}, function(index){
	layer.close(index);
	window.location.href="/slackck/index.php/Member/member_group_del/member_group_id/"+id+"";
	});
}

function stateyes(val){
		  $.post('<?php echo U("member_group_state");?>',
		  {x:val},
	function(data){
		if(data.status){
			if(data.info=='状态禁止'){
				var a='<button class="btn btn-minier btn-danger">状态禁用</button>'
				$('#zt'+val).html(a);
				layer.alert(data.info, {icon: 5});
				return false;
			}else{
				var b='<button class="btn btn-minier btn-yellow">状态开启</button>'
				$('#zt'+val).html(b);
				layer.alert(data.info, {icon: 6});
				return false;
			}
		}
	});
	return false;
}

//修改模态框状态
$(document).ready(function(){
	$("#myModaledit").hide();
	$("#gb").click(function(){
		$("#myModaledit").hide(200);
	});
	$("#gbb").click(function(){
		$("#myModaledit").hide(200);
	});
	$("#gbbb").click(function(){
		$("#myModaledit").hide(200);
	});
});

function member_group_edit(val){
		  $.post('<?php echo U("member_group_edit");?>',
		  {member_group_id:val},
	function(data){
		if(data.status==1){
				$(document).ready(function(){
					$("#myModaledit").show(300);
					$("#editmember_group_id").val(data.member_group_id);
					$("#editmember_group_name").val(data.member_group_name);
					$("#editmember_group_open").val(data.member_group_open);
					$("#editmember_group_toplimit").val(data.member_group_toplimit);
					$("#editmember_group_bomlimit").val(data.member_group_bomlimit);
					$("#editmember_group_order").val(data.member_group_order);
				});
			}else{

		}
	});
	return false;
}


$(function(){
	$('#member_group_runadd').ajaxForm({
		beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
		success: complete, // 这是提交后的方法
		dataType: 'json'
	});
	
	function checkForm(){
	
		if( '' == $.trim($('#member_group_name').val())){
			layer.alert('会员组名称不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#member_group_name').focus(); 
			});
			return false;
		}
		
		if( '' == $.trim($('#form-field-icon-1').val())){
			layer.alert('会员组积分下限不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#form-field-icon-1').focus(); 
			});
			return false;
		}
		
		if( '' == $.trim($('#form-field-icon-2').val())){
			layer.alert('会员组积分上限不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#form-field-icon-2').focus(); 
			});
			return false;
		}
 }
	function complete(data){
		if(data.status==1){
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
		}else{
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
			return false;	
		}
	}
});


$(function(){
	$('#member_group_runedit').ajaxForm({
		beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
		success: complete, // 这是提交后的方法
		dataType: 'json'
	});
	
	function checkForm(){
		if( '' == $.trim($('#editmember_group_name').val())){
			layer.alert('会员组名称不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#member_group_name').focus(); 
			});
			return false;
		}
		
		if( '' == $.trim($('#editmember_group_bomlimit').val())){
			layer.alert('会员组积分下限不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#editmember_group_bomlimit').focus(); 
			});
			return false;
		}
		
		if( '' == $.trim($('#editmember_group_toplimit').val())){
			layer.alert('会员组积分上限不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#editmember_group_toplimit').focus(); 
			});
			return false;
		}
	}
	function complete(data){
		if(data.status==1){
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
		}else{
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
			return false;	
		}
	}
 
});

//排序提交
$(function(){
	$('#member_group_order').ajaxForm({
		success: complete, // 这是提交后的方法
		dataType: 'json'
	});

	function complete(data){
		if(data.status==1){
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
		}else{
			layer.alert(data.info, {icon: 6}, function(index){
 			layer.close(index);
			window.location.href=data.url;
			});
		}
	}
});

</script>
				<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">slackck</span>
							后台管理系统 &copy; 2015-2016
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>
            

		<!-- basic scripts -->


		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='/slackck/Public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="/slackck/Public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="/slackck/Public/assets/js/maxlength.js"></script>
		<script src="/slackck/Public/assets/js/ace/ace.js"></script>
		<script src="/slackck/Public/assets/js/ace/ace.sidebar.js"></script>
		<script src="/slackck/Public/assets/js/ace/ace.submenu-hover.js"></script>


		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			   $('#sidebar2').insertBefore('.page-content');
			   
			   $('.navbar-toggle[data-target="#sidebar2"]').insertAfter('#menu-toggler');
			   
			   
			   $(document).on('settings.ace.two_menu', function(e, event_name, event_val) {
				 if(event_name == 'sidebar_fixed') {
					 if( $('#sidebar').hasClass('sidebar-fixed') ) {
						$('#sidebar2').addClass('sidebar-fixed');
						$('#navbar').addClass('h-navbar');
					 }
					 else {
						$('#sidebar2').removeClass('sidebar-fixed')
						$('#navbar').removeClass('h-navbar');
					 }
				 }
			   }).triggerHandler('settings.ace.two_menu', ['sidebar_fixed' ,$('#sidebar').hasClass('sidebar-fixed')]);
			})
		</script>
		<script src="/slackck/Public/assets/js/jquery.form.js"></script>

    
		</div><!-- /.main-container -->
	</body>
</html>