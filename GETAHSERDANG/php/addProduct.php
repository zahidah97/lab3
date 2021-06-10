
<?php
include_once("dbconnect.php");
if (isset($_POST['button'])) {
    $prname = $_POST['prname'];
    $prtype = $_POST['prtype'];
    $prprice = $_POST['prprice'];
    $picture = uniqid() . '.png';

    if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
        $sqlinsertprod =  "INSERT INTO tbl_product(prname, prtype, prprice, picture) VALUES('$prname','$prtype','$prprice','$picture')";
        if ($conn->exec($sqlinsertprod)) {
            uploadImage($picture);
            echo "<script>alert('Success')</script>";
            echo "<script>window.location.replace('../index.php')</script>";
        } else {
            echo "<script>alert('Failed')</script>";
            return;
        }
    } else {
        echo "<script>alert('Image not available')</script>";
        return;
    }
}
function uploadImage($picture)
{
    $target_dir = "../images/";
    $target_file = $target_dir . $picture;
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../js/depositori.js"></script>
    <link rel="stylesheet" href="../css/style.css">
    </style>
</head>

<body>
        <div class="header">
            <a href="#default" class="logo">MyShop</a>
            <div class="header-right">
            <a class="active" href="addProduct.php">Add Product</a>
            <a href="../index.php">Back</a>

        </div>
    </div>
    <center><h2>New Product</h2></center>

    <div class="container">
        <form action="addProduct.php" method="post" enctype="multipart/form-data">
        <div class="row" align="center">
                <img class="imgselection" src="images.png"><br>
                <input type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload" accept="image/*"><br>
            </div>

            <div class="row">
                <div class="col-25">
                    <label for="fprname">Product Name</label>
                </div>
                <div class="col-75">
                    <input type="text" id="fprname" name="prname" placeholder="Product name..">
                </div>
            </div>

            <div class="row">
                <div class="col-25">
                    <label for="prtype">Product Type</label>
                </div>
                <div class="col-75">
                    <select id="idprtype" name="prtype">
                        <option value="Getah Beku">Getah Beku</option>
                        <option value="Getah Cair">Getah Cair</option>
                        <option value="Face Mask">Face Mask</option>
                        <option value="Hand Sanitizer">Hand Sanitizer</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-25">
                    <label for="lprice">Product Price</label>
                </div>
                <div class="col-75">
                    <input type="text" id="idprice" name="prprice" placeholder="Price (RM).." >
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-25">
                    <label for="lprice">Product Quantity</label>
                </div>
                <div class="col-75">
                    <input type="text" id="idprqty" name="prqty" placeholder="Product Quantity.." >
                </div>
            </div> -->

            <div class="row">
                <div class="col-25">
                </div>
                <div class="col-75">
                    <input type="submit" name="button" value="Submit">
                </div>
            </div>
        </form>
    </div>

</body>

</html>