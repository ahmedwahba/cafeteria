<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $username = $this->createElement('text','name');
        $username->setLabel('Username: *')
                ->setRequired(true);
        //$username->setAttrib('size', '32');
       
        $password = $this->createElement('password','password');
        $password->setLabel('Password: *')->setRequired(true);
       //  $password->setAttrib('size', '32');       
       
         $signin = $this->createElement('submit','signin');
        $signin->setLabel('Sign in')->setIgnore(true);
        
                
        $this->addElements(array(
                        $username,
                        $password,
                        $signin,
        ));
    }


}

