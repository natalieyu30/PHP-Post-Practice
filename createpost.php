<?php
include('config/sqli_db_connect.php');

$sql = 
    'SELECT id, name FROM categories
    ORDER BY name';

$cat_result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$cats = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);

//require('input_validator.php');
$title = $author = $body = '';
$category_id ='1';
$titleErr = $authorErr = $bodyErr = '';



// Form Submit
if(isset($_POST['submit'])){
    // Validate title
    if (empty($_POST['title'])) {
        $titleErr = 'Post title is required';
    } else {
        // $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
    }

    // Validate author
    if (empty($_POST['author'])) {
        $authorErr = 'Author is required';
    } else {
        // $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_EMAIL);
        $author = mysqli_real_escape_string($conn, $_POST['author']);
    }

    // Validate body
    if (empty(strip_tags($_POST['body']))) {
        $bodyErr = 'Description is required';
    } else {
        $body = mysqli_real_escape_string($conn, $_POST['body']);
        //$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    //  Category id
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    echo $category_id;

    if ($title != '' && $author != '' && $body != '') {
        // Create sql
        $sql = "INSERT INTO posts (title, body, author, category_id)
                VALUES ('$title', '$body', '$author', '$category_id')";

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
    <h2>Create New Post</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="mt-4 w-75">
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
        <select class="form-select p-1" name="category_id">
            <?php foreach($cats as $cat): ?>
                <option value="<?php echo $cat['id'] ?>"><?php echo htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-4">
        <label for="body">Description</label>
        <textarea name="body" class="form-control" rows="5" >
            <?php echo $body ?? ''; ?>
        </textarea>
        <div class="has-error text-danger">
            <?php echo $bodyErr ?? ''; ?>
        </div>
    </div>
    <div class="mb-3">
        <input type="submit" Value="Create" name="submit"  class="btn btn-dark"/>
    </div>
    </form>
<?php include('templates/footer.php'); ?>