<?php
    // Define a class named Controller
    class Controller {
        
        // Private property to hold the model instance
        private $model;

        // Constructor that accepts a model instance and assigns it to the private property
        public function __construct($model) {
            $this->model = $model;
        }

        // Method to search products by keyword
        public function searchProducts($keyword) {
            if (empty($keyword)) {
                // If keyword is empty, retrieve all products
                $products = $this->model->getAllProducts();
                $result = $products->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }

            // Search products by name using the model
            $products = $this->model->getProductsByName($keyword);
            $result1 = $products->fetchAll(PDO::FETCH_ASSOC);

            // Search products by description using the model
            $products = $this->model->getProductsByDescription($keyword);
            $result2 = $products->fetchAll(PDO::FETCH_ASSOC);

            // Combine the results from name and description searches
            $combined_results = array_merge($result1, $result2);

            // Remove duplicates based on product.id
            $dedup_results = array();
            foreach ($combined_results as $result) {
                $product_id = $result['id'];
                if (!isset($dedup_results[$product_id])) {
                    $dedup_results[$product_id] = $result;
                }
            }

            // Re-index the array to ensure consecutive numeric keys
            return array_values($dedup_results);
        }
    }
?>