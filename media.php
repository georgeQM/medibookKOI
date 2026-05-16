<?php session_start(); $pageTitle = 'Gallery'; include __DIR__ . '/includes/header.php'; ?>

  <main>

    <!-- PAGE HERO -->
    <section class="page-hero">
      <div class="container">
        <span class="section-tag" style="background:rgba(255,255,255,0.2); color:#fff;">Our Clinic</span>
        <h1>Gallery</h1>
        <p>Take a look inside MediBook's clinic facilities and meet our environment.</p>
      </div>
    </section>

    <!-- GALLERY -->
    <section class="section">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Photos</span>
          <h2>Clinic Facilities</h2>
          <p>Click any image to view it full size.</p>
        </div>

        <div class="gallery-grid" role="list" aria-label="Clinic photo gallery">

          <button class="gallery-item" aria-label="View reception area photo" data-src="images/gallery-1.jpg" data-caption="Reception &amp; Waiting Area">
            <img src="images/gallery-1.jpg" alt="Clean and welcoming clinic reception area" />
            <div class="gallery-overlay" aria-hidden="true">
              <span>Reception Area</span>
            </div>
          </button>

          <button class="gallery-item" aria-label="View consultation room photo" data-src="images/gallery-2.jpg" data-caption="Consultation Room">
            <img src="images/gallery-2.jpg" alt="Private consultation room with modern medical equipment" />
            <div class="gallery-overlay" aria-hidden="true">
              <span>Consultation Room</span>
            </div>
          </button>

          <button class="gallery-item" aria-label="View waiting room photo" data-src="images/gallery-3.jpg" data-caption="Patient Waiting Room">
            <img src="images/gallery-3.jpg" alt="Comfortable patient waiting room with seating" />
            <div class="gallery-overlay" aria-hidden="true">
              <span>Waiting Room</span>
            </div>
          </button>

          <button class="gallery-item" aria-label="View specialist room photo" data-src="images/gallery-4.jpg" data-caption="Specialist Suite">
            <img src="images/gallery-4.jpg" alt="Specialist consultation suite with examination table" />
            <div class="gallery-overlay" aria-hidden="true">
              <span>Specialist Suite</span>
            </div>
          </button>

          <button class="gallery-item" aria-label="View pharmacy area photo" data-src="images/gallery-5.jpg" data-caption="On-site Pharmacy">
            <img src="images/gallery-5.jpg" alt="On-site pharmacy with dispensing counter" />
            <div class="gallery-overlay" aria-hidden="true">
              <span>On-site Pharmacy</span>
            </div>
          </button>

          <button class="gallery-item" aria-label="View exterior photo" data-src="images/gallery-6.jpg" data-caption="Clinic Exterior">
            <img src="images/gallery-6.jpg" alt="MediBook clinic building exterior with entrance" />
            <div class="gallery-overlay" aria-hidden="true">
              <span>Clinic Exterior</span>
            </div>
          </button>

        </div>
      </div>
    </section>

    <!-- VIDEO SECTION -->
    <section class="section section--alt">
      <div class="container">
        <div class="section-header">
          <span class="section-tag">Video</span>
          <h2>How Booking Works</h2>
          <p>A quick walkthrough of the MediBook appointment process.</p>
        </div>
        <div class="video-wrapper">
          <iframe
            src="https://www.youtube.com/embed/xMR3FHymtz4?si=PRjSTdhrJE1KHV0h"
            title="MediBook appointment booking walkthrough video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-banner">
      <div class="container cta-inner">
        <h2>Like What You See?</h2>
        <p>Book your appointment online and experience the clinic in person.</p>
        <a href="contact.php" class="btn btn-white">Book Now</a>
      </div>
    </section>

  </main>

  <!-- LIGHTBOX -->
  <div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer" hidden>
    <button class="lightbox-close" id="lightbox-close" aria-label="Close image viewer">&times;</button>
    <button class="lightbox-prev" id="lightbox-prev" aria-label="Previous image">&#8249;</button>
    <button class="lightbox-next" id="lightbox-next" aria-label="Next image">&#8250;</button>
    <figure class="lightbox-content">
      <img id="lightbox-img" src="" alt="" />
      <figcaption id="lightbox-caption"></figcaption>
    </figure>
  </div>

<?php include __DIR__ . '/includes/footer.php'; ?>
<script src="/medibook/js/gallery.js"></script>
