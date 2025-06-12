<span?php
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
    <link rel="stylesheet" href="nande.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
        <div class="logo">
                <img src="../uploads/logo.png" alt="Department Logo" class="dept-logo">
                <!-- <div class="logo-text-container">
                    <div class="css-logo">
                        <img src="../uploads/css-text-logo.svg" alt="CSS Logo">
                    </div>
                    <div class="text-content">
                        <h1>COMPUTER SCIENCE SOCIETY</h1>
                        <p class="binary-text">11111010010</p>
                    </div>
                </div> -->
            </div>

            <div class="nav-links">
                <a href="?page=home" class="<?= $current_page === 'home' ? 'active' : '' ?>">Home</a>
                <a href="?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">About</a>
                <a href="?page=community" class="<?= $current_page === 'community' ? 'active' : '' ?>">Community</a>
                <a href="?page=news" class="<?= $current_page === 'news' ? 'active' : '' ?>">News and Events</a>
                <a href="?page=developers" class="<?= $current_page === 'developers' ? 'active' : '' ?>">Developers</a>
            </div>
        </div>
    </nav>

    <?php if ($current_page === 'home'): ?>
        <!-- Hero Section -->
        <section class="hero">
        <div class="hero-content">
            <div class="hero-left">
                <h1 class="hero-title">
                    <span>Discover</span> the <span class="feature-btn">Code</span><br>
                    <span>Minds</span> <span>Behind</span> <span class="feature-btn outline">Style</span><br>
                    the <span>Code</span> <span class="feature-btn">Shine</span>
                </h1>
                <div class="hashtag">#MakeItCSS</div>
            </div>
            <!-- <div class="hero-right">
                <img src="../uploads/logo.png" alt="CSS Logo">
            </div> -->
        </div>
    </section>

        <!-- Blog Posts Section -->
        <div class="container">
            <h2 class="section-title">Latest <span>Blog Posts</span></h2>
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
        </div>

    <?php elseif ($current_page === 'about'): ?>
        <div class="container">
            <h2 class="section-title">About <span>Us</span></h2>
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
        </div>

    <?php elseif ($current_page === 'community'): ?>
        <div class="container">
            <h2 class="section-title">Our <span>Community</span></h2>
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
        </div>

    <?php elseif ($current_page === 'news'): ?>
        <div class="container">
            <h2 class="section-title">News & <span>Events</span></h2>
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
        </div>

    <?php elseif ($current_page === 'developers'): ?>
        <div class="container">
            <h2 class="section-title">Our <span>Developers</span></h2>
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
        </div>
    <?php endif; ?>
</body>