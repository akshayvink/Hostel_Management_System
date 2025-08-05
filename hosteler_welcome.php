<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Happy Home Boys Hostel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #2B2B2B;
            color: #fff;
            margin: 0;
            padding: 0;
            text-align: center;
            overflow: hidden;
        }
        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            background: url('hostelpic.jpg') no-repeat center center/cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            animation: fadeIn 1.5s ease-in-out;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }
        .content {
            position: relative;
            z-index: 1;
            max-width: 800px;
        }
        h1 {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        p {
            font-size: 18px;
            font-weight: 300;
            max-width: 600px;
            margin: 0 auto 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 18px;
            font-weight: 600;
            color: #2B2B2B;
            background: white;
            border-radius: 25px;
            text-decoration: none;
            transition: 0.3s;
        }
        .btn:hover {
            background: #D4D4D4;
            color: #2B2B2B;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="overlay"></div>
        <div class="content">
            <h1>Welcome to Happy Home Boys Hostel</h1>
            <p>Experience a comfortable and secure hostel life with all the necessary amenities. Start your journey today!</p>
            <a href="homepagecheck.php" class="btn">Get Started</a>
        </div>
    </div>
</body>
</html>
