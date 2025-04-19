<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'gaming_friendship';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, profile_photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $profile_photo);
$stmt->fetch();
$stmt->close();

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Platform - Gaming Friendship Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            font-family: 'Orbitron', sans-serif;
            color: #fff;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        header {
            background-color: #000;
            padding: 15px 25px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            animation: slideDown 1s ease-in-out;
            transition: transform 0.5s ease, box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px rgba(0, 204, 255, 0.9);
        }
        header.hidden {
            transform: translateY(-100%);
        }
        header h1 {
            color: #00ccff;
            text-shadow: 0 0 12px #00ccff;
            margin: 0;
            font-size: 2.8em;
        }
        .profile-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-img {
            max-width: 40px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(0, 204, 255, 0.6);
        }
        .profile-section span {
            font-size: 1.1em;
            color: #00ccff;
            text-shadow: 0 0 5px #00ccff;
            font-family: 'Roboto', sans-serif;
        }
        .profile-section button {
            padding: 8px 12px;
            background-color: #ff4500;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 0.9em;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-family: 'Orbitron', sans-serif;
        }
        .profile-section button:hover {
            background-color: #e03e00;
        }
        nav {
            background: linear-gradient(90deg, #1c2526, #2a3b3f);
            padding: 20px;
            display: flex;
            justify-content: center;
            box-shadow: 0 0 25px rgba(0, 204, 255, 0.6);
            position: fixed;
            top: 70px;
            width: 100%;
            z-index: 999;
        }
        nav a {
            color: #00ccff;
            margin: 0 25px;
            text-decoration: none;
            font-size: 1.3em;
            text-shadow: 0 0 12px #00ccff, 0 0 24px #00ccff;
            transition: all 0.3s ease;
            font-family: 'Orbitron', sans-serif;
        }
        nav a:hover {
            color: #fff;
            text-shadow: 0 0 18px #00ccff, 0 0 36px #00ccff;
            transform: scale(1.15);
        }
        .container {
            max-width: 1000px;
            margin: 130px auto 30px;
            padding: 25px;
            background-color: rgba(42, 42, 42, 0.9);
            border-radius: 12px;
            position: relative;
            z-index: 1;
            flex: 1 0 auto;
            text-align: center;
        }
        h2 {
            font-size: 2em;
            color: #00ccff;
            text-shadow: 0 0 10px #00ccff;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            margin: 10px 0;
            font-family: 'Roboto', sans-serif;
            color: #b3e6ff;
        }
        .game-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .game-card {
            background: linear-gradient(45deg, #1a1a1a, #2a2a2a);
            padding: 20px;
            border-radius: 12px;
            width: 300px;
            text-align: center;
            border: 2px solid #00ccff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .game-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 204, 255, 0.7);
        }
        .game-card img {
            max-width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .game-card h3 {
            font-size: 1.6em;
            color: #00ccff;
            text-shadow: 0 0 5px #00ccff;
            margin: 10px 0;
            line-height: 1.3;
            font-family: 'Orbitron', sans-serif;
        }
        .game-card p {
            font-size: 1.2em;
            color: #b3e6ff;
            line-height: 1.6;
            margin: 10px 0;
            font-family: 'Roboto', sans-serif;
        }
        .game-card a {
            display: inline-block;
            padding: 12px 20px;
            background-color: #ff4500;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
            font-family: 'Orbitron', sans-serif;
        }
        .game-card a:hover {
            background-color: #e03e00;
        }
        footer {
            background: #1a1a1a;
            padding: 50px 20px;
            text-align: center;
            border-top: 3px solid #00ccff;
            box-shadow: 0 0 20px rgba(0, 204, 255, 0.5);
            flex-shrink: 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        footer:hover {
            animation: bounce 0.5s ease;
            box-shadow: 0 0 30px rgba(0, 204, 255, 0.7);
        }
        .footer-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: nowrap;
        }
        .footer-box {
            font-family: 'Roboto', Arial, sans-serif;
            padding: 30px;
            border-radius: 12px;
            width: 320px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .footer-box:hover {
            transform: scale(1.08);
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.6);
        }
        .footer-box h3 {
            margin: 0 0 15px;
            font-size: 1.6em;
            font-weight: 500;
            line-height: 1.3;
        }
        .footer-box p {
            font-size: 1.2em;
            font-weight: 400;
            line-height: 1.6;
            margin: 0 0 15px;
        }
        .footer-box.kunal {
            background: linear-gradient(45deg, #00ccff, #1a1a1a);
            border: 2px solid #00ccff;
            box-shadow: 0 0 10px rgba(0, 204, 255, 0.5);
        }
        .footer-box.kunal h3 {
            color: #00ccff;
            text-shadow: 0 0 5px #00ccff;
        }
        .footer-box.kunal p {
            color: #b3e6ff;
        }
        .footer-box.sachin {
            background: linear-gradient(45deg, #cc00ff, #1a1a1a);
            border: 2px solid #cc00ff;
            box-shadow: 0 0 10px rgba(204, 0, 255, 0.5);
        }
        .footer-box.sachin h3 {
            color: #cc00ff;
            text-shadow: 0 0 5px #cc00ff;
        }
        .footer-box.sachin p {
            color: #e6b3ff;
        }
        .footer-box.amit {
            background: linear-gradient(45deg, #00ff99, #1a1a1a);
            border: 2px solid #00ff99;
            box-shadow: 0 0 10px rgba(0, 255, 153, 0.5);
        }
        .footer-box.amit h3 {
            color: #00ff99;
            text-shadow: 0 0 5px #00ff99;
        }
        .footer-box.amit p {
            color: #b3ffd9;
        }
        .social-links {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 15px;
        }
        .social-links a {
            color: inherit;
            font-size: 2em;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .footer-box.kunal .social-links a {
            color: #00ccff;
            text-shadow: 0 0 5px #00ccff;
        }
        .footer-box.kunal .social-links a:hover {
            color: #fff;
            text-shadow: 0 0 10px #00ccff;
            transform: scale(1.2);
        }
        .footer-box.sachin .social-links a {
            color: #cc00ff;
            text-shadow: 0 0 5px #cc00ff;
        }
        .footer-box.sachin .social-links a:hover {
            color: #fff;
            text-shadow: 0 0 10px #cc00ff;
            transform: scale(1.2);
        }
        .footer-box.amit .social-links a {
            color: #00ff99;
            text-shadow: 0 0 5px #00ff99;
        }
        .footer-box.amit .social-links a:hover {
            color: #fff;
            text-shadow: 0 0 10px #00ff99;
            transform: scale(1.2);
        }
        .ad-banner {
            margin-top: 30px;
            background: linear-gradient(45deg, #ff4500, #cc00ff);
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #fff;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .ad-banner:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(255, 255, 255, 0.7);
        }
        .ad-banner p {
            color: #fff;
            text-shadow: 0 0 5px #fff;
            margin: 0;
            font-size: 1.5em;
            font-weight: 400;
            line-height: 1.4;
        }
        .copyright {
            margin-top: 20px;
            font-size: 1.2em;
            color: #fff;
        }
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-12px);
            }
        }
    </style>
</head>
<body>
    <header id="header">
        <h1>Gaming Friendship Hub</h1>
        <div class="profile-section">
            <img src="<?php echo htmlspecialchars($profile_photo ?: 'Uploads/default_profile.jpg'); ?>" alt="Profile Photo" class="profile-img">
            <span><?php echo htmlspecialchars($username); ?></span>
            <form action="gaming_platform.php" method="post" style="display: inline;">
                <button type="submit" name="logout">Logout</button>
            </form>
        </div>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <a href="profile.php">Profile</a>
        <a href="gaming_platform.php">Gaming Platform</a>
    </nav>
    <div class="container">
        <h2>Gaming Platform</h2>
        <p>Play your favorite games online with friends!</p>
        <div class="game-list">
            <div class="game-card">
                <img src="https://images.unsplash.com/photo-1628191012027-6bacf847cba2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="BGMI">
                <h3>Battlegrounds Mobile India</h3>
                <p>Join 100 players in an epic battle royale for ultimate survival glory.</p>
                <a href="https://now.gg/play/krafton-inc/1234/bgmi" target="_blank">Play Now</a>
            </div>
            <div class="game-card">
                <img src="https://images.unsplash.com/photo-1593305841991-2b5672496d92?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Free Fire">
                <h3>Garena Free Fire</h3>
                <p>Survive intense 50-player battles in this fast-paced battle royale.</p>
                <a href="https://now.gg/play/garena/5678/free-fire" target="_blank">Play Now</a>
            </div>
            <div class="game-card">
                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="COD Mobile">
                <h3>Call of Duty: Mobile</h3>
                <p>Experience thrilling FPS action with multiplayer and battle royale modes.</p>
                <a href="https://now.gg/play/activision/9012/call-of-duty-mobile" target="_blank">Play Now</a>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-box kunal">
                <h3>Kunal Kumar</h3>
                <p>A passionate gamer and B.Tech CSE student skilled in Java, Python, JavaScript, HTML, CSS, and Database.</p>
                <div class="social-links">
                    <a href="https://instagram.com/_kunal_sharma_n1" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://facebook.com/kunal kumar" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/kunalkumar" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://linkedin.com/in/kunalkumar" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-box sachin">
                <h3>Sachin Kumar</h3>
                <p>A skilled hacker and gamer, best friend of Kunal Kumar.</p>
                <div class="social-links">
                    <a href="https://instagram.com/its_romeosachin_official" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://facebook.com/Romeo sachin" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/sachinkumar" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-box amit">
                <h3>Amit Kumar</h3>
                <p>A dedicated gamer, close friend of Kunal and Sachin.</p>
                <div class="social-links">
                    <a href="https://instagram.com/amitkumar123769" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://facebook.com/amit kumarS" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/amitkumar" target="_blank"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="ad-banner">
            <p>Join the Ultimate Gaming Tournament! Register Now for Epic Rewards!</p>
        </div>
        <div class="copyright">
            <p>© 2025 Gaming Friendship Hub. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Background image slideshow
        const images = [
            'https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
            'https://images.unsplash.com/photo-1511886922116-93bc567b4d91?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
            'https://images.unsplash.com/photo-1538481199705-c710c4e965fc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80'
        ];
        let currentImageIndex = 0;

        function changeBackground() {
            document.body.style.backgroundImage = `url(${images[currentImageIndex]})`;
            document.body.style.opacity = 0;
            setTimeout(() => {
                document.body.style.transition = 'opacity 1s ease';
                document.body.style.opacity = 1;
            }, 50);
            currentImageIndex = (currentImageIndex + 1) % images.length;
        }

        changeBackground();
        setInterval(changeBackground, 5000);

        // Header slide effect on scroll
        let lastScrollTop = 0;
        const header = document.getElementById('header');
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                header.classList.add('hidden');
            } else {
                header.classList.remove('hidden');
            }
            lastScrollTop = scrollTop;
        });
    </script>
</body>
</html>