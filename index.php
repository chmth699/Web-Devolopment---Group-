<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'UniShare.lk — Resources';
$pageCss = 'Home.css';
$active = 'home';
require_once __DIR__ . '/includes/header.php';
$flashSuccess = get_flash('success');
?>

  <?php if ($flashSuccess): ?>
    <p class="flash flash-success">✅ <?= h($flashSuccess) ?></p>
  <?php endif; ?>

  <!-- Page heading -->
   <br>
  <main class="home-page">
    <div class="hero-row">
      <section class="hero">
        <div class="hero-text">
          <span class="hero-badge">★ Smart University Platform</span>
          <br>
          <h1>
            All Your University<br>
            Resources,<br>
            <span class="highlight">In One Place.</span>
          </h1>
          <br>
          <p class="hero-desc">
            Find notes, past papers, lectures and more easily.<br>
            Save time, learn better, grow together.
          </p>
          <br>
          

          <form class="search-box" action="<?= base_url('resources.php') ?>" method="GET">
            <span class="search-icon">🔍</span>
            <input type="text" name="q" placeholder="[Your learning area]">
            <button type="submit" class="btn btn-filled search-btn">Search</button>
          </form>

          <br>
          <br>

          <div class="hero-actions">
            <a href="<?= base_url('resources.php') ?>" class="btn btn-filled btn-lg">Explore Resources →</a>
            <?php if (is_logged_in()): ?>
              <a href="<?= base_url('dashboard.php') ?>" class="btn btn-outline btn-lg">Upload Now ⤴</a>
            <?php else: ?>
              <a href="<?= base_url('auth/register.php') ?>" class="btn btn-outline btn-lg">Upload Now ⤴</a>
            <?php endif; ?>

          </div>

        </div>
      </section>

      <a href="<?= base_url('index.php') ?>" class="hero-image-link">
        <img src="<?= base_url('images/hm.png') ?>" alt="UniShare illustration" class="hero-image">
      </a>
    </div>
  </main>

<?php
$pageJs = ['hom.js'];
require_once __DIR__ . '/includes/footer.php';
?>
