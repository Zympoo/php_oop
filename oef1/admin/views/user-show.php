<?php
declare(strict_types=1);
?>

<section class="p-6">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?></h2>

        <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></p>
        <p class="mb-2"><strong>Rol:</strong> <?php echo htmlspecialchars($user['role_name'], ENT_QUOTES); ?></p>
        <p class="mb-2"><strong>Datum aangemaakt:</strong> <?php echo htmlspecialchars($user['created_at'], ENT_QUOTES); ?></p>

        <div class="mt-6 flex gap-4">
            <a class="underline" href="/php/oef1/admin/users">Terug naar overzicht</a>
            <a class="underline" href="/php/oef1/admin/users/<?php echo (int)$user['id']; ?>/edit">Bewerken</a>
        </div>
    </div>
</section>