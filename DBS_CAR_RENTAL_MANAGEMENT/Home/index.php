<?php
session_start();

//  Debugging ke liye session print karo

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <link
    rel="shortcut icon"
    href="../car rental images/Home/logo.png"
    type="image/x-icon" />
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home-Car Rental Managment</title>
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
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <header class="custom_container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <div class="logo">
          <img src="../car rental images/Home/logo.png" alt="Logo" />
        </div>

        <div class="center">
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="../Vehicals/Vehicals.php">Vehicles</a></li>
              <li class="nav-item"><a class="nav-link" href="../Details/details.php">Details</a></li>
              <li class="nav-item"><a class="nav-link" href="../about/about.php">About us</a></li>

              <!--  Sirf "Manager" ke liye "Add Vehicles" & "Branches" dikhaye -->
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Manager') : ?>
                <li class="nav-item">
                  <a class="nav-link" href="../car details/carDetails.php">Add Vehicles</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="../branch/branch.php">Branches</a>
                </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>

        <!--  Profile Dropdown -->
        <div class="dropdown">
          <a class="reg dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fa-solid fa-user"></i>
            <?php
            if (isset($_SESSION['employee_firstname']) && isset($_SESSION['employee_lastname'])) {
              echo $_SESSION['employee_firstname'] . " " . $_SESSION['employee_lastname'];
            }
            ?>
          </a>
          <ul class="dropdown-menu">
            <?php if (isset($_SESSION['role'])) : ?>
              <li><a class="dropdown-item" href="../Profile/Profile.php">Profile</a></li>
              <li><a class="dropdown-item" href="../Registered/logout.php">Logout</a></li>
            <?php else : ?>
              <li><a class="dropdown-item" href="../Registered/login.php">Login</a></li>
              <li><a class="dropdown-item" href="../Registered/Registered.php">Register</a></li>
            <?php endif; ?>
          </ul>
        </div>

      </div>
    </nav>
  </header>





  <!-- Header ended -->

  <!-- Main start  -->
  <main
    class="custom_container main d-flex justify-content-evenly align-items-center rounded-5 relative">
    <!-- Left -->
    <div class="width-30 top-z">
      <div class="card-sm">
        <h1 class="fs-1 fw-bolder">RIDE YOUR DREAMS WITH RIDEO</h1>
        <p class="fs-6">
          Drive the best. Enjoy top-tier Vehicles, exceptional service, and
          competitive rates. Elevate your journey with us today
        </p>

        <button type="button" class="btn btn_color">Explore</button>
      </div>
    </div>
    <!-- Right -->
    <form class="form_color p-4 border rounded-5 top-z">
      <div class="mb-3">
        <h2 class="text-center">Book your car</h2>
        <select
          class="form-select"
          id="exampleSelect"
          aria-label="Default select example">
          <option selected>Car type</option>
          <option value="1">Sports</option>
          <option value="2">Suv</option>
          <option value="3">Sedan</option>
        </select>
      </div>
      <div class="mb-3">
        <select
          class="form-select"
          id="exampleSelect"
          aria-label="Default select example">
          <option selected>Place of rental</option>
          <option value="1">Lahore</option>
          <option value="2">Gujranwala</option>
          <option value="3">Islamabad</option>
        </select>
      </div>
      <div class="mb-3">
        <select
          class="form-select"
          id="exampleSelect"
          aria-label="Default select example">
          <option selected>Place of return</option>
          <option value="1">Sialkot</option>
          <option value="2">Quetta</option>
          <option value="3">Faislabad</option>
        </select>
      </div>
      <div class="mb-3">
        <input
          type="date"
          class="form-control"
          id="exampleInputEmail1"
          aria-describedby="emailHelp"
          placeholder="Rental start date" />
      </div>

      <div class="mb-3">
        <input
          type="date"
          class="form-control"
          id="exampleInputEmail1"
          aria-describedby="emailHelp"
          placeholder="Return date" />
      </div>

      <button type="submit" class="btn btn-primary btn_form"><a href="../payment/payment.php">Book now</a></button>
    </form>

    <!-- Back  image -->
    <div class="absolute back_image">
      <img src="../car rental images/Home/Blue.png" alt="Blue" />
    </div>
  </main>
  <!-- Main end -->

  <!-- Three-landing -->
  <section
    class="Three-landing custom_container d-flex justify-content-evenly mt-5">
    <div class="gap-2 d-flex flex-column" style="width: 18rem">
      <i class="fa-solid fa-location-dot fa-8 text-center"></i>
      <div class="card-body">
        <h2 class="card-title text-black text-center">Availability</h2>
        <p class="card-text text-black">
          Discover the range of cars in your area
        </p>
      </div>
    </div>
    <div class="gap-2 d-flex flex-column" style="width: 18rem">
      <i class="fa-solid fa-truck fa-8 text-center"></i>
      <div class="card-body">
        <h2 class="card-title text-black text-center">Comfort</h2>
        <p class="card-text text-black">We provide the comfort at our best</p>
      </div>
    </div>
    <div class="gap-2 d-flex flex-column" style="width: 18rem">
      <i class="fa-solid fa-floppy-disk text-center fa-8"></i>
      <div class="card-body">
        <h2 class="card-title text-black text-center">Savings</h2>
        <p class="card-text text-black">
          We provide buget friendly deals that suit you
        </p>
      </div>
    </div>
  </section>

  <!-- Two-div -->
  <section
    class="two_div custom_container mt-5 d-flex justify-content-evenly rounded-5">
    <!-- Left -->
    <div class="d-flex justify-content-evenly align-items-center img_30">
      <div>
        <img
          src="../car rental images/Home/WhatsApp Image 2024-12-08 at 11.55.23 AM.jpeg"
          alt=""
          width="100%" />
      </div>
    </div>
    <!-- Left -->

    <div class="width-30 d-flex flex-column justify-content-evenly">
      <div class="card-sm">
        <h2 class="fs-4 fw-bolder">RIDE YOUR DREAMS WITH RIDEO</h2>
        <p class="fs-6">
          Drive the best. Enjoy top-tier Vehicles, exceptional service, and
          competitive rates. Elevate your journey with us today
        </p>

        <button type="button" class="btn btn_color">Explore</button>
      </div>
      <div class="card-sm">
        <h2 class="fs-4 fw-bolder">RIDE YOUR DREAMS WITH RIDEO</h2>
        <p class="fs-6">
          Drive the best. Enjoy top-tier Vehicles, exceptional service, and
          competitive rates. Elevate your journey with us today
        </p>

        <button type="button" class="btn btn_color">Explore</button>
      </div>
      <div class="card-sm">
        <h2 class="fs-4 fw-bolder">RIDE YOUR DREAMS WITH RIDEO</h2>
        <p class="fs-6">
          Drive the best. Enjoy top-tier Vehicles, exceptional service, and
          competitive rates. Elevate your journey with us today
        </p>

        <button type="button" class="btn btn_color">Explore</button>
      </div>
    </div>
  </section>

  <!-- six-div -->
  <section class="custom_container mt-5">
    <h2 class="fs-1 width-[5%]">Choose the car that suits you</h2>
    <div class="container">
      <?php
      include('../php/conn.php');  // Database connection
      $readQuery = $conn->query("SELECT * FROM car");

      if ($readQuery->num_rows > 0) {
        while ($row = $readQuery->fetch_assoc()) {
      ?>
          <div class="col">
            <div class="card p-2" style="width: 19rem">
              <div class="card_img">
                <img src="<?= $row['imageUrl'] ?>" width="80">
              </div>
              <div class="color-[black]">
                <div class="d-flex justify-content-between pt-2">
                  <div class="left">
                    <h3 class="card-title text-black"><?php echo $row['name']; ?></h3>
                    <blockquote class="text-black"><?php echo $row['type']; ?></blockquote>
                  </div>
                  <div class="right pt-2">
                    <h3 class="card-title text-black">$<?php echo $row['price']; ?></h3>
                    <blockquote class="text-black">per day</blockquote>
                  </div>
                </div>
                <div class="d-flex justify-content-between">
                  <div class="text-black"><?php echo $row['Color']; ?></div>
                  <div class="text-black"><?php echo $row['Model']; ?></div>
                </div>

                <!-- Check if car is booked -->
                <?php if ($row['status'] === 'booked') : ?>
                  <button class="btn btn-secondary mt-2" disabled>Already Booked</button>
                <?php else : ?>
                  <a href="../Details/details.php?car_id=<?php echo $row['CarID']; ?>" class="btn btn_color_car">View Details</a>
                <?php endif; ?>

                <!-- Manager ke liye extra options -->
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Manager') : ?>
                  <a href="../Details/maintain.php?car_id=<?php echo $row['CarID']; ?>" class="btn mt-4 btn_color_car">Maintain</a>
                  <a href="../car details/carDetails.php?car_id=<?php echo $row['CarID']; ?>" class="btn mt-4 btn_color_car">Update</a>

                <?php endif; ?>

              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<p>No Cars Found</p>";
      }
      ?>

    </div>
  </section>

  <!-- Four divs -->
  <section class="facts_section custom_container mt-5">
    <h2 class="head_2">Facts In Numbers</h2>
    <p>
      Rated 5 stars by 95% of our customers for exceptional service and
      Vehicles quality Offering flexible rental periods from one day to one
      month, with easy extensions available
    </p>


  </section>

  <!-- Two-div -->
  <section
    class="two_div custom_container mt-5 d-flex justify-content-evenly">
    <!-- Left -->

    <div
      class="width-30 d-flex flex-column justify-content-center align-items-start">
      <h2 class="head_3">Downlod mobile app</h2>
      <p>
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nulla
        quibusdam omnis enim perspiciatis. Impedit ducimus debitis tempore
        dignissimos ab saepe ipsum repudiandae assumenda harum, nemo earum,
        minus eveniet iusto adipisci. Hic aut consectetur magnam repellat
        cumque vel illo neque vitae.
      </p>
      <!-- Butttons -->

      <div
        class="images_icons_stores d-flex justify-content-evenly align-items-center">
        <div class="image width-[30rem] pointer">
          <img
            src="../car rental images/Home/app.png"
            alt="App store"
            width="100%" />
        </div>
        <div class="image width-[30rem] pointer">
          <img
            src="../car rental images/Home/play.png"
            alt="App store"
            width="40%" />
        </div>
      </div>
    </div>
    <!-- Left -->
    <div class="d-flex justify-content-center align-items-center img_30">
      <div>
        <img
          src="../car rental images/Home/iPhone 14 Pro - Silver - Portrait.png"
          alt=""
          width="100%" />
      </div>
      <div>
        <img
          src="../car rental images/Home/iPhone 14 Pro - Silver - Portrait.png"
          alt=""
          width="100%" />
      </div>
    </div>
  </section>

  <!-- search divs -->
  <section class="search_section custom_container mt-5">
    <div class="d-flex justify-content-evenly align-items-center gap">
      <div class="left width-50">
        <h2 class="head_2">Enjoy every mile adorable companionship</h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis
          voluptas hic repellat voluptate iusto. Culpa repellat tempora quam
          assumenda nisi.
        </p>

        <div class="search d-flex flex-column align-items-start gap">
          <input type="text" placeholder="City" />
          <button type="submit" class="btn_search">search</button>
        </div>
      </div>
      <!-- right -->
      <div class="width-30 d-flex justify-content-center align-items-center">
        <img
          src="../car rental images/Home/car-icon-png-4272.png"
          alt=""
          width="100%" />
      </div>
    </div>
  </section>

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