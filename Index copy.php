<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Browlesque</title>
    
    <link rel="icon" href="./assets/images/icon/Browlesque-Icon.svg" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
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
                    <a class="nav-link" href="#">About us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
  <h1 class="fw-bold mt-2" id="browlesque">BROWLESQUE</h1>
    <img src="./assets/images/pictures/microblading2.jpg" id="microblading2" alt="First Image">

  <div class="button-appoint">
    <button type="button" class="btn btn-primary btn-primary-custom" onclick="window.location.href='book_appointment1.php'">BOOK YOUR APPOINTMENT NOW HERE!</button>
    </div>

    <div class="grid-container mt-2">
        <div class="grid-item">
            <img class="grid-image" src="./assets/images/pictures/microblading1.webp" alt="Image 1">
            <div class="grid-text">Lorem Ipsum</div>
            <div class="description-text">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Laudantium consectetur repellat ratione sint reiciendis impedit nostrum aut explicabo ipsa velit aperiam assumenda rem ipsum dicta sunt, quidem dignissimos quas sequi!</div> 
        </div>
        <div class="grid-item">
            <img class="grid-image" src="./assets/images/pictures/microblading1.webp" alt="Image 2">
            <div class="grid-text">Lorem Ipsum</div>
            <div class="description-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quibusdam omnis deleniti libero ut ratione eius distinctio quos, voluptatibus harum pariatur. Repellendus illum dicta qui alias nemo dolorum itaque expedita incidunt.</div> 
        </div>
        <div class="grid-item">
            <img class="grid-image" src="./assets/images/pictures/microblading1.webp" alt="Image 3">
            <div class="grid-text">Lorem Ipsum</div>
            <div class="description-text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita hic voluptatum dolorum nobis laudantium. Dolor et enim culpa. Deleniti quos autem ex odio vero esse fugit amet velit fugiat nemo.</div>
        </div>
    </div>

    <h2 class="fw-bold mt-5 text-start custom-margin-left" id="text-recommend">Most Recommended</h2>
    <h4 class="fw-lighter text-start custom-margin-left text-muted" id="text-sub-recommend">Loved by clients!</h4>

    <div class="image-container">
        <img src="./assets/images/pictures/microblading2.jpg" id="microblading2" class="mt-5" alt="First Image">
        <h4 class="fw-lighter mt-4 text-muted text-decoration-underline" id="text-seemore">See more information</h4>
    </div>

</div>

<footer id="footer">
  <div class="contact-info">
    <h2 class="fw-bold mt-5 text-start custom-margin-left" id="text-contact-us">Contact Us</h2>
    <img class="fb-icon" src="./assets/images/icon/Facebook.svg" alt="facebook">
    <h2 class="fw-bold mt-5 custom-margin-left" id="text-facebook">Facebook</h2>
    <img class="fb-icon" src="./assets/images/icon/Phone.svg" alt="phone">
    <h2 class="fw-bold mt-5 custom-margin-left" id="text-phone">09123456789</h2>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
