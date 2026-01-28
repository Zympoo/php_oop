<?php
declare(strict_types=1);
?>

<section class="p-6">
    <div class="bg-white p-6 rounded shadow max-w-xl">
        <h2 class="text-xl font-bold mb-4 text-red-600"><?php echo htmlspecialchars($title, ENT_QUOTES); ?></h2>

        <p class="mb-4">Ben je zeker dat je deze user wil verwijderen?</p>
        <p class="mb-6"><strong><?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?></strong> (<?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?>)</p>

        <form method="post" action="/php/oef1/admin/users/<?php echo (int)$user['id']; ?>/delete">
            <div class="flex gap-4">
                <button class="border px-4 py-2 text-red-600" type="submit">Ja, verwijder</button>
                <a class="underline" href="/php/oef1/admin/users">Annuleren</a>
            </div>
        </form>
    </div>
</section>