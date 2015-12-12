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
	class JobModel extends Model{
		/**
		 * 获得城市列表信息
		 * @param array
		 */
		public function getJobList($param){
			//分页参数
        	$page 		= isset($param['page']) ? $param['page'] : 1;
        	$pageNum 	= isset($param['pageNum']) ? $param['pageNum'] : 10;

        	$where = "status = 0";
        	//类型
	       	if ($param['type']) {
	            $where .=  " and type_id =".$param['type'];
	        }
	        //城市
	       	if ($param['city']) {
	            $where .=  " and city_id =".$param['city'];
	        }
	        //状态
	       	if ($param['is_show'] == 1) {
	            $where .=  " and is_show = 0";
	        }elseif ($param['is_show'] == 2) {
	        	$where .=  " and is_show = -1";
	        }
	        //是否推荐
	       	if ($param['is_recommend'] == 1) {
	            $where .=  " and is_recommend = 1";
	        }elseif ($param['is_recommend'] == 2) {
	        	$where .=  " and is_recommend = 0";
	        }
	        //是否常招
	       	if ($param['is_resident'] == 1) {
	            $where .=  " and is_resident = 1";
	        }elseif ($param['is_resident'] == 2) {
	        	$where .=  " and is_resident = 0";
	        }
	        //开始时间
	    	if (!empty($param['start_time'])) {
	        	$where .=  " and update_time >=".strtotime($param['start_time']);
	    	}
			//结束时间
	        if (!empty($param['end_time'])) {
	            $where .=  " and update_time <=".(strtotime($param['end_time'])+3590*24);
	        }
	        //专题名称
	        if (!empty($param['name']) && $param['name'] !== '输入职位关键字') {
	            $where .=  " and job_name like '%{$param['name']}%'";
	        }
	        if(empty($param['field'])){
	        	$param['field'] = 'id';
	        }

	        //数据总条数
			$this ->total = M()->Table('zp_job') -> where ($where) ->field('id') -> count();
			//总页数
	    	$this->pagecount = ceil(($this->total)/$pageNum);
	      	if( $page  >= $this->pagecount){
				$page = $this->pagecount;
			}
			//条件a：发布时间 1:升序(时间越近越靠前) 2:降序(时间越远越靠前)
			//条件b：招聘人数 1:升序(人数越多越靠前) 2:降序(人数越少越靠前)
			if($param['a'] == 1){
				$result = M()->Table('zp_job')->field($param['field'])->where($where)->page($page)->limit($pageNum)->order('update_time desc')->select();
			}elseif($param['a'] == 2){
				$result = M()->Table('zp_job')->field($param['field'])->where($where)->page($page)->limit($pageNum)->order('update_time')->select();
			}elseif($param['b'] == 1){
				$result = M()->Table('zp_job')->field($param['field'])->where($where)->page($page)->limit($pageNum)->order('invite_number desc')->select();
			}elseif($param['b'] == 2){
				$result = M()->Table('zp_job')->field($param['field'])->where($where)->page($page)->limit($pageNum)->order('invite_number')->select();
			}else{
				$result = M()->Table('zp_job')->field($param['field'])->where($where)->page($page)->limit($pageNum)->order('update_time desc')->select();
			}
			
        	//当前条数	
			$this->subtotal = count($result);
        	foreach ($result as $key => $val) {
        		if (empty($val['update_time'])) {
             		$result[$key]['update_time'] = '';
	          	} else {
	          	    $result[$key]['update_time'] = date('Y-m-d H:i:s',$val['update_time']);
	          	}
		      	//如果人数为10000则显示为若干
				if($val['invite_number'] == 10000){
					$result[$key]['invite_number'] 	= '若干';
				}else{
					$result[$key]['invite_number'] 	= $val['invite_number'];
				}
        		//获得工作城市名
        		$result[$key]['city_name'] = M() -> Table('zp_city') -> where(array('id'=>$val['city_id'],'status'=>0)) -> getfield('city_name');
        		//获得工作类型名
        		$result[$key]['type_name'] = M() -> Table('zp_job_type') -> where(array('id'=>$val['type_id'],'status'=>0)) -> getfield('type_name');
        	}
        	//echo M()->getLastSql();echo '<pre>';print_r($result);
        	if($result){
        		return $result;
        	}
		}

		/**
		 * 获得编辑城市信息
		 * @param int id
		 */
		public function getJobInfo($param){
			$result = M() -> Table('zp_job') ->where('id='.$param) -> find();
			//如果人数为10000则显示为若干
			if($result['invite_number'] == 10000){
				$result['invite_number'] 	= '若干';
			}else{
				$result['invite_number'] 	= $result['invite_number'];
			}
			
			if($result){
				return $result;
			}
		}

		/**
		 * 获得编辑城市操作
		 * @param int id
		 */
		public function saveJobInfo($param){
			//echo '<pre>';print_r($param);exit;
			if(empty($param['city']) || empty($param['type']) || empty($param['job_name']) || empty($param['invite_number']) || empty($param['is_show']) || empty($param['profession']) || empty($param['require']) ){
				die('<script>alert("请完善各数据！");history.back();</script>');
			}
			//是否展示
			if($param['is_show'] == 1){
				$data['is_show'] = 0;
			}elseif($param['is_show'] == 2){
				$data['is_show'] = -1;
			}
			//是否推荐
			if ($param['is_recommend']) {
				$data['is_recommend'] = 1;
			}else{
				$data['is_recommend'] = 0;
			}
			//是否常招
			if ($param['is_resident']) {
				$data['is_resident'] = 1;
			}else{
				$data['is_resident'] = 0;
			}
			$data['city_id'] 		= $param['city'];
			$data['type_id'] 		= $param['type'];
			$data['job_name'] 		= $param['job_name'];
			//如果人数为若干则赋值为10000
			if(trim($param['invite_number']) == '若干'){
				$data['invite_number'] 	= 10000;
			}else{
				$data['invite_number'] 	= $param['invite_number'];
			}
			$data['profession']		= urldecode($param['profession']);
			$data['require']		= urldecode($param['require']);
			//
			//编辑
			if($param['id']){
				$data['update_time'] 	= time();
				$result = M() -> Table('zp_job') -> where('id='.$param['id']) -> save($data);
			}else{//添加
				$data['create_time'] 	= time();
				$data['update_time']	= time();
				$result = M() -> Table('zp_job') -> add($data);
			}
			//echo M()->getLastSql();echo '<pre>';print_r($result);exit;
			return $result;
		}

		/**
		 * 获得城市列表信息
		 * @param array
		 */
		public function getCityList($param){
			$where = 'status = 0';
			//分页参数
        	$page 		= isset($param['page']) ? $param['page'] : 1;
        	$pageNum 	= isset($param['pageNum']) ? $param['pageNum'] : 10;
        	
        	$this ->citytotal = M()->Table('zp_city') -> where ($where) ->field('id') -> count();
			//总页数
	    	$this->citypagecount = ceil(($this->citytotal)/$pageNum);
	      	if( $page  >= $this->citypagecount){
				$page = $this->citypagecount;
			}

			$result = M() -> Table('zp_city') -> where($where) -> page($page) -> limit($pageNum) -> order('create_time desc') -> select();
			$this->citysubtotal = count($result);
			foreach ($result as $key => $val) {
				if (empty($val['create_time'])) {
             		$result[$key]['create_time'] = '';
	          	} else {
	          	    $result[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
	          	}
			}
			//echo M()->getLastSql();echo '<pre>';print_r($result);
			if($result){
				return $result;
			}
		}

		/**
		 * 获得编辑城市信息
		 * @param int id
		 */
		public function getCityInfo($param){
			$result = M() -> Table('zp_city') ->where('id='.$param) -> find();
			//echo M()->getLastSql();echo '<pre>';print_r($result);
			if($result){
				return $result;
			}
		}

		/**
		 * 添加/编辑城市
		 * @param array 
		 * 			$id 
		 * 			$city_name 城市名
		 */
		public function saveCityInfo($param){
			
			$data['city_name'] = trim($param['city_name']);
			
			//编辑
			if($param['id']){
				$data['update_time'] 	= time();
				$result = M() -> Table('zp_city') -> where('id='.$param['id']) -> save($data);
			}else{//添加
				$data['create_time'] 	= time();
				$result = M() -> Table('zp_city') -> add($data);
			}
		
			return $result;
		}


		/**
		 * 获得类型列表信息
		 * @param array
		 * 			$pgae
		 *			$pageNum
		 */
		public function getTypeList($param){
			$where = 'status = 0';
			//分页参数
        	$page 		= isset($param['page']) ? $param['page'] : 1;
        	$pageNum 	= isset($param['pageNum']) ? $param['pageNum'] : 10;

        	$this ->typetotal = M()->Table('zp_job_type') -> where ($where) ->field('id') -> count();
			//总页数
	    	$this->typepagecount = ceil(($this->typetotal)/$pageNum);
	      	if( $page  >= $this->typepagecount){
				$page = $this->typepagecount;
			}
			$result = M() -> Table('zp_job_type') -> where($where) -> page($page) -> limit($pageNum) -> order('create_time desc') -> select();
			$this->typesubtotal = count($result);

			foreach ($result as $key => $val) {
				if (empty($val['create_time'])) {
             		$result[$key]['create_time'] = '';
	          	} else {
	          	    $result[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
	          	}
				$result[$key]['number']   = M() -> Table('zp_job') -> where(array('type_id'=>$val['id'],status=>0)) -> field('id') -> count();
			}
			//echo M()->getLastSql();echo '<pre>';print_r($result);exit;
			if($result){
				return $result;
			}
		}
		
		/**
		 * 获得编辑类型信息
		 * @param int id
		 */
		public function getTypeInfo($param){
			$result = M() -> Table('zp_job_type') -> where('id='.$param) -> find();
			//echo M()->getLastSql();echo '<pre>';print_r($result);
			if($result){
				return $result;
			}
		}

		/**
		 * 添加/编辑工作类型
		 * @param array 
		 * 			$id 
		 * 			$type_name 类型名
		 * 			$recommend 是否推荐
		 */
		public function saveTypeInfo($param){
			
			$data['type_name'] = trim($param['type_name']);
			if($param['recommend']){
				$data['recommend'] = 0;
			}else{
				$data['recommend'] = -1;
			}
			//echo '<pre>';print_r($param);exit;
			//编辑
			if($param['id']){
				$data['update_time'] 	= time();
				$result = M() -> Table('zp_job_type') -> where('id='.$param['id']) -> save($data);
			}else{//添加
				$data['create_time'] 	= time();
				$data['update_time'] 	= time();
				$result = M() -> Table('zp_job_type') -> add($data);
			}
		
			return $result;
		}

		/**
		* 删除城市
		*
		* $id 城市id
		* 
		*/
	    public function delCity ($id) {
	        if (is_array($id)) {
	           	$where =array('id' => array('in', $id));
	     		$res = array('city_id' => array('in', $id));
	        } else {
	           	$where = array('id' => array('in', "$id"));
	            $res = array('city_id' => array('in', "$id"));
	        }
	        //删除城市
       		$result = M()->Table('zp_city') -> where($where) -> save(array('status'=> -1));
       		//删除相关城市职位
       		$result = M()->Table('zp_job') -> where($res) -> save(array('status'=> -1));
       		if ($result !== FALSE) {
	            $data =  1;
	        } else {
	            $data = 0;
	        }
	        return $data;
	    }

	    /**
		* 删除职位类型
		*
		* $id 类型id
		* 
		*/
	    public function delType ($id) {
	        if (is_array($id)) {
	           	$where =array('id' => array('in', $id));
	     		$res = array('type_id' => array('in', $id));
	        } else {
	           	$where = array('id' => array('in', "$id"));
	          	$res = array('type_id' => array('in', "$id"));
	        }
	        //删除类型
       		$result = M()->Table('zp_job_type') -> where($where) -> save(array('status'=> -1));
       		//删除相关职位
       		$result = M()->Table('zp_job') -> where($res) -> save(array('status'=> -1));
       		if ($result !== FALSE) {
	            $data =  1;
	        } else {
	            $data = 0;
	        }
	        return $data;
	    }

	    /**
		 * 删除职位
		 *
		 * $id 职位id
		 * 
		 */
	    public function delJob ($id) {
	        if (is_array($id)) {
	           	$where =array('id' => array('in', $id));
	     
	        } else {
	           	$where = array('id' => array('in', "$id"));
	          
	        }
	        //删除职位
       		$result = M()->Table('zp_job') -> where($where) -> save(array('status'=> -1));
       		if ($result !== FALSE) {
	            $data =  1;
	        } else {
	            $data = 0;
	        }
	        return $data;
	    }
	    /**
		 * 获得职位类型id和类型名
		 *
		 */
	    public function getTypeNumber(){
	    	$result = M() -> Table('zp_job_type') -> where('status = 0') -> field('id,type_name') -> select();
	    	//echo M()->getLastSql();echo '<pre>';print_r($result);
	    	return $result;
	    }
	     /**
		 * 获得城市id和类型名
		 *
		 */
	    public function getCityNumber(){
	    	$result = M() -> Table('zp_city') -> where('status = 0') -> field('id,city_name') -> select();
	    	return $result;
	    }

	}

?>