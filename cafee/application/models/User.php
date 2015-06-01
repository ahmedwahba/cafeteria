<?php

class Application_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'user';
    
    function checkUnique($username)
    {
        $select = $this->_db->select()
                            ->from($this->_name,array('name'))
                            ->where('name=?',$username);
        $result = $this->getAdapter()->fetchOne($select);
        if($result){
            return true;
        }
        return false;
    }

    function  listUser(){
        $select = $this->_db->select()
                            ->from($this->_name)->query()->fetchAll();
            return $select;
        
    }
    function  deleteUser($id){
         $select = $this->_db->delete('user', 'id='.$id);
         return $select;
    }
    
    function editUser($id,$userUpdatedData){
    $select =  $this->update($userUpdatedData, 'id='.$id);
    return $select;
       
    }
            
    function  getRoom_no($username){
        $select = $this->_db->select('room_no')
                ->from($this->_name,array('name'))
                            ->where('name=?',$username);
        $result = $this->getAdapter()->fetchOne($select);
        if($result){
            return true;
        }
        return false;
    }
    
    function  getUserById($id){
          return $this->find($id);
    }
    
    
    public function getUserForOrders($id)
    {
        $result = $this->find($id);
       
       foreach ($result as $key => $value) {
            $name = $value['name'];
            $room = $value['room_no'];
            $ext = $value['ext'];     
       }
       $obj = new stdClass;
       $obj->name = $name;
       $obj->room_no = $room;
       $obj->ext = $ext;
       
       return $obj;
    }
    
     public function listUsers() 
    {

        return $this->fetchAll()->toArray();
    }
    
     public function listRooms()
     {
        $select = $this->select()->distinct()->from('user', 'room_no');
        $rowset = $this->fetchAll($select)->toArray();
        return $rowset;
    }
    
     function checkuserEmail ($email){
        return 
        $this->fetchAll($this->select()->from('user',array('id'))->where('email=?',$email))->toArray();
    }
   function  updateuseremail($newPassWord,$id){
       return $this->update(array('password'=>$newPassWord),"id=".$id);
   }
    
}

