<?php
if (!isset($page_title)) {
    $page_title = "Task Management";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <?php if (isLoggedIn()): ?>
            <?php include 'includes/sidebar.php'; ?>
        <?php endif; ?>
        
        <div class="main-content">
            <?php if (isLoggedIn()): ?>
                <header class="top-header">
                    <div class="header-left">
                        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
                        <h1><?php echo $page_title; ?></h1>
                    </div>
                    <div class="header-right">
                        <div class="user-info">
                            <span class="user-name"><?php echo getCurrentUser()['full_name'] ?? getCurrentUser()['username']; ?></span>
                            <div class="user-avatar"><?php echo strtoupper(substr(getCurrentUser()['username'], 0, 1)); ?></div>
                        </div>
                    </div>
                </header>
            <?php endif; ?>
            
            <div class="page-content"><?php echo "\n"; ?>