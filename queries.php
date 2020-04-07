<?php
        
//here we will add the functions

if(isset($_POST['button1'])) { 
    retrieveAllDentists(); 
} 

function retrieveAllDentists(){
    
    $servername = "localhost:3306";
    $database = "mainproject";
    $username = "projectuser";
    $password = "projectuser353";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);


    $sql = "SELECT * FROM dentist";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>wid</th>";
                    echo "<th>cid</th>";
                    echo "<th>name</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['wid'] . "</td>";
                    echo "<td>" . $row['cid'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    mysqli_close($conn);
}


?>

