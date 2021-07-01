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
            addPatient.classList.toggle("hidden", true);
            scheduleAppointment.classList.toggle("hidden", false);
            modifyAppointment.classList.toggle("hidden", true);
            deleteAppointment.classList.toggle("hidden", true);
            break;
        case "Modify Appointment":
            addPoolElement(appointments, "modAID");
            addPoolElement(dentists, "modWID"); // worker IDs
            addPoolElement(patients, "modPID");

            if (modifyAppointmentID.value) {
                modAptModifications.classList.toggle("hidden", false);
            }

            document.cookie = `aptToModify=${modifyAppointmentID.value}`;

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

function updateNextSelections() { // fill in select optiosn with values from chosen appointment
    document.getElementById('modWID').value = appointments[modifyAppointmentID.value - 1]['workerID'];
    document.getElementById('modPID').value = appointments[modifyAppointmentID.value - 1]['patientID'];
    document.getElementById('modStatus').value = appointments[modifyAppointmentID.value - 1]['status'];
    document.getElementById('modDate').value = appointments[modifyAppointmentID.value - 1]['datetime'].replace(" ", "T");
}

function addNewPatient() {
    var firstName = document.getElementById("newPatientFirstName").value;
    var lastName = document.getElementById("newPatientLastName").value;
    var messageToSend= ['addNewPatients', `${firstName} ${lastName}`];
    document.cookie = `newPatient=${JSON.stringify(messageToSend)}`;
}

function scheduleNewAppointment() {
    var worker = document.getElementById("schedWID").value;
    var patient = document.getElementById("schedPID").value;
    var date = document.getElementById("schedDate").value.replace('T', ' ');
    var messageToSend = ['createNewAppointment', worker, patient, date];
    document.cookie = `newAppointment=${JSON.stringify(messageToSend)}`;
}

function modifyExistingAppointment() {
    var appointment = document.getElementById("modAID").value;
    var worker = document.getElementById("modWID").value;
    var patient = document.getElementById("modPID").value;
    var status = document.getElementById("modStatus").value;
    var date = document.getElementById("modDate").value.replace('T', ' ');
    var messageToSend = ['updateAppointment', worker, patient, status, date];
    document.cookie = `modifiedAppointment=${JSON.stringify(messageToSend)}`;
}

function deleteExistingAppointment() {
    var appointment = document.getElementById("delAID").value;
    var messageToSend = ["deleteAppointment", appointment];
    document.cookie = `appointmentToDelete=${JSON.stringify(messageToSend)}`;
}