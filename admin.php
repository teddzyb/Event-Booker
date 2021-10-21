<?php session_start() ?>
<!DOCTYPE html>
<html>
<head>
    <?php require 'scripts/framework.php' ?>
    <title>Event Booker - Admin Page</title>
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

        <?php
        if(isset($_SESSION["userErr"])) {
            unset($_SESSION["userErr"]);
        ?>
        <div class="row justify-content-center text-danger mb-3">
            Passwords do not match!
        </div>
        <?php } ?>

        <hr>
        <form action="" method="post">
            <div class="row justify-content-center">
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-primary" type="submit" name="createEvent" value="Create Event">
                </div>
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-success" type="submit" name="createUser" value="Create User">
                </div>
            </div>
        </form>

        <?php if (isset($_POST["createEvent"])) { ?>
        <div class="row justify-content-center">
            <!-- Create Event -->
            <div class="row">
                <form class="conatainer-fluid mt-3 mb-3" action="scripts/dbconnect.php" method="post">
                    <p class="fs-5">Create Event</p>
                    <div class="row justify-content-center mb-3  mt-4">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="eventimage">Event Image</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="text" placeholder="Insert image link" name="eventimage" id="eventimage">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="eventname">Event Name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="text" name="eventname" id="eventname">
                        </div>
                    </div>
                    <input class="btn btn-success mt-3" type="submit" name="createEvent" value="Confirm">
                </form>
            </div>
        </div>
        <?php } ?>

        <?php if (isset($_POST["createUser"])) { ?>
        <div class="row justify-content-center">
            <!-- Create User -->
            <div class="row">
                <form class="conatainer-fluid mt-3 mb-3" action="scripts/dbconnect.php" method="post">
                    <p class="fs-5">Create User</p>
                    <div class="row justify-content-center mb-3 mt-4">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="usertype">User Type</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" list="userTypes" required value="User" name="usertype" id="usertype">
                            <datalist id="userTypes">
                                <option value="Admin">
                                <option value="User">
                              </datalist>
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="uname">Username</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="text" name="uname" id="uname">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3 mt-4">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="name">Name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="text" name="name" id="name">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="uemail">Email</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="text" name="uemail" id="uemail">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="upass">Password</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="password" name="upass" id="upass">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="uconfpass">Confirm Password</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" required type="password" name="uconfpass" id="uconfpass">
                        </div>
                    </div>
                    <input class="btn btn-success mt-3" type="submit" name="createUser" value="Confirm">
                </form>
            </div>
        </div>
        <?php } ?>

        <?php if (isset($_POST["updateEvent"])) { ?>
        <div class="row justify-content-center">
            <!-- Update Event -->
            <div class="row">
                <form class="conatainer-fluid mt-3 mb-3" action="scripts/dbconnect.php" method="post">
                    <p class="fs-5">Update Event</p>
                    <div class="row justify-content-center mb-3  mt-4">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="eventimage">Event Image</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" placeholder="Insert image link" name="neweventimage" id="eventimage">
                        </div>
                    </div>
                    <div class="row justify-content-center mb-3">
                        <div class="col-2 text-end">
                            <label class="col-form-label text-primary" for="eventname">Event Name</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" value="<?php 
                                foreach ($_SESSION["eventsList"] as $event) {
                                    if ($event["event_id"] == $_POST["updateEvent"]) {
                                        echo $event["event_name"];
                                        break;
                                    }
                                }
                            ?>" name="neweventname" id="eventname">
                        </div>
                    </div>
                    <button class="btn btn-success mt-3" type="submit" name="pushEventUpdate" value="<?php echo $_POST["updateEvent"] ?>">Confirm</button>
                </form>
            </div>
        </div>
        <?php } ?>

        <?php if (isset($_POST["deleteEvent"])) { ?>
        <div class="row justify-content-center">
            <!-- Delete Event -->
            <div class="row">
                <form class="conatainer-fluid mt-3 mb-3" action="scripts/dbconnect.php" method="post">
                    <p class="fs-5">Delete Event</p>
                    <div class="row justify-content-center mb-3">
                        <div class="col text-center text-danger">
                            Are you sure you want to delete event: <?php 
                                foreach ($_SESSION["eventsList"] as $event) {
                                    if ($event["event_id"] == $_POST["deleteEvent"]) {
                                        echo $event["event_name"];
                                        break;
                                    }
                                }
                            ?>?
                        </div>
                    </div>
                    <button class="btn btn-danger mt-3" type="submit" name="pushEventDelete" value="<?php echo $_POST["deleteEvent"] ?>">Confirm</button>
                </form>
            </div>
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventCreated"])) {
            unset($_SESSION["eventCreated"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully created an event!
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["userSuccess"])) {
            unset($_SESSION["userSuccess"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully created a user!
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventUpdated"])) {
            unset($_SESSION["eventUpdated"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully updated an event!
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventUpdateErr"])) {
            unset($_SESSION["eventUpdateErr"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Event image link is not an image!
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventDeleted"])) {
            unset($_SESSION["eventDeleted"]);
        ?>
        <div class="row justify-content-center text-success mb-3">
            Successfully deleted an event!
        </div>
        <?php } ?>

        <?php
        if(isset($_SESSION["eventCreatedErr"])) {
            unset($_SESSION["eventCreatedErr"]);
        ?>
        <div class="row justify-content-center text-danger mb-3">
            Event image link is not an image!
        </div>
        <?php } ?>

        <hr>
        
        <form action="scripts/dbconnect.php" method="post">
            <div class="row justify-content-center">
                <div class="col-auto mb-3">
                    <input class="btn btn-outline-secondary" type="submit" name="displayEvents" value="Display Events">
                </div>
            </div>
        </form>

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
                    <p>Update the name or image of this event below! To book this event, log in with your user account. Deleting this event will permanently remove it from the database.</p>
                </div>
                <div class="w-100"></div>
                <form class="row justify-content-center" action="" method="post">
                    <div class="col-auto">
                        <button class="btn btn-outline-primary" type="submit" name="updateEvent" value="<?php echo $event["event_id"] ?>">Update</button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-danger" type="submit" name="deleteEvent" value="<?php echo $event["event_id"] ?>">Delete</button>
                    </div>
                </form>
            </div>
        <?php } ?>
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

    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js' integrity='sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ' crossorigin='anonymous'></script>
</body>
</html>