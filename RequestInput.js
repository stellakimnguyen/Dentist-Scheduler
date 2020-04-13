// TEST VALUES
// const patients = [
//     {
//         "value": "1",
//         "text": "Maryam Benadada",
//     },
//     {
//         "value": "2",
//         "text": "Stella Nguyen",
//     },
//     {
//         "value": "3",
//         "text": "Finn Davidson",
//     },
// ];
// const dentists = [
//     {
//         value: "1",
//         text: 'Kim Trang'
//     },
//     {
//         value: "2",
//         text: 'Manh Cuong'
//     },
//     {
//         value: "3",
//         text: 'Kim Chi'
//     },
//     {
//         value: "4",
//         text: 'Giao Trinh'
//     },
//     {
//         value: "5",
//         text: 'Bao Nguyen'
//     },
// ];
// const clinics = [
//     {
//         value: "1",
//         text: "Complexe Desjardins"
//     },
//     {
//         value: "2",
//         text: "Côte-des-Neiges"
//     },
//     {
//         value: "3",
//         text: "Rockland Center"
//     },
//     {
//         value: "4",
//         text: "Le Riverain"
//     },
// ];
// const appointments = [
//     {
//         value: "1",
//         text: "Appointment 1"
//     },
//     {
//         value: "2",
//         text: "Appointment 2"
//     },
//     {
//         value: "3",
//         text: "Appointment 3"
//     },
//     {
//         value: "4",
//         text: "Appointment 4"
//     },
//     {
//         value: "5",
//         text: "Appointment 5"
//     },
//     {
//         value: "6",
//         text: "Appointment 6"
//     },
// ];

// CONSTANTS
// var patients;
var dentists;
var clinics;
var appointments;

const patientSelection = document.getElementById("patientSelection");
const dentistSelection = document.getElementById("dentistSelection");
const clinicSelection = document.getElementById("clinicSelection");
const appointmentSelection = document.getElementById("appointmentSelection");
const timeForm = document.getElementById("timeSelection");
const onSpecificPeriod = document.getElementById("onSpecificPeriod");
const additionalDetails = document.getElementById("additionalDetails");
const amount = document.getElementById("number");

const requestPreview = document.getElementById("stringRequest");
const weekForm = document.getElementById("week");
const dayForm = document.getElementById("day");

const info = document.getElementById('info');
const type = document.getElementById('type');
const number = document.getElementById('number');
const pool = document.getElementById('pool');
const date = document.getElementById('date');

// REQUEST VARIABLES
var selectedPoolID;
var firstDayOfWeek;
var lastDayOfWeek;
var chosenPool;

function optionExists( optionToCheck, selectElement) { // determines if option already exists in select tag
    var optionExists = false,
        optionsLength = selectElement.length;

    while (optionsLength--)
    {
        if (selectElement.options[optionsLength].value === optionToCheck.value &&
            selectElement.options[optionsLength].text === optionToCheck.text)
        {
            optionExists = true;
            break;
        }
    }

    return optionExists;
}

function addPoolElement(dbResults, pool) { // add options from database to pool
    var optionsList = document.getElementById(pool).options;

    console.log(dbResults);

    dbResults.forEach(option => {
        if (!optionExists(option, document.getElementById(pool))) {
            optionsList.add(
                new Option(option.text, option.value)
            )
        } // if option already exists, do not recompile
    });
}

function triggerAdditionalSection() {
    if (amount.value === "number") {
        additionalDetails.classList.toggle('hidden', false);
    } else {
        additionalDetails.classList.toggle('hidden', true);
    }
}

function additionalPoolDetails() {
    var number = amount.value;
    var selectedPool = document.getElementById('pool').value;

    if (number === "given") {
        additionalDetails.classList.toggle('hidden', false);
        
        switch(selectedPool) {
            case "patient":
                addPoolElement(patients, "patient");
                patientSelection.classList.toggle('hidden', false);
                dentistSelection.classList.toggle('hidden', true);
                clinicSelection.classList.toggle('hidden', true);
                appointmentSelection.classList.toggle('hidden', true);
                onSpecificPeriod.classList.toggle('hidden', false);
                break;
            case "dentist(s)":
                addPoolElement(dentists, "dentist");
                patientSelection.classList.toggle('hidden', true);
                dentistSelection.classList.toggle('hidden', false);
                clinicSelection.classList.toggle('hidden', true);
                appointmentSelection.classList.toggle('hidden', true);
                onSpecificPeriod.classList.toggle('hidden', false);
                break;
            case "clinic(s)":
                addPoolElement(clinics, "clinic");
                patientSelection.classList.toggle('hidden', true);
                dentistSelection.classList.toggle('hidden', true);
                clinicSelection.classList.toggle('hidden', false);
                appointmentSelection.classList.toggle('hidden', true);
                onSpecificPeriod.classList.toggle('hidden', false);
                break;
            case "appointment":
                addPoolElement(appointments, "appointment");
                patientSelection.classList.toggle('hidden', true);
                dentistSelection.classList.toggle('hidden', true);
                clinicSelection.classList.toggle('hidden', true);
                appointmentSelection.classList.toggle('hidden', false);
                onSpecificPeriod.classList.toggle('hidden', true);
                timeForm.classList.toggle('hidden', true);
                break;
        }
    } else {
        additionalDetails.classList.toggle('hidden', true);
        // onSpecificPeriod.classList.toggle('hidden', true);
        // TO DO: Only enable all days option for time period when !given
    }
}

function additionalDateDetails() {
    var timePeriod = date.value; // specific date type (week/date)

    if (!(timePeriod === "all days")) {
        additionalDetails.classList.toggle('hidden', false);
        timeForm.classList.toggle("hidden", false);

        switch(timePeriod) {
            case "specific week":
                weekForm.classList.toggle('hidden', false);
                dayForm.classList.toggle('hidden', true);
                break;
            case "specific date":
                weekForm.classList.toggle('hidden', true);
                dayForm.classList.toggle('hidden', false);
                break;
        }
    } else {
        timeForm.classList.toggle("hidden", true);
    }
}

function getWeekDates(weekNumber) {
    // example format of parameter: 2020-W15
    var year = weekNumber.substr(0, weekNumber.indexOf('-')); 
    var week = weekNumber.substr(weekNumber.lastIndexOf("-") + 2); // +2 to ignore the W character

    var d = new Date("Jan 01, " + year + " 00:00:00"); // start calculations with new year
    var w = d.getTime() + 604800000 * (week - 1) - (d.getDay() - 1) * 86400000;
    //new years time value + (one week ms * week index)  - (day of week for jan 1st + 1) * one day ms
    //'one week ms * week index' is to calculate chosen week
    //'day of for jan 1st + 1' is to make the week start on a Monday
    
    var weekStart = new Date(w);
    var weekEnd = new Date(w + 518400000) // add six days in ms for end of week

    weekStart = weekStart.toISOString(); // convert to ISO date format
    weekEnd = weekEnd.toISOString();

    firstDayOfWeek = weekStart.substr(0, weekStart.indexOf('T'));
    lastDayOfWeek = weekEnd.substr(0, weekStart.indexOf('T'));

    return (firstDayOfWeek + " to " + lastDayOfWeek); // return ISO format for SQL
}

function getOptionText(selectedOption) { // as 'value' is associated with the tuple's ID
    return (selectedOption.options[selectedOption.selectedIndex].text);
}

function computePreview(specifics, selected) {
    var userRequest;
    chosenPool = selected;
    
    if (specifics) { // if *given* was chosen (i.e. asking for specific element)
        numberValue = "";
    } else {
        infoValue = info.value;
        typeValue = type.value;
        numberValue = number.value;
        poolValue = pool.value;
        dateValue = date.value;
    }

    switch(specifics) { // if specific attribute, use text instead of value (which is associated with ID)
        case "patient":
        case "dentist":
        case "clinic":
            poolValue = getOptionText(selected);
            selectedPoolID = selected.value;
            break; 
        case "appointment":
            poolValue = getOptionText(selected);
            selectedPoolID = selected.value;
            dateValue = "appointed date";
            break;
        case "week":
            dateValue = getWeekDates(weekForm.value);
            break;
        case "day":
            dateValue = dayForm.value;
    }

    userRequest = `Get ${infoValue} of all ${typeValue} for ${numberValue} ${poolValue} on ${dateValue}`;
    requestPreview.innerHTML = userRequest;
}

function sendRequest() {
    var messageToSend;
    var toSend;
    
    if (type.value !== "appointments") {
        switch(type.value) {
            case "dentists":
                toSend = ["retrieveAllDentists"];
                break;
            case "missed appointments":
                toSend = ["getMissedAppointmentsForAll"];
                break;
            case "treatments":
                toSend = ["getTreatmentDetailForAppointment", selectedPoolID];
                break;
                // TO DO: Consider AID
            case "unpaid bills":
                toSend = ["getUnpaidBills"];
                break;
            }
    } else {
        console.log(pool.value);
        switch (pool.value) {
            case "patient":
                toSend = ["getAppointmentsForPatient", selectedPoolID];
                break;
            case "dentist(s)":
                toSend = ["getAppointmentsForDentistForAWeek", selectedPoolID, firstDayOfWeek, lastDayOfWeek];
                break;
            case "clinic(s)":
                toSend = ["getAppointmentsForClinicForADay", selectedPoolID, `${dayForm.value} 00:00:00`, `${dayForm.value} 23:59:59`];
                break;
        }
    }
    
    messageToSend = toSend;
    console.log(messageToSend);
}

function setSpecificsArrays(specifics, array) {
    switch (specifics) {
        case "patients":
            patients = (JSON.parse(array));
            break;
        case "appointments":
            appointments = JSON.parse(array);
            break;
        case "dentists":
            dentists = (JSON.parse(array));
            break;
        case "clinics":
            clinics = (JSON.parse(array));
            break;
    }
}