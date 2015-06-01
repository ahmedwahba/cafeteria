<?php

class Application_Form_Element_SelectCategory extends Zend_Form_Element_Select {
    public function init() {
         $user_model = new Application_Model_Category();
        $allcats = $user_model->listCategory();
 for ($i = 0; $i < count($allcats); $i++) {
            $name = $allcats[$i]['name'];
            $id = $allcats[$i]['id'];
        
            $this->addMultiOption($id, $name);
            
        }
    }
}

