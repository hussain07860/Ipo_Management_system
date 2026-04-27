
<!DOCTYPE html>
<html>
<head>
    <title>IPO Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            width: 400px;
            padding: 40px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            color: white;
            opacity: 0;
            transform: translateY(40px);
            animation: fadeUp 1s ease forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-custom {
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .tagline {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <h3>📈 IPO Management System</h3>
        <div class="tagline">
            Smart Investing Starts Here
        </div>

        <a href="login.php" class="btn btn-primary btn-custom mb-3 w-100">
            Login as User
        </a>

        <a href="register.php" class="btn btn-success btn-custom mb-3 w-100">
            Register New User
        </a>

        <a href="admin_login.php" class="btn btn-dark btn-custom w-100">
            Login as Admin
        </a>

    </div>

</body>
</html>
