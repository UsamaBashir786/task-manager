<?php
require_once 'config/database.php';
requireLogin();

$page_title = "Dashboard";
$conn = getDBConnection();
$user = getCurrentUser();

// Get statistics
$total_tasks = $conn->query("SELECT COUNT(*) as count FROM tasks")->fetch_assoc()['count'];
$completed_tasks = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE status = 'completed'")->fetch_assoc()['count'];
$in_progress = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE status = 'in_progress'")->fetch_assoc()['count'];
$pending_tasks = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE status = 'pending'")->fetch_assoc()['count'];

// Get recent tasks
$recent_tasks = $conn->query("
    SELECT t.*, c.name as category_name, u.username as assigned_to_name 
    FROM tasks t 
    LEFT JOIN categories c ON t.category_id = c.id 
    LEFT JOIN users u ON t.assigned_to = u.id 
    ORDER BY t.created_at DESC 
    LIMIT 10
");

include 'includes/header.php';
?>

<div class="stats-grid">
    <div class="stat-card" style="border-left-color: var(--primary-color);">
        <h3>Total Tasks</h3>
        <div class="stat-value"><?php echo $total_tasks; ?></div>
    </div>
    
    <div class="stat-card" style="border-left-color: var(--secondary-color);">
        <h3>Completed</h3>
        <div class="stat-value"><?php echo $completed_tasks; ?></div>
    </div>
    
    <div class="stat-card" style="border-left-color: var(--warning-color);">
        <h3>In Progress</h3>
        <div class="stat-value"><?php echo $in_progress; ?></div>
    </div>
    
    <div class="stat-card" style="border-left-color: var(--danger-color);">
        <h3>Pending</h3>
        <div class="stat-value"><?php echo $pending_tasks; ?></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Tasks</h2>
        <a href="add-task.php" class="btn btn-primary">Add New Task</a>
    </div>
    
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Task Name</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_tasks->num_rows > 0): ?>
                    <?php while ($task = $recent_tasks->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $task['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($task['task_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($task['category_name'] ?? 'N/A'); ?></td>
                            <td><span class="badge badge-<?php echo $task['priority']; ?>"><?php echo ucfirst($task['priority']); ?></span></td>
                            <td><span class="badge badge-<?php echo str_replace('_', '-', $task['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?></span></td>
                            <td><?php echo htmlspecialchars($task['assigned_to_name'] ?? 'Unassigned'); ?></td>
                            <td><?php echo $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'N/A'; ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $task['progress_percentage']; ?>%"></div>
                                </div>
                                <small><?php echo $task['progress_percentage']; ?>%</small>
                            </td>
                            <td>
                                <a href="view-task.php?id=<?php echo $task['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">View</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 2rem;">No tasks found. <a href="add-task.php">Create your first task</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>