<?php session_start(); $pageTitle = 'Services'; include __DIR__ . '/includes/header.php'; ?>

  <main>

    <!-- PAGE HERO -->
    <section class="page-hero">
      <div class="container">
        <span class="section-tag" style="background:rgba(255,255,255,0.2); color:#fff;">What We Offer</span>
        <h1>Our Services</h1>
        <p>From general checkups to specialist consultations — book the care you need online.</p>
      </div>
    </section>

    <!-- MAIN SERVICES -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Medical Services</span>
          <h2>Available Appointments</h2>
          <p>All services can be booked online. Select a service below to learn more.</p>
        </div>
        <div class="grid-3">

          <article class="card" aria-label="General Practice">
            <div class="card-icon" aria-hidden="true">🩺</div>
            <h3>General Practice</h3>
            <p>Routine checkups, sick certificates, referrals, health assessments, and management of ongoing conditions.</p>
            <ul class="service-list" aria-label="General Practice includes">
              <li>Annual health checks</li>
              <li>Chronic disease management</li>
              <li>Referrals & certificates</li>
            </ul>
            <a href="contact.php" class="card-link">Book this →</a>
          </article>

          <article class="card" aria-label="Specialist Consultations">
            <div class="card-icon" aria-hidden="true">🔬</div>
            <h3>Specialist Consultations</h3>
            <p>Access qualified specialists across cardiology, dermatology, orthopaedics, and more.</p>
            <ul class="service-list" aria-label="Specialist Consultations includes">
              <li>Cardiology</li>
              <li>Dermatology</li>
              <li>Orthopaedics</li>
            </ul>
            <a href="contact.php" class="card-link">Book this →</a>
          </article>

          <article class="card" aria-label="Mental Health">
            <div class="card-icon" aria-hidden="true">🧠</div>
            <h3>Mental Health</h3>
            <p>Speak with registered psychologists and counsellors in a confidential, supportive environment.</p>
            <ul class="service-list" aria-label="Mental Health includes">
              <li>Psychology sessions</li>
              <li>Counselling</li>
              <li>Mental health plans</li>
            </ul>
            <a href="contact.php" class="card-link">Book this →</a>
          </article>

          <article class="card" aria-label="Preventive Care">
            <div class="card-icon" aria-hidden="true">💉</div>
            <h3>Preventive Care</h3>
            <p>Vaccinations, health screenings, and lifestyle assessments to keep you ahead of illness.</p>
            <ul class="service-list" aria-label="Preventive Care includes">
              <li>Vaccinations</li>
              <li>Blood pressure screening</li>
              <li>Diabetes screening</li>
            </ul>
            <a href="contact.php" class="card-link">Book this →</a>
          </article>

          <article class="card" aria-label="Women's Health">
            <div class="card-icon" aria-hidden="true">🌸</div>
            <h3>Women's Health</h3>
            <p>Dedicated care for women at every stage of life, from reproductive health to menopause support.</p>
            <ul class="service-list" aria-label="Women's Health includes">
              <li>Pap smears</li>
              <li>Pregnancy support</li>
              <li>Hormonal health</li>
            </ul>
            <a href="contact.php" class="card-link">Book this →</a>
          </article>

          <article class="card" aria-label="Telehealth">
            <div class="card-icon" aria-hidden="true">💻</div>
            <h3>Telehealth</h3>
            <p>Consult with a doctor from home via video call. Available for most non-emergency appointments.</p>
            <ul class="service-list" aria-label="Telehealth includes">
              <li>Video consultations</li>
              <li>Prescription renewals</li>
              <li>Follow-up appointments</li>
            </ul>
            <a href="contact.php" class="card-link">Book this →</a>
          </article>

        </div>
      </div>
    </section>

    <!-- PRICING NOTE -->
    <section class="section section--alt">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Pricing</span>
          <h2>Consultation Fees</h2>
          <p>We bulk bill eligible Medicare patients. Private fees apply for non-Medicare holders.</p>
        </div>
        <div class="grid-3">
          <div class="pricing-card">
            <h3>Standard Consult</h3>
            <div class="price">$85</div>
            <p>Up to 15 minutes with a GP. Ideal for single-issue appointments.</p>
          </div>
          <div class="pricing-card pricing-card--featured">
            <span class="section-tag">Most Common</span>
            <h3>Long Consult</h3>
            <div class="price">$130</div>
            <p>Up to 30 minutes. Suitable for complex issues or multiple concerns.</p>
          </div>
          <div class="pricing-card">
            <h3>Specialist</h3>
            <div class="price">From $200</div>
            <p>Fees vary by specialist type. Referral from a GP required.</p>
          </div>
        </div>
        <p style="text-align:center; font-size:0.85rem; color:var(--text-muted); margin-top:1.5rem;">
          * Bulk billing available for children under 16, concession card holders, and pensioners.
        </p>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-banner">
      <div class="container cta-inner">
        <h2>Ready to Book?</h2>
        <p>Choose your service and lock in a time that works for you.</p>
        <a href="contact.php" class="btn btn-white">Book an Appointment</a>
      </div>
    </section>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
