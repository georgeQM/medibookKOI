<?php session_start(); $pageTitle = 'About Us'; include __DIR__ . '/includes/header.php'; ?>

  <main>

    <!-- PAGE HERO -->
    <section class="page-hero">
      <div class="container">
        <span class="section-tag" style="background:rgba(255,255,255,0.2); color:#fff;">Who We Are</span>
        <h1>About MediBook</h1>
        <p>A simple, patient-first platform built to make clinic bookings easier for everyone.</p>
      </div>
    </section>

    <!-- MISSION -->
    <section class="section">
      <div class="container about-intro">
        <div>
          <span class="section-tag">Our Mission</span>
          <h2>Healthcare access shouldn't be complicated</h2>
          <p>MediBook was built out of frustration with the old way — phone queues, missed calls, and unclear availability. We wanted a better experience for both patients and clinics.</p>
          <p>Our platform connects patients with qualified doctors and specialists through a clean, straightforward booking process. No account needed. No waiting on hold.</p>
          <a href="contact.php" class="btn btn-primary" style="margin-top:0.5rem; margin: 5px;">Book an Appointment</a>
        </div>
        <div class="about-img">
          <img src="images/about-clinic.jpg" alt="Reception area of a modern clinic with friendly staff" />
        </div>
      </div>
    </section>

    <!-- VALUES -->
    <section class="section section--alt">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">What We Stand For</span>
          <h2>Our Values</h2>
        </div>
        <div class="grid-3">
          <div class="card">
            <div class="card-icon" aria-hidden="true">🤝</div>
            <h3>Patient First</h3>
            <p>Every decision we make starts with the patient experience. Booking should be the easiest part of your healthcare journey.</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">🔒</div>
            <h3>Privacy by Default</h3>
            <p>We collect only what's needed to book your appointment. No data is sold, shared, or stored beyond what's necessary.</p>
          </div>
          <div class="card">
            <div class="card-icon" aria-hidden="true">🌐</div>
            <h3>Accessible to All</h3>
            <p>Our site is designed to work for everyone — across devices, screen sizes, and accessibility needs.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- TEAM -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Our Team</span>
          <h2>Meet the Doctors</h2>
          <p>Our network includes experienced GPs and specialists committed to quality care.</p>
        </div>
        <div class="grid-3">

          <div class="team-card">
            <div class="team-avatar" aria-hidden="true">DR</div>
            <h3>Dr. Rachel Morgan</h3>
            <p class="team-role">General Practitioner</p>
            <p class="team-bio">15 years of experience in general practice. Special interest in chronic disease management and preventive care.</p>
          </div>

          <div class="team-card">
            <div class="team-avatar" aria-hidden="true">DK</div>
            <h3>Dr. James Kim</h3>
            <p class="team-role">Cardiologist</p>
            <p class="team-bio">Specialist in cardiovascular health with over a decade in clinical cardiology across Sydney and Melbourne.</p>
          </div>

          <div class="team-card">
            <div class="team-avatar" aria-hidden="true">DA</div>
            <h3>Dr. Priya Anand</h3>
            <p class="team-role">Psychologist</p>
            <p class="team-bio">Registered psychologist focused on anxiety, depression, and workplace stress. Offers both in-person and telehealth sessions.</p>
          </div>

        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-banner">
      <div class="container cta-inner">
        <h2>Want to See Our Full Team?</h2>
        <p>Contact us and we'll match you with the right doctor for your needs.</p>
        <a href="contact.php" class="btn btn-white">Get in Touch</a>
      </div>
    </section>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
