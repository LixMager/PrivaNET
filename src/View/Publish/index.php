<?php
$page_title = 'PrivaNET - Realizar publicación';
ob_start();
?>
<main class="container main-layout">
    <?php include APP_PATH . '/View/components/create_post_form.php'; ?>
</main>

<?php
$page_content = ob_get_clean();
$page_scripts = '<script src="/PrivaNet/src/View/Publish/publish.js?v=' . time() . '"></script>';
include __DIR__ . '/../layouts/base.php';
?>
