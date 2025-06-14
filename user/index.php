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
    <title>CSS Blog - Community</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="binary-animation.js" defer></script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="../uploads/logo.png" alt="Department Logo" class="dept-logo">
            </div>

            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="community.php" class="active">Community</a>
                <a href="news.php">News and Events</a>
                <a href="developers.php">Developers</a>
            </div>

            <!-- Burger Menu -->
            <div class="burger-menu" id="burgerMenu">
                <div class="burger-line"></div>
                <div class="burger-line"></div>
                <div class="burger-line"></div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="community.php" class="active">Community</a>
        <a href="news.php">News and Events</a>
        <a href="developers.php">Developers</a>
    </div>

    <!-- Community Section -->
    <div class="community-container">
        <h1 class="Community"> NextGen <span class="highlight">CSS</span></h1>

        <div class="campaign-container">
            <img src="../uploads/Ado.jpg" alt="Campaign" class="Campaign-image">
                <p class="Community-description">We, the Computer Science Society (CSS) community, are committed to building a progressive and inclusive society.<br><br>
                    Rooted in innovation and driven by purpose, we aim to empower Clarean students to lead with integrity, collaborate with intention, and thrive in the evolving world of Information and Computing Sciences.<br><br>
                        Through the Progressive CSS Campaign, we foster a culture of excellence, growth, and shared knowledge.<br><br>
                            We envision a future where every member is equipped, inspired, and united in shaping meaningful change through technology.<br></p>
        </div>

    <div class="society-section">
        <h1 class="society-introduction" id="society-introduction"> Clarean CS Society:<br> <span class="highlight"> Code. Connect. Create.<br><br></span></h1>
            <div class="society-description" id="society-description">
                <p class="soc-description"> The Clarean CS Society is a student-led organization dedicated to cultivating excellence in information and Computing Sciences through coding collaboration and innovation. <br><br>
                    We code with passion, connect as a united community and create a future driven by technology empowering Clarean students to lead in a rapidly evolving digital world.<br><br><br></p>

    </div>

    <div class="department-section">
        <div class="department-head-container">
            <h1 class="department-head-title"> The <span class="highlight">Department Head and Adviser<br><br></span></h1>
                <p class="department-head-description"> Our dedicated adviser after invaluable experience and insight, providing strategic guidance and mentorship to support our members in achieving both academic excellence and professional growth.<br><br></p>   
                    <img src="../uploads/logo.png" class="adviser-image" alt="adviser-image"> <!--Placeholder Only -->   
                        <h2 class="adviser-name"> Ms. Jeanethjoy D. Naturales </h2>
                            <p class="adviser-position">Department Head and Adviser </p>
        </div>
    </div>

    <div class="board-faculty-section">
        <h1 class="board-faculty-title"> The <span class="highlight"> Board of Academic Faculty </h1> 
            <p class="board-faculty-description"> The Board of Academic Faculty is composed of esteemed educators and subject matter experts who contribute to the intellectual foundation of the society. They provide academic guidance, uphold scholarly standards, and support the holistic development of students within the field of Information and Computing Sciences. </p>
                    <div class="faculty-container">
            <div class="faculty-grid">
                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=MB" alt="Ms. Maireen Baltazar" class="faculty-image">
                    <h3 class="faculty-name">Ms. Maireen Baltazar</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=MAM" alt="Ms. May Ann Maniego" class="faculty-image">
                    <h3 class="faculty-name">Ms. May Ann Maniego</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=EC" alt="Mr. Enrico Columna" class="faculty-image">
                    <h3 class="faculty-name">Mr. Enrico Columna</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=AG" alt="Mr. Alvin Gallo" class="faculty-image">
                    <h3 class="faculty-name">Mr. Alvin Gallo</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>
            </div>
        </div>
    </div>

    </div>

        <!-- Faculty Member Section -->
    <div class="faculty-member-section">
        <h1 class="board-faculty-title">The
            <span class="highlight">Faculty Members</span>
        </h1>
        
        <p class="board-faculty-description">
            Our dedicated faculty members who contribute to the academic excellence and student development within the Computer Science Society.
        </p>

        <div class="faculty-container">
            <!-- First Row: 5 Members -->
            <div class="faculty-member-grid">
                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=BBM" alt="Benedict Bryan Montances" class="faculty-image">
                    <h3 class="faculty-name">Benedict Bryan Montances</h3>
                    <p class="faculty-position">Membership Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=JPG" alt="John Paul Galano" class="faculty-image">
                    <h3 class="faculty-name">John Paul Galano</h3>
                    <p class="faculty-position">Business Manager/Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=JLB" alt="Juliana L. Baguio" class="faculty-image">
                    <h3 class="faculty-name">Juliana L. Baguio</h3>
                    <p class="faculty-position">Academic Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=SB" alt="Seth Brisenio" class="faculty-image">
                    <h3 class="faculty-name">Seth Brisenio</h3>
                    <p class="faculty-position">ICT Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=CCBF" alt="Cleave Cole B. Fresto" class="faculty-image">
                    <h3 class="faculty-name">Cleave Cole B. Fresto</h3>
                    <p class="faculty-position">Research Committee Chair</p>
                </div>
            </div>

            <!-- Second Row: 4 Members -->
            <div class="faculty-member-grid-row2">
                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=JBBS" alt="John Benny B. Socorro" class="faculty-image">
                    <h3 class="faculty-name">John Benny B. Socorro</h3>
                    <p class="faculty-position">Sports Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=ELGF" alt="Elaiza Lujibilla G. Fulgencio" class="faculty-image">
                    <h3 class="faculty-name">Elaiza Lujibilla G. Fulgencio</h3>
                    <p class="faculty-position">Events Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=DM" alt="Denver Masangcay" class="faculty-image">
                    <h3 class="faculty-name">Denver Masangcay</h3>
                    <p class="faculty-position">Community Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/180x180/00d4ff/ffffff?text=PC" alt="Princess Calimoso" class="faculty-image">
                    <h3 class="faculty-name">Princess Calimoso</h3>
                    <p class="faculty-position">Environmental Committee Chair</p>
                </div>
            </div>
        </div>
    </div>

    <!-- From Uiverse.io by Nawsome --> 
<div class="banter-loader">
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
  <div class="banter-loader__box"></div>
</div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <div class="footer-logo-container">
                    <img src="../uploads/logo.png" alt="CSS Logo" class="footer-logo">
                </div>
                <p class="footer-tagline">Built with love, code, and caffeine by CSS ☕️</p>
            </div>
            <div class="footer-content">
                <div class="footer-links">
                    <h2>SCC Computer Science Society</h2>
                    <nav>
                        <a href="about.php">About</a>
                        <a href="community.php">Community</a>
                        <a href="news.php">News and Events</a>
                        <a href="developers.php">Developers</a>
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
    </script>
</body>
</html>