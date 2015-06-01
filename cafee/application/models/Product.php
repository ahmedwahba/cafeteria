<?php

class Application_Model_Product extends Zend_Db_Table_Abstract
{
    protected $_name = 'product';
    
    function listProducts()
    {
        
        return $this->fetchAll()->toArray();
        
    }
   
    function  availableProducts ($state)
    {
         $select =  $this->select();       
         $select ->where("state=$state");
         return $this->fetchAll($select)->toArray();
    }
    
    function importantInfo($id)
    {
        
       $result = $this->find($id);
       
       foreach ($result as $key => $value) {
            $name = $value['name'];
            $price = $value['price'];
            $picture = $value['picture'];     
       }
       $obj = new stdClass;
       $obj->name = $name;
       $obj->price = $price;
       $obj->picture = $picture;
       
       return $obj;
    }



 function addProduct($product_date) 
    {
        $row = $this->insert($product_date);

        return "ok";
    }
    
 function deleteProduct($id){
        
        $this->delete("id=$id");
        
    }
    
    function updateProduct($id,$new_data){
        
        $this->update($new_data, "id=$id");
        
    }
    
    function getProductById($id){
        
        return $this->find($id);
        
    }
    
}
?>