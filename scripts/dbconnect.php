<?php

session_start();

$user = "localhost";    // server address
$host = "root";         // username
$pass = "";             // password
$db = "event_booking";  // database name
$authuser = $_SESSION['authuser'];

// LOG IN -------------------------------
if (isset($_POST["login"])) {
   $username = $_POST["user"];
   $password = $_POST["pass"];

   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Log In: Connection successful!<br>";
      $query = "SELECT * FROM `users_detail` WHERE `username` = '$username' AND `password` = '$password'";
      $result = mysqli_query($conn, $query);
   
      if ($result) {
         if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["authuser"] = $row["user_id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["usertype"] = $row["user_type"];
         } else {
            header("Location: ../index.php");
            $_SESSION["loginErr"] = TRUE;
         }
      } else {
         echo "Error: " . mysqli_error($conn) . "<br>";
      }
   
      if (isset($_SESSION["authuser"])) {
         $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
         exit();
      }
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// EDIT PROFILE -------------------------------
if (isset($_POST["updateprofile"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Edit Profile: Connection successful!<br>";

      if ($_POST["newusername"] != "") {
         $newusername = $_POST['newusername'];
         mysqli_query($conn, "UPDATE `users_detail` SET `username` = '$newusername' WHERE `user_id` = '$authuser'");
         $_SESSION["username"] = $newusername;
      }
      if ($_POST["newpass"] != "") {
         if ($_POST["newpass"] == $_POST["confpass"]) {
            $newpass = $_POST['newpass'];
            mysqli_query($conn, "UPDATE `users_detail` SET `password` = '$newpass' WHERE `user_id` = '$authuser'");
         } else {
            $_SESSION["passErr"] = TRUE;
         }
      }
      if ($_POST["newname"] != "") {
         $newname = $_POST['newname'];
         mysqli_query($conn, "UPDATE `users_detail` SET `name` = '$newname' WHERE `user_id` = '$authuser'");
         $_SESSION["name"] = $newname;
      }
      if ($_POST["newemail"] != "") {
         $newemail = $_POST['newemail'];
         mysqli_query($conn, "UPDATE `users_detail` SET `email` = '$newemail' WHERE `user_id` = '$authuser'");
         $_SESSION["email"] = $newemail;
      } 
      
      if (!isset($_SESSION["passErr"])) {
         $_SESSION["updateSuccess"] = TRUE;
      }

      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// CREATE USER -------------------------------
if (isset($_POST["createUser"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Create User: Connection successful!<br>";

      $username = $_POST["uname"];
      $password = $_POST["upass"];
      $confpass = $_POST["uconfpass"];
      $name = $_POST["name"];
      $email = $_POST["uemail"];
      $usertype = $_POST["usertype"];

      if ($password == $confpass) {
         mysqli_query($conn, "INSERT INTO `users_detail` (`username`, `password`, `name`, `email`, `user_type`)
                              VALUES ('$username', '$password', '$name', '$email', '$usertype')");
         $_SESSION["userSuccess"] = TRUE;
      } else {
         $_SESSION["userErr"] = TRUE;
      }

      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// CREATE EVENT -------------------------------
if (isset($_POST["createEvent"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Create Event: Connection successful!<br>";

      $eventName = $_POST["eventname"];
      $eventImage = $_POST["eventimage"];

      // saving image
      if (@getimagesize($eventImage)) {
         $image_link = $eventImage;
         $split_image = pathinfo($image_link);

         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL , $image_link);
         curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
         $response = curl_exec($ch);
         curl_close($ch);
         $file_name = str_replace(" ", "", $eventName).".".$split_image['extension'];
         $file = fopen("eventImages/".$file_name , 'w') or die("X_x");
         fwrite($file, $response);
         fclose($file);

         mysqli_query($conn, "INSERT INTO `events` (`event_name`, `event_image`)
                                 VALUES ('$eventName', '$file_name')");

         $_SESSION["eventCreated"] = TRUE;
      } else {
         $_SESSION["eventCreatedErr"] = TRUE;
      }  

      unset($_SESSION["eventsList"]);
      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// DISPLAY EVENTS -------------------------------
if (isset($_POST["displayEvents"])) {

   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Display Events: Connection successful!<br>";

      $query = "SELECT * FROM `events` WHERE `event_id` NOT IN ('$booked')";
      $result = mysqli_query($conn, $query);
   
      if ($result) {
         if (mysqli_num_rows($result) > 0) {
            $_SESSION["eventsList"] = [];
            while ($row = mysqli_fetch_assoc($result)) {
               array_push($_SESSION["eventsList"], ["event_id" => $row["event_id"], "event_name" => $row["event_name"], "event_image" => $row["event_image"]]);
            }
         } else {
            $_SESSION["eventsErr"] = TRUE;
         }
      } else {
         echo "Error: " . mysqli_error($conn) . "<br>";
      }
   
      unset($_SESSION["bookedList"]);
      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// UPDATE EVENT -------------------------------
if (isset($_POST["pushEventUpdate"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Update Event: Connection successful!<br>";

      $eventID = $_POST['pushEventUpdate'];
      $newImg = $_POST['neweventimage'];
      $newName = $_POST['neweventname'];

      if ($_POST["neweventimage"] != "") {
         if (@getimagesize($newImg)) {
            $image_link = $newImg;
            $split_image = pathinfo($image_link);
   
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL , $image_link);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            $response = curl_exec($ch);
            curl_close($ch);
            $file_name = str_replace(" ", "", $newName).".".$split_image['extension'];
            $file = fopen("eventImages/".$file_name , 'w') or die("X_x");
            fwrite($file, $response);
            fclose($file);
   
            mysqli_query($conn, "UPDATE `events` SET `event_image` = '$file_name' WHERE `event_id` = '$eventID'");
   
            $_SESSION["eventUdated"] = TRUE;
         } else {
            $_SESSION["eventUpdateErr"] = TRUE;
         }  
      }

      if ($_POST["neweventname"] != "") {
         mysqli_query($conn, "UPDATE `events` SET `event_name` = '$newName' WHERE `event_id` = '$eventID'");
      }

      unset($_SESSION["eventsList"]);
      $_SESSION["eventUpdated"] = TRUE;

      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// DELETE EVENT -------------------------------
if (isset($_POST["pushEventDelete"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Delete Event: Connection successful!<br>";

      $eventID = $_POST['pushEventDelete'];
      $result = mysqli_query($conn, "DELETE FROM `events` WHERE `event_id` = '$eventID'");

      unset($_SESSION["eventsList"]);
      $_SESSION["eventDeleted"] = TRUE;

      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// DISPLAY BOOKED -------------------------------
if (isset($_POST["displayBooked"])) {

   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Booked Events: Connection successful!<br>";

      $userID = $_SESSION["authuser"];

      $query = "SELECT * FROM `bookings` INNER JOIN `events` ON `bookings`.`event_id` = `events`.`event_id`
                  WHERE `bookings`.`user_id` = $userID";
      $result = mysqli_query($conn, $query);
   
      if ($result) {
         if (mysqli_num_rows($result) > 0) {
            $_SESSION["bookedList"] = [];
            while ($row = mysqli_fetch_assoc($result)) {
               array_push($_SESSION["bookedList"], $row);
            }
         } else {
            $_SESSION["bookedErr"] = TRUE;
         }
      } else {
         echo "Error: " . mysqli_error($conn) . "<br>";
      }
      
      unset($_SESSION["eventsList"]);
      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// BOOK EVENT -------------------------------
if (isset($_POST["bookEvent"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Book Event: Connection successful!<br>";

      $userID = $_SESSION["authuser"];
      $eventID = $_POST["bookEvent"];

      if (!isset($_SESSION["bookedIDs"])) {
         $_SESSION["bookedIDs"] = [];
      }

      if (!in_array($eventID, $_SESSION["bookedIDs"])) {
         mysqli_query($conn, "INSERT INTO `bookings` (`event_id`, `user_id`)
                              VALUES ('$eventID', '$userID')");

         array_push($_SESSION["bookedIDs"], $eventID);
         $_SESSION["eventBooked"] = TRUE;
      } else {
         $_SESSION["bookErr"] = TRUE;
      }
      
      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

// CANCEL EVENT -------------------------------
if (isset($_POST["cancelEvent"])) {
   $conn = mysqli_connect($user, $host, $pass, $db);

   if ($conn) {
      echo "Cancel Event: Connection successful!<br>";

      $eventID = $_POST['cancelEvent'];
      $userID = $_SESSION['authuser'];
      $result = mysqli_query($conn, "DELETE FROM `bookings` WHERE `event_id` = '$eventID' AND `user_id` = '$userID'");

      foreach ($_SESSION["bookedIDs"] as $event) {
         if ($event == $eventID) {
            unset($event);
         }
      }

      unset($_SESSION["eventsList"]);
      $_SESSION["eventCanceled"] = TRUE;
      unset($_SESSION["bookedList"]);
      $_SESSION["usertype"] == "Admin" ? header("Location: ../admin.php") : header("Location: ../user.php");
   } else {
      echo "Connection failed: " . mysqli_connect_error() . "<br>";
   }
}

?>
