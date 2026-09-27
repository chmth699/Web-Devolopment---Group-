<?php
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'UniShare.lk — Features';
$pageCss = 'Features.css';
$active = 'features';
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Page heading -->
  <main>
    <h1 class="page-title">Features</h1>

    <section class="features-grid">

      <a class="card-link" href="<?= is_logged_in() ? base_url('dashboard.php') : base_url('auth/register.php') ?>">
        <article class="card">
          <div class="card-text">
            <h3>Upload Resources</h3>
            <p>Upload the materials you want to share</p>
          </div>
          <div class="icon-circle">⬆️</div>
        </article>
      </a>

      <a class="card-link" href="<?= base_url('resources.php') ?>">
        <article class="card">
          <div class="card-text">
            <h3>Download Resources</h3>
            <p>Download the materials you want</p>
          </div>
          <div class="icon-circle">⬇️</div>
        </article>
      </a>

      <a class="card-link" href="<?= base_url('resources.php') ?>">
        <article class="card">
          <div class="card-text">
            <h3>Comments</h3>
            <p>Discuss and ask questions in comment section</p>
          </div>
          <div class="icon-circle">💬</div>
        </article>
      </a>

      <a class="card-link" href="<?= is_logged_in() ? base_url('dashboard.php') . '#bookmarks-card' : base_url('auth/login.php') ?>">
        <article class="card">
          <div class="card-text">
            <h3>Bookmarks &amp; Save</h3>
            <p>Save your favourite materials</p>
          </div>
          <div class="icon-circle">🔖</div>
        </article>
      </a>

      <a class="card-link" href="<?= is_logged_in() ? base_url('dashboard.php') . '#todo-card' : base_url('auth/login.php') ?>">
        <article class="card">
          <div class="card-text">
            <h3>To Do List</h3>
            <p>Plan your tasks and do it</p>
          </div>
          <div class="icon-circle">📝</div>
        </article>
      </a>

      <a class="card-link" href="<?= base_url('resources.php') ?>">
        <article class="card">
          <div class="card-text">
            <h3>Advance Search</h3>
            <p>Search your subjects</p>
          </div>
          <div class="icon-circle">🔍</div>
        </article>
      </a>

      <a class="card-link card-wide-link" href="<?= base_url('contact.php') ?>">
        <article class="card card-wide">
          <div class="card-text">
            <h3>Report</h3>
            <p>Report unnecessary things</p>
          </div>
          <div class="icon-circle">🚩</div>
        </article>
      </a>

    </section>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
