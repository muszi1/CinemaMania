<?php
require_once __DIR__. '/../Library/autoload.php';
use App\Database;
$dbs=new App\Database;

$genres=$dbs->read('genre');
print_r($genres);

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

    <div class="container">

    <hr>

    <label> <b>Fullname</b> </label>

    <input type="text" name="name" placeholder= "Fullname" size="50" required /> 

    <label for="email"><b>Email</b></label>

    <input type="text" placeholder="Enter Email" name="email" required>
    
    <input type="password" name="password" required>



    

  </form>