<?php
require_once '../config.php';

// Get current page for active state
$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Fetch data based on current page
switch($current_page) {
    case 'home':
        // Use latest News & Events as the source for the homepage "Latest Blog Posts" preview
        $posts = $conn->query('SELECT id, title, content, image, event_date, created_at FROM news ORDER BY created_at DESC');
        $postRows = [];
        if ($posts) {
            while ($row = $posts->fetch_assoc()) {
                $postRows[] = $row;
            }
        }
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CSS Blog - <?= ucfirst($current_page) ?></title>
    <link rel="stylesheet" href="style.css">
    <?php if ($current_page === 'home'): ?>
        <link rel="stylesheet" href="home.css">
    <?php elseif ($current_page === 'about'): ?>
        <link rel="stylesheet" href="about.css">
    <?php elseif ($current_page === 'community'): ?>
        <link rel="stylesheet" href="community.css">
    <?php elseif ($current_page === 'news'): ?>
        <link rel="stylesheet" href="news.css">
    <?php elseif ($current_page === 'developers'): ?>
        <link rel="stylesheet" href="developers.css">
    <?php endif; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Bar -->
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <img src="../assets/images/icons/css-css-icon.png" class="header-logo"/>
                <div class="nav-spacer">
                    <div class="nav-links">
                    <a href="?page=home" class="<?= $current_page === 'home' ? 'active' : '' ?>">
                        Home
                    </a>
                    <a href="?page=about" class="<?= $current_page === 'about' ? 'active' : '' ?>">
                        About
                    </a>
                    <a href="?page=community" class="<?= $current_page === 'community' ? 'active' : '' ?>">
                        Community
                    </a>
                    <a href="?page=news" class="<?= $current_page === 'news' ? 'active' : '' ?>">
                        News and Events
                    </a>
                    <a href="?page=developers" class="<?= $current_page === 'developers' ? 'active' : '' ?>">
                        Developers
                    </a>
                    </div>
                
                    <!-- Mobile Menu Toggle -->
                    <div class="burger-menu" id="burgerMenu">
                        <div class="burger-line"></div>
                        <div class="burger-line"></div>
                        <div class="burger-line"></div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu" id="mobileMenu">
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

    <main>

        <!-- Home Page -->

        <?php if ($current_page === 'home'): ?>
            <div class="home-page">

                <!-- Hero Section -->
                <section class="first-content">
                    <div class="binary-rain"></div>
                    <div class="main-content">
                        <div class="main-text-container">
                            <div class="layer">
                                <span class="non-highlighted-text">Discover the</span><div class="special-text-container"><span class="special-text-highlighted">C</span><span class="special-text-non-highlighted">ode</span></div>
                            </div>
                            <div class="layer">
                                <span class="highlighted-text">Minds</span><span class="non-highlighted-text">Behind</span><div class="special-text-container"><span class="special-text-highlighted">S</span><span class="special-text-non-highlighted">tyle</span></div>
                            </div>
                            <div class="layer">
                                <span class="non-highlighted-text">the</span><span class="highlighted-text">Code</span><div class="special-text-container"><span class="special-text-highlighted">S</span><span class="special-text-non-highlighted">hine</span></div>
                            </div>
                            <div class="layer">
                                <span class="quote-text">#MakeItCSS</span>
                            </div>
                        </div>
                        <img class="face-icon" src="../assets/images/icons/face-css.png">
                    </div>
                </section>

                <div class="section-divider"></div>

                <!-- Institution Section -->
                <section class="institution-section">
                    <div class="institution-content">
                        <div class="institution-left">
                            <div class="feature-cards">

                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <img src="../assets/images/code.png" alt="Code Icon" />
                                    </div>
                                    <div class="feature-text">
                                        <h3>Code</h3>
                                        <p>Dive into a space where innovation thrives—write, build, and bring ideas to life through the power of code.</p>
                                    </div>
                                </div>

                                <div class="feature-card dark">
                                    <div class="feature-icon">
                                        <img src="../assets/images/style.png" alt="Style Icon" />
                                    </div>
                                    <div class="feature-text">
                                        <h3>Style</h3>
                                        <p>Shine as a future tech leader—show your skills, inspire, and leave your mark in computer science.</p>
                                    </div>
                                </div>

                                <div class="feature-card">
                                    <div class="feature-icon">
                                        <img src="../assets/images/shine.png" alt="Shine Icon" />
                                    </div>
                                    <div class="feature-text">
                                        <h3>Shine</h3>
                                        <p>Shine as a future tech leader—show your skills, inspire, and leave your mark in computer science.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="institution-right">
                            <h1 class="institution-title">
                                Our <span class="highlight">Institution</span>
                            </h1>
                            <p class="institution-description">
                                The SCC Computer Science Society (SCC-CSS) operates as the official academic organization of the Computer Science Department under the guidance of the Center for Online Education System (COES) at St. Clare College. Anchored in the values of excellence and integrity, SCC-CSS is dedicated to promoting academic excellence and providing quality services to both internal and external stakeholders.
                            </p>
                            <div class="logo-group">
                                <img src="../assets/images/scc.png" alt="SCC Logo" class="institution-logo" />
                                <img src="../assets/images/sco.png" alt="SCO Logo" class="institution-logo" />
                            </div>
                        </div>
                    </div>
                </section>

                <div class="section-divider"></div>

                <!-- Partners Section -->
                <section class="partners-section">
                    <h2>Our <span>Partners</span></h2>
                    <div class="partners-container">
                        <div class="partner-logo"><img src="../assets/images/sco.png" alt="SCO Online" /></div>
                        <div class="partner-logo"><img src="../assets/images/acer.png" alt="Acer" /></div>
                        <div class="partner-logo"><img src="../assets/images/edusuite.png" alt="Edusuite" /></div>
                        <div class="partner-logo"><img src="../assets/images/predator.png" alt="Predator" /></div>
                        <div class="partner-logo"><img src="../assets/images/knights.png" alt="Knights" /></div>
                        <div class="partner-logo"><img src="../assets/images/meta.png" alt="Meta" /></div>
                    </div>
                </section>

                <div class="section-divider"></div>

                <!-- Blog Posts Section -->
                <section class="blog-section">
                    <div class="blog-container">
                        <h2 class="blog-section-title">Latest <span>Blog Posts</span></h2>
                        <div class="blog-carousel-wrapper">
                            <div class="blog-carousel" id="blogCarousel">

                                <?php if (!empty($postRows)): ?>
                                    <?php foreach ($postRows as $postIndex => $row): ?>
                                        <article class="blog-post-card <?= $postIndex === 0 ? 'active' : '' ?>" data-index="<?= $postIndex ?>">
                                            <div class="blog-post-image">
                                                <?php if (!empty($row['image'])): ?>
                                                    <img class="preview-post-img" src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" />
                                                <?php else: ?>
                                                    <div class="blog-post-placeholder">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="blog-post-content">
                                                <h3 class="blog-post-title"><?= htmlspecialchars($row['title']) ?></h3>
                                                <div class="blog-post-subtitle">By Committee</div>
                                                <p class="blog-post-excerpt"><?= substr(strip_tags($row['content']), 0, 150) ?>...</p>
                                                <hr class="blog-post-divider" />
                                                <div class="blog-post-dates">
                                                    <div class="blog-post-date-line">
                                                        Date :
                                                        <?php if (!empty($row['event_date'])): ?>
                                                            <?= date('d / m / Y', strtotime($row['event_date'])) ?>
                                                        <?php else: ?>
                                                            <?= date('d / m / Y', strtotime($row['created_at'])) ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="blog-post-posted">Posted recently</div>
                                                </div>
                                                <a class="blog-post-see-more" href="?page=news#news-<?= (int) $row['id'] ?>">
                                                    See More <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </article>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="blog-no-content">
                                        <i class="fas fa-newspaper"></i>
                                        <h3>No posts available yet</h3>
                                        <p>Check back soon for exciting updates from the CSS community!</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($postRows) && count($postRows) > 1): ?>
                                <div class="blog-carousel-controls">
                                    <button class="blog-carousel-btn blog-carousel-prev" id="blogPrev">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <button class="blog-carousel-btn blog-carousel-next" id="blogNext">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="blog-carousel-indicators" id="blogIndicators">
                                    <?php for ($indicatorIndex = 0; $indicatorIndex < count($postRows); $indicatorIndex++): ?>
                                        <button class="blog-indicator <?= $indicatorIndex === 0 ? 'active' : '' ?>" data-index="<?= $indicatorIndex ?>"></button>
                                    <?php endfor; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>

        <!-- About Page -->

        <?php elseif ($current_page === 'about'): ?>
            <div class="about-page">
                <!-- About Hero Section -->
                <div class="about-page-hero">
                    <div class="about-page-container">
                        <div class="about-page-text-content">
                            <h1>Time to talk about <span class="about-css-highlight">CSS!</span></h1>
                            <p>
                                A dedicated community of students striving for academic excellence, serving as role models while actively contributing 
                                to the personal growth and social development of its members.
                            </p>
                            <button class="about-know-more-btn"  onclick="window.location.href='https://web.facebook.com/SCC.CSSociety'">Know more about us!</button>
                        </div>
                        <div class="about-page-hero-image">
                            <?php
                            $hero_image = $conn->query('SELECT * FROM about WHERE title = "Hero Image" LIMIT 1');
                            if ($hero_image && $hero_image->num_rows > 0) {
                                $hero = $hero_image->fetch_assoc();
                            ?>
                                <img src="../uploads/<?= htmlspecialchars($hero['image']) ?>" alt="Hero Image">
                            <?php } else { ?>
                                <img src="https://via.placeholder.com/400x300/667eea/ffffff?text=Hero+Image" alt="Hero Image">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="about-section-divider"></div>

                <!-- BSCS Perks Section -->
                <div class="about-main-text">
                    <h1>
                        <span class="about-highlight">PERKS</span> of taking a<br>
                        Bachelor of <span class="about-highlight">Science</span> in <span class="about-highlight">Computer Science</span> 
                        (<span class="about-highlight">BSCS</span>)
                    </h1>
                </div>

                <div class="about-container">
                    <!-- Main Perks -->
                    <div class="about-grid-container">
                        <div class="about-card">
                            <h4>High Demand & Job Opportunities</h4>
                            <ul>
                                <li>The tech industry is booming—CS graduates are in demand globally.</li>
                                <li>Careers in software development, cybersecurity, data science, AI, and more.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Competitive Salary</h4>
                            <ul>
                                <li>One of the highest-paying fields for fresh graduates and professionals.</li>
                                <li>Opportunities to grow financially with experience and specialization.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Strong Problem-Solving & Logical Thinking Skills</h4>
                            <ul>
                                <li>Learn to break down complex problems and think critically.</li>
                                <li>These skills are useful beyond tech—great for entrepreneurship, leadership, and research.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Global Opportunities</h4>
                            <ul>
                                <li>Computer science skills are in demand everywhere.</li>
                                <li>Work remotely or apply to tech companies around the world.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Flexibility in Career Paths</h4>
                            <ul>
                                <li>Not limited to one role—you can go into web dev, mobile apps, games, AI, data, security, etc.</li>
                                <li>You can also pursue teaching, management, or freelancing.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Innovation & Creativity</h4>
                            <ul>
                                <li>Build apps, websites, games, and systems that solve real-world problems.</li>
                                <li>Turn your ideas into reality through code and technology.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Foundation for Further Studies</h4>
                            <ul>
                                <li>Strong base for pursuing advanced degrees like MS in CS, Data Science, or AI.</li>
                                <li>You can also shift into related fields like IT, engineering, or business analytics.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Hands-on Skills</h4>
                            <ul>
                                <li>Learn programming, algorithms, database systems, software engineering, etc.</li>
                                <li>Gain project experience through capstone, internships, and hackathons.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Opportunity to Build Real-World Projects</h4>
                            <ul>
                                <li>
                                    From apps and websites to AI systems and games, CS students get hands-on experience 
                                    building real tech solutions.
                                </li>
                                <li>
                                    These projects not only boost your portfolio but also prepare you for internships, 
                                    freelancing, or launching your own startup.
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="about-section-divider"></div>

                    <!-- Job Opportunities Section -->
                    <h1 class="about-job-title">
                        <span class="about-highlight">Job opportunities</span> of taking a<br>
                        Bachelor of <span class="about-highlight">Science</span> in Computer Science 
                        (<span class="about-highlight">BSCS</span>)
                    </h1>

                    <!-- Specialized Jobs -->
                    <div class="about-grid-container">
                        <div class="about-card">
                            <h4>Software Developer / Engineer</h4>
                            <ul>
                                <li>Design, build, and maintain applications or systems.</li>
                                <li>Can specialize in web, mobile, desktop, or enterprise software.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Web Developer</h4>
                            <ul>
                                <li>Create and manage websites and web applications.</li>
                                <li>Front-end (user interface), back-end (server-side logic), or full-stack (both).</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Mobile App Developer</h4>
                            <ul>
                                <li>
                                    Develop apps for iOS and Android using tools like Flutter, React Native, or native languages.
                                </li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>AI / Machine Learning Engineer</h4>
                            <ul>
                                <li>Design smart systems that can learn and improve.</li>
                                <li>Used in fields like healthcare, finance, and robotics.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Data Analyst / Data Scientist</h4>
                            <ul>
                                <li>Analyze large sets of data to help companies make data-driven decisions.</li>
                                <li>Skills in Python, SQL, and tools like Power BI or Tableau are key.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Cybersecurity Specialist</h4>
                            <ul>
                                <li>Protect systems, networks, and data from attacks.</li>
                                <li>Work in ethical hacking, network security, or risk management.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Cloud Engineer / DevOps Engineer</h4>
                            <ul>
                                <li>Manage cloud infrastructure (AWS, Azure, Google Cloud).</li>
                                <li>Automate and optimize software deployment pipelines.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Systems Analyst</h4>
                            <ul>
                                <li>Evaluate and improve computer systems for businesses.</li>
                                <li>Act as a bridge between tech and business.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>IT Support Specialist / Network Administrator</h4>
                            <ul>
                                <li>Maintain hardware, software, and networks.</li>
                                <li>Solve tech issues in real time for users and teams.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Game Developer</h4>
                            <ul>
                                <li>Create and design interactive games for PC, consoles, or mobile.</li>
                                <li>Requires coding, creativity, and sometimes 3D modeling skills.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>Tech Entrepreneur / Startup Founder</h4>
                            <ul>
                                <li>Use your skills to launch your own tech business, app, or service.</li>
                            </ul>
                        </div>

                        <div class="about-card">
                            <h4>UI/UX Designer</h4>
                            <ul>
                                <li>
                                    UI (User Interface) deals with how the app or website looks—layouts, colors, 
                                    fonts, and visual elements.
                                </li>
                                <li>
                                    UX (User Experience) focuses on how it feels—making sure it's smooth, user-friendly, 
                                    and enjoyable.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="about-section-divider"></div>

                <!-- Why BSCS Section -->
                <main class="about-main-content">
                    <div class="about-content-left">
                        <h1 class="about-main-title">
                            <span class="about-why-text">WHY</span>
                            <span class="about-bachelor-text">Bachelor of Science in<br>Computer Science</span>
                        </h1>

                        <p class="about-description">
                            BSCS empowers you with in-demand skills in programming, problem-solving, and innovation.
                            It opens doors to careers in software development, artificial intelligence, cybersecurity, 
                            game design, and more.
                        </p>

                        <p class="about-impact-text">
                            Computer Science gives you the power to create, lead, and make a real impact in the digital world.
                        </p>
                    </div>

                    <div class="about-content-right">
                        <div class="about-poster-container">
                            <?php
                            $poster_image = $conn->query('SELECT * FROM about WHERE title = "Poster Image" LIMIT 1');
                            if ($poster_image && $poster_image->num_rows > 0) {
                                $poster = $poster_image->fetch_assoc();
                            ?>
                                <img src="../uploads/<?= htmlspecialchars($poster['image']) ?>" alt="Why BSCS" class="about-poster-image">
                            <?php } else { ?>
                                <img src="../assets/images/cc.ember.jpg" alt="Why BSCS" class="about-poster-image">
                            <?php } ?>
                        </div>
                    </div>
                </main>

                <div class="about-section-divider"></div>

                <!-- What is BSCS Section -->
                <section class="about-what-is-bscs-section">
                    <div class="about-what-is-container">
                        <?php
                        $bscs_image = $conn->query('SELECT * FROM about WHERE title = "BSCS Image" LIMIT 1');
                        if ($bscs_image && $bscs_image->num_rows > 0) {
                            $bscs = $bscs_image->fetch_assoc();
                        ?>
                            <img src="../uploads/<?= htmlspecialchars($bscs['image']) ?>" 
                                alt="What is BSCS" 
                                class="about-poster-image">
                        <?php } else { ?>
                            <img src="https://via.placeholder.com/500x600/667eea/ffffff?text=What+is+BSCS" 
                                alt="What is BSCS" 
                                class="about-poster-image">
                        <?php } ?>
                        
                        <div class="about-bscs-content">
                            <h1 class="about-bscs-main-title">
                                <span class="about-what-highlight">WHAT</span>
                                <span class="about-is-text">IS</span>
                                <span class="about-degree-title">Bachelor of Science in<br>Computer Science</span>
                            </h1>
                            
                            <div class="about-bscs-description">
                                <p>
                                    The Bachelor of Science in Computer Science (BSCS) is a four-year degree program that focuses on 
                                    the study of computers, software, and technology. It teaches students how to design, develop, and 
                                    improve computer systems and applications. Topics include programming, data structures, algorithms, 
                                    databases, artificial intelligence, cybersecurity, and more.
                                </p>
                                
                                <p>
                                    BSCS prepares students for careers in fields like software development, web and mobile app development, 
                                    IT support, systems analysis, game development, and emerging tech industries like AI and robotics. 
                                    It also builds strong problem-solving, logical thinking, and analytical skills—important tools in today's digital world.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="about-section-divider"></div>

                <!-- Enrollment Section -->
                <section class="about-enrollment-section">
                    <div class="about-enrollment-container">
                        <?php
                        $enrollment_image = $conn->query('SELECT * FROM about WHERE title = "Enrollment Image" LIMIT 1');
                        if ($enrollment_image && $enrollment_image->num_rows > 0) {
                            $enrollment = $enrollment_image->fetch_assoc();
                        ?>
                            <img src="../uploads/<?= htmlspecialchars($enrollment['image']) ?>" 
                                alt="Enrollment" 
                                class="about-enrollment-image">
                        <?php } else { ?>
                            <img src="https://via.placeholder.com/500x400/764ba2/ffffff?text=Enrollment" 
                                alt="Enrollment" 
                                class="about-enrollment-image">
                        <?php } ?>
                        
                        <div class="about-content-section">
                            <h1>ENROLLMENT FOR SCHOOL YEAR 2025-2026</h1>
                            <h2>Bachelor of Science in Computer Science</h2>
                            
                            <p>
                                Step into the future with a Bachelor of Science in Computer Science. Learn coding, problem-solving, and 
                                innovation in a supportive and tech-driven environment. Join us and start building the skills for tomorrow, today!
                            </p>

                            <div class="about-tour-info">
                                <h3>Campus Tour & Walk-in Enrollment</h3>
                                <p>Monday to Saturday</p>
                                <p>8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="about-section-divider"></div>

                <!-- Partner Spotlight Section -->
                <div class="about-page-hero">
                    <div class="about-page-container">
                        <div class="about-page-text-content">
                            <h1>Partner Spotlight: <span class="about-highlight">Edusuite</span></h1>
                            <p>
                                Edusuite is an AI-powered school management system that helps schools like ours run smarter and more efficiently. 
                                As a valued partner of St. Clare College, Edusuite supports the academic journey of students by improving 
                                class scheduling, enrollment, grading, and even online payments.
                            </p>

                            <div class="about-features-list">
                                <p>With Edusuite, students experience:</p>
                                <ul>
                                    <li>Smarter subject advising</li>
                                    <li>Organized class schedules</li>
                                    <li>Easy access to grades and academic progress</li>
                                    <li>Convenient online payment options</li>
                                </ul>
                            </div>

                            <p>
                                Our school stays innovative and future-ready—making your experience as a Computer Science student smoother 
                                and more tech-driven.
                            </p>
                        </div>

                        <div class="about-page-hero-images">
                            <div class="about-card-header">
                                <?php
                                $edusuite_image = $conn->query('SELECT * FROM about WHERE title = "Edusuite Image" LIMIT 1');
                                if ($edusuite_image && $edusuite_image->num_rows > 0) {
                                    $edusuite = $edusuite_image->fetch_assoc();
                                ?>
                                    <img src="../uploads/<?= htmlspecialchars($edusuite['image']) ?>" 
                                        alt="Edusuite Logo" 
                                        class="about-school-logo">
                                <?php } else { ?>
                                    <img src="https://via.placeholder.com/200x100/00bcd4/ffffff?text=Edusuite" 
                                        alt="Edusuite Logo" 
                                        class="about-school-logo">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edusuite Payment Instructions Section -->
                <section class="about-edusuite-payment-section">
                    <div class="about-container" style="max-width: 1400px; margin: 0 auto;">
                        <!-- Main Title -->
                        <div style="text-align: center; margin-bottom: 5rem;">
                            <h1 style="font-size: 4rem; font-weight: bold; margin-bottom: 3rem; letter-spacing: -1px;">
                                Edusuite Portal <span style="color: #00bcd4;">Payment Instruction</span>
                            </h1>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
                            <!-- Left Side - Edusuite Portal Card -->
                            <div style="margin-bottom: 4rem;">
                                <div class="about-edusuite-card">
                                    <!-- Header with St. Clare Logo and CSS Logo -->
                                    <div class="about-card-header">
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 60px; height: 60px; background: #333; border-radius: 50%; 
                                                        display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                                <span style="color: white; font-weight: bold; font-size: 14px;">SCC</span>
                                            </div>
                                            <div>
                                                <h6 style="color: #333; margin: 0; font-size: 12px; font-weight: bold; letter-spacing: 1px;">
                                                    ST. CLARE COLLEGE
                                                </h6>
                                                <p style="color: #666; margin: 0; font-size: 10px; letter-spacing: 0.5px;">
                                                    COMPUTER SCIENCE SOCIETY
                                                </p>
                                            </div>
                                        </div>
                                        <div style="background: #333; color: white; padding: 12px 18px; border-radius: 12px; 
                                                    font-weight: bold; font-size: 18px; letter-spacing: 1px;">
                                            CSS
                                        </div>
                                    </div>

                                    <!-- Main Title -->
                                    <div style="text-align: center; margin-bottom: 50px;">
                                        <h2 style="color: #333; font-size: 2.8rem; font-weight: 900; margin-bottom: 8px; 
                                                letter-spacing: -1px; line-height: 1;">
                                            EDUSUITE PORTAL
                                        </h2>
                                        <h3 style="color: #00bcd4; font-size: 2.2rem; font-weight: 900; margin: 0; 
                                                letter-spacing: -0.5px; line-height: 1;">
                                            PAYMENT INSTRUCTION
                                        </h3>
                                        <p style="color: #666; font-size: 11px; margin-top: 15px; letter-spacing: 1px; font-weight: 500;">
                                            CENTER FOR ONLINE EDUCATION SYSTEM
                                        </p>
                                    </div>

                                    <!-- Bottom Section -->
                                    <div class="about-card-footer">
                                        <!-- Progress Bar -->
                                        <div style="margin-bottom: 25px;">
                                            <div style="display: flex; align-items: center; justify-content: center;">
                                                <div style="width: 12px; height: 12px; background: #00bcd4; border-radius: 50%; position: relative;">
                                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                                                                width: 6px; height: 6px; background: white; border-radius: 50%;">
                                                    </div>
                                                </div>
                                                <div style="width: 80px; height: 3px; background: #00bcd4; margin: 0 8px;"></div>
                                                <div style="width: 12px; height: 12px; background: #00bcd4; border-radius: 50%; position: relative;">
                                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); 
                                                                width: 6px; height: 6px; background: white; border-radius: 50%;">
                                                    </div>
                                                </div>
                                                <div style="width: 80px; height: 3px; background: #ddd; margin: 0 8px;"></div>
                                                <div style="width: 12px; height: 12px; background: #ddd; border-radius: 50%;"></div>
                                            </div>
                                        </div>

                                        <!-- Footer Info -->
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div style="color: #666; font-size: 10px; font-weight: bold; letter-spacing: 0.5px;">
                                                EST. 2124-25-077
                                            </div>
                                            <div style="color: #666; font-size: 10px; font-weight: bold; letter-spacing: 0.5px;">
                                                MAY 27, 2025
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Background Elements -->
                                    <div style="position: absolute; top: 20px; right: 20px; width: 150px; height: 150px; 
                                                background: radial-gradient(circle, rgba(0,188,212,0.1) 0%, transparent 70%); 
                                                border-radius: 50%;">
                                    </div>
                                    <div style="position: absolute; bottom: 20px; left: 20px; width: 100px; height: 100px; 
                                                background: radial-gradient(circle, rgba(0,188,212,0.05) 0%, transparent 70%); 
                                                border-radius: 50%;">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side - Payment Steps -->
                            <div>
                                <div class="about-payment-steps">
                                    <div class="about-payment-step"><h4>Step 1: Log in to Your Edusuite Account</h4></div>
                                    <div class="about-payment-step"><h4>Step 2: Access the Finance Widget</h4></div>
                                    <div class="about-payment-step"><h4>Step 3: Initiate Online Payment</h4></div>
                                    <div class="about-payment-step"><h4>Step 4: Select Term and Payment Method</h4></div>
                                    <div class="about-payment-step"><h4>Step 5: Enter Payment Amount</h4></div>
                                    <div class="about-payment-step"><h4>Step 6: Proceed to Payment</h4></div>
                                    <div class="about-payment-step"><h4>Step 7: Confirm Payment</h4></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>


<?php elseif ($current_page === 'community'): ?>
  <div class="community-page">
    <?php
    // Fetch community content from database using the simplified structure
    $community_entries = $conn->query('SELECT * FROM community WHERE category NOT IN ("faculty", "executive", "core", "year_representative", "committee") ORDER BY created_at DESC');
    ?>
    
    <!-- Community Section -->
    <div class="community-container">
      <h1 class="Community">
        NextGen <span class="highlight-community">CSS</span>
      </h1>
      
      <p class="Community-description">
        We, the Computer Science Society (CSS) community, are committed to building a progressive and inclusive society.<br><br>
        Rooted in innovation and driven by purpose, we aim to empower Clarean students to lead with integrity, collaborate with intention, and thrive in the evolving world of Information and Computing Sciences.<br><br>
        Through the Progressive CSS Campaign, we foster a culture of excellence, growth, and shared knowledge.<br><br>
        We envision a future where every member is equipped, inspired, and united in shaping meaningful change through technology.
      </p>
      
      <?php 
      // Display embedded images
      $campaign_image = $conn->query('SELECT * FROM community WHERE category = "embedded" AND section = "campaign" LIMIT 1');
      if ($campaign_image && $campaign_image->num_rows > 0) {
          $campaign = $campaign_image->fetch_assoc();
      ?>
        <img src="../uploads/<?= htmlspecialchars($campaign['image']) ?>" alt="Campaign" class="campaign-picture">
      <?php } ?>
      
    </div>

    <!-- CS Society Section -->
    <div class="cs-society-section">
        <h1 class="cs-society-title">
            Clarean CS Society: <br> 
            <span class="highlight">Code. Connect. Create.</span>
        </h1>
        
        <p class="cs-society-description">
            The Clarean CS Society is a student-led organization dedicated to cultivating excellence in information and Computing Sciences through coding collaboration and innovation. 
            <br><br>
            We code with passion, connect as a united community and create a future driven by technology empowering Clarean students to lead in a rapidly evolving digital world.
        </p>
        
        <?php 
        $cs_image = $conn->query('SELECT * FROM community WHERE category = "embedded" AND section = "cs_society" LIMIT 1');
        if ($cs_image && $cs_image->num_rows > 0) {
            $cs = $cs_image->fetch_assoc();
        ?>
            <img src="../uploads/<?= htmlspecialchars($cs['image']) ?>" alt="CS Society" class="cs-image">
        <?php } ?>
    </div>

    <!-- Department Head and Adviser Section -->
    <div class="department-section">
        <div class="department-head-container">
            <h1 class="department-head-title"> The <span class="highlight">Department Head and Adviser<br><br></span></h1>
            <p class="department-head-description"> Our dedicated adviser after invaluable experience and insight, providing strategic guidance and mentorship to support our members in achieving both academic excellence and professional growth.<br><br></p>   
            
            <?php 
            $adviser_image = $conn->query('SELECT * FROM community WHERE category = "embedded" AND section = "adviser" LIMIT 1');
            if ($adviser_image && $adviser_image->num_rows > 0) {
                $adviser = $adviser_image->fetch_assoc();
            ?>
                <img src="../uploads/<?= htmlspecialchars($adviser['image']) ?>" class="adviser-image" alt="adviser-image">
            <?php } ?>
            
            <h2 class="adviser-name"> Ms. Jeanethjoy D. Naturales </h2>
            <p class="adviser-position">Department Head and Adviser </p>
        </div>
    </div>

    <!-- Faculty Members Section -->
    <div class="board-faculty-section">
        <h1 class="board-faculty-title"> The <span class="highlight"> Board of Academic Faculty </h1> 
        <p class="board-faculty-description"> The Board of Academic Faculty is composed of esteemed educators and subject matter experts who contribute to the intellectual foundation of the society. They provide academic guidance, uphold scholarly standards, and support the holistic development of students within the field of Information and Computing Sciences. </p>
        <div class="faculty-container">
            <div class="faculty-grid">
                <?php 
                $faculty_members = $conn->query('SELECT * FROM community WHERE category = "faculty" ORDER BY created_at DESC');
                if ($faculty_members && $faculty_members->num_rows > 0) {
                    $facultyCount = 1;
                    while ($faculty = $faculty_members->fetch_assoc()) {
                ?>
                    <div class="faculty-member">
                        <?php if ($faculty['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($faculty['image']) ?>" alt="<?= htmlspecialchars($faculty['title']) ?>" class="faculty-image">
                        <?php else: ?>
                            <img src="../uploads/bscslogo.png" alt="<?= htmlspecialchars($faculty['title']) ?>" class="faculty-image">
                        <?php endif; ?>
                        <h3 class="faculty-name"><?= htmlspecialchars($faculty['title']) ?></h3>
                        <p class="faculty-position"><?= htmlspecialchars($faculty['content']) ?></p>
                    </div>
                <?php 
                        $facultyCount++;
                    }
                }
                ?>
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
            <div class="executive-faculty-grid">
                <?php 
                $executive_members = $conn->query('SELECT * FROM community WHERE category = "executive" ORDER BY created_at DESC');
                if ($executive_members && $executive_members->num_rows > 0) {
                    $execCount = 1;
                    while ($executive = $executive_members->fetch_assoc()) {
                ?>
                    <div class="executive-<?= $execCount ?>">
                        <?php if ($executive['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($executive['image']) ?>" alt="<?= htmlspecialchars($executive['title']) ?>" class="faculty-image">
                        <?php else: ?>
                            <img src="../uploads/bscslogo.png" alt="<?= htmlspecialchars($executive['title']) ?>" class="faculty-image">
                        <?php endif; ?>
                        <h3 class="faculty-name"><?= htmlspecialchars($executive['title']) ?></h3>
                        <p class="faculty-position"><?= htmlspecialchars($executive['content']) ?></p>
                    </div>
                <?php 
                        $execCount++;
                    }
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Core Executive Section -->
    <div class="core-executive-section">
        <h1 class="core-executive-title">The
            <span class="highlight">Core Executive</span>
        </h1>
    </div>

    <div class="core-executive-container">
        <p class="core-executive-description">The Core Executive Board is composed of dedicated and capable leaders who play a vital role in shaping the vision of the society, providing strategic leadership, and advancing excellence in the field of Information and Computing Sciences.</p>
    </div>

    <div class="faculty-member-grid">
        <?php 
        $core_members = $conn->query('SELECT * FROM community WHERE category = "core" ORDER BY created_at DESC');
        if ($core_members && $core_members->num_rows > 0) {
            $coreCount = 1;
            while ($core = $core_members->fetch_assoc()) {
        ?>
            <div class="faculty-member">
                <?php if ($core['image']): ?>
                    <img src="../uploads/<?= htmlspecialchars($core['image']) ?>" alt="<?= htmlspecialchars($core['title']) ?>" class="faculty-image">
                <?php else: ?>
                    <img src="../uploads/bscslogo.png" alt="<?= htmlspecialchars($core['title']) ?>" class="faculty-image">
                <?php endif; ?>
                <h3 class="faculty-name"><?= htmlspecialchars($core['title']) ?></h3>
                <p class="faculty-position"><?= htmlspecialchars($core['content']) ?></p>
            </div>
        <?php 
                $coreCount++;
            }
        }
        ?>
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
            <?php 
            $year_rep_members = $conn->query('SELECT * FROM community WHERE category = "year_representative" ORDER BY created_at DESC');
            if ($year_rep_members && $year_rep_members->num_rows > 0) {
                $yearRepCount = 1;
                while ($year_rep = $year_rep_members->fetch_assoc()) {
            ?>
                <div class="faculty-member">
                    <?php if ($year_rep['image']): ?>
                        <img src="../uploads/<?= htmlspecialchars($year_rep['image']) ?>" alt="<?= htmlspecialchars($year_rep['title']) ?>" class="faculty-image">
                    <?php else: ?>
                        <img src="../uploads/bscslogo.png" alt="<?= htmlspecialchars($year_rep['title']) ?>" class="faculty-image">
                    <?php endif; ?>
                    <h3 class="faculty-name"><?= htmlspecialchars($year_rep['title']) ?></h3>
                    <p class="faculty-position"><?= htmlspecialchars($year_rep['content']) ?></p>
                </div>
            <?php 
                    $yearRepCount++;
                }
            }
            ?>
        </div>
    </div>

    <!-- Committee Board Section -->
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
            <div class="faculty-member-grid">
                <?php 
                $committee_members = $conn->query('SELECT * FROM community WHERE category = "committee" ORDER BY created_at DESC');
                if ($committee_members && $committee_members->num_rows > 0) {
                    $committeeCount = 1;
                    while ($committee = $committee_members->fetch_assoc()) {
                ?>
                    <div class="faculty-member">
                        <?php if ($committee['image']): ?>
                            <img src="../uploads/<?= htmlspecialchars($committee['image']) ?>" alt="<?= htmlspecialchars($committee['title']) ?>" class="faculty-image">
                        <?php else: ?>
                            <img src="../uploads/bscslogo.png" alt="<?= htmlspecialchars($committee['title']) ?>" class="faculty-image">
                        <?php endif; ?>
                        <h3 class="faculty-name"><?= htmlspecialchars($committee['title']) ?></h3>
                        <p class="faculty-position"><?= htmlspecialchars($committee['content']) ?></p>
                    </div>
                <?php 
                        $committeeCount++;
                    }
                }
                ?>
            </div>
        </div>
    </div>
        </div>

<?php elseif ($current_page === 'news'): ?>
  <div class="news-page">
    <section class="container">
      <h2 class="section-title">News and <span>Events</span></h2>
      <div class="posts">
        <?php if ($news_entries && $news_entries->num_rows > 0): ?>
          <?php $entryCount = 1; ?>
          <?php while ($row = $news_entries->fetch_assoc()): ?>
            <article id="news-<?= (int)$row['id'] ?>" class="post entry<?= $entryCount ?>">
              <h2><?= htmlspecialchars($row['title']) ?></h2>
              <?php if ($row['image']): ?>
                <img class="pic<?= $entryCount ?>" src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
              <?php endif; ?>
              <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
              <?php if (!empty($row['category'])): ?>
                <div class="post-category">Category: <?= htmlspecialchars($row['category']) ?></div>
              <?php endif; ?>
              <?php if (!empty($row['event_date'])): ?>
                <div class="post-event-date">Event Date: <?= date('F j, Y', strtotime($row['event_date'])) ?></div>
              <?php endif; ?>
              <div class="post-date">Posted on: <?= date('F j, Y', strtotime($row['created_at'])) ?></div>
            </article>
            <?php $entryCount++; ?>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="no-content">News and Events content coming soon.</p>
        <?php endif; ?>
      </div>
    </section>
  </div>

    

<?php elseif ($current_page === 'developers'): ?>
  <div class="developers-page">
    <h1 class="developer-title">Meet Our Developers</h1>
    <section class="developers-section">
      <div class="developers-grid">
        <?php if ($dev_entries && $dev_entries->num_rows > 0): ?>
          <?php while ($row = $dev_entries->fetch_assoc()): ?>
            <article class="developer-card">
              <div class="developer-name-pretext"><?= htmlspecialchars($row['title']) ?></div>
              <?php if ($row['image']): ?>
                <img class="developer-image-card" src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" loading="lazy">
              <?php else: ?>
                <img class="developer-image-card" src="../uploads/" alt="<?= htmlspecialchars($row['title']) ?>" loading="lazy">
              <?php endif; ?>
              <div class="developer-container">
                <?php if ($row['image']): ?>
                  <img class="developer-profile-image" src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Developer Profile" />
                <?php else: ?>
                  <img class="developer-profile-image" src="../uploads/" alt="Developer Profile" />
                <?php endif; ?>
                <div class="developer-top">
                  <span><strong><?= htmlspecialchars($row['title']) ?></strong></span>
                  <span class="developer-role"><?= htmlspecialchars($row['roles']) ?></span>
                </div>
                <div class="developer-middle">
                  <p class="content"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                </div>
                <div class="developer-bottom">
                  <?php if (!empty($row['fb_links'])): ?>
                    <span class="skill">
                      <i class="bi bi-facebook"></i>
                      <a href="<?= htmlspecialchars($row['fb_links']) ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($row['instagram_links'])): ?>
                    <span class="skill">
                      <i class="bi bi-instagram"></i>
                      <a href="<?= htmlspecialchars($row['instagram_links']) ?>" target="_blank" rel="noopener noreferrer">Instagram</a>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($row['linkIn_links'])): ?>
                    <span class="skill">
                      <i class="bi bi-linkedin"></i>
                      <a href="<?= htmlspecialchars($row['linkIn_links']) ?>" target="_blank" rel="noopener noreferrer">LinkIn</a>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($row['github_links'])): ?>
                    <span class="skill">
                      <i class="bi bi-github"></i>
                      <a href="<?= htmlspecialchars($row['github_links']) ?>" target="_blank" rel="noopener noreferrer">GitHub</a>
                    </span>
                  <?php endif; ?>
                  <?php if (!empty($row['x_links'])): ?>
                    <span class="skill">
                      <i class="bi bi-twitter-x"></i>
                      <a href="<?= htmlspecialchars($row['x_links']) ?>" target="_blank" rel="noopener noreferrer">X</a>
                    </span>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        <?php else: ?>
          <div class="no-content">
            <h3>Developer Profiles Coming Soon</h3>
            <p>We're working on showcasing our amazing development team. Check back soon!</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </div>
    <?php endif; ?>
    </main>

    <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
    <script>
    // About Slider Functionality
    function slideAbout(direction) {
        const slider = document.getElementById('aboutSlider');
        const entries = document.querySelectorAll('.about-entry');
        const totalSlides = entries.length;
        const currentSlide = parseInt(slider.getAttribute('data-current') || 0);
        
        let newSlide = currentSlide + direction;
        if (newSlide < 0) newSlide = totalSlides - 1;
        if (newSlide >= totalSlides) newSlide = 0;
        
        const slideWidth = entries[0].offsetWidth + 32; // width + gap
        slider.style.transform = `translateX(-${newSlide * slideWidth}px)`;
        slider.setAttribute('data-current', newSlide);
        
        // Update indicators
        const indicators = document.querySelectorAll('.indicator');
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === newSlide);
        });
        
        // Update button states
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        if (prevBtn) prevBtn.disabled = newSlide === 0;
        if (nextBtn) nextBtn.disabled = newSlide === totalSlides - 1;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Navigation elements
        const navbar = document.querySelector('.navbar');
        const burgerMenu = document.getElementById('burgerMenu');
        const mobileMenu = document.getElementById('mobileMenu');

        // Enhanced scroll animation with smoother performance
        let lastScrollY = 0;
        let ticking = false;

        function updateNavbar() {
            const scrollY = window.scrollY;
            
            // Use requestAnimationFrame for smoother scrolling
            if (scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScrollY = scrollY;
            ticking = false;
        }

        // Optimized scroll listener with throttling
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        }, { passive: true });

        // Burger menu toggle with enhanced modal behavior
        if (burgerMenu) {
            burgerMenu.addEventListener('click', (e) => {
                e.stopPropagation();
                burgerMenu.classList.toggle('active');
                mobileMenu.classList.toggle('active');
                // No need to prevent body scroll for modal design
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
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (mobileMenu.classList.contains('active') && 
                !mobileMenu.contains(e.target) && 
                !burgerMenu.contains(e.target)) {
                if (burgerMenu) {
                    burgerMenu.classList.remove('active');
                }
                mobileMenu.classList.remove('active');
            }
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                if (burgerMenu) {
                    burgerMenu.classList.remove('active');
                }
                mobileMenu.classList.remove('active');
            }
        });

        // Close mobile menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                if (burgerMenu) {
                    burgerMenu.classList.remove('active');
                }
                mobileMenu.classList.remove('active');
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
        if (!container) return;
        
        const windowWidth = window.innerWidth;
        let activeRains = 0;
        const maxActiveRains = 80;

        function createBinary() {
            if (activeRains >= maxActiveRains) return;
            activeRains++;
            
            const binary = document.createElement('div');
            
            // Random size distribution (large=background, small=foreground)
            const sizeRandom = Math.random();
            let sizeClass, duration;
            
            if (sizeRandom < 0.2) {
                // 20% large (background)
                sizeClass = 'large';
                duration = Math.random() * 4 + 8; // Slower fall
            } else if (sizeRandom < 0.5) {
                // 30% medium
                sizeClass = 'medium';
                duration = Math.random() * 3 + 6;
            } else {
                // 50% small (foreground)
                sizeClass = 'small';
                duration = Math.random() * 3 + 4; // Faster fall
            }
            
            binary.className = 'binary ' + sizeClass;
            binary.style.left = Math.random() * windowWidth + 'px';
            binary.style.top = '-100px';
            binary.style.position = 'absolute';
            binary.style.animation = 'binaryFall linear ' + duration + 's';
            binary.style.color = '#3EF4E2';
            binary.style.fontFamily = 'Courier New, monospace';
            binary.style.fontWeight = 'bold';
            binary.textContent = Math.random() < 0.5 ? '0' : '1';
            
            container.appendChild(binary);

            // Remove after animation completes
            binary.addEventListener('animationend', () => {
                if (binary.parentNode) {
                    binary.remove();
                    activeRains--;
                }
            });
            
            // Fallback timeout removal
            setTimeout(() => {
                if (binary.parentNode) {
                    binary.remove();
                    activeRains--;
                }
            }, duration * 1000 + 1000);
        }

        // Preload initial binaries scattered throughout the screen
        function preloadBinaries() {
            for (let i = 0; i < 40; i++) {
                const binary = document.createElement('div');
                
                // Random size distribution
                const sizeRandom = Math.random();
                let sizeClass, duration;
                
                if (sizeRandom < 0.2) {
                    sizeClass = 'large';
                    duration = Math.random() * 4 + 8;
                } else if (sizeRandom < 0.5) {
                    sizeClass = 'medium';
                    duration = Math.random() * 3 + 6;
                } else {
                    sizeClass = 'small';
                    duration = Math.random() * 3 + 4;
                }
                
                binary.className = 'binary ' + sizeClass;
                binary.style.left = Math.random() * windowWidth + 'px';
                // Position randomly throughout the screen height instead of all at top
                binary.style.top = Math.random() * window.innerHeight + 'px';
                binary.style.position = 'absolute';
                binary.style.animation = 'binaryFall linear ' + duration + 's';
                binary.style.color = '#3EF4E2';
                binary.style.fontFamily = 'Courier New, monospace';
                binary.style.fontWeight = 'bold';
                binary.textContent = Math.random() < 0.5 ? '0' : '1';
                
                container.appendChild(binary);
                activeRains++;

                // Remove after animation completes
                binary.addEventListener('animationend', () => {
                    if (binary.parentNode) {
                        binary.remove();
                        activeRains--;
                    }
                });
                
                // Fallback timeout removal
                setTimeout(() => {
                    if (binary.parentNode) {
                        binary.remove();
                        activeRains--;
                    }
                }, duration * 1000 + 1000);
            }
        }
        
        // Create preloaded binaries immediately
        preloadBinaries();

        // Continue creating binary digits at intervals
        setInterval(createBinary, 150);
    }

    // Start when page loads
    window.addEventListener('load', createBinaryRain);

    // Blog Carousel Functionality
    function initBlogCarousel() {
        const carousel = document.getElementById('blogCarousel');
        const cards = document.querySelectorAll('.blog-post-card');
        const prevBtn = document.getElementById('blogPrev');
        const nextBtn = document.getElementById('blogNext');
        const indicators = document.querySelectorAll('.blog-indicator');
        
        if (!carousel || cards.length === 0) return;

        let currentIndex = 0;
        let autoTransitionInterval;
        const autoTransitionDelay = 5000; // 5 seconds

        function showCard(index) {
            // Remove active class from all cards and indicators
            cards.forEach((card, i) => {
                card.classList.remove('active', 'prev');
                if (i < index) {
                    card.classList.add('prev');
                } else if (i === index) {
                    card.classList.add('active');
                }
            });

            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('active', i === index);
            });

            currentIndex = index;
        }

        function nextCard() {
            const nextIndex = (currentIndex + 1) % cards.length;
            showCard(nextIndex);
        }

        function prevCard() {
            const prevIndex = (currentIndex - 1 + cards.length) % cards.length;
            showCard(prevIndex);
        }

        function startAutoTransition() {
            if (cards.length <= 1) return;
            autoTransitionInterval = setInterval(nextCard, autoTransitionDelay);
        }

        function stopAutoTransition() {
            if (autoTransitionInterval) {
                clearInterval(autoTransitionInterval);
                autoTransitionInterval = null;
            }
        }

        function restartAutoTransition() {
            stopAutoTransition();
            startAutoTransition();
        }

        // Event listeners
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevCard();
                restartAutoTransition();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextCard();
                restartAutoTransition();
            });
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showCard(index);
                restartAutoTransition();
            });
        });

        // Pause auto-transition on hover
        carousel.addEventListener('mouseenter', stopAutoTransition);
        carousel.addEventListener('mouseleave', startAutoTransition);

        // Handle touch events for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
            stopAutoTransition();
        });

        carousel.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].clientX;
            const touchDiff = touchStartX - touchEndX;
            
            if (Math.abs(touchDiff) > 50) { // Minimum swipe distance
                if (touchDiff > 0) {
                    nextCard(); // Swipe left - next card
                } else {
                    prevCard(); // Swipe right - previous card
                }
            }
            
            restartAutoTransition();
        });

        // Handle visibility change to pause/resume auto-transition
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoTransition();
            } else {
                startAutoTransition();
            }
        });

        // Initialize
        showCard(0);
        startAutoTransition();
    }

    // Initialize blog carousel when DOM is loaded
    document.addEventListener('DOMContentLoaded', initBlogCarousel);
    </script>
</body>
</html> 
