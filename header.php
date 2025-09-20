<?php
session_start();
include 'config.php';

// ✅ SIGNUP
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $First_name = $_POST['First_name'];
    $Last_name = $_POST['Last_name'];
    $Gender = $_POST['Gender'];
    $Email = $_POST['Email'];
    $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $Address = $_POST['Address'];
    $Country = $_POST['Country'];
    $City = $_POST['City'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO customers (First_name, Last_name, Gender, Email, Password, Address, Country, City, phone)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $First_name, $Last_name, $Gender, $Email, $Password, $Address, $Country, $City, $phone);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please login.');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// ✅ LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $identifier = trim($_POST['Email']); // Email ya Phone
    $Password   = $_POST['Password'];

    $sql = "SELECT * FROM customers WHERE Email=? OR phone=? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("❌ SQL Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("❌ Query Failed: " . $stmt->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        echo "<pre>DEBUG: "; print_r($row); echo "</pre>"; // 👈 pehle ye check karo

        if (password_verify($Password, $row['Password'])) {
            $_SESSION['username'] = $row['First_name'];
            $_SESSION['user_id']  = $row['id'];
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('❌ Password galat hai');</script>";
        }
    } else {
        echo "<script>alert('❌ User not found: " . htmlspecialchars($identifier) . "');</script>";
    }

    $stmt->close();
}


// ✅ LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>




<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="ThemeServices">
    <!-- Favicon Icon -->
    <link rel="icon" href="assets/img/favicon.png">
    <!-- Site Title -->
    <title>Arkdin – Air Conditioning Services HTML Template</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/slick.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <style>
    /* Modal Container */
.custom-modal {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.6);
  justify-content: center;
  align-items: center;
}

/* Modal Content */
.custom-modal-content {
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  width: 90%;
  max-width: 800px;
  position: relative;
  animation: fadeIn 0.3s ease-in-out;
  font-family: inherit;
}

/* Close Button */
.custom-modal-close {
  position: absolute;
  top: 15px;
  right: 20px;
  font-size: 28px;
  color: #333;
  cursor: pointer;
}

/* Form Grid */
.custom-form .custom-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 15px;
}

/* Input Fields */
.custom-form input,
.custom-form select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 14px;
}

/* Submit Button */
.custom-submit {
  margin-top: 20px;
  background-color: #0088cc;
  color: white;
  padding: 12px 25px;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s;
}
.custom-submit:hover {
  background-color: #006fa1;
}
#openModalBtn1{
margin-left:10px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none; /* ✅ by default hidden */
  position: absolute;
  background-color: #fff;
  min-width: 160px;
  box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
  z-index: 1;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.dropdown-content a {
  color: #333;
  padding: 10px 15px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
}

/* ✅ Show only on hover */
.dropdown:hover .dropdown-content {
  display: block;
}
.whatsapp-float {
  position: fixed;
  width: 60px;
  height: 60px;
  bottom: 20px;
  right: 20px;
  background-color: #25D366; /* ✅ Green color */
  color: #fff;
  border-radius: 50%;
  text-align: center;
  font-size: 30px;
  z-index: 100;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.2s;
}

.whatsapp-float:hover {
  transform: scale(1.1);
}

.whatsapp-float img {
  width: 35px;
  height: 35px;
}
#logopng{
    height: 20vh;
    

}
  </style>
  <body>
    
    <!-- Start Header Section -->
    <header class="cs_site_header cs_style_1 cs_heading_color cs_sticky_header">
      <div class="cs_top_header">
        <div class="container">
          <div class="cs_top_header_in">
            <div class="cs_top_header_left">Welcome to Our Urgent Technical & Repairing Services</div>
            <!-- <div class="cs_top_header_left">
              <div class="cs_header_social_links_wrap">
                <p class="mb-0">Follow Us On: </p>
                <div class="cs_header_social_links">
                  <a href="#" class="cs_social_btn cs_center">
                    <i class="fa-brands fa-linkedin-in"></i>
                  </a>
                  <a href="#" class="cs_social_btn cs_center">
                    <i class="fa-brands fa-twitter"></i>
                  </a>
                  <a href="#" class="cs_social_btn cs_center">
                    <i class="fa-brands fa-youtube"></i>
                  </a>
                  <a href="#" class="cs_social_btn cs_center">
                    <i class="fa-brands fa-facebook-f"></i>
                  </a>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
      <div class="cs_main_header cs_accent_bg">
        <div class="container">
          <div class="cs_main_header_in">
            <div class="cs_main_header_left">
              <a class="cs_site_branding" href="index.php">
                <img src="assets/img/logo.png" id="logopng" alt="Logo">
              </a>
            </div>
            <div class="cs_main_header_center">
              <div class="cs_nav">
                <ul class="cs_nav_list">
                  <li class="menu-item">
                    <a href="index.php">Home</a>
                  </li>
                  <li><a href="about-us.php">About Us</a></li>
                  <li class="menu-item-has-children">
                    <a href="service.php">Services</a>
                    <ul>
                      <li><a href="service.php">Services</a></li>
                      <li><a href="service-details.php">Service Details</a></li>
                    </ul>
                  </li>
                  <!-- <li class="menu-item-has-children">
                    <a href="#">Pages</a>
                    <ul>
                      <li><a href="team.php">Team</a></li>
                      <li><a href="team-details.php">Team Details</a></li>
                      <li><a href="projects.php">Projects</a></li>
                      <li><a href="project-details.php">Project Details</a></li>
                    </ul>
                  </li> -->
                 
                  <li><a href="contact.php">Contact</a></li>
                </ul>
              </div>
            </div>
           <div class="cs_main_header_right">
  <?php if (isset($_SESSION['username'])): ?>
      <div class="dropdown" style="position:relative;">
  <button class="cs_btn cs_style_1">
    <span>👤 <?php echo $_SESSION['username']; ?></span>
  </button>
  <div class="dropdown-content">
    <a href="update-details.php">Update Details</a>
    <a href="?logout=1">Logout</a>
  </div>
</div>
  <?php else: ?>
      <a href="#" class="cs_btn cs_style_1" id="openSignupBtn"><span>Signup</span></a>
      <a href="#" class="cs_btn cs_style_1" id="openLoginBtn"><span>Login</span></a>
  <?php endif; ?>
</div>


          </div>
        </div>
      </div>
    </header>
    <div class="cs_site_header_spacing_130"></div>
<!-- Custom Modal -->
<!-- Signup Modal -->
<div id="signupModal" class="custom-modal">
  <div class="custom-modal-content">
    <span class="custom-modal-close" id="closeSignupBtn">&times;</span>
    <h2>Signup</h2>
    <form action="" method="POST" class="custom-form">
      <input type="hidden" name="signup" value="1">
      <div class="custom-grid">
        <div><label>First Name</label><input type="text" name="First_name" required></div>
        <div><label>Last Name</label><input type="text" name="Last_name" required></div>
        <div><label>Gender</label>
          <select name="Gender" required>
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </div>
        <div><label>Email</label><input type="email" name="Email" required></div>
        <div><label>Password</label><input type="password" name="Password" required></div>
        <div><label>Phone</label><input type="text" name="phone" required></div>
        <div><label>Address</label><input type="text" name="Address" required></div>
        <div><label>Country</label><input type="text" name="Country" readonly value="UAE"></div>
        <div><label>City</label><input type="text" name="City" readonly value="Dubai"></div>
      </div>
      <button type="submit" class="custom-submit">Signup</button>
    </form>
  </div>
</div>



<!-- Login Modal -->
<div id="loginModal" class="custom-modal">
  <div class="custom-modal-content">
    <span class="custom-modal-close" id="closeLoginBtn">&times;</span>
    <h2>Login</h2>
    <form action="" method="POST" class="custom-form">
      <input type="hidden" name="login" value="1">
      <div class="custom-grid">
        <div><label>Email / Phone</label>
  <input type="text" name="Email" required>
</div>
    
        <div><label>Password</label><input type="password" name="Password" required></div>
      </div>
      <button type="submit" class="custom-submit">Login</button>
    </form>
  </div>
</div>

<script>
 // Signup Modal
const openSignupBtn = document.getElementById('openSignupBtn');
const closeSignupBtn = document.getElementById('closeSignupBtn');
const signupModal = document.getElementById('signupModal');

openSignupBtn.addEventListener('click', function (e) {
  e.preventDefault();
  signupModal.style.display = 'flex';
});
closeSignupBtn.addEventListener('click', function () {
  signupModal.style.display = 'none';
});

// Login Modal
const openLoginBtn = document.getElementById('openLoginBtn');
const closeLoginBtn = document.getElementById('closeLoginBtn');
const loginModal = document.getElementById('loginModal');

openLoginBtn.addEventListener('click', function (e) {
  e.preventDefault();
  loginModal.style.display = 'flex';
});
closeLoginBtn.addEventListener('click', function () {
  loginModal.style.display = 'none';
});

// Close modal when clicking outside
window.addEventListener('click', function (e) {
  if (e.target === signupModal) signupModal.style.display = 'none';
  if (e.target === loginModal) loginModal.style.display = 'none';
});

</script>
