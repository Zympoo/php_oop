<?php
declare(strict_types=1);
?>

<section class="p-6">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">User bewerken</h2>

        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-4 border border-red-200 bg-red-50 rounded">
                <p class="font-bold mb-2">Controleer je invoer:</p>
                <ul class="list-disc pl-6">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/php/oef1/admin/users/<?php echo (int)$userId; ?>/update" class="space-y-4">
            <div>
                <label class="block text-sm font-bold mb-1" for="name">Naam</label>
                <input class="w-full border rounded p-2" type="text" id="name" name="name"
                       value="<?php echo htmlspecialchars($old['name'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="email">Email</label>
                <input class="w-full border rounded p-2" type="email" id="email" name="email"
                       value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES); ?>">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="password">Nieuw wachtwoord (optioneel)</label>
                <input class="w-full border rounded p-2" type="password" id="password" name="password">
            </div>

            <div>
                <label class="block text-sm font-bold mb-1" for="role">Rol</label>
                <select class="w-full border rounded p-2" id="role" name="role">
                    <option value="editor" <?php echo (($old['role'] ?? 'editor') === 'editor') ? 'selected' : ''; ?>>
                        Editor
                    </option>
                    <option value="admin" <?php echo (($old['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>
                        Admin
                    </option>
                </select>
            </div>

            <div class="flex gap-4">
                <button class="border rounded px-4 py-2" type="submit">Update</button>
                <a class="underline" href="/php/oef1/admin/users">Annuleren</a>
            </div>
        </form>
    </div>
</section>