<?php
namespace App\Controllers;
use App\Models\User_model;
use App\Tools;
class User_Controller
{
    public function insertUser($arr)
    {
            $data = [
            "full_name" => $arr["name"],
            "password" => Tools::Crypt($arr["password"]),
            "type" => "user",
            "genre_id" => intval($arr["genre"]),
            "email" => $arr["email"]
            ];
            try{
                $user=new User_model($data);
                if($user -> save())
                {
                    header("Location: /");
                }
            }
            catch (\Exception $e) {
                echo $e;
            }
    }
    

}

?>