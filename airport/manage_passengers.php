<?php
include 'db.php'; // ملف الاتصال بقاعدة البيانات

// إضافة راكب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_passenger'])) {
    $name = $_POST['name'];
    $passport = $_POST['passport'];
    $contact = $_POST['contact'];
    $query = "INSERT INTO Passengers (FullName, PassportNumber, ContactNumber) VALUES (:name, :passport, :contact)";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':name', $name);
    oci_bind_by_name($stmt, ':passport', $passport);
    oci_bind_by_name($stmt, ':contact', $contact);
    oci_execute($stmt);
    oci_free_statement($stmt);
}

// حذف راكب
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM Passengers WHERE PassengerID = :id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':id', $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
}

// جلب جميع الركاب
$query = "SELECT * FROM Passengers";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
$passengers = [];
while ($row = oci_fetch_assoc($stmt)) {
    $passengers[] = $row;
}
oci_free_statement($stmt);
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">

    <title>Manage Passengers</title>
</head>
<body>
    <h1>Manage Passengers</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="passport" placeholder="Passport Number" required>
        <input type="text" name="contact" placeholder="Contact Number" required>
        <button type="submit" name="add_passenger">Add Passenger</button>
    </form>
    <table border="1">
        <tr>
            <th>PassengerID</th>
            <th>FullName</th>
            <th>PassportNumber</th>
            <th>ContactNumber</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($passengers as $passenger): ?>
            <tr>
                <td><?= $passenger['PASSENGERID'] ?></td>
                <td><?= $passenger['FULLNAME'] ?></td>
                <td><?= $passenger['PASSPORTNUMBER'] ?></td>
                <td><?= $passenger['CONTACTNUMBER'] ?></td>
                <td>
                    <a href="?delete=<?= $passenger['PASSENGERID'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>