<?php
	/**
	* 招聘控制器
	*
	+------------------------------------------------------------------------
	* @author: jasonjiang
	+------------------------------------------------------------------------
	* @date: 2014/09/12 Friday
	+------------------------------------------------------------------------
	*/
	class JobAction extends ExtendAction{
		/**
		 * 工作职位列表
		 *
		 */
		public function index(){
			//获得传值
			$data = $this->_get('data');
			//print_r($data);
			$job  = D('Job');
			//分页参数
         	$data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
          	$data['pageNum'] = 10;
			//url地址
          	$url='/iadmin.php/Job/index?';
          	//条件：职位类型
          	if ($data['type']) {
          		$url.='data[type]='.$data['type'].'&';
          		$this->assign('type',$data['type']);
          	}
          	//条件：城市名称
          	if ($data['city']) {
          		$url.='data[city]='.$data['city'].'&';
          		$this->assign('city',$data['city']);
          	}
          	//条件：状态
          	if ($data['is_show']) {
          		$url.='data[is_show]='.$data['is_show'].'&';
          		$this->assign('is_show',$data['is_show']);
          	}
          	//条件：推荐职位
          	if ($data['is_recommend']) {
          		$url.='data[is_recommend]='.$data['is_recommend'].'&';
          		$this->assign('is_recommend',$data['is_recommend']);
          	}
          	//条件：常招职位
          	if ($data['is_resident']) {
          		$url.='data[is_resident]='.$data['is_resident'].'&';
          		$this->assign('is_resident',$data['is_resident']);
          	}
          	//条件：开始时间
			if($data['start_time']){
			  	$url.='data[start_time]='.$data['start_time'].'&';
			  	$this->assign('start_time',$data['start_time']);
			}
			//条件：结束时间
			if($data['end_time']){
			  	$url.='data[end_time]='.$data['end_time'].'&';
			  	$this->assign('end_time',$data['end_time']);
			}
			//条件：职位名称
          	if($data['name'] && !in_array(trim($data['name']),'输入职位关键字')){
              	$url.='data[name]='.urlencode($data['name']).'&';
              	$this->assign('name',$data['name']);
			}
			//条件：发布时间 1:升序(时间越近越靠前) 2:降序(时间越远越靠前)
			$turl = '/iadmin.php/Job/index?data[type]='.$data['type'].'&data[city]='.$data['city'].'&data[is_show]='.$data['is_show'].'&data[is_recommend]='.$data['is_recommend'].'&data[is_resident]='.$data['is_resident'].'&data[start_time]='.$data['start_time'].'&data[end_time]='.$data['end_time'].'&data[name]='.$data['name'];
          	if ($data['a'] == 1) {
          		$url.='data[a]=1&';
          		$turl .= '&data[a]=2&page='.$data['page'];
          		$this->assign('turl',$turl);
          		$this->assign('a',$data['a']);
          	}elseif($data['a'] == 2){
          		$url.='data[a]=2&';
          		$turl .= '&data[a]=1&page='.$data['page'];
          		$this->assign('turl',$turl);
          		$this->assign('a',$data['a']);
          	}elseif(!$data['a']){
          		$turl .= '&data[a]=1&page='.$data['page'];
          		$this->assign('turl',$turl);
          		$this->assign('a',$data['a']);
          	}

          	//条件：招聘人数 1:升序(人数越多越靠前) 2:降序(人数越少越靠前)
          	$nurl = '/iadmin.php/Job/index?data[type]='.$data['type'].'&data[city]='.$data['city'].'&data[is_show]='.$data['is_show'].'&data[is_recommend]='.$data['is_recommend'].'&data[is_resident]='.$data['is_resident'].'&data[start_time]='.$data['start_time'].'&data[end_time]='.$data['end_time'].'&data[name]='.$data['name'];
          	if ($data['b'] == 1) {
          		$url.='data[b]=1&';
          		$nurl .= '&data[b]=2&page='.$data['page'];
          		$this->assign('nurl',$nurl);
          		$this->assign('b',$data['b']);
          	}elseif($data['b'] == 2){
          		$url.='data[b]=2&';
          		$nurl .= '&data[b]=1&page='.$data['page'];
          		$this->assign('nurl',$nurl);
          		$this->assign('b',$data['b']);
          	}elseif(!$data['b']){
          		$nurl .= '&data[b]=1&page='.$data['page'];
          		$this->assign('nurl',$nurl);
          		$this->assign('b',$data['b']);
          	}
          	
			
			$data['field'] = 'id,type_id,city_id,job_name,invite_number,is_show,status,update_time,is_recommend,is_resident';
			//echo '<pre>';print_r($data);
			
          	$url .= 'page=';
          	//工作列表信息
			$result = $job -> getJobList($data);
          	//如果超过页面数据则为最大
			if( $data['page']  >= $job->pagecount){
				$data['page'] = $job->pagecount;
			}
			$pageHtml = $this->page($url,$job->pagecount, $data['pageNum'],$data['page'],$job->subtotal);
			//类型
			$typeList = $job -> getTypeNumber();

			$this->assign('typeList',$typeList);
			//城市
			$cityList = $job -> getCityNumber();
			$this->assign('cityList',$cityList);
			
			
			$this->assign('jobList',$result);
			//页数分页
			if($job->pagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			$this->assign('page',$data['page']);
			$this->display('index');
		}

		/**
		 * 添加/编辑工作类型页面
		 *
		 */
		public function addJob(){
			$id = $this->_get('id');
			//title
			if($id){
				$result = D('Job') -> getJobInfo($id);
				$title  = '编辑职位';
			}else{
				$title  = '新增职位';
			}
			//类型
			$typeList = D('Job') -> getTypeNumber();
			$this->assign('typeList',$typeList);
			//城市
			$cityList = D('Job') -> getCityNumber();
			$this->assign('cityList',$cityList);

			$this->assign('title',$title);
			$this->assign('arrInfo',$result);
			$this->display('addJob');
		}

		/**
		 * 添加/编辑工作操作
		 *
		 */
		public function saveJob(){

			$data = $this->_post();
			//echo '<pre>';print_r(urldecode($data['require']));exit;
			$result = D('Job') -> saveJobInfo($data);
			
			if ($result) {
				echo "<script text='text/javascript'>alert('操作成功！');location.href='/iadmin.php/Job/index'</script>";
				
			}else {
			   echo "<script text='text/javascript'>alert('操作失败！');history.back();</script>";
			}

		}

		/**
		 * 工作类型列表
		 *
		 */
		public function type(){
			$job = D('Job');
			//url地址
          	$url='/iadmin.php/Job/type?';
          	//分页
          	$data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
          	$data['pageNum'] = 10;
          	$url .= 'page=';
          	//获得类型列表

			$result = $job -> getTypeList($data);
			//如果超过页面数据则为最大
			if( $data['page']  >= $job->typepagecount){
				$data['page'] = $job->typepagecount;
			}
			$pageHtml = $this->page($url,$job->typepagecount, $data['pageNum'],$data['page'],$job->typesubtotal);
			
			//echo '<pre>';print_r($result);
			$this->assign('typeList',$result);
			$this->assign('page',$data['page']);
			//页数分页
			if($job->typepagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			
			$this->display('type');
		}

		/**
		 * 添加/编辑工作类型页面
		 *
		 */
		public function addType(){
			$id = $this->_get('id');
			//title
			if($id){
				$result = D('Job') -> getTypeInfo($id);
				$title  = '编辑类型';
			}else{
				$title  = '新增类型';
			}
			$this->assign('title',$title);
			$this->assign('arrInfo',$result);
			$this->display('addType');
		}

		/**
		 * 添加/编辑工作类型操作
		 *
		 */
		public function saveType(){

			$data = $this->_get('data');
			if(empty($data['type_name'])){
				die('<script>alert("类型名称不能为空");history.back();</script>');
			}
			$result = D('Job') -> saveTypeInfo($data);

			if ($result) {
				echo "<script text='text/javascript'>alert('操作成功！');location.href='/iadmin.php/Job/type'</script>";
				
			}else {
			   echo "<script text='text/javascript'>alert('操作失败！');history.back();</script>";
			}

		}

		/**
		 * 更改类型推荐操作
		 *
		 */
		public function changeType(){
			$data = $this->_get();
			if($data['recom'] == 0){
				$param['recommend'] 	= -1;
			}else{
				$param['recommend'] = 0;
				$param['update_time'] 	= time();
			}

			$result = M() -> Table('zp_job_type') ->where('id='.$data['id']) -> save($param);
			//echo M()->getLastSql();echo '<pre>';print_r($result);exit;
			if($result){
				$this->redirect('/iadmin.php/Job/type?page='.$data['page']);
			}else{
				echo "<script text='text/javascript'>alert('操作失败！');history.back();</script>";
			}
		}


		/**
		 * 城市列表
		 *
		 */
		public function city(){
			$job = D('Job');
			//url地址
          	$url='/iadmin.php/Job/city?';
          	//分页参数
          	$data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
          	$data['pageNum'] = 10;
          	$url .= 'page=';

			$result = $job -> getCityList($data);
			//如果超过页面数据则为最大
			if( $data['page']  >= $job->citypagecount){
				$data['page'] = $job->citypagecount;
			}
			$pageHtml = $this->page($url,$job->citypagecount, $data['pageNum'],$data['page'],$job->citysubtotal);
			$this->assign('page',$data['page']);
			//页数分页
			if($job->citypagecount > 1){
				$this->assign('pageHtml',$pageHtml);
			}
			$this->assign('page',$data['page']);
			//echo '<pre>';print_r($result);
			$this->assign('cityList',$result);
			$this->display('city');
		}
		/**
		 * 添加/编辑城市页面
		 *
		 */
		public function addCity(){
			$id = $this->_get('id');
			if($id){
				$result = D('Job') -> getCityInfo($id);
				$title  = '编辑城市';
			}else{
				$title  = '新增城市';
			}
			$this->assign('title',$title);
			$this->assign('arrInfo',$result);
			$this->display('addCity');
		}

		/**
		 * 添加/编辑城市操作
		 *
		 */
		public function saveCity(){

			$data = $this->_get('data');
			if(empty($data['city_name'])){
				die('<script>alert("城市名称不能为空");history.back();</script>');
			}
			$result = D('Job') -> saveCityInfo($data);
			if ($result) {
				echo "<script text='text/javascript'>alert('操作成功！');location.href='/iadmin.php/Job/city'</script>";
				
			}else {
			   echo "<script text='text/javascript'>alert('操作失败！');history.back();</script>";
			}
		}
		/**
		* ajax删除城市
		*	
		*/
		public function ajaxDelCity(){
			$jobModel = D('Job');
	        $ids = $this->_get('ajaxDelCity');
	        $act = $this->_get('act');
	      	$page = $this->_get('page');
	        $idArr = array_filter(explode(',',$ids));

	        $result =$jobModel -> delCity ($idArr) ;
	        if(empty($act)){
	            echo "<script>location.href='/iadmin.php/Job/city';</script>";
	        }else{
	            echo 1;
	            exit;
	        }
		}
		/**
		* ajax删除职位类型
		*	
		*/
		public function ajaxDelType(){
			$jobModel = D('Job');
	        $ids = $this->_get('ajaxDelType');
	        $act = $this->_get('act');
	      	$page = $this->_get('page');
	        $idArr = array_filter(explode(',',$ids));

	        $result =$jobModel -> delType ($idArr) ;
	        if(empty($act)){
	            echo "<script>location.href='/iadmin.php/Job/type';</script>";
	        }else{
	            echo 1;
	            exit;
	        }
		}

		/**
		* ajax删除职位
		*	
		*/
		public function ajaxDelJob(){
			$jobModel = D('Job');
	        $ids = $this->_get('ajaxDelJob');
	        $act = $this->_get('act');
	      	$page = $this->_get('page');
	        $idArr = array_filter(explode(',',$ids));

	        $result =$jobModel -> delJob ($idArr) ;
	        if(empty($act)){
	            echo "<script>location.href='/iadmin.php/Job/index';</script>";
	        }else{
	            echo 1;
	            exit;
	        }
		}
		
	}

?>