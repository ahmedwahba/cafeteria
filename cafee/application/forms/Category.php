<?php

class Application_Form_Category extends Zend_Form
{

    public function init()
    {
        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("category name: ");
        
        //instanse 
        
        $this->addElements(array($name));
        $submit = new Zend_Form_Element_Submit("submit");
        $this->addElements(array($submit));
        
    }

    
    

}
