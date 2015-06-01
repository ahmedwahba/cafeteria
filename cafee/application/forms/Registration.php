<?php

class Application_Form_Registration extends Zend_Form {

    public function init() {


        $username = new Zend_Form_Element_Text('name');
        //  $username = $this->createElement('text', 'name');
        $username->setLabel('Username: *')
                ->setRequired(true);
        //$username->addFilter(new Zend_Filter_HtmlEntities());
        $username->addFilter(new Zend_Filter_StripTags);

        $email = $this->createElement('text', 'email');
        $email->setLabel('Email: *')
                ->setRequired(false);





        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: *')
                ->setRequired(true);


        $confirmPassword = $this->createElement('password', 'confirmPassword');
        $confirmPassword->setLabel('Confirm Password: *')
                ->setRequired(true);

//        $confirmPasswor = $this->createElement('text', 'image');
//        $confirmPasswor->setLabel('Confirm image: *')
//                ->setRequired(false);

        $image = new Zend_Form_Element_File('image');
        $image->setLabel("upload your iamge")
                ->setDestination(APPLICATION_PATH . '/../public/data')
                ->setRequired(true)
                ->addValidator('Extension', false, array('jpg', 'jpeg', 'png'))
                ->getValidator('Extension')->setMessage('This file type is not supportted.');
        // $image->setDestination('/www/coff/data');
        $this->setAttrib('enctype', 'multipart/form-data');








        $room_no = $this->createElement('text', 'room_no');
        $room_no->setLabel('Room number:')
                ->setRequired(false);

        $ext = $this->createElement('text', 'ext');
        $ext->setLabel('Ext:')
                ->setRequired(false);


        $register = $this->createElement('submit', 'register');
        $register->setLabel('Sign up')
                ->setIgnore(true);

        $this->addElements(array(
            $username,
            $email,
            $password,
            $confirmPassword,
            $image,
            $room_no,
            $ext,
            $register
        ));
    }

}
