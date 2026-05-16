<?php
session_start();
require_once __DIR__ . '/config/db.php';

$pageTitle = 'Contact & Book';
$errors    = [];
$success   = false;
$post      = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = [
        'first-name'     => trim($_POST['first-name']     ?? ''),
        'last-name'      => trim($_POST['last-name']      ?? ''),
        'email'          => trim($_POST['email']          ?? ''),
        'phone'          => trim($_POST['phone']          ?? ''),
        'dob'            => trim($_POST['dob']            ?? ''),
        'service'        => trim($_POST['service']        ?? ''),
        'preferred-date' => trim($_POST['preferred-date'] ?? ''),
        'preferred-time' => trim($_POST['preferred-time'] ?? ''),
        'message'        => trim($_POST['message']        ?? ''),
        'privacy'        => isset($_POST['privacy']),
    ];

    if ($post['first-name'] === '')                              $errors['first-name']     = 'Please enter your first name.';
    if ($post['last-name'] === '')                               $errors['last-name']      = 'Please enter your last name.';
    if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL))     $errors['email']          = 'Please enter a valid email address.';
    if ($post['phone'] === '')                                   $errors['phone']          = 'Please enter a valid phone number.';
    if ($post['dob'] === '')                                     $errors['dob']            = 'Please enter your date of birth.';
    if ($post['service'] === '')                                 $errors['service']        = 'Please select a service.';
    if ($post['preferred-date'] === '') {
        $errors['preferred-date'] = 'Please select a preferred appointment date.';
    } elseif ($post['preferred-date'] < date('Y-m-d')) {
        $errors['preferred-date'] = 'Please select a future date.';
    }
    if ($post['preferred-time'] === '')                          $errors['preferred-time'] = 'Please select a preferred time.';
    if (!$post['privacy'])                                       $errors['privacy']        = 'You must agree to the privacy policy.';

    if (empty($errors)) {
        $notes = 'Service: ' . $post['service']
               . ' | Preferred time: ' . $post['preferred-time'];
        if ($post['message'] !== '') {
            $notes .= ' | Notes: ' . $post['message'];
        }

        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare(
                "INSERT INTO appointments (patient_id, doctor_id, slot_id, date, status, notes)
                 VALUES (?, 1, 1, ?, 'pending', ?)"
            );
            $stmt->execute([$_SESSION['user_id'], $post['preferred-date'], $notes]);

            $log = $pdo->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, 'contact_booking_submitted')");
            $log->execute([$_SESSION['user_id']]);
        }

        $success = true;
    }
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>

  <main>

    <!-- PAGE HERO -->
    <section class="page-hero">
      <div class="container">
        <span class="section-tag" style="background:rgba(255,255,255,0.2); color:#fff;">Get in Touch</span>
        <h1>Book an Appointment</h1>
        <p>Fill in the form below and we'll confirm your appointment within one business day.</p>
      </div>
    </section>

    <!-- CONTACT LAYOUT -->
    <section class="section">
      <div class="container contact-layout">

        <!-- FORM -->
        <div class="contact-form-wrap">
          <h2>Appointment Request</h2>
          <p style="color:var(--text-muted); margin-bottom:1.5rem; font-size:0.95rem;">All fields marked with * are required.</p>

          <?php if ($success): ?>
            <div class="form-success" role="status" aria-live="polite">
              <span aria-hidden="true">✅</span>
              <strong>Booking request received!</strong>
              <p>Thank you. We'll confirm your appointment via email within one business day.</p>
            </div>
          <?php else: ?>

          <form id="booking-form" method="POST" action="" novalidate aria-label="Appointment booking form">

            <div class="grid-2" style="gap:1rem;">
              <div class="form-group">
                <label for="first-name">First Name *</label>
                <input type="text" id="first-name" name="first-name" autocomplete="given-name" required aria-required="true" aria-describedby="first-name-error" value="<?= htmlspecialchars($post['first-name'] ?? '') ?>" />
                <span class="field-error" id="first-name-error" role="alert" <?= isset($errors['first-name']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['first-name'] ?? 'Please enter your first name.') ?></span>
              </div>
              <div class="form-group">
                <label for="last-name">Last Name *</label>
                <input type="text" id="last-name" name="last-name" autocomplete="family-name" required aria-required="true" aria-describedby="last-name-error" value="<?= htmlspecialchars($post['last-name'] ?? '') ?>" />
                <span class="field-error" id="last-name-error" role="alert" <?= isset($errors['last-name']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['last-name'] ?? 'Please enter your last name.') ?></span>
              </div>
            </div>

            <div class="form-group">
              <label for="email">Email Address *</label>
              <input type="email" id="email" name="email" autocomplete="email" required aria-required="true" aria-describedby="email-error" value="<?= htmlspecialchars($post['email'] ?? '') ?>" />
              <span class="field-error" id="email-error" role="alert" <?= isset($errors['email']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['email'] ?? 'Please enter a valid email address.') ?></span>
            </div>

            <div class="form-group">
              <label for="phone">Phone Number *</label>
              <input type="tel" id="phone" name="phone" autocomplete="tel" placeholder="04XX XXX XXX" required aria-required="true" aria-describedby="phone-error" value="<?= htmlspecialchars($post['phone'] ?? '') ?>" />
              <span class="field-error" id="phone-error" role="alert" <?= isset($errors['phone']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['phone'] ?? 'Please enter a valid Australian phone number.') ?></span>
            </div>

            <div class="form-group">
              <label for="dob">Date of Birth *</label>
              <input type="date" id="dob" name="dob" required aria-required="true" aria-describedby="dob-error" value="<?= htmlspecialchars($post['dob'] ?? '') ?>" />
              <span class="field-error" id="dob-error" role="alert" <?= isset($errors['dob']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['dob'] ?? 'Please enter your date of birth.') ?></span>
            </div>

            <div class="form-group">
              <label for="service">Service Required *</label>
              <select id="service" name="service" required aria-required="true" aria-describedby="service-error">
                <option value="">— Select a service —</option>
                <option value="gp"           <?= ($post['service'] ?? '') === 'gp'           ? 'selected' : '' ?>>General Practice</option>
                <option value="specialist"   <?= ($post['service'] ?? '') === 'specialist'   ? 'selected' : '' ?>>Specialist Consultation</option>
                <option value="mental-health"<?= ($post['service'] ?? '') === 'mental-health'? 'selected' : '' ?>>Mental Health</option>
                <option value="preventive"   <?= ($post['service'] ?? '') === 'preventive'   ? 'selected' : '' ?>>Preventive Care</option>
                <option value="womens"       <?= ($post['service'] ?? '') === 'womens'       ? 'selected' : '' ?>>Women's Health</option>
                <option value="telehealth"   <?= ($post['service'] ?? '') === 'telehealth'   ? 'selected' : '' ?>>Telehealth</option>
              </select>
              <span class="field-error" id="service-error" role="alert" <?= isset($errors['service']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['service'] ?? 'Please select a service.') ?></span>
            </div>

            <div class="form-group">
              <label for="preferred-date">Preferred Date *</label>
              <input type="date" id="preferred-date" name="preferred-date" required aria-required="true" aria-describedby="date-error" value="<?= htmlspecialchars($post['preferred-date'] ?? '') ?>" />
              <span class="field-error" id="date-error" role="alert" <?= isset($errors['preferred-date']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['preferred-date'] ?? 'Please select a preferred appointment date.') ?></span>
            </div>

            <div class="form-group">
              <label for="preferred-time">Preferred Time *</label>
              <select id="preferred-time" name="preferred-time" required aria-required="true" aria-describedby="time-error">
                <option value="">— Select a time —</option>
                <option value="morning"   <?= ($post['preferred-time'] ?? '') === 'morning'   ? 'selected' : '' ?>>Morning (8am – 12pm)</option>
                <option value="afternoon" <?= ($post['preferred-time'] ?? '') === 'afternoon' ? 'selected' : '' ?>>Afternoon (12pm – 5pm)</option>
                <option value="evening"   <?= ($post['preferred-time'] ?? '') === 'evening'   ? 'selected' : '' ?>>Evening (5pm – 7pm)</option>
              </select>
              <span class="field-error" id="time-error" role="alert" <?= isset($errors['preferred-time']) ? 'style="display:block;"' : '' ?>><?= htmlspecialchars($errors['preferred-time'] ?? 'Please select a preferred time.') ?></span>
            </div>

            <div class="form-group">
              <label for="message">Additional Notes</label>
              <textarea id="message" name="message" rows="4" placeholder="Describe your symptoms or reason for visit (optional)" aria-describedby="message-hint"><?= htmlspecialchars($post['message'] ?? '') ?></textarea>
              <span id="message-hint" style="font-size:0.8rem; color:var(--text-muted);">Max 500 characters.</span>
            </div>

            <div class="form-group" style="display:flex; align-items:flex-start; gap:0.75rem;">
              <input type="checkbox" id="privacy" name="privacy" required aria-required="true" aria-describedby="privacy-error" style="width:auto; margin-top:4px;" <?= !empty($post['privacy']) ? 'checked' : '' ?> />
              <div>
                <label for="privacy" style="display:inline; font-weight:400;">I agree to the <a href="/medibook/privacy-policy.php">Privacy Policy</a> and consent to my details being used to process this booking. *</label>
                <span class="field-error" id="privacy-error" role="alert" style="display:<?= isset($errors['privacy']) ? 'block' : 'none' ?>;"><?= htmlspecialchars($errors['privacy'] ?? 'You must agree to the privacy policy.') ?></span>
              </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:0.5rem;">Submit Booking Request</button>

          </form>

          <?php endif; ?>
        </div>

        <!-- CONTACT INFO -->
        <aside class="contact-info" aria-label="Contact information">
          <h2>Contact Us</h2>

          <div class="info-block">
            <div class="info-icon" aria-hidden="true">📍</div>
            <div>
              <h3>Address</h3>
              <p>123 Health Street<br />Sydney NSW 2000<br />Australia</p>
            </div>
          </div>

          <div class="info-block">
            <div class="info-icon" aria-hidden="true">📞</div>
            <div>
              <h3>Phone</h3>
              <a href="tel:+61299990000">+61 2 9999 0000</a>
              <p style="font-size:0.85rem; color:var(--text-muted);">Mon–Fri 8am–6pm, Sat 8am–1pm</p>
            </div>
          </div>

          <div class="info-block">
            <div class="info-icon" aria-hidden="true">✉️</div>
            <div>
              <h3>Email</h3>
              <a href="mailto:hello@medibook.com.au">hello@medibook.com.au</a>
            </div>
          </div>

          <div class="info-block">
            <div class="info-icon" aria-hidden="true">🕐</div>
            <div>
              <h3>Opening Hours</h3>
              <table class="hours-table" aria-label="Opening hours">
                <tr><td>Monday – Friday</td><td>8am – 6pm</td></tr>
                <tr><td>Saturday</td><td>8am – 1pm</td></tr>
                <tr><td>Sunday</td><td>Closed</td></tr>
              </table>
            </div>
          </div>

          <div class="info-block">
            <div class="info-icon" aria-hidden="true">🌐</div>
            <div>
              <h3>Follow Us</h3>
              <div class="social-links">
                <a href="#" aria-label="MediBook on Facebook">Facebook</a>
                <a href="#" aria-label="MediBook on Instagram">Instagram</a>
                <a href="#" aria-label="MediBook on LinkedIn">LinkedIn</a>
              </div>
            </div>
          </div>
        </aside>

      </div>
    </section>

  </main>

<?php include __DIR__ . '/includes/footer.php'; ?>
