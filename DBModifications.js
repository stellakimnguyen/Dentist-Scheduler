// TEST VALUES
const workerIDs = [
    {
        value: "1",
        text: "Isabelle Dunford",
    },
    {
        value: "2",
        text: "Emily Rowland",
    },
    {
        value: "3",
        text: "Josh Smith",
    },
    {
        value: "4",
        text: "Corey Ranford",
    }
];

// CONSTANTS
const modificationSelection = document.getElementById("modif-choice");
const modificationSection = document.getElementById("modifications");

const addPatient = document.getElementById("addPatient");
const scheduleAppointment = document.getElementById("schedApt");
const modifyAppointment = document.getElementById("modApt");
const deleteAppointment = document.getElementById("delApt");

const modifyAppointmentID = document.getElementById("modAID");
const modAptModifications = document.getElementById("modAptModifs");

// function optionExists( optionToCheck, selectElement) { // determines if option already exists in select tag
//     var optionExists = false,
//         optionsLength = selectElement.length;

//     while (optionsLength--)
//     {
//         if (selectElement.options[optionsLength].value === optionToCheck.value &&
//             selectElement.options[optionsLength].text === optionToCheck.text)
//         {
//             optionExists = true;
//             break;
//         }
//     }

//     return optionExists;
// }

// function addPoolElement(dbResults, pool) { // add options from database to pool
//     var optionsList = document.getElementById(pool).options;

//     dbResults.forEach(option => {
//         if (!optionExists(option, document.getElementById(pool))) {
//             optionsList.add(
//                 new Option(option.text, option.value)
//             )
//         } // if option already exists, do not recompile
//     });
// }

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
            addPoolElement(workerIDs, "schedWID");
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

// function getOptionText(selectedOption) { // as 'value' is associated with the tuple's ID
//     return (selectedOption.options[selectedOption.selectedIndex].text);
// }

function addNewPatient() {
    var firstName = document.getElementById("newPatientFirstName").value;
    var lastName = document.getElementById("newPatientLastName").value;
    var messageToSend = ['addNewPatients', `${firstName} ${lastName}`];
    console.log(messageToSend);
}

function scheduleNewAppointment() {
    var worker = document.getElementById("schedWID").value;
    var patient = document.getElementById("schedPID").value;
    var date = document.getElementById("schedDate").value;
    var clinic = document.getElementById("schedCID").value;
    var messageToSend = ['createNewAppointment', worker, patient, clinic, date];
    console.log(messageToSend);
}

function modifyExistingAppointment() {
    var appointment = document.getElementById("modAID").value;
    var worker = document.getElementById("modWID").value;
    var patient = document.getElementById("modPID").value;
    var status = document.getElementById("modStatus").value;
    var date = document.getElementById("modDate").value;
    var clinic = document.getElementById("modCID").value;
    var messageToSend = ['updateAppointment', worker, patient, clinic, status, date];
    console.log(messageToSend);
}

function deleteExistingAppointment() {
    var appointment = document.getElementById("delAID").value;
    var messageToSend = ["deleteAppointment", appointment];
    console.log(messageToSend);
}