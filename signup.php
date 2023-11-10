<?php 
include './database.php';
$umid = '';
$first = '';
$last = '';
$email = '';
$phone = '';


function test_input($data)
{
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $umid = test_input($_POST['umid']);
    $first = test_input($_POST['fname']);
    $last = test_input($_POST['lname']);
    $email = test_input($_POST['email']);
    $phone = test_input($_POST['phone']);
    $password = test_input($_POST['password']);
    $password1 = test_input($_POST['password1']);

    $query1 = "SELECT umid FROM students WHERE umid='$umid'";
    $statement1 = $db->prepare($query1);
    $statement1->execute();
    $rows = $statement1->rowCount();

    $err = false;
    if($password !== $password1){
        $wpass = "Passwords do not match";
        $err = true;
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $wmail = "Invalid email";
        $err = true;
    }
    if(!preg_match("/^\d{8}$/",$umid)){
        $wumid = "Invalid umid";
        $err = true;
    }
    if(!preg_match("/^[a-zA-Z]{3,}$/",$first)){
        $wfirstname = "First name can only contain valid characters";
        $err = true;
    }
    if(!preg_match("/^[a-zA-Z]{3,}$/",$last)){
        $wlastname = "Last name can only contain valid characters";
        $err = true;
    }
    if(!preg_match('/^[0-9]{3}(-)[0-9]{3}(-)[0-9]{4}$/',$phone)){
        $wphone = "Please enter a valid phone number";
        $err = true;
    }
    if($rows > 0){
        $wuser = "Account already exists";
        $err = true;
    }
    if(!$err){
        $hiddenpassword = password_hash($password,PASSWORD_DEFAULT);
        $query = "INSERT INTO students VALUES (:umid,:first,:last,:email,:phone,:password,:password1)";
        $statement = $db->prepare($query);
        $statement->bindValue(':umid', $umid);
        $statement->bindValue(':first', $first);
        $statement->bindValue(':last', $last);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':phone', $phone);
        $statement->bindValue(':password', $hiddenpassword);
        $statement->bindValue(':password1', $hiddenpassword);
        $statement->execute();
        $statement->closeCursor();
        header('location:index.php');
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
            <span>University of Michigan</span>
            CIS 525 Register Portal
        </h1>
    </div>
    <div class="loginform">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div style="margin-bottom:20px"><span class="errorMessage"><?php if(isset($wuser)) echo $wuser ?></span> <a style="font-size:small" href="./index.php">Log in here</a></div> 
            <div class="userInput">
                <label for="umid">UMID</label>
                <input type="text" value="<?php if(isset($umid)) echo $umid ?>" name="umid" id="" required>
                <span class="errorMessage"><?php if(isset($wumid)) echo $wumid ?></span>
            </div>
            <div class="userInput">
                <label for="name">First name</label>
                <input type="text" value="<?php if(isset($first)) echo $first ?>" name="fname" id="" required>
                <span class="errorMessage"><?php if(isset($wfirstname)) echo $wfirstname ?></span>
            </div>
            <div class="userInput">
                <label for="name">Last name</label>
                <input type="text" value="<?php if(isset($last)) echo $last; else echo '' ?>" name="lname" id="" required>
                <span class="errorMessage"><?php if(isset($wlastname)) echo $wlastname ?></span>
            </div>
            <div class="userInput">
                <label for="name">Email</label>
                <input type="email" value="<?php if(isset($email)) echo $email ?>" name="email" id="" required>
                <span class="errorMessage"><?php if(isset($wmail)) echo $wmail ?></span>
            </div>
            <div class="userInput">
                <label for="name">Phone number</label>
                <input type="tel" value="<?php if(isset($phone)) echo $phone ?>" name="phone" id="" required>
                <span class="errorMessage"><?php if(isset($wphone)) echo $wphone ?></span>
            </div>
            <div class="userInput">
                <label for="name">Password</label>
                <input type="password" name="password" id="" required>
            </div>
            <div class="userInput">
                <label for="name">Confirm Password</label>
                <input type="password" name="password1" id="" required>
                <span class="errorMessage"><?php if(isset($wpass)) echo $wpass ?></span>
            </div>
            <button type="submit">Sign up</button>
        </form>
    </div>
</body>
</html>
