<?php if (!defined('THINK_PATH')) exit();?>	<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>网站后台系统管理</title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/bootstrap.css" />
		<link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->
        <link rel="stylesheet" href="/slackck2015-11-27/Public/assets/css/slackck.css" />
		<!-- ace settings handler -->
		<script src="/slackck2015-11-27/Public/assets/js/ace-extra.js"></script>
		<script src="/slackck2015-11-27/Public/assets/js/jquery.leanModal.min.js"></script>
		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="/slackck2015-11-27/Public/assets/js/html5shiv.js"></script>
		<script src="/slackck2015-11-27/Public/assets/js/respond.js"></script>
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

						<img src="/slackck2015-11-27/Public/assets/avatars/user.jpg" alt="Jason's Photo" />
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
																<img src="/slackck2015-11-27/Public/assets/avatars/avatar.png" class="msg-photo" alt="Alex's Avatar" />
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
																<img src="/slackck2015-11-27/Public/assets/avatars/avatar3.png" class="msg-photo" alt="Susan's Avatar" />
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
																<img src="/slackck2015-11-27/Public/assets/avatars/avatar4.png" class="msg-photo" alt="Bob's Avatar" />
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
																<img src="/slackck2015-11-27/Public/assets/avatars/avatar2.png" class="msg-photo" alt="Kate's Avatar" />
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
																<img src="/slackck2015-11-27/Public/assets/avatars/avatar5.png" class="msg-photo" alt="Fred's Avatar" />
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
								<img class="nav-user-photo" src="/slackck2015-11-27/Public/assets/avatars/user.jpg" alt="Jason's Photo" />
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
<script src="/slackck2015-11-27/Public/assets/js/jquery.min.js"></script>
<script src="/slackck2015-11-27/Public/assets/js/jquery.form.js"></script>
<script src="/slackck2015-11-27/Public/layer/layer.js"></script>

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

                        
                        
                        <!--主题-->
						<div class="page-header">
							<h1>
								您当前操作
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									添加文章
								</small>
							</h1>
						</div>


						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" name="form0" id="form0" method="post" action="<?php echo U('news_runedit');?>"  enctype="multipart/form-data">
								<input type="hidden" name="n_id" id="n_id" value="<?php echo ($news_list["n_id"]); ?>" />
														
                                	<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章所属主栏目： </label>
										<div class="col-sm-10">
                                        <select name="news_columnid"  class="col-sm-3 " >
                                        <option value="">请选择所属栏目</option>
                                        <?php if(is_array($column)): foreach($column as $key=>$vo): ?><option value="<?php echo ($vo["c_id"]); ?>"  <?php if($vo["c_id"] == $news_list['news_columnid']): ?>selected<?php endif; ?> <?php if($vo["column_type"] == 1): ?>disabled class="bgccc"<?php else: ?>class="bgc"<?php endif; ?>><?php echo ($vo["lefthtml"]); echo ($vo["column_name"]); ?> <?php if($vo["column_type"] == 1): ?>(频道页)<?php endif; ?></option><?php endforeach; endif; ?>
                                        </select>
                                        </div>
									</div>
									<div class="space-4"></div>
                                    
                                	<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章名称：  </label>
										<div class="col-sm-10">
											<input type="text" name="news_title" id="news_title"  value="<?php echo ($news_list["news_title"]); ?>"   class="col-xs-10 col-sm-6"/>
											 <input type="text" name="news_titleshort" id="news_titleshort"  value="<?php echo ($news_list["news_titleshort"]); ?>" placeholder="简短标题，建议6~12字数" class="col-xs-10 col-sm-4" style="margin-left:10px;" />
                                            <span class="help-inline col-xs-12 col-sm-7">
												<span class="middle" id="resone"></span>
											</span>
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章属性：  </label>
												<div class="checkbox">
                                                <?php if(is_array($diyflag)): foreach($diyflag as $key=>$diyflag): ?><label id="news_flag_<?php echo ($diyflag["diyflag_value"]); ?>">
														<input <?php if(strstr($news_list['news_flag'],$diyflag['diyflag_value']) == true): ?>checked<?php endif; ?> class="ace ace-checkbox-2" name="news_flag[]" type="checkbox" id="news_flag_va<?php echo ($diyflag["diyflag_value"]); ?>" value="<?php echo ($diyflag["diyflag_value"]); ?>" />
														<span class="lbl"> <?php echo ($diyflag["diyflag_name"]); ?></span>
													</label><?php endforeach; endif; ?>
												</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="form-group" id="pptaddress">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 跳转地址：  </label>
										<div class="col-sm-10">
											<input type="text" name="news_zaddress" id="news_zaddress"  value="<?php echo ($news_list["news_zaddress"]); ?>"   placeholder="输入页面跳转地址" class="col-xs-10 col-sm-7" />
                                            <span class="help-inline col-xs-12 col-sm-5">
												<span class="middle">正确格式：http:// 开头</span>
											</span>
										</div>
									</div>
                                    <div class="space-4"></div>
																		
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章关键字：  </label>
										<div class="col-sm-10">
											<input type="text" name="news_key" id="news_key"  value="<?php echo ($news_list["news_key"]); ?>"  placeholder="输入文章关键字，以英文,逗号隔开" class="col-xs-10 col-sm-6" />
										</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章来源：  </label>
										<div class="col-sm-10">
											<input type="text" name="news_source" id="news_source"  value="<?php echo ($news_list["news_source"]); ?>" class="col-xs-10 col-sm-2" />
											<label class="input_last">
											常用：<button class="btn btn-minier btn-yellow" id="source_1" value="龙岩网">龙岩网</button>
												 <button class="btn btn-minier btn-yellow" id="source_2" value="海峡导报">海峡导报</button>
												 <button class="btn btn-minier btn-yellow" id="source_3" value="闽西新闻网">闽西新闻网</button>
												 <button class="btn btn-minier btn-yellow" id="source_4" value="海峡都市报">海峡都市报</button>
												 <button class="btn btn-minier btn-yellow" id="source_5" value="凤凰房产">凤凰房产</button>
												 <button class="btn btn-minier btn-yellow" id="source_6" value="新华网">新华网</button>
												 <button class="btn btn-minier btn-yellow" id="source_7" value="人民网">人民网</button>
												 <button class="btn btn-minier btn-yellow" id="source_8" value="南方都市报">南方都市报</button>
												 <button class="btn btn-minier btn-yellow" id="source_9" value="东南网">东南网</button>
												 <button class="btn btn-minier btn-yellow" id="source_10" value="腾讯网">腾讯网</button>
											</label>
										</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 封面图片上传： </label>
										<div class="col-sm-10">
											<input type="hidden" name="checkpic" id="checkpic" value="/slackck2015-11-27/Public<?php echo ($news_list["news_img"]); ?>" />
											<input type="hidden" name="oldcheckpic" id="oldcheckpic" value="/slackck2015-11-27/Public<?php echo ($news_list["news_img"]); ?>" />
										<a href="javascript:;" class="file" title="点击选择所要上传的图片">
                                            <input type="file" name="file0" id="file0" multiple="multiple"/>
											选择上传文件
										</a>
											<span class="lbl">&nbsp;&nbsp;<img src="<?php if($news_list[news_img] != ''): ?>/slackck2015-11-27/Public<?php echo ($news_list["news_img"]); else: ?>/slackck2015-11-27/Public/img/no_img.jpg<?php endif; ?>" width="100" height="70" id="img0" ></span>&nbsp;&nbsp;<a href="javascript:;" onclick="return backpic('/slackck2015-11-27/Public<?php if($news_list["news_img"] == ''): ?>/img/no_img.jpg<?php else: echo ($news_list["news_img"]); endif; ?>');" title="还原修改前的图片" class="file">
                                            撤销修改
										</a>
											<span class="lbl">&nbsp;&nbsp;上传前先用PS处理成等比例图片后上传，默认比例100*70、600*420、800*560像素<br />
</span>
										</div>
									</div>
                                    <div class="space-4"></div>
									
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 是否审核： </label>
										<div class="col-sm-10" style="padding-top:5px;">
                                            <input name="news_open" id="news_open" <?php if($news_list[news_open] == 1): ?>checked<?php endif; ?>  value="1" class="ace ace-switch ace-switch-4 btn-flat" type="checkbox" />
											<span class="lbl">&nbsp;&nbsp;默认关闭</span>
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章简介： </label>
										<div class="col-sm-9">
											<input type="text" name="news_scontent" id="news_scontent" class="col-xs-10 col-sm-10"  maxlength="50" value="<?php echo ($news_list["news_scontent"]); ?>"  placeholder="输入文章简介"  />
											<label class="input_last">已限制在50个字以内</label>
										</div>
									</div>
                                    <div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 文章主内容 </label>
										<div class="col-sm-10">
											<script src="/slackck2015-11-27/Public/ueditor/ueditor.config.js" type="text/javascript"></script>
                                            <script src="/slackck2015-11-27/Public/ueditor/ueditor.all.js" type="text/javascript"></script>
											<textarea name="news_content" rows="100%" style="width:100%" id="myEditor"><?php echo ($news_list["news_content"]); ?></textarea>  
											<script type="text/javascript"> 
                                                var editor = new UE.ui.Editor(); 
                                                editor.render("myEditor"); 
                                            </script>
										</div>
									</div>
                                    <div class="space-4"></div>
                                    
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												保存
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												重置
											</button>
										</div>
									</div>
								</form>
                        	</div>
                        </div>
									<div class="hr hr-24"></div>

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
			if('ontouchstart' in document.documentElement) document.write("<script src='/slackck2015-11-27/Public/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="/slackck2015-11-27/Public/assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="/slackck2015-11-27/Public/assets/js/maxlength.js"></script>
		<script src="/slackck2015-11-27/Public/assets/js/ace/ace.js"></script>
		<script src="/slackck2015-11-27/Public/assets/js/ace/ace.sidebar.js"></script>
		<script src="/slackck2015-11-27/Public/assets/js/ace/ace.submenu-hover.js"></script>


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
		<script src="/slackck2015-11-27/Public/assets/js/jquery.form.js"></script>


		</div><!-- /.main-container -->


	</body>
</html>
<script>
function backpic(picurl){
	$("#img0").attr("src",picurl);//还原修改前的图片
	$("input[name='file0']").val("");//清空文本框的值
	$("input[name='oldcheckpic']").val(picurl);//清空文本框的值
}

$(document).ready(function(){
  $("#pptaddress").hide();
  $("#news_flag_vaj").click(function(){
    $("#pptaddress").toggle(500);
  });
  
  $("#source_1").click(function(){
	var source_1=$('#source_1').val();
	$("#news_source").val(source_1);
	return false;
  });
  
  $("#source_2").click(function(){
	var source_2=$('#source_2').val();
	$("#news_source").val(source_2);
	return false;
  });
    
  $("#source_3").click(function(){
	var source_3=$('#source_3').val();
	$("#news_source").val(source_3);
	return false;
  });
  
  $("#source_4").click(function(){
	var source_4=$('#source_4').val();
	$("#news_source").val(source_4);
	return false;
  });
  
  $("#source_5").click(function(){
	var source_5=$('#source_5').val();
	$("#news_source").val(source_5);
	return false;
  });
  
  $("#source_6").click(function(){
	var source_6=$('#source_6').val();
	$("#news_source").val(source_6);
	return false;
  });
  
  $("#source_7").click(function(){
	var source_7=$('#source_7').val();
	$("#news_source").val(source_7);
	return false;
  });
  
  $("#source_8").click(function(){
	var source_8=$('#source_8').val();
	$("#news_source").val(source_8);
	return false;
  });
  
  $("#source_9").click(function(){
	var source_9=$('#source_9').val();
	$("#news_source").val(source_9);
	return false;
  });
  
  $("#source_10").click(function(){
	var source_10=$('#source_10').val();
	$("#news_source").val(source_10);
	return false;
  });
});

$(function(){
	$('#address').hide();//默认隐藏
	$('#type2').click(function(){
		$('#address').show();
	});	
	$('#type1,#type3,#type4,#type5').click(function(){
		$('#address').hide();
	});	
});

$(function(){
	$('#form0').ajaxForm({
		beforeSubmit: checkForm, // 此方法主要是提交前执行的方法，根据需要设置
		success: complete, // 这是提交后的方法
		dataType: 'json'
	});
	
	function checkForm(){
		if( '' == $.trim($('#news_title').val())){
			layer.alert('文章标题不能为空', {icon: 6}, function(index){
 			layer.close(index);
			$('#news_title').focus(); 
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
			alert(data.info);
			return false;	
		}
	}
 
});
</script>
<script>	
$("#file0").change(function(){
	var objUrl = getObjectURL(this.files[0]) ;
	console.log("objUrl = "+objUrl) ;
	if (objUrl) {
		$("#img0").attr("src", objUrl) ;
	}
}) ;
//建立一個可存取到該file的url
function getObjectURL(file) {
	var url = null ; 
	if (window.createObjectURL!=undefined) { // basic
	$("#news_flag_vap").attr("checked", true);
	$("#oldcheckpic").val("nopic");
		url = window.createObjectURL(file) ;
	} else if (window.URL!=undefined) { // mozilla(firefox)
	$("#news_flag_vap").attr("checked", true);
	$("#oldcheckpic").val("nopic");
		url = window.URL.createObjectURL(file) ;
	} else if (window.webkitURL!=undefined) { // webkit or chrome
		$("#news_flag_vap").attr("checked", true);
		$("#oldcheckpic").val("nopic");
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url ;
}
</script>