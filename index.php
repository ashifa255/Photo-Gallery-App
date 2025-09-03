<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Fetch all images from database
$stmt = $pdo->query("SELECT * FROM images ORDER BY upload_date DESC");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-center mb-4">Photo Gallery</h1>

<?php if (count($images) > 0): ?>
    <div class="row">
        <?php foreach ($images as $image): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="assets/images/<?php echo htmlspecialchars($image['filename']); ?>" 
                         class="card-img-top gallery-image" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($image['description']); ?></p>
                    </div>
                    <div class="card-footer text-muted">
                        Uploaded on <?php echo date('M j, Y', strtotime($image['upload_date'])); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="text-center">
        <p>No images uploaded yet. <a href="upload.php">Upload the first image!</a></p>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
