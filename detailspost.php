<?php
include('config/sqli_db_connect.php');

// GET id param
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query
    $sql = "SELECT 
                c.name AS category_name, 
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at,
                p.img 
            FROM posts P JOIN categories c
            ON p.category_id = c.id
            WHERE p.id = $id";
    
    // Get query result
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_close($conn);
}

// DELETE current post
if (isset($_POST['delete'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_delete']);
    $sql = "DELETE FROM posts WHERE id=$id_to_delete";

    if (mysqli_query($conn, $sql)) {
        // Success
        header('Location: index.php');
    } else {
        // Error
        echo 'Query error: ' . mysqli_error($conn);
    }
}


?>

<?php include('templates/header.php'); ?>

    <div class="container">
        <?php if($post): ?>
            <div class="card" >
                <?php if ($post['img'] != '') : ?>
                    <?php $src = "uploads/". $post['img'] ?>
                    <img class="card-img-top" src="<?php echo $src ?>" alt="Card image" style="height: 350px; object-fit:cover;">
                <?php endif; ?>

                <div class="card-body">
                    <h4 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h4>
                    <p class="card-subtitle">Created by: <?php echo htmlentities($post['author']); ?></p>
                    <p class="text-muted"><?php $d=date_create($post['created_at']); echo date_format($d, "Y/m/d H:i"); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($post['body']); ?></p>

                    <!-- DELETE POST -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <a href="/php-crash/api_myblog/index.php" class="btn btn-outline-secondary">Go Back</a>
                        <input type="hidden" name="id_to_delete" value="<?php echo $post['id']; ?>">
                        <input type="submit" name="delete" value="Delete" class="btn btn-outline-danger" onClick="return confirm('Are you sure you want to delete this post?')">
                        <a href="/php-crash/api_myblog/updatepost.php?id=<?php echo $post['id']; ?>" class="btn btn-outline-success">Update</a>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <h5>Searched post does not exist</h5>
        <?php endif; ?>
    </div>



<?php include('templates/footer.php'); ?>