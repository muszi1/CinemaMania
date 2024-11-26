<?php
namespace App;
use Database;

class user_handle

{
    public function insertUser($arr)

    {
        $db=new Database;
        $concrate_use=$db->getItemByValue("user","email",$arr["email"])
    }
}



?>