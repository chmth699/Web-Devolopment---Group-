<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$validCategories = ['notes' => 'Notes', 'slides' => 'Lecture Slides', 'lab' => 'Lab Reports', 'videos' => 'Videos', 'papers' => 'Past Papers'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login(); // both actions need an account; sends guests to login.php

    $action        = $_POST['action'] ?? '';
    $resourceId    = (int) ($_POST['resource_id'] ?? 0);
    $backCategory  = clean($_POST['category'] ?? 'all');
    $backSearch    = clean($_POST['q'] ?? '');

    if ($resourceId > 0 && $action === 'toggle_bookmark') {
        $check = $pdo->prepare('SELECT id FROM bookmarks WHERE user_id = ? AND resource_id = ?');
        $check->execute([$_SESSION['user_id'], $resourceId]);

        if ($check->fetch()) {
            $stmt = $pdo->prepare('DELETE FROM bookmarks WHERE user_id = ? AND resource_id = ?');
            $stmt->execute([$_SESSION['user_id'], $resourceId]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO bookmarks (user_id, resource_id) VALUES (?, ?)');
            $stmt->execute([$_SESSION['user_id'], $resourceId]);
        }
    } elseif ($resourceId > 0 && $action === 'add_comment') {
        $body = clean($_POST['body'] ?? '');
        if ($body !== '') {
            $stmt = $pdo->prepare('INSERT INTO comments (resource_id, user_id, body) VALUES (?, ?, ?)');
            $stmt->execute([$resourceId, $_SESSION['user_id'], $body]);
        }
    }

    $query = http_build_query(['category' => $backCategory, 'q' => $backSearch]);
    header('Location: ' . base_url('resources.php') . '?' . $query . '#res-' . $resourceId);
    exit;
}

$category = clean($_GET['category'] ?? 'all');
$search   = clean($_GET['q'] ?? '');

$sql = 'SELECT r.*, u.username FROM resources r JOIN users u ON u.id = r.user_id WHERE 1=1';
$params = [];

if ($category !== 'all' && isset($validCategories[$category])) {
    $sql .= ' AND r.category = ?';
    $params[] = $category;
}
if ($search !== '') {
    $sql .= ' AND r.title LIKE ?';
    $params[] = '%' . $search . '%';
}
$sql .= ' ORDER BY r.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$resources = $stmt->fetchAll();


$bookmarkedIds = [];
if (is_logged_in()) {
    $bStmt = $pdo->prepare('SELECT resource_id FROM bookmarks WHERE user_id = ?');
    $bStmt->execute([$_SESSION['user_id']]);
    $bookmarkedIds = array_column($bStmt->fetchAll(), 'resource_id');
}


$commentsByResource = [];
if (!empty($resources)) {
    $ids = array_column($resources, 'id');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $cStmt = $pdo->prepare(
        "SELECT c.*, u.username FROM comments c JOIN users u ON u.id = c.user_id
         WHERE c.resource_id IN ($placeholders) ORDER BY c.created_at ASC"
    );
    $cStmt->execute($ids);
    foreach ($cStmt->fetchAll() as $c) {
        $commentsByResource[$c['resource_id']][] = $c;
    }
}

$pageTitle = 'UniShare.lk — Resources';
$pageCss = 'Resourse.css';
$active = 'resources';
require_once __DIR__ . '/includes/header.php';
?>

  <main>
    <br>
    <h1 align="left">&nbsp;&nbsp;Resources</h1>
    <br>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;Find the best study materials you want from here.</p>

    <form method="GET" action="resources.php" class="resource-filter-bar">
      <select name="category" class="category-select" onchange="this.form.submit()">
        <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>All Categories</option>
        <?php foreach ($validCategories as $key => $label): ?>
          <option value="<?= h($key) ?>" <?= $category === $key ? 'selected' : '' ?>><?= h($label) ?></option>
        <?php endforeach; ?>
      </select>
      <input type="text" name="q" class="resource-search-input" value="<?= h($search) ?>" placeholder="Search by title...">
      <button type="submit" class="btn btn-filled resource-submit-btn">Search</button>
      <?php if ($category !== 'all' || $search !== ''): ?>
        <a href="resources.php" class="btn btn-outline resource-clear-btn">Clear</a>
      <?php endif; ?>
    </form>

    <!-- Resource type shortcuts -->
    <section class="resource-types" id="resourceTypes" align="Center">
      <?php foreach ($validCategories as $key => $label): ?>
        <a href="resources.php?category=<?= h($key) ?>" style="text-decoration:none; color:inherit;">
          <article class="type-card" data-category="<?= h($key) ?>">
            <h3><?= h($label) ?></h3>
            <div class="type-icon icon-<?= h($key) ?>">
              <?= ['notes' => '📕', 'slides' => '📊', 'lab' => '🧪', 'videos' => '🎥', 'papers' => '📰'][$key] ?>
            </div>
          </article>
        </a>
      <?php endforeach; ?>
    </section>

    <!-- Actual uploaded resources -->
    <div class="resource-list">
      <?php if (empty($resources)): ?>
        <div class="empty-state">
          <p>No resources found yet<?= $category !== 'all' || $search !== '' ? ' for this filter' : '' ?>.</p>
          <?php if (!is_logged_in()): ?>
            <p><a class="text-link" href="<?= base_url('auth/register.php') ?>">Sign up</a> to be the first to upload one!</p>
          <?php else: ?>
            <p><a class="text-link" href="<?= base_url('dashboard.php') ?>">Upload one from your dashboard →</a></p>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <?php foreach ($resources as $r): ?>
          <div class="resource-item" id="res-<?= (int) $r['id'] ?>">
            <div class="resource-main">
              <div>
                <strong><?= h($r['title']) ?></strong>
                <div class="meta">
                  <?= h($validCategories[$r['category']] ?? $r['category']) ?>
                  · uploaded by <?= h($r['username']) ?>
                  · <?= h(date('d M Y', strtotime($r['created_at']))) ?>
                </div>
                <?php if (!empty($r['description'])): ?>
                  <div class="meta"><?= h($r['description']) ?></div>
                <?php endif; ?>
              </div>

              <div class="resource-actions">
                <?php if (is_logged_in()): ?>
                  <?php $isSaved = in_array((int) $r['id'], $bookmarkedIds, true); ?>
                  <form method="POST" action="<?= base_url('resources.php') ?>" class="inline-form">
                    <input type="hidden" name="action" value="toggle_bookmark">
                    <input type="hidden" name="resource_id" value="<?= (int) $r['id'] ?>">
                    <input type="hidden" name="category" value="<?= h($category) ?>">
                    <input type="hidden" name="q" value="<?= h($search) ?>">
                    <button type="submit" class="btn btn-outline bookmark-btn <?= $isSaved ? 'is-saved' : '' ?>">
                      <?= $isSaved ? '🔖 Saved' : '🔖 Save' ?>
                    </button>
                  </form>
                <?php else: ?>
                  <a class="btn btn-outline" href="<?= base_url('auth/login.php') ?>">🔖 Save</a>
                <?php endif; ?>
                <a class="btn btn-outline" href="<?= base_url('uploads/' . rawurlencode($r['file_path'])) ?>" download>Download</a>
              </div>
            </div>

            <details class="comments-section">
              <?php $resourceComments = $commentsByResource[$r['id']] ?? []; ?>
              <summary>💬 Comments (<?= count($resourceComments) ?>)</summary>

              <div class="comments-list">
                <?php if (empty($resourceComments)): ?>
                  <p class="meta">No comments yet — be the first to ask a question.</p>
                <?php else: ?>
                  <?php foreach ($resourceComments as $c): ?>
                    <div class="comment">
                      <div class="comment-head">
                        <strong><?= h($c['username']) ?></strong>
                        <span class="meta"><?= h(date('d M Y, g:i a', strtotime($c['created_at']))) ?></span>
                      </div>
                      <p><?= nl2br(h($c['body'])) ?></p>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>

              <?php if (is_logged_in()): ?>
                <form method="POST" action="<?= base_url('resources.php') ?>" class="comment-form">
                  <input type="hidden" name="action" value="add_comment">
                  <input type="hidden" name="resource_id" value="<?= (int) $r['id'] ?>">
                  <input type="hidden" name="category" value="<?= h($category) ?>">
                  <input type="hidden" name="q" value="<?= h($search) ?>">
                  <textarea name="body" rows="2" placeholder="Ask a question or leave a comment..." required></textarea>
                  <button type="submit" class="btn btn-filled">Post Comment</button>
                </form>
              <?php else: ?>
                <p class="meta"><a class="text-link" href="<?= base_url('auth/login.php') ?>">Log in</a> to leave a comment.</p>
              <?php endif; ?>
            </details>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
