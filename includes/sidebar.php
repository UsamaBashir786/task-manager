<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Task Manager</h2>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="nav-link">
                    <span class="icon">📊</span>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="tasks.php" class="nav-link">
                    <span class="icon">✓</span>
                    <span class="text">All Tasks</span>
                </a>
            </li>
            <li>
                <a href="add-task.php" class="nav-link">
                    <span class="icon">➕</span>
                    <span class="text">Add Task</span>
                </a>
            </li>
            <li>
                <a href="categories.php" class="nav-link">
                    <span class="icon">📁</span>
                    <span class="text">Categories</span>
                </a>
            </li>
            <?php if (getCurrentUser()['role'] === 'admin'): ?>
            <li>
                <a href="users.php" class="nav-link">
                    <span class="icon">👥</span>
                    <span class="text">Users</span>
                </a>
            </li>
            <?php endif; ?>
            <li>
                <a href="profile.php" class="nav-link">
                    <span class="icon">⚙️</span>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php" class="nav-link">
                    <span class="icon">🚪</span>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>