document.addEventListener("DOMContentLoaded", function () {

    // Confirm delete actions
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            if (!confirm("Are you sure you want to delete this record?")) {
                event.preventDefault();
            }
        });
    });

    // Set minimum appointment date to today
    const dateInput = document.querySelector(
        'input[name="appointment_date"]'
    );

    if (dateInput) {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, "0");
        const day = String(today.getDate()).padStart(2, "0");

        dateInput.min = `${year}-${month}-${day}`;
    }

    // Basic form validation
    const forms = document.querySelectorAll("form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            const requiredFields = form.querySelectorAll("[required]");

            for (let field of requiredFields) {

                if (field.value.trim() === "") {
                    alert("Please fill in all required fields.");
                    field.focus();
                    event.preventDefault();
                    return;
                }
            }
        });

    });

});