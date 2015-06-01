<?php

class CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
         $auth = Zend_Auth::getInstance();
       
        
         if (!($auth->hasIdentity()) ) {
            $this->redirect("Auth/login");
        }
    }

    public function indexAction()
    {
        // action body
    }
    
     public function listAction() { 
        $category_model = new Application_Model_Category();
        $this->view->categories = $category_model->listCategory(); 
        
    } 
    
     public function addAction() {
        $form = new Application_Form_Category();
        $this->view->form = $form;
        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $this->view->submit = 'done';
                $name= $form->getValues();
                
               
                $category_model = new Application_Model_Category();
                $category_model->addCategory($name);
                        $this->redirect('Category/list');

            }
        
        }
        $this->render('add');
    }
    

    public function updateAction()
    {
        
        $form = new Application_Form_Category();
        $category_model = new Application_Model_Category();
        $id = $this->getRequest()->getParam('id');
        $values = $category_model->getCategoryById($id)->toArray();

        $this->view->form = $form->populate($values[0]);


        if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getParams())){
                $this->view->submit = 'done';
                $name= $form->getValues();
                var_dump($name);
                
               
                $category_model = new Application_Model_Category();
                $category_model->updateCategory($id,$name);
            }
        
        }
       $this->render('form');
      
        
       
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        $category_model = new Application_Model_Category();
        $delete = $category_model->deleteCategory($id);
        $this->redirect('category/list');

   
    }
    
    
    

}
