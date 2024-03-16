<?php
session_start();
$location = '../';

global $db_location;
global $cnxn;
//global $use_local;
global $viewingID;

// Logout and return to login.php if ?logout=true
include '../php/roles/logout_check.php';
// Check for user_id in SESSION and redirect to login if null
include '../php/roles/user_check.php';
// might need admins
// Redirect admins to admin dashboard
//include 'php/roles/admin_kick.php';

echo
'<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edited User</title>
        <!-- Load theme from localstorage -->
        <script src="../js/themescript.js"></script>
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="../styles/styles.css"/>
        <!-- Latest compiled JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </head>
<body>';


include '../php/nav_bar.php';
?>
<main>
    <div class="container p-3" id="main-container">
        <?php

        function echoError($errorMessage) {
            echo "
            <div class='form-error'>
                <h3>Update failed, please try again.</h3>
                <a>$errorMessage</a>
                <a class='link' href='../user_edit.php'>Go to edit form</a>
            </div>
         ";
        }

        if(! empty($_POST)) {
            // removing
            foreach ($_POST as $value) {
                $value = trim($value);

                if (empty($value)) {
                    echoError('Nothing set');
                    return;
                }
            }

            include '../db_picker.php';
            if ($use_local){
                include '../' . $db_location;
            }else{
                include $db_location;
            }

            // constants
            $RADIO_VALUES = array("Seeking Internship", "Seeking Job", "Not Actively Searching");
            $MIN_COHORT_NUM = 1;
            $MAX_COHORT_NUM = 100;
            $MIN_ROLES = 5;
            $MAX_ROLES = 250;
            $MIN_PASSWORD = 8;
            $MAX_PASSWORD = 16;
            $MIN_NAME = 1;
            $MAX_NAME = 30;

            // form values
            $fname = $_POST['firstName'];
            $lname = $_POST['lastName'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password-confirm'];
            $cohortNum = $_POST['cohort-num'];
            $status = $_POST['status'];
            $roles = $_POST['roles'];

            // sanitization
            $fname = strip_tags(filter_var($fname, FILTER_SANITIZE_ADD_SLASHES));
            $lname = strip_tags(filter_var($lname, FILTER_SANITIZE_ADD_SLASHES));
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $cohortNum = filter_var($cohortNum, FILTER_SANITIZE_NUMBER_INT);
            $roles = strip_tags(filter_var($roles, FILTER_SANITIZE_ADD_SLASHES));
            $password = strip_tags($password);
            $passwordConfirm = strip_tags($passwordConfirm);

            // validation

            // names
            if (! (strlen($fname) >= $MIN_NAME && strlen($fname) <= $MAX_NAME) || ! (strlen($lname) >= $MIN_NAME && strlen($lname) <= $MAX_NAME)) {
                echoError('Name error');
                return;
            }

            // cohort number
            if(! $cohortNum >= $MIN_COHORT_NUM && ! $cohortNum <= $MAX_COHORT_NUM) {
                echoError('Cohort error');
                return;
            }

            // roles
            if(! strlen($roles) >= $MIN_ROLES && ! strlen($roles) <= $MAX_ROLES) {
                echoError('Roles error');
                return;
            }

            // email
            if(! preg_match("/[^\s@]+@[^\s@]+\.[^\s@]+/", $email) ) {
                echoError('Email error');
                return;
            }

            // checks if email is already in use
            $checkEmail = "SELECT * FROM users WHERE email = '$email'";
            $resultCheckEmail = @mysqli_query($cnxn, $checkEmail);

            if(mysqli_num_rows($resultCheckEmail) !== 0) {
                echoError('Email already in use');
                return;
            }

            // password
            if(strlen($password) < $MIN_PASSWORD || strlen($password) > $MAX_PASSWORD) {
                echoError('Password error1');
                return;
            }

            if($password !== $passwordConfirm) {
                echoError('Password error2');
                return;
            }

            if(! preg_match("/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/", $password)) {
                echoError('Password error3');
                return;
            }

            //  status

            if(! in_array($status, $RADIO_VALUES)) {
                echoError('Status error');
                return;
            }

            $name = ucfirst($fname) . " " . ucfirst($lname);

            $sql = "UPDATE `users` 
                        SET `fname` = '$fname', `lname` = '$lname', `email` = '$email', `password` = '$password',
                            `cohortNum` = '$cohortNum', `status` = '$status', `roles` = '$roles'
                        WHERE `user_id` = '$viewingID';";

            $result = @mysqli_query($cnxn, $sql);

            echo "
            <div class='container p-3'>
            <h3 class='receipt-message p-3 mb-0'>Success! Your account has been edited.</h3>
            <div class='form-receipt-container p-3'>
                <ul class='receipt-content list-group list-group-flush'>
                    <li class='list-group-item'>
                        Name: $name
                    </li>
                    <li class='list-group-item'>
                        Email: $email
                    </li>
                    <li class='list-group-item'>
                        Password: " . str_pad('',strlen($password),'*') . "
                    </li>
                    <li class='list-group-item'>
                        Cohort Number: $cohortNum
                    </li>
                    <li class='list-group-item'>
                        Status: $status
                    </li>
                    <li class='list-group-item message-box'>
                        " . stripslashes($roles) . "
                    </li>
                    <li class='align-self-center'>
                        <a class='link' href='../login.php'>Please Login</a>
                    </li>
                </ul>
        
            </div>
            </div>
        </main>
    ";
        } else {
            $formLocation = '../user_edit.php';
            include 'empty_form_msg.php';
        }
        ?>
    </div>
</main>

<?php include '../php/footer.php' ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="../js/main.js"></script>
<script src="../js/signupscript.js"></script>
</body>
</html>
