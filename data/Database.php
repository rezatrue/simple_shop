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

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
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

    private function isTextclean($text){
        $codeLatters = ['`','!','@','#','$','%','^','&','*','_','+','-','=','/','?','<','>'];
        return true;
    }

    public function login($user, $password) {
        if(!$this->isTextclean($user))
            return;
        $sql = 'SELECT u_id, u_full_name, u_role FROM user WHERE u_name = "' .$user. '" AND u_password = "' .$password. '"' ;
        $result = $this->fetchAll($this->query($sql));
        if(!$result)
            return null;
        $user = [
            'id' => $result[0]['u_id'],
            'name' => $result[0]['u_full_name'],
            'role' => $result[0]['u_role']
        ];
        return $user; 
    }    


    public function queryCountForListPage($user) {
        // SQL query to select data
        if($user === 'admin')
            $sql = "SELECT COUNT(*) AS total_count FROM products"; 
        else
            $sql = "SELECT COUNT(*) AS total_count FROM products WHERE p_is_show = True"; // visitor will only see which is for dispaly
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function productCountForMainCat($cat_id) {
        // SQL query to select data

        $sql = "SELECT 
            COUNT(*) as total_count 
        FROM 
            product_categories pc
        JOIN 
            products p ON pc.p_id = p.p_id
        JOIN 
            categories c ON pc.cat_id = c.cat_id             
        WHERE 
            c.parent_cat_id = " . $cat_id . " 
        And
            p.p_is_show = True";

        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
    }


    // Nmae changed previous one was queryCountFor(
    public function productCountForCat($user, $cat_id) {
        // SQL query to select data
        if($user === 'admin')
            $sql = "SELECT COUNT(*) as total_count FROM product_categories where cat_id = " . $cat_id ;
        else
            $sql = "SELECT 
                COUNT(*) as total_count 
            FROM 
                product_categories pc
            JOIN 
                products p ON pc.p_id = p.p_id
            where 
                pc.cat_id = " . $cat_id . " 
            And
                p.p_is_show = True";

        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
    }


    public function queryForListPage($user, $page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        if($user === 'admin')
            $sql = "SELECT * FROM products LIMIT " .$itemsPerPage ." OFFSET ". $offset;
        else
            $sql = "SELECT * FROM products WHERE p_is_show = True LIMIT " .$itemsPerPage ." OFFSET ". $offset;
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
                        'p_images' => json_decode($row['p_images'], true),
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

    public function queryAllSubcatProductsForCat($cat_id, $page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;

        $sql = "SELECT 
            p.p_id, p.p_name, p.p_images, p.p_price, p.p_sizes
        FROM 
            products p
        JOIN 
            product_categories pc ON p.p_id = pc.p_id
        JOIN 
            categories c ON pc.cat_id = c.cat_id          
        WHERE 
            c.parent_cat_id = " . $cat_id .
        " AND
            p.p_is_show = true     
        LIMIT "
            . $itemsPerPage .
        " OFFSET "
            . $offset;
       
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
                        'p_images' => json_decode($row['p_images'], true),
                        'p_price' => $row['p_price'],
                        'p_sizes' => $row['p_sizes']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($relatedProductList);
        // echo '<pre/>';
        // exit();
        return $relatedProductList;

    }

    // repalce for "public function queryFor("
    public function queryForRelatedProduct($user, $cat_id, $page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;

        if($user === 'admin'){
            $sql = "SELECT 
                p.p_id, p.p_name, p.p_images, p.p_price, p.p_sizes
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
        }else{
            $sql = "SELECT 
                p.p_id, p.p_name, p.p_images, p.p_price, p.p_sizes
            FROM 
                products p
            JOIN 
                product_categories pc ON p.p_id = pc.p_id   
            WHERE 
                pc.cat_id = " . $cat_id . "
            AND
                p.p_is_show = true     
            LIMIT "
                . $itemsPerPage .
            " OFFSET "
                . $offset;
        }
        
        
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
                        'p_images' => json_decode($row['p_images'], true),
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
                    $productDetails['p_images'] = json_decode($row['p_images'], true);
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

    public function getProductWithPartialName($query) {
        // SQL query to select data
        $sql = "SELECT * FROM products WHERE p_name LIKE ? ";
        $stmt = $this->prepare($sql);
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    } 

    public function updateOrdertUnit($oid, $pid, $units) {
        // SQL query to select data
        $sql = "UPDATE order_table SET o_unit = ? WHERE p_id = ? AND o_id = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("dis", $units, $pid, $oid); // d for daouble & i for number
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 
    } 

    public function deleteOrdertItem($oid, $pid) {
        // SQL query to select data
        $sql = "DELETE FROM order_table WHERE p_id = ? AND o_id = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("is", $pid, $oid); // d for double & i for number
        if ($stmt->execute()) {
            return true; // Or any other success indication
        } else {
            // Log or handle the error
            error_log("Delete failed: " . $stmt->error);
            return false;
        }           
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

    public function isMainCat($cat_id) {
        // SQL query to select data
        $sql = "SELECT COUNT(cat_name) as row_count FROM categories WHERE cat_id = '" . $cat_id . "' AND parent_cat_id = '0'";    
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $rowCount = (int)$row['row_count'];
        if($rowCount > 0)
            return 1;
        else
            return 0;
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
                                                    
        $sql = "INSERT INTO products (p_name, p_images, p_price, p_sizes, p_is_show, p_is_featured, p_short_description, p_specification) 
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
            $sql = "UPDATE products SET p_images = '" . $image . "' WHERE p_id = ". $productId;
        }

        if($productName != null && $image != null && $productPrice != null && $productSize != null && $productId != null){
            $sql = "UPDATE products SET p_name = '" . $productName . "', p_images = '" . $image . "', p_price = '" . $productPrice . "', p_sizes = '" . $productSize 
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
public function getOrderDateTime($o_id) {
    $sql = "SELECT o_date FROM order_table WHERE o_id = '" . $o_id . "'";
    $result = $this->query($sql);
        
    if ($result) { 
        $row = mysqli_fetch_assoc($result);
        $o_date = $row['o_date']; 
        return $o_date;
    }else
        return '';
}

public function addOderItem($o_id, $o_date, $u_ip, $p_id, $o_unit, $p_size, $c_notes) {

    if($o_date)
        $sql = "INSERT INTO order_table ( o_id,  o_date , u_ip ,  p_id ,  o_unit ,  p_size ,  c_notes ) 
            VALUES ('" . $o_id . "', '". $o_date . "' ,'" . $u_ip . "','". $p_id . "','". $o_unit . "','". $p_size . "','". $c_notes . "')";
    else
        $sql = "INSERT INTO order_table ( o_id,  o_date , u_ip ,  p_id ,  o_unit ,  p_size ,  c_notes ) 
            VALUES ('" . $o_id . "', now() ,'" . $u_ip . "','". $p_id . "','". $o_unit . "','". $p_size . "','". $c_notes . "')";        
    $result = $this->query($sql);
    if ($result) {    
        return $o_id;
    }else
        return 0;
}

    public function queryForOrderListPage($page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    o.o_id,
                    o.o_date,
                    o.u_ip,
                    dd.o_name,
                    dd.o_phone,
                    dd.o_address,
                    dd.o_notes,
                    SUM(o.o_unit * p.p_price) AS order_amount
                FROM 
                    order_table o
                JOIN 
                    products p ON o.p_id = p.p_id
                LEFT JOIN 
                    delivery_details dd ON o.o_id = dd.o_id
                WHERE 
                    dd.o_is_confirmed = 0        
                GROUP BY 
                    o.o_id, o.o_date, dd.o_name, dd.o_phone, dd.o_address, dd.o_notes 
                ORDER BY 
                    o.o_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
                    
        $result = $this->query($sql);

        $relatedOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $relatedOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_date' => $row['o_date'],
                        'u_ip' => $row['u_ip'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'o_notes' => $row['o_notes'],
                        'order_amount' => $row['order_amount']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($relatedOrderList);
        // echo '<pre/>';
        //exit();
        return $relatedOrderList; 
    }

    public function queryCountForOrderListPage() {
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM delivery_details WHERE o_is_confirmed = 0";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function queryForPartialOrderIdListPage($like_o_id, $page, $itemsPerPage) {
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    o.o_id,
                    o.o_date,
                    o.u_ip,
                    dd.o_name,
                    dd.o_phone,
                    dd.o_address,
                    dd.o_notes,
                    SUM(o.o_unit * p.p_price) AS order_amount
                FROM 
                    order_table o
                JOIN 
                    products p ON o.p_id = p.p_id
                LEFT JOIN 
                    delivery_details dd ON o.o_id = dd.o_id    
                WHERE
                    dd.o_is_confirmed = 0
                AND 
                    o.o_id LIKE '%" . $like_o_id . "%'     
                GROUP BY 
                    o.o_id, o.o_date, dd.o_name, dd.o_phone, dd.o_address, dd.o_notes
                ORDER BY 
                    o.o_date ASC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
                    
        $result = $this->query($sql);

        $relatedOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $relatedOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_date' => $row['o_date'],
                        'u_ip' => $row['u_ip'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'o_notes' => $row['o_notes'],
                        'order_amount' => $row['order_amount']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($relatedOrderList);
        // echo '<pre/>';
        //exit();
        return $relatedOrderList; 
    }

    public function queryCountForPartialOrderIdListPage($like_o_id) {
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM delivery_details WHERE o_is_confirmed = 0 AND o_id LIKE '%" . $like_o_id . "%'";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function orderDetails($o_id) {
        // SQL query to select data
        $sql = "SELECT 
                o.p_id,
                o.o_unit,
                o.p_size,
                o.o_date,
                o.c_notes,
                p.p_name,
                p.p_price,
                (o.o_unit * p.p_price) AS total_amount
            FROM 
                order_table o
            INNER JOIN 
                products p ON o.p_id = p.p_id
            WHERE
                o.o_id = '" .$o_id . "' ORDER BY o.o_date DESC"; 
                    
        $result = $this->query($sql);

        $orderDetails['item'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['p_id'])) {
                    // Store product name and price
                    $orderDetails['item'][] = [
                        'p_id' => $row['p_id'],
                        'o_unit' => $row['o_unit'],
                        'p_size' => $row['p_size'],
                        'c_notes' => $row['c_notes'],
                        'p_name' => $row['p_name'],
                        'o_date' => $row['o_date'],
                        'p_price' => $row['p_price'],
                        'total_amount' => $row['total_amount']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($orderDetails);
        // echo '<pre/>';
        // exit();
        return $orderDetails; 
    }

    public function deliveryDetails($o_id, $name, $phone, $address, $notes) {

        $sql = "INSERT INTO delivery_details (o_id, o_name, o_phone, o_address, o_notes) VALUES (?,?,?,?,?)";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("sssss", $o_id, $name, $phone, $address, $notes); // d for daouble & i for number
        if ($stmt->execute()) {
            return true; // Or any other success indication
        } else {
            return false;
        }
    }

    public function updateDeliveryDetails($o_id, $name, $phone, $address, $notes) {

        $sql = "UPDATE delivery_details SET o_name = ? , o_phone = ?, o_address = ?, o_notes = ? WHERE o_id = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("sssss", $name, $phone, $address, $notes, $o_id); // d for daouble & i for number
        if ($stmt->execute()) {
            return true; // Or any other success indication
        } else {
            return false;
        }
    }

    public function getDeliveryDetails($o_id) {

        $sql = "SELECT * FROM delivery_details WHERE o_id = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("s", $o_id); // d for daouble & i for number
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; // Store each row in an array
        }
        return $data;
        
    }

    public function cancelOrder($o_id, $notes) {
        $sql = "SELECT * FROM delivery_details WHERE o_id = ?";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("s", $o_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $name = '';
        $phone = '';
        $address = '';
        while ($row = $result->fetch_assoc()) {
            $name = $row['o_name'] ;
            $phone = $row['o_phone'];
            $address = $row['o_address'];
        }
        $stmt->close();

        $sql1 = "SELECT 
                ot.p_id,
                ot.o_unit,
                ot.p_size,
                ot.c_notes,
                ot.o_date,
                p.p_name,
                p.p_price,
                (ot.o_unit * p.p_price) AS total_amount
            FROM 
                order_table ot
            INNER JOIN 
                products p ON ot.p_id = p.p_id
            WHERE
                ot.o_id = ? ORDER BY ot.o_date DESC";    
        
        $stmt1 = $this->prepare($sql1);
        $stmt1->bind_param("s", $o_id);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $items = [];
        $date = '';
        while ($row = $result1->fetch_assoc()) {
            if(!$date) $date = $row['o_date'];
            $items[] = "(" . $row['p_id'] . ")-" . $row['p_name'] . "[" . $row['p_size'] . "]-" 
            . $row['p_price'] ."x" . $row['o_unit'] . "=" . $row['total_amount'] . "[" . $row['c_notes'] . "]";
        }
        $stmt1->close();
        $jsonItems = json_encode($items);

        $sql2 = "INSERT INTO cancel_order (o_id, o_date, o_name, o_phone, o_address, c_notes, c_items) 
                        VALUES (?,?,?,?,?,?,?)";
        $stmt2 = $this->prepare($sql2);
        $stmt2->bind_param("sssssss", $o_id, $date, $name, $phone, $address, $notes, $jsonItems);
        //$stmt2->execute();        
        //$result = $stmt2->get_result();

        if($stmt2->execute()){
            $this->removeOrder($o_id);
        }
        
    }

    public function removeOrder($o_id){
            $sql3 = "DELETE FROM delivery_details WHERE o_id = '" . $o_id . "'";
            $this->query($sql3);
            $sql4 = "DELETE FROM order_table WHERE o_id = '" . $o_id . "'";
            $this->query($sql4);
    }
    

    public function cancelOrderListPage($page, $itemsPerPage) { // queryForOrderListPage
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    co.o_id,
                    co.o_date,
                    co.o_name,
                    co.o_phone,
                    co.o_address,
                    co.c_notes,
                    co.c_items
                FROM 
                    cancel_order co
                ORDER BY 
                    co.o_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
                    
        $result = $this->query($sql);

        $cancelOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $cancelOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_date' => $row['o_date'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'c_notes' => $row['c_notes'],
                        'c_items' => json_decode($row['c_items'], true)
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($cancelOrderList);
        // echo '<pre/>';
        //exit();
        return $cancelOrderList; 
        
    }


    public function countForCancelOrderListPage() { //queryCountForOrderListPage
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM cancel_order";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function partialCancelOrderIdListPage($like_o_id, $page, $itemsPerPage) { // queryForPartialOrderIdListPage
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    co.o_id,
                    co.o_date,
                    co.o_name,
                    co.o_phone,
                    co.o_address,
                    co.c_notes,
                    co.c_items
                FROM 
                    cancel_order co    
                WHERE 
                    co.o_id LIKE '%" . $like_o_id . "%'  
                ORDER BY 
                    co.o_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
                    
        $result = $this->query($sql);

        $cancelOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $cancelOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_date' => $row['o_date'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'c_notes' => $row['c_notes'],
                        'c_items' => json_decode($row['c_items'], true)
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($cancelOrderList);
        // echo '<pre/>';
        //exit();
        return $cancelOrderList; 
    }

    public function countForPartialCancelOrderIdListPage($like_o_id) { //queryCountForPartialOrderIdListPage
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM cancel_order WHERE o_id LIKE '%" . $like_o_id . "%'";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function confirmOrderListPage($page, $itemsPerPage) { // cancelOrderListPage
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    dd.o_id,
                    dd.o_name,
                    dd.o_phone,
                    dd.o_address,
                    dd.o_notes,
                    dd.o_is_delivered,
                    ot.o_date
                FROM 
                    delivery_details dd
                JOIN 
                    order_table ot ON dd.o_id = ot.o_id
                WHERE 
                    dd.o_is_confirmed = 1
                GROUP BY
                    ot.o_id           
                ORDER BY 
                    ot.o_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
                    
        $result = $this->query($sql);

        $confirmOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $item = $this->orderDetails($row['o_id']);
                    $confirmOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_date' => $row['o_date'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'o_notes' => $row['o_notes'],
                        'c_items' => $item,
                        'o_is_delivered' => $row['o_is_delivered']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($confirmOrderList);
        // echo '<pre/>';
        // exit();
        return $confirmOrderList; 
        
    }


    public function countForConfirmOrderListPage() { //countForCancelOrderListPage
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM delivery_details WHERE o_is_confirmed = 1";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function confirmOrders($o_id) { 
        // SQL query to select data
        $sql = "UPDATE delivery_details SET o_is_confirmed = 1 WHERE o_id = '".$o_id."'" ;
        $result = $this->query($sql);
        return $result ? 1 : 0;
    }

    public function confirmDelivery($o_id, $status) { 
        // SQL query to select data
        $sql = "UPDATE delivery_details SET o_is_delivered = ".$status." WHERE o_id = '".$o_id."'" ;
        $result = $this->query($sql);
        return $result ? 1 : 0;
    }

    public function partialConfirmOrderIdListPage($like_o_id, $page, $itemsPerPage) { // partialCancelOrderIdListPage
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    dd.o_id,
                    dd.o_name,
                    dd.o_phone,
                    dd.o_address,
                    dd.o_notes,
                    dd.o_is_delivered,
                    ot.o_date
                FROM 
                    delivery_details dd
                JOIN 
                    order_table ot ON dd.o_id = ot.o_id
                WHERE 
                    dd.o_is_confirmed = 1
                AND
                    dd.o_id LIKE '%" . $like_o_id . "%'" .     
                " GROUP BY
                    ot.o_id           
                ORDER BY 
                    ot.o_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
         
        $result = $this->query($sql);

        $confirmOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $item = $this->orderDetails($row['o_id']);
                    $confirmOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_date' => $row['o_date'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'o_notes' => $row['o_notes'],
                        'c_items' => $item,
                        'o_is_delivered' => $row['o_is_delivered']
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($confirmOrderList);
        // echo '<pre/>';
        // exit();
        return $confirmOrderList; 
    }    

    public function countForPartialConfirmOrderIdListPage($like_o_id) { //countForPartialCancelOrderIdListPage
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM delivery_details WHERE o_is_confirmed = 1 AND o_id LIKE '%" . $like_o_id . "%'";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function addToClosedOrders($row) { 
        
        // Convert the array to a JSON string
        $itemsString = json_encode($row['c_items']['item']);
        $sql = "INSERT INTO closed_orders(o_id, o_name, o_phone, o_address, o_notes, o_date, d_date, o_items) 
        VALUES (? , ? , ? , ? , ? , ? , now() , ?)";
        $stmt = $this->prepare($sql);
        $stmt->bind_param("sssssss", $row['o_id'], $row['o_name'], $row['o_phone'], $row['o_address'], $row['o_notes'], $row['o_date'], $itemsString); 
        $stmt->execute();
        $result = $stmt->get_result();
        return $result; 

    }

    public function closedOrderListPage($page, $itemsPerPage) { // confirmOrderListPage
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                     *
                FROM 
                    closed_orders         
                ORDER BY 
                    d_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
                    
        $result = $this->query($sql);

        $closedOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $closedOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'o_notes' => $row['o_notes'],
                        'o_date' => $row['o_date'],
                        'd_date' => $row['d_date'],
                        'o_items' => json_decode($row['o_items'], true)
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($closedOrderList);
        // echo '<pre/>';
        // exit();
        return $closedOrderList; 
        
    }

    public function countForClosedOrderListPage() { //countForConfirmOrderListPage
        // SQL query to select data
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM closed_orders";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

    public function partialClosedOrderIdListPage($like_o_id, $page, $itemsPerPage) { // partialConfirmOrderIdListPage
        // SQL query to select data
        $offset = ($page - 1) * $itemsPerPage ;
        $sql = "SELECT 
                    *
                FROM 
                    closed_orders
                WHERE
                    o_id LIKE '%" . $like_o_id . "%'" .     
                " ORDER BY 
                    d_date DESC
                LIMIT " 
                    .$itemsPerPage .
                " OFFSET "
                    . $offset; 
         
        $result = $this->query($sql);

        $closedOrderList['order'] = [];
        if ($result) {
            // Assuming $result is an associative array of rows
            foreach ($result as $row) {
                // Check if the product already exists in the array
                if (isset($row['o_id'])) {
                    // Store product name and price
                    $closedOrderList['order'][] = [
                        'o_id' => $row['o_id'],
                        'o_name' => $row['o_name'],
                        'o_phone' => $row['o_phone'],
                        'o_address' => $row['o_address'],
                        'o_notes' => $row['o_notes'],
                        'o_date' => $row['o_date'],
                        'd_date' => $row['d_date'],
                        'o_items' => json_decode($row['o_items'], true)
                    ];
                }  
            }
        }
        // echo '<pre>';
        // print_r($closedOrderList);
        // echo '<pre/>';
        // exit();
        return $closedOrderList; 
    } 

    public function countForPartialClosedOrderIdListPage($like_o_id) { //countForPartialConfirmOrderIdListPage
        // SQL query to select data 
        $sql = "SELECT COUNT(DISTINCT o_id) AS total_count FROM closed_orders WHERE o_id LIKE '%" . $like_o_id . "%'";
        $result = $this->query($sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount = (int)$row['total_count'];
        return $totalCount;
        // $totalItems = mysqli_num_rows($result);
    }

}

?>