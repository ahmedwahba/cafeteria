<?php

class AuthController extends Zend_Controller_Action {

    private $user_id;

    public function init() {
        /* Initialize action controller here */
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->user_id = $auth->getIdentity()->id;
        }
        if (!($auth->hasIdentity()) && $this->_request->getActionName() != "signup" 
                && $this->_request->getActionName() != "login" && $this->_request->getActionName() != "forgetpassword") {
            $this->redirect("Auth/login");
        }
    }

    public function indexAction() {
            $this->redirect("Auth/login");

        // action bod
    }

    public function loginAction() {

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $storage = new Zend_Auth_Storage_Session();
            $storage->clear();
        }
        $users = new Application_Model_User();
        $form = new Application_Form_Login();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $form->getValues();
                $auth = Zend_Auth::getInstance();
                $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(), 'user');
                $authAdapter->setIdentityColumn('name')
                        ->setCredentialColumn('password');
                $authAdapter->setIdentity($data['name'])
                        ->setCredential($data['password']);
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($authAdapter->getResultRowObject(array('id', 'name', 'image')));

                    if ($auth->getIdentity()->name == 'admin') {

                        $this->redirect("Order/adminhome");
                    } elseif ($auth->getIdentity()->name != 'admin') {
                        $this->redirect("Order/adduserorder");
                    }
                } else {
                    $this->view->errorMessage = "Invalid username or password. Please try again.";
                }
            }
        }
    }

    public function signupAction() {
         $users = new Application_Model_User();
        $form = new Application_Form_Registration();
        $this->view->form = $form;


        // Define a transport and set the destination on the server
        //   $upload = new Zend_File_Transfer_Adapter_Http();
        // $upload->addFilter('Rename', APPLICATION_PATH . '/../data/'.'jpg');
        // $upload->addFilter('Rename', APPLICATION_PATH . '/../data/');
        // Zend_Debug::dump($upload->getFileInfo());



        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
//                 $upload->setDestination('data/');
//               
//                 $upload->receive();
                $data = $form->getValues();

                $upload = new Zend_File_Transfer_Adapter_Http;
                
                $upload->receive();



                if ($data['password'] != $data['confirmPassword']) {
                    $this->view->errorMessage = "Password and confirm password don't match.";
                    return;
                }
                if ($users->checkUnique($data['name'])) {
                    $this->view->errorMessage = "Name already taken. Please choose      another one.";
                    return;
                }
                unset($data['confirmPassword']);
                $users->insert($data);
                echo 'tmaaaaaaaam';
                $this->_redirect('auth/login');
                echo 'tmaaaaaaaam';
            }
        }
    }

    public function logoutAction() {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('auth/login');
    }

     public function homeAction() {
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if (!$data) {
            $this->_redirect('auth/login');
        }
        //$this->view->username = $data->username;
    }

    public function deleteAction() {
         $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if (!$data) {
            $this->_redirect('auth/login');
        }
         $user = new Application_Model_User();
         $delete = $user->deleteUser($this->getRequest()->getParam('id'));
         if($delete){
             $this->_redirect('auth/list');
         }else{
             $this->_redirect('auth/delete');
         }
        //$this->view->username = $data->username;
    }

    public function editAction() {
          $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if (!$data){
            $this->_redirect('auth/login');
        }
        
         
         
         
         
         $form = new Application_Form_Registration();
        $this->view->form = $form;

        $id = $this->getRequest()->getParam('id');

        $model = new Application_Model_User();
        $form_data = $model->getUserById($id)->toArray();

        $form->populate($form_data[0]);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $data_to_edit = $form->getValues();
              $user = new Application_Model_User();
             unset($data_to_edit['confirmPassword']);
                   $edit=$user->editUser($this->getRequest()->getParam('id'),$data_to_edit);
                $this->redirect('auth/login');
            }
        }
         
         
         
         
    }

    public function listAction() {
        // action body
        $user = new Application_Model_User();
        $this->view->users = $user->listUser();
    }

    public function forgetpasswordAction() {


        $form2 = new Application_Form_Registration();
        $form2->getElement('register')->setLabel('send');
        $form2->removeElement('name');
        $form2->removeElement('password');
        $form2->removeElement('confirmPassword');
        $form2->removeElement('image');
        $form2->removeElement('room_no');
        $form2->removeElement('ext');



        $this->view->form = $form2;
        if ($this->getRequest()->isPost()) {
            if ($form2->isValid($this->getRequest()->getParams())) {
                $email = $this->_request->getParam('email');
                var_dump($email);
                $usermodel = new Application_Model_User();

                if ($userid = $usermodel->checkuserEmail($email)) {

                    $newpassword = substr(hash('sha512', rand()), 0, 8);

//////////////////// mail && password ///////////////////////////
                    $smtpoptions = array(
                        'auth' => 'login',
                        'username' => 'marwa.b.zeidan@gmail.com',
                        'password' => 'Marwaspassword', // ÇßÊÈ ÇáÈÇÓæÑÏ ÈÊÇÚß
                        'ssl' => 'tls',
                        'port' => 587
                    );
                    $mailtransport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $smtpoptions);
                    $mail = new Zend_Mail();
                    $mail->addTo($email, 'to..');
                    $mail->setSubject('Hi');
                    $mail->setBodyText('your new password is ' . $newpassword);
                    $mail->setFrom('marwa.b.zeidan@gmail.com', 'Cafee Prohect');

                    //Send it!
                    $sent = true;
                    try {
                        $mail->send($mailtransport);
                    } catch (Exception $e) {

                        $sent = false;
                    }

                    //Do stuff (display error message, log it, redirect user, etc)
                    if ($sent) {
                        if ($usermodel->updateuseremail(md5($newpassword), $userid[0]['id'])) {
                            echo 'Successfully Sent Please Check your Email';
                        } else {
                            echo 'Error in Server';
                        }
                    } else {
                        echo 'Failed Sending to your Email Please Check your Settings';
                    }
                } else {

                    echo 'This Email is not Existed in my Database';
                }
            }
        }
    }

}
