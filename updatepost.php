<?php
include('config/sqli_db_connect.php');

// Get all categories and display to Dropdownlist
$sql = 'SELECT id, name FROM categories ORDER BY name';
$cat_result = mysqli_query($conn, $sql);
// Fetch the resulting rows as an array
$cats = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);


$id = $title = $author = $body = '';
$category_id ='';
$titleErr = $authorErr = $bodyErr = '';

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
                p.created_at 
            FROM posts P JOIN categories c
            ON p.category_id = c.id
            WHERE p.id = $id";
    
    // Get query result
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);

    $title = $post['title'];
    $author = $post['author'];
    $body = $post['body'];
    $category_id = $post['category_id'];

    // mysqli_free_result($result);
    // mysqli_close($conn);
}




// Form Submit
if(isset($_POST['submit'])){
    // Validate title
    if (empty($_POST['title'])) {
        $titleErr = 'Post title is required';
    } else {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
    }

    // Validate author
    if (empty($_POST['author'])) {
        $authorErr = 'Author is required';
    } else {
        $author = mysqli_real_escape_string($conn, $_POST['author']);
    }

    // Validate body
    if (empty(strip_tags($_POST['body']))) {
        $bodyErr = 'Description is required';
    } else {
        $body = mysqli_real_escape_string($conn, $_POST['body']);
    }

    //  Category id
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    if ($title != '' && $author != '' && $body != '' && $category_id != '') {
        // Create sql
        $sql = "UPDATE posts
            SET title = '$title', body = '$body', author = '$author', category_id = '$category_id'
            WHERE id = $id";

        // Save DB and check
        if (mysqli_query($conn, $sql)) {
            // Success
            header('Location: index.php');
        } else {
            echo 'Query error: ' . mysqli_error($conn); 
        } 
    } else {
        echo 'There is error in the form';
    }
}


?>

<?php include('templates/header.php'); ?>
    <h2>Edit Post</h2>
    <!-- <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="mt-4 w-75"> -->
    <form action="<?php echo "updatepost.php?id=$id" ?>" method="POST" class="mt-4 w-75">
    <div class="form-group mb-4">
        <label for="title">Post Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $title ?? ''; ?>"/>
        <div class="has-error text-danger">
            <?php echo $titleErr; ?>
        </div>
    </div>
    <div class="form-group mb-4">
        <label for="author">Author</label>
        <input type="text" name="author" class="form-control" value="<?php echo $author ?? ''; ?>"/>
        <div class="has-error text-danger">
            <?php echo $authorErr; ?>
        </div>
    </div>

    <div class="form-group mb-4">
        <label for="body">Category</label>
        <select class="form-select p-1 ml-2" name="category_id">
            <option value="">--Please choose a category--</option>
            <?php foreach($cats as $cat): ?>
                <option value="<?php echo $cat['id'] ?>" <?php echo ($cat['id'] == $category_id) ? 'selected': null ?>><?php echo htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-4">
        <label for="body">Description</label>
        <textarea name="body" class="form-control" rows="5" ><?php echo $body ?? ''; ?></textarea>
        <div class="has-error text-danger">
            <?php echo $bodyErr ?? ''; ?>
        </div>
    </div>
    <div class="mb-3">
        <input type="submit" Value="Update" name="submit"  class="btn btn-success"/>
    </div>
    </form>
<?php include('templates/footer.php'); ?>