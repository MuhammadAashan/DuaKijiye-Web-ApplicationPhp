<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'admin') {
    header('location:login.php');
    exit;
}
?>
<?php require "../includes/adminheader.php" ?>
<?php require "../functions/function.php" ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1 class="mt-5">Add Dua</h1>
        </div>
        <div class="col-12">
            <?php if (isset($_GET['error'])) {
                $errorMessage = $_GET['error'];
                echo "<div class='alert alert-danger'>$errorMessage</div>";
            }
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h3 class="mt-5 text-center">Dua Names</h3>
            <form action="process_dua.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="arabicText" class="form-label">Arabic Text</label>
                    <input type="text" class="form-control urdu-font" id="arabicText" name="arabicText" required>
                </div>
                <div class="mb-3">
                    <label for="urduText" class="form-label">Urdu Text</label>
                    <textarea class="form-control urdu-font" id="urduText" name="urduText" rows="3" style="resize: none;" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="englishText" class="form-label">English Text</label>
                    <textarea class="form-control" id="englishText" name="englishText" rows="3" style="resize: none;" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="romanArabicText" class="form-label">Roman Arabic Text</label>
                    <input type="text" class="form-control" id="romanArabicText" name="romanArabicText" required>
                </div>
                <h3 class="mt-5 text-center">Dua</h3>
                <div class="mb-3">
                    <label for="urduTranslation" class="form-label">Urdu Translation</label>
                    <textarea class="form-control urdu-font" id="urduTranslation" name="urduTranslation" rows="3" style="resize: none;" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="englishTranslation" class="form-label">English Translation</label>
                    <textarea class="form-control" id="englishTranslation" name="englishTranslation" rows="3" style="resize: none;" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="arabicTranslation" class="form-label">Arabic Translation</label>
                    <textarea class="form-control urdu-font" id="arabicTranslation" name="arabicTranslation" rows="3" style="resize: none;" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="romanTranslation" class="form-label">Roman Translation</label>
                    <input type="text" class="form-control" id="romanTranslation" name="romanTranslation" required>
                </div>
                <div class="mb-3">
                    <label for="categorySelect" class="form-label">Select Category</label>
                    <select class="form-select urdu-font" id="categorySelect" name="categorySelect" required>
                        <!-- Options populated dynamically from PHP -->
                        <?php
                        $categories = getAllCat();
                        if ($categories) {
                            foreach ($categories as $category) {
                                echo "<option class='urdu-font' value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="audioFile" class="form-label">Choose Audio File</label>
                    <input class="form-control" type="file" id="audioFile" name="audioFile"  required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

<?php require "../includes/adminfooter.php" ?>