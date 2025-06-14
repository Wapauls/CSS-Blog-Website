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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="binary-animation.js" defer></script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
    <div class="nav-container">
        <div class="logo">
            <img src="../user/uploads/logo.png" alt="Department Logo" class="dept-logo">
        </div>

        <div class="nav-links">
            <a href="index.php" class="<?= $current_page === 'home' ? 'active' : '' ?>">Home</a>
            <a href="?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">About</a>
            <a href="?page=community" class="<?= $current_page === 'community' ? 'active' : '' ?>">Community</a>
            <a href="?page=news" class="<?= $current_page === 'news' ? 'active' : '' ?>">News and Events</a>
            <a href="?page=developers" class="<?= $current_page === 'developers' ? 'active' : '' ?>">Developers</a>
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
    <a href="index.php" class="<?= $current_page === 'home' ? 'active' : '' ?>">Home</a>
    <a href="?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">About</a>
    <a href="community.php">Community</a>
    <a href="?page=news" class="<?= $current_page === 'news' ? 'active' : '' ?>">News and Events</a>
    <a href="?page=developers" class="<?= $current_page === 'developers' ? 'active' : '' ?>">Developers</a>
</div>

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
                <img src="../user/uploads/bscslogo.png" alt="Logo" class="hero-department-logo">
            </div>
        </section>

        <!-- Institution Section -->
        <section class="institution-section">
            <div class="institution-content">
                <div class="institution-left">
                    <div class="feature-cards">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <img src="../user/uploads/code.png" alt="Code Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Code</h3>
                                <p>Dive into a space where innovation thrives—write, build, and bring ideas to life through the power of code.</p>
                            </div>
                        </div>
                        <div class="feature-card dark">
                            <div class="feature-icon">
                                <img src="../user/uploads/style.png" alt="Style Icon">
                            </div>
                            <div class="feature-text">
                                <h3>Style</h3>
                                <p>Shine as a future tech leader—show your skills, inspire, and leave your mark in computer science.</p>
                            </div>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <img src="../user/uploads/shine.png" alt="Shine Icon">
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
                        <img src="../user/uploads/scc.png" alt="SCC Logo" class="institution-logo">
                        <img src="../user/uploads/sco.png" alt="SCO Logo" class="institution-logo">
                    </div>
                </div>
            </div>
        </section>
       <!-- Partners Section -->
        <section class="partners-section">
            <h2>Our <span>Partners</span></h2>
            <div class="partners-container">
                <div class="partner-logo">
                    <img src="../user/uploads/sco.png" alt="SCO Online">
                </div>
                <div class="partner-logo">
                    <img src="../user/uploads/acer.png" alt="Acer">
                </div>
                <div class="partner-logo">
                    <img src="../user/uploads/edusuite.png" alt="Edusuite">
                </div>
                <div class="partner-logo">
                    <img src="../user/uploads/predator.png" alt="Predator">
                </div>
                <div class="partner-logo">
                    <img src="../user/uploads/knights.png" alt="Knights">
                </div>
                <div class="partner-logo">
                    <img src="../user/uploads/meta.png" alt="Meta">
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

<!-- Community Section -->

    <?php elseif ($current_page === 'community'): ?>
        <div class="community-title">
            <h1 class="community-header"> Our <span class="highlight"> Community </span> </div>

    <div class="community-container">
        <h1 class="Community"> NextGen <span class="highlight-community">CSS</span></h1>

        <div class="campaign-container">
            <img src="/user/uploads/css-campaign.png" alt="Campaign" class="campaign-image">
                <p class="Community-description">We, the Computer Science Society (CSS) community, are committed to building a progressive and inclusive society.<br><br>
                    Rooted in innovation and driven by purpose, we aim to empower Clarean students to lead with integrity, collaborate with intention, and thrive in the evolving world of Information and Computing Sciences.<br><br>
                        Through the Progressive CSS Campaign, we foster a culture of excellence, growth, and shared knowledge.<br><br>
                            We envision a future where every member is equipped, inspired, and united in shaping meaningful change through technology.<br></p>
        </div>

    <div class="cs-society-section">
        <div class="cs-society-text">
            <div class="cs-society-header">   
                <h1 class="cs-society-title">Clarean CS Society:</h1>
                    <h2 class="cs-society-subtitle">Code. Connect. Create.</h2>
        </div>

        <div class="cs-society-content">
            <img src="../user/uploads/hartsdeh.jpg" alt="CS Society" class="cs-image">         
                <p class="cs-society-description">The Clarean CS Society is a student-led organization dedicated to cultivating excellence in information and Computing Sciences through coding collaboration and innovation. <br> <br>
                    We code with passion, connect as a united community and create a future driven by technology empowering Clarean students to lead in a rapidly evolving digital world. </p>
        </div>
    </div>
</div>


</div>
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

            <div class="faculty-grid"> <!--First Row-->
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

            <div class="faculty-grid"> <!--Second Row-->
                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=MB" alt="Mr. Hobel Dioquino" class="faculty-image">
                    <h3 class="faculty-name">Mr. Hobel Dioquino</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=MAM" alt="Mr. Julius Abendano" class="faculty-image">
                    <h3 class="faculty-name">Mr. Julius Abendano</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=EC" alt="Mr. Erwin Guerra" class="faculty-image">
                    <h3 class="faculty-name">Mr. Erwin Guerra</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>

                <div class="faculty-member">
                    <img src="https://via.placeholder.com/200x200/00d4ff/ffffff?text=AG" alt="Mr. Rafaelito Quimora" class="faculty-image">
                    <h3 class="faculty-name">Mr. Rafaelito Quimora</h3>
                    <p class="faculty-position">BSCS Faculty</p>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Board of Executive Section -->

<div class="board-executive-section">
    <h1 class="board-executive-title">The
        <span class="highlight">Board of Executive</span>
    </h1>
    <p class="board-executive-description">
        The Executive Board comprises committed and experienced leaders who provide strategic direction to the society, driving its mission and fostering excellence in the field of information and Computing Sciences. </p>
        <div class="board-executive-container">
        <div class="executive-faculty-grid"> <!--First Row-->
                <div class="pres-Kyle">
                    <img src="../user/uploads/Pres.Kyle.jpg" alt="Kyle-jpg" class="faculty-image">
                    <h3 class="faculty-name">Kyle Vincent Z. Pasuquin</h3>
                    <p class="faculty-position">Departmental President</p>
                </div>

                <div class="VP-internal">
                    <img src="../user/uploads/VP internal.Jake.jpg" alt="Russel-jpg" class="faculty-image">
                    <h3 class="faculty-name">Jake Russel Belen</h3>
                    <p class="faculty-position">Vice-President Internal</p>
                </div>

                <div class="VP-external">
                    <img src="../user/uploads/VP ecternal.raine.jpg" alt="Raine-jpg" class="faculty-image">
                    <h3 class="faculty-name">Raine E.Esparrago</h3>
                    <p class="faculty-position">Vice-President External</p>
                </div>

            </div>

            <div class="executive-grid"> <!--Second Row-->

                <div class="dept-secretary">
                    <img src="../user/uploads/secretary.clarisse.jpg" alt="Clariss-jpg" class="faculty-image">
                    <h3 class="faculty-name">Clariss Mae D. Selisana</h3>
                    <p class="faculty-position">Departmental Secretary</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/treasurer.shen.jpg" alt="Shenmar Bonifacio" class="faculty-image">
                    <h3 class="faculty-name">Shenmar Bonifacio</h3>
                    <p class="faculty-position">Departmental Treasurer</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/audtior.balili.jpg" alt="Jahn Florence Balili" class="faculty-image">
                    <h3 class="faculty-name">Jahn Florence Balili</h3>
                    <p class="faculty-position">Departmental Auditor</p>
                </div>
        </div>
    </div>

    <!--Core Executive Section -->

    <div class="core-executive-section">
        <h1 class="core-executive-title">The
            <span class="highlight">Core Executive</span>
        </h1>
    </div>

    <div class="core-executive-container">
            <p class="core-executive-description">The Core Executive Board is composed of dedicated and capable leaders who play a vital role in shaping the vision of the society, providing strategic leadership, and advancing excellence in the field of Information and Computing Sciences.</p>
        </div>

    <div class="faculty-member-grid">
                <div class="faculty-member">
                    <img src="../user/uploads/core.shane.jpg" alt="Shane Xander C. Samonte" class="faculty-image">
                    <h3 class="faculty-name">Shane Xander C. Samonte</h3>
                    <p class="faculty-position">Core Officer</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/core.abby.jpg" alt="Marie Bernadette V. Agustin" class="faculty-image">
                    <h3 class="faculty-name">Marie Bernadette V. Agustin</h3>
                    <p class="faculty-position">Core Officer</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/core.jam.jpg" alt="Jamaica C. Magtoto" class="faculty-image">
                    <h3 class="faculty-name">Jamaica C. Magtoto</h3>
                    <p class="faculty-position">Core Officer</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/core.jhonas.jpg" alt="Jhonas Angelo A. Cañotal" class="faculty-image">
                    <h3 class="faculty-name">Jhonas Angelo A. Cañotal</h3>
                    <p class="faculty-position">Core Officer</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/core.jayz.jpg" alt="Jay-z Nicolo Reyes" class="faculty-image">
                    <h3 class="faculty-name">Jay-z Nicolo Reyes</h3>
                    <p class="faculty-position">Core Officer</p>
                </div>
            </div>
    </div>

<!-- Board of Year-level Representatives Section -->

<div class="board-year-level-section">
    <h1 class="board-year-level-title">The
        <span class="highlight">Board of Year-level Representatives</span>
    </h1>
        
        <div class="board-year-level-container">
            <p class="board-year-level-description">The Board of Year-Level Representatives is composed of student leaders from each academic level who serve as the voice of their peers, fostering active engagement, promoting inclusivity, and supporting the society's mission within the field of Information and Computing Sciences.</p>
        </div>

    <div class="faculty-grid">
                <div class="faculty-member">
                    <img src="../user/uploads/4thyr rep.leofred.jpg" alt="Leofred Nuñez" class="faculty-image">
                    <h3 class="faculty-name">Leofred Nuñez</h3>
                    <p class="faculty-position">4th Year Level Representative</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/3rdyr rep.maye.jpg" alt="Marielle Nicole G. Silvestre" class="faculty-image">
                    <h3 class="faculty-name">Marielle Nicole G. Silvestre</h3>
                    <p class="faculty-position">3rd Year Level Representative</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/2ndyr rep.felicity.jpg" alt="Felicity Marie Santos" class="faculty-image">
                    <h3 class="faculty-name">Felicity Marie Santos</h3>
                    <p class="faculty-position">2nd Year Level Representative</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/1st yr rep.leigh.jpg" alt="Jaylah Leigh M. Biasa" class="faculty-image">
                    <h3 class="faculty-name">Jaylah Leigh M. Biasa</h3>
                    <p class="faculty-position">1st Year Level Representative</p>
                </div>
            </div>



<!-- Commitee Board Section -->

<div class="committee-board-section">
    <div class="faculty-member-section">
        <h1 class="board-faculty-title">The
            <span class="highlight">Operations & Committees Board</span>
        </h1>
        
        <p class="board-faculty-description">
        The Operations & Committees Board is composed of committee chairs and functional officers who lead the society's core initiatives and activities. Through their specialized roles, they ensure the effective implementation of programs, promote collaboration, and uphold the society's commitment to excellence in the field of Information and Computing Sciences.
        </p>
    </div>

        <div class="faculty-container">
            <!-- First Row: 5 Members -->
            <div class="faculty-member-grid">
                <div class="faculty-member">
                    <img src="../user/uploads/membership.benedict.jpg" alt="Benedict Bryan Montances" class="faculty-image">
                    <h3 class="faculty-name">Benedict Bryan Montances</h3>
                    <p class="faculty-position">Membership Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/business.wapol.jpg" alt="John Paul Galano" class="faculty-image">
                    <h3 class="faculty-name">John Paul Galano</h3>
                    <p class="faculty-position">Business Manager/Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/acad.juliana.jpg" alt="Juliana L. Baguio" class="faculty-image">
                    <h3 class="faculty-name">Juliana L. Baguio</h3>
                    <p class="faculty-position">Academic Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/ict.seth.jpg" alt="Seth Brisenio" class="faculty-image">
                    <h3 class="faculty-name">Seth Brisenio</h3>
                    <p class="faculty-position">ICT Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/research.cleave.jpg" alt="Cleave Cole B. Fresto" class="faculty-image">
                    <h3 class="faculty-name">Cleave Cole B. Fresto</h3>
                    <p class="faculty-position">Research Committee Chair</p>
                </div>
            </div>

            <!-- Second Row: 4 Members -->
            <div class="faculty-member-grid-row2">
                <div class="faculty-member">
                    <img src="../user/uploads/membership.benedict.jpg" alt="John Benny B. Socorro" class="faculty-image">
                    <h3 class="faculty-name">John Benny B. Socorro</h3>
                    <p class="faculty-position">Sports Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/event.elaiza.jpg" alt="Elaiza Lujibilla G. Fulgencio" class="faculty-image">
                    <h3 class="faculty-name">Elaiza Lujibilla G. Fulgencio</h3>
                    <p class="faculty-position">Events Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/cc.ember.jpg" alt="Denver Masangcay" class="faculty-image">
                    <h3 class="faculty-name">Denver Masangcay</h3>
                    <p class="faculty-position">Community Committee Chair</p>
                </div>

                <div class="faculty-member">
                    <img src="../user/uploads/environment.cali.jpg" alt="Princess Calimoso" class="faculty-image">
                    <h3 class="faculty-name">Princess Calimoso</h3>
                    <p class="faculty-position">Environmental Committee Chair</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- News and Events Section -->
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
        
<!-- Developers Section -->
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

 <!-- Footer -->
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-left">
                    <div class="footer-logo-container">
                        <img src="../uploads/transparentlogo.png" alt="CSS Logo" class="footer-logo">
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