<?php
// Get the current page name from the filename
$current_file = basename($_SERVER['PHP_SELF']);
$current_page = str_replace('.php', '', $current_file);
?>

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