<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bKash Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-500 flex justify-center items-center min-h-screen">
    <div class="bg-white p-6 rounded-xl shadow-lg w-96">
        <!-- bKash Logo -->
        {{-- <div class="flex justify-center mb-4">
            <img src="https://upload.wikimedia.org/wikipedia/en/thumb/c/c7/BKash_Logo.svg/1200px-BKash_Logo.svg.png" 
                alt="bKash" class="w-20">
        </div> --}}

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">bKash Payment</h2>

        <form action="{{ route('url-create') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Amount Input -->
            <div>
                <label class="block text-gray-600 text-sm font-semibold mb-2">Enter Amount</label>
                <input type="text" name="amount" placeholder="Amount" required 
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-400 focus:outline-none bg-white">
            </div>

            <!-- Pay Button -->
            <button type="submit" class="w-full bg-pink-500 text-white py-3 rounded-md font-semibold 
                hover:bg-pink-600 transition-all duration-300 shadow-md">
                Pay with bKash
            </button>
        </form>
    </div>
</body>
</html>
