<?php
/**
 *  订单列表
 * @author fanghui
 * @date 2015/12/01
 *
 */
class CustomerAction extends Action {

    public function CustomerOrder(){

        $this->display('CustomerOrder');
    }

    public function CustomerAddress(){

        $this->display('CustomerAddress');
    }

    public function CustomerVirtual(){

        $this->display('CustomerVirtual');
    }

    public function CustomerInfo(){

        $this->display('CustomerInfo');
    }
}
