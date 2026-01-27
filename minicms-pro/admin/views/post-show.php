<?php
declare(strict_types=1);

use Admin\Core\Auth;
?>
<section class="p-6">
    <div class="bg-white p-6 rounded shadow">
        <div class="flex justify-between">
            <h2 class="text-2xl font-bold mb-4">
                <?php echo htmlspecialchars((string)$post['title'], ENT_QUOTES); ?>
            </h2>
            <div class="space-x-3">
                <a class="underline" href="/php/minicms-pro/admin/posts/<?php echo
                (int)$post['id']; ?>/edit">
                    Bewerken
                </a>
                <?php if (Auth::isAdmin()): ?>
                    <a class="underline text-red-600" href="/php/minicms-pro/admin/posts/<?php echo
                    (int)$post['id']; ?>/delete">
                        Verwijderen
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <p class="mb-6">
            <?php echo htmlspecialchars((string)$post['content'], ENT_QUOTES); ?>
        </p>
        <div class="text-sm text-gray-600">
            <span class="mr-4">
                Status: <?php echo htmlspecialchars((string)$post['status'], ENT_QUOTES); ?>
            </span>
            <span>
                Datum: <?php echo htmlspecialchars((string)$post['created_at'], ENT_QUOTES); ?>
            </span>
        </div>
        <div class="mt-6">
            <a class="underline" href="/php/minicms-pro/admin/posts">Terug naar overzicht</a>
        </div>
    </div>
</section>