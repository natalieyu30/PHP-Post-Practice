<?php
include('config/sqli_db_connect.php');
// Query
$sql = 
    'SELECT 
        c.name AS category_name, 
        p.id,
        p.category_id,
        p.title,
        p.body,
        p.author,
        p.created_at 
    FROM posts P JOIN categories c
    ON p.category_id = c.id
    ORDER BY p.created_at DESC';

$result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Free from memory
mysqli_free_result($result);

// Close connection
mysqli_close($conn);
?>


<?php include('templates/header.php'); ?>
<h2>Posts</h2>

<?php foreach($posts as $item): ?>
    <div class="card my-3 w-75">
        <div class="card-header">
            <?php echo htmlspecialchars($item['category_name']); ?>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
            <span class="card-subtitle mb-2 text-muted">
                By <?php echo htmlspecialchars($item['author']); ?> on <?php echo $item['created_at']; ?>
            </h6>
            <p class="card-text">
                <?php echo htmlspecialchars($item['body']); ?>
            </p>
            <a href="/php-crash/api_myblog/detailspost.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-dark">Go Details</a>
        </div>
    </div>
<?php endforeach; ?>


<?php include('templates/footer.php'); ?>