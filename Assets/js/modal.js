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
            if (response.appointment_id) {
                // Redirect to confirmation-qr.php with appointment_id parameter
                window.location.href = "confirmation-qr.php?appointment_id=" + response.appointment_id;
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
    // Show the modal
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
    var successModal = document.getElementById('successModal');
    successModal.style.display = 'block';
}

// Function to hide the success modal
function hideSuccessModal() {
    // Hide the modal
    var successModal = document.getElementById('successModal');
    successModal.style.display = 'none';
}