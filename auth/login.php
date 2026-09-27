<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    header('Location: ' . base_url('dashboard.php'));
    exit;
}

$errors = [];
$old = ['username' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $old['username'] = $username;

    if ($username === '' || $password === '') {
        $errors['general'] = 'Please enter both username/email and password.';
    } else {
        // Allow logging in with either username or email
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id(true);

            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];

            set_flash('success', 'Welcome back, ' . $user['username'] . '!');
            header('Location: ' . base_url('dashboard.php'));
            exit;
        } else {
            $errors['general'] = 'Incorrect username/email or password.';
        }
    }
}

$pageTitle = 'UniShare.lk — Log In';
$pageCss = 'contact.css';
$active = '';
require_once __DIR__ . '/../includes/header.php';
?>

<main>
  <div class="auth-wrap">
    <div class="auth-card">
      <h2>Welcome back</h2>
      <p class="auth-sub">Log in to access your dashboard.</p>

      <?php if (!empty($errors['general'])): ?>
        <p class="field-error" style="margin-bottom:14px;"><?= h($errors['general']) ?></p>
      <?php endif; ?>

      <form method="POST" action="login.php" novalidate>
        <div class="form-group">
          <label for="username">Username or Email</label>
          <input type="text" id="username" name="username" value="<?= h($old['username']) ?>" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-filled btn-lg">Log In</button>
      </form>

      <p class="auth-switch">Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
