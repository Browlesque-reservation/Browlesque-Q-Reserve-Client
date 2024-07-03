<?php
   // define('INCLUDED', true);
   
   require_once('connect.php');

   // Check if form has already been submitted
    if (isset($_SESSION["appointment_submitted"])) {
        // Redirect to another page or show a message
        header("Location: index.php");
        exit();
    }
   
   $query = "SELECT service_id, service_name, service_description, service_image FROM services WHERE service_state = 'Activated'";
   $result = mysqli_query($conn, $query);
   
   $query1 = "SELECT promo_id, promo_details, promo_image FROM promo WHERE promo_state = 'Activated'";
   $result1 = mysqli_query($conn, $query1);
   
   // Check if both queries returned empty results
   if (mysqli_num_rows($result) == 0 && mysqli_num_rows($result1) == 0) {
       header("Location: index.php"); // Redirect to index.php
       exit(); // Stop further execution
   }

   // Variable to track if either section has content
   $servicesNotEmpty = mysqli_num_rows($result) > 0;
   $promosNotEmpty = mysqli_num_rows($result1) > 0;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Browlesque</title>
    <link rel="icon" href="assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <!-- CSS Link -->
    <link rel="stylesheet" href="Assets/css/style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Box Icon Link for Icons -->
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
  </head>
   <body>


   <?php include_once('topnavbar.php') ?>

    <!-- Book Appointment Section -->
    <section class="appointment" id="appointment">
        <h2 class="section_title"> Book Your Appointment</h2>
        <div class="section_container" style="padding-bottom: 20px;">
            <div class="appointment_container" style="border: 1px solid #ccc; border-radius: 20px; padding: 20px; background-color: #eeeeee;">
                <form id="appointmentForm" method="POST" action="insert.php" onsubmit="validateForm(event);">
                    <h4 class="py-3 text-center"><span class="asterisk">*</span>Select service(s) or a promo</h4>
                      <span id="numServicesError" style="color: red; display: none;">Maximum of only 3 services selection.</span>
                        <?php
                        // Check if services are not empty
                        if (mysqli_num_rows($result) > 0) {
                            echo '<span class="label-checkbox mb-2">Type of Service to be Availed:</span><br>';
                            // Loop through services and display checkboxes
                            while ($row = mysqli_fetch_assoc($result)) {
                                $service_id = $row['service_id'];
                                $service_name = $row['service_name'];
                                $isDisabled = true;
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input service-checkbox" type="checkbox" name="service_id[]" value="' . $service_id . '" id="Service_' . $service_id . '" data-disable="' . ($isDisabled ? 'true' : 'false') . '">';
                                echo '<label class="form-check-label" for="Service_' . $service_id . '">' . $service_name . '</label>';
                                echo '</div>';
                            }
                            echo '<input type="hidden" name="service_id[]" id="service_id">';
                        }
                        else{
                            echo '<div class="top-margin"></div>';
                        }

                        // Check if promos are not empty
                        if (mysqli_num_rows($result1) > 0) {
                            echo '<span class="label-checkbox mb-2">Type of Promo to be Availed:</span><br>';
                            // Loop through promos and display checkboxes
                            while ($row1 = mysqli_fetch_assoc($result1)) {
                                $promo_id = $row1['promo_id'];
                                $promo_details = $row1['promo_details'];
                                $isDisabled = true;
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input promo-checkbox" type="checkbox" name="promo_id[]" value="' . $promo_id . '" id="Promo_' . $promo_id . '" data-disable="' . ($isDisabled ? 'true' : 'false') . '">';
                                echo '<label class="form-check-label" for="Promo_' . $promo_id . '">' . $promo_details . '</label>';
                                echo '</div>';
                            }
                            echo '<input type="hidden" name="promo_id[]" id="promo_id">';
                        }
                        else{
                            echo '<div class="bot-margin"></div>';
                        }
                        ?>
                <div class="mt-2 mb-4">
                    <div class="d-flex justify-content-end fixed-buttons pb-4 me-4">
                        <button type="button" id="nextButton" class="custom-next me-2 fs-5 text-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Please check at least one service or promo first" disabled>
                            Next <i class='bx bx-chevron-right-circle'></i></button>
                    </div>
                </div>
                <!-- Calendar section -->
                <span id="clientDateError" style="color: red; display: none;">Please select a date.</span>
                <div id="nameAndNumberContainer" style="display: none;">
                    <div id="calendarContainer" class="calendar-container container-fluid" style="display: none;">
                        <span class="mb-2" id="focus_date"><span class="asterisk">*</span>Set appointment date:</span><br><br>
                        <div class="calendar mb-3">
                            <div class="calendar-header">
                            <span class="month-picker" id="month-picker">May</span>
                            <div class="year-picker" id="year-picker">
                                <span class="year-change" id="pre-year">
                                    <pre><</pre>
                                </span>
                                <span id="year">2020</span>
                                <span class="year-change" id="next-year">
                                    <pre></pre>
                                </span>
                            </div>
                            </div>
                            <div class="calendar-body">
                            <div class="calendar-week-days">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div class="wednesday">Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                            <div class="calendar-days"></div>
                            </div>
                        </div>
                        <!-- <div class="calendar-footer"></div>
                            <div class="date-time-formate">
                                <div class="day-text-formate">TODAY</div>
                                <div class="date-time-value">
                                    <div class="time-formate">01:41:20</div>
                                    <div class="date-formate">03 - march - 2022</div>
                                </div>
                            </div> -->
                        <div class="month-list"></div>
                    </div>
                    <input type="hidden" name="client_date" id="client_date">
                    <!-- time buttons section --><br>
                    <div id="timeButtonsContainer" style="display: none;">
                    <span id="clientTimeError" style="color: red; display: none;">Please select a time.</span>
                        <span class="mb-2 mt-2"><span class="asterisk">*</span>Set appointment time:</span><br>
                        <div class="btn-grid-container">
                            <div class="parent mb-3">
                                <div class="div1"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="9:00 AM-12:00 PM">9-12 PM</button></div>
                                <div class="div2"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="10:00 AM-1:00 PM">10-1 PM</button></div>
                                <div class="div3"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="11:00 AM-2:00 PM">11-2 PM</button></div>
                                <div class="div4"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="12:00 PM-3:00 PM">12-3 PM</button></div>
                                <div class="div5"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="1:00 PM-4:00 PM">1-4 PM</button></div>
                                <div class="div6"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="2:00 PM-5:00 PM">2-5 PM</button></div>
                                <div class="div7"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="3:00 PM-6:00 PM">3-6 PM</button></div>
                                <div class="div8"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="4:00 PM-7:00 PM">4-7 PM</button></div>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="start_time" id="start_time">
                    <input type="hidden" name="end_time" id="end_time">
                    <!-- <input type="hidden" name="client_time" id="client_time"> -->
                    <div class="container-fluid">
                        <div class="mb-3" id="clientNameDiv">
                            <label for="client_name"><span class="asterisk">*</span>Name:</label>
                            <input type="text" class="form-control" id="client_name" name="client_name">
                            <span id="clientNameError" style="color: red; display: none;"></span>
                        </div>
                        <div class="mb-4" id="clientNumberDiv">
                            <label for="client_contactno"><span class="asterisk">*</span>Enter your phone number:</label>
                            <input type="tel" class="form-control" id="client_contactno" name="client_contactno" value="09" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);">
                            <span id="clientContactError" style="color: red; display: none;"></span>
                        </div>
                        <span id="clientCompanionChoiceError" style="color: red; display: none;">Please select an answer.</span>
                        <div class="mb-3 d-flex justify-content-center">
                            <div class="btn-grid-container">
                            <label class="label-checkbox mb-2 me-4 text-center"><span class="asterisk text-center">*</span>Do you have a companion?</label>
                            <div class="parent-comp mb-3">
                                <div class="div11"><button type="button" class="btn companion-btn me-2 fs-6 text-center" id="Yes" >Yes, I do</button></div>
                                <div class="div12"><button type="button" class="btn companion-btn me-2 fs-6 text-center" id="No">No, I don't</button></div>
                            </div>
                            </div>
                        </div>
                        <span id="clientCompanionError" style="color: red; display: none;">Please select number of companion/s.</span>
                        <div id="num_companion"  style="display: none;">
                            <div class="mb-3 d-flex justify-content-center">
                            <div class="btn-grid-container">
                                <label for="no_of_companions" class="label-checkbox mb-2 me-4 text-center"><span class="asterisk text-center">*</span>No. of Companions:</label>
                                <div class="parent-comp mb-3">
                                    <div class="div13"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="1">1</button></div>
                                    <div class="div14"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="2">2</button></div>
                                    <div class="div15"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="3">3</button></div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <input type="hidden" name="no_of_companions" id="no_of_companions">
                        <div class="mb-3">
                            <label for="client_notes">Notes (optional):</label>
                            <textarea class="form-control tall-input" id="client_notes" name="client_notes" maxlength="250"></textarea>
                        </div>
                        <div class="container mt-5">
                        <span id="clientTermsError" style="display: none; color: red;">Please accept the terms and conditions to continue with the booking.</span>
                            <div class="mb-3 checkbox-container">
                            <input type="checkbox" class="form-check-input" id="terms_conditions" name="terms_conditions" value="Agree">
                            <label for="terms_conditions" class="label-checkbox-custom mb-2 click-effect"><span class="asterisk">*</span>Terms and Conditions</label>
                            </div>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary click-effect" data-bs-toggle="modal" data-bs-target="#termsAndConditions" id="modalButton" style="display: none;">
                            Launch static backdrop modal
                            </button>
                        </div>
                        <div class="button-appoint-in btn-center">
                            <button type="submit" name="client_submit" class="btn btn-primary-custom fs-4">Book Now</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>
     
      <!-- Terms and Service Modal -->
<div class="modal fade blur-backdrop" id="termsAndConditions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="termsAndConditionsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1 class="modal-title fs-5 mx-auto" id="termsAndConditionsLabel">Terms and Conditions</h1>
            </div>
            <div class="modal-body">
                By accessing and using the reservation system provided by Browlesque, you agree to be bound by the following terms and conditions. When making a reservation, you are required to provide accurate information. Modifications to reservations are dependent on availability and may incur additional charges. While using our services, you agree to conduct yourself lawfully and responsibly. We are not liable for any loss or damage to personal belongings. Our liability is limited to the extent permitted by law, and you agree to indemnify us against any claims arising from your use of the services. Intellectual property rights in the reservation system belong to Browlesque. These terms are governed by the laws of the Republic of the Philippines, and we reserve the right to amend them at any time. If any provision is deemed unenforceable, the remaining terms shall remain in effect. By using our reservation system, you signify your acceptance of these terms and conditions.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary-custom-tc" id="acceptBtn">Accept</button>
                <button type="button" class="btn btn-secondary btn-secondary-custom" id="declineBtn" data-bs-dismiss="modal">Decline</button>
            </div>
        </div>
    </div>
</div>



      <div id="confirmationModal" data-bs-backdrop="static" class="modal">
         <div class="modal-content-c custom-modal-content d-flex flex-column align-items-center">
            <h1 class="text-center mt-3 mb-0">Are the information details correct?</h1>
            <h6 class="text-center custom-subtitle mt-2 mb-2">By confirming, this will submit the appointment with the information you provided. Please review and ensure that these details are correct.</h6>
                  <div class="d-flex justify-content-end mt-5">
                     <button type="button" id="confirmButton" class="btn btn-primary-custom-tc me-2 fs-5 text-center" onclick="submitForm()">Confirm</button>
                     <button type="button" id="editButton" class="btn btn-secondary btn-secondary-custom me-2 fs-5 text-center" onclick="hideConfirmationModal()">Cancel</button>
             </div>
         </div>
      </div>

      <?php include_once('footer.php') ?>


<script>

document.addEventListener('DOMContentLoaded', function () {
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const promoCheckboxes = document.querySelectorAll('.promo-checkbox');

    function updateCheckboxStates() {
        const checkedServices = Array.from(serviceCheckboxes).filter(checkbox => checkbox.checked).length;
        const checkedPromos = Array.from(promoCheckboxes).filter(checkbox => checkbox.checked).length;

        if (checkedServices >= 1) {
            promoCheckboxes.forEach(checkbox => checkbox.disabled = true);
        } else {
            promoCheckboxes.forEach(checkbox => checkbox.disabled = false);
        }

        if (checkedServices >= 3) {
            serviceCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) checkbox.disabled = true;
            });
        } else {
            serviceCheckboxes.forEach(checkbox => {
                if (checkedPromos === 0) checkbox.disabled = false;
            });
        }

        if (checkedPromos >= 1) {
            serviceCheckboxes.forEach(checkbox => checkbox.disabled = true);
            promoCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) checkbox.disabled = true;
            });
        } else if (checkedServices < 3) {
            serviceCheckboxes.forEach(checkbox => checkbox.disabled = false);
        }
    }

    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckboxStates);
    });

    promoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckboxStates);
    });
});
        
document.addEventListener('DOMContentLoaded', (event) => {
    const modalElement = document.getElementById('termsAndConditions');

    modalElement.addEventListener('shown.bs.modal', () => {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.backdropFilter = 'blur(10px)';
            backdrop.style.webkitBackdropFilter = 'blur(10px)'; // For Safari
        }
    });

    modalElement.addEventListener('hidden.bs.modal', () => {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.style.backdropFilter = '';
            backdrop.style.webkitBackdropFilter = ''; // For Safari
        }
    });
});


document.getElementById("client_name").addEventListener("keypress", function(event) {
    var charCode = event.charCode;
    var inputValue = event.target.value;
    
    // Prevent whitespace as the first character
    if (inputValue.length === 0 && charCode === 32) {
        event.preventDefault();
        return;
    }

    // Allow letters, hyphens, and period
    if (!(charCode >= 65 && charCode <= 90) && // Uppercase letters
        !(charCode >= 97 && charCode <= 122) && // Lowercase letters
        !(charCode === 32) && // Whitespace
        !(charCode === 45) && // Hyphen
        !(charCode === 46)) { // Period
        event.preventDefault();
    }
});

document.getElementById("client_notes").addEventListener("keypress", function(event) {
    var charCode = event.charCode;
    var inputValue = event.target.value;
    
    // Prevent whitespace as the first character
    if (inputValue.length === 0 && charCode === 32) {
        event.preventDefault();
        return;
    }
});

document.getElementById('client_contactno').addEventListener('input', function(event) {
    let input = event.target.value;

         if (!input.startsWith('09')) {
            event.target.value = '09'; 
         } else {
            let numbersAfterPrefix = input.replace('09', ''); 
            let numbersOnly = /^\d*$/; 

            if (!numbersAfterPrefix.match(numbersOnly) || numbersAfterPrefix.length > 9) {
        event.target.value = '09' + numbersAfterPrefix.slice(0, 9);
    }
         }
});

document.getElementById('Yes').addEventListener('click', function() {
    document.getElementById('num_companion').style.display = 'block';
    document.getElementById('no_of_companions').value = ''; // Clear previous value
    document.getElementById('clientCompanionChoiceError').style.display = 'none';
    this.classList.add('active');
    document.getElementById('No').classList.remove('active');
});

document.getElementById('No').addEventListener('click', function() {
    document.getElementById('num_companion').style.display = 'none';
    document.getElementById('no_of_companions').value = '0'; // Set value to 0 when no companion
    document.getElementById('clientCompanionChoiceError').style.display = 'none';
    document.getElementById('clientCompanionError').style.display = 'none';
    this.classList.add('active');
    document.getElementById('Yes').classList.remove('active');
});

document.querySelectorAll('#num_companion .companion-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('no_of_companions').value = this.value;
        document.getElementById('clientCompanionError').style.display = 'none';
    });
});

function validateForm(event) {
    event.preventDefault(); // Prevent form submission by default

    var clientName = document.getElementById('client_name');
    var clientContact = document.getElementById('client_contactno');
    var clientDate = document.getElementById('client_date');
    var clientTime = document.getElementById('start_time');
    var clientCompanion = document.getElementById('no_of_companions');
    var clientNameError = document.getElementById('clientNameError');
    var clientContactError = document.getElementById('clientContactError');
    var clientDateError = document.getElementById('clientDateError');
    var clientTimeError = document.getElementById('clientTimeError');
    var clientCompanionChoiceError = document.getElementById('clientCompanionChoiceError');
    var clientCompanionError = document.getElementById('clientCompanionError');
    var termsCheckbox = document.getElementById('terms_conditions');

    // Check if client date is empty
    if (clientDate.value.trim() === '') {
        clientDateError.style.display = 'block';
        clientDateError.scrollIntoView();
        return false;
    } else {
        clientDateError.style.display = 'none';
    }

    // Check if client time is empty
    if (clientTime.value.trim() === '') {
        clientTimeError.style.display = 'block';
        clientTimeError.scrollIntoView();
        return false;
    } else {
        clientTimeError.style.display = 'none';
    }

       // Check if client name is empty or less than 5 characters
       if (clientName.value.trim() === '') {
        clientNameError.style.display = 'block';
        clientNameError.innerText = 'This field is required. Please input your name.';
        clientNameError.scrollIntoView();
        clientName.focus();
        return false;
    } else if (clientName.value.trim().length < 3) {
        clientNameError.style.display = 'block';
        clientNameError.innerText = 'Name must be at least 3 characters long.';
        clientNameError.scrollIntoView();
        clientName.focus();
        return false;
    } else {
        clientNameError.style.display = 'none';
    }

    // Check if client contact number is only the pre-added value
    if (clientContact.value.trim() === '09') {
        clientContactError.style.display = 'block';
        clientContactError.innerText = 'This field is required. Please input your phone number';
        clientContactError.scrollIntoView();
        clientContact.focus();
        return false;
    } else if (clientContact.value.trim().length < 11) {
        clientContactError.style.display = 'block';
        clientContactError.innerText = 'Phone number must be 11 numbers long.';
        clientContactError.scrollIntoView();
        clientContact.focus();
        return false;
    } else {
        clientContactError.style.display = 'none';
    }

    // Check if client companion choice is made
    if (!document.getElementById('Yes').classList.contains('active') && !document.getElementById('No').classList.contains('active')) {
        clientCompanionChoiceError.style.display = 'block';
        clientCompanionChoiceError.scrollIntoView();
        return false;
    } else {
        clientCompanionChoiceError.style.display = 'none';
    }

    // Check if client companion value is empty if Yes was selected
    if (document.getElementById('Yes').classList.contains('active') && clientCompanion.value.trim() === '') {
        clientCompanionError.style.display = 'block';
        clientCompanionError.scrollIntoView();
        return false;
    } else {
        clientCompanionError.style.display = 'none';
    }

    // Check if terms and conditions checkbox is checked
    if (!termsCheckbox.checked) {
        document.getElementById('clientTermsError').style.display = 'block';
        document.getElementById('clientTermsError').scrollIntoView();
        return false;
    } else {
        document.getElementById('clientTermsError').style.display = 'none';
    }

    // If all inputs have values, show the modal
    showConfirmationModal();

    return false; // Prevent form submission
}

// Listen for input events on the client name and contact fields
document.getElementById('client_name').addEventListener('input', function() {
    var clientNameError = document.getElementById('clientNameError');
    clientNameError.style.display = 'none';
});

document.getElementById('client_contactno').addEventListener('input', function() {
    var clientContactError = document.getElementById('clientContactError');
    clientContactError.style.display = 'none';
});

document.getElementById('client_date').addEventListener('input', function() {
    var clientDateError = document.getElementById('clientDateError');
    clientDateError.style.display = 'none';
});

document.getElementById('start_time').addEventListener('input', function() {
    var clientTimeError = document.getElementById('clientTimeError');
    clientTimeError.style.display = 'none';
});

         
 document.addEventListener('DOMContentLoaded', function() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
         var nextButton = document.getElementById('nextButton');
         var tooltip = new bootstrap.Tooltip(nextButton);
         var calendarContainer = document.getElementById('calendarContainer');
         var timeButtonsContainer = document.getElementById('timeButtonsContainer');
         var nameAndNumberContainer = document.getElementById('nameAndNumberContainer');
         var formContainer = document.getElementById('appointmentForm');
         
         // Function to check if any checkbox is checked
         function anyCheckboxChecked() {
             return Array.from(checkboxes).some(function(checkbox) {
                 return checkbox.checked;
             });
         }
         
         // Show tooltip initially
         tooltip.show();
         
         checkboxes.forEach(function(checkbox) {
             checkbox.addEventListener('change', function() {
                 var isChecked = anyCheckboxChecked();
                 nextButton.disabled = !isChecked;
         
                 // Show or hide tooltip based on checkbox state
                 if (isChecked) {
                     tooltip.hide();
                 } else {
                     tooltip.show();
                 }
             });
         });
         
         // Disable tooltip when hovering over the button
         nextButton.addEventListener('mouseover', function() {
             tooltip.disable();
         });
         
         // Enable tooltip when mouse leaves the button
         nextButton.addEventListener('mouseout', function() {
             tooltip.enable();
         });
         
         // Show calendar when "Next" button is clicked
         nextButton.addEventListener('click', function() {
             if (anyCheckboxChecked()) {
                 // Move calendarContainer into dropdown-container
                formContainer.appendChild(calendarContainer);
                formContainer.appendChild(timeButtonsContainer);
                formContainer.appendChild(nameAndNumberContainer);
                 // Set calendarContainer display style to 'block'
                 calendarContainer.style.display = 'block';
                 timeButtonsContainer.style.display = 'block';
                 nameAndNumberContainer.style.display = 'block';
                 nextButton.disabled = true;
                 // Record the checked checkboxes (you can store them in an array or any other data structure)
                 var checkedCheckboxes = [];
                 checkboxes.forEach(function(checkbox) {
                     if (checkbox.checked) {
                         checkedCheckboxes.push(checkbox.value);
                     }
                 });
                 console.log('Checked checkboxes:', checkedCheckboxes);
                 // Disable specific checkboxes
                 checkboxes.forEach(function(checkbox) {
                     if (checkbox.dataset.disable === 'true') {
                         checkbox.disabled = true;
                     }
                 });
         
                 // Hide the nextButton
                 nextButton.style.display = 'none';
             }
         });
         
// Function to update service IDs
function updateServiceIDs() {
    const serviceIDs = Array.from(document.querySelectorAll('.service-checkbox:checked')).map(checkbox => checkbox.value);
    document.getElementById('service_id').value = JSON.stringify(serviceIDs);
    console.log("Selected service IDs:", serviceIDs);
}

// Function to update promo IDs
function updatePromoIDs() {
    const promoIDs = Array.from(document.querySelectorAll('.promo-checkbox:checked')).map(checkbox => checkbox.value);
    document.getElementById('promo_id').value = JSON.stringify(promoIDs);
    console.log("Selected promo IDs:", promoIDs);
}

         
             // Function to format time to HH:MM:SS format
             function formatTime(time) {
                 const isAMPM = time.includes('AM') || time.includes('PM');
                 const [hours, minutes, seconds] = time.split(/:| /).map(part => parseInt(part, 10));
                 const formattedHours = isAMPM ? ((hours % 12) + (time.includes('PM') ? 12 : 0)) : hours;
                 const paddedHours = String(formattedHours).padStart(2, '0');
                 const paddedMinutes = String(minutes).padStart(2, '0');
                 const paddedSeconds = String(seconds || 0).padStart(2, '0');
                 return `${paddedHours}:${paddedMinutes}:${paddedSeconds}`;
             }
         
             // Add event listeners after DOM content is loaded
             const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
             const promoCheckboxes = document.querySelectorAll('.promo-checkbox');
             const buttons = document.querySelectorAll('.time-buttons');
             const companionButtons = document.querySelectorAll('.companion-btn');
         
             serviceCheckboxes.forEach(checkbox => checkbox.addEventListener('click', updateServiceIDs));
             promoCheckboxes.forEach(checkbox => checkbox.addEventListener('click', updatePromoIDs));
         
             buttons.forEach(button => {
                 button.addEventListener('click', function() {
                     buttons.forEach(btn => btn.classList.remove('highlight'));
                     this.classList.add('highlight');
         
                     const selectedTimeRange = this.value;
                     const [startTime, endTime] = selectedTimeRange.split('-').map(time => formatTime(time.trim()));
         
                     document.getElementById('start_time').value = startTime;
                     document.getElementById('end_time').value = endTime;
         
                     console.log("Selected time range:", selectedTimeRange);
                     console.log("Start time:", startTime);
                     console.log("End time:", endTime);
                 });
             });
         
             companionButtons.forEach(button => {
                 button.addEventListener('click', function() {
                     companionButtons.forEach(btn => btn.classList.remove('highlight'));
                     this.classList.add('highlight');
         
                     const selectedCompanions = this.value;
                     document.getElementById('no_of_companions').value = selectedCompanions;
         
                     // Log the current value of no_of_companions element
                     console.log("Current value of no_of_companions:", document.getElementById('no_of_companions').value);
                 });
             });
         
             
         });

         // Add alert when user tries to refresh the page or close the browser
        let formChanged = false;

     document.getElementById('appointmentForm').addEventListener('change', function() {
        formChanged = true;
    });

        window.addEventListener('beforeunload', function (e) {
        if (formChanged) {
            const confirmationMessage = ' ';
            e.returnValue = confirmationMessage;
            return confirmationMessage;
        }
        });

        // Add an event listener to the "Give Feedback" button to remove the beforeunload event
        document.getElementById('confirmButton').addEventListener('click', function() {
        formChanged = false; // Reset the flag
        window.removeEventListener('beforeunload', function (e) {
            if (formChanged) {
            const confirmationMessage = ' ';
            e.returnValue = confirmationMessage;
            return confirmationMessage;
            }
        });
    });

         
      </script>
      <!-- Include your separate JavaScript file using the script tag with src attribute -->
      <script src="./assets/js/modal.js"></script>
      <script src="./assets/js/scripts.js"></script>
      <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   </body>
</html>