<?php
include('../php/conn.php');
session_start();

// Agar "car_id" URL me exist karti hai
if (isset($_GET['car_id'])) {
  $car_id = $_GET['car_id'];

  // Car ka data fetch karna
  $stmt = $conn->prepare("SELECT * FROM car WHERE CarID = ?");
  $stmt->bind_param("i", $car_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $car = $result->fetch_assoc();
  } else {
    echo "<p>Car details not found!</p>";
    exit();
  }
} else {
  echo "<p>No Car Selected</p>";
  exit();
}

if (isset($_SESSION['customer_id'])) {
  $rentUrl = "../BookDetails/book.php?car_id=" . $car_id . "&customer_id=" . $_SESSION['customer_id'];
} else {
  $rentUrl = "../Contact Us/Contact Us/Contact.php?redirect=payment&car_id=" . $car_id;
}

//  Debugging: Print URL
echo "<script>console.log('Redirect URL: " . $rentUrl . "');</script>";


?>






<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Details-Rental Managment System</title>
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
  <link
    rel="shortcut icon"
    href="../car rental images/Home/logo.png"
    type="image/x-icon" />
  <!-- Google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&display=swap"
    rel="stylesheet" />

  <!-- Link css -->

  <link rel="stylesheet" href="details.css" />
</head>

<body>
  <!-- Navbar -->

  <header class="custom_container">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <div class="logo">
          <img src="../car rental images/Home/logo.png" alt="logo" />
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
  <!-- Header ended -->

  <section class="two_div custom_container mt-5">
    <div class="d-flex justify-content-between">
      <!-- Left -->
      <div class="left w-40">
        <h1 class="fs-1 fw-bold"><?php echo $car['name']; ?></h1>
        <h2 class="fs-5 fw-normal">
          $<?php echo $car['price']; ?>/ <span class="fs-4 fw-lighter">day</span>
        </h2>

        <div class="img-full img_25">
          <!-- <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>" /> -->
        </div>

        <div class="image_parts d-flex justify-content-evenly mt-4">
          <div class="img_short_part">
            <!-- <img src="<?php echo $car['image']; ?>" alt=""> -->
          </div>
          <div class="img_short_part">
            <img src="../car rental images/PICS CARS/12.jpg" alt="">
          </div>
          <div class="img_short_part">
            <img src="../car rental images/Home/car-icon-png-4272.png" alt="">
          </div>
        </div>
      </div>

      <!-- Right -->
      <div class="right">
        <h2>Technical Specification</h2>

        <div class="grid">
          <div class="row">
            <div class="col back_gray d-flex flex-column align-items-start">
              <i class="fa-solid fa-car fs-2 py-4"></i>
              <h3 class="data pt-3">Model</h3>
              <blockquote class="pb-2"><?php echo $car['Model']; ?></blockquote>
            </div>
            <div class="col back_gray d-flex flex-column align-items-start">
              <i class="fa-solid fa-palette fs-2 py-4"></i>
              <h3 class="data pt-3">Color</h3>
              <blockquote class="pb-2"><?php echo $car['Color']; ?></blockquote>
            </div>
            <div class="col back_gray d-flex flex-column align-items-start">
              <i class="fa-solid fa-gas-pump fs-2 py-4"></i>
              <h3 class="data pt-3">Type</h3>
              <blockquote class="pb-2"><?php echo $car['type']; ?></blockquote>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col back_gray d-flex flex-column align-items-start">
              <i class="fa-solid fa-cogs fs-2 py-4"></i>
              <h3 class="data pt-3">Name</h3>
              <blockquote class="pb-2"><?php echo $car['name']; ?></blockquote>
            </div>
            <div class="col back_gray d-flex flex-column align-items-start">
              <i class="fa-solid fa-calendar fs-2 py-4"></i>
              <h3 class="data pt-3">Year</h3>
              <blockquote class="pb-2"><?php echo $car['YearOfManufacture']; ?></blockquote>
            </div>
            <div class="col back_gray d-flex flex-column align-items-start">
              <i class="fa-solid fa-road fs-2 py-4"></i>
              <h3 class="data pt-3">Mileage</h3>
              <blockquote class="pb-2"><?php echo $car['Mileage']; ?> km</blockquote>
            </div>
          </div>
          <div class="mt-4 mx-auto">
            <!--  Rent a Car Button with Dynamic Redirect -->
            <div class="mt-4 mx-auto">
              <button type="button" class="btn bc-color">
                <a href="<?php echo $rentUrl; ?>">Rent a Car</a> </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Equipment -->
  <section class="custom_container mt-3 d-flex justify-content-between">
    <div class="left-width"></div>

    <div class="right mt-3 w-40">
      <h2>Car Equipment</h2>
      <!-- Each -->
      <div class="row">
        <div class="d-flex justify-content-start align-items-center col">
          <div
            class="d-flex justify-content-center align-items-center bc_green">
            <i class="fa-solid fa-check"></i>
          </div>
          <h3 class="ml">ABS</h3>
        </div>
        <div class="d-flex justify-content-start align-items-center col">
          <div
            class="d-flex justify-content-center align-items-center bc_green">
            <i class="fa-solid fa-check"></i>
          </div>
          <h3 class="ml">ABS</h3>
        </div>
      </div>
      <!-- Each -->
      <div class="row mt-2">
        <div class="d-flex justify-content-start align-items-center col">
          <div
            class="d-flex justify-content-center align-items-center bc_green">
            <i class="fa-solid fa-check"></i>
          </div>
          <h3 class="ml">ABS</h3>
        </div>
        <div class="d-flex justify-content-start align-items-center col">
          <div
            class="d-flex justify-content-center align-items-center bc_green">
            <i class="fa-solid fa-check"></i>
          </div>
          <h3 class="ml">ABS</h3>
        </div>
      </div>
      <!-- Each -->
      <div class="row mt-2">
        <div class="d-flex justify-content-start align-items-center col">
          <div
            class="d-flex justify-content-center align-items-center bc_green">
            <i class="fa-solid fa-check"></i>
          </div>
          <h3 class="ml">ABS</h3>
        </div>
        <div class="d-flex justify-content-start align-items-center col">
          <div
            class="d-flex justify-content-center align-items-center bc_green">
            <i class="fa-solid fa-check"></i>
          </div>
          <h3 class="ml">ABS</h3>
        </div>
      </div>
    </div>
  </section>

  <!-- six-div -->
  <section class="custom_container mt-5">
    <div class="all d-flex justify-content-between align-items-center">
      <h2 class="fs-1 width-[5%]">Other Cars</h2>
      <h2 class="fs-5 pointer">View All -></h2>
    </div>
    <div class="container">
      <!-- row -->
      <div class="row mb-3">
        <?php
        include('../php/conn.php');  // Database connection
        $readQuery = $conn->query("SELECT * FROM car");

        if ($readQuery->num_rows > 0) {
          while ($row = $readQuery->fetch_assoc()) {
        ?>
            <div class="col">
              <div class="card p-2" style="width: 19rem">
                <div class="card_img">
                  <!-- <img src="<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" /> -->
                </div>
                <div class="color-[black]">
                  <div class="d-flex justify-content-between pt-2">
                    <div class="left">
                      <h3 class="card-title  text-black"><?php echo $row['name']; ?></h3>
                      <blockquote class="text-black"><?php echo $row['type']; ?></blockquote>
                    </div>
                    <div class="right pt-2">
                      <h3 class="card-title text-black">$<?php echo $row['price']; ?></h3>
                      <blockquote class="text-black">per day</blockquote>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between">
                    <div class="text-black">
                      <p class=""></p>

                      <?php echo $row['Color']; ?>
                    </div>
                    <div class="text-black"><?php echo $row['Model']; ?></div>
                    <div class="text-black">

                    </div>
                  </div>
                  <a href="../Details/details.php?car_id=<?php echo $row['CarID']; ?>" class="btn btn_color_car">View Details</a>
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