<?php 
    include 'connection.php';
//here we will add the functions

if(isset($_POST['button1'])) { // isset gets button click of button with name button1 (maybe id?)

    // Create connection
    $conn = openConnection();
    

    //TODO: SWITCH DEPENDING ON THE RESULTS
    getAllAppointment ($conn);
    
    //close connection
    closeConnection($conn);
} 

// TODO: SWITCH FOR ARRAY AT INDEX 0 (function name)
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

//QUERIES USED FOR PART 2

function addNewPatients($conn, $name){
    
    $sql = "INSERT INTO patient (name) VALUES ('" . $name . "');";

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
    $tempArr = array();
    $sql = "SELECT * FROM patient;"; 

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
                
            }
           
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    return $tempArr;
}

function getAllClinics($conn){
    $tempArr = array();
    $sql = "SELECT * FROM clinic;"; 

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
                
            }
           
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    for ($i = 0; $i< sizeof($tempArr); $i++){
        echo $tempArr[$i]['cid'] . " x---x " . $tempArr[$i]['name'] . " x---x " . $tempArr[$i]['address'] . "<br>";
    } // access array that is returned

    return $tempArr;
}

function getAllAppointment($conn){
    $tempArr = array();
    $sql = "SELECT * FROM appointment;"; 

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
                
            }
           
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    for ($i = 0; $i< sizeof($tempArr); $i++){
        echo $tempArr[$i]['aid'] . " x---x " . $tempArr[$i]['date_time'] . " x---x " . $tempArr[$i]['status'] . "<br>";
    } // access array that is returned

    return $tempArr;
}

function queryDBA($conn, $sql){ //to modify
    $tempArr = array();
    $arr = explode(' ',trim($sql));
    $keyword = $arr[0];
    if($result = mysqli_query($conn, $sql)){
        if (strcmp($keyword, "SELECT") == 0){
            if(mysqli_num_rows($result) > 0){
            

                while($row = mysqli_fetch_array($result)){
                    array_push($tempArr, $row);
                    
                }
                mysqli_free_result($result);
            } else {
                echo "No records matching your query were found.";
            }
            
           return $tempArr;
            
        } else if (strcmp($keyword, "DELETE") == 0){
            echo "Record has been successfully deleted";
        }else if (strcmp($keyword, "INSERT") == 0){
            echo "Record has been successfully added";
        }else if (strcmp($keyword, "UPDATE") == 0){
            echo "Record has been successfully updated";
        }else{
            echo "Query run successfully.";
        }

        
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }


}

//PART 1 QUERIES

function retrieveAllDentists($conn){

    $tempArr = array();

    $sql = "SELECT * FROM dentist";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
                
            }
           
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    for ($i = 0; $i< sizeof($tempArr); $i++){
        echo $tempArr[$i]['wid'] . " xx " . $tempArr[$i]['name'] . " xx " . $tempArr[$i]['cid'] . "<br>";
    }

    return $tempArr;

}


function getAppointmentsForDentistForAWeek($conn, $wid, $beginning, $end){  

    $tempArr = array();

    $sql = "SELECT * 
    FROM appointment
    WHERE wid = " . $wid . " AND date_time BETWEEN '" . $beginning . "' AND '" . $end . "';";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
            }
            
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    return $tempArr;

}

function getAppointmentsForClinicForADay($conn, $cid, $beginning, $end){
    $tempArr = array();
    $sql = "SELECT * 
    FROM appointment
    WHERE cid = " . $cid . " AND date_time BETWEEN '" . $beginning . "' AND '" . $end . "';";
    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
            }
            
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    return $tempArr;
}

function getAppointmentsForPatient($conn, $pid){

    $tempArr = array();

    $sql = "SELECT * 
    FROM appointment
    WHERE pid = " . $pid . ";";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
            }
            
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    return $tempArr;
}

function getMissedAppointmentForAll($conn){
    $tempArr = array();
    $sql = "SELECT pid, COUNT(status)
    From appointment
    WHERE status = 'missed'
    GROUP BY pid;";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
            }
            
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
    return $tempArr;

}

function getTreatmentDetailForAppointment($conn, $aid){
    $tempArry = array();
    $sql = "SELECT *
    From treatment
    WHERE aid = " . $aid . ";
    ";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
            }
            
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
    return $tempArr;
}

function getUnpaidBills($conn){
    $tempArr = array();
    $sql = "SELECT *
    FROM bill
    WHERE status = 'unpaid'";

    if($result = mysqli_query($conn, $sql)){
        if(mysqli_num_rows($result) > 0){
            
            while($row = mysqli_fetch_array($result)){
                array_push($tempArr, $row);
            }
            
            // Free result set
            mysqli_free_result($result);
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }
    return $tempArr;
}

?>

