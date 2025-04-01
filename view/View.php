<?php
    // Define a class named View
    class View {
        // Method to display a table of product information
        public function displayProducts($result) {
            echo '<table>';
            echo '<tr>';
            echo '<th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Stock Qty</th><th>Order Qty</th><th>&nbsp;</th>';
            echo '</tr>';

            // Loop through each product and generate rows in the table
            foreach ($result as $product) {
                $stock_quantity = $product['quantity'];
                $price = $product['price'];
                echo '<tr>';
                echo '<td>' . $product['id'] . '</td>';
                echo '<td>' . $product['name'] . '</td>';
                echo '<td>' . $product['description'] . '</td>';
                echo '<td>' . $price . '</td>';
                echo '<td>' . $stock_quantity . '</td>';
                echo '<td><input type="number" name="order_quantity_product_' . $product['id'] . '" min="0" max="' . $stock_quantity . '" value="0"></td>';
                echo '<td><button type="button" class="add-to-order" product-id="' . $product['id'] . '" product-stock="' . $stock_quantity . '">Update Order</button></td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        // Method to display a list of customers as links
        public function showCustomers($customers) {
            while ($customer = $customers->fetch()) {
                echo '<li><a href="admin_interface.php?value=' . $customer['id'] . '">' . $customer['name'] . '</a></li>';
            }
        }

        // Method to display a table of past orders for a given customer
        public function showPastOrders($orders) {
            echo '<table>';
            echo '<tr>';
            echo '<th>ID</th><th>Order Details</th><th>Deleted</th><th>&nbsp;</th><th>&nbsp;</th>';
            echo '</tr>';

            // Loop through each order and generate rows in the table
            while ($order = $orders->fetch()) {
                echo '<tr>';
                echo '<td>' . $order['id'] . '</td>';
                echo '<td>' . $order['comment'] . '</td>';
                echo '<td>' . ($order['isDeleted'] == 0 ? "false" : "true") . '</td>';
                echo '<td>';
                if ($order['isDeleted'] == 0) {
                    echo '<form action="update_order_form.php" method="post">';
                    echo '<input type="hidden" name="order_id" value="' . $order['id'] . '">';
                    echo '<button type="submit" name="update">Update Order</button>';
                    echo '</form>';
                }
                echo '</td>';

                echo '<td>';
                if ($order['isDeleted'] == 0) {
                    echo '<form action="delete_order.php" method="post" id="delete_order">';
                    echo '<input type="hidden" name="order_id" value="' . $order['id'] . '">';
                    echo '<button type="submit" name="delete">Delete Order</button>';
                    echo '</form>';
                }  
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        // Method to display a table for updating order details
        public function displayUpdateOrder($orders, $product_name, $product_stock, $product_deleted) {
            echo '<table>';
            echo '<tr>';
            echo '<th>Name</th><th>Deleted</th><th>Stock Qty</th><th>Order Qty</th>';
            echo '</tr>';

            // Loop through each order and generate rows in the table
            foreach ($orders as $key => $value) {
                $total_stock = intval($product_stock[$key]) + intval($value);
                echo '<tr>';
                echo '<td>' . $product_name[$key] . '</td>';
                echo '<td>' . ($product_deleted[$key] == 0 ? "false" : "true") . '</td>';
                echo '<td>' . $total_stock . '</td>';
                echo '<td>';
                if ($product_deleted[$key] == 0) {
                    echo '<input type="number" min="0" max="' . $total_stock . '" value="' . $value . '" name="order_quantity_product_' . $key . '">';
                    echo '<input type="hidden" name="original_quantity_product_' . $key . '" value="' . $value . '">';
                    echo '<input type="hidden" name="name_product_' . $key . '" value="' . $product_name[$key] . '">';
                } else {
                    echo $value;
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }

        // Method to display a table for updating product details
        public function displayUpdateProduct($product) {
            $result = $product->fetch(PDO::FETCH_ASSOC);
            echo '<table>';
            echo '<tr>';
            echo '<th>Field</th><th>Value</th>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Name</td>';
            echo '<td><input type="text" name="product_name" value="' . $result['name'] . '" required></td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Description</td>';
            echo '<td><input type="text" name="product_description" value="' . $result['description'] . '"></td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Price</td>';
            echo '<td><input type="number" name="product_price" step="0.01" value="' . $result['price'] . '"></td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Stock Qty</td>';
            echo '<td><input type="number" name="product_stock" value="' . $result['quantity'] . '"></td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td>Deleted</td>';

            echo '<td>';
            echo '<select name="product_deleted">';
            if ($result['isDeleted'] == 0) {
                echo '<option value="false">false</option>';
                echo '<option value="true">true</option>';
            } else {
                echo '<option value="true">true</option>';
                echo '<option value="false">false</option>';
            }
            echo '</select>';
            echo '</td>';
            echo '</tr>';

            echo '</table>';
        }
    }
?>