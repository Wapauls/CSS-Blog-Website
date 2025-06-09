<?php
require_once '../config.php';

// Fetch statistics for dashboard
$stats = [
    'posts' => $conn->query('SELECT COUNT(*) as count FROM posts')->fetch_assoc()['count'],
    'about' => $conn->query('SELECT COUNT(*) as count FROM about')->fetch_assoc()['count'],
    'community' => $conn->query('SELECT COUNT(*) as count FROM community')->fetch_assoc()['count'],
    'news' => $conn->query('SELECT COUNT(*) as count FROM news')->fetch_assoc()['count'],
    'developers' => $conn->query('SELECT COUNT(*) as count FROM developers')->fetch_assoc()['count'],
];

// Fetch recent items from each section
$recent_posts = $conn->query('SELECT * FROM posts ORDER BY created_at DESC LIMIT 5');
$recent_news = $conn->query('SELECT * FROM news ORDER BY created_at DESC LIMIT 5');
$recent_community = $conn->query('SELECT * FROM community ORDER BY created_at DESC LIMIT 5');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    <div class="main-content modern-dashboard">
        <h1>Dashboard Overview</h1>
        <div class="stats-grid">
    <div class="stat-card">
        <div class="stat-body">
            <i class="fas fa-blog"></i>
            <h3>Blog Posts</h3>
            <p class="stat-number"><?= $stats['posts'] ?></p>
        </div>
        <a href="blogpost.php" class="stat-link">Posts</a>
    </div>

    <div class="stat-card">
        <div class="stat-body">
            <i class="fas fa-info-circle"></i>
            <h3>About Entries</h3>
            <p class="stat-number"><?= $stats['about'] ?></p>
        </div>
        <a href="about.php" class="stat-link">About</a>
    </div>

    <div class="stat-card">
        <div class="stat-body">
            <i class="fas fa-users"></i>
            <h3>Community Updates</h3>
            <p class="stat-number"><?= $stats['community'] ?></p>
        </div>
        <a href="community.php" class="stat-link">Community</a>
    </div>

    <div class="stat-card">
        <div class="stat-body">
            <i class="fas fa-newspaper"></i>
            <h3>News & Events</h3>
            <p class="stat-number"><?= $stats['news'] ?></p>
        </div>
        <a href="news.php" class="stat-link">News</a>
    </div>

    <div class="stat-card">
        <div class="stat-body">
            <i class="fas fa-code"></i>
            <h3>Developers</h3>
            <p class="stat-number"><?= $stats['developers'] ?></p>
        </div>
        <a href="developers.php" class="stat-link">Developers</a>
    </div>
</div>


        <div class="recent-activity">
            <div class="activity-section">
                <h2>Recent Blog Posts</h2>
                <div class="activity-list">
                    <?php if ($recent_posts && $recent_posts->num_rows > 0): ?>
                        <?php while ($post = $recent_posts->fetch_assoc()): ?>
                            <div class="activity-item">
                                <h4><?= htmlspecialchars($post['title']) ?></h4>
                                <p class="activity-date">Posted: <?= date('M j, Y', strtotime($post['created_at'])) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-items">No recent posts</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="activity-section">
                <h2>Recent News & Events</h2>
                <div class="activity-list">
                    <?php if ($recent_news && $recent_news->num_rows > 0): ?>
                        <?php while ($news = $recent_news->fetch_assoc()): ?>
                            <div class="activity-item">
                                <h4><?= htmlspecialchars($news['title']) ?></h4>
                                <p class="activity-date">Posted: <?= date('M j, Y', strtotime($news['created_at'])) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-items">No recent news</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="activity-section">
                <h2>Recent Community Updates</h2>
                <div class="activity-list">
                    <?php if ($recent_community && $recent_community->num_rows > 0): ?>
                        <?php while ($community = $recent_community->fetch_assoc()): ?>
                            <div class="activity-item">
                                <h4><?= htmlspecialchars($community['title']) ?></h4>
                                <p class="activity-date">Posted: <?= date('M j, Y', strtotime($community['created_at'])) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="no-items">No recent updates</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 
