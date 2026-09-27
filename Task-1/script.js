/* =========================================================
   MEDICARE HEALTH CENTRE
   Task 1 - Healthcare / Clinic Landing Page
   JavaScript
   ========================================================= */


/* =========================================================
   1. MOBILE NAVIGATION
   ========================================================= */

const menuToggle =
    document.querySelector(".menu-toggle");

const navigation =
    document.querySelector(".main-navigation");


if (menuToggle && navigation) {

    menuToggle.addEventListener(
        "click",
        function () {

            const isOpen =
                navigation.classList.toggle("open");

            menuToggle.setAttribute(
                "aria-expanded",
                isOpen
            );

        }
    );


    // Close menu after selecting a link

    const navigationLinks =
        document.querySelectorAll(".nav-link");


    navigationLinks.forEach(
        function (link) {

            link.addEventListener(
                "click",
                function () {

                    navigation.classList.remove("open");

                    menuToggle.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                }
            );

        }
    );

}


/* =========================================================
   2. APPOINTMENT DATE
   ========================================================= */

const appointmentDate =
    document.getElementById(
        "appointmentDate"
    );


let minimumDate = "";


if (appointmentDate) {

    const today = new Date();

    const year =
        today.getFullYear();

    const month =
        String(
            today.getMonth() + 1
        ).padStart(2, "0");

    const day =
        String(
            today.getDate()
        ).padStart(2, "0");


    minimumDate =
        `${year}-${month}-${day}`;


    // Do not allow past dates

    appointmentDate.min =
        minimumDate;

}


/* =========================================================
   3. APPOINTMENT FORM
   ========================================================= */

const appointmentForm =
    document.getElementById(
        "appointmentForm"
    );


const formMessage =
    document.getElementById(
        "formMessage"
    );


if (appointmentForm) {

    appointmentForm.addEventListener(
        "submit",
        function (event) {

            // Stop page refresh

            event.preventDefault();


            // Remove old errors

            clearErrors();


            // Get values

            const fullName =
                document
                    .getElementById("fullName")
                    .value
                    .trim();


            const email =
                document
                    .getElementById("email")
                    .value
                    .trim();


            const phone =
                document
                    .getElementById("phone")
                    .value
                    .trim();


            const department =
                document
                    .getElementById("department")
                    .value;


            const selectedDate =
                document
                    .getElementById("appointmentDate")
                    .value;


            const consent =
                document
                    .getElementById("consent")
                    .checked;


            let isValid = true;


            /* ---------------------------------------------
               NAME
               --------------------------------------------- */

            if (fullName === "") {

                showError(
                    "fullName",
                    "Please enter your full name."
                );

                isValid = false;

            }
            else if (fullName.length < 3) {

                showError(
                    "fullName",
                    "Name must contain at least 3 characters."
                );

                isValid = false;

            }
            else if (
                !/^[A-Za-z\s.'-]+$/.test(fullName)
            ) {

                showError(
                    "fullName",
                    "Please enter a valid name."
                );

                isValid = false;

            }


            /* ---------------------------------------------
               EMAIL
               --------------------------------------------- */

            if (email === "") {

                showError(
                    "email",
                    "Please enter your email address."
                );

                isValid = false;

            }
            else if (!isValidEmail(email)) {

                showError(
                    "email",
                    "Please enter a valid email address."
                );

                isValid = false;

            }


            /* ---------------------------------------------
               PHONE
               --------------------------------------------- */

            if (phone === "") {

                showError(
                    "phone",
                    "Please enter your phone number."
                );

                isValid = false;

            }
            else if (!isValidPhone(phone)) {

                showError(
                    "phone",
                    "Please enter a valid phone number."
                );

                isValid = false;

            }


            /* ---------------------------------------------
               DEPARTMENT
               --------------------------------------------- */

            if (department === "") {

                showError(
                    "department",
                    "Please select a department."
                );

                isValid = false;

            }


            /* ---------------------------------------------
               DATE
               --------------------------------------------- */

            if (selectedDate === "") {

                showError(
                    "appointmentDate",
                    "Please select a preferred date."
                );

                isValid = false;

            }
            else if (
                isDateInPast(selectedDate)
            ) {

                showError(
                    "appointmentDate",
                    "Please select today or a future date."
                );

                isValid = false;

            }


            /* ---------------------------------------------
               CONSENT
               --------------------------------------------- */

            if (!consent) {

                const consentError =
                    document.getElementById(
                        "consentError"
                    );


                consentError.textContent =
                    "Please confirm that the information is accurate.";


                isValid = false;

            }


            /* ---------------------------------------------
               RESULT
               --------------------------------------------- */

            if (isValid) {

                formMessage.textContent =
                    "Your appointment enquiry has been submitted successfully.";


                formMessage.className =
                    "form-message success";


                // Reset form

                appointmentForm.reset();


                // Keep date restriction

                if (appointmentDate) {

                    appointmentDate.min =
                        minimumDate;

                }

            }
            else {

                formMessage.textContent =
                    "Please correct the highlighted fields and try again.";


                formMessage.className =
                    "form-message error";

            }

        }
    );

}


/* =========================================================
   4. EMAIL VALIDATION
   ========================================================= */

function isValidEmail(email) {

    const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return emailPattern.test(email);

}


/* =========================================================
   5. PHONE VALIDATION
   ========================================================= */

function isValidPhone(phone) {

    const phonePattern =
        /^\+?[0-9\s-]{10,15}$/;

    return phonePattern.test(phone);

}


/* =========================================================
   6. DATE VALIDATION
   ========================================================= */

function isDateInPast(dateValue) {

    const selectedDate =
        new Date(
            dateValue + "T00:00:00"
        );


    const today =
        new Date();


    today.setHours(
        0,
        0,
        0,
        0
    );


    return selectedDate < today;

}


/* =========================================================
   7. SHOW ERROR
   ========================================================= */

function showError(
    fieldId,
    message
) {

    const field =
        document.getElementById(
            fieldId
        );


    const error =
        document.getElementById(
            fieldId + "Error"
        );


    if (field) {

        field.classList.add(
            "input-error"
        );

    }


    if (error) {

        error.textContent =
            message;

    }

}


/* =========================================================
   8. CLEAR ERRORS
   ========================================================= */

function clearErrors() {

    const fields = [

        "fullName",
        "email",
        "phone",
        "department",
        "appointmentDate"

    ];


    fields.forEach(
        function (fieldId) {

            const field =
                document.getElementById(
                    fieldId
                );


            const error =
                document.getElementById(
                    fieldId + "Error"
                );


            if (field) {

                field.classList.remove(
                    "input-error"
                );

            }


            if (error) {

                error.textContent = "";

            }

        }
    );


    const consentError =
        document.getElementById(
            "consentError"
        );


    if (consentError) {

        consentError.textContent = "";

    }


    if (formMessage) {

        formMessage.textContent = "";

        formMessage.className =
            "form-message";

    }

}


/* =========================================================
   9. CLEAR ERROR WHILE TYPING
   ========================================================= */

const formFields =
    document.querySelectorAll(
        "#appointmentForm input, " +
        "#appointmentForm select, " +
        "#appointmentForm textarea"
    );


formFields.forEach(
    function (field) {

        field.addEventListener(
            "input",
            function () {

                field.classList.remove(
                    "input-error"
                );


                const error =
                    document.getElementById(
                        field.id + "Error"
                    );


                if (error) {

                    error.textContent = "";

                }

            }
        );


        field.addEventListener(
            "change",
            function () {

                field.classList.remove(
                    "input-error"
                );


                const error =/* MediCare Health Centre - Task 1 JavaScript */


/* MOBILE NAVIGATION */

const menuToggle = document.querySelector(".menu-toggle");
const navigation = document.querySelector(".main-navigation");

if (menuToggle && navigation) {

    menuToggle.addEventListener("click", function () {

        const isOpen =
            navigation.classList.toggle("open");

        menuToggle.setAttribute(
            "aria-expanded",
            isOpen
        );

    });


    const navigationLinks =
        document.querySelectorAll(".nav-link");

    navigationLinks.forEach(function (link) {

        link.addEventListener("click", function () {

            navigation.classList.remove("open");

            menuToggle.setAttribute(
                "aria-expanded",
                "false"
            );

        });

    });

}


/* APPOINTMENT DATE */

const appointmentDate =
    document.getElementById("appointmentDate");

let minimumDate = "";

if (appointmentDate) {

    const today = new Date();

    const year =
        today.getFullYear();

    const month =
        String(
            today.getMonth() + 1
        ).padStart(2, "0");

    const day =
        String(
            today.getDate()
        ).padStart(2, "0");

    minimumDate =
        `${year}-${month}-${day}`;

    appointmentDate.min =
        minimumDate;

}


/* APPOINTMENT FORM */

const appointmentForm =
    document.getElementById("appointmentForm");

const formMessage =
    document.getElementById("formMessage");


if (appointmentForm) {

    appointmentForm.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();

            clearErrors();


            const fullName =
                document
                    .getElementById("fullName")
                    .value
                    .trim();

            const email =
                document
                    .getElementById("email")
                    .value
                    .trim();

            const phone =
                document
                    .getElementById("phone")
                    .value
                    .trim();

            const department =
                document
                    .getElementById("department")
                    .value;

            const selectedDate =
                document
                    .getElementById("appointmentDate")
                    .value;

            const consent =
                document
                    .getElementById("consent")
                    .checked;


            let isValid = true;


            /* FULL NAME */

            if (fullName === "") {

                showError(
                    "fullName",
                    "Please enter your full name."
                );

                isValid = false;

            }
            else if (fullName.length < 3) {

                showError(
                    "fullName",
                    "Name must contain at least 3 characters."
                );

                isValid = false;

            }
            else if (
                !/^[A-Za-z\s.'-]+$/.test(fullName)
            ) {

                showError(
                    "fullName",
                    "Please enter a valid name."
                );

                isValid = false;

            }


            /* EMAIL */

            if (email === "") {

                showError(
                    "email",
                    "Please enter your email address."
                );

                isValid = false;

            }
            else if (!isValidEmail(email)) {

                showError(
                    "email",
                    "Please enter a valid email address."
                );

                isValid = false;

            }


            /* PHONE */

            if (phone === "") {

                showError(
                    "phone",
                    "Please enter your phone number."
                );

                isValid = false;

            }
            else if (!isValidPhone(phone)) {

                showError(
                    "phone",
                    "Please enter a valid phone number."
                );

                isValid = false;

            }


            /* DEPARTMENT */

            if (department === "") {

                showError(
                    "department",
                    "Please select a department."
                );

                isValid = false;

            }


            /* DATE */

            if (selectedDate === "") {

                showError(
                    "appointmentDate",
                    "Please select a preferred date."
                );

                isValid = false;

            }
            else if (
                isDateInPast(selectedDate)
            ) {

                showError(
                    "appointmentDate",
                    "Please select today or a future date."
                );

                isValid = false;

            }


            /* CONSENT */

            if (!consent) {

                document.getElementById(
                    "consentError"
                ).textContent =
                    "Please confirm that the information is accurate.";

                isValid = false;

            }


            /* SUCCESS */

            if (isValid) {

                formMessage.textContent =
                    "Your appointment enquiry has been submitted successfully.";

                formMessage.className =
                    "form-message success";


                appointmentForm.reset();


                if (appointmentDate) {

                    appointmentDate.min =
                        minimumDate;

                }

            }
            else {

                formMessage.textContent =
                    "Please correct the highlighted fields and try again.";

                formMessage.className =
                    "form-message error";

            }

        }
    );

}


/* EMAIL VALIDATION */

function isValidEmail(email) {

    const emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return emailPattern.test(email);

}


/* PHONE VALIDATION */

function isValidPhone(phone) {

    const phonePattern =
        /^\+?[0-9\s-]{10,15}$/;

    return phonePattern.test(phone);

}


/* DATE VALIDATION */

function isDateInPast(dateValue) {

    const selectedDate =
        new Date(
            dateValue + "T00:00:00"
        );

    const today =
        new Date();

    today.setHours(
        0,
        0,
        0,
        0
    );

    return selectedDate < today;

}


/* SHOW ERROR */

function showError(
    fieldId,
    message
) {

    const field =
        document.getElementById(
            fieldId
        );

    const error =
        document.getElementById(
            fieldId + "Error"
        );


    if (field) {

        field.classList.add(
            "input-error"
        );

    }


    if (error) {

        error.textContent =
            message;

    }

}


/* CLEAR ERRORS */

function clearErrors() {

    const fields = [
        "fullName",
        "email",
        "phone",
        "department",
        "appointmentDate"
    ];


    fields.forEach(
        function (fieldId) {

            const field =
                document.getElementById(
                    fieldId
                );

            const error =
                document.getElementById(
                    fieldId + "Error"
                );


            if (field) {

                field.classList.remove(
                    "input-error"
                );

            }


            if (error) {

                error.textContent =
                    "";

            }

        }
    );


    const consentError =
        document.getElementById(
            "consentError"
        );

    if (consentError) {

        consentError.textContent =
            "";

    }


    if (formMessage) {

        formMessage.textContent =
            "";

        formMessage.className =
            "form-message";

    }

}


/* CLEAR ERROR WHEN USER CORRECTS FIELD */

const formFields =
    document.querySelectorAll(
        "#appointmentForm input, " +
        "#appointmentForm select, " +
        "#appointmentForm textarea"
    );


formFields.forEach(
    function (field) {

        field.addEventListener(
            "input",
            function () {

                removeFieldError(
                    field
                );

            }
        );


        field.addEventListener(
            "change",
            function () {

                removeFieldError(
                    field
                );

            }
        );

    }
);


function removeFieldError(field) {

    field.classList.remove(
        "input-error"
    );


    const error =
        document.getElementById(
            field.id + "Error"
        );


    if (error) {

        error.textContent =
            "";

    }

}
                    document.getElementById(
                        field.id + "Error"
                    );


                if (error) {

                    error.textContent = "";

                }

            }
        );

    }
);