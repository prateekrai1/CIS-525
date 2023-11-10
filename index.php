<?php 
include './database.php';
function test_input($data)
{
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $umid = test_input($_POST['umid']);
    $password = test_input($_POST['password']);
    $hiddenpassword = password_hash($password,PASSWORD_DEFAULT);
    $query = "SELECT * FROM students s WHERE s.umid='$umid'";
    $statement = $db->prepare($query);
    $statement->execute();
    $rows = $statement->rowCount();
    $passRecord = $statement->fetch(PDO::FETCH_ASSOC);
    if($rows > 0){
        if(password_verify($password,$passRecord['password'])){
            session_start();
            $_SESSION['auth'] = 'true';
            $_SESSION['user'] = $umid;
            header('location:./views/available-slots.php');
        }
        else{
            $error = "Invalid umid or password, please try again";
        }
    }
    else{
        $error = "Invalid umid or password, please try again";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="./style.css"> 
    <link rel="stylesheet" href="./styles/dashboard.css">
    <title>CIS 525 Register Portal</title>
</head>
<body>
    <div class="container">
    <img src="./images/1200px-UMDearborn_Vertical_Logo.svg.png" alt="">
        <h1>
            <span class= "logo">University of Michigan</span>
            CIS 525 Register Portal
        </h1>
    </div>

    <div class="loginform">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <span class="errorMessage"><?php if(isset($error)) echo $error ?></span>
            <div class="userInput">
                <label for="umid">UMID</label>
                <input type="text" name="umid" id="" required>
            </div>
            <div class="userInput">
                <label for="name">Password</label>
                <input type="password" name="password" id="" required>
            </div>
            <button type="submit">Log in</button>
            <span style="font-size:smaller">Do not have an account? <a href="./signup.php">Signup here</a</span>
        </form>
    </div>
</body>
</html>
