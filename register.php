<?php

include 'connect.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    if($select_user->rowCount()>0){
        $message[] = 'email already taken!';
    }else{
        if($pass != $cpass){
            $message[] = 'Password does not match!';
        }else{
            $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password)VALUES(?,?,?)");
            $insert_user->execute([$name, $email, $cpass]);
            if($insert_user){
                $fetch_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
                $fetch_user->execute([$email,$cpass]);
                $row = $fetch_user->fetch(PDO::FETCH_ASSOC);
                if($fetch_user->rowCount() > 0){
                    setcookie('user_id', $row['id'], time() + 60*60*24, '/' );
                    header('location:home.php');
                }
            }
        }
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
    <title>Register Form</title>

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
    <h3>Register Now</h3>

    <input type="text" class="box" required placeholder="Enter your Username:" name="name" id="name">
    <input type="email" class="box" required placeholder="Enter your Email:" name="email" id="email">
    <input type="password" class="box" required placeholder="Enter your Password:" name="pass" maxlength="50" id="pass">
    <input type="password" class="box" required placeholder="Confirm your Password:" name="cpass" maxlength="50" id="cpass">
    <input type="submit" value="register now" name="submit" class="btn" id="submit">
    <p>Already have an account? <a href="login.php">Login Now</a></p>

    </form>

    </section>

</body>
</html>