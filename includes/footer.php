  <!-- Footer -->
  <footer class="footer">
    <div class="footer-brand">
      <a href="<?= base_url('index.php') ?>"><img src="<?= base_url('images/logo.png') ?>" alt="logo" width="100px"></a>
      <span class="footer-brand-name">UniShare.lk</span>
    </div>

    <div class="footer-col">
      <h4>Quick Links</h4>
      <a href="<?= base_url('index.php') ?>">Home</a>
      <a href="<?= base_url('features.php') ?>">Features</a>
      <a href="<?= base_url('resources.php') ?>">Resources</a>
      <a href="<?= base_url('contact.php') ?>">Contact</a>
    </div>

    <div class="footer-col">
      <h4>Support</h4>
      <a href="#">Help Center</a>
      <a href="#">Privacy</a>
    </div>

    <div class="footer-col">
      <h4>Contact</h4>
      <span>+94 78 854 6975</span>
      <span>Uni@Share.lk</span>
    </div>

    <div class="footer-social">
      <a href="#" aria-label="LinkedIn">in</a>
      <a href="#" aria-label="Facebook">f</a>
      <a href="#" aria-label="X">𝕏</a>
      <a href="#" aria-label="GitHub">gh</a>
    </div>
  </footer>

  <?php if (!empty($pageJs)): foreach ((array) $pageJs as $js): ?>
    <script src="<?= base_url('js/' . $js) ?>"></script>
  <?php endforeach; endif; ?>
</body>
</html>
