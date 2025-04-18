<?php

if(!isset($_SESSION)){
    session_start();
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick and Morty Game - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #97ce4c;      /* Verde Rick and Morty */
            --secondary: #44281d;    /* Marrone Rick and Morty */
            --accent: #f0e14a;       /* Giallo Rick and Morty */
            --background: #2b2b2b;   /* Grigio scuro */
            --text: #ffffff;         /* Bianco */
            --error: #ff3c7b;        /* Rosa/Rosso per errori */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--background);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        .portal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/assets/img/placeholder-4by3.svg') no-repeat center center;
            background-size: cover;
            opacity: 0.15;
            z-index: -1;
        }
        
        .container {
            width: 100%;
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(151, 206, 76, 0.5);
            backdrop-filter: blur(10px);
            border: 2px solid var(--primary);
            animation: glow 3s infinite alternate;
        }
        
        @keyframes glow {
            from {
                box-shadow: 0 0 15px rgba(151, 206, 76, 0.5);
            }
            to {
                box-shadow: 0 0 30px rgba(151, 206, 76, 0.8);
            }
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            max-width: 200px;
            height: auto;
        }
        
        h1 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.2rem;
            text-shadow: 0 0 10px rgba(151, 206, 76, 0.8);
        }
        
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: var(--error);
            color: white;
            text-align: center;
            font-weight: bold;
            display: none;
        }
        
        .message.show {
            display: block;
            animation: fadeIn 0.5s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-group {
            margin-bottom: 15px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--accent);
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid var(--primary);
            border-radius: 8px;
            background-color: rgba(0, 0, 0, 0.5);
            color: var(--text);
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 8px rgba(240, 225, 74, 0.6);
        }
        
        .form-group .icon {
            position: absolute;
            right: 15px;
            bottom: 15px;
            color: var(--primary);
        }
        
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: var(--primary);
            color: var(--secondary);
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        button:hover {
            background-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        }
        
        .switch-form {
            text-align: center;
            margin-top: 20px;
            color: var(--text);
        }
        
        .switch-form a {
            color: var(--accent);
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .switch-form a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        .tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .tab {
            padding: 10px 20px;
            margin: 0 5px;
            background-color: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .tab.active {
            background-color: var(--primary);
            color: var(--secondary);
        }
        
        .tab:hover:not(.active) {
            background-color: rgba(151, 206, 76, 0.2);
        }
        
        .form-container {
            position: relative;
        }
        
        .form-container form {
            transition: all 0.5s;
        }
        
        .portal-animation {
            position: fixed;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, var(--accent) 0%, var(--primary) 70%, transparent 100%);
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="portal-bg"></div>
    
    <div class="container">
        <div class="logo">
            <!-- Placeholder for logo -->
            <h1>Rick and Morty Game</h1>
        </div>
        
        <div class="tabs">
            <div class="tab active" id="login-tab">Login</div>
            <div class="tab" id="register-tab">Registrazione</div>
        </div>
        
        <div class="message" id="message-display"></div>
        
        <div class="form-container">
            <!-- Login Form -->
            <form action="gestioneLogin.php" method="POST" id="login-form">
                <div class="form-group">
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" required>
                    <span class="icon"><i class="fas fa-user"></i></span>
                </div>
                
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                    <span class="icon"><i class="fas fa-lock"></i></span>
                </div>
                
                <button type="submit">Accedi <i class="fas fa-sign-in-alt"></i></button>
            </form>
            
            <!-- Registration Form (initially hidden) -->
            <form action="gestioneRegistrazione.php" method="POST" id="register-form" style="display: none;">
                <div class="form-group">
                    <label for="register-username">Username</label>
                    <input type="text" id="register-username" name="username" required>
                    <span class="icon"><i class="fas fa-user-plus"></i></span>
                </div>
                
                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" required>
                    <span class="icon"><i class="fas fa-lock"></i></span>
                </div>
                
                <button type="submit">Registrati <i class="fas fa-user-check"></i></button>
            </form>
        </div>
    </div>
    
    <div class="portal-animation" id="portal"></div>

    <script>
        // Check for message in URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('messaggio');
            
            if (message) {
                const messageDisplay = document.getElementById('message-display');
                messageDisplay.textContent = message;
                messageDisplay.classList.add('show');
                
                // Hide message after 5 seconds
                setTimeout(() => {
                    messageDisplay.classList.remove('show');
                }, 5000);
            }
            
            // Check if we need to show registration form based on URL
            if (window.location.pathname.includes('registrazione.php')) {
                switchTab('register-tab');
            }
        };
        
        // Tab switching
        document.getElementById('login-tab').addEventListener('click', function() {
            switchTab('login-tab');
        });
        
        document.getElementById('register-tab').addEventListener('click', function() {
            switchTab('register-tab');
        });
        
        function switchTab(tabId) {
            // Update tab styles
            document.getElementById('login-tab').classList.remove('active');
            document.getElementById('register-tab').classList.remove('active');
            document.getElementById(tabId).classList.add('active');
            
            // Show/hide forms
            if (tabId === 'login-tab') {
                document.getElementById('login-form').style.display = 'block';
                document.getElementById('register-form').style.display = 'none';
            } else {
                document.getElementById('login-form').style.display = 'none';
                document.getElementById('register-form').style.display = 'block';
            }
            
            // Animate portal effect
            animatePortal();
        }
        
        // Portal animation
        function animatePortal() {
            const portal = document.getElementById('portal');
            
            // Reset position and opacity
            portal.style.top = '50%';
            portal.style.left = '50%';
            portal.style.transform = 'translate(-50%, -50%) scale(0)';
            portal.style.opacity = '0.8';
            
            // Animate
            let scale = 0;
            const interval = setInterval(() => {
                scale += 0.1;
                portal.style.transform = `translate(-50%, -50%) scale(${scale})`;
                
                if (scale >= 2) {
                    clearInterval(interval);
                    portal.style.opacity = '0';
                }
            }, 30);
        }
        
        // Add portal animation on buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function() {
                const portal = document.getElementById('portal');
                
                // Position portal at button
                const rect = this.getBoundingClientRect();
                portal.style.top = `${rect.top + rect.height/2}px`;
                portal.style.left = `${rect.left + rect.width/2}px`;
                portal.style.transform = 'translate(-50%, -50%) scale(0)';
                portal.style.opacity = '0.8';
                
                // Animate
                let scale = 0;
                const interval = setInterval(() => {
                    scale += 0.1;
                    portal.style.transform = `translate(-50%, -50%) scale(${scale})`;
                    
                    if (scale >= 2) {
                        clearInterval(interval);
                        portal.style.opacity = '0';
                    }
                }, 30);
            });
        });
    </script>
</body>
</html>