<?php
    define('INCLUDED', true);
    require_once('connect.php');

    $query = "SELECT service_id, service_name, service_description, service_image FROM services";
    $result = mysqli_query($conn, $query);

    $query1 = "SELECT promo_id, promo_details, promo_image FROM promo";
    $result1 = mysqli_query($conn, $query1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browlesque</title>
    
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom container-fluid">
    <div class="container">
    <a href="http://localhost/browlesque">
        <img src="./assets/images/icon/Browlesque.svg" class="logo-browlesque-client" alt="Browlesque Logo">
    </a>
    <a class="navbar-toggler" href="Index.php" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="Index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book_appointment1.php">Book Appointment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about_us.php">About us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <h1 class="fw-bold mt-2" id="browlesque">BOOK YOUR APPOINTMENT</h1>
    <div class="container-md container-md-custom" id="dropdown-container">
        <form id="appointmentForm" method="POST" action="insert.php">
            <span class="label-checkbox mb-2"><span class="asterisk">*</span>Type of Service to be Availed: </span><br>
            <?php
    // Loop through services and display checkboxes
    while ($row = mysqli_fetch_assoc($result)) {
        $service_id = $row['service_id'];
        $service_name = $row['service_name'];

        // Determine whether this checkbox should be disabled based on your logic
        $isDisabled = true; // Replace true with your actual logic to determine whether this checkbox should be disabled

        // Output the checkbox with the custom attribute
        echo '<div class="form-check">';
        echo '<input class="form-check-input service-checkbox" type="checkbox" value="' . $service_id . '" id="Service_' . $service_id . '" data-disable="' . ($isDisabled ? 'true' : 'false') . '">';
        echo '<label class="form-check-label" for="Service_' . $service_id . '">' . $service_name . '</label>';
        echo '</div>';
    }
?>

                <input type="hidden" name="service_ids[]" id="service_ids">

                <span class="label-checkbox mb-2"><span class="asterisk">*</span>Type of Promo to be Availed:</span><br>
                <?php
                    // Loop through promos and display checkboxes
                    while ($row1 = mysqli_fetch_assoc($result1)) {
                        $promo_id = $row1['promo_id'];
                        $promo_details = $row1['promo_details'];
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input promo-checkbox" type="checkbox" value="' . $promo_id . '" id="Promo_' . $promo_id . '">';
                        echo '<label class="form-check-label" for="Promo_' . $promo_id . '">' . $promo_details . '</label>';
                        echo '</div>';
                    }
                ?>
                <input type="hidden" name="promo_ids[]" id="promo_ids">

            <div class="mt-2 mb-4">
                <div class="d-flex justify-content-end fixed-buttons me-4">
                    <button type="button" id="nextButton" class="btn custom-next me-2 fs-5 text-center" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="Please check at least one service or promo first">Next</button>
                </div>
            </div>
            <!-- Calendar section -->
            <div id="calendarContainer" class="calendar-container container-fluid" style="display: none;">
                <span class="label-checkbox mb-2"><span class="asterisk">*</span>Set appointment date:</span><br>
                <div class="calendar mb-3">
                    <div class="calendar-header">
                        <span class="month-picker" id="month-picker">May</span>
                        <div class="year-picker" id="year-picker">
                            <span class="year-change" id="pre-year">
                                <pre><</pre>
                            </span>
                            <span id="year">2020</span>
                            <span class="year-change" id="next-year">
                                <pre>></pre>
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

            <!-- time buttons section -->
            <div id="timeButtonsContainer" style="display: none;">
                <span class="label-checkbox mb-2"><span class="asterisk">*</span>Set appointment time:</span><br>
                <div class="btn-grid-container">
                    <div class="parent mb-3">
                        <div class="div1"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="9:00 AM-11:00 AM">9-11 AM</button></div>
                        <div class="div2"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="10:00 AM-12:00 PM">10-12 PM</button></div>
                        <div class="div3"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="11:00 AM-1:00 PM">11-1 PM</button></div>
                        <div class="div4"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="12:00 PM-2:00 PM">12-2 PM</button></div>
                        <div class="div5"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="1:00 PM-3:00 PM">1-3 PM</button></div>
                        <div class="div6"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="2:00 PM-4:00 PM">2-4 PM</button></div>
                        <div class="div7"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="3:00 PM-5:00 PM">3-5 PM</button></div>
                        <div class="div8"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="4:00 PM-6:00 PM">4-6 PM</button></div>
                        <div class="div9"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="5:00 PM-7:00 PM">5-7 PM</button></div>
                        <div class="div10"><button type="button" class="btn time-buttons me-2 fs-6 text-center" value="6:00 PM-8:00 PM">6-8 PM</button></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="start_time" id="start_time">
            <input type="hidden" name="end_time" id="end_time">

            <input type="hidden" name="appointment_time" id="appointment_time">

            <div id="nameAndNumberContainer" style="display: none;">
                <div class="container-fluid">
                    <div class="mb-3">
                        <label for="client_name" class="label-checkbox mb-2"><span class="asterisk">*</span>Name:</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="client_contactno" class="label-checkbox mb-2"><span class="asterisk">*</span>Enter your phone number:</label>
                        <input type="tel" class="form-control" id="client_contactno" name="client_contactno"
                        pattern="09[0-9]{9}" title="Please enter a valid contact number." placeholder="" required
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);">
                    </div>

                    <div class="mb-3 d-flex justify-content-center">
                        <div class="btn-grid-container">
                            <label for="no_of_companions" class="label-checkbox mb-2 me-4 text-center"><span class="asterisk text-center">*</span>No. of Companions:</label>
                            <div class="parent-comp mb-3">
                                <div class="div11"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="1">1</button></div>
                                <div class="div12"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="2">2</button></div>
                                <div class="div13"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="3">3</button></div>
                                <div class="div14"><button type="button" class="btn companion-btn me-2 fs-6 text-center" value="4">4</button></div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="no_of_companions" id="no_of_companions">

                    <div class="mb-3">
                        <label for="client_notes" class="label-checkbox mb-2">Notes:</label>
                        <textarea class="form-control tall-input" id="client_notes" name="client_notes"></textarea>
                    </div>

                    <div class="container mt-5">
                        <div class="mb-3 checkbox-container">
                            <input type="checkbox" class="form-check-input form-check-input-custom" id="terms_conditions" name="terms_conditions" value="1" required>
                            <label for="terms_conditions" class="label-checkbox-custom mb-2 click-effect"><span class="asterisk">*</span>Terms and Conditions</label>
                        </div>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary click-effect" data-bs-toggle="modal" data-bs-target="#termsAndConditions" id="modalButton" style="display: none;">
                            Launch static backdrop modal
                        </button>
                    </div>

                    <div class="button-appoint">
                        <button type="submit" name="user_submit" class="btn btn-primary btn-primary-custom fs-4">BOOK NOW!</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

  <footer class="container-fluid d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top footer-black">
    <div class="col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
        <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
      </a>
      <span class="mb-3 mb-md-0 footer-text-white">Contact Us</span>
    </div>
    <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
      <li class="ms-3">
        <a class="footer-text-white" href="https://www.facebook.com/BrowlesqueCavite">
          <img src="./assets/images/icon/Facebook.svg" alt="Facebook Icon">
          Browlesque Cavite
        </a>
      </li>
      <li class="ms-3">
        <span class="footer-text-white">
          <img src="./assets/images/icon/Phone.svg" alt="Phone Icon">
          09123456789
        </span>
      </li>
    </ul>
  </footer>

<!-- Terms and Service Modal -->
<div class="modal fade" id="termsAndConditions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="termsAndConditionsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h1 class="modal-title fs-5 mx-auto" id="termsAndConditionsLabel">Terms and Conditions</h1>
      </div>
      <div class="modal-body">
        By accessing and using the reservation system provided by [Your Company Name], you agree to be bound by the following terms and conditions.
        When making a reservation, you are required to provide accurate information, and payment in full is necessary to confirm the booking. Cancellations may incur fees, and refunds, if applicable, will be subject to our refund policy. Modifications to reservations are dependent on availability and may incur additional charges. While using our Services, you agree to conduct yourself lawfully and responsibly. We are not liable for any loss or damage to personal belongings. Our liability is limited to the extent permitted by law, and you agree to indemnify us against any claims arising from your use of the Services. Intellectual property rights in the reservation system belong to [Your Company Name]. These terms are governed by the laws of [Your Country], and we reserve the right to amend them at any time. If any provision is deemed unenforceable, the remaining terms shall remain in effect. By using our reservation system, you signify your acceptance of these terms and conditions.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-primary-custom-tc" id="acceptBtn">Accept</button>
        <button type="button" class="btn btn-secondary btn-secondary-custom" id="declineBtn" data-bs-dismiss="modal">Decline</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all service and promo checkboxes
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const promoCheckboxes = document.querySelectorAll('.promo-checkbox');

    // Add click event listener to each service checkbox
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('click', updateServiceIDs);
    });

    // Add click event listener to each promo checkbox
    promoCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('click', updatePromoIDs);
    });
});

// Function to update service IDs
function updateServiceIDs() {
    const serviceIDs = Array.from(document.querySelectorAll('.service-checkbox:checked')).map(checkbox => checkbox.value);
    document.getElementById('service_ids').value = serviceIDs.join(',');
    
    console.log("Selected service IDs:", serviceIDs);
}

// Function to update promo IDs
function updatePromoIDs() {
    const promoIDs = Array.from(document.querySelectorAll('.promo-checkbox:checked')).map(checkbox => checkbox.value);
    document.getElementById('promo_ids').value = promoIDs.join(',');
    
    console.log("Selected promo IDs:", promoIDs);
}
    
document.addEventListener('DOMContentLoaded', function() {
    // Get all buttons
    const buttons = document.querySelectorAll('.time-buttons');

    // Add click event listener to each button
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove highlight from all buttons
            buttons.forEach(btn => btn.classList.remove('highlight'));
            // Add highlight to the clicked button
            this.classList.add('highlight');

            // Get the value of the clicked button (time range)
            const selectedTimeRange = this.value;

            // Split the time range into start and end times
            const [startTime, endTime] = selectedTimeRange.split('-').map(time => formatTime(time.trim()));

            // Set the value of the hidden input fields to the start and end times
            document.getElementById('start_time').value = startTime;
            document.getElementById('end_time').value = endTime;

            console.log("Selected time range:", selectedTimeRange);
            console.log("Start time:", startTime);
            console.log("End time:", endTime);
        });
    });
});

// Function to format time to HH:MM:SS format
function formatTime(time) {
    // Check if time is in AM/PM format
    const isAMPM = time.includes('AM') || time.includes('PM');

    // Extract hours, minutes, and optionally seconds from the time string
    const [hours, minutes, seconds] = time.split(/:| /).map(part => parseInt(part, 10));

    // Convert 12-hour format to 24-hour format if necessary
    const formattedHours = isAMPM ? ((hours % 12) + (time.includes('PM') ? 12 : 0)) : hours;

    // Pad hours, minutes, and seconds with leading zeroes if necessary
    const paddedHours = String(formattedHours).padStart(2, '0');
    const paddedMinutes = String(minutes).padStart(2, '0');
    const paddedSeconds = String(seconds || 0).padStart(2, '0');

    // Construct formatted time string
    return `${paddedHours}:${paddedMinutes}:${paddedSeconds}`;
}



document.addEventListener('DOMContentLoaded', function() {
    // Get all companion buttons
    const companionButtons = document.querySelectorAll('.companion-btn');

    // Add click event listener to each companion button
    companionButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove highlight from all companion buttons
            companionButtons.forEach(btn => btn.classList.remove('highlight'));
            // Add highlight to the clicked companion button
            this.classList.add('highlight');

            // Get the value of the clicked companion button
            const selectedCompanions = this.value;

            // Set the value of the hidden input field to the selected number of companions
            document.getElementById('no_of_companions').value = selectedCompanions;
            console.log("Selected number of companions:", selectedCompanions); // You can use this value as per your requirement
        });
    });
});


    // const checkbox = document.getElementById('terms_conditions');

    // if (checkbox.checked) {
    //     const value = checkbox.value;
    //     console.log('Checkbox value:', value);
    // } else {
    //     console.log('Checkbox not checked');
    // }

//     document.getElementById('appointmentForm').addEventListener('submit', function(event) {
//     // Get selected service IDs
//     const serviceCheckboxes = document.querySelectorAll('input[name="service_ids[]"]:checked');
//     const serviceIds = Array.from(serviceCheckboxes).map(checkbox => checkbox.value);
//     document.getElementById('service_ids').value = serviceIds.join(',');

//     // Get selected promo IDs
//     const promoCheckboxes = document.querySelectorAll('input[name="promo_ids[]"]:checked');
//     const promoIds = Array.from(promoCheckboxes).map(checkbox => checkbox.value);
//     document.getElementById('promo_ids').value = promoIds.join(',');
// });
</script>

<!-- Include your separate JavaScript file using the script tag with src attribute -->
<script src="./assets/js/scripts.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
