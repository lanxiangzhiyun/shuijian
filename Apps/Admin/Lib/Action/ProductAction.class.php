<?php
/*
*产品管理控制器
*/
class ProductAction extends ExtendAction{

    /*
    *品类管理
    */
    public function category(){
        $arrAssign = array();
        $GoodsCategoryModel = D('GoodsCategory');
        $data = $this -> _get('data');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;

        // 字段
        $data['fields'] = '*';

        // 当前url地址
        $url='/iadmin.php/Product/category?';
        // 查询条件
        $url.="page=";

        // 获取列表
        $arrList = $GoodsCategoryModel -> getList($data);
        $arrAssign['arrList'] = $arrList;

        // 获取分页信息
        $pageHtml = $this->page($url,$GoodsCategoryModel->pagecount, $data['pageNum'],$data['page'],$GoodsCategoryModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this->display('GoodsCategory_list');
    }

    public function category_edit(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $GoodsCategoryModel = D('GoodsCategory');
        $result = $GoodsCategoryModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success','data'=>$result));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
     * 删除
     */
    public function category_delete () {
        $GoodsCategoryModel = D('GoodsCategory');
        $ids = $this->_post('id');

        $idArr = array_filter(explode(',',$ids));
        $result =$GoodsCategoryModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /*
    *分类管理
    */
    public function type(){
        $arrAssign = array();
        $GoodsTypeModel = D('GoodsType');
        $data = $this -> _get('data');
        $keyword = $this -> _get('keyword');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;
        $data['keyword'] = $keyword;

        // 字段
        $data['fields'] = '*';

        // 当前url地址
        $url='/iadmin.php/Product/type?';
        // 查询条件
        $url.="page=";

        // 获取列表
        $arrList = $GoodsTypeModel -> getList($data);
        $arrAssign['arrList'] = $arrList;

        // 获取分页信息
        $pageHtml = $this->page($url,$GoodsTypeModel->pagecount, $data['pageNum'],$data['page'],$GoodsTypeModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this->display('GoodsType_list');
    }

    public function type_edit(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $GoodsTypeModel = D('GoodsType');
        $result = $GoodsTypeModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success','data'=>$result));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
     * 删除
     */
    public function type_delete () {
        $GoodsTypeModel = D('GoodsType');
        $ids = $this->_post('id');

        $idArr = array_filter(explode(',',$ids));
        $result =$GoodsTypeModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /*
    *基础产品管理
    */
    public function base(){
        $arrAssign = array();
        $baseproducgtModel = D('BaseProduct');
        $data = $this -> _get('data');
        $keyword = $this -> _get('keyword');
        $arrAssign['data'] = $data;
        //分页参数
        $data['page']= isset($_GET['page']) ? $_GET['page'] : 1;
        $data['pageNum'] = 10;
        $data['keyword'] = $keyword;

        // 字段
        $data['fields'] = '*';

        // 当前url地址
        $url='/iadmin.php/Product/base?';
        // 查询条件
        $url.="page=";

        // 获取列表
        $arrList = $baseproducgtModel -> getList($data);
        $arrAssign['arrList'] = $arrList;

        // 获取分页信息
        $pageHtml = $this->page($url,$baseproducgtModel->pagecount, $data['pageNum'],$data['page'],$baseproducgtModel->subtotal);
        $this->assign('pageHtml',$pageHtml);

        foreach ($arrAssign as $key => $val) {
            $this -> assign($key,$val);
        }

        $this->display('BaseProduct_list');
    }

    public function base_edit(){
        // URL参数
        $data = $this -> _post('data');
        // 新增
        $GoodsTypeModel = D('GoodsType');
        $result = $GoodsTypeModel -> addList($data);
        if ($result) {
            $this->ajaxReturn(array('title'=>'success','data'=>$result));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
     * 删除
     */
    public function base_delete () {
        $GoodsTypeModel = D('GoodsType');
        $ids = $this->_post('id');

        $idArr = array_filter(explode(',',$ids));
        $result =$GoodsTypeModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }

    /**
     * 删除
     */
    public function ajaxDelList () {
        $deliverModel = D('Deliver');
        // 待删除友链id字符串（英文逗号串接）
        $ids = $this->_post('id');

        // 分割友链id字符串
        $idArr = array_filter(explode(',',$ids));
        // 删除友链
        $result =$deliverModel -> delList($idArr) ;
        if ($result) {
            $this->ajaxReturn(array('title'=>'success'));
        }else {
            echo "<script>alert('操作失败!');history.back();</script>";
        }
    }


    public function category_title_check(){

        $value = $this -> _post('value');
        $GoodsCategoryModel = D('GoodsCategory');
        $result = $GoodsCategoryModel -> category_title_check($value);
        $this->ajaxReturn($result);
    }

    public function title_unique_check(){

        $value = $this -> _post('value');
        $GoodsTypeModel = D('GoodsType');
        $result = $GoodsTypeModel -> title_unique_check($value);
        $this->ajaxReturn($result);
    }

    public function code_unique_check(){

        $value = $this -> _post('value');
        $GoodsTypeModel = D('GoodsType');
        $result = $GoodsTypeModel -> code_unique_check($value);
        $this->ajaxReturn($result);
    }
}
