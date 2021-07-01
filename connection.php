<?php

    function openConnection(){

        $servername = "localhost:3306";
        $database = "mainproject";
        $username = "projectuser";
        $password = "projectuser353";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);

        // Check connection

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // $connStatus = "Connected Succesfully";

        // echo "Connected successfully";

        // $htmlContents = file_get_contents("UserInput.php");
        // $htmlContents = str_replace("{{CONNECTION_STATUS}}", "Connected successfully", $htmlContents);

        // echo $htmlContents;

        return $conn;
    }

    function closeConnection($conn){
        mysqli_close($conn);
    }
    //mysqli_close($conn);
?>