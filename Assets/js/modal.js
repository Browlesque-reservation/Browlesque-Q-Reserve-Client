// Function to submit the form and show success modal
//for SERVICESSSS
function submitForm() {
    // Submit the form using AJAX
    var formData = new FormData(document.getElementById('appointmentForm'));
    $.ajax({
        type: "POST",
        url: "insert.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', // Ensure the response is parsed as JSON
        success: function(response) {
            if (response.success) {
                hideConfirmationModal();
                showSuccessModal();
                // window.location.href = "confirmation-qr.php?appointment_id=" + response.appointment_id;
            } else if (response.error === 'booking_exists') {
                    hideConfirmationModal();
                    showRejectModal();
                }
            else {
                console.error(response.error);
            }
        },
        error: function(xhr, status, error) {
            // Handle errors here
            console.error(xhr.responseText);
        }
    });
}



function showConfirmationModalDelete() {
    var checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    var serviceIds = Array.from(checkboxes).map(function(checkbox) {
        return checkbox.id.split('_')[2]; // Extract service ID from checkbox ID
    });
    
    if(serviceIds.length === 0) {
        alert("Please select at least one service to delete.");
        return;
    }

    // Store the service IDs in a data attribute of the confirm button
    $('#confirmButton').data('serviceIds', serviceIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}

function showConfirmationModalDeleteP() {
    var checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    var serviceIds = Array.from(checkboxes).map(function(checkbox) {
        return checkbox.id.split('_')[2]; // Extract service ID from checkbox ID
    });
    
    if(serviceIds.length === 0) {
        alert("Please select at least one service to delete.");
        return;
    }

    // Store the service IDs in a data attribute of the confirm button
    $('#confirmButton').data('serviceIds', serviceIds);

    // Show the confirmation modal
    $('#confirmationModal').show();
}


function showConfirmationModal() {
    // Gather input values
    const clientName = document.getElementById('client_name').value.trim();
    const clientEmail = document.getElementById('client_email').value.trim();
    const clientContact = document.getElementById('client_contactno').value.trim();
    const clientDate = document.getElementById('client_date').value.trim();
    const clientTime = document.getElementById('start_time').value.trim();
    const clientTimeEnd = document.getElementById('end_time').value.trim();
    const clientNotes = document.getElementById('client_notes').value.trim();
    const uploadedImage = document.getElementById('gcash_upload').files[0]; // Get uploaded image file

    const formattedClientTime = convertTo12HourFormat(clientTime);
    const formattedClientTimeEnd = convertTo12HourFormat(clientTimeEnd);

    // Gather selected services and promos
    const selectedServices = Array.from(document.querySelectorAll('.service-checkbox:checked'))
        .map(checkbox => checkbox.value).join(', ');
    const selectedPromos = Array.from(document.querySelectorAll('.promo-checkbox:checked'))
        .map(checkbox => checkbox.value).join(', ');

    // Populate the modal with the gathered values
    document.getElementById('confirmClientName').innerText = clientName;
    document.getElementById('confirmClientEmail').innerText = clientEmail;
    document.getElementById('confirmClientContact').innerText = clientContact;
    document.getElementById('confirmClientDate').innerText = clientDate;
    document.getElementById('confirmClientTime').innerText = formattedClientTime;
    document.getElementById('confirmClientTimeend').innerText = formattedClientTimeEnd;
    document.getElementById('confirmClientNotes').innerText = clientNotes;
    document.getElementById('confirmServices').innerText = selectedServices;
    document.getElementById('confirmPromos').innerText = selectedPromos;

    // Display uploaded image
    const confirmImageContainer = document.getElementById('confirmImageContainer');
    const confirmImage = document.getElementById('confirmImage');
    
    if (uploadedImage) {
        const reader = new FileReader();
        reader.onload = function(event) {
            confirmImage.src = event.target.result;
            confirmImageContainer.style.display = 'block';
        };
        reader.readAsDataURL(uploadedImage);
    } else {
        confirmImageContainer.style.display = 'none'; // Hide image container if no file uploaded
    }

    // Conditionally hide fields if empty
    const confirmNotesField = document.getElementById('confirmNotesField');
    const confirmServicesField = document.getElementById('confirmServicesField');
    const confirmPromosField = document.getElementById('confirmPromosField');

    if (clientNotes === '') {
        confirmNotesField.style.display = 'none';
    } else {
        confirmNotesField.style.display = 'block';
    }

    if (selectedServices === '') {
        confirmServicesField.style.display = 'none';
    } else {
        confirmServicesField.style.display = 'block';
    }

    if (selectedPromos === '') {
        confirmPromosField.style.display = 'none';
    } else {
        confirmPromosField.style.display = 'block';
    }

    var confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.style.display = 'block';
}


function showRejectModal() {
    // Show the modal
    var rejectModal = document.getElementById('rejectModal');
    rejectModal.style.display = 'block';
}



// Function to hide the confirmation modal
function hideConfirmationModal() {
    // Hide the modal
    var confirmationModal = document.getElementById('confirmationModal');
    confirmationModal.style.display = 'none';
}

// Function to show the success modal
function showSuccessModal() {
    // Show the modal
    var successModal = document.getElementById('successBookedModal');
    successModal.style.display = 'block';
}

// Function to hide the success modal
function hideSuccessModal() {
    // Hide the modal
    var successModal = document.getElementById('successBookedModal');
    successModal.style.display = 'none';
}

// Function to convert time to 12-hour format
function convertTo12HourFormat(time24) {
    const [hours, minutes] = time24.split(':');
    const period = hours >= 12 ? 'PM' : 'AM';
    const twelveHour = hours % 12 || 12;
    return `${twelveHour}:${minutes} ${period}`;
}