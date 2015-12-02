<?php
/**
 *  品牌活动
 * @author fanghui
 * @date 2015/12/01
 *
 */
class BrandAction extends Action {
    /**
     * 首页
     *
     */
    public function index(){

        $this->display('Brand');
    }

    public function inside(){

        $this->display('BrandDetail');
    }
}
