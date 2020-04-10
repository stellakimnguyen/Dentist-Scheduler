<?php 
    include 'connection.php';
//here we will add the functions

if(isset($_POST['button1'])) { 

    // Create connection
    $conn = openConnection();
    //$beginning = "'2020-4-09 00:00:00'";
    //$end = "'2020-4-09 23:59:59'";
    //$pid = 3;
    createNewAppointment ($conn,3,4, 1, "2020-7-10 10:00:00");
    
    
    closeConnection($conn);
} 

/*
 if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'insert':
                insert();
                break;
            case 'select':
                select();
                break;
        }
    }
*/

function addNewPatients($conn, $name){
    
    $sql = "INSERT INTO patient (pid, name) VALUES (6, '" . $name . "');";

    if (mysqli_query($conn, $sql)){
        echo "New record created successfully";
    }else{
        echo "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

}

function createNewAppointment($conn, $wid, $pid, $cid, $date){
    
    if (strlen($wid) == 0){
        $sql = "INSERT INTO appointment (pid,cid,date_time) VALUES (" . $pid . ", " . $cid . ",'" . $date . "');";
    }else{
        $sql = "INSERT INTO appointment (wid,pid,cid,date_time) VALUES (" . $wid . ", " . $pid . ", " . $cid . ",'" . $date . "');";
    }
    if (mysqli_query($conn, $sql)){
        echo "New record created successfully";
    }else{
        echo "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

}

function selectAppointment($conn, $aid){

    $sql = "SELECT * FROM appointment WHERE aid = " . $aid . ";";

    $result = mysqli_fetch_array(mysqli_query($conn, $sql));

    //echo $result['wid'] . " - " . $result['pid'] . " - " . $result['cid'] . " - " . $result['status'] . " - " . $result['date_time'];


    return $result;

}

function updateAppointment ($conn, $aid, $wid, $pid, $cid, $status, $date_time){

    $sql = "UPDATE appointment set wid = " . $wid . ", pid = " . $pid . ", cid = " . $cid . ", status = '" . $status . "', date_time = '" . $date_time . "' WHERE aid = " . $aid . ";";

    if (mysqli_query($conn, $sql)){
        echo "Record updated successfully";
    }else{
        echo "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

}

function deleteAppointment($conn, $aid){
    
    $sql = "DELETE FROM treatment WHERE aid = " . $aid . ";";
    mysqli_query($conn, $sql);

    $sql = "DELETE FROM appointment WHERE aid = " . $aid . ";";

    if(mysqli_query($conn, $sql)){
        echo "Record deleted successfully";
    }else{
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

}

function getAllPatients($conn){
    
    $sql = "SELECT * FROM patient;";
    $result = mysqli_query($conn, $sql); 

    return mysqli_fetch_array($result); // value = ['pid'], name = ['name']
    
}

function queryDBA($conn, $sql){

    if (mysqli_query($conn, $sql)){
        echo "Query passed successfully";
    }else{
        echo "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

}

function retrieveAllDentists($conn){

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

    return mysqli_fetch_array($result);

}


function getAppointmentsForDentistForAWeek($conn, $wid, $beginning, $end){  

    $sql = "SELECT * 
    FROM appointment
    WHERE wid = " . $wid . " AND date_time BETWEEN '" . $beginning . "' AND '" . $end . "';";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>aid</th>";
                    echo "<th>wid</th>";
                    echo "<th>pid</th>";
                    echo "<th>cid</th>";
                    echo "<th>status</th>";
                    echo "<th>date_time</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['aid'] . "</td>";
                    echo "<td>" . $row['wid'] . "</td>";
                    echo "<td>" . $row['pid'] . "</td>";
                    echo "<td>" . $row['cid'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['date_time'] . "</td>";
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

}

function getAppointmentsForClinicForADay($conn, $cid, $beginning, $end){

    $sql = "SELECT * 
    FROM appointment
    WHERE cid = " . $cid . " AND date_time BETWEEN '" . $beginning . "' AND '" . $end . "';";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>aid</th>";
                    echo "<th>wid</th>";
                    echo "<th>pid</th>";
                    echo "<th>cid</th>";
                    echo "<th>status</th>";
                    echo "<th>date_time</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['aid'] . "</td>";
                    echo "<td>" . $row['wid'] . "</td>";
                    echo "<td>" . $row['pid'] . "</td>";
                    echo "<td>" . $row['cid'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['date_time'] . "</td>";
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

}

function getAppointmentsForPatient($conn, $pid){

    $sql = "SELECT * 
    FROM appointment
    WHERE pid = " . $pid . ";";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>aid</th>";
                    echo "<th>wid</th>";
                    echo "<th>pid</th>";
                    echo "<th>cid</th>";
                    echo "<th>status</th>";
                    echo "<th>date_time</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['aid'] . "</td>";
                    echo "<td>" . $row['wid'] . "</td>";
                    echo "<td>" . $row['pid'] . "</td>";
                    echo "<td>" . $row['cid'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>" . $row['date_time'] . "</td>";
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

}

function getMissedAppointmentForAll($conn){

    $sql = "SELECT pid, COUNT(status)
    From appointment
    WHERE status = 'missed'
    GROUP BY pid;";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>pid</th>";
                    echo "<th>number of missed apt</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['pid'] . "</td>";
                    echo "<td>" . $row['COUNT(status)'] . "</td>";
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

}

function getTreatmentDetailForAppointment($conn, $aid){

    $sql = "SELECT *
    From treatment
    WHERE aid = " . $aid . ";
    ";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>aid</th>";
                    echo "<th>wid</th>";
                    echo "<th>pid</th>";
                    echo "<th>tid</th>";
                    echo "<th>cid</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['aid'] . "</td>";
                    echo "<td>" . $row['wid'] . "</td>";
                    echo "<td>" . $row['pid'] . "</td>";
                    echo "<td>" . $row['tid'] . "</td>";
                    echo "<td>" . $row['cid'] . "</td>";
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
}

function getUnpaidBills($conn){

    $sql = "SELECT *
    FROM bill
    WHERE status = 'unpaid'";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<table>";
                echo "<tr>";
                    echo "<th>tid</th>";
                    echo "<th>cost</th>";
                    echo "<th>status</th>";
                echo "</tr>";
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    echo "<td>" . $row['tid'] . "</td>";
                    echo "<td>" . $row['cost'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
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

}

?>

