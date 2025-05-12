<?php
require_once 'includes/config.php';
require_once 'includes/autoload.php';
include 'includes/header.php';
?>
<style>
  /* Premium card styling with shadow, border radius, and transition for hover effects */
  .premium-card {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  /* Hover effect to slightly lift the card */
  .premium-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
  }

  /* Optional icon styling for consistency */
  .premium-card .icon {
    font-size: 40px;
    color: #007bff;
    margin-bottom: 15px;
  }

  /* Typography for a premium feel */
  .premium-card h4 {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-weight: 700;
    margin-bottom: 15px;
  }

  .premium-card p {
    font-family: 'Open Sans', sans-serif;
    line-height: 1.6;
    color: #555;
  }
</style>

 <!-- Login Modal -->
 <div class="modal fade animate__animated" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content animate__animated animate__zoomIn">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Client Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-0">
            <!-- Left Column: Animated GIF -->
            <div class="col-md-6 modal-animation d-none d-md-block animate__animated animate__slideInLeft">
              <!-- Replace with your premium auction GIF -->
              <img src="assets/img/money.gif" alt="Auction Animation">
            </div>
            <!-- Right Column: Login Form -->
            <div class="col-md-6 modal-form">
              <!-- Logo above input fields -->
              <div class="logo-container">
                <img src="assets/img/finbelt.png" alt="Finbelt Logo">
              </div>
              <form method="post" action="login.php">
                <div class="mb-3">
                  <label for="username_email" class="form-label">Username or Email:</label>
                  <input type="text" id="username_email" name="username_email" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password:</label>
                  <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background-color:#39b54a;">Login</button>
              </form>
              <p class="mt-3 text-center">
                Don't have an account? 
                <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">
                  Register Here
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal fade animate__animated" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content animate__animated animate__zoomIn">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Client Registration</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-0">
            <!-- Left Column: Animated GIF -->
            <div class="col-md-6 modal-animation d-none d-md-block animate__animated animate__slideInLeft">
              <!-- Replace with your premium loan GIF -->
              <img src="assets/img/money.gif" alt="Loan Animation">
            </div>
            <!-- Right Column: Registration Form -->
            <div class="col-md-6 modal-form">
              <!-- Logo above input fields -->
              <div class="logo-container">
                <img src="assets/img/finbelt.png" alt="Finbelt Logo">
              </div>
              <form method="post" action="register.php">
                <div class="mb-3">
                  <label for="username" class="form-label">Username:</label>
                  <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email:</label>
                  <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">Password:</label>
                  <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password:</label>
                  <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="background-color:#39b54a;">Register</button>
              </form>
              <p class="mt-3 text-center">
                Already have an account? 
                <a href="#" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
                  Login Here
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Hero Section -->
<section id="hero" class="hero section">
  <div class="container">
    <div class="row gy-4">
      <!-- Left Column: Carousel with text content -->
      <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="fade-up">
        <div id="heroTextCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
              <h1>Personal Loans</h1>
              <p>
Flexible loan options tailored to your personal needs with competitive rates.
</p>
              <div class="d-flex">
                <a href="#about" class="btn-get-started">Discover More</a>
                <a href="" class="glightbox btn-watch-video d-flex align-items-center">
                  <i class="bi bi-play-circle"></i><span>Register Now</span>
                </a>
              </div>
            </div>
            <!-- Slide 2 -->
            <div class="carousel-item">
              <h1>Business Loans</h1>
              <p>
Empower your growth with loans designed to support and expand your business.</p>
              <div class="d-flex">
                <a href="#services" class="btn-get-started">Our Services</a>
                <a href="" class="glightbox btn-watch-video d-flex align-items-center">
                  <i class="bi bi-play-circle"></i><span>Register Now</span>
                </a>
              </div>
            </div>
            <!-- Slide 3 -->
            <div class="carousel-item">
              <h1>Auctions</h1>
              <p>Repurchase your defaulted collateral, or bid on already existing auctions.</p>
              <div class="d-flex">
                <a href="#portfolio" class="btn-get-started">Discover More</a>
                <a href="#services" class="glightbox btn-watch-video d-flex align-items-center">
                  <i class="bi bi-play-circle"></i><span>Register Now</span>
                </a>
              </div>
            </div>
          </div>
          <!-- Carousel Controls -->
          <button class="carousel-control-prev" type="button" data-bs-target="#heroTextCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#heroTextCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
      <!-- Right Column: Loan Calculator replacing the Hero Image -->
      <div class="col-lg-6 order-1 order-lg-2 d-flex align-items-center justify-content-center" data-aos="zoom-out" data-aos-delay="100">
        <div id="loanCalculator" class="loan-calculator" style="background: #fff; padding: 40px; width: 100%; max-width: 500px; box-shadow: 0 2px 15px rgba(0,0,0,0.2); border-radius: 8px;">
          <h4 style="font-size: 1.8rem; margin-bottom: 20px;">Loan Calculator</h4>
          <form id="calculatorForm">
            <div class="mb-3">
              <label for="loanAmount" class="form-label">Loan Amount (ZMW)</label>
              <input type="number" class="form-control" id="loanAmount" min="1000" placeholder="Enter loan amount" required>
            </div>
            <div class="mb-3">
              <label for="loanMonths" class="form-label">Duration (months)</label>
              <input type="number" class="form-control" id="loanMonths" min="1" placeholder="Enter duration in months" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="background-color:#39b54a;">Calculate</button>
          </form>
          <div id="result" class="mt-3"></div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Hero Section -->

<script>
  // Loan Calculator Script
  document.getElementById('calculatorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var amount = parseFloat(document.getElementById('loanAmount').value);
    var months = parseInt(document.getElementById('loanMonths').value, 10);
    
    // Check minimum loan amount
    if (amount < 1000) {
      document.getElementById('result').innerHTML = '<div class="alert alert-danger">Minimum loan amount is ZMW 1000.</div>';
      return;
    }
    
    // Calculate interest and repayment (35% monthly interest)
    var totalInterest = amount * 0.35 * months;
    var totalRepayment = amount + totalInterest;
    
    document.getElementById('result').innerHTML = 
      '<p>Total Interest: ZMW ' + totalInterest.toFixed(2) + '</p>' +
      '<p>Total Repayment: ZMW ' + totalRepayment.toFixed(2) + '</p>';
  });
</script>




 <!-- How It Works Section -->
<section id="how-it-works" class="how-it-works section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <h2>How It Works</h2>
      <p>Follow these three simple steps to secure your financing with ease and confidence.</p>
    </div>
    <div class="row gy-4">
      <!-- Step 1: Apply for a Loan -->
      <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="100">
        <div class="premium-card step-item position-relative hover-effect">
          <div class="icon">
            <i class="bi bi-pencil-square icon"></i>
          </div>
          <h4><a href="" class="stretched-link">Apply for a Loan</a></h4>
          <p>Fill out a quick and easy application form to get started with your loan process.</p>
        </div>
      </div>
      <!-- End Step 1 -->

      <!-- Step 2: Application Review -->
      <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="200">
        <div class="premium-card step-item position-relative hover-effect">
          <div class="icon">
            <i class="bi bi-search icon"></i>
          </div>
          <h4><a href="" class="stretched-link">Application Review</a></h4>
          <p>Our experts review your application thoroughly to ensure you meet all the necessary criteria.</p>
        </div>
      </div>
      <!-- End Step 2 -->

      <!-- Step 3: Get Financing -->
      <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="300">
        <div class="premium-card step-item position-relative hover-effect">
          <div class="icon">
            <i class="bi bi-currency-dollar icon"></i>
          </div>
          <h4><a href="" class="stretched-link">Get Financing</a></h4>
          <p>Once approved, receive your funds quickly and efficiently to meet your financial needs.</p>
        </div>
      </div>
      <!-- End Step 3 -->
    </div>
  </div>
</section>



        </div>

      </div>

    </section><!-- /Featured Services Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>About Us<br></span>
        <h2>About</h2>
        <p>We offer collateral based loans starting from as low as ZMW 1000 at competitive rates. Our system provides fast approval, secure transactions, and an innovative auction mechanism in case of default.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">
          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="100">
            <img src="assets/img/money.gif" class="img-fluid" width="600" alt="">
            <a href="#about" class="glightbox pulsating-play-btn"></a>
          </div>
          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="200">
            <h3>Summary</h3>
            <p class="fst-italic">
             Finbelt Microfinance is a dynamic microfinance lending business designed to 
offer rapid, high-yield loans secured by collateral. Our innovative model offers 
loans at a competitive monthly interest rate of 35%. In the event of a default, our structured collateral liquidation process includes an 
auction mechanism that gives clients the opportunity to repurchase their assets  
ensures that our principal is recovered with added value.
            </p>
            <ul>
              <li><i class="bi bi-check2-all"></i> <b>Microfinance Loans:</b><span> Providing short-term loans with a monthly interest 
rate of 35%.</span></li>
              <li><i class="bi bi-check2-all"></i><b>Collateral-Backed Lending:</b> <span> Securing loans with client collateral to 
minimize risk. </span></li>
              <li><i class="bi bi-check2-all"></i> <b>Auctioning:</b><span> In case of default, collateral is auctioned. 
Clients are given a chance to repurchase their assets, with Finbelt Microfinance earning a 2% listing fee on the final sale value.</span></li>
            </ul>
            <p>
              our business is positioned to deliver superior financial services while fostering long-term client relationships.
            </p>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Clients</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-6 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Total Auctions</p>
            </div>
          </div><!-- End Stats Item -->

   <!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Our Services</span>
        <h2>Services</h2>
        <p>Explore our range of tailored loan solutions designed to meet your financial needs.</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="premium-card service-item position-relative">
              <div class="icon">
                <i class="bi bi-person icon"></i>
              </div>
              <a href="service-details.html" class="stretched-link">
                <h3>Personal Loans</h3>
              </a>
              <p>Flexible loan options tailored to your personal needs with competitive rates.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="premium-card service-item position-relative">
              <div class="icon">
                <i class="bi bi-briefcase icon"></i>
              </div>
              <a href="service-details.html" class="stretched-link">
                <h3>Business Loans</h3>
              </a>
              <p>Empower your growth with loans designed to support and expand your business.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="premium-card service-item position-relative">
              <div class="icon">
                <i class="bi bi-cart-check-fill"></i>
              </div>
              <a href="service-details.html" class="stretched-link">
                <h3>Auctions</h3>
              </a>
              <p>Repurchase your defaulted collateral, or bid on already existing auctions.</p>
            </div>
          </div><!-- End Service Item -->

          
        </div>

      </div>

    </section><!-- /Services Section -->

   

        </div>

      </div>

    </section><!-- /Portfolio Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Thoughts from Corporate Leaders</span>
        <h2>Thoughts from corporate leaders</h2>
            <p>Access thoughts from corporate leaders.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper" data-speed="600" data-delay="5000" data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item" "="">
            <p>
              <i class=" bi bi-quote quote-icon-left"></i>
                <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Saul Goodman</h3>
                <h4>Ceo &amp; Founder</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                <h3>Sara Wilsson</h3>
                <h4>Designer</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                <h3>Jena Karlis</h3>
                <h4>Store Owner</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                <h3>Matt Brandon</h3>
                <h4>Freelancer</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                <h3>John Larson</h3>
                <h4>Entrepreneur</h4>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Call To Action Section -->
  

  

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>Contact Us!</span>
        <h2>Contact</h2>
        <p>Contact us!</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-5">

            <div class="info-wrap">
              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <div>
                  <h3>Address</h3>
                  <p>Lusaka Zambia</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone flex-shrink-0"></i>
                <div>
                  <h3>Call Us</h3>
                  <p>+260 978 081 408</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-envelope flex-shrink-0"></i>
                <div>
                  <h3>Email Us</h3>
                  <p>info@finbeltmicro.com</p>
                </div>
              </div><!-- End Info Item -->

              <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus" frameborder="0" style="border:0; width: 100%; height: 270px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
          </div>

          <div class="col-lg-7">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
              <div class="row gy-4">

                <div class="col-md-6">
                  <label for="name-field" class="pb-2">Your Name</label>
                  <input type="text" name="name" id="name-field" class="form-control" required="">
                </div>

                <div class="col-md-6">
                  <label for="email-field" class="pb-2">Your Email</label>
                  <input type="email" class="form-control" name="email" id="email-field" required="">
                </div>

                <div class="col-md-12">
                  <label for="subject-field" class="pb-2">Subject</label>
                  <input type="text" class="form-control" name="subject" id="subject-field" required="">
                </div>

                <div class="col-md-12">
                  <label for="message-field" class="pb-2">Message</label>
                  <textarea class="form-control" name="message" rows="10" id="message-field" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Send Message</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>
  <?php
  include 'includes/footer.php';
  ?>