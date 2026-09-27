const doctors = {
    1: {
        name: "Dr. Ananya Rao",
        department: "General Medicine",
        experience: "8 years of experience",
        days: "Monday - Saturday",
        time: "9:00 AM - 1:00 PM",
        initials: "AR",
        description:
            "Dr. Ananya Rao provides consultation and treatment for common illnesses, general health concerns and preventive care."
    },

    2: {
        name: "Dr. Arjun Mehta",
        department: "Cardiology",
        experience: "10 years of experience",
        days: "Monday - Friday",
        time: "10:00 AM - 2:00 PM",
        initials: "AM",
        description:
            "Dr. Arjun Mehta provides cardiac consultations and supports patients with heart health and cardiovascular concerns."
    },

    3: {
        name: "Dr. Meera Nair",
        department: "Pediatrics",
        experience: "7 years of experience",
        days: "Monday - Saturday",
        time: "9:00 AM - 1:00 PM",
        initials: "MN",
        description:
            "Dr. Meera Nair provides healthcare services for children, including routine consultations and general pediatric care."
    },

    4: {
        name: "Dr. Kavya Menon",
        department: "Dermatology",
        experience: "6 years of experience",
        days: "Monday - Friday",
        time: "2:00 PM - 6:00 PM",
        initials: "KM",
        description:
            "Dr. Kavya Menon provides consultation for skin, hair and related dermatological concerns."
    },

    5: {
        name: "Dr. Rahul Iyer",
        department: "Diagnostics",
        experience: "9 years of experience",
        days: "Monday - Saturday",
        time: "10:00 AM - 4:00 PM",
        initials: "RI",
        description:
            "Dr. Rahul Iyer provides diagnostic consultation and supports patients through appropriate medical investigations."
    }
};


/* Doctor Search and Department Filter */

const searchInput = document.getElementById("doctorSearch");
const departmentFilter = document.getElementById("departmentFilter");

if (searchInput && departmentFilter) {

    const doctorItems = document.querySelectorAll(".doctor-item");
    const noDoctors = document.getElementById("noDoctors");

    function filterDoctors() {

        const searchText = searchInput.value.toLowerCase().trim();
        const department = departmentFilter.value;

        let count = 0;

        doctorItems.forEach(function (doctor) {

            const name =
                doctor.getAttribute("data-name").toLowerCase();

            const doctorDepartment =
                doctor.getAttribute("data-department");

            const nameMatch =
                name.includes(searchText);

            const departmentMatch =
                department === "" ||
                doctorDepartment === department;

            if (nameMatch && departmentMatch) {
                doctor.style.display = "block";
                count++;
            } else {
                doctor.style.display = "none";
            }
        });

        if (count === 0) {
            noDoctors.style.display = "block";
        } else {
            noDoctors.style.display = "none";
        }
    }

    searchInput.addEventListener("input", filterDoctors);
    departmentFilter.addEventListener("change", filterDoctors);
}


/* Doctor Details */

const doctorName = document.getElementById("doctorName");

if (doctorName) {

    const parameters =
        new URLSearchParams(window.location.search);

    const doctorId =
        parameters.get("id") || "1";

    const doctor = doctors[doctorId];

    if (doctor) {

        document.getElementById("doctorPageTitle").textContent =
            doctor.name;

        document.getElementById("doctorName").textContent =
            doctor.name;

        document.getElementById("doctorDepartment").textContent =
            doctor.department;

        document.getElementById("doctorExperience").textContent =
            doctor.experience;

        document.getElementById("doctorDescription").textContent =
            doctor.description;

        document.getElementById("doctorDays").textContent =
            doctor.days;

        document.getElementById("doctorTime").textContent =
            doctor.time;

        document.getElementById("doctorAvatar").textContent =
            doctor.initials;

        document.getElementById("bookDoctor").href =
            "booking.html?doctor=" +
            encodeURIComponent(doctor.name);
    }
}


/* Appointment Date */

const appointmentDate =
    document.getElementById("appointmentDate");

if (appointmentDate) {

    const today = new Date();

    const year = today.getFullYear();

    const month =
        String(today.getMonth() + 1).padStart(2, "0");

    const day =
        String(today.getDate()).padStart(2, "0");

    appointmentDate.min =
        `${year}-${month}-${day}`;
}


/* Booking Form */

const bookingForm =
    document.getElementById("bookingForm");

if (bookingForm) {

    const parameters =
        new URLSearchParams(window.location.search);

    const selectedDoctor =
        parameters.get("doctor");

    if (selectedDoctor) {

        const doctorSelect =
            document.getElementById("doctor");

        for (let option of doctorSelect.options) {

            if (option.value === selectedDoctor) {
                option.selected = true;
                break;
            }
        }
    }

    bookingForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const doctor =
            document.getElementById("doctor").value;

        const date =
            document.getElementById("appointmentDate").value;

        const time =
            document.getElementById("appointmentTime").value;

        const patient =
            document.getElementById("patientName").value.trim();

        const email =
            document.getElementById("patientEmail").value.trim();

        const phone =
            document.getElementById("patientPhone").value.trim();

        const reason =
            document.getElementById("reason").value.trim();

        const error =
            document.getElementById("bookingError");

        error.textContent = "";

        if (doctor === "") {
            error.textContent = "Please select a doctor.";
            return;
        }

        if (date === "") {
            error.textContent = "Please select an appointment date.";
            return;
        }

        if (time === "") {
            error.textContent = "Please select an appointment time.";
            return;
        }

        if (patient.length < 3) {
            error.textContent = "Please enter a valid name.";
            return;
        }

        if (!/^[A-Za-z\s.'-]+$/.test(patient)) {
            error.textContent = "Please enter a valid name.";
            return;
        }

        if (!isValidEmail(email)) {
            error.textContent = "Please enter a valid email address.";
            return;
        }

        if (!/^[0-9]{10}$/.test(phone)) {
            error.textContent =
                "Please enter a valid 10-digit phone number.";
            return;
        }

        const appointmentId =
            "MC" +
            Date.now().toString().slice(-6);

        const appointment = {
            id: appointmentId,
            doctor: doctor,
            date: date,
            time: time,
            patient: patient,
            email: email,
            phone: phone,
            reason: reason
        };

        localStorage.setItem(
            "medicareAppointment",
            JSON.stringify(appointment)
        );

        window.location.href = "summary.html";
    });
}


/* Appointment Summary */

const summaryId =
    document.getElementById("summaryId");

if (summaryId) {

    const savedAppointment =
        localStorage.getItem("medicareAppointment");

    const summary =
        document.getElementById("appointmentSummary");

    const noAppointment =
        document.getElementById("noAppointment");

    if (savedAppointment) {

        const appointment =
            JSON.parse(savedAppointment);

        summary.style.display = "block";
        noAppointment.style.display = "none";

        document.getElementById("summaryId").textContent =
            appointment.id;

        document.getElementById("summaryDoctor").textContent =
            appointment.doctor;

        document.getElementById("summaryDate").textContent =
            formatDate(appointment.date);

        document.getElementById("summaryTime").textContent =
            appointment.time;

        document.getElementById("summaryPatient").textContent =
            appointment.patient;

        document.getElementById("summaryPhone").textContent =
            appointment.phone;

    } else {

        summary.style.display = "none";
        noAppointment.style.display = "block";
    }
}


/* Email Validation */

function isValidEmail(email) {

    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}


/* Date Formatting */

function formatDate(dateString) {

    const date =
        new Date(dateString + "T00:00:00");

    return date.toLocaleDateString("en-IN", {
        day: "2-digit",
        month: "long",
        year: "numeric"
    });
}