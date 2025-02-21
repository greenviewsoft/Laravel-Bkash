<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Refund</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-500 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <!-- bKash Logo -->
        {{-- <div class="flex justify-center mb-4">
            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/c/c7/BKash_Logo.svg/1200px-BKash_Logo.svg.png" 
                alt="bKash" class="w-20">
        </div> --}}

        <h2 class="text-2xl font-semibold text-gray-700 mb-4 text-center">bKash Refund</h2>

        <form action="{{ route('url-post-refund') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Payment ID Input -->
            <div>
                <label class="block text-gray-600 text-sm font-semibold mb-2">Payment ID</label>
                <input type="text" name="paymentID" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-gray-400 focus:outline-none">
            </div>

            <!-- Transaction ID Input -->
            <div>
                <label class="block text-gray-600 text-sm font-semibold mb-2">Transaction ID</label>
                <input type="text" name="trxID" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-gray-400 focus:outline-none">
            </div>

            <!-- Amount Input -->
            <div>
                <label class="block text-gray-600 text-sm font-semibold mb-2">Amount</label>
                <input type="text" name="amount" required 
                    class="w-full p-3 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-gray-400 focus:outline-none">
            </div>

            <!-- Request Refund Button -->
            <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-md font-semibold 
                hover:bg-pink-600 transition-all duration-300 shadow-md">
                Request Refund
            </button>
        </form>
    </div>
</body>
</html>
