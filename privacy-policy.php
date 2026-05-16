<?php session_start(); $pageTitle = 'Privacy Policy'; include __DIR__ . '/includes/header.php'; ?>

  <main>

    <section class="page-hero">
      <div class="container">
        <span class="section-tag" style="background:rgba(255,255,255,0.2); color:#fff;">Legal</span>
        <h1>Privacy Policy</h1>
        <p>How MediBook collects, uses, and protects your personal information.</p>
      </div>
    </section>

    <section class="section">
      <div class="container" style="max-width:800px;">

        <p style="color:var(--text-muted); margin-bottom:2rem;">Last updated: <?= date('F Y') ?></p>

        <h2>1. What Data We Collect</h2>
        <p>When you submit a booking request or create an account, we collect the following personal information:</p>
        <ul style="margin:0.75rem 0 1.5rem 1.5rem; line-height:1.8;">
          <li>Full name</li>
          <li>Email address</li>
          <li>Phone number</li>
          <li>Date of birth</li>
          <li>Appointment preferences (service type, preferred date and time)</li>
          <li>Any additional notes you provide regarding your appointment</li>
        </ul>

        <h2>2. Why We Collect It</h2>
        <p>We collect this information solely to:</p>
        <ul style="margin:0.75rem 0 1.5rem 1.5rem; line-height:1.8;">
          <li>Process and confirm your appointment booking</li>
          <li>Contact you with appointment confirmations or changes</li>
          <li>Match you with the appropriate doctor or specialist</li>
          <li>Maintain records required for healthcare administration</li>
        </ul>

        <h2>3. How Long We Keep It</h2>
        <p>Your personal data is retained for a maximum of <strong>2 years</strong> from the date of your last appointment or account activity. After this period, your data is securely deleted from our systems.</p>

        <h2>4. How We Protect Your Data</h2>
        <p>MediBook takes reasonable technical and organisational measures to protect your personal information, including:</p>
        <ul style="margin:0.75rem 0 1.5rem 1.5rem; line-height:1.8;">
          <li>Encrypted password storage using industry-standard hashing</li>
          <li>Restricted access to personal data — only authorised clinic staff can view booking records</li>
          <li>Secure HTTPS connections for all data transmission</li>
        </ul>

        <h2>5. We Do Not Sell or Share Your Data</h2>
        <p>MediBook does not sell, rent, or share your personal information with third parties for marketing purposes. Your data is used exclusively to operate this booking service. We will never disclose your information to external parties without your explicit consent, except where required by law.</p>

        <h2>6. Your Rights Under GDPR / Australian Privacy Law</h2>
        <p>You have the following rights regarding your personal data:</p>
        <ul style="margin:0.75rem 0 1.5rem 1.5rem; line-height:1.8;">
          <li><strong>Access:</strong> You may request a copy of the personal data we hold about you.</li>
          <li><strong>Correction:</strong> You may request that inaccurate or incomplete data be corrected.</li>
          <li><strong>Deletion:</strong> You may request that your personal data be deleted from our systems.</li>
          <li><strong>Portability:</strong> You may request your data in a structured, machine-readable format.</li>
          <li><strong>Objection:</strong> You may object to how your data is processed in certain circumstances.</li>
        </ul>
        <p>To exercise any of these rights, please contact us at the email address below. We will respond within 30 days.</p>

        <h2>7. Cookies</h2>
        <p>MediBook uses session cookies only to maintain your login state while you are using the site. These cookies are not used for tracking or advertising. They expire when you close your browser or log out.</p>

        <h2>8. Contact Us</h2>
        <p>For any privacy-related requests or questions, please contact our Privacy Officer:</p>
        <p style="margin-top:0.75rem;">
          <strong>Email:</strong> <a href="mailto:privacy@medibook.com.au">privacy@medibook.com.au</a><br />
          <strong>Address:</strong> 123 Health Street, Sydney NSW 2000, Australia
        </p>

      </div>
    </section>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
