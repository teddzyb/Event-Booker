<?php 

session_start();

if (isset($_SESSION["authuser"])) {
    $_SESSION["usertype"] == "Admin" ? header("Location: admin.php") : header("Location: user.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php require 'scripts/framework.php' ?>
    <title>Event Booker - Log In</title>
</head>
<style>
    /* div {
        border: 1px solid black;
    } */
</style>
<body class="bg-light pt-4">
    
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-6 pt-5 pb-5 border border-primary border-2 rounded-3">
                <h2 class="text-primary">Event Booker</h2>
                <p class="fs-6 text-secondary mb-5">By: Edwin D. Bartlett</p>
                <form class="container-fluid pt-3" action="scripts/dbconnect.php" method="post">
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="col-form-label" for="user">Username</label>
                        </div>
                        <div class="col">
                            <input class="form-control" type="text" name="user" id="user">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-3">
                            <label class="col-form-label" for="pass">Password</label>
                        </div>
                        <div class="col">
                            <input class="form-control" type="password" name="pass" id="pass">
                        </div>
                    </div>
                    <?php
                    if (isset($_SESSION["loginErr"])) {
                        unset($_SESSION["loginErr"]);
                    ?>
                    <div class="row justify-content-center mb-3 text-danger">
                        Username or Password incorrect
                    </div>
                    <?php } ?>
                    <input class="btn btn-primary mt-3" type="submit" name="login" value="Log In">
                </form>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
</body>
</html>