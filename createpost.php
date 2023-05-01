<?php
include('config/sqli_db_connect.php');

// Get all categories and display to Dropdownlist
$sql = 'SELECT id, name FROM categories ORDER BY name';

$cat_result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$cats = mysqli_fetch_all($cat_result, MYSQLI_ASSOC);


//require('input_validator.php');
$title = $author = $body = $upload = '';
$category_id ='';
$titleErr = $authorErr = $bodyErr = $uploadErr = '';

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

    // Validate file
    $allowed_ext = array('png', 'jpg', 'jpeg', 'gif');
    if (!empty($_FILES['upload']['name'])) {
        $timestamp = date_timestamp_get(date_create());
        $file_name = $timestamp . $_FILES['upload']['name'];
        $file_size = $_FILES['upload']['size'];
        $file_tmp = $_FILES['upload']['tmp_name'];
        $target_dir = "uploads/${file_name}";

        // Get file extension
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if (in_array($file_ext, $allowed_ext)) {
            if ($file_size <= 1000000) {
                move_uploaded_file($file_tmp, $target_dir);
                $upload = $file_name;
            } else {
                $uploadErr = 'File is too large';
            }
        } else {
            $uploadErr = 'Invalid file type';
        }
    } 
    // else {
    //     $uploadErr = 'Please choose a file';
    // }

    //  Category id
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    if ($title != '' && $author != '' && $body != '' && $category_id != '') {
        // Create sql
        $sql = "INSERT INTO posts (title, body, author, category_id, img)
                VALUES ('$title', '$body', '$author', '$category_id', '$upload')";

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
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="mt-4 w-75" enctype="multipart/form-data">
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
            <label for="category">Category</label>
            <select class="form-select p-1 ml-2" name="category_id">
                <?php foreach($cats as $cat): ?>
                    <option value="<?php echo $cat['id'] ?>"><?php echo htmlspecialchars($cat['name']) ?></option>
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

        <div class="form-group mb-4">
            <label for="upload">Select image to upload</label>
            <input type="file" name="upload" class="file-control" />
            <div class="has-error text-danger">
                <?php echo $uploadErr ?? ''; ?>
            </div>
        </div>

        <div class="mb-3">
            <input type="submit" Value="Create" name="submit"  class="btn btn-dark"/>
        </div>
    </form>
<?php include('templates/footer.php'); ?>