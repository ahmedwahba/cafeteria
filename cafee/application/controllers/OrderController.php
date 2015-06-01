<?php

class OrderController extends Zend_Controller_Action {

    private $user_id;
    private $user_name;
    private $user_image;

    public function init() {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->user_id = $auth->getIdentity()->id;
            $this->user_name = $auth->getIdentity()->name;
            $this->user_image = $auth->getIdentity()->image;
        }
        

        if (!($auth->hasIdentity())) {
            $this->redirect("Auth/login");
        }
    }

    public function indexAction() {
        // action body
                    $this->redirect("auth/login");
    }
    

    public function adminhomeAction()
    {
        $toprepare = new Application_Model_Order();
        $details = new Application_Model_OrderDetails();
       $alltoprepare = $toprepare->getAllOrders('"o"');
        $this->view->orders = $alltoprepare;
        $this->test1 = $alltoprepare;
        for($i =0; $i < count($alltoprepare);$i++)
        {
            $id = $alltoprepare[$i]["orderid"];
            $detail[$id] = $details->getOrderDetailsById($id);
            
        }
        $this->view->detail = $detail;
        $this->test2 = $detail;
    }
    
    public function pushordersAction()
    {
         
         header('Content-Type: text/event-stream');
         header('Cache-Control: no-cache');
       
         $toprepare = new Application_Model_Order();
        $details = new Application_Model_OrderDetails();
       $alltoprepare = $toprepare->getAllOrders('"o"');
     
        for($i =0; $i < count($alltoprepare);$i++)
        {
            $id = $alltoprepare[$i]["orderid"];
            $detail[$id] = $details->getOrderDetailsById($id);
            
        }
       
         $this->view->orders = json_encode($alltoprepare);
         $this->view->detail = json_encode($detail);
         
          flush();
    
   
    }

    public function  updateallordersAction()
    {
        $status = $_GET['status'];
        $id = $_GET['id'];
        $order = new Application_Model_Order();
        $order->updateOrderStatus($id, $status);
        
    }

    public function confirmadminorderAction() {
        // GET DATAA from ajax , json object by decode ittt then access
        $myOrder = $_GET['myorder'];
        $myorder = json_decode($myOrder);
        $totalprice = $myorder->totalprice;
        $roomid = $myorder->roomid;
        $userid = $myorder->userid;
        $notes = $myorder->notes;


        // make array of prev data ,, add to order table
        $insertedOrder = array('totalprice' => $totalprice, 'roomid' => $roomid, 'notes' => $notes);
        $order_model = new Application_Model_Order();
        $orderdetails_model = new Application_Model_OrderDetails();


        $order_model->addOrder($insertedOrder, $userid);

        // to add into order_details table we need last order id 
        $order_id = $order_model->getAdapter()->lastInsertId();
        for ($i = 0; $i < count($myorder->products); $i++) {
            $prod_id = $myorder->products[$i]->prod_id;
            $prod_amount = $myorder->products[$i]->prod_amount;
            $confirmadminorder = $orderdetails_model->addOrderDetails($order_id, $prod_id, $prod_amount);
        }

        if ($confirmadminorder) {
            echo'ok success';


            $this->redirect("Order/addadminorder");
        } else {
            echo'failed';
        }
    }

    public function confirmuserorderAction() {

        $myOrder = $_GET['myorder'];
        $myorder = json_decode($myOrder);
        $totalprice = $myorder->totalprice;
        $roomid = $myorder->roomid;
        $notes = $myorder->notes;
        $userid = $this->user_id;

        $insertedOrder = array('totalprice' => $totalprice, 'roomid' => $roomid, 'notes' => $notes);
        $order_model = new Application_Model_Order();
        $orderdetails_model = new Application_Model_OrderDetails();


        $order_model->addOrder($insertedOrder, $userid);

        $order_id = $order_model->getAdapter()->lastInsertId();
        for ($i = 0; $i < count($myorder->products); $i++) {
            $prod_id = $myorder->products[$i]->prod_id;
            $prod_amount = $myorder->products[$i]->prod_amount;
            $confirmuserorder = $orderdetails_model->addOrderDetails($order_id, $prod_id, $prod_amount);
        }
        if ($confirmuserorder) {
            if ($confirmuserorder) {
                echo'ok success';


                $this->redirect("Order/adduserorder");
            } else {
                echo'failed';
            }
        }
    }

    public function addadminorderAction() {
        // action body
        $product_model = new Application_Model_Product();
        $user_model = new Application_Model_User();
        //$this->view->products = $product_model->listProducts();
        $allproducts = $product_model->availableProducts('"1"');
        //var_dump($allproducts);
        $allUsers = $user_model->listUsers();
        $allRooms = $user_model->listRooms();

        $this->view->products = $allproducts;
        $this->view->users = $allUsers;
        $this->view->rooms = $allRooms;
    }

    public function adduserorderAction() {

        $product_model = new Application_Model_Product();
        $user_model = new Application_Model_User();
        $order = new Application_Model_Order();
        $order_details_model = new Application_Model_OrderDetails();

        //$this->view->products = $product_model->listProducts();
        $allproducts = $product_model->availableProducts('"1"');
        $allRooms = $user_model->listRooms();
        //get last  order id ,, max
        $userid = $this->user_id;

        $user_order = $order->getUserOrders($userid);
        if (count($user_order)) {
            //3shan mmkn tkon lsa new user has no orders&order detailss

            $max = $user_order[0]['id'];
            for ($i = 0; $i < count($user_order); $i++) {
                if ($user_order[$i]['id'] > $max) {
                    $max = $user_order[$i]['id'];
                }
            }

            // get order details fr table 2 .. prod ids
            $order_details = $order_details_model->getOrderDetails($max);
            ///var_dump($order_details);

            $productsid = array();
            for ($i = 0; $i < count($order_details); $i++) {

                array_push($productsid, $order_details[$i]['product_id']);
            }

            // get prods info 
            $productdetails = array();
            for ($i = 0; $i < count($productsid); $i++) {

                array_push($productdetails, $product_model->getProductById($productsid[$i]));
            }
            $this->view->latestorder = $productdetails;
        }
        //mskt araay fiha info kl prod fe a5r order
        $this->view->products = $allproducts;
        $this->view->rooms = $allRooms;
        $this->view->myname = $this->user_name;
        $this->view->myimg = $this->user_image;
    }

    public function mychecksajaxAction() {
        $order = new Application_Model_Order();

        $mystartdate = $_GET['mystartdate'];
        $myenddate = $_GET['enddate'];

        $user_id = $_GET['user_id'];
        $myorders = $order->viewMyOrders($user_id, $mystartdate, $myenddate);
        // var_dump($myorders);
        $this->view->checks = $myorders;


        $this->render("mychecks");
    }

    public function mydetailsAction() {
        $order = new Application_Model_Order();


        $order_id = $_GET['order_id'];
        $myuserschecks = $order->getdetails($order_id);
       // var_dump($myuserschecks);
        $this->view->myprodorders = $myuserschecks;


        $this->render("mychecks");
    }

    public function myuserschecksAction() {
        $order = new Application_Model_Order();

        $mystartdate = $_GET['mystartdate'];
        $myenddate = $_GET['enddate'];

        $user_id = $_GET['user_id'];
        $myuserschecks = $order->getOrders($mystartdate, $myenddate, $user_id);
        var_dump($myuserschecks);
        $this->view->mydates = $myuserschecks;


        $this->render("mychecks");
    }

    public function mychecksAction() {

        $order = new Application_Model_Order();
        $mychecks = $order->getNames();
       // $this->view->checks = $mychecks;
        $users = new Application_Model_User();
        $listusers = $users->listUsers();
        $this->view->users = $listusers;
    }

    public function myordersajaxAction() {
        $order = new Application_Model_Order();

        $mystartdate = $_GET['mystartdate'];
        $myenddate = $_GET['enddate'];
        $userid = $this->user_id;

        $myorders = $order->getOrders($mystartdate, $myenddate, $userid);
        //var_dump($myorders);
        $this->view->checks = $myorders;
        $this->render("myorders");
    }

    public function ylmyordersdateajaxAction() {
        $order = new Application_Model_Order();

        $mydate = $_GET['mydate'];
        $userid = $_GET['user_id'];

        $myprods = $order->getOrderDetails($mydate, $userid);
        //var_dump($myorders);
        $this->view->myprodorders = $myprods;
        $this->render("myorders");
    }


    public function myordersAction() {


        $orderobj = new Application_Model_Order();
      $this->view->myname = $this->user_name;
        $this->view->myimg = $this->user_image;
    }

}
