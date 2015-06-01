<?php

class Application_Model_Order extends Zend_Db_Table_Abstract {

    protected $_name = 'order';

    function addOrder($order_data, $user_id) {

        $row = $this->createRow();
        $row->notes = $order_data['notes'];
        $row->room = $order_data['roomid'];
        $row->total_price = $order_data['totalprice'];
        $row->status = 'o';


        $row->u_id = $user_id;
        var_dump("room ID " + $order_data['roomid']);

        return $row->save();
    }
    
    function updateOrderStatus($id,$status)
    {
        if($status == "r")
            {
                $data = array('status'=>'r');
                $this->update($data, "id=$id");
            }
        
    }

    function listOrders() {
        return $this->fetchAll()->toArray();
    }

    function getOrderById($id) {
        $select = $this->select()->where("id=$id");
        return $this->fetchAll($select)->toArray();
    }

    function getUserOrders($id) {
        $select = $this->select()->where("u_id=$id");
        return $this->fetchAll($select)->toArray();
    }

    function deleteOrder($id) {
        $this->delete("id=$id");
    }

    function editOrder($id, $order_data) {
        $this->update($order_data, "id=$id");
    }

     function getAllOrders($status)
    {
          
         $select =  $this->select();       
         $select ->where("status=$status");
         $select->from(array('o' => 'order'),array('orderid'=>'o.id', 'date','room','notes','total_price'))
                    ->join(array('u' => 'user'),
                    'u.id = o.u_id',array('userid'=>'u.id', 'name','ext'))
                    ->order(array('date DESC'));
         $select->setIntegrityCheck(false);
        return $this->fetchAll($select)->toArray();
    }

    function viewMyOrders($id, $startdate, $enddate) {
        $result = $this->select()->from(array('o' => 'order'), array('id', 'date', 'total_price' => 'SUM(`total_price`)'))
                        ->join(array('od' => 'order_details'), 'o.id=od.order_id', array('product_id', 'amount'))
                        ->join(array('u' => 'user'), 'u.id=o.u_id', array('user_name'=> 'name','u_id'=>'id'))
                        ->join(array('prod' => 'product'), 'od.product_id=prod.id', array('name', 'picture'))
                        ->where('o.u_id=?', $id)->order('o.date')
                        ->where('o.date >= ?', $startdate)->where('o.date <= ?', $enddate)->group('user_name');

        $result->setIntegrityCheck(false);
        return $this->fetchAll($result)->toArray();
    }

    function checkOrders($startdate, $enddate) {

        $result = $this->select()->from(array('o' => 'order'), array('id', 'date'))
                        ->join(array('od' => 'order_details'), 'o.id=od.order_id', array('product_id', 'amount'))
                        ->join(array('prod' => 'product'), 'od.product_id=prod.id', array('prod_name' => 'name', 'picture'))
                        ->join(array('user' => 'user'), 'o.u_id=user.id', array('user_name' => 'name', 'user_id' => 'user.id'))->distinct()
                        ->where('o.date >= ?', $startdate)
                        ->where('o.date <= ?', $enddate)->order('o.date')->distinct();

        $result->setIntegrityCheck(false);
        return $this->fetchAll($result)->toArray();
    }

    //////////////////////

    function getNames() {


        $select = $this->select();
        $select->from(array('o' => 'order'), array('orderid' => 'o.id', 'date', 'u_id', 'total_price' => 'SUM(`total_price`)'))
                ->join(array('u' => 'user'), 'u.id = o.u_id', array('userid' => 'u.id', 'user_name' => 'name'))->group('user_name');
        $select->setIntegrityCheck(false);
        return $this->fetchAll($select)->toArray();
    }

    function getOrders($date1, $date2, $id) {


        $select = $this->select()
                ->from(array('o' => 'order'), //t1
                        array('date', 'u_id', 'total_price', 'id'))  //select cols from table
                ->join(array('r' => 'order_details'), //t2
                        'o.id = r.order_id')
                ->where('o.date >= ?', $date1)
                ->where('o.date <= ?', $date2)
                ->where('o.u_id = ?', $id);

        $select->setIntegrityCheck(false);
        echo $select;
        $row = $this->fetchAll($select)->toArray();
        return $row;
    }

    function getOrderDetails($order_date, $user_id) {
        $select = $this->select();
        $select->from(array('o' => 'order'), array("total_price"))
                ->join(array('po' => 'order_details'), 'o.id=po.order_id', array("amount"))
                ->join(array('p' => 'product'), 'p.id = po.product_id', array("prod_name" => "name", "picture"))
                ->where("date= ?", $order_date)
                ->where("u_id= ?", $user_id)->setIntegrityCheck(false);

        return $this->fetchAll($select)->toArray();
    }
    
    
    function getdetails($order_id) {
        $select = $this->select();
        $select->from(array('o' => 'order'), array("total_price",'id'))
                ->join(array('po' => 'order_details'), 'o.id=po.order_id', array("amount"))
                ->join(array('p' => 'product'), 'p.id = po.product_id', array("prod_name" => "name", "picture"))
                ->where("o.id= ?", $order_id)
                ->setIntegrityCheck(false);

        return $this->fetchAll($select)->toArray();
    }

}
