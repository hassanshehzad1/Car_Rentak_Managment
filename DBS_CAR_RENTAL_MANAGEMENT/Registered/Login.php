<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" href="/Home/logo.png" type="image/x-icon" />
  <title>Registered-Car rental Managment</title>
  <link rel="shortcut icon" href="/Home/logo.png" type="image/x-icon" />
  <!-- Bootstrap link  css-->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous" />

  <!-- Font awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
    integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />

  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&display=swap"
    rel="stylesheet" />

  <!-- Link css -->
  <link rel="stylesheet" href="reg.css" />

<body>
  <header class="custom_container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <div class="logo">
          <img src="/Home/logo.png" alt="" />
        </div>

        <div class="center">
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a
                  class="nav-link active"
                  aria-current="page"
                  href="../Home/index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../Vehicals/Vehicals.php">Vehicles</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../Details/details.php">Details</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../about/about.php">About us</a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                  href="../Contact Us/Contact Us/Contact.php">Contact us</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="">
          <i class="fa-solid fa-user">+</i>
        </div>
      </div>
    </nav>
  </header>
  <div class="login mt-4">
    <form action="../php/login.php" method="POST">
      <h2>Manager Login</h2>
      <input type="email" name="email" placeholder="Email" required />
      <input type="text" name="Password" placeholder="Password" required />
      <button type="submit" name="login">Login</button>
    </form>
  </div>

  </div>
  <!-- Footer  -->
  <footer class="custom_container footer_section mt-5">
    <!-- row -->
    <div class="row">
      <!-- Col -->
      <div class="col">
        <!-- div box -->
        <div class="box d-flex flex-column justify-content-evenly gap-10">
          <div class="d-flex justify-content-start gap-2 fs-2">
            <i class="fa-solid fa-car"></i>
            <h2 class="">Car Rental</h2>
          </div>

          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo
            doloremque repudiandae deleniti mollitia libero! Mollitia ducimus
            odit, maxime excepturi, labore consequatur recusandae ab animi
            totam officiis itaque praesentium quos ullam?
          </p>

          <div class="icons d-flex justify-content-evenly">
            <i class="fa-brands fa-facebook fs-1 pointer"></i>
            <i class="fa-brands fa-instagram fs-1"></i>
            <i class="fa-brands fa-twitter fs-1"></i>
            <i class="fa-brands fa-youtube fs-1"></i>
          </div>
        </div>
      </div>
      <!-- Col -->
      <!-- Col -->
      <div class="col">
        <!-- div box -->
        <div class="box d-flex flex-column justify-content-evenly gap-10">
          <div class="d-flex justify-content-start gap-2 fs-2">
            <i class="fa-solid fa-envelope"></i>
            <h2 class="fs-6">Email<br />email@gmail.com</h2>
          </div>

          <div class="d-flex flex-column align-items-start">
            <h3>Vehicles</h3>

            <ul class="icons d-flex flex-column align-items-start">
              <li class="">Sedan</li>
              <li class="">Cabrilot</li>
              <li class="">Pick up</li>
              <li class="">Minivan</li>
              <li class="">SUV</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Col -->
      <!-- Col -->
      <div class="col">
        <!-- div box -->
        <div class="box d-flex flex-column justify-content-evenly gap-10">
          <div class="d-flex justify-content-start gap-2 fs-2">
            <i class="fa-solid fa-location-crosshairs"></i>
            <h2 class="fs-6">Address<br />Oxford Ave,Cary NC 27511</h2>
          </div>

          <div class="d-flex flex-column align-items-start">
            <h3>Usefull links</h3>

            <ul class="icons d-flex flex-column align-items-start">
              <li class="">About Us</li>
              <li class="">Contact Us</li>
              <li class="">Gallery</li>
              <li class="">Blog</li>
              <li class="">F.A.Q</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Col -->
      <div class="col">
        <!-- div box -->
        <div class="box d-flex flex-column justify-content-evenly gap-10">
          <div class="d-flex justify-content-start gap-2 fs-2">
            <i class="fa-solid fa-phone"></i>
            <h2 class="fs-6">Phone<br />+92389239</h2>
          </div>

          <h3>Downlod APP</h3>

          <div class="images">
            <div class="img width_10">
              <img src="/app.png" alt="" width="100%" />
            </div>
            <div class="img width_10">
              <img src="play.png" alt="" width="100%" />
            </div>
          </div>
        </div>
      </div>

      <!-- Col -->
    </div>

    <!-- Copyright -->
    <div class="copyright">
      <p class="text-center fs-5 mt-3">
        @Copyright Car Rental 2024 - Design by Wala loog
      </p>
    </div>
  </footer>

  <!-- Bootstrap js -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>