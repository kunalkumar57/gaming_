<?php
// Start session
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Friendship Hub</title>
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
            padding: 25px;
            text-align: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            animation: slideDown 1s ease-in-out;
            transition: transform 0.5s ease, box-shadow 0.3s ease;
        }
        header:hover {
            transform: translateY(-5px) scale(1.05);
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
        nav {
            background: linear-gradient(90deg, #1c2526, #2a3b3f);
            padding: 20px;
            display: flex;
            justify-content: center;
            box-shadow: 0 0 25px rgba(0, 204, 255, 0.6);
            margin-top: 90px;
        }
        nav a {
            color: #00ccff;
            margin: 0 25px;
            text-decoration: none;
            font-size: 1.3em;
            text-shadow: 0 0 12px #00ccff, 0 0 24px #00ccff;
            transition: all 0.3s ease;
        }
        nav a:hover {
            color: #fff;
            text-shadow: 0 0 18px #00ccff, 0 0 36px #00ccff;
            transform: scale(1.15);
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 25px;
            background-color: rgba(42, 42, 42, 0.9);
            border-radius: 12px;
            position: relative;
            z-index: 1;
            flex: 1 0 auto;
        }
        input, button {
            padding: 12px;
            margin: 12px 0;
            width: 100%;
            box-sizing: border-box;
        }
        button {
            background-color: #ff4500;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #e03e00;
        }
        .profile-img {
            max-width: 150px;
            border-radius: 50%;
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
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <a href="profile.php">Profile</a>
        <a href="gaming_platform.php">Gaming Platform</a>
    </nav>
    <div class="container">
        <h2>Welcome to the Gaming Community!</h2>
        <p>Connect with gamers, upload your gaming profile photo, and make new friends!</p>
    </div>
    <footer>
        <div class="footer-container">
            <div class="footer-box kunal">
                <h3>Kunal Kumar</h3>
                <p>A passionate gamer who dominates in BGMI and masters cyber attacks.</p>
                <div class="social-links">
                    <a href="https://instagram.com/_kunal_sharma_n1" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="https://facebook.com/kunal kumar" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/kunalkumar" target="_blank"><i class="fab fa-twitter"></i></a>
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

        // Basic form validation
        function validateForm() {
            let username = document.getElementById('username')?.value;
            let password = document.getElementById('password')?.value;
            if (username && username.length < 3) {
                alert("Username must be at least 3 characters long.");
                return false;
            }
            if (password && password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }
            return true;
        }

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