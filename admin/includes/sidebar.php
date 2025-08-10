<?php
// Get the current page name from the filename
$current_file = basename($_SERVER['PHP_SELF']);
$current_page = str_replace('.php', '', $current_file);
?>
<div class="sidebar" id="adminSidebar">
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
<button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle sidebar"><i class="fas fa-bars"></i></button>
<script>
  (function(){
    const sidebar = document.getElementById('adminSidebar');
    const btn = document.getElementById('sidebarToggle');
    if(!sidebar || !btn) return;
    function updateForWidth(){
      const isMobile = window.innerWidth <= 768;
      if(isMobile){
        sidebar.classList.add('collapsed');
        btn.classList.add('show');
      } else {
        sidebar.classList.remove('collapsed');
        btn.classList.add('show');
      }
    }
    btn.addEventListener('click', function(){
      sidebar.classList.toggle('collapsed');
      document.body.classList.toggle('sidebar-collapsed');
    });
    window.addEventListener('resize', updateForWidth);
    updateForWidth();
  })();
</script>