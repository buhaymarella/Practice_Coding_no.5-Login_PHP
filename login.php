<?php

include 'connect.php';

if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount()>0){
        setcookie('user_id', $row['id'], time() + 60*60*24, '/' );
        header('location:home.php');
    }else{
        $message[] = 'Incorrect email or password!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Login Form</title>

    <!-- font link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

<?php

if(isset($message)){
    foreach($message as $message){
        echo '<div class="message"><span>'.$message.'</span><i class="fa-solid fa-xmark" onclick="this.parentElement.remove();"></i></div>';
    }
}

?>
    <!-- register starts-->
    <section class="form-container">

    <form action="" method="post">
    <h3>Login Now</h3>

    <input type="email" class="box" required placeholder="Enter your Email:" name="email">
    <input type="password" class="box" required placeholder="Enter your Password:" name="pass" maxlength="50">
    <input type="submit" value="login now" name="submit" class="btn">
    <p>Don't have an account? <a href="register.php">Register Now</a></p>

    </form>

    </section>

</body>
</html>