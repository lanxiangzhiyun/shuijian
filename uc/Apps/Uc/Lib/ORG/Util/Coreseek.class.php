<?php
/**
* coreseek 查找类
*
* @author:zzy
* @created:2013-03-28
*/

class Coreseek{

	private $object;  //对象变量
	private $sphix_host;    //服务器IP
	private $conf;    //选择sphinx配置中对应要操作的项目
	private $keyword; //搜索关键字
	private $limit;   //每页显示条数
	private $startRow;//起始条数
	private $page;    //当前页数
	/**
	* 初始化
	*
	* @param array(
	*		'host'=>服务器IP
	*		'conf'=>config中的配置
	*       'keyword'=>搜索关键字
	*	)
	*/
	public function __construct($param){
		Vendor('coreseek.sphinxapi');
		$this->object = new SphinxClient ();
		$this->sphix_host = C('SPHINX_HOST');
		$sphinxConf = C('SPHINX_CONF');
		$this->conf = $sphinxConf[$param['conf']];
		$this->keyword = $param['keyword'];
		$this->limit = empty($param['limit'])?10:$param['limit'];
		$this->page  = empty($param['page'])? 1 : $param['page'];
		$this->startRow = ($this->limit * ($this->page - 1));
		//设置排序模式
		//$cl->SetSortMode(SPH_SORT_RELEVANCE);
		//设置属性过滤，用来过滤是帖子还是文章
		//$cl->SetFilter('type',array(1),true);

		//查询，第一个参数为要查询的内容，第二个参数为索引名
	}

	/**
	* sphinx链接
	*/
	private function connect(){
		//链接
		$this->object->SetServer($this->sphix_host, C('SPHINX_PORT'));
		//设置超时时间
		$this->object->SetConnectTimeout(10);
		//true以数组的格式返回
		$this->object->SetArrayResult(true);
		//设置匹配模式
		$this->object->SetMatchMode(SPH_MATCH_ALL);
		//设置结果集偏移量(分页)
		$this->object->SetLimits($this->startRow,$this->limit);
	}

	public function select(){
		$this->connect();
		$result = $this->object->Query($this->keyword,$this->conf);
		$userList = array();
		if($this->conf=='user'){
			foreach($result['matches'] as $key=>$val){
				$userList['uid'][] = $val['id'];
			}
			$userList['total'] = $result['total'];
		}
		return $userList;
	}


}
?>