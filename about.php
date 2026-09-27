<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'UniShare.lk — About';
$pageCss = 'about.css';
$active = 'about';
require_once __DIR__ . '/includes/header.php';
?>

   <main>

    <! Page heading >
    <section class="about-hero">
      <span class="hero-badge">★ Our Story</span>
      <h1 class="page-title">About Us</h1>
      <p class="about-lead">
        UniShare.lk was built by students, for students — a single place to find notes,
        past papers and lectures without the endless group-chat scavenger hunt.
      </p>
    </section>

    <!-- Mission / Vision -->
    <section class="mv-grid">
      <article class="mv-card">
        <div class="icon-circle">🎯</div>
        <h3>Our Mission</h3>
        <p>Make quality academic resources easy to find, share and organise for every
           university student in Sri Lanka.</p>
      </article>

      <article class="mv-card">
        <div class="icon-circle">🌍</div>
        <h3>Our Vision</h3>
        <p>A connected student community where knowledge flows freely between
           faculties, batches and universities.</p>
      </article>

      <article class="mv-card">
        <div class="icon-circle">🤝</div>
        <h3>Our Values</h3>
        <p>Openness, collaboration and giving back — every upload helps the next
           student learn a little faster.</p>
      </article>
    </section>

    <!-- Stats -->
    <section class="stats-strip">
      <div class="stat">
        <span class="stat-num">12K+</span>
        <span class="stat-label">Resources Shared</span>
      </div>
      <div class="stat">
        <span class="stat-num">6K+</span>
        <span class="stat-label">Active Students</span>
      </div>
      <div class="stat">
        <span class="stat-num">40+</span>
        <span class="stat-label">Faculties Covered</span>
      </div>
      <div class="stat">
        <span class="stat-num">15</span>
        <span class="stat-label">Universities</span>
      </div>
    </section>

    <!-- Story -->
    <section class="story-section">
      <div class="story-text">
        <h2>Why we started UniShare.lk</h2>
        <p>
          It began with a simple frustration — hunting through scattered WhatsApp groups
          and drives for last year's past papers. We wanted one home for everything:
          notes, papers, assignments and the conversations around them.
        </p>
        <p>
          Today UniShare.lk helps students across Sri Lanka upload, discover and discuss
          resources in one clean, organised space — built and improved with feedback
          from the very students who use it.
        </p>
      </div>
      <div class="story-art">
        <div class="art-circle">📚</div>
      </div>
    </section>

    <!-- Team -->
    <section class="team-section">
      <h2 class="section-title">Meet the Team</h2>
      <div class="team-grid">
        <article class="team-card">
          <div class="avatar">👨‍💻</div>
          <h4>Harsha Madhushanka</h4>
          <span class="role">Founder &amp; Developer</span>
        </article>
        <article class="team-card">
          <div class="avatar">👨‍💻</div>
          <h4>Harsha Madhushanka</h4>
          <span class="role">Product Design</span>
        </article>
        <article class="team-card">
          <div class="avatar">👩‍🎓</div>
          <h4>Chamath Dulanga</h4>
          <span class="role">Community Lead</span>
        </article>
        <article class="team-card">
          <div class="avatar">👨‍🎓</div>
          <h4>Chamath Dulanga</h4>
          <span class="role">Content &amp; Support</span>
        </article>
      </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
      <h2>Have questions or want to collaborate?</h2>
      <p>We'd love to hear from you.</p>
      <a href="<?= base_url('contact.php') ?>" class="btn btn-filled btn-lg">Contact Us →</a>
    </section>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
