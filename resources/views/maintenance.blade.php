<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .maintenance-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 500px;
        }
        .maintenance-icon {
            font-size: 5rem;
            color: #667eea;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .progress-bar {
            animation: progress 2s ease-in-out infinite;
        }
        @keyframes progress {
            0% { width: 0%; }
            50% { width: 100%; }
            100% { width: 0%; }
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <i class="bi bi-gear maintenance-icon mb-4"></i>
        <h1 class="mb-3">Under Maintenance</h1>
        <p class="text-muted mb-4">
            We're currently performing scheduled maintenance to improve your experience. 
            Please check back soon!
        </p>
        
        <div class="progress mb-4" style="height: 6px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
        </div>
        
        <p class="text-muted small">
            <i class="bi bi-clock me-1"></i>
            We'll be back shortly. Thank you for your patience.
        </p>
        
        <hr class="my-4">
        
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-person me-1"></i>Admin Login
        </a>
    </div>
</body>
</html>
