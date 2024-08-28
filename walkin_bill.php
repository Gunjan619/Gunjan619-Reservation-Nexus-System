<?php
include('includes/dbcon.php');

$reservation_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$reservation_id) {
    echo "<script>alert('Invalid reservation ID.'); document.location='index.php';</script>";
    exit;
}

// Get reservation details
$query = "SELECT * FROM walkin_reservation WHERE id='$reservation_id'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$reservation = mysqli_fetch_assoc($result);

// Get order details
$query = "SELECT * FROM walkin_order WHERE reservation_id='$reservation_id'";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[] = $row;
}

// Function to get item prices from the menu table
function get_item_price($con, $item) {
    $query = "SELECT menu_price FROM menu WHERE menu_name='$item'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['menu_price'] : 0;
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2, h3 {
            color: #333;
            text-align: center;
        }
        .center-text {
            text-align: center;
        }
        p {
            font-size: 14px;
            color: #555;
            margin: 0;
        }
        .details {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .total {
            font-weight: bold;
        }
        .print-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .print-button:hover {
            background-color: #45a049;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
    <script>
        function printBill() {
            window.print();
            redirectToHome();
        }

        function redirectToHome() {
            setTimeout(function() {
                window.location.href = 'index.php'; // Replace 'index.php' with your actual home page URL
            }, 500); // Adjust the delay (in milliseconds) as needed
        }
    </script>
</head>
<body>
    <h2>Bill</h2>
    <p>Name: <?php echo htmlspecialchars($reservation['name']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($reservation['phone']); ?></p>
    <p>Date: <?php echo htmlspecialchars($reservation['r_date']); ?></p>
    <p>Time: <?php echo htmlspecialchars($reservation['r_time']); ?></p>
    <p>Number of People: <?php echo htmlspecialchars($reservation['pax']); ?></p>
    <h3>Order Details</h3>
    <table>
        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <?php
            $item = htmlspecialchars($order['item']);
            $quantity = (int) $order['quantity'];
            $price = get_item_price($con, $item);
            $item_total = $quantity * $price;
            $total += $item_total;
            ?>
            <tr>
                <td><?php echo $item; ?></td>
                <td><?php echo $quantity; ?></td>
                <td><?php echo number_format($price, 2); ?></td>
                <td><?php echo number_format($item_total, 2); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="total">
            <td colspan="3">Total</td>
            <td><?php echo number_format($total, 2); ?></td>
        </tr>
    </table>

    <!-- Print Button -->
    <button class="print-button no-print" onclick="printBill()">Print</button>
</body>
</html>
