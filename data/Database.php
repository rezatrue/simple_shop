<?php

class Database {
    private $servername  = "localhost"; // Your server name
    private $username = "root";     // Your database username
    private $password = "";     // Your database password
    private $dbname = "001_shop";          // Your database name
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        //echo "Connected successfully<br>";
        //echo console.log("Connected successfully");
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function fetchAll($result) {
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


    public function queryCountForListPage() {
        // SQL query to select data
        $sql = "SELECT COUNT(*) AS total_count FROM products";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    // Nmae changed previous one was queryCountFor(
    public function productCountForCat($cat_id) {
        // SQL query to select data
        $sql = "SELECT COUNT(*) as total_count FROM product_categories where cat_id = " . $cat_id ;
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
    }


    public function queryForListPage($page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT * FROM products LIMIT " .$itemsPerPage ." OFFSET ". $offset;
        $result = $this->query($sql);

        $relatedProductList['product'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['p_id'])) {
                    // Store product name and price
                    $relatedProductList['product'][] = [
                        'p_id' => $row['p_id'],
                        'p_name' => $row['p_name'],
                        'p_image' => $row['p_image'],
                        'p_price' => $row['p_price'],
                        'p_sizes' => $row['p_sizes'],
                        'p_is_show' => $row['p_is_show'],
                        'p_is_featured' => $row['p_is_featured'],
                        'p_short_description' => $row['p_short_description'],
                        'p_specification' => $row['p_specification']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($relatedProductList);
        // echo '<pre/>';
        //exit();
        return $relatedProductList; 
    }

    /*
    public function queryFor($searchParam, $page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT p_id, p_name, p_image, p_price, p_sizes FROM products WHERE "  . $searchParam . " LIMIT " .$itemsPerPage ." OFFSET ". $offset; 
        //echo $sql;
        return $this->query($sql); 
    }*/

    // repalce for "public function queryFor("
    public function queryForRelatedProduct($cat_id, $page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        //echo $sql;
        $sql = "SELECT 
            p.p_id, p.p_name, p.p_image, p.p_price, p.p_sizes
        FROM 
            products p
        JOIN 
            product_categories pc ON p.p_id = pc.p_id   
        WHERE 
            pc.cat_id = " . $cat_id .
        " LIMIT "
            . $itemsPerPage .
        " OFFSET "
            . $offset;
        //echo $sql;
        $result = $this->query($sql);

        $relatedProductList['product'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['p_id'])) {
                    // Store product name and price
                    $relatedProductList['product'][] = [
                        'p_id' => $row['p_id'],
                        'p_name' => $row['p_name'],
                        'p_image' => $row['p_image'],
                        'p_price' => $row['p_price'],
                        'p_sizes' => $row['p_sizes']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($relatedProductList);
        // echo '<pre/>';
        //exit();
        return $relatedProductList;

    }

    /*
    public function getDetails($id) {
        // SQL query to select data
        $sql = "SELECT * FROM products WHERE p_id = " . $id;    
        $result = $this->query($sql);    
        return $result;

    }*/

    public function getProductDetails($id) {
        // SQL query to select data
        $sql = "SELECT 
                    p.*, 
                    c.cat_name,
                    c.cat_id as sub_cat_id,
                    c1.cat_name as parent_cat
                FROM 
                    products p
                JOIN 
                    product_categories pc ON p.p_id = pc.p_id
                JOIN 
                    categories c ON pc.cat_id = c.cat_id
                JOIN 
	                categories c1 ON c.parent_cat_id = c1.cat_id      
                WHERE 
                    p.p_id = " . $id;    
        $result = $this->query($sql);    
        //return $result;
        
        // Initialize an array to store product details
        $productDetails = [];
        
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (!isset($productDetails['p_name'])) {
                    // Store product name and price
                    $productDetails['p_id'] = $row['p_id'];
                    $productDetails['p_name'] = $row['p_name'];
                    $productDetails['p_image'] = $row['p_image'];
                    $productDetails['p_price'] = $row['p_price'];
                    $productDetails['p_sizes'] = $row['p_sizes'];
                    $productDetails['p_is_show'] = $row['p_is_show'];
                    $productDetails['p_is_featured'] = $row['p_is_featured'];
                    $productDetails['p_short_description'] = $row['p_short_description'];
                    $productDetails['p_specification'] = $row['p_specification'];
                    // Initialize categories array
                    $productDetails['categories'] = [];
                }
                
                // Add category name to the categories array
                if (!empty($row['cat_name'])) {
                    $productDetails['categories'][] = [
                        'cat_name' => $row['cat_name'],
                        'parent_cat' => $row['parent_cat'],
                        'sub_cat_id' => $row['sub_cat_id']
                    ];
                }
            }
        }
        // echo '<pre/>';
        // print_r($productDetails);
        // exit();
        return $productDetails;
    }


    public function getMainCategories($query) {
        // SQL query to select data
        $sql = "SELECT * FROM categories WHERE parent_cat_id = 0  AND cat_name LIKE ? ";
        $stmt = $this->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    }    

    public function getSubCategories($category) {
        // SQL query to select data
        $sql = "SELECT cat_name, cat_id FROM categories WHERE parent_cat_id = (SELECT cat_id FROM categories WHERE cat_name = ?)";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }    


    public function queryFeaturedList() {
        // SQL query to select data
        $sql = "SELECT * FROM products WHERE p_is_featured = TRUE";    
        $result = $this->query($sql);    
        return $result;

    }

    public function queryIsCatOfMonth() {
        // SQL query to select data
        $sql = "SELECT * FROM categories WHERE cat_is_catofmonth = TRUE";    
        $result = $this->query($sql);    
        return $result;
    
    }

// SQL query for admin
    public function getCategoryList() {
        // SQL query to select data
        $sql = "SELECT 
                    cats.*, 
                    cats1.cat_id as sub_cat_id,
                    cats1.cat_name as sub_cat_name
                FROM 
                    categories cats
                JOIN 
                    categories cats1 ON cats.cat_id = cats1.parent_cat_id     
                WHERE 
                    cats.parent_cat_id = 0
                ORDER BY 
                    cats.cat_id ASC"; 

        $result = $this->query($sql);    
        
        // Initialize an array to store product details
        $categories = [];
        
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                    // Store category name and id
                    $category = [];
                    $category['cat_id'] = $row['cat_id'];
                    $category['cat_name'] = $row['cat_name'];
                    $category['cat_image'] = $row['cat_image'];
                    $category['cat_is_catofmonth'] = $row['cat_is_catofmonth'];
                    $category['sub_cat_id'] = $row['sub_cat_id'];
                    $category['sub_cat_name'] = $row['sub_cat_name'];
                    // Initialize categories array
                    $categories[] = $category;
            }
        }
        // echo '<pre>';
        // print_r($categories);
        // echo "</pre>";
        // echo count($categories);
        // exit();
        return $categories;
    }

    public function queryCountCategory() {
        // SQL query to select data
        $sql = "SELECT COUNT(*) AS total_count FROM categories";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function getSubCategoryDetails($sub_cat_id) {
        // SQL query to select data
            $sql = "SELECT 
                cats.cat_name as sub_cat_name, 
                cats.cat_id as sub_cat_id,
                cats1.cat_id as cat_id,
                cats1.cat_name as cat_name,
                cats1.cat_image as cat_image,
                cats1.cat_is_catofmonth as cat_is_catofmonth
            FROM 
                categories cats
            JOIN 
                categories cats1 ON cats.parent_cat_id = cats1.cat_id     
            WHERE 
                cats.cat_id = " . $sub_cat_id; 

    $result = $this->query($sql);    
    
    // Initialize an array to store product details
    $category = [];
    
    if ($result) {
        // Assuming $result is an associative array of rows
        foreach ($result as $row) {
                // Store category name and id
                $category['cat_id'] = $row['cat_id'];
                $category['cat_name'] = $row['cat_name'];
                $category['cat_image'] = $row['cat_image'];
                $category['cat_is_catofmonth'] = $row['cat_is_catofmonth'];
                $category['sub_cat_id'] = $row['sub_cat_id'];
                $category['sub_cat_name'] = $row['sub_cat_name'];
        }
    }
    // echo '<pre/>';
    // print_r($category);
    // echo "</pre>";
    // exit();
    return $category;
    }


    public function isSubCategoryExist($name, $cat_id) {
        // SQL query to select data
        $sql = "SELECT cat_id FROM categories WHERE cat_name = '" . $name . "' AND parent_cat_id = " . $cat_id;
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        return $row['cat_id'];
    }

    public function catIdForSubCat($sub_cat_id) {
        // SQL query to select data
        $sql = "SELECT parent_cat_id FROM categories WHERE cat_id = " . $sub_cat_id;
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        return $row['parent_cat_id'];
    }


    public function getCategoryIdFromName($name){ // there was funtion called categoryId that is replaces
        // SQL query to select data
        $sql = "SELECT cat_id FROM categories WHERE cat_name = '" . $name . "' AND parent_cat_id = 0";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        if($row == null)
            return 0;
        else
            return $row['cat_id'];
    }


    public function addToCategory($name, $sub_cat, $image, $catmonth) {

        $search_cat_id = $this->getCategoryIdFromName($name);
        // if name (cat) not exist
        if ($search_cat_id == 0 && $image == null) {
            $sqlMainCat = "INSERT INTO categories (cat_name, parent_cat_id, cat_image, cat_is_catofmonth) VALUES ('" . $name . "', '" . 0 . "', '" . $image . "', " .$catmonth . ")";
            $result = $this->query($sqlMainCat);
            $cat_id = $this->conn->insert_id;
            $sqlSubCat = "INSERT INTO categories (cat_name, parent_cat_id, cat_image, cat_is_catofmonth) VALUES ('" . $sub_cat . "', '" . $cat_id . "', '" . null . "', " . 0 . ")";
            $this->query($sqlSubCat);
            return $cat_id;
        }

        if ($search_cat_id > 0 && $image == null) {
            $sqlSubCat = "INSERT INTO categories (cat_name, parent_cat_id, cat_image, cat_is_catofmonth) VALUES ('" . $sub_cat . "', '" . $search_cat_id . "', '" . null . "', " . 0 . ")";
            $this->query($sqlSubCat);
            return 1;
        }
        if ($result && $image != null) {    
            return 0;
        }

        if ($result == null) {    
            return -1;
        } 
         

    }
 
    public function updateCategory($name, $cat_id, $sub_cat, $sub_cat_id, $image, $catmonth){
        echo $name . " <br>";
        echo $cat_id . " <br>";
        echo $sub_cat . " <br>";
        echo $sub_cat_id . " <br>";
        echo $image . " <br>";
        echo $catmonth . " <br>";
        echo '<pre/>';
        print_r($name . " <br>" . $cat_id . " <br>". $sub_cat . " <br>". $sub_cat_id . " <br>". $image . " <br>". $catmonth . " <br>");

        // image location update while adding new category
        if ($name == null && $cat_id != null && $sub_cat == null && $sub_cat_id == null && $image != null && $catmonth == null){
            $sql = "UPDATE categories SET cat_image = '" . $image . "' WHERE cat_id = ". $cat_id;
        }
        // adding new sub category
        if ($name == null && $cat_id != null && $sub_cat != null && $sub_cat_id == null && $image == null && $catmonth == null){
            $sql = "INSERT INTO categories (cat_name, parent_cat_id) VALUES ('" . $sub_cat . "', " . $cat_id . ")";
        }
        // updates with image    
        if ($name != null && $cat_id != null && $sub_cat != null && $sub_cat_id != null && $image != null){
            $sql1 = "UPDATE categories SET cat_name = '" . $sub_cat . "' WHERE cat_id = ". $sub_cat_id;
            $this->query($sql1);
            $sql = "UPDATE categories SET cat_name = '" . $name . "', cat_image = '" . $image . "', cat_is_catofmonth = " . $catmonth . " WHERE cat_id = ". $cat_id;
        }
        // updates without image 
        if ($name != null && $cat_id != null && $sub_cat != null && $sub_cat_id != null && $image == null){
            $sql1 = "UPDATE categories SET cat_name = '" . $sub_cat . "' WHERE cat_id = ". $sub_cat_id;
            $this->query($sql1);
            $sql = "UPDATE categories SET cat_name = '" . $name . "', cat_is_catofmonth = " . $catmonth . " WHERE cat_id = ". $cat_id;
        }

        $result = $this->query($sql);
        return $result;
    } 

    public function addToProduct($productName, $image, $productPrice, $productSize, $selectedCategories, 
                                    $isShowed, $isFeatured, $productDescription, $productSpecification) {
                                                    
        $sql = "INSERT INTO products (p_name, p_image, p_price, p_sizes, p_is_show, p_short_description, p_specification) 
                    VALUES ('" . $productName . "', '" . $image . "', '" . $productPrice .  "', '" . $productSize . "', " . $isShowed . ", " . $isFeatured . ", '" . $productDescription ."', '" . $productSpecification . "')";

        $result = $this->query($sql);
        
        if ($result && $image == null) {
            $pod_id = $this->conn->insert_id;
            $this->updateProductCategories($selectedCategories, $pod_id);
            return $pod_id;
        }
        if ($result && $image != null) {    
            return $result;
        }    

    }  

    public function updateProductCategories($selectedCategories, $productId){
        //delete
        $sql = "DELETE FROM product_categories WHERE p_id = " . $productId;
        $this->query($sql);
  
        //add
        foreach ($selectedCategories as $category) {
            $sql = "INSERT INTO product_categories (p_id, cat_id) VALUES (" . $productId . "," . $category . ")";
            $this->query($sql);
        }
     
    }

    public function updateProduct($productName, $image, $productPrice, $productSize, $selectedCategories, 
                                    $isShowed, $isFeatured, $productDescription, $productSpecification, $productId) {
        // SQL query to select data
        if($productName != null && $image == null && $productPrice != null && $productSize != null && $productId != null){
            $sql = "UPDATE products SET p_name = '" . $productName . "', p_price = '" . $productPrice . "', p_sizes = '" . $productSize 
                 . "', p_is_show = " . $isShowed . ", p_is_featured = " . $isFeatured . ", p_short_description = '" . $productDescription . "', p_specification = '" . $productSpecification . "' WHERE p_id = ". $productId;
        }      
        
        if($productName == null && $image != null && $productId != null){
            $sql = "UPDATE products SET p_image = '" . $image . "' WHERE p_id = ". $productId;
        }

        if($productName != null && $image != null && $productPrice != null && $productSize != null && $productId != null){
            $sql = "UPDATE products SET p_name = '" . $productName . "', p_image = '" . $image . "', p_price = '" . $productPrice . "', p_sizes = '" . $productSize 
                 . "', p_is_show = " . $isShowed . ", p_is_featured = " . $isFeatured . ", p_short_description = '" . $productDescription . "', p_specification = '" . $productSpecification . "' WHERE p_id = ". $productId;
        }

        $result = $this->query($sql);

        if (!empty($selectedCategories)) {
                $this->updateProductCategories($selectedCategories, $productId);
        }

        return $result;
    }
    
    public function getProduct($id) {
        // SQL query to select data
        $sql = "SELECT * FROM categories WHERE cat_id = ". $id;
        $result = $this->query($sql);
        return $result;
    }

// SQL query for admin

public function addOderItem($o_id, $u_ip, $p_id, $o_unit, $p_size, $c_notes) {

    $sql = "INSERT INTO order_table ( o_id,  o_date , u_ip ,  p_id ,  o_unit ,  p_size ,  c_notes ) 
            VALUES ('" . $o_id . "', now() ,'" . $u_ip . "','". $p_id . "','". $o_unit . "','". $p_size . "','". $c_notes . "')";
    $result = $this->query($sql);

    if ($result) {    
        return $o_id;
    }else
        return 0;
}




    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>