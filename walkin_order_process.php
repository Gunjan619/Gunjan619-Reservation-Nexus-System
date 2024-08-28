<?php
include('includes/dbcon.php');

$name = $_POST['name'];
$phone = $_POST['phone'];
$pax = $_POST['pax'];
$items = $_POST['items'];
$quantities = $_POST['quantities'];

// Insert into walkin_reservation table
$query = "INSERT INTO walkin_reservation (name, phone, pax, r_time, r_date) VALUES ('$name', '$phone', '$pax', NOW(), CURDATE())";
mysqli_query($con, $query) or die(mysqli_error($con));
$reservation_id = mysqli_insert_id($con);

// Insert into walkin_order table
foreach ($items as $index => $item) {
    $quantity = $quantities[$index];
    $query = "INSERT INTO walkin_order (reservation_id, item, quantity) VALUES ('$reservation_id', '$item', '$quantity')";
    mysqli_query($con, $query) or die(mysqli_error($con));
}

echo "<script>alert('Order placed successfully!'); document.location='walkin_bill.php?id=$reservation_id';</script>";
?>
