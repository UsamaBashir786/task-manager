<?php
// update_credentials_form.php
require_once 'config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $conn = getDBConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if user exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing user
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $updateStmt->bind_param("ss", $hashedPassword, $email);
            
            if ($updateStmt->execute()) {
                $message = "Password updated successfully for $email";
            } else {
                $message = "Error updating password: " . $conn->error;
            }
            $updateStmt->close();
        } else {
            $message = "User with email $email not found.";
        }
        
        $checkStmt->close();
        $conn->close();
    } else {
        $message = "Please fill in all fields";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Credentials</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Update User Credentials</h2>
            
            <?php if ($message): ?>
                <div style="background: #d1fae5; color: #065f46; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="grid@pro.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" value="@grindxpro" required>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Update Credentials</button>
            </form>
        </div>
    </div>
</body>
</html>