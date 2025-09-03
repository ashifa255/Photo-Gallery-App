<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    // Check if file was uploaded without errors
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $file = $_FILES['image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed file types
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Validate file
        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {
                if ($file_size <= 5000000) { // 5MB limit
                    // Generate unique filename
                    $new_filename = uniqid('', true) . '.' . $file_ext;
                    $file_destination = 'assets/images/' . $new_filename;
                    
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        // Insert image data into database
                        $stmt = $pdo->prepare("INSERT INTO images (title, description, filename) VALUES (?, ?, ?)");
                        if ($stmt->execute([$title, $description, $new_filename])) {
                            $_SESSION['message'] = 'Image uploaded successfully!';
                            $_SESSION['message_type'] = 'success';
                            header('Location: index.php');
                            exit();
                        } else {
                            $error = 'Failed to save image data to database.';
                        }
                    } else {
                        $error = 'Failed to move uploaded file.';
                    }
                } else {
                    $error = 'File size is too large. Maximum size is 5MB.';
                }
            } else {
                $error = 'There was an error uploading your file.';
            }
        } else {
            $error = 'You cannot upload files of this type. Allowed: JPG, JPEG, PNG, GIF.';
        }
    } else {
        $error = 'Please select an image file to upload.';
    }
    
    if (isset($error)) {
        $_SESSION['message'] = $error;
        $_SESSION['message_type'] = 'danger';
    }
}
?>

<h1 class="text-center mb-4">Upload New Image</h1>

<div class="row justify-content-center">
    <div class="col-md-6">
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Image Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Select Image (Max 5MB)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
