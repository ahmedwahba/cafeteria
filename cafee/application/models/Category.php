<?php

class Application_Model_Category extends Zend_Db_Table_Abstract {

    protected $_name = 'category';

    public function listCategory() {

          return $this->fetchAll()->toArray();
    
    }
 function addCategory($cat_data) {
        $row = $this->insert($cat_data);

        return "ok";
    }
      function getCategoryById($parent_cat_id) {
            return $this->fetchAll(
    $this->select()
        ->where("parent_cat_id = ?", $parent_cat_id)
    )->toArray();
    
        
      }
}