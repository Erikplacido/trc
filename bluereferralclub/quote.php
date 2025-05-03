<!-- index.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Blue Facility Services</title>
  <link rel="stylesheet" href="quote/landing.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

  <!-- Sticky Header -->
  <header>
    <div class="container header-inner">
      <a href="/" class="logo"><img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/11/logo_blue.png" alt="Blue Logo" style="height:40px;"></a>
      <nav>
        <ul class="nav-links">
          <li><a href="#services">Services</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#work">Recent Work</a></li>
          <li><a href="#about">About Us</a></li>
        </ul>
        <button class="hamburger" aria-label="Open menu">&#9776;</button>
      </nav>
      <div class="header-ctas">
        <a href="tel:+180057711" class="phone">1800 577 11</a>
        <a href="#quoteModal" class="btn btn-book">BOOK NOW</a>
      </div>
    </div>
  </header>

  <!-- Hero Slider -->
  <section class="hero-slider">
    <div class="slides">
      <div class="slide" style="background-image:url('https://bluefacilityservices.com.au/wp-content/uploads/2025/01/home_cleaning_banner.webp');">
        <div class="overlay"></div>
        <div class="slide-content">
          <h1>Home Cleaning</h1>
          <p>Transform Your Home with Our Cleaning Services</p>
          <a href="#services" class="btn btn-outline">Learn More</a>
        </div>
      </div>
      <div class="slide" style="background-image:url('https://bluefacilityservices.com.au/wp-content/uploads/2025/01/office2.webp');">
        <div class="overlay"></div>
        <div class="slide-content">
          <h1>Professional Cleaning</h1>
          <p>Elevate Your Workspace with Tailored Cleaning</p>
          <a href="#services" class="btn btn-outline">Learn More</a>
        </div>
      </div>
      <div class="slide" style="background-image:url('https://bluefacilityservices.com.au/wp-content/uploads/2024/12/short_rental-bg.webp');">
        <div class="overlay"></div>
        <div class="slide-content">
          <h1>Short Rental Services</h1>
          <p>Comprehensive Short-Rental Management Solutions</p>
          <a href="#services" class="btn btn-outline">Learn More</a>
        </div>
      </div>
      <div class="slide" style="background-image:url('https://bluefacilityservices.com.au/wp-content/uploads/2024/11/pressure_wash.webp');">
        <div class="overlay"></div>
        <div class="slide-content">
          <h1>Support Services</h1>
          <p>Dedicated Care for Every Corner of Your Property</p>
          <a href="#services" class="btn btn-outline">Learn More</a>
        </div>
      </div>
      <div class="slide" style="https://bluefacilityservices.com.au/wp-content/uploads/2024/10/strata.webp');">
        <div class="overlay"></div>
        <div class="slide-content">
          <h1>Strata Services</h1>
          <p>Comprehensive Strata Management Services</p>
          <a href="#services" class="btn btn-outline">Learn More</a>
        </div>
      </div>      
    </div>
    <button class="prev" aria-label="Previous slide">&#10094;</button>
    <button class="next" aria-label="Next slide">&#10095;</button>
    <div class="indicators"></div>
  </section>

  <!-- Services Section -->
  <section id="services" class="services">
    <div class="container">
      <div id="cards-container">

        <!-- Home Cleaning -->
        <div class="card" data-service="Home Cleaning">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2025/01/home_cleaning_banner.webp" alt="Home Cleaning">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Home Cleaning</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Transform Your Home with Our Cleaning Services.</p>
            <p class="mini-desc">Experience unmatched cleanliness with Basic, Deep, and End of Lease Cleaning tailored to your needs.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>Jan 10, 2025</span>
              <span><i class="icon-location"></i>Sydney</span>
              <span><i class="icon-clock"></i>2h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>7 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Commercial Cleaning -->
        <div class="card" data-service="Commercial Cleaning">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2025/01/office2.webp" alt="Commercial Cleaning">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Commercial Cleaning</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Elevate Your Workspace with Tailored Cleaning.</p>
            <p class="mini-desc">We serve gyms, churches, medical centers, and offices with expert commercial cleaning solutions.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>Feb 05, 2025</span>
              <span><i class="icon-location"></i>Melbourne</span>
              <span><i class="icon-clock"></i>3h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>9 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Short Rental Services -->
        <div class="card" data-service="Short Rental Cleaning">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/12/short_rental-bg.webp" alt="Short Rental Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Short Rental Cleaning</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Comprehensive Short‑Rental Management Solutions.</p>
            <p class="mini-desc">Keep your rental property immaculate with our professional services.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>Mar 12, 2025</span>
              <span><i class="icon-location"></i>Brisbane</span>
              <span><i class="icon-clock"></i>1.5h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>8 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>
        
                <!-- Short Rental Services -->
        <div class="card" data-service="Short Rental Management">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/12/short_rental-bg.webp" alt="Short Rental Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Short Rental Management</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Comprehensive Short‑Rental Management Solutions.</p>
            <p class="mini-desc">Keep your rental property immaculate with our professional services.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>Mar 12, 2025</span>
              <span><i class="icon-location"></i>Brisbane</span>
              <span><i class="icon-clock"></i>1.5h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>8 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Strata Services -->
        <div class="card" data-service="Strata Services">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/10/strata.webp" alt="Strata Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Strata Services</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Comprehensive Strata Management Services.</p>
            <p class="mini-desc">Maintain harmony and cleanliness in shared spaces with expert care.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>Apr 01, 2025</span>
              <span><i class="icon-location"></i>Adelaide</span>
              <span><i class="icon-clock"></i>2.5h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>7.5 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Support Services -->
        <div class="card" data-service="Handyman">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/11/steam.webp" alt="Support Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Handyman</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Dedicated Care for Every Corner of Your Property.</p>
            <p class="mini-desc">Specialised services designed to keep every part of your property in pristine condition.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>May 08, 2025</span>
              <span><i class="icon-location"></i>Perth</span>
              <span><i class="icon-clock"></i>2h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>10 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>
        
        <!-- Support Services -->
        <div class="card" data-service="Gardening">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/11/steam.webp" alt="Support Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Gardening</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Dedicated Care for Every Corner of Your Property.</p>
            <p class="mini-desc">Specialised services designed to keep every part of your property in pristine condition.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>May 08, 2025</span>
              <span><i class="icon-location"></i>Perth</span>
              <span><i class="icon-clock"></i>2h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>10 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Support Services -->
        <div class="card" data-service="Pressure Washing">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/11/steam.webp" alt="Support Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Pressure Washing</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Dedicated Care for Every Corner of Your Property.</p>
            <p class="mini-desc">Specialised services designed to keep every part of your property in pristine condition.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>May 08, 2025</span>
              <span><i class="icon-location"></i>Perth</span>
              <span><i class="icon-clock"></i>2h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>10 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>      
        
        <!-- Support Services -->
        <div class="card" data-service="Steam Cleaning">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/11/steam.webp" alt="Support Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Steam Cleaning</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Dedicated Care for Every Corner of Your Property.</p>
            <p class="mini-desc">Specialised services designed to keep every part of your property in pristine condition.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>May 08, 2025</span>
              <span><i class="icon-location"></i>Perth</span>
              <span><i class="icon-clock"></i>2h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>10 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>

        <!-- Support Services -->
        <div class="card" data-service="Window Cleaning">
          <div class="card-image">
            <img src="https://bluefacilityservices.com.au/wp-content/uploads/2024/11/steam.webp" alt="Support Services">
            <button class="bookmark" aria-label="Bookmark service">&#9733;</button>
          </div>
          <div class="card-body">
            <div class="title-rating">
              <h3>Window Cleaning</h3>
              <span class="rating"><span class="star">&#9733;</span>4.6/5</span>
            </div>
            <p class="category">Dedicated Care for Every Corner of Your Property.</p>
            <p class="mini-desc">Specialised services designed to keep every part of your property in pristine condition.</p>
            <div class="meta">
              <span><i class="icon-calendar"></i>May 08, 2025</span>
              <span><i class="icon-location"></i>Perth</span>
              <span><i class="icon-clock"></i>2h</span>
            </div>
            <div class="price-book">
              <div class="price">
                <small>Start from</small>
                <strong>10 $/H</strong>
              </div>
              <button class="btn btn-orange booking-btn">Book Now</button>
            </div>
          </div>
        </div>           

      </div>
    </div>
  </section>

  <!-- Quote Modal -->
  <div id="quoteModal" class="modal">
    <div class="modal-content">
      <button class="close-modal" aria-label="Close modal">&times;</button>
      <div class="modal-grid">
        <div class="modal-left" id="modalLeft" style="background-image:url('assets/images/default-bg.jpg');">
          <div class="modal-left-overlay"></div>
          <div class="modal-left-text">
            <h2 id="modalServiceName">Service Name</h2>
            <h4 id="modalServiceSubtitle">Service Subtitle</h4>
            <p id="modalServiceDesc">Mini description goes here.</p>
          </div>
        </div>
        <div class="modal-right">
          <h3>Enter Your Details</h3>
          <form id="quoteForm">
  <input type="hidden" name="user_id" value="1"> <!-- ✅ Aqui está o user_id -->

  <input type="text" name="referral_code" id="referral_code" placeholder="Referral Code (if any)" aria-label="Referral Code">
  <div class="form-row">
    <input type="text" name="referred" placeholder="First Name" required aria-label="First Name">
    <input type="text" name="referred_last_name" placeholder="Last Name" required aria-label="Last Name">
  </div>
  <input type="email" name="email" placeholder="Email" required aria-label="Email">
  <input type="tel" name="mobile" placeholder="Mobile" required aria-label="Mobile">
  <input type="text" name="postcode" id="postcode" placeholder="Postal Code" required aria-label="Postal Code">
  <select name="client_type" required>
  <option value="">Select Client Type</option>
  <option value="Home">Home</option>
  <option value="Company">Company</option>
</select>
            <select name="service_name">
                <option>Select Service</option>
                <option>Commercial Cleaning</option>
                <option>Home Cleaning</option>
                <option>Short Rental Cleaning</option>
                <option>Short Rental Management</option>
                <option>Handyman</option>
                <option>Gardening</option>
                <option>Pressure Washing</option>
                <option>Steam Cleaning</option>
                <option>Window Cleaning</option>
                <option>Strata Services</option>
  </select>
  <textarea name="more_details" placeholder="Additional Comments" rows="3" aria-label="Additional Details"></textarea>
  <button type="submit" class="btn btn-submit">Submit</button>
  <p class="help-link">Need Help? <a href="mailto:support@bluefacilityservices.com.au">Contact Us</a></p>
</form>
        </div>
      </div>
    </div>
  </div>
   <script src="quote/landing.js"></script>
</body>
</html>