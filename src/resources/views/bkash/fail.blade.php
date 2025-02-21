<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-center">
        <!-- Warning Icon -->
        <div class="flex justify-center mb-4">
            <svg class="w-16 h-16 text-red-500 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 5.07a9.004 9.004 0 0113.86 0M5.07 18.93a9.004 9.004 0 0113.86 0M12 3v1m0 16v1m7.071-13.071l-.707.707m-12.728 0l.707-.707M21 12h-1M4 12H3m16.071 7.071l-.707-.707m-12.728 0l.707.707"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-red-600 mb-4">Payment Failed</h2>
        <p class="text-gray-600 mb-4">Sorry, your transaction could not be processed.</p>

        @if(isset($response))
            <div class="bg-red-200 p-3 rounded-md text-red-800 font-semibold">{{ $response }}</div>
        @endif

        <a href="/" class="block mt-5 bg-red-500 text-white py-2 rounded-md font-semibold hover:bg-red-600 transition-all duration-300 shadow-md">
            Return Home
        </a>
    </div>
</body>
</html>
