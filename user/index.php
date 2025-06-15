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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">
                <img src="../assets/images/logo.png" />
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
    <?php if ($current_page === 'home'): ?>
        <!-- Hero Section -->
        <section class="hero">
            <div class="binary-rain"></div>
            <div class="hero-content">
                <div class="hero-left">
                    <h1 class="hero-title">
                        <span class="highlight">Discover</span> the<br>
                        <span class="highlight">Minds Behind</span><br>
                        the <span class="highlight">Code</span>
                    </h1>
                    <div class="hashtag">#MakeItCSS</div>
                </div>
                <div class="hero-right">
                    <div class="feature-pills">
                        <div class="feature-pill-same"><span class="highlight-letter">C</span>ode</div>
                        <div class="feature-pill outline"><span class="highlight-letter">S</span>tyle</div>
                        <div class="feature-pill-same"><span class="highlight-letter">S</span>hine</div>
                    </div>
                </div>
                <img src="../assets/images/bscslogo.png" alt="Logo" class="hero-department-logo">
            </div>
        </section>

        <!-- Institution Section -->
        <section class="institution-section">
            <div class="institution-content">
                <div class="institution-left">
                    <div class="feature-cards">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <img src="../assets/images/code.png" alt="Code Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Code</h3>
                                <p>Dive into a space where innovation thrives—write, build, and bring ideas to life through the power of code.</p>
                            </div>
                        </div>
                        <div class="feature-card dark">
                            <div class="feature-icon">
                                <img src="../assets/images/style.png" alt="Style Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Style</h3>
                                <p>Shine as a future tech leader—show your skills, inspire, and leave your mark in computer science.</p>
                            </div>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <img src="../assets/images/shine.png" alt="Shine Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Shine</h3>
                                <p>Shine as a future tech leader—show your skills, inspire, and leave your mark in computer science.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="institution-right">
                    <h1 class="institution-title">Our <span class="highlight">Institution</span></h1>
                    <p class="institution-description">The SCC Computer Science Society (SCC-CSS) operates as the official academic organization of the Computer Science Department under the guidance of the Center for Online Education System (COES) at St. Clare College. Anchored in the values of excellence and integrity, SCC-CSS is dedicated to promoting academic excellence and providing quality services to both internal and external stakeholders.</p>
                    <div class="logo-group">
                        <img src="../assets/images/scc.png" alt="SCC Logo" class="institution-logo">
                        <img src="../assets/images/sco.png" alt="SCO Logo" class="institution-logo">
                    </div>
                </div>
            </div>
        </section>
       <!-- Partners Section -->
        <section class="partners-section">
            <h2>Our <span>Partners</span></h2>
            <div class="partners-container">
                <div class="partner-logo">
                    <img src="../assets/images/sco.png" alt="SCO Online">
                </div>
                <div class="partner-logo">
                    <img src="../assets/images/acer.png" alt="Acer">
                </div>
                <div class="partner-logo">
                    <img src="../assets/images/edusuite.png" alt="Edusuite">
                </div>
                <div class="partner-logo">
                    <img src="../assets/images/predator.png" alt="Predator">
                </div>
                <div class="partner-logo">
                    <img src="../assets/images/knights.png" alt="Knights">
                </div>
                <div class="partner-logo">
                    <img src="../assets/images/meta.png" alt="Meta">
                </div>
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
                                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="">
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
            <h1>About Us</h1>
            <div class="about-section">
                <?php if ($about_entries && $about_entries->num_rows > 0): ?>
                    <?php while ($row = $about_entries->fetch_assoc()): ?>
                        <div class="about-entry">
                            <h2><?= htmlspecialchars($row['title']) ?></h2>
                            <?php if ($row['image']): ?>
                                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="">
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
                                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="">
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
                                <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="">
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
    <h1 class="developer-title">Meet Our Developers</h1>
    <div class="developers-section">
        <?php if ($dev_entries && $dev_entries->num_rows > 0): ?>
            <?php while ($row = $dev_entries->fetch_assoc()): ?>
                <div class="developer-card">
                    <div class="developer-name-pretext"><?= htmlspecialchars($row['title']) ?></div>
                    <?php if ($row['image']): ?>
                        <img class="developer-image-card" src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" loading="lazy">
                    <?php else: ?>
                        <img class="developer-image-card" src="../assets/images/default-avatar.jpg" alt="<?= htmlspecialchars($row['title']) ?>" loading="lazy">
                    <?php endif; ?>
                    <div class="developer-container">
                        <?php if ($row['image']): ?>
                            <img class="developer-profile-image" src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="Developer Profile" />
                        <?php else: ?>
                            <img class="developer-profile-image" src="../assets/images/default-avatar.jpg" alt="Developer Profile" />
                        <?php endif; ?>

                        <div class="developer-top">
                            <span><strong><?= htmlspecialchars($row['title']) ?></strong></span>
                            <span class="developer-role"><?= htmlspecialchars($row['roles']) ?></span>
                    </div>
                    
                        <div class="developer-middle">
                            <p class="content"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                            </div>

                            <div class="developer-bottom">
    <span class="skill">
        <i class="bi bi-facebook"></i>
        <a href="<?= htmlspecialchars($row['fb_links']) ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
    </span>
    <span class="skill">
        <i class="bi bi-github"></i>
        <a href="<?= htmlspecialchars($row['github_links']) ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
    </span>
</div>

                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-content">
                <h3>Developer Profiles Coming Soon</h3>
                <p>We're working on showcasing our amazing development team. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    </div>

     <!-- Footer -->
     <footer class="footer">
            <div class="footer-container">
                <div class="footer-left">
                    <div class="footer-logo-container">
                        <img src="../assets/images/transparentlogo.png" alt="CSS Logo" class="footer-logo">
                    </div>
                    <p class="footer-tagline">Built with love, code, and caffeine by CSS ☕️</p>
                </div>
                <div class="footer-content">
                    <div class="footer-links">
                        <h2>SCC Computer Science Society</h2>
                        <nav>
                            <a href="#about">About</a>
                            <a href="community.php">Community</a>
                            <a href="#news">News and Events</a>
                            <a href="#developers">Developers</a>
                        </nav>
                    </div>
                    <div class="footer-right">
                        <div class="footer-social">
                            <a href="https://www.facebook.com/SCC.CSSociety" class="social-link facebook" target="_blank" rel="noopener noreferrer">
                                <img src="../uploads/facebook.png" alt="Facebook">
                                <span>Facebook</span>
                            </a>
                            <a href="#" class="social-link email" target="_blank" rel="noopener noreferrer">
                                <img src="../uploads/email.png" alt="Email">
                                <span>Email</span>
                            </a>
                        </div>
                        <div class="footer-copyright">
                            <p>Copyright © 2025 SCC Computer Science - All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Navigation elements
        const navbar = document.querySelector('.navbar');
        const burgerMenu = document.getElementById('burgerMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        // Scroll animation
        let lastScrollY = 0;
        let ticking = false;

        function updateNavbar() {
            const scrollY = window.scrollY;
            
            if (scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScrollY = scrollY;
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        });

        // Burger menu toggle
        if (burgerMenu) {
            burgerMenu.addEventListener('click', () => {
                burgerMenu.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            });
        }

        // Close mobile menu when clicking on a link
        const mobileLinks = document.querySelectorAll('.mobile-menu a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (burgerMenu) {
                    burgerMenu.classList.remove('active');
                }
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                if (burgerMenu) {
                    burgerMenu.classList.remove('active');
                }
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Smooth reveal animation on page load
        navbar.style.transform = 'translateY(-100%)';
        navbar.style.transition = 'transform 0.5s ease';
        
        setTimeout(() => {
            navbar.style.transform = 'translateY(0)';
        }, 100);
    });

    // Binary Rain Animation
    function createBinaryRain() {
        const container = document.querySelector('.binary-rain');
        const windowWidth = window.innerWidth;

        // Create a binary digit
        function createBinary() {
            const binary = document.createElement('div');
            binary.className = 'binary';
            binary.style.left = Math.random() * windowWidth + 'px';
            binary.textContent = Math.random() < 0.5 ? '0' : '1';
            binary.style.animationDuration = (Math.random() * 10 + 3) + 's'; // Random duration between 3-5s
            container.appendChild(binary);

            // Remove the binary digit after animation
            binary.addEventListener('animationend', () => {
                binary.remove();
            });
        }

        // Create binary digits at regular intervals
        setInterval(createBinary, 100); // Create a new digit every 100ms
    }

    // Start the animation when the page loads
    window.addEventListener('load', createBinaryRain);
    </script>
</body>
</html> 