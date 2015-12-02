<?php
/**
 *  品牌理念
 * @author fanghui
 * @date 2015/12/01
 *
 */
class ServiceAction extends Action {
    /**
     * 首页
     *
     */
    public function index(){

        $this->display('ServiceIndex');
    }

    public function product(){

        $this->display('ServiceProduct');
    }

    public function hpp(){

        $this->display('HPP');
    }
}
