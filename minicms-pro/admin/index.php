<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');//toon fouten effectief in browser

require __DIR__ . '/autoload.php';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/sidebar.php';

use Admin\Controllers\DashboardController;
use Admin\Models\StatsModel;

$statsModel = new StatsModel();
$controller = new DashboardController($statsModel);

$title = $controller->getTitle();
?>
    <main class="flex-1">
        <?php require __DIR__ . '/includes/topbar.php';
              $controller->index(); ?>
    </main>
<?php
require __DIR__ . '/includes/footer.php';