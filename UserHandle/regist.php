<?php
require_once __DIR__. '/../Library/autoload.php';
use App\Database;
use App\Controllers\User_Controller;
$dbs=new App\Database;

$genres=$dbs->read('genre');

if(isset($_POST["submit"]))
{
    $Usermodell=new App\Controllers\User_Controller;
    $Usermodell -> insertUser($_POST);
}

?>

<form method="post"> 

    <div class="container">

    <hr>

    <label> <b>Fullname</b> </label>

    <input type="text" name="name" placeholder= "Fullname" size="50" required /> 

    <label for="email"><b>Email</b></label>

    <input type="text" placeholder="Enter Email" name="email" required>
    
    <label> <b>Password</b> </label>
    
    <input type="password" name="password" required>

    <select name="genre" required>
        <?php foreach($genres as $genre):?>
            <option value="<?=$genre["id"];?>"><?=$genre["genre_name"];?></option>
        <?php endforeach;?>
    </select>
    <input type="submit" value="Regisztráció" name="submit">
    

  </form>