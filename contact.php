<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$old = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name']    = clean($_POST['name'] ?? '');
    $old['email']   = clean($_POST['email'] ?? '');
    $old['subject'] = clean($_POST['subject'] ?? '');
    $old['message'] = clean($_POST['message'] ?? '');

    // ---- Server-side validation (mirrors contact.js, but never trust the client alone) ----
    if (strlen($old['name']) < 2) {
        $errors['name'] = 'Please enter your full name.';
    }
    if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (strlen($old['subject']) < 3) {
        $errors['subject'] = 'Please enter a subject.';
    }
    if (strlen($old['message']) < 10) {
        $errors['message'] = 'Message should be at least 10 characters.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$old['name'], $old['email'], $old['subject'], $old['message']]);

        // Post/Redirect/Get pattern: prevents the form from being resubmitted on refresh
        set_flash('success', 'Your message has been sent successfully!');
        header('Location: ' . base_url('contact.php'));
        exit;
    }
}

$pageTitle = 'UniShare.lk — Contact';
$pageCss = 'contact.css';
$active = 'contact';
require_once __DIR__ . '/includes/header.php';
$flashSuccess = get_flash('success');
?>

  <main>
    <?php if ($flashSuccess): ?>
      <p class="flash flash-success">✅ <?= h($flashSuccess) ?></p>
    <?php endif; ?>

    <!-- Page heading -->
    <section class="contact-hero">
      <span class="hero-badge">★ Get in Touch</span>
      <h1 class="page-title">Contact Us</h1>
      <p class="contact-lead">
        Have a question, suggestion, or found an issue? Send us a message and
        our team will get back to you as soon as possible.
      </p>
    </section>

    <!-- Contact info cards -->
    <section class="info-grid">
      <article class="info-card">
        <div class="icon-circle">📍</div>
        <h3>Location</h3>
        <p>Colombo, Sri Lanka</p>
      </article>

      <article class="info-card">
        <div class="icon-circle">📞</div>
        <h3>Phone</h3>
        <p>+94 70 213 5698</p>
      </article>

      <article class="info-card">
        <div class="icon-circle">✉️</div>
        <h3>Email</h3>
        <p>Uni@Share.lk</p>
      </article>

      <article class="info-card">
        <div class="icon-circle">🕒</div>
        <h3>Working Hours</h3>
        <p>Mon – Fri, 9AM – 5PM</p>
      </article>
    </section>

    <!-- Contact form -->
    <section class="form-section">
      <div class="form-card">
        <h2>Send us a message</h2>
        <p class="form-sub">We usually reply within 24 hours.</p>

        <form id="contactForm" method="POST" action="contact.php" novalidate>
          <div class="form-row">
            <div class="form-group">
              <label for="name">Full Name</label>
              <input type="text" id="name" name="name" placeholder="Your name" value="<?= h($old['name']) ?>">
              <span class="error-msg" id="nameError"><?= h($errors['name'] ?? '') ?></span>
            </div>

            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" placeholder="you@example.com" value="<?= h($old['email']) ?>">
              <span class="error-msg" id="emailError"><?= h($errors['email'] ?? '') ?></span>
            </div>
          </div>

          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="What is this about?" value="<?= h($old['subject']) ?>">
            <span class="error-msg" id="subjectError"><?= h($errors['subject'] ?? '') ?></span>
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" placeholder="Write your message here..."><?= h($old['message']) ?></textarea>
            <span class="error-msg" id="messageError"><?= h($errors['message'] ?? '') ?></span>
          </div>

          <button type="submit" class="btn btn-filled btn-lg">Send Message →</button>

          <p class="form-success" id="formSuccess"></p>
        </form>
      </div>

      <div class="map-card">
        <div class="map-placeholder">🗺️</div>
        <h4>UniShare.lk HQ</h4>
        <p>123 Campus Road, Colombo, Sri Lanka</p>
      </div>
    </section>
  </main>

<?php
$pageJs = ['contact.js'];
require_once __DIR__ . '/includes/footer.php';
?>
