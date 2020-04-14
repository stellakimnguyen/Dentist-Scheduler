// CONSTANTS
const modificationSelection = document.getElementById("modif-choice");
const modificationSection = document.getElementById("modifications");

const addPatient = document.getElementById("addPatient");
const scheduleAppointment = document.getElementById("schedApt");
const modifyAppointment = document.getElementById("modApt");
const deleteAppointment = document.getElementById("delApt");

const modifyAppointmentID = document.getElementById("modAID");
const modAptModifications = document.getElementById("modAptModifs");

// VARIABLES
var dbModifToSend;

function triggerModifSection() {
    if (modificationSelection.value) {
        modificationSection.classList.toggle("hidden", false);
    }
    
    switch(modificationSelection.value) {
        case "Add Patient":
            addPatient.classList.toggle("hidden", false);
            scheduleAppointment.classList.toggle("hidden", true);
            modifyAppointment.classList.toggle("hidden", true);
            deleteAppointment.classList.toggle("hidden", true);
            break;
        case "Schedule Appointment":
            addPoolElement(dentists, "schedWID");
            addPoolElement(patients, "schedPID");
            addPoolElement(clinics, "schedCID");
            addPatient.classList.toggle("hidden", true);
            scheduleAppointment.classList.toggle("hidden", false);
            modifyAppointment.classList.toggle("hidden", true);
            deleteAppointment.classList.toggle("hidden", true);
            break;
        case "Modify Appointment":
            addPoolElement(appointments, "modAID");
            addPoolElement(workerIDs, "modWID");
            addPoolElement(patients, "modPID");
            addPoolElement(clinics, "modCID");

            if (modifyAppointmentID.value) {
                modAptModifications.classList.toggle("hidden", false);
            }

            addPatient.classList.toggle("hidden", true);
            scheduleAppointment.classList.toggle("hidden", true);
            modifyAppointment.classList.toggle("hidden", false);
            deleteAppointment.classList.toggle("hidden", true);
            break;
        case "Delete Appointment":
            addPoolElement(appointments, "delAID");
            addPatient.classList.toggle("hidden", true);
            scheduleAppointment.classList.toggle("hidden", true);
            modifyAppointment.classList.toggle("hidden", true);
            deleteAppointment.classList.toggle("hidden", false);
            break;
    }
}

function addNewPatient() {
    var firstName = document.getElementById("newPatientFirstName").value;
    var lastName = document.getElementById("newPatientLastName").value;
    var messageToSend= ['addNewPatients', `${firstName} ${lastName}`];
    console.log(messageToSend);
    document.cookie = `newPatient=${JSON.stringify(messageToSend)}`;
}

function scheduleNewAppointment() {
    var worker = document.getElementById("schedWID").value;
    var patient = document.getElementById("schedPID").value;
    var date = document.getElementById("schedDate").value;
    var clinic = document.getElementById("schedCID").value;
    var messageToSend = ['createNewAppointment', worker, patient, clinic, date];
    document.cookie = `newAppointment=${JSON.stringify(messageToSend)}`;
    
    alert(document.cookie);
}

function modifyExistingAppointment() {
    var appointment = document.getElementById("modAID").value;
    var worker = document.getElementById("modWID").value;
    var patient = document.getElementById("modPID").value;
    var status = document.getElementById("modStatus").value;
    var date = document.getElementById("modDate").value;
    var clinic = document.getElementById("modCID").value;
    var messageToSend = ['updateAppointment', worker, patient, clinic, status, date];
    document.cookie = `modifiedAppointment=${JSON.stringify(messageToSend)}`;
    console.log(messageToSend);
}

function deleteExistingAppointment() {
    var appointment = document.getElementById("delAID").value;
    var messageToSend = ["deleteAppointment", appointment];
    document.cookie = `no=${JSON.stringify(messageToSend)}`;
    console.log(messageToSend);
}

function test() {
    console.log('clicked!');
}