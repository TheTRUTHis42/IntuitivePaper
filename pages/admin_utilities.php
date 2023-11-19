<?php
session_start();

// Database connection
$mysql = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// CSRF Token for form submission
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle POST requests for utilities management
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token mismatch.');
    }

    $action = $_POST['action'] ?? '';
    $paperId = $_POST['paperId'] ?? '';
    $title = $_POST['title'] ?? '';
    $categories = $_POST['categories'] ?? '';

    switch ($action) {
        case 'add':
            // Logic for adding a new entry
            break;
        case 'edit':
            // Logic for editing an existing entry
            if (!empty($paperId)) {
                $updateStmt = $mysql->prepare("UPDATE xie_import_6000 SET title = ?, categories = ? WHERE id = ?");
                $updateStmt->bind_param("sss", $title, $categories, $paperId);
                $updateStmt->execute();
                $updateStmt->close();
            }
            break;
        case 'delete':
            // Logic for deleting an entry
            if (!empty($paperId)) {
                $stmt = $mysql->prepare("DELETE FROM xie_import_6000 WHERE id = ?");
                $stmt->bind_param("s", $paperId);
                $stmt->execute();
                $stmt->close();
            }
            break;
    }
}

// Fetching data from the database
$searchTerm = $_GET['search'] ?? '';
$searchType = $_GET['searchType'] ?? 'ALL';
$query = "SELECT id, title, categories FROM xie_import_6000";
if (!empty($searchTerm) && $searchType != 'ALL') {
    $searchTerm = $mysql->real_escape_string($searchTerm);
    $query .= " WHERE $searchType LIKE '%$searchTerm%'";
}

$results = $mysql->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<h2>Data Management</h2>
<form id="searchForm" method="GET" action="admin_utilities.php">
    <input type="text" name="search" id="searchField" placeholder="Search for Entry">
    <select name="searchType" id="searchType">
        <option value="ALL">ALL</option>
        <option value="title">Title</option>
        <option value="id">Id</option>
        <option value="categories">Sub Category</option>
    </select>
    <button type="submit" id="searchButton">Search</button>
</form>

<form id="dataManagementForm" method="POST" action="admin_utilities.php">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="hidden" name="action" id="actionInput">
    <input type="hidden" name="paperId" id="paperIdInput">
    <input type="hidden" name="title" id="titleInput">
    <input type="hidden" name="categories" id="categoriesInput">
    <table id="dataTable">
        <thead>
            <tr>
                <th>Paper ID</th>
                <th>Title</th>
                <th>Sub Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $results->fetch_assoc()): ?>
                <tr id="entry-<?php echo $row['id']; ?>">
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['categories']); ?></td>
                    <td class="action-cell">
                        <button type="button" class="deleteEntry" data-entryid="<?php echo $row['id']; ?>">--</button>
                        <button type="button" class="editEntry" data-entryid="<?php echo $row['id']; ?>">/</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</form>

<script>
    $(document).on('click', '.deleteEntry', function() {
        const entryId = $(this).data('entryid');
        if (confirm('Are you sure you want to delete this entry?')) {
            $('#actionInput').val('delete');
            $('#paperIdInput').val(entryId);
            $('#dataManagementForm').submit();
        }
    });

    $(document).on('click', '.editEntry', function() {
        const entryId = $(this).data('entryid');
        const title = prompt('Enter new title:');
        const categories = prompt('Enter new categories:');

        if (title !== null && categories !== null) {
            $('#actionInput').val('edit');
            $('#paperIdInput').val(entryId);
            $('#titleInput').val(title);
            $('#categoriesInput').val(categories);
            $('#dataManagementForm').submit();
        }
    });
</script>

</body>
</html>
