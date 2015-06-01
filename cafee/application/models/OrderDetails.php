<?php

class Application_Model_OrderDetails extends Zend_Db_Table_Abstract {

    protected $_name = 'order_details';

    function addOrderDetails($order_id, $product_id, $amount) {
        $row = $this->createRow();
        $row->order_id = $order_id;
        $row->product_id = $product_id;
        $row->amount = $amount;
        return $row->save();
    }

    function listOrderDetails() {
        return $this->fetchAll()->toArray();
    }

    function getOrderDetailsById($id) {
        $select = $this->select();
        // $select->where("order_id=$id");
        $select->from(array('d' => 'order_details'), array('orderid' => 'd.order_id', 'amount'))
                ->join(array('p' => 'product'), 'p.id = d.product_id', array('productid' => 'p.id', 'name', 'picture', 'price'))
                ->where("order_id=$id");
        $select->setIntegrityCheck(false);
        return $this->fetchAll($select)->toArray();
    }

    function getOrderDetails($id) {
        $select = $this->select()->where("order_id=$id");
        return $this->fetchAll($select)->toArray();
    }

    function deleteOrderDetails($id) {
        $this->delete("order_id=$id");
    }

    function editOrderDetails($id, $order_details) {
        $this->update($order_details, "order_id=$id");
    }

}
