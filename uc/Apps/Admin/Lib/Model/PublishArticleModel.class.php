<?php
/*
*栏目组 model
*/
class PublishArticleModel extends Model{

	protected $tableName='boqii_publish_article';
	
	/*
	*获得栏目
	*/
	public function getPublishArticle($page,$limit,$where){
		$result = $this->where($where)->order('position asc,id desc')->page($page)->limit($limit)->select();
		return $result;
	}

	/*
	*获取栏目个数
	*/
	public function getPublishArticleCount($where){
		$result = $this->where($where)->count();	
		return $result;
	}

	/*
	*获取推荐文章详细信息
	*/
	public function getPublishArticleInfo($id){
		$result = $this->where(array('id'=>$id))->find();
		return $result;
	}

	/*
	*推荐文章修改
	*/
	public function savePublishArticle($data){
		$result = $this->save($data);
		return $result;
	}

	/**
	*推荐文章添加
	*/
	public function addPublishArticle($data){
		$result = $this->add($data);
		return $result;
	}

    /**
     * 根据推荐文章获得栏目code
     *
     */
    public function getPublishCode($id){
        $publishArticle = $this->getPublishArticleInfo($id);
        $result = D('Publish')->where('id='.$publishArticle['publish_id'])->find();
        return $result['code'];
    }

    /****************************** APP配置 ********************************/

    /**
     * 获得banner列表
     * @param array
     *			$page int 当前页
     *			$pageNum int 每页数量
     */
    public function getAppDeployList($param){

       	// 分页参数
		$param['page'] 	 	= $param['page'] ? $param['page'] : 1;
		$param['pageNum'] 	= $param['pageNum'] ? $param['pageNum'] : 10;
		
		// 条件
		$where = 'status = 0 and publish_id = 50002';
		// 总数量
		$this->bantotal = $this->where($where)->count();
		// 总页数
		$this->banpagecount = ceil(($this->bantotal)/$param['pageNum']);
		// 输入页数比总页数大
		if ($this->banpagecount < $param['page']) {
			$param['page'] = $this->banpagecount;
		}
		$appDeployList = $this->field('id,publish_id,status,input1,title,url,position,create_time,img1,textarea1')->where($where)->order('id desc')->page($param['page'])->limit($param['pageNum'])->select();
		// 当前页数量
		$this->bansubtotal = count($appDeployList);
		
		foreach ($appDeployList as $k => $val) {
			$appDeployList[$k]['create_time'] = $val['create_time']?date('Y-m-d H:i:s',$val['create_time']):'';
			$appDeployList[$k]['img1'] 		  = $val['img1']?C('IMG_DIR').'/'.$val['img1']:'';
		}
		if (empty($appDeployList)) {
			return array();
		}
		return $appDeployList;
// echo "<pre>";print_r($appDeployList);exit();
    }

    /**
     * 根据推荐banner的id获得详情信息
     * @param $id int banner的id
     * return array
     */
    public function getBanDetail($id,$field){
       return $this->where('status = 0 and id = '.$id)->field($field)->find();
    }

    /**
     * 根据推荐banner的相应信息
     * @param $id int banner的id
     * return array
     */
    public function getBanInfo($id,$field){
       return $this->where('status = 0 and id = '.$id)->field($field)->select();
    }


    /**
     * 更新banner信息
     * @param array 
     *			$type 	int 类型 
     *			$title 	string H5标题
     *			$content string  内容
     *			$position int 排序
     *			$img1 string 图片地址
     *			$url string 链接地址
     *     $bid int banner的id
     * return resutl boolean 返回结果
     */
    public function saveBanData($param,$bid){

       $res = $this->where('id='.$bid)->save($param);
       return $res;
    }

     /**
     * 添加banner
     * @param array 
     *			$type 	int 类型 
     *			$title 	string H5标题
     *			$content string  内容
     *			$position int 排序
     *			$img1 string 图片地址
     *			$url string 链接地址
     * return resutl boolean 返回结果
     */
    public function addBanData($param){
       	// echo "<pre>";print_r($param);exit();
    	$param['create_time'] = time();
       	$res = $this->add($param);
       	return $res;
    }

    /**
     * 添加banner
     * @param id int banner的id
     * return resutl boolean 返回结果
     */
    public function delAppDeploy($id){
       	
       	$res = $this->where(array('id'=>$id))->setField('status',-1);
       	return $res;
    }
}
?>