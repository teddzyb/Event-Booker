<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
    <?php require 'scripts/framework.php' ?>
    <title>Event Booker - User Page</title>
</head>
<style>
    /* div {
        border: 1px solid black;
    } */
</style>
<body class="bg-light pt-4">

    <?php
        // AUTH VALIDATION
        if (!isset($_SESSION["authuser"])) {
            header("Location: index.php");
            exit();
        }

        // LOG OUT
        if (isset($_POST["logout"])) {
            session_destroy();
            header("Location: index.php");
            exit();
        }
    ?>

    <div class="container text-center">

        <form action="" method="post">
            <div class="row justify-content-center">
                <div class="col-2 mb-2">
                    <img class="img-thumbnail" src="assets/profile_photo_placeholder.png" alt="">
                </div>
                <h4><?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "[username]" ?></h4>
                <p class="fs-6 text-secondary mb-4"><?php echo isset($_SESSION["usertype"]) ? $_SESSION["usertype"] : "[user_type]" ?></p>
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-secondary" type="submit" name="editProfile" value="Edit Profile">
                </div>
                <!-- Log Out -->
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-danger" type="submit" name="logout" value="Log Out">
                </div>
            </div>
        </form>

        <?php if (isset($_POST["editProfile"])) { ?>
        <div class="row justify-content-center">
            <!-- Edit Profile -->
            <div class="row">
                <form class="conatainer-fluid mt-3 mb-3" action="scripts/dbconnect.php" method="post">
                    <p class="fs-5 mb-4">Edit Profile</p>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="newname">Name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" value="<?php echo isset($_SESSION["name"]) ? $_SESSION["name"] : "[name]" ?>" type="text" name="newname" id="newname">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="newemail">Email</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" value="<?php echo isset($_SESSION["email"]) ? $_SESSION["email"] : "[email]" ?>" type="text" name="newemail" id="newemail">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="newusername">Username</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" value="<?php echo isset($_SESSION["username"]) ? $_SESSION["username"] : "[username]" ?>" type="text" name="newusername" id="newusername">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="newpass">New Password</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="password" name="newpass" id="newpass">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="confpass">Confirm Password</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="password" name="confpass" id="confpass">
                        </div>
                    </div>
                    <input class="btn btn-success mt-3" type="submit" name="updateprofile" value="Confirm">
                </form>
            </div>
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["updateSuccess"])) {
            unset($_SESSION["updateSuccess"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully updated your profile!
        </div>
        <?php } ?>
        
        <?php
        if(isset($_SESSION["passErr"])) {
            unset($_SESSION["passErr"]);
        ?>
        <div class="row justify-content-center text-danger mb-3">
            Passwords do not match!
        </div>
        <?php } ?>

        <hr>
        
        <form action="scripts/dbconnect.php" method="post">
            <div class="row justify-content-center">
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-secondary" type="submit" name="displayEvents" value="Display Events">
                </div>
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-secondary" type="submit" name="displayBooked" value="Booked Events">
                </div>
            </div>
        </form>

        <?php
        if(isset($_SESSION["eventBooked"])) {
            unset($_SESSION["eventBooked"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully booked an event.
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventCanceled"])) {
            unset($_SESSION["eventCanceled"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully canceled an event.
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventsErr"])) {
            unset($_SESSION["eventsErr"]);
        ?>
        <div class="row justify-content-center text-danger mb-3">
            No events available.
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["bookedErr"])) {
            unset($_SESSION["bookedErr"]);
        ?>
        <div class="row justify-content-center text-danger mb-3">
            No booked events.
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["bookErr"])) {
            unset($_SESSION["bookErr"]);
        ?>
        <div class="row justify-content-center text-danger mb-3">
            This event is already booked!
        </div>
        <?php } ?>

        <?php if (isset($_SESSION["eventsList"])) { ?>
        <div class="row justify-content-center">
        <!-- Events -->
        <p class="fs-5 mt-3">Events</p>
        <?php foreach ($_SESSION["eventsList"] as $event) { ?>
            <div class="row justify-content-center col-md-5 mb-5 mt-3">
                <div class="col mb-3">
                <img class="img-thumbnail" src="<?php echo "scripts/eventImages/".$event["event_image"] ?>" alt="">
                </div>
                <h4><?php echo $event["event_name"] ?></h4>
                <div class="col mt-3 mb-3">
                    <p>Book this event by clicking "Book Event" below!</p>
                </div>
                <div class="w-100"></div>
                <form class="row justify-content-center" action="scripts/dbconnect.php" method="post">
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit" name="bookEvent" value="<?php echo $event["event_id"] ?>">Book Event</button>
                    </div>
                </form>
            </div>
        <?php } ?>
        </div>
        <?php } ?>

        <?php if (isset($_SESSION["bookedList"])) { ?>
        <div class="row justify-content-center">
        <!-- Booked Events -->
        <p class="fs-5 mt-3">Booked Events</p>
        <?php foreach ($_SESSION["bookedList"] as $event) { ?>
            <div class="row justify-content-center col-md-5 mb-5 mt-3">
                <div class="col mb-3">
                <img class="img-thumbnail" src="<?php echo "scripts/eventImages/".$event["event_image"] ?>" alt="">
                </div>
                <h4><?php echo $event["event_name"] ?></h4>
                <div class="col mt-3 mb-3">
                    <p>Cancel this event by clicking "Cancel Event" below!</p>
                </div>
                <div class="w-100"></div>
                <form class="row justify-content-center" action="scripts/dbconnect.php" method="post">
                    <div class="col-auto">
                        <button class="btn btn-outline-danger" type="submit" name="cancelEvent" value="<?php echo $event["event_id"] ?>">Cancel Event</button>
                    </div>
                </form>
            </div>
        <?php } ?>
        </div>
        <?php } ?>

    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
</body>
</html>