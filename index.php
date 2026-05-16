<?php session_start(); $pageTitle = 'Home'; include __DIR__ . '/includes/header.php'; ?>

  <main>

    <!-- ===== HERO ===== -->
    <section class="hero" aria-labelledby="hero-heading">
      <div class="container hero-inner">
        <div class="hero-text" style="padding: 10px;">
          <span class="section-tag">Trusted Healthcare Booking</span>
          <h1 id="hero-heading">Book Your Clinic Appointment <em>Online</em></h1>
          <p>Skip the phone queues. Book, reschedule, or cancel appointments with qualified doctors and specialists — anytime, from any device.</p>
          <div class="hero-actions">
            <a href="contact.php" class="btn btn-primary">Book an Appointment</a>
            <a href="services.php" class="btn btn-outline">Our Services</a>
          </div>
        </div>
        <div class="hero-image" aria-hidden="true">
          <div class="hero-img-placeholder">
            <img src="images/hero-clinic.jpg" alt="Doctor consulting with a patient in a modern clinic" />
          </div>
        </div>
      </div>
    </section>

    <!-- ===== HOW IT WORKS ===== -->
    <section class="section section--alt" aria-labelledby="how-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Simple Process</span>
          <h2 id="how-heading">How It Works</h2>
          <p>Book your appointment in three easy steps — no account required.</p>
        </div>
        <ol class="steps-grid" role="list">
          <li class="step-card">
            <h3>Choose a Service</h3>
            <p>Browse our range of general and specialist medical services and select the one you need.</p>
          </li>
          <li class="step-card">
            <h3>Pick a Time</h3>
            <p>Select an available date and time that suits your schedule — mornings, afternoons, or evenings.</p>
          </li>
          <li class="step-card">
            <h3>Confirm & Attend</h3>
            <p>Fill in your details, submit the form, and receive a confirmation. Just show up at your appointment.</p>
          </li>
        </ol>
      </div>
    </section>

    <!-- ===== SERVICES PREVIEW ===== -->
    <section class="section" aria-labelledby="services-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">What We Offer</span>
          <h2 id="services-heading">Our Services</h2>
          <p>From general checkups to specialist consultations, we cover your health needs.</p>
        </div>
        <div class="grid-3">
          <article class="card" aria-label="General Practice service">
            <div class="card-icon" aria-hidden="true">🩺</div>
            <h3>General Practice</h3>
            <p>Routine checkups, referrals, health assessments, and chronic disease management.</p>
            <a href="services.php" class="card-link">Learn more →</a>
          </article>
          <article class="card" aria-label="Specialist Consultations service">
            <div class="card-icon" aria-hidden="true">🔬</div>
            <h3>Specialist Consultations</h3>
            <p>Access cardiologists, dermatologists, orthopaedic surgeons, and more.</p>
            <a href="services.php" class="card-link">Learn more →</a>
          </article>
          <article class="card" aria-label="Mental Health service">
            <div class="card-icon" aria-hidden="true">🧠</div>
            <h3>Mental Health</h3>
            <p>Speak with psychologists and counsellors in a safe, confidential environment.</p>
            <a href="services.php" class="card-link">Learn more →</a>
          </article>
        </div>
        <div style="text-align:center; margin-top: 2.5rem;">
          <a href="services.php" class="btn btn-outline">View All Services</a>
        </div>
      </div>
    </section>

    <!-- ===== WHY US ===== -->
    <section class="section section--alt" aria-labelledby="why-heading">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Why MediBook</span>
          <h2 id="why-heading">Healthcare Made Simple</h2>
          <p>We put patients first — online booking that actually works.</p>
        </div>
        <div class="grid-2">
          <div class="why-item">
            <div class="why-icon" aria-hidden="true">⚡</div>
            <div>
              <h3>Instant Confirmation</h3>
              <p>No waiting on hold. Submit your booking form and get immediate feedback on your appointment.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-icon" aria-hidden="true">🔒</div>
            <div>
              <h3>Private & Secure</h3>
              <p>Your health information stays private. We collect only what's needed to book your appointment.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-icon" aria-hidden="true">📱</div>
            <div>
              <h3>Works on Any Device</h3>
              <p>Book from your phone, tablet, or desktop. Our site is fully responsive across all screen sizes.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-icon" aria-hidden="true">🗓️</div>
            <div>
              <h3>Flexible Scheduling</h3>
              <p>Choose early morning, afternoon, or evening slots across weekdays and Saturdays.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== CTA BANNER ===== -->
    <section class="cta-banner gradient-bg" aria-labelledby="cta-heading">
      <div class="container cta-inner">
        <h2 id="cta-heading">Ready to Book Your Appointment?</h2>
        <p>Take control of your health today. It only takes two minutes.</p>
        <a href="contact.php" class="btn btn-white">Book Now</a>
      </div>
    </section>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
