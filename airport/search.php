<?php
include 'db.php';

$search_results = [];

// البحث في الجداول
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $table = $_POST['table'];
    $column = $_POST['column'];
    $value = $_POST['value'];
    $query = "SELECT * FROM {$table} WHERE {$column} LIKE '%' || :value || '%'";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':value', $value);
    oci_execute($stmt);
    while ($row = oci_fetch_assoc($stmt)) {
        $search_results[] = $row;
    }
    oci_free_statement($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">

    <title>Search</title>
</head>
<body>
    <h1>Search</h1>
    <form method="POST">
        <select name="table" required>
            <option value="Passengers">Passengers</option>
            <option value="Flights">Flights</option>
            <option value="Bookings">Bookings</option>
        </select>
        <input type="text" name="column" placeholder="Column Name" required>
        <input type="text" name="value" placeholder="Search Value" required>
        <button type="submit" name="search">Search</button>
    </form>
    <?php if (!empty($search_results)): ?>
        <table border="1">
            <tr>
                <?php foreach (array_keys($search_results[0]) as $header): ?>
                    <th><?= $header ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($search_results as $result): ?>
                <tr>
                    <?php foreach ($result as $value): ?>
                        <td><?= $value ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>