<?php
session_start();

// Database connection
$mysqli = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

// CSRF Token Generation and Validation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Handle POST requests for account management
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    // Determine action: add, edit, or delete
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'add':
            // Logic to add a new account
            break;
        case 'edit':
            // Logic to edit an existing account
            $userIdToEdit = $_POST['userId'];
            $newUsername = $_POST['newUsername']; // Assuming new username is passed
            $newPassword = $_POST['newPassword']; // Assuming new password is passed
            $mysqli->query("UPDATE users SET username = '" . $mysqli->real_escape_string($newUsername) . "', password = '" . $mysqli->real_escape_string($newPassword) . "' WHERE user_id = " . intval($userIdToEdit));
            break;
        case 'delete':
            $userIdToDelete = $_POST['userId'];
            $mysqli->query("DELETE FROM users WHERE user_id = " . intval($userIdToDelete));
            break;
    }
}

// Fetch all user accounts from the database
$users = $mysqli->query("SELECT user_id, username, password, creation_date FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Account Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for simpler DOM manipulation -->
</head>
<body>

<h2>Account Management</h2>
<button id="editMode">Edit</button>
<form id="userActionForm" method="POST" action="admin_accounts.php">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    <input type="hidden" name="action" id="actionInput">
    <input type="hidden" name="userId" id="userIdInput">
    <input type="hidden" name="newUsername" id="newUsernameInput">
    <input type="hidden" name="newPassword" id="newPasswordInput">
    <table id="accountsTable">
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Creation Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr id="user-<?php echo $user['user_id']; ?>">
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['password']); ?></td>
                    <td><?php echo htmlspecialchars($user['creation_date']); ?></td>
                    <td class="action-cell">
                        <!-- Action buttons will be dynamically added here by JavaScript -->
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</form>

<script>
    // JavaScript functionality for edit and delete operations
    document.getElementById('editMode').addEventListener('click', function() {
        const table = document.getElementById('accountsTable');
        for (let row of table.rows) {
            const actionCell = row.getElementsByClassName('action-cell')[0];
            if (actionCell) {
                actionCell.innerHTML = '<button class="deleteUser" data-userid="' + row.id.split('-')[1] + '">--</button> <button class="editUser" data-userid="' + row.id.split('-')[1] + '">/</button>';
            }
        }
    });

    // Delete user
    $(document).on('click', '.deleteUser', function() {
        const userId = $(this).data('userid');
        if (confirm('Are you sure you want to delete this user?')) {
            $('#actionInput').val('delete');
            $('#userIdInput').val(userId);
            $('#userActionForm').submit();
        }
    });

    // Edit user
    $(document).on('click', '.editUser', function() {
        const userId = $(this).data('userid');
        const username = prompt("Enter new username:");
        const password = prompt("Enter new password:");
        if (username !== null && password !== null) {
            $('#actionInput').val('edit');
            $('#userIdInput').val(userId);
            $('#newUsernameInput').val(username);
            $('#newPasswordInput').val(password);
            $('#userActionForm').submit();
        }
    });
</script>

</body>
</html>
