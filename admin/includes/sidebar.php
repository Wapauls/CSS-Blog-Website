<?php
// Get the current page name from the filename
$current_file = basename($_SERVER['PHP_SELF']);
$current_page = str_replace('.php', '', $current_file);
?>
<div class="sidebar">
    <div class="logo">
        <h2>Admin Panel</h2>
    </div>
    <nav>
        <a href="index.php" class="<?= $current_page === 'index' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard Overview</span>
        </a>
        <a href="blogpost.php" class="<?= $current_page === 'blogpost' ? 'active' : '' ?>">
            <i class="fas fa-blog"></i>
            <span>Blog Posts</span>
        </a>
        <a href="about.php" class="<?= $current_page === 'about' ? 'active' : '' ?>">
            <i class="fas fa-info-circle"></i>
            <span>About</span>
        </a>
        <a href="community.php" class="<?= $current_page === 'community' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Community</span>
        </a>
        <a href="news.php" class="<?= $current_page === 'news' ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i>
            <span>News and Events</span>
        </a>
        <a href="developers.php" class="<?= $current_page === 'developers' ? 'active' : '' ?>">
            <i class="fas fa-code"></i>
            <span>Developers</span>
        </a>
    </nav>
</div> 