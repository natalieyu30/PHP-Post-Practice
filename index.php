<?php
include('config/sqli_db_connect.php');

// Get all categories and display to Dropdownlist
$catsql = 'SELECT id, name FROM categories ORDER BY name';

$cat_result = mysqli_query($conn, $catsql);

// Fetch the resulting rows as an array
$cats = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);

$sql = 
"SELECT 
    c.name AS category_name, 
    p.id,
    p.category_id,
    p.title,
    p.author,
    p.body,
    p.created_at,
    p.img 
FROM posts P JOIN categories c
ON p.category_id = c.id
ORDER BY p.created_at DESC";

$result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(isset($_POST['submit'])){
    $cat_id = $_POST['category_id'];
    // Query
    if ($cat_id =="") {
        $sql = "SELECT 
            c.name AS category_name, 
            p.id,
            p.category_id,
            p.title,
            p.author,
            p.body,
            p.created_at,
            p.img 
        FROM posts P JOIN categories c
        ON p.category_id = c.id
        ORDER BY p.created_at DESC";
    } else {
        $sql = 
        "SELECT 
            c.name AS category_name, 
            p.id,
            p.category_id,
            p.title,
            p.author,
            p.body,
            p.created_at,
            p.img 
        FROM posts P JOIN categories c
        ON p.category_id = c.id
        WHERE c.id = $cat_id
        ORDER BY p.created_at DESC";
    }

    $result = mysqli_query($conn, $sql);

    // Fetch the resulting rows as an array
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Free from memory
    mysqli_free_result($result);

    // Close connection
    mysqli_close($conn);
}

function shorten_string($string, $wordsreturned)
{
    $retval = $string;  //  Just in case of a problem
    $array = explode(" ", $string);
    /*  Already short enough, return the whole thing*/
    if (count($array)<=$wordsreturned)
    {
        $retval = $string;
    }
    /*  Need to chop of some words*/
    else
    {
        array_splice($array, $wordsreturned);
        $retval = implode(" ", $array)." ...";
    }
    return $retval;
}


?>


<?php include('templates/header.php'); ?>
<h2>Posts</h2>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="d-flex a-items-center">
    <select class="form-select p-2 mr-2" name="category_id">
        <option value="">All Categories</option>
        <?php foreach($cats as $cat): ?>
            <option value="<?php echo $cat['id'] ?>"><?php echo htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" class="btn btn-dark" name="submit" value="GO">
</form>
<div class="container">
    <div class="row">
        <?php foreach($posts as $item): ?>
            <div class="my-3 col-12 col-md-6 col-lg-4">
                <div class="card" >
                    <div class="card-header">
                        <?php echo htmlspecialchars($item['category_name']); ?>
                    </div>
                    <?php if ($item['img'] != '') : ?>
                        <?php $src = "uploads/". $item['img'] ?>
                        <img class="card-img-top" src="<?php echo $src ?>" alt="Card image" style="width:100%; object-fit:cover; height: 200px;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <p class="card-subtitle mb-2 text-muted">
                            By <?php echo htmlspecialchars($item['author']); ?> 
                            on <?php $d=date_create($item['created_at']); echo date_format($d, "Y/m/d"); ?>
                        </p>
                        <p class="card-text">
                            <?php echo shorten_string(htmlspecialchars($item['body']), 10); ?>
                        </p>
                        <a href="/php-crash/api_myblog/detailspost.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-dark">Go Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php include('templates/footer.php'); ?>