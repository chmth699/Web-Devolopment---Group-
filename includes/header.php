<?php

require_once __DIR__ . '/functions.php';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= h($pageTitle ?? 'UniShare.lk') ?></title>
<link rel="stylesheet" href="<?= base_url('css/' . ($pageCss ?? 'Home.css')) ?>?v=<?= @filemtime(__DIR__ . '/../css/' . ($pageCss ?? 'Home.css')) ?>">
<link rel="stylesheet" href="<?= base_url('css/shared.css') ?>?v=<?= @filemtime(__DIR__ . '/../css/shared.css') ?>">
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="logo">
      <a href="<?= base_url('index.php') ?>"><img src="<?= base_url('images/logo.png') ?>" alt="logo" width="100px"></a>
      <div class="logo-text">
        <span class="logo-title">UniShare.lk</span>
        <span class="logo-sub">Smart University</span>
      </div>
    </div>

    <nav class="nav-links">
      <a href="<?= base_url('index.php') ?>" class="<?= $active === 'home' ? 'active' : '' ?>">Home</a>
      <a href="<?= base_url('features.php') ?>" class="<?= $active === 'features' ? 'active' : '' ?>">Features</a>
      <a href="<?= base_url('resources.php') ?>" class="<?= $active === 'resources' ? 'active' : '' ?>">Resources</a>
      <a href="<?= base_url('contact.php') ?>" class="<?= $active === 'contact' ? 'active' : '' ?>">Contact</a>
      <a href="<?= base_url('about.php') ?>" class="<?= $active === 'about' ? 'active' : '' ?>">About us</a>
    </nav>

    <div class="nav-actions">
      <?php if (is_logged_in()): ?>
        <span class="nav-username">Hi, <?= h($_SESSION['username']) ?></span>
        <a href="<?= base_url('dashboard.php') ?>" class="btn btn-outline">Dashboard</a>
        <a href="<?= base_url('auth/logout.php') ?>" class="btn btn-filled">Log out</a>
      <?php else: ?>
        <a href="<?= base_url('auth/login.php') ?>" class="btn btn-outline">Log in</a>
        <a href="<?= base_url('auth/register.php') ?>" class="btn btn-filled">Sign up</a>
      <?php endif; ?>
    </div>
  </header>
