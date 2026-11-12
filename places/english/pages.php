<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'about';
$is_fa = ($current_lang === 'fa');
?>

<?php if ($page === 'about'): ?>
<div class="page-content active" id="pageAbout">
    <div class="container-page">

        <div class="page-header text-center mb-5">
            <h1><i class="fas fa-chess-queen" style="color: #00796B;"></i> About Us</h1>
            <p>Getting to know the Iranian Celebrities website</p>
            <div class="header-line"></div>
        </div>

        <div class="about-card mb-4">
            <h3><i class="fas fa-chess-queen"></i> What is Iranian Celebrities?</h3>
            <p>
                <strong>Iranian Celebrities</strong> is a specialized and comprehensive website for introducing the greats, scientists, poets, mystics,
                artists, and influential figures of Iranian history.
            </p>
            <p>
                On this site, you can get acquainted with the lives, works, thoughts, and influences of great figures such as
                <strong>Hafez, Saadi, Rumi, Ferdowsi, Avicenna, Khayyam, Amir Kabir, Cyrus the Great, Darius the Great</strong>.
            </p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="about-card h-100">
                    <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                    <p>Preserving the name and memory of Iran's greats and transmitting their valuable heritage to future generations.</p>
                    <ul>
                        <li>Comprehensive and accurate introduction of Iranian celebrities</li>
                        <li>Preservation and dissemination of Iranian culture and history</li>
                        <li>Providing reliable and scientific content</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="about-card h-100">
                    <h3><i class="fas fa-eye"></i> Our Vision</h3>
                    <p>Becoming the largest digital reference of Iranian celebrities in the country and the world.</p>
                    <ul>
                        <li>Development of multimedia content</li>
                        <li>Creation of a complete database of celebrities</li>
                        <li>Providing educational and research content</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="about-card mb-4">
            <h3><i class="fas fa-gem"></i> Our Values</h3>

            <div class="row g-3 mt-2">

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-scroll"></i>
                        <span>Historical Perspective</span>
                        <small>Preserving the past and learning from it</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-feather-alt"></i>
                        <span>Cultivation of Literature</span>
                        <small>Expanding Persian culture and literature</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-lightbulb"></i>
                        <span>Knowledge Enhancement</span>
                        <small>Enhancing public awareness and knowledge</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-handshake"></i>
                        <span>National Solidarity</span>
                        <small>Unity and cohesion of Iranians</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-balance-scale"></i>
                        <span>Justice Orientation</span>
                        <small>Impartiality in historical representation</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-heart"></i>
                        <span>Love for Iran</span>
                        <small>Love for Iran and pride in its past</small>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?php endif; ?>


<?php if ($page === 'contact'): ?>
<div class="page-content active" id="pageContact">

    <div class="container-page">

        <div class="page-header text-center mb-5">
            <h1>
                <i class="fas fa-handshake" style="color: #00796B;"></i>
                Contact Us
            </h1>

            <p>We are eager to hear your comments, suggestions, and criticisms</p>

            <div class="header-line"></div>
        </div>


        <div class="row g-4 mb-5">

            <div class="col-md-4">
                <div class="contact-card">

                    <div class="icon-circle">
                        <i class="fas fa-envelope"></i>
                    </div>

                    <h5>Email</h5>

                    <p style="direction: ltr;">
                        info@mashahir.ir
                    </p>

                    <p style="direction: ltr; font-size: 0.85rem;">
                        support@mashahir.ir
                    </p>

                </div>
            </div>


            <div class="col-md-4">
                <div class="contact-card">

                    <div class="icon-circle instagram-bg">
                        <i class="fab fa-instagram"></i>
                    </div>

                    <h5>Instagram</h5>

                    <p>@mashahir_ir</p>

                    <p style="font-size: 0.85rem;">
                        Follow and send messages
                    </p>

                </div>
            </div>


            <div class="col-md-4">
                <div class="contact-card">

                    <div class="icon-circle telegram-bg">
                        <i class="fab fa-telegram-plane"></i>
                    </div>

                    <h5>Telegram</h5>

                    <p>@mashahir_ir</p>

                    <p style="font-size: 0.85rem;">
                        Send messages on Telegram
                    </p>

                </div>
            </div>

        </div>


        <div class="row mb-5">

            <div class="col-12">

                <div class="social-section">

                    <h5>
                        <i class="fas fa-share-alt"></i>
                        Follow us on social networks
                    </h5>

                    <div class="social-buttons">

                        <a href="#" class="social-btn telegram-btn">
                            <i class="fab fa-telegram-plane"></i>
                            Telegram
                        </a>

                        <a href="#" class="social-btn instagram-btn">
                            <i class="fab fa-instagram"></i>
                            Instagram
                        </a>

                        <a href="#" class="social-btn whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            WhatsApp
                        </a>

                        <a href="#" class="social-btn youtube-btn">
                            <i class="fab fa-youtube"></i>
                            YouTube
                        </a>

                        <a href="#" class="social-btn aparat-btn">
                            <i class="fas fa-video"></i>
                            Aparat
                        </a>

                        <a href="#" class="social-btn twitter-btn">
                            <i class="fab fa-twitter"></i>
                            Twitter
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-lg-7">

                <div class="form-section">

                    <div class="form-title">
                        <i class="fas fa-pen"></i>
                        Send Message
                    </div>

                    <p class="form-desc">
                        Your comments, suggestions, and criticisms help improve our content.
                    </p>


                    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>

                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            Your message has been sent successfully.
                        </div>

                    <?php endif; ?>


                    <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>

                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            Message sending failed. Please try again.
                        </div>

                    <?php endif; ?>


                    <form id="contactForm" action="../../mashahir/sendmessage.php" method="POST">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="contactName"
                                    name="name"
                                    placeholder="Enter your name..."
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="contactEmail"
                                    name="email"
                                    placeholder="Enter your email..."
                                    required
                                >

                            </div>

                        </div>


                        <div class="mb-3 mt-3">

                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Subject
                            </label>

                            <select
                                class="form-control"
                                id="contactSubject"
                                name="subject"
                                required
                            >

                                <option value="">
                                    Select a subject...
                                </option>

                                <option value="Suggestion for new celebrities">
                                    Suggestion for new celebrities
                                </option>

                                <option value="Criticism and suggestions">
                                    Criticism and suggestions
                                </option>

                                <option value="Collaboration in content production">
                                    Collaboration in content production
                                </option>

                                <option value="Question about celebrities">
                                    Question about celebrities
                                </option>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                <i class="fas fa-comment"></i>
                                Your Message
                            </label>

                            <textarea
                                class="form-control"
                                id="contactMessage"
                                name="message"
                                rows="5"
                                placeholder="Write your message text..."
                                required
                            ></textarea>

                        </div>


                        <button
                            type="submit"
                            class="btn-submit"
                        >
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>

                    </form>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="form-section h-100">

                    <h5 class="info-title">
                        <i class="fas fa-info-circle"></i>
                        Contact Information
                    </h5>


                    <div class="info-item">

                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>

                        <div>

                            <p class="info-label">
                                Response Time
                            </p>

                            <p class="info-value">
                                Working days 9 AM to 6 PM
                            </p>

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-icon">
                            <i class="fas fa-reply-all"></i>
                        </div>

                        <div>

                            <p class="info-label">
                                Response Timeframe
                            </p>

                            <p class="info-value">
                                Maximum 24 hours
                            </p>

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>

                            <p class="info-label">
                                Our Team
                            </p>

                            <p class="info-value">
                                Persian history and literature experts
                            </p>

                        </div>

                    </div>


                    <hr class="info-divider">


                    <div class="info-quote">

                        <i class="fas fa-quote-right"></i>

                        We respond to your messages with care and respect.

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<?php endif; ?>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>