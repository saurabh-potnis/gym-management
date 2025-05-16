<?php
session_start(); // Start the session

// Database connection
include "includes/config.php"; 


$loginMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Prepare statement to fetch user data
    $stmt = $conn->prepare("SELECT id, password FROM userinfo WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashedPassword);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;

            // Redirect to user home page
            header("Location: userhome.php");
            exit();
        } else {
            $loginMessage = "Invalid password!";
        }
    } else {
        $loginMessage = "No user found with that email!";
    }

    // Close statement
    $stmt->close();
}
$conn->close();
?>







<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> 
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/login.css">
<title></title>
  <style>
       .message {
            text-align: center;
            color: red;
            margin-bottom: 20px;
        }
        .success {
            color: green;
        }
  body, h2, input, button {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background:url(img/1.jpg) ;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
}


body{
font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    display: flex;
   height: 100vh;
}
header {
        font-size: 62.5%;
        overflow-x:hidden;
    
    }
 header{
        position: fixed;
        width: 100%;
        top:0;
        right: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        transition:all 0.5s ease;
        font-size: 62.3%;
        overflow-x:hidden;
    }
.logo {
        font-size: 30px;
        margin-left: 160px;
        color: #fff;
        font-weight: 800;
        cursor:pointer;
        transition: 0.3s ease-in-out;
    }
    
.logo:hover{
        transform: scale(1.1);
        color: #fff;
    }
    
span{
        color: rgb(18, 248, 232);
    
    }
.navbar{
        display:flex;
        font-size: 62.3%;
        overflow-x:hidden;
    }
    
.navbar a{
        font-size: 20px;
        font-weight: 600;
        color:#FFF;
        margin-left: 4rem;
        transition:  all 0.5s ease;
        border-bottom:3px solid transparent;
    }
.navbar a:hover,
.navbar a.active{
        color:rgb(18, 248, 232);
        border-bottom: 3px solid var();
    }
 #menu-icon {
       margin-right: 500px;
        font-size: 40px;
        color:rgb(18, 248, 232);
        cursor: pointer;
    }

.container{
    align-items: center;
    
    max-width: 360px;
    width: 100%;
    padding: 20px;
    background:#23112a;
    border-radius: 15px;
    box-shadow: 0 2px 10px #074c8f ;
   
}

/* Form heading styling */
.login-form h2{
    margin-bottom: 30px;
    font-size: 40px;
    text-align: center;
    justify-content: center;
    align-items: center;
    color:rgb(18,248,232) ;
  

}


.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;

    color:rgb(18,248,232);
    font-size: 25px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background-color: #fff;
}


.rounded-button {
   
  box-shadow: none;
    padding: 15px;
    border-radius: 12px;
    color:#850707f0;
    display: inline-block;
    margin: 4px 2px;
    background-color:#45ffca;
    font-size: 25px;
    font-weight: 600;
  
     cursor: pointer;
    transition:  0.5s ease;
}

.rounded-button:hover {
   color: #fff;
    box-shadow: 0 0 10px #45ffca;
  
}




@media (max-width: 600px) {
    .container {
        padding: 15px;
    }
   

    .login-form h2 {
        font-size: 20px;
    }
    
 

    .form-group input {
        padding: 8px;
        font-size: 14px;
    }

    .rounded-button {
        padding: 25px;
        font-size: 14px;
       color: #000;
    }
  
  
}
.container h1  {
    margin-top: 20px;
    font-size: 20px;
    color: skyblue;
  
}
.container a{
    font-size: 20px;
    color: skyblue;
    padding-left: 30px;
    font-weight: bold;
    margin-top: 20px;
}

</style>

</head>

<body>


<header>
<a href="#home" class="logo">Powerhouse<span>Fitness</span></a>

<div class='bx bx-menu' id="menu-icon"></div>
        <ul class="navbar">
            <li> <a href="index.php#home">Home</a></li>
            <li> <a href="index.php#services">Services</a></li>
            <li> <a href="index.php#about">About Us</a></li>
           
        </ul>
 </header>



    <div class="container">
   
        <form class="login-form" name="login" method="post" action="login.php">
            <h2>Login</h2>
           
        <div class="message"><?php echo $loginMessage; ?></div>
  
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="rounded-button" type="submit">Login</button>
           
           
        </form>
      <h1> Not Registered? <a href="registration.php">Register Now</a>
</h1>
        
    </div>
</body>
</html>

