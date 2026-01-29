<aside class="w-64 bg-gray-900 text-white p-6 flex flex-col">
    <div>
        <h2 class="text-xl font-bold mb-6">MiniCMS Pro</h2>
        <nav class="space-y-3">
            <a href="/admin" class="block text-gray-300 hover:text-white">
                Dashboard
            </a>
            <a href="/admin/posts" class="block text-gray-300 hover:text-white">
                Posts
            </a>
            <a href="/admin/users" class="block text-gray-300 hover:text-white">
                Users
            </a>
        </nav>
    </div>
    <form method="post" action="/admin/logout" class="mt-auto">
        <button type="submit" class="underline text-gray-300 hover:text-white">
            Logout
        </button>
    </form>
</aside>
