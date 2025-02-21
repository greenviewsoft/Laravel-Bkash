<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <!-- Success Icon -->
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-green-500 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-green-600 mb-4">Payment Successful</h2>
        <p class="text-gray-600 mb-4">Your transaction was completed successfully.</p>

        @if(isset($response))
            <div class="bg-green-200 p-3 rounded-md text-green-800 font-semibold">
                bKash Trx ID: <strong>{{ $response }}</strong>
            </div>
        @endif

        <a href="/" class="block mt-5 bg-green-500 text-white py-2 rounded-md font-semibold hover:bg-green-600 transition-all duration-300 shadow-md">
            Return Home
        </a>
    </div>
</body>
</html>
