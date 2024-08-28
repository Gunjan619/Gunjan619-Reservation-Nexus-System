<?php
include('includes/dbcon.php');

// Fetch menu items from the database
$query = "SELECT menu_name FROM menu";
$result = mysqli_query($con, $query) or die(mysqli_error($con));
$menu_items = [];
while ($row = mysqli_fetch_assoc($result)) {
    $menu_items[] = $row['menu_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Walk-In Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .add-item-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .add-item-btn:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function addItem() {
            const itemsDiv = document.getElementById('items');
            const itemIndex = itemsDiv.children.length + 1;
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('form-group');
            itemDiv.innerHTML = `
                <label for="item${itemIndex}">Item ${itemIndex}:</label>
                <select name="items[]" id="item${itemIndex}" required>
                    <option value="">Select Item</option>
                    <?php foreach ($menu_items as $item): ?>
                        <option value="<?php echo htmlspecialchars($item); ?>"><?php echo htmlspecialchars($item); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="quantity${itemIndex}">Quantity:</label>
                <input type="number" name="quantities[]" id="quantity${itemIndex}" min="1" required>
            `;
            itemsDiv.appendChild(itemDiv);
        }
    </script>
</head>
<body>
    <h2>Walk-In Order</h2>
    <form action="walkin_order_process.php" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="pax">Number of People:</label>
            <input type="number" id="pax" name="pax" min="1" required>
        </div>
        <div id="items">
            <div class="form-group">
                <label for="item1">Item 1:</label>
                <select name="items[]" id="item1" required>
                    <option value="">Select Item</option>
                    <?php foreach ($menu_items as $item): ?>
                        <option value="<?php echo htmlspecialchars($item); ?>"><?php echo htmlspecialchars($item); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="quantity1">Quantity:</label>
                <input type="number" name="quantities[]" id="quantity1" min="1" required>
            </div>
        </div>
        <button type="button" class="add-item-btn" onclick="addItem()">+ Add Another Item</button>
        <button type="submit" class="add-item-btn" style="background-color: #2196F3;">Submit Order</button>
    </form>
</body>
</html>
