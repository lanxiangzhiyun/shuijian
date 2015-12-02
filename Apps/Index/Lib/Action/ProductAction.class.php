<?php
/**
 *  产品
 * @author fanghui
 * @date 2015/12/01
 *
 */
class ProductAction extends Action {
    /**
     * 首页
     *
     */

    public function index(){

        $this->display('Product');
    }

    public function inside($sid){

        $this->display('ProductDetail');
    }
}
