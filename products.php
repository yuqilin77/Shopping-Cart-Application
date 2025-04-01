<?php
    // Include the database configuration file
    require_once('database.php');

    // Set the response content type based on the request
    if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/xml') {
        header('Content-Type: application/xml');
    } else {
        header('Content-Type: application/json');
    }

    // Handle API requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (empty($_GET)) {
            // Handle request for all products
            $sql_query = "SELECT * FROM products WHERE isDeleted = 0 ORDER BY id";
        } else if (isset($_GET['name'])) {
            // Handle request for products by name
            $name = $_GET['name'];
            $sql_query = "SELECT * FROM products WHERE name = \"$name\" AND isDeleted = 0 ORDER BY id";
        } else if (isset($_GET['min_price']) && isset($_GET['max_price'])) {
            // Handle request for products within a price range
            $minPrice = (float) $_GET['min_price'];
            $maxPrice = (float) $_GET['max_price'];
            $sql_query = "SELECT * FROM products WHERE price BETWEEN $minPrice AND $maxPrice AND isDeleted = 0 ORDER BY id";
        } else {
            // Handle invalid query parameters
            http_response_code(400);
            echo json_encode(['error' => 'Bad Request: Invalid query parameter key']);
            exit;
        }

        // Execute the query and construct the response
        $result = $db->query($sql_query);
        if ($result) {
            $products = [];
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $products[] = $row;
            }

            // Output the response in the requested format
            if (isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] === 'application/xml') {
                echo arrayToXml(['products' => ['product' => $products]]);
            } else {
                echo json_encode(['products' => $products]);
            }
        } else {
            // Handle database query error
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
    }
    
    // Function to convert an array to XML
    function arrayToXml($array, $rootElement = null, $xml = null) {
        if ($xml === null) {
            $xml = new SimpleXMLElement($rootElement !== null ? $rootElement : '<root/>');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                arrayToXml($value, $key, $xml->addChild($key));
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
        return $xml->asXML();
    }
?>
