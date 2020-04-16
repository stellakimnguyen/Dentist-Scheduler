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

    if (!empty($resultToDisplay)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $resultToDisplay . '"; }</script>';
    }

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

    updateAppointment($conn, $_COOKIE['aptToModify'], $parameters[1], $parameters[2], $parameters[3], $parameters[4]);
}

// DELETE APPOINTMENT
if (isset($_POST['sendModifsDeleteBtn'])) {
    $parameters = json_decode($_COOKIE['appointmentToDelete']);

    deleteAppointment($conn, $parameters[1]);
}

// QUERY DBA
if (isset($_POST['sendQueryBtn'])) {
    $parameter = json_decode($_COOKIE['DBAquery']);
    // $temp = queryDBA($conn, $parameter);
    // $resultToDisplay = "";

    // for ($i = 0; $i < sizeof($temp); $i++) {
    //     $resultToDisplay .= json_encode($temp[$i]) . "<br>";
    // }

    // //DELETE FROM `appointment` WHERE (`aid` = '8');

    // $test = json_encode($resultToDisplay);
    // echo $test;

    // echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $test . '"; }</script>';
    queryDBA($conn, $parameter);
}

// PHP VARIABLES FOR JS
$allPatients = getAllPatients($conn); 
$allDentists = retrieveAllDentists($conn); // check
$allAppointments = getAllAppointment($conn);
$allClinics = getAllClinics($conn);

class jsElement {
    public $value;
    public $text;
}

class modifiableAppointment extends jsElement {
    // public $value;
    // public $text;
    public $workerID;
    public $patientID;
    public $status;
    public $datetime;
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
        $message = "Record deleted successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    for ($i = 0; $i< sizeof($tempArr); $i++){
        ${"patient" . $i} = new jsElement();
        ${"patient" . $i} -> value = $tempArr[$i]['pid'];
        ${"patient" . $i} -> text = $tempArr[$i]['name'];
        $jsArray[$i] = ${"patient" . $i};
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    for ($i = 0; $i< sizeof($tempArr); $i++){
        // echo $tempArr[$i]['cid'] . " x---x " . $tempArr[$i]['name'] . " x---x " . $tempArr[$i]['address'] . "<br>";
    
        ${"clinic" . $i} = new jsElement();
        ${"clinic" . $i} -> value = $tempArr[$i]['cid'];
        ${"clinic" . $i} -> text = $tempArr[$i]['name'];
        $jsArray[$i] = ${"clinic" . $i};
    } // access array that is returned

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
    }

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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    // for ($i = 0; $i< sizeof($tempArr); $i++){
    //     echo $tempArr[$i]['aid'] . " x---x " . $tempArr[$i]['date_time'] . " x---x " . $tempArr[$i]['status'] . "<br>";
    // } // access array that is returned

    for ($i = 0; $i< sizeof($tempArr); $i++){
        ${"appointment" . $i} = new jsElement();
        ${"appointment" . $i} -> value = $tempArr[$i]['aid'];
        ${"appointment" . $i} -> text = 'Appointment ' . $tempArr[$i]['aid'];
        ${"appointment" . $i} -> workerID = $tempArr[$i]['wid'];
        ${"appointment" . $i} -> patientID = $tempArr[$i]['pid'];
        ${"appointment" . $i} -> status = $tempArr[$i]['status'];
        ${"appointment" . $i} -> datetime = $tempArr[$i]['date_time'];
        $jsArray[$i] = ${"appointment" . $i};
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            
                // return $tempArr;
                displaySelect($tempArr);
            } else {
                $message = "No records matching your query were found.";
            }
            
        } else if (strcmp($keyword, "DELETE") == 0){
            $message = "Record has been successfully deleted";
        } else if (strcmp($keyword, "INSERT") == 0){
            $message = "Record has been successfully added";
        } else if (strcmp($keyword, "UPDATE") == 0){
            $message = "Record has been successfully updated";
        } else {
            $message = "Query run successfully.";
        }
        
    } else {
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
    }

}

function displaySelect($resultArray) {
    $resultToDisplay = "";

    for ($i = 0; $i < sizeof($resultArray); $i++) {
        $resultToDisplay .= json_encode($resultArray[$i]) . "<br>";
    }

    // $test = print_r($resultArray, true);
    $test = '("'.implode('", "', (array)$resultToDisplay).'")';
    echo $test;
    echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $test . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    for ($i = 0; $i< sizeof($tempArr); $i++){
        // echo $tempArr[$i]['wid'] . $tempArr[$i]['name'];
        ${"dentist" . $i} = new jsElement();
        ${"dentist" . $i} -> value = $tempArr[$i]['wid'];
        ${"dentist" . $i} -> text = $tempArr[$i]['name'];

        $jsArray[$i] = ${"dentist" . $i};
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    // for ($i = 0; $i< sizeof($tempArr); $i++){
    //     echo $tempArr[$i]['wid'] . " xx " . $tempArr[$i]['name'] . " xx " . $tempArr[$i]['cid'] . "<br>";
    // }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
        } else {
            $message = "No records matching your query were found.";
        }
    } else {
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    // for ($i = 0; $i < sizeof($tempArr); $i++){
    //     echo $tempArr[$i]['aid'];
    // }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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
            $message = "No records matching your query were found.";
        }
    } else{
        $message = "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
    }

    if (isset($message)) {
        echo '<script type="text/javascript">window.onload = function() { document.getElementById("result").innerHTML = "' . $message . '"; }</script>';
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