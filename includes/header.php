<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Finbelt Microfinance</title>
  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Raleway&family=Ubuntu&display=swap" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <!-- Animate.css for premium entrance effects -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: eNno
  * Template URL: https://bootstrapmade.com/enno-free-simple-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
    /* Floating effect for the calculator */
    #loanCalculator {
      animation: float 3s ease-in-out infinite;
    }
    /* Pause floating animation on hover (or focus within) */
    #loanCalculator:hover,
    #loanCalculator:focus-within {
      animation-play-state: paused;
    }
    @keyframes float {
      0%   { transform: translateY(0); }
      50%  { transform: translateY(-10px); }
      100% { transform: translateY(0); }
    }
    border-bottom: 2px solid #f1f1f1;
      padding: 1rem 1.5rem;
    }
    .modal-title {
      font-weight: 700;
      font-size: 1.5rem;
      color: #333;
      margin: 0;
    }
    .btn-close {
      background: transparent;
      border: none;
      font-size: 1.5rem;
      opacity: 0.8;
    }
    .btn-close:hover {
      opacity: 1;
    }
    /* Left column animation styles */
    .modal-animation {
      background-color: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .modal-animation img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.02); }
      100% { transform: scale(1); }
    }
    /* Right column - form styles for a rich look */
    .modal-form {
      padding: 2rem;
      background: #fff;
    }
    .modal-form .logo-container {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .modal-form .logo-container img {
      width: 80px;
      height: auto;
    }
    .modal-form label {
      font-weight: 600;
      color: #555;
    }
    .modal-form .form-control {
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 0.75rem;
    }
    .modal-form button {
      font-weight: 600;
      border-radius: 5px;
      padding: 0.5rem 1.5rem;
    }
    /* Entrance animation for modals */
    .modal.fade .modal-dialog {
      transform: translate(0, -50px);
      transition: transform 0.3s ease-out;
    }
    .modal.show .modal-dialog {
      transform: translate(0, 0);
    }
  </style>
  
  
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
         <img src="assets/img/finbelt.png" alt=""> 
      <!--  <h1 class="sitename">Finbelt MicroFinance</h1>-->
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li class="dropdown"><a href="#"><span>Loans</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Apply Now</a></li>
             
         
            
            </ul>
          </li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

     <!-- Buttons to trigger modals -->
     <a class="btn-getstarted  " data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
      <a class="btn-getstarted " data-bs-toggle="modal" data-bs-target="#registerModal">Register</a>
    </div>
  </header>
  <main class="main">