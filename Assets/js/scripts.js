//calendar source code js

const isLeapYear = (year) => {
    return (
        (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) ||
        (year % 100 === 0 && year % 400 === 0)
    );
  };
  
  const getFebDays = (year) => {
    return isLeapYear(year) ? 29 : 28;
  };
  
  let calendar = document.querySelector('.calendar');
  const month_names = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December',
  ];
  let month_picker = document.querySelector('#month-picker');
  let month_list = document.querySelector('.month-list');
  
  const dayTextFormate = document.querySelector('.day-text-formate');
  const timeFormate = document.querySelector('.time-formate');
  const dateFormate = document.querySelector('.date-formate');
  
  
  month_picker.onclick = () => {
      month_list.classList.remove('hideonce');
      month_list.classList.remove('hide');
      month_list.classList.add('show');
  };
  
  let dateClicked = false;
  let previousClickedDay = null;
  
  const generateCalendar = (month, year) => {
      let calendar_days = document.querySelector('.calendar-days');
      calendar_days.innerHTML = '';
      let calendar_header_year = document.querySelector('#year');
      let days_of_month = [
          31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31
      ];
  
      let currentDate = new Date();
  
      month_picker.innerHTML = month_names[month];
  
      calendar_header_year.innerHTML = year;
  
      let first_day = new Date(year, month);
  
      const isSameDate = (date1, date2) => {
          return (
              date1.getFullYear() === date2.getFullYear() &&
              date1.getMonth() === date2.getMonth() &&
              date1.getDate() === date2.getDate()
          );
      };
      
      for (let i = 0; i < 42; i++) {
          let day = document.createElement('div');
          let currentDay = i - first_day.getDay() + 1;
          let currentFullDate = new Date(year, month, currentDay);
      
          if (i >= first_day.getDay() && currentDay <= days_of_month[month]) {
              day.innerHTML = currentDay;
      
              day.addEventListener('click', function() {
                  if (previousClickedDay) {
                      previousClickedDay.classList.remove('clicked-date');
                  }
                  saveDate(currentFullDate);
                  day.classList.add('clicked-date');
                  previousClickedDay = day;
              });
      
              if (
                  currentDay === currentDate.getDate() &&
                  year === currentDate.getFullYear() &&
                  month === currentDate.getMonth()
              ) {
                  // Do not add 'disabled' class for the current date
              } else if (currentFullDate < currentDate && !isSameDate(currentFullDate, currentDate)) {
                  day.classList.add('disabled');
              }
      
              if (currentFullDate.getDay() === 3) {
                  day.classList.add('disabled');
                  if (i === first_day.getDay()) {
                      day.classList.add('wednesday');
                  }
              }
          } else {
              day.classList.add('disabled');
          }
      
          calendar_days.appendChild(day);
      }
      
  };
  
  //-----------------------------------------
  let clientDate = ""; // Initialize clientDate variable globally

  function saveDate(date) {
      // Format the date as YYYY-MM-DD
      const formattedDate = date.getFullYear() + '-' + 
                            ('0' + (date.getMonth() + 1)).slice(-2) + '-' + 
                            ('0' + date.getDate()).slice(-2);
      
      // Set the value of the hidden input field to the formatted date
      document.getElementById('client_date').value = formattedDate;
      console.log("Clicked date:", formattedDate);
  
      // Update clientDate variable
      clientDate = formattedDate;
  
      // Reset the state of time buttons
      resetTimeButtonsState();
  
      // Fetch appointments for the selected date
      fetchAppointmentsForDate(formattedDate);
  }
  
  function resetTimeButtonsState() {
      console.log("Resetting time buttons state...");
      const timeButtons = document.querySelectorAll('.time-buttons');
      timeButtons.forEach(button => {
          button.disabled = true; // Disable all time buttons initially
      });
  }
  
  function fetchAppointmentsForDate(date) {
      console.log("Fetching appointments for date:", date);
  
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'fetch_appointments.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
          console.log("XHR Ready State:", xhr.readyState);
          if (xhr.readyState === 4) {
              console.log("XHR Status:", xhr.status);
              if (xhr.status === 200) {
                  try {
                      const response = JSON.parse(xhr.responseText);
                      console.log("Response:", response);
                      disableBookedTimeButtons(date, response);
                  } catch (error) {
                      console.error("Error parsing JSON response: ", error);
                      console.log("Response text: ", xhr.responseText);
                  }
              } else {
                  console.error("Error fetching appointments: ", xhr.statusText);
              }
          }
      };
      // Ensure the date is properly sent in the request body
      const params = 'date=' + encodeURIComponent(date);
      console.log("Request params:", params);
      xhr.send(params);
  }
  
  function convertTo24HourClock(time) {
      const [timePart, meridiem] = time.split(' ');
      let [hours, minutes] = timePart.split(':').map(part => parseInt(part, 10));
  
      if (meridiem === 'PM' && hours < 12) {
          hours += 12;
      } else if (meridiem === 'AM' && hours === 12) {
          hours = 0;
      }
  
      return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
  }
  
  function disableBookedTimeButtons(clientDate, appointments) {
    console.log("Disabling time buttons based on existing appointments...");

    // Get current date and time
    const currentDate = new Date();
    const currentDateString = currentDate.toISOString().split('T')[0]; // YYYY-MM-DD
    const currentTime = currentDate.getHours() * 60 + currentDate.getMinutes(); // Current time in minutes since midnight

    // Get all time buttons
    const timeButtons = document.querySelectorAll('.time-buttons');

    // Enable or disable time buttons based on existing appointments and current time
    timeButtons.forEach(button => {
        const [buttonStart, buttonEnd] = button.value.split('-').map(time => time.trim());
        const buttonStartTime = convertTo24HourClock(buttonStart) + ':00';
        const buttonEndTime = convertTo24HourClock(buttonEnd) + ':00';

        // Convert button times to comparable format
        const [startHours, startMinutes] = buttonStart.split(':').map(part => parseInt(part, 10));
        const buttonTimeInMinutes = startHours * 60 + startMinutes;

        // Check if the button is for today and if the time has passed
        const isToday = clientDate === currentDateString;
        const isTimePassed = isToday && (currentTime > buttonTimeInMinutes);

        // Check if any appointment exactly matches the time slot
        const isBooked = appointments.some(appointment => {
            const appointmentStartTime = appointment.start_time.split(' ')[0];
            const appointmentEndTime = appointment.end_time.split(' ')[0];

            return buttonStartTime === appointmentStartTime && buttonEndTime === appointmentEndTime;
        });

        if (isBooked || isTimePassed) {
            button.disabled = true;
            console.log(`Time button (${button.value}) disabled for Client Date: ${clientDate}.`);
        } else {
            button.disabled = false;
            console.log(`Time button (${button.value}) enabled for Client Date: ${clientDate}.`);
        }
    });
}

  
  document.addEventListener('DOMContentLoaded', function() {
      resetTimeButtonsState(); // Reset time buttons state on page load
  
      const clientDateInput = document.getElementById('client_date');
  
      clientDateInput.addEventListener('change', function() {
          clientDate = this.value;
          fetchAppointmentsForDate(clientDate);
      });
  
      if (clientDate) {
          fetchAppointmentsForDate(clientDate);
      }
  });
  
  
//--------------------------------------------------------------------------------  
  
  const currentMonthValue = new Date().getMonth();
  const currentYearValue = new Date().getFullYear();
  
  month_names.forEach((e, index) => {
    let month = document.createElement('div');
    month.innerHTML = `<div>${e}</div>`;
    month_list.append(month);
  
    if (index < currentMonthValue && currentYearValue === new Date().getFullYear()) {
        month.classList.add('disabled');
    } else {
        month.onclick = () => {
            currentMonth.value = index;
            generateCalendar(currentMonth.value, currentYear.value);
            month_list.classList.replace('show', 'hide');
        };
    }
  });
  
  document.querySelector('#pre-year').onclick = () => {
    const minYear = currentYear.value - 1;
    if (minYear >= currentYearValue) {
        --currentYear.value;
        generateCalendar(currentMonth.value, currentYear.value);
    }
  };
  
  document.querySelector('#next-year').onclick = () => {
    const currentYearValue = new Date().getFullYear();
    if (currentYear.value < currentYearValue) {
        ++currentYear.value;
        generateCalendar(currentMonth.value, currentYear.value);
    }
  };
  
  let currentDate = new Date();
  let currentMonth = { value: currentDate.getMonth() };
  let currentYear = { value: currentDate.getFullYear() };
  generateCalendar(currentMonth.value, currentYear.value);
  

// const todayShowTime = document.querySelector('.time-formate');
// const todayShowDate = document.querySelector('.date-formate');

// const currshowDate = new Date();
// const showCurrentDateOption = {
//   year: 'numeric',
//   month: 'long',
//   day: 'numeric',
//   weekday: 'long',
// };
// const currentDateFormate = new Intl.DateTimeFormat(
//   'en-US',
//   showCurrentDateOption
// ).format(currshowDate);
// todayShowDate.textContent = currentDateFormate;

// setInterval(() => {
//   const timer = new Date();
//   const option = {
//       hour: 'numeric',
//       minute: 'numeric',
//       second: 'numeric',
//   };
//   const formateTimer = new Intl.DateTimeFormat('en-us', option).format(timer);
//   let time = `${`${timer.getHours()}`.padStart(
//       2,
//       '0'
//   )}:${`${timer.getMinutes()}`.padStart(
//       2,
//       '0'
//   )}: ${`${timer.getSeconds()}`.padStart(2, '0')}`;
//   todayShowTime.textContent = formateTimer;
// }, 1000);


// terms and service modals js

document.getElementById('terms_conditions').addEventListener('change', function () {
    var modalButton = document.getElementById('modalButton');
    if (this.checked) {
        // Simulate button click
        modalButton.click();
        
        // Apply click effect visually
        modalButton.classList.add('click-effect');
        setTimeout(function() {
            modalButton.classList.remove('click-effect');
        }, 300); // 300ms is the duration of the transform transition
    }
});

document.getElementById('acceptBtn').addEventListener('click', function () {
    var checkBox = document.getElementById('terms_conditions');
    checkBox.checked = true;
    var modal = document.getElementById('termsAndConditions');
    var modalInstance = bootstrap.Modal.getInstance(modal);
    modalInstance.hide();
});

document.getElementById('declineBtn').addEventListener('click', function () {
    var checkBox = document.getElementById('terms_conditions');
    checkBox.checked = false;
    var modal = document.getElementById('termsAndConditions');
    var modalInstance = bootstrap.Modal.getInstance(modal);
    modalInstance.hide();
});

document.querySelector('.label-checkbox-custom').addEventListener('click', function () {
    document.getElementById('modalButton').click();
});