<?php 
    include 'connection.php';
//here we will add the functions

if(isset($_POST['button1'])) { // isset gets button click of button with name button1 (maybe id?)

    // Create connection
    $conn = openConnection();

    echo "HELLO";

    //TODO: SWITCH DEPENDING ON THE RESULTS
    getAllAppointment ($conn);
    
    //close connection
    closeConnection($conn);
}

echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . "Awaiting query submission" . '"; }</script>';

$receivingResult = "";

$conn = openConnection();

if (isset($_POST['sendRequestInputBtn'])) {
    $parameters = json_decode($_COOKIE['name']);
    $resultToDisplay = "";

    switch($parameters[0]) {
        case "getAllDentistsFromAllClinics":
            $temp = getAllDentistsFromAllClinics($conn);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .= $temp[$i]['wid'] . "\t " . $temp[$i]['name'] . "\t" . $temp[$i]['cid'] . "<br>";
            }
            break;
        case "getAppointmentsForDentistForAWeek":
            $temp = getAppointmentsForDentistForAWeek($conn, $parameters[1], $parameters[2], $parameters[3]);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .="Appointment " . $temp[$i]['aid'] . " on " . $temp[$i]['date_time'] . "<br>";
            }
            break;
        case "getAppointmentsForClinicForADay":
            $temp = getAppointmentsForClinicForADay($conn, $parameters[1], $parameters[2], $parameters[3]);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .= "Appointment " . $temp[$i]['aid'] . " on " . $temp[$i]['date_time'] . "<br>";
            }
            break;
        case "getAppointmentsForPatient":
            $temp = getAppointmentsForPatient($conn, $parameters[1]);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .= "Appointment " . $temp[$i]['aid'] . " on " . $temp[$i]['date_time'] . "<br>";
            }
            break;
        case "getMissedAppointmentsForAll":
            $temp = getMissedAppointmentForAll($conn);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .= "Patient " . $temp[$i]['pid'] . " missed " . $temp[$i]['COUNT(status)'] . " appointment(s)" . "<br>";
            }
            break;
        //NOT WORKING
        case "getTreatmentDetailForAppointment":
            $temp = getTreatmentDetailForAppointment($conn, $parameters[1]);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .= "Treatment " . $temp[$i]['tid']. " was given in Appointment " . $parameters[1] . "<br>"; //how to get count
            }
            break;
        case "getUnpaidBills":
            $temp = getUnpaidBills($conn);
            for ($i = 0; $i < sizeof($temp); $i++){
                $resultToDisplay .= "Bill " .( $i + 1) . " costs " . $temp[$i]['cost']. "<br>"; 
            }
            break;
            
        default:
            $resultToDisplay = "Awaiting query submission";           
    }

    echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $resultToDisplay . '"; }</script>';

}

// ADDING NEW PATIENT
if (isset($_POST['sendModifsAddPatientBtn'])) {
    $parameters = json_decode($_COOKIE['newPatient']);

    addNewPatients($conn, $parameters[1]);
}

// ADDING NEW APPOINTMENT
if (isset($_POST['sendModifsNewSchedBtn'])) {
    $parameters = json_decode($_COOKIE['newAppointment']);

    createNewAppointment($conn, $parameters[1], $parameters[2], $parameters[3]);
}

// MODIFY APPOINTMENT
// Get appointment to modify
if (isset($_POST['sendModifsModifyAptBtn'])) {
    $parameters = json_decode($_COOKIE['modifiedAppointment']);

    //updateAppointment($conn, $parameters[0], $parameters[1], $parameters[2], $parameters[3], $parameters[4]);
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

// PHP VARIABLES FOR JS
$allPatients = getAllPatients($conn); 
$allDentists = retrieveAllDentists($conn); // check
$allAppointments = getAllAppointment($conn);
$allClinics = getAllClinics($conn);

class jsElement {
    public $value;
    public $text;
}

//QUERIES USED FOR PART 2

function addNewPatients($conn, $name){
    
    $sql = "INSERT INTO patient (name) VALUES ('" . $name . "');";

    if (mysqli_query($conn, $sql)){
        $message = "New record created successfully";
    } else {
        $message = "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

    echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
}

function createNewAppointment($conn, $wid, $pid, $date){
    
    if (strlen($wid) == 0){
        $sql = "INSERT INTO appointment (pid,date_time) VALUES (" . $pid . ",'" . $date . "');";
    } else {
        $sql = "INSERT INTO appointment (wid,pid,date_time) VALUES (" . $wid . ", " . $pid . ",'" . $date . "');";
    }

    if (mysqli_query($conn, $sql)){
        $message =  "New record created successfully";
    } else {
        $message =  "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

    echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
}

function selectAppointment($conn, $aid){

    $sql = "SELECT * FROM appointment WHERE aid = " . $aid . ";";

    $result = mysqli_fetch_array(mysqli_query($conn, $sql));

    //echo $result['wid'] . " - " . $result['pid'] . " - " . $result['cid'] . " - " . $result['status'] . " - " . $result['date_time'];


    return $result;

}

function updateAppointment ($conn, $aid, $wid, $pid, $status, $date_time){

    $sql = "UPDATE appointment set wid = " . $wid . ", pid = " . $pid . ", status = '" . $status . "', date_time = '" . $date_time . "' WHERE aid = " . $aid . ";";

    if (mysqli_query($conn, $sql)){
        $message = "Record updated successfully";
    } else {
        $message = "Error:" . $sql . "<br>" . mysqli_error($conn);
    }

    echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
    $jsArray = array();
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

    for ($i = 0; $i< sizeof($tempArr); $i++){
        // echo $tempArr[$i]['pid'] . $tempArr[$i]['name'];

        ${"patient" . $i} = new jsElement();
        ${"patient" . $i} -> value = $tempArr[$i]['pid'];
        ${"patient" . $i} -> text = $tempArr[$i]['name'];
        $jsArray[$i] = ${"patient" . $i};
    }

    return $jsArray;
}

function getAllClinics($conn){
    $tempArr = array();
    $jsArray = array();
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
        // echo $tempArr[$i]['cid'] . " x---x " . $tempArr[$i]['name'] . " x---x " . $tempArr[$i]['address'] . "<br>";
    
        ${"clinic" . $i} = new jsElement();
        ${"clinic" . $i} -> value = $tempArr[$i]['cid'];
        ${"clinic" . $i} -> text = $tempArr[$i]['name'];
        $jsArray[$i] = ${"clinic" . $i};
    } // access array that is returned

    return $jsArray;
}

function getAllAppointment($conn){
    $tempArr = array();
    $jsArray = array();
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

    // for ($i = 0; $i< sizeof($tempArr); $i++){
    //     echo $tempArr[$i]['aid'] . " x---x " . $tempArr[$i]['date_time'] . " x---x " . $tempArr[$i]['status'] . "<br>";
    // } // access array that is returned

    for ($i = 0; $i< sizeof($tempArr); $i++){
        ${"appointment" . $i} = new jsElement();
        ${"appointment" . $i} -> value = $tempArr[$i]['aid'];
        ${"appointment" . $i} -> text = 'Appointment ' . $tempArr[$i]['aid'];
        $jsArray[$i] = ${"appointment" . $i};
    }

    return $jsArray;
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
    $jsArray = array();

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
        // echo $tempArr[$i]['wid'] . $tempArr[$i]['name'];
        ${"dentist" . $i} = new jsElement();
        ${"dentist" . $i} -> value = $tempArr[$i]['wid'];
        ${"dentist" . $i} -> text = $tempArr[$i]['name'];

        $jsArray[$i] = ${"dentist" . $i};
    }

    return $jsArray;

}

function getAllDentistsFromAllClinics($conn) {
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

    // for ($i = 0; $i< sizeof($tempArr); $i++){
    //     echo $tempArr[$i]['wid'] . " xx " . $tempArr[$i]['name'] . " xx " . $tempArr[$i]['cid'] . "<br>";
    // }

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
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    // for ($i = 0; $i < sizeof($tempArr); $i++){
    //     echo $tempArr[$i]['aid'];
    // }

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
    $tempArr = array();
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

<script type="text/javascript">
    setSpecificsArrays('patients', '<?php echo json_encode($allPatients); ?>');
    setSpecificsArrays('appointments', '<?php echo json_encode($allAppointments); ?>');
    setSpecificsArrays('dentists','<?php echo json_encode($allDentists); ?>');
    setSpecificsArrays('clinics', '<?php echo json_encode($allClinics); ?>');
</script>