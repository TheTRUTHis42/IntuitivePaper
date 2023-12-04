<?php
session_start();

// Database connection
$mysqli = new mysqli("webdev.iyaserver.com", "louisxie_user1", "sampleimport", "louisxie_IPImportTest");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
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
            $insertStmt = $mysqli->prepare("INSERT INTO xie_import_6000 (title, categories) VALUES (?, ?)");
            $insertStmt->bind_param("ss", $title, $categories);
            $insertStmt->execute();
            $insertStmt->close();
            break;
        case 'edit':
            // Logic for editing an existing entry
            if (!empty($paperId)) {
                $updateStmt = $mysqli->prepare("UPDATE xie_import_6000 SET title = ?, categories = ? WHERE id = ?");
                $updateStmt->bind_param("sss", $title, $categories, $paperId);
                $updateStmt->execute();
                $updateStmt->close();
            }
            break;
        case 'delete':
            // Logic for deleting an entry
            if (!empty($paperId)) {
                $stmt = $mysqli->prepare("DELETE FROM xie_import_6000 WHERE id = ?");
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
$query = "SELECT paper_id AS id, title, sub_category AS categories FROM paper_category_view";
if (!empty($searchTerm) && $searchType != 'ALL') {
    $searchTerm = $mysqli->real_escape_string($searchTerm);
    $query .= " WHERE $searchType LIKE '%$searchTerm%'";
}

$results = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="w-full ">
    <h2 class="text-2xl font-bold mb-4">Data Management</h2>
    <form id="searchForm" method="GET" action="admin_utilities.php" class="mt-4">
        <input type="text" name="search" id="searchField" placeholder="Search for Entry" class="border px-4 py-2">
        <select name="searchType" id="searchType" class="border px-4 py-2">
            <option value="ALL">ALL</option>
            <option value="title">Title</option>
            <option value="id">Id</option>
            <option value="categories">Sub Category</option>
        </select>
        <button type="submit" id="searchButton" class="py-2 px-4 rounded-full border border-gray-200 transition hover:bg-gray-100 bg-white text-sm text-gray-700">Search</button>
    </form>

    <form id="dataManagementForm" method="POST" action="admin_utilities.php" class="mt-4">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="action" id="actionInput">
        <input type="hidden" name="paperId" id="paperIdInput">
        <input type="hidden" name="title" id="titleInput">
        <input type="hidden" name="categories" id="categoriesInput">
        <table id="dataTable" class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Paper ID</th>
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Sub Category</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php while ($row = $results->fetch_assoc()): ?>
                    <tr id="entry-<?php echo $row['id']; ?>" class="border px-4 py-2">
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
</div>

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
