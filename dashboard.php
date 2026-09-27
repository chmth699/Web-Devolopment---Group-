<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();

$userId = $_SESSION['user_id'];
$validCategories = ['notes' => 'Notes', 'slides' => 'Lecture Slides', 'lab' => 'Lab Reports', 'videos' => 'Videos', 'papers' => 'Past Papers'];
$errors = [];

//  Handle new resource upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? 'upload') === 'upload') {
    $title       = clean($_POST['title'] ?? '');
    $category    = clean($_POST['category'] ?? '');
    $description = clean($_POST['description'] ?? '');

    if (strlen($title) < 3) {
        $errors['title'] = 'Please enter a title (min. 3 characters).';
    }
    if (!isset($validCategories[$category])) {
        $errors['category'] = 'Please choose a valid category.';
    }
    if (empty($_FILES['file']['name'])) {
        $errors['file'] = 'Please choose a file to upload.';
    } elseif ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $errors['file'] = 'File upload failed. Please try again.';
    } elseif ($_FILES['file']['size'] > 10 * 1024 * 1024) {
        $errors['file'] = 'File is too large (max 10MB).';
    }

    if (empty($errors)) {
        $originalName = basename($_FILES['file']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
        $storedName = uniqid('res_', true) . '_' . $safeName;
        $destination = __DIR__ . '/uploads/' . $storedName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            $stmt = $pdo->prepare(
                'INSERT INTO resources (user_id, title, category, description, file_path) VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$userId, $title, $category, $description, $storedName]);

            set_flash('success', 'Resource uploaded successfully!');
            header('Location: ' . base_url('dashboard.php'));
            exit;
        } else {
            $errors['file'] = 'Could not save the uploaded file on the server.';
        }
    }
}

//  Handle to-do list actions (add / toggle done / delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'todo') {
    $todoAction = $_POST['todo_action'] ?? '';

    if ($todoAction === 'add') {
        $task = clean($_POST['task'] ?? '');
        if ($task !== '') {
            $stmt = $pdo->prepare('INSERT INTO todos (user_id, task) VALUES (?, ?)');
            $stmt->execute([$userId, $task]);
        }
    } elseif ($todoAction === 'toggle') {
        $todoId = (int) ($_POST['todo_id'] ?? 0);
        $stmt = $pdo->prepare('UPDATE todos SET is_done = NOT is_done WHERE id = ? AND user_id = ?');
        $stmt->execute([$todoId, $userId]);
    } elseif ($todoAction === 'delete') {
        $todoId = (int) ($_POST['todo_id'] ?? 0);
        $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ? AND user_id = ?');
        $stmt->execute([$todoId, $userId]);
    }

    header('Location: ' . base_url('dashboard.php') . '#todo-card');
    exit;
}

//  Handle un-bookmarking straight from the dashboard
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'unbookmark') {
    $resourceId = (int) ($_POST['resource_id'] ?? 0);
    $stmt = $pdo->prepare('DELETE FROM bookmarks WHERE id = ? AND user_id = ?');
    $stmt->execute([$resourceId, $userId]);
    header('Location: ' . base_url('dashboard.php') . '#bookmarks-card');
    exit;
}

// Fetch this user's uploads
$stmt = $pdo->prepare('SELECT * FROM resources WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$myResources = $stmt->fetchAll();

// Fetch this user's bookmarked resources
$stmt = $pdo->prepare(
    'SELECT b.id AS bookmark_id, r.id AS resource_id, r.title, r.category, r.file_path
     FROM bookmarks b JOIN resources r ON r.id = b.resource_id
     WHERE b.user_id = ? ORDER BY b.created_at DESC'
);
$stmt->execute([$userId]);
$myBookmarks = $stmt->fetchAll();

// Fetch this user's to-do items
$stmt = $pdo->prepare('SELECT * FROM todos WHERE user_id = ? ORDER BY is_done ASC, created_at DESC');
$stmt->execute([$userId]);
$myTodos = $stmt->fetchAll();

$pageTitle = 'UniShare.lk — Dashboard';
$pageCss = 'Home.css';
$active = '';
require_once __DIR__ . '/includes/header.php';
$flashSuccess = get_flash('success');
$flashError = get_flash('error');
?>

<main>
  <div class="dashboard-wrap">
    <?php if ($flashSuccess): ?><p class="flash flash-success">✅ <?= h($flashSuccess) ?></p><?php endif; ?>
    <?php if ($flashError): ?><p class="flash flash-error">⚠️ <?= h($flashError) ?></p><?php endif; ?>

    <h1>Welcome, <?= h($_SESSION['username']) ?> 👋</h1>
    <p class="sub">Manage the study resources you've shared with UniShare.lk.</p>

    <div class="dash-grid">
      <div class="dash-card" id="uploads-card">
        <h3>My Uploads (<?= count($myResources) ?>)</h3>
        <?php if (empty($myResources)): ?>
          <p class="empty-state">You haven't uploaded anything yet — use the form to add your first resource.</p>
        <?php else: ?>
          <?php foreach ($myResources as $r): ?>
            <div class="resource-row">
              <div>
                <strong><?= h($r['title']) ?></strong>
                <div class="meta"><span class="tag"><?= h($validCategories[$r['category']] ?? $r['category']) ?></span></div>
              </div>
              <a class="btn btn-outline" href="<?= base_url('uploads/' . rawurlencode($r['file_path'])) ?>" download>Download</a>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="dash-card">
        <h3>Upload a New Resource</h3>
        <form method="POST" action="dashboard.php" enctype="multipart/form-data" novalidate>
          <input type="hidden" name="form" value="upload">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="e.g. ICT1209 Week 5 Notes" value="<?= h($_POST['title'] ?? '') ?>">
            <?php if (!empty($errors['title'])): ?><span class="field-error"><?= h($errors['title']) ?></span><?php endif; ?>
          </div>

          <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category">
              <option value="">Choose a category</option>
              <?php foreach ($validCategories as $key => $label): ?>
                <option value="<?= h($key) ?>" <?= (($_POST['category'] ?? '') === $key) ? 'selected' : '' ?>><?= h($label) ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['category'])): ?><span class="field-error"><?= h($errors['category']) ?></span><?php endif; ?>
          </div>

          <div class="form-group">
            <label for="description">Description (optional)</label>
            <textarea id="description" name="description" rows="3" placeholder="Short description..."><?= h($_POST['description'] ?? '') ?></textarea>
          </div>

          <div class="form-group">
            <label for="file">File</label>
            <input type="file" id="file" name="file">
            <?php if (!empty($errors['file'])): ?><span class="field-error"><?= h($errors['file']) ?></span><?php endif; ?>
          </div>

          <button type="submit" class="btn btn-filled btn-lg">Upload Resource</button>
        </form>
      </div>

      <div class="dash-card" id="bookmarks-card">
        <h3>My Bookmarks (<?= count($myBookmarks) ?>)</h3>
        <?php if (empty($myBookmarks)): ?>
          <p class="empty-state">Nothing saved yet — hit 🔖 Save on any resource to keep it here.</p>
        <?php else: ?>
          <?php foreach ($myBookmarks as $b): ?>
            <div class="resource-row">
              <div>
                <strong><?= h($b['title']) ?></strong>
                <div class="meta"><span class="tag"><?= h($validCategories[$b['category']] ?? $b['category']) ?></span></div>
              </div>
              <div class="resource-actions">
                <a class="btn btn-outline" href="<?= base_url('uploads/' . rawurlencode($b['file_path'])) ?>" download>Download</a>
                <form method="POST" action="dashboard.php" class="inline-form">
                  <input type="hidden" name="form" value="unbookmark">
                  <input type="hidden" name="resource_id" value="<?= (int) $b['bookmark_id'] ?>">
                  <button type="submit" class="btn btn-outline">Remove</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="dash-card" id="todo-card">
        <h3>My To-Do List (<?= count($myTodos) ?>)</h3>

        <form method="POST" action="dashboard.php#todo-card" class="todo-add-form">
          <input type="hidden" name="form" value="todo">
          <input type="hidden" name="todo_action" value="add">
          <input type="text" name="task" placeholder="e.g. Finish reading ICT1209 notes" required>
          <button type="submit" class="btn btn-filled">Add</button>
        </form>

        <?php if (empty($myTodos)): ?>
          <p class="empty-state">No tasks yet — add your first one above.</p>
        <?php else: ?>
          <div class="todo-list">
            <?php foreach ($myTodos as $t): ?>
              <div class="todo-row <?= $t['is_done'] ? 'is-done' : '' ?>">
                <form method="POST" action="dashboard.php#todo-card" class="inline-form">
                  <input type="hidden" name="form" value="todo">
                  <input type="hidden" name="todo_action" value="toggle">
                  <input type="hidden" name="todo_id" value="<?= (int) $t['id'] ?>">
                  <button type="submit" class="todo-check" aria-label="Toggle done">
                    <?= $t['is_done'] ? '☑️' : '⬜' ?>
                  </button>
                </form>
                <span class="todo-text"><?= h($t['task']) ?></span>
                <form method="POST" action="dashboard.php#todo-card" class="inline-form">
                  <input type="hidden" name="form" value="todo">
                  <input type="hidden" name="todo_action" value="delete">
                  <input type="hidden" name="todo_id" value="<?= (int) $t['id'] ?>">
                  <button type="submit" class="todo-delete" aria-label="Delete task">🗑️</button>
                </form>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
