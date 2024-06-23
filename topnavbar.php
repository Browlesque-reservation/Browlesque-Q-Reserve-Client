 <!-- Header & Navbar Section -->
 <header>
      <nav>
        <div class="nav_logo nav-logo">
          <a href="index.php">
            <h2><img src="Assets/images/icon/Browlesque.png"></h2>
          </a>
        </div>

        <input type="checkbox" id="click" />
        <label for="click">
          <i class="menu_btn bx bx-menu"></i>
          <i class="close_btn bx bx-x"></i>
        </label>

        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a class="<?php echo $isEmpty ? 'disabled' : ''; ?>" href="<?php echo $isEmpty ? '#' : 'book_appointment.php'; ?>">Book Appointment</a></li>
          <li><a href="index.php#about_us_section">About Us</a></li>
        </ul>
      </nav>
    </header>