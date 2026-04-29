<?php
include 'db.php';

// إضافة حجز
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_booking'])) {
    $passenger_id = $_POST['passenger_id'];
    $flight_id = $_POST['flight_id'];
    $booking_date = $_POST['booking_date'];
    $query = "BEGIN AddBooking(:passenger_id, :flight_id, TO_DATE(:booking_date, 'YYYY-MM-DD HH24:MI:SS')); END;";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':passenger_id', $passenger_id);
    oci_bind_by_name($stmt, ':flight_id', $flight_id);
    oci_bind_by_name($stmt, ':booking_date', $booking_date);
    oci_execute($stmt);
    oci_free_statement($stmt);
}

// حذف حجز
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "BEGIN DeleteBooking(:id); END;";
    $stmt = oci_parse($conn, $query);
    oci_bind_by_name($stmt, ':id', $id);
    oci_execute($stmt);
    oci_free_statement($stmt);
}

// جلب جميع الحجوزات
$query = "SELECT b.BookingID, p.FullName AS PassengerName, f.FlightNumber AS FlightNumber, b.BookingDate 
          FROM Bookings b
          JOIN Passengers p ON b.PassengerID = p.PassengerID
          JOIN Flights f ON b.FlightID = f.FlightID";
$stmt = oci_parse($conn, $query);
oci_execute($stmt);
$bookings = [];
while ($row = oci_fetch_assoc($stmt)) {
    $bookings[] = $row;
}
oci_free_statement($stmt);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h1>Manage Bookings</h1>
    <form method="POST">
        <input type="number" name="passenger_id" placeholder="Passenger ID" required>
        <input type="number" name="flight_id" placeholder="Flight ID" required>
        <input type="datetime-local" name="booking_date" placeholder="Booking Date" required>
        <button type="submit" name="add_booking">Add Booking</button>
    </form>
    <table border="1">
        <tr>
            <th>BookingID</th>
            <th>PassengerName</th>
            <th>FlightNumber</th>
            <th>BookingDate</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= $booking['BOOKINGID'] ?></td>
                <td><?= $booking['PASSENGERNAME'] ?></td>
                <td><?= $booking['FLIGHTNUMBER'] ?></td>
                <td><?= $booking['BOOKINGDATE'] ?></td>
                <td>
                    <a href="?delete=<?= $booking['BOOKINGID'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>