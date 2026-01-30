<?php
use Admin\Core\Flash;

$flash = Flash::get();

if (!$flash) {
    return;
}

$type    = $flash['type'];
$message = $flash['message'];

$kleurClass = match ($type) {
    'success' => 'bg-green-100 border-green-400 text-green-800',
    'error'   => 'bg-red-100 border-red-400 text-red-800',
    default   => 'bg-gray-100 border-gray-400 text-gray-800',
};
?>

<div id="flash" class="mb-4 rounded border px-4 py-3 <?= $kleurClass ?>">
    <?php if (is_array($message)): ?>
        <ul class="list-disc pl-5 space-y-1">
            <?php foreach ($message as $m): ?>
                <li><?= htmlspecialchars($m) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <?= htmlspecialchars($message) ?>
    <?php endif; ?>
</div>

<script>
    // Bonus: success message automatisch laten verdwijnen
    setTimeout(() => {
        document.getElementById('flash')?.remove();
    }, 3000);
</script>
