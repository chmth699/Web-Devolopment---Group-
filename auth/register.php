<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Already logged in? No need to register again.
if (is_logged_in()) {
    header('Location: ' . base_url('dashboard.php'));
    exit;
}

$errors = [];
$old = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $email    = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $old['username'] = $username;
    $old['email'] = $email;

    //  Server-side validation (must not rely on JS alone) 
    if (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    //  Uniqueness check 
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors['general'] = 'That username or email is already registered.';
        }
    }

    //  Create the account 
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare(
            'INSERT INTO users (username, email, password) VALUES (?, ?, ?)'
        );
        $stmt->execute([$username, $email, $hashed]);

        set_flash('success', 'Account created! You can log in now.');
        header('Location: ' . base_url('auth/login.php'));
        exit;
    }
}

$pageTitle = 'UniShare.lk — Sign Up';
$pageCss = 'contact.css';
$active = '';
require_once __DIR__ . '/../includes/header.php';
?>

<main>
  <div class="auth-wrap">
    <div class="auth-card">
      <h2>Create your account</h2>
      <p class="auth-sub">Join UniShare.lk to upload and download resources.</p>

      <?php if (!empty($errors['general'])): ?>
        <p class="field-error" style="margin-bottom:14px;"><?= h($errors['general']) ?></p>
      <?php endif; ?>

      <form method="POST" action="register.php" novalidate>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?= h($old['username']) ?>" required minlength="3">
          <?php if (!empty($errors['username'])): ?><span class="field-error"><?= h($errors['username']) ?></span><?php endif; ?>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?= h($old['email']) ?>" required>
          <?php if (!empty($errors['email'])): ?><span class="field-error"><?= h($errors['email']) ?></span><?php endif; ?>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required minlength="8">
          <?php if (!empty($errors['password'])): ?><span class="field-error"><?= h($errors['password']) ?></span><?php endif; ?>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
          <?php if (!empty($errors['confirm_password'])): ?><span class="field-error"><?= h($errors['confirm_password']) ?></span><?php endif; ?>
        </div>

        <button type="submit" class="btn btn-filled btn-lg">Sign Up</button>
      </form>

      <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
