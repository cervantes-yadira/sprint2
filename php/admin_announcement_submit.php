<?php
session_start();
$_SESSION['location'] = '../';

global $cnxn;
global $db_location;
global $use_local;

// Logout and return to login.php if ?logout=true
include '../php/roles/logout_check.php';
// Ensure a user is logged in
include '../php/roles/user_check.php';
// Ensure an admin is logged in
include '../php/roles/admin_check.php';

echo
'<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Announcement Submit</title>
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
if(! empty($_POST)) {
    $finished = 0;
    // removing
    foreach ($_POST as $key => $value) {
        $value = trim($value);

        if (empty($value)){
            if($key !== 'additional-text'){
                $finished++;
                if ($finished == 1) {
                    echo "
                <div class='content'>
                    <h2>Failed to send.</h2>
                    <h2>Please fill out ". str_replace('-', ' ', $key) .".</h2>
                    ";
                } else {
                    echo "
                    <h2>Please fill out ". str_replace('-', ' ', $key) .".</h2>
                    ";
                }
            }
        }
    }
    if(!isset($_POST['job-or-intern'])) {
        $finished++;
        if ($finished == 1) {
            echo "
                <div class='content'>
                    <h2>Failed to send.</h2>
                    ";
        }
        echo"<h2>Please fill out job or intern button.</h2>";
    }
    if($finished > 0) {
        echo "
                <a class='link' href='../admin_announcement.php'>Go back</a>
            </div>
            ";
    } else {
        include '../db_picker.php';
        if ($use_local){
            include '../' . $db_location;
        }else{
            include $db_location;
        }

        $title = $_POST['announcement-title'];
        $jobType = $_POST['job-or-intern'];
        $location = $_POST['location'];
        $employer = $_POST['employer'];
        $addltext = $_POST['additional-text'];
        $url = $_POST['announcement-url'];
        // $sentto = $_POST['sent-to'];
//        $fname = $_POST['first-name'] == 'default' ? '' : $_POST['first-name'];
//        $lname = $_POST['last-name'] == 'default' ? '' : $_POST['last-name'];

        // sanitization
        $title = strip_tags(filter_var($title, FILTER_SANITIZE_ADD_SLASHES));
        $jobType = strip_tags(filter_var($jobType, FILTER_SANITIZE_ADD_SLASHES));
        $location = strip_tags(filter_var($location, FILTER_SANITIZE_ADD_SLASHES));
        $employer = strip_tags(filter_var($employer, FILTER_SANITIZE_ADD_SLASHES));
        $addltext = strip_tags(filter_var($addltext, FILTER_SANITIZE_ADD_SLASHES));
        $url = strip_tags(filter_var($url, FILTER_SANITIZE_ADD_SLASHES));
        // $sentto = filter_var($sentto, FILTER_SANITIZE_ADD_SLASHES);
        $today = date("Y-m-d");

        // run queries
        $sql = "INSERT INTO announcements (title, job_type, location, ename, additional_info, jurl, sent_to, date_created, is_deleted) 
                VALUES ('$title', '$jobType', '$location', '$employer', '$addltext', '$url', 'all', '$today', 0)";
        $result = @mysqli_query($cnxn, $sql);

        $sql2 = "SELECT fname, lname, email FROM users WHERE is_deleted = 0";
        $result2 = @mysqli_query($cnxn, $sql2);


//        if(! preg_match("/[^\s@]+@[^\s@]+\.[^\s@]+/", $sentto) ) {
//            echoError();
//            return;
//        }

        // mailing

        // static variables
        $addltext = wordwrap($addltext, 70); // formatted message
        $subject = $title . " " . $jobType . " at " . $employer ; // announcement title, job type, and company
        $from = 'Admin<admin@greenriver.edu>';
        $headers = 'From: ' . $from . "\r\n" .
            'Reply-To: ' . $from . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        $message = "Location: " . $location .
            "\nURL: ". $url .
            "\nAdditional Info: \n" . $addltext;


        $isSent = false;

        // checks if result2 is null/empty
        if($result2) {
            // loops throw result2 array
            foreach ($result2 as $row) {
                $fname = $row['fname'];
                $lname = $row['lname'];
                $email = $row['email'];

                $name = $fname . " " . $lname; // user's name
                $to = $name . "<" . $email . ">"; // user's name and email

                if(mail($to, $subject, $message, $headers)) {
                    $isSent = true;
                }
            }
        }

        if ($isSent) {
            echo "
            <h3 class='receipt-message p-3 mb-0'>Success! Your announcement has been sent.</h3>
            <div class='form-receipt-container p-3'>
                <ul class='receipt-content list-group list-group-flush'>
                    <li class='list-group-item text-break'>
                        Title: $title
                    </li>
                    <li class='list-group-item text-break'>
                        Job Type: $jobType
                    </li>
                    <li class='list-group-item text-break'>
                        Location: $location
                    </li>
                    <li class='list-group-item text-break'>
                        Employer: $employer
                    </li>
                    <li class='list-group-item text-break'>
                        More Information: $addltext
                    </li>
                    <li class='list-group-item text-break'>
                        URL: $url
                    </li>
                    <!--
                    <li class='list-group-item text-break'>
                        Sent To: 
                    </li>
                    -->
                </ul>
            </div>
            ";
        }

    }
} else {
    $formLocation = '../admin_announcement.php';
    include 'empty_form_msg.php';
}
?>
    </div>
</main>

<?php include '../php/footer.php' ?>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="../js/main.js"></script>
</body>
</html>

