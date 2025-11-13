<?php
require_once 'config/database.php';
requireLogin();

$page_title = "Add New Task";
$conn = getDBConnection();
$user = getCurrentUser();
$success = '';
$error = '';

// Get categories and users
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
$users = $conn->query("SELECT id, username, full_name FROM users ORDER BY username");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_name = $_POST['task_name'] ?? '';
    $task_description = $_POST['task_description'] ?? '';
    $task_key_points = $_POST['task_key_points'] ?? '';
    $category_id = $_POST['category_id'] ?? null;
    $assigned_to = $_POST['assigned_to'] ?? null;
    $priority = $_POST['priority'] ?? 'medium';
    $status = $_POST['status'] ?? 'pending';
    $start_date = $_POST['start_date'] ?? null;
    $due_date = $_POST['due_date'] ?? null;
    $estimated_time = $_POST['estimated_time'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $created_by = $user['id'];
    
    // Priority colors
    $priority_colors = [
        'low' => '#10b981',
        'medium' => '#f59e0b',
        'high' => '#f97316',
        'urgent' => '#ef4444'
    ];
    $priority_color = $priority_colors[$priority];
    
    if (!empty($task_name)) {
        $stmt = $conn->prepare("
            INSERT INTO tasks (task_name, task_description, task_key_points, category_id, assigned_to, 
                             created_by, priority, priority_color, status, start_date, due_date, 
                             estimated_time, tags) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "sssiiisssssss",
            $task_name, $task_description, $task_key_points, $category_id, $assigned_to,
            $created_by, $priority, $priority_color, $status, $start_date, $due_date,
            $estimated_time, $tags
        );
        
        if ($stmt->execute()) {
            $success = "Task created successfully!";
            $task_name = $task_description = $task_key_points = $estimated_time = $tags = '';
        } else {
            $error = "Error creating task: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Task name is required";
    }
}

include 'includes/header.php';
?>

<?php if ($success): ?>
    <div class="alert" style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
        <?php echo $success; ?> <a href="tasks.php">View all tasks</a>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem;">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card">
    <h2 class="card-title" style="margin-bottom: 1.5rem;">Create New Task</h2>
    
    <form method="POST" action="" id="taskForm">
        <div class="form-group">
            <label for="task_name">Task Name *</label>
            <input type="text" id="task_name" name="task_name" class="form-control" required value="<?php echo htmlspecialchars($task_name ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="task_description">Task Description</label>
            <textarea id="task_description" name="task_description" class="form-control"><?php echo htmlspecialchars($task_description ?? ''); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="task_key_points">Task Key Points</label>
            <textarea id="task_key_points" name="task_key_points" class="form-control" placeholder="Enter key points (one per line)"><?php echo htmlspecialchars($task_key_points ?? ''); ?></textarea>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div class="form-group">
                <label for="category_id">Category / Department</label>
                <select id="category_id" name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="assigned_to">Assigned To</label>
                <select id="assigned_to" name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    <?php while ($usr = $users->fetch_assoc()): ?>
                        <option value="<?php echo $usr['id']; ?>"><?php echo htmlspecialchars($usr['full_name'] ?? $usr['username']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority" class="form-control">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control">
                    <option value="pending" selected>Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="under_review">Under Review</option>
                    <option value="completed">Completed</option>
                    <option value="on_hold">On Hold</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="due_date">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="estimated_time">Estimated Time</label>
                <input type="text" id="estimated_time" name="estimated_time" class="form-control" placeholder="e.g., 3 hours, 2 days" value="<?php echo htmlspecialchars($estimated_time ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="tags">Tags / Labels</label>
                <input type="text" id="tags" name="tags" class="form-control" placeholder="urgent, frontend, personal" value="<?php echo htmlspecialchars($tags ?? ''); ?>">
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">Create Task</button>
            <a href="tasks.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php
$conn->close();
include 'includes/footer.php';
?>