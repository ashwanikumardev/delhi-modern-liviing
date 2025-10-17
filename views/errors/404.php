<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Delhi Modern Living</title>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full text-center px-6">
        <!-- Error Icon -->
        <div class="mb-8">
            <div class="text-8xl text-primary-500 mb-4">
                <i class="fas fa-home"></i>
            </div>
            <div class="text-6xl font-bold text-gray-900 mb-2">404</div>
            <h1 class="text-2xl font-semibold text-gray-900 mb-4">Page Not Found</h1>
            <p class="text-gray-600 mb-8">
                Sorry, we couldn't find the page you're looking for. The page might have been moved, deleted, or you entered the wrong URL.
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="/demo-pg-01-main/" 
               class="w-full bg-primary-600 text-white hover:bg-primary-700 py-3 px-6 rounded-lg font-semibold transition duration-300 inline-block">
                <i class="fas fa-home mr-2"></i>
                Back to Home
            </a>
            
            <a href="/demo-pg-01-main/rooms" 
               class="w-full bg-gray-200 text-gray-700 hover:bg-gray-300 py-3 px-6 rounded-lg font-semibold transition duration-300 inline-block">
                <i class="fas fa-bed mr-2"></i>
                Browse Rooms
            </a>
            
            <button onclick="history.back()" 
                    class="w-full bg-white text-gray-700 hover:bg-gray-50 py-3 px-6 rounded-lg font-semibold border border-gray-300 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </button>
        </div>
        
        <!-- Help Text -->
        <div class="mt-8 text-sm text-gray-500">
            <p>Need help? Contact us at:</p>
            <div class="flex justify-center items-center space-x-4 mt-2">
                <a href="tel:+919876543210" class="text-primary-600 hover:text-primary-700">
                    <i class="fas fa-phone mr-1"></i>
                    +91-9876543210
                </a>
                <a href="mailto:info@delhimodernliving.com" class="text-primary-600 hover:text-primary-700">
                    <i class="fas fa-envelope mr-1"></i>
                    Email Us
                </a>
            </div>
        </div>
    </div>
    
    <!-- Background Pattern -->
    <div class="fixed inset-0 z-[-1] opacity-5">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-100 to-primary-200"></div>
    </div>
</body>
</html>
