<?php
/**
 * 宠物种类分类 Model
 *
 * @author: yumie
 * @created: 13-9-26
 */
class BkPetCategoryModel extends Model {
	protected $trueTableName = 'bk_pet_category';

	/**
	 * 获取TOP分类
	 * @param string $fields
	 * @return mixed
	 */
	public function getAllTopList ($params,$fields = '') {
		//分页参数
		$page = $params['page'] ? $params['page'] : 1;
		$page_num = $params['page_num'] ? $params['page_num'] : 25; //$param['num'] 自定义 显示条数
		$page_start = ($page - 1) * $page_num;
		//本页条数
		if($params['level'] == 2){
			$this->count = $this->where(array('status' => 0, 'parent_id' => array('neq', 0)))->limit($page_start . ',' . $page_num)->count();
			//总条数
			$total = $this->where(array('status' => 0, 'parent_id' => array('neq', 0)))->count();
			//总页数
			$this->totalPage = ceil($total / $page_num);
			$arrAllTopList = $this->where(array('status' => 0, 'parent_id' => array('neq', 0)))
				->field($fields)->order('id asc ')->limit($page_start . ',' . $page_num)->select();
		}else{
			$this->count = $this->where(array('status' => 0, 'parent_id' => 0))->limit($page_start . ',' . $page_num)->count();
			//总条数
			$total = $this->where(array('status' => 0, 'parent_id' => 0))->count();
			//总页数
			$this->totalPage = ceil($total / $page_num);
			$arrAllTopList = $this->where(array('status' => 0, 'parent_id' => 0))
				->field($fields)->order('id asc ')->limit($page_start . ',' . $page_num)->select();
		}
		
			//echo $this->getLastSql();
		return $arrAllTopList;
	}

	/**
	 * 分类id上一级获取下一级信部分字段信息
	 * @param $parentId   父id
	 * @param string $fields   可传多个字段
	 * @return array
	 */
	public function getSubList ($parentId,$params,$fields = '') {
		//分页参数
		$page = $params['page'] ? $params['page'] : 1;
		$page_num = $params['page_num'] ? $params['page_num'] : 25; //$param['num'] 自定义 显示条数
		$page_start = ($page - 1) * $page_num;
		//本页条数
		$this->count = $this->where(array('parent_id' => $parentId, 'status' => array('egt', 0)))
			->limit($page_start . ',' . $page_num)->count();
		//总条数
		$total = $this->where(array('parent_id' => $parentId, 'status' => array('egt', 0)))->count();
		//总页数
		$this->totalPage = ceil($total / $page_num);
		$arrSubList = $this->where(array('parent_id' => $parentId, 'status' => array('egt', 0)))->field($fields)
			->order('id asc')->limit($page_start . ',' . $page_num)->select();
			//echo $this->getLastSql();
		return !empty($arrSubList) ? $arrSubList : array();
	}

	/**
	 * 分类id获取分类信息
	 * @param $categoryIds   分类id '1,2,3' 或 '4'
	 * @param string $fields   可传多个字段
	 * @return array
	 */
	public function getInfo ($categoryIds, $fields = '') {
		if (empty($categoryIds)) {
			return array();
		}
		$category = $this->
			where(array('status' => array('egt', 0), 'id' => array('in', "$categoryIds")))
			->field($fields)->order('parent_id asc')->select();
		return !empty($category) ? $category : array();
	}

	/**
	 * 分类id获取分类某个字段信息
	 * @param $categoryId 分类 id 分类id '1,2,3' 或 '4'
	 * @param $field  单个字段
	 * @param bool $true  defaul:false 获取单个字段信息  ,true :获取一维数组
	 * @return bool
	 */
	public function getFieldById ($categoryId, $field, $true = false) {
		if (empty($field)) {
			return false;
		}
		if ($true) {
			$arrField = $this->where(array('id' => array('in', $categoryId), 'status' => array('egt', 0)))->getField($field, true);
			return $arrField;
		} else {
			$strField = $this->where(array('id' => $categoryId, 'status' => array('egt', 0)))->getField($field);
			return $strField;
		}
	}

	//增加分类
	public function addCategory ($data) {
		$status = $this->add($data);
		return $status;
	}

	//删除分类
	public function deleteCategory ($id) {
		$status = $this->where(array('id' => $id))->setField('status', -1);
		return $status;
	}

	//编辑分类
	public function updateCategory ($params) {
		$status = $this->save($params);
		return $status;
	}

	/**
	 *  ajax 一级分类变化 获取二级分类
	 * @param $id  二级分类 的id
	 * @return string
	 */
	public function getAjaxSub($id){
		$strSubList = '<option value="0" selected>请选择</option>';
		//分类id 和 父id
		$fields = 'id,parent_id';
		$arrInfo = $this->getInfo($id, $fields);
		//获取二级分类
		$strSubList = $this->_getSubList($arrInfo);
		return $strSubList;
	}

	/**
	 * select框获取分类
	 * @param $id  分类id
	 * @return array
	 */
	public function  getBkCategory ($id) {
		$strSubList = '<option value="0" selected>请选择</option>';
		//分类id 和 父id
		$fields = 'id,parent_id';
		$arrInfo = $this->getInfo($id, $fields);
			if($arrInfo[0]['parent_id'] != 0){
				//获取二级分类
				$strSubList = $this->_getSubList($arrInfo);
			}


		//获取一级分类
		$strTopList = $this->_getTopList($arrInfo);
		$category['top'] = $strTopList;
		$category['sub'] = $strSubList;
		return $category;
	}

	/**
	 * 一级分类
	 * @param $arrInfo
	 * @return string
	 */
	private function _getTopList ($arrInfo) {
		$fields = 'id,name';
		//所有一级分类 id/name
		$arrTopList = $this->getAllTopList($fields);
		$options = '<option value="0" selected>请选择</option>';
		foreach ($arrTopList as $key => $val) {
			if($arrInfo[0]['id'] == $val['id']) {
				$options .= '<option value="' . $val['id'] . '" selected>' . $val['name'] . '</option>';
			} else {
				$options .= '<option value="' . $val['id'] . '" >' . $val['name'] . '</option>';
			}
		}
		return $options;
	}

	/**
	 * 二级分类
	 * @param $params
	 * @return string
	 */
	private function _getSubList ($params) {
		$fields = 'id,name';
		//所有一级分类 id/name
		$arrSubList = $this->getSubList ($params[0]['parent_id'], $fields);
		$options = '<option value="0" selected>请选择</option>';
		foreach ($arrSubList as $key => $val) {
			if($params[0]['id'] == $val['id']) {
				$options .= '<option value="' . $val['id'] . '" selected>' . $val['name'] . '</option>';
			} else {
				$options .= '<option value="' . $val['id'] . '" >' . $val['name'] . '</option>';
			}
		}
		return $options;
	}

}
?>
