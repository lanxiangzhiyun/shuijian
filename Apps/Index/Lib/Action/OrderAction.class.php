<?php
/**
 *  订单收货信息
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

        $this->display('Order');
    }

    public function OrderInfo($order_code){

        $this->display('OrderInfo');
    }

    public function OrderWepay($order_code){

        $this->display('OrderWepay');
    }
}
