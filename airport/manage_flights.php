<?php
include 'db.php';

// إضافة رحلة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_flight'])) {
    $number = $_POST['number'];
    $departure = $_POST['departure'];
    $arrival = $_POST['arrival'];
    $dep_time = $_POST['dep_time'];
    $arr_time = $_POST['arr_time'];
    $seats = $_POST['seats'];
    $query = "INSERT INTO Flights (FlightNumber, DepartureCity, ArrivalCity, DepartureDateTime, ArrivalDateTime, AvailableSeats) 
              VALUES (:number, :departure, :arrival, TO_DATE(:dep_time, 'YYYY-MM-DD HH24:MI'), TO_DATE(:arr_time, 'YYYY-MM-DD HH24:MI'), :seats)";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':number', $number);
    oci_bind_by_name($stmt, ':departure', $departure);
    oci_bind_by_name($stmt, ':arrival', $arrival);
    oci_bind_by_name($stmt, ':dep_time', $dep_time);
    oci_bind_by_name($stmt, ':arr_time', $arr_time);
    oci_bind_by_name($stmt, ':seats', $seats);
    oci_execute($stmt);
    oci_free_statement($stmt);
}

// حذف رحلة
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM Flights WHERE FlightID = :id";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':id', $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
}

// جلب جميع الرحلات
$query = "SELECT * FROM Flights";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
$flights = [];
while ($row = oci_fetch_assoc($stmt)) {
    $flights[] = $row;
}
oci_free_statement($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Flights</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h1>Manage Flights</h1>
    <form method="POST">
        <input type="text" name="number" placeholder="Flight Number" required>
        <input type="text" name="departure" placeholder="Departure City" required>
        <input type="text" name="arrival" placeholder="Arrival City" required>
        <input type="datetime-local" name="dep_time" placeholder="Departure DateTime" required>
        <input type="datetime-local" name="arr_time" placeholder="Arrival DateTime" required>
        <input type="number" name="seats" placeholder="Available Seats" required>
        <button type="submit" name="add_flight">Add Flight</button>
    </form>
    <table border="1">
        <tr>
            <th>FlightID</th>
            <th>FlightNumber</th>
            <th>DepartureCity</th>
            <th>ArrivalCity</th>
            <th>DepartureDateTime</th>
            <th>ArrivalDateTime</th>
            <th>AvailableSeats</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($flights as $flight): ?>
            <tr>
                <td><?= $flight['FLIGHTID'] ?></td>
                <td><?= $flight['FLIGHTNUMBER'] ?></td>
                <td><?= $flight['DEPARTURECITY'] ?></td>
                <td><?= $flight['ARRIVALCITY'] ?></td>
                <td><?= $flight['DEPARTUREDATETIME'] ?></td>
                <td><?= $flight['ARRIVALDATETIME'] ?></td>
                <td><?= $flight['AVAILABLESEATS'] ?></td>
                <td>
                    <a href="?delete=<?= $flight['FLIGHTID'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>