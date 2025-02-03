<?php
    // // Start output buffering
    // ob_start();

    // // Output the message
    // echo "NOT AUTHORIZE TO ACCESS THIS PAGE";

    // // Flush the output buffer and send the content to the browser
    // ob_flush();
    
    // // Wait for 3 seconds
    // sleep(3);

    // // Redirect to the login page
    // header("Location: admin/login.php");

    // // End buffering and discard any remaining output
    // ob_end_flush();

    // Output the message
    echo "NOT AUTHORIZED TO ACCESS THIS PAGE";

    // Flush the output buffer and send the content to the browser
    flush();

    // Wait for 3 seconds
    sleep(3);

    // Redirect to the login page using JavaScript
    echo '<script>setTimeout(function() { window.location.href = "admin/login.php"; }, 3000);</script>';

?>