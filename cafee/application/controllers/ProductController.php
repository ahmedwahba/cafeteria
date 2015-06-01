<?php

class ProductController extends Zend_Controller_Action {
    private $user_id;

    public function init() {
        /* Initialize action controller here */
          $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->user_id = $auth->getIdentity()->id;
        }
         if (!($auth->hasIdentity()) ) {
            $this->redirect("Auth/login");
        }
    }

    public function indexAction() {
        // action body
                    $this->redirect("Auth/login");

    }

    public function getCategoriesArray(){
        
        $cat_id=array();
        $cat_name=array();
        
        $category_model=new Application_Model_Category();
        $cat_info=$category_model->listCategory();
        
        for($i=0 ; $i < count($cat_info) ; $i++){
            
            foreach($cat_info[$i] as $key => $value){
                if($key == "id"){
                    array_push($cat_id,$value);
                }
                elseif($key == "name"){
                    array_push($cat_name,$value);
                }
                
            }
        }
        
        $cat_arr=array_combine($cat_id, $cat_name);
        return $cat_arr;
    }
    
    public function addAction() {
        // action body
        
         $cat_arr=  $this->getCategoriesArray();
        
        $prodform = new Application_Form_Product();
        $catName=$prodform->getElement('catid');
        foreach ($cat_arr as $id => $name) {
            $catName->addMultiOption($id, $name);
        }
        $this->view->prodform = $prodform; 
        

        if ($this->getRequest()->isPost()) {


            if ($form->isValid($this->getAllParams())) {

                $new_image_name = time() . '_' . $this->getParam('name');
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->addFilter('Rename', APPLICATION_PATH . '/../public/img/product/' . $new_image_name);
                $upload->receive();
                $form_data = $form->getValues();
                $model = new Application_Model_Product();
                $form_data['picture'] = $new_image_name;
                $da = $model->addProduct($form_data);
                $this->redirect('Product/list');
            }
        }

       
    }

    public function listAction() {
        // action body
        $products = new Application_Model_Product();
        $this->view->products = $products->listProducts();
    }

    public function deleteAction() {
        // action body
        /*
          $prod = new Application_Model_Product();
          $res = $prod->deleteProduct(9);
          $this->view->res = $res ;
         * 
         */

        $id = $this->getRequest()->getParam('id');
        $model = new Application_Model_Product();
        $model->deleteProduct($id);
        $this->redirect("Product/list");
    }

    public function updateAction() {
        // action body

        $form = new Application_Form_Product();
        $this->view->form = $form;

        $id = $this->getRequest()->getParam('id');

        $model = new Application_Model_Product();
        $form_data = $model->getProductById($id)->toArray();

        $form->populate($form_data[0]);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $data_to_edit = $form->getValues();
                $model = new Application_Model_Product();
                $model->updateProduct($id, $data_to_edit);
                $this->redirect('Product/list');
            }
        }



        /*
          $con = new Application_Model_Product();

          $data = array(
          'name'=>'ahmed',
          'price'=>50


          );

          $con->updateProduct(7,$data);
         * 
         */
    }

}
