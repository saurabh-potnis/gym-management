<?php

error_reporting(0);
require_once'include/config.php';

if(isset($_POST['submit']))
{ 
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$mobile=$_POST['mobile'];
$email=$_POST['email'];
$password=$_POST['password'];


// Email id Already Exit

$usermatch=$con->prepare("SELECT mobile,email FROM userinfo WHERE (email=:email || mobile=:mobile)");
$usermatch->execute(array(':usreml'=>$email,':mblenmbr'=>$mobile)); 
while($row=$usermatch->fetch(PDO::FETCH_ASSOC))
{
$email= $row['email'];
$mobile=$row['mobile'];
}







if(empty($fname))
{
  $nameerror="Please Enter First Name";
}
else if(empty($lname))
{
  $nameerror="Please Enter First Name";
}

 else if(empty($mobile))
 {
 $mobileerror="Please Enter Mobile No";
 }

 else if(empty($email))
 {
 $emailerror="Please Enter Email";
 }

else if($email==$email || $mobile==$mobile)
 {
  $error="Email Id or Mobile Number Already Exists!";
 }
  else if(empty($password))
{
$passworderror="Please Enter password";
}
else{
$sql="INSERT INTO userinfo (fname,lname,email,mobile,password) Values(:fname,:lname,:email,:mobile,:password)";

$query = $con -> prepare($sql);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':lname',$lname,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
$query->bindParam(':password',$pass,PDO::PARAM_STR);

$query -> execute();
if($lastInsertId>0)
{
echo "<script>alert('Registration successfull. Please login');</script>";
echo "<script> window.location.href='login.php';</script>";
}
else 
{
$error ="Registration Not successfully";
 }
}
}
?>




/*userinfo
<?php

$con=mysqli_connect('localhost','root','saurabh');

if($con){
   echo "connection successful";
}
else{
  echo"no connection";
}

mysqli_select_db($con,'gym');


$fname =$_POST['fname'];
$lname =$_POST['lname'];
$mobile =$_POST['mobile'];
$email=$_POST['email'];
$password =$_POST['password'];

 



$query="insert into userinfo (fname,lname,mobile,email,password) values ('$fname','$lname','$mobile','$email','$password')";


mysqli_query($con,$query);