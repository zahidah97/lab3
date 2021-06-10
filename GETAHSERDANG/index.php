<?php
session_start();
include_once("php/dbconnect.php");
if (!isset($_COOKIE['email'])) {
    echo "<script>loadCookies()</script>";
} else {
    $email = $_COOKIE['email'];
    //add to cart button
    if (isset($_GET['op'])) {
        $prodid = $_GET['prodid'];
        $sqlcheckstock = "SELECT * FROM tbl_product WHERE prid = '$prodid'"; //check product in stock
        $stmt = $conn->prepare($sqlcheckstock);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $stmt->fetchAll();
        foreach ($rows as $product) {
            $quantity = $product['prqty']; //check qty  in stock?
            if ($quantity == 0) {
                echo "<script>alert('Quantity not available');</script>";
                echo "<script> window.location.replace('index.php')</script>";
            } else {
                //continue insert to cart
                $sqlcheckcart = "SELECT * FROM tbl_carts WHERE prid = '$prodid' AND email = '$email'";
                $stmt = $conn->prepare($sqlcheckcart);
                $stmt->execute();
                $number_of_result = $stmt->rowCount();
                if ($number_of_result == 0) { //insert cart if not in the cart
                    $sqladdtocart = "INSERT INTO tbl_carts (email, prid, qty) VALUES ('$email','$prodid','1')";
                    if ($conn->exec($sqladdtocart)) {
                        echo "<script>alert('Success')</script>";
                        echo "<script> window.location.replace('index.php')</script>";
                    } else {
                        echo "<script>alert('Failed')</script>";
                        echo "<script> window.location.replace('index.php')</script>";
                    }
                } else { //update cart if the item already in the cart
                    $sqlupdatecart = "UPDATE tbl_carts SET qty = qty +1 WHERE prid = '$prodid' AND email = '$email'";
                    if ($conn->exec($sqlupdatecart)) {
                        echo "<script>alert('Success')</script>";
                        echo "<script> window.location.replace('index.php')</script>";
                    } else {
                        echo "<script>alert('Failed')</script>";
                        echo "<script> window.location.replace('index.php')</script>";
                    }
                }
            }
        }
    }
}

//search and list products
if (isset($_GET['button'])) {
    $prname = $_GET['prname'];
    $prtype = $_GET['prtype'];
    if ($prtype == "all") {
        $sqlsearch = "SELECT * FROM tbl_product WHERE prname LIKE '%$prname%' ORDER BY created_timestamp DESC";
    } else {
        $sqlsearch = "SELECT * FROM tbl_product WHERE prtype = '$prtype' AND prname LIKE '%$prname%'ORDER BY created_timestamp DESC";
    }
} else {
    $sqlsearch = "SELECT * FROM tbl_product ORDER BY created_timestamp DESC";
}
$stmt = $conn->prepare($sqlsearch);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>MyShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!--Datatable-->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src='js/myscript.js'></script>
</head>

<body onload="loadCookies()">
    <div class="header">
        <a href="#default" class="logo">MyShop</a>
        <div class="header-right">
            <a class="active" href="index.php">Home</a>
            <a href="php/addProduct.php">Add Product</a>
            <a href="index.html">Log Out</a>

        </div>
    </div>

    <h2 class="text-center mt-2">List of Products</h2>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-8">
                            <form action="index.php" method="get">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <input type="text" class="form-control" id="fprname" name="prname" placeholder="Product name..">
                                    </div>
                                    <select class="form-control" id="idprtype" name="prtype">
                                        <option value="all">All</option>
                                        <option value="Face Mask">Face Mask</option>
                                        <option value="Getah Beku">Getah Beku</option>
                                        <option value="Getah Cair">Getah Cair</option>
                                        <option value="Hand Sanitizer">Hand Sanitizer</option>
                                    </select>
                                    <div class="input-group-append">
                                        <input class="btn btn-primary" type="submit" name="button" value="Search">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php

    include_once("php/dbconnect.php");

    try {
        echo "<div class=''>";
        echo "<table class='table table-hover table-bordered'>
        
        
    <tr class='bg-dark text-center'>
    <th class='txt-white'>Picture</th>
    <th class='txt-white'>Item Name</th>
    <th class='txt-white'>Price</th>
    <th class='txt-white'>Type</th>
    </tr>";

        foreach ($rows as $products) {
            echo "<tr class='text-center'>";
            $imgurl = "images/" . $products['picture'];
            echo "<tr class='text-center'><td><img src= '$imgurl' class='primage img-thumbnail data:image/jpeg;base64," . base64_encode($products['picture']). "'width='250' height='250'></td>";
            echo "<td class='text-center'>" . $products['prname'] . "</td>";
            echo "<td class='text-center'>" . $products['prprice'] . "</td>";
            echo "<td class='text-center'>" . $products['prtype'] . "</td>";
           

            echo "</div>";
            echo "</div>";
        }
        echo "</table>";
        echo "</div>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    $conn = null;
    ?>

    <a href="php/addProduct.php" class="float">
        <i class="fa fa-plus my-float"></i>
    </a>
</body>

</html>
