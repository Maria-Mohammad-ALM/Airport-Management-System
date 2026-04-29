<?php
include 'db.php';

// جلب السجلات
$query = "SELECT * FROM ActivityLogs ORDER BY LogDate DESC";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
$logs = [];
while ($row = oci_fetch_assoc($stmt)) {
    $logs[] = $row;
}
oci_free_statement($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    
    <title>Activity Logs</title>
</head>
<body>
    <h1>Activity Logs</h1>
    <table border="1">
        <tr>
            <th>LogID</th>
            <th>Description</th>
            <th>LogDate</th>
        </tr>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= $log['LOGID'] ?></td>
                <td><?= $log['DESCRIPTION'] ?></td>
                <td><?= $log['LOGDATE'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>