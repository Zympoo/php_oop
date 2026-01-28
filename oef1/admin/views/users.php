<?php
declare(strict_types=1);
?>

<section class="p-6">
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">Users overzicht</h2>
            <a class="underline" href="/php/oef1/admin/users/create">+ Nieuwe user</a>
        </div>

        <table class="w-full text-sm">
            <thead>
            <tr class="text-left border-b">
                <th class="py-2">Naam</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Datum</th>
                <th class="text-right">Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr class="border-b">
                    <td class="py-2">
                        <a class="underline" href="/php/oef1/admin/users/<?php echo (int)$user['id']; ?>">
                            <?php echo htmlspecialchars($user['name'], ENT_QUOTES); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($user['role_name'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at'], ENT_QUOTES); ?></td>
                    <td class="text-right space-x-3">
                        <a class="underline" href="/php/oef1/admin/users/<?php echo (int)$user['id']; ?>/edit">Bewerken</a>
                        <a class="underline text-red-600" href="/php/oef1/admin/users/<?php echo (int)$user['id']; ?>/delete">Verwijderen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>