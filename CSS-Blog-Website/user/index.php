<?php
require_once '../config.php';

// Get current page for active state
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Fetch data based on current page
switch($current_page) {
    case 'home':
        $posts = $conn->query('SELECT * FROM posts ORDER BY created_at DESC');
        break;
    case 'about':
        $about_entries = $conn->query('SELECT * FROM about ORDER BY created_at DESC');
        break;
    case 'community':
        $community_entries = $conn->query('SELECT * FROM community ORDER BY created_at DESC');
        break;
    case 'news':
        $news_entries = $conn->query('SELECT * FROM news ORDER BY event_date DESC');
        break;
    case 'developers':
        $dev_entries = $conn->query('SELECT * FROM developers ORDER BY created_at DESC');
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSS Blog - <?= ucfirst($current_page) ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="../uploads/logo.png" />
            </a>
            <div class="nav-links">
                <a href="?page=home" class="<?= $current_page === 'home' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">
                    <i class="fas fa-info-circle"></i> About
                </a>
                <a href="?page=community" class="<?= $current_page === 'community' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Community
                </a>
                <a href="?page=news" class="<?= $current_page === 'news' ? 'active' : '' ?>">
                    <i class="fas fa-newspaper"></i> News and Events
                </a>
                <a href="?page=developers" class="<?= $current_page === 'developers' ? 'active' : '' ?>">
                    <i class="fas fa-code"></i> Developers
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <?php if ($current_page === 'home'): ?>
            <h1>Latest Blog Posts</h1>
            <div class="posts">
                <?php if ($posts && $posts->num_rows > 0): ?>
                    <?php while ($row = $posts->fetch_assoc()): ?>
                        <div class="post">
                            <h2><?= htmlspecialchars($row['title']) ?></h2>
                            <?php if ($row['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
                            <?php endif; ?>
                            <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                            <div class="post-date">Posted on: <?= date('F j, Y', strtotime($row['created_at'])) ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-content">No posts available yet.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($current_page === 'about'): ?>
            <h1>About Us</h1>
            <div class="about-section">
                <?php if ($about_entries && $about_entries->num_rows > 0): ?>
                    <?php while ($row = $about_entries->fetch_assoc()): ?>
                        <div class="about-entry">
                            <h2><?= htmlspecialchars($row['title']) ?></h2>
                            <?php if ($row['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
                            <?php endif; ?>
                            <div class="content">
                                <?= nl2br(htmlspecialchars($row['content'])) ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-content">About content coming soon.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($current_page === 'community'): ?>
            <h1>Our Community</h1>
            <div class="community-section">
                <?php if ($community_entries && $community_entries->num_rows > 0): ?>
                    <?php while ($row = $community_entries->fetch_assoc()): ?>
                        <div class="community-entry">
                            <h2><?= htmlspecialchars($row['title']) ?></h2>
                            <?php if ($row['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
                            <?php endif; ?>
                            <div class="content">
                                <?= nl2br(htmlspecialchars($row['content'])) ?>
                            </div>
                            <div class="members">
                                <i class="fas fa-users"></i> <?= number_format($row['members']) ?> members
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-content">Community content coming soon.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($current_page === 'news'): ?>
            <h1>News & Events</h1>
            <div class="news-section">
                <?php if ($news_entries && $news_entries->num_rows > 0): ?>
                    <?php while ($row = $news_entries->fetch_assoc()): ?>
                        <div class="news-entry">
                            <h2><?= htmlspecialchars($row['title']) ?></h2>
                            <?php if ($row['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
                            <?php endif; ?>
                            <div class="event-date">
                                <i class="far fa-calendar-alt"></i> <?= date('F j, Y', strtotime($row['event_date'])) ?>
                            </div>
                            <div class="content">
                                <?= nl2br(htmlspecialchars($row['content'])) ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-content">No upcoming events at this time.</p>
                <?php endif; ?>
            </div>

        <?php elseif ($current_page === 'developers'): ?>
            <h1>Our Developers</h1>
            <div class="developers-section">
                <?php if ($dev_entries && $dev_entries->num_rows > 0): ?>
                    <?php while ($row = $dev_entries->fetch_assoc()): ?>
                        <div class="developer-entry">
                            <h2><?= htmlspecialchars($row['title']) ?></h2>
                            <?php if ($row['image']): ?>
                                <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
                            <?php endif; ?>
                            <div class="content">
                                <?= nl2br(htmlspecialchars($row['content'])) ?>
                            </div>
                            <?php if ($row['github_link']): ?>
                                <a href="<?= htmlspecialchars($row['github_link']) ?>" target="_blank" class="github-link">
                                    <i class="fab fa-github"></i> View GitHub Profile
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="no-content">Developer profiles coming soon.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 