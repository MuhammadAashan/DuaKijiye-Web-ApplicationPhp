<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'admin') {
    header('location: login.php');
    exit;
}
?>
<?php require "../includes/adminheader.php" ?>
<?php require "../functions/function.php" ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    <h2>Category List</h2>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
                        <i class="bi bi-plus-circle-fill"></i> Add Category
                    </button>
                </div>
            </div>

            <div id="errorContainer" class="error-container">
            </div>



            <ol class="list-group">
                <?php
                $categories = getAllCat();
                if ($categories) {
                    foreach ($categories as $category) {
                ?>
                        <li class="list-group-item  d-flex justify-content-between urdu-font align-items-center">
                            <?php echo $category['name']; ?>
                            <div>
                                <!-- Edit button opens modal -->
                                <button type="button" class="btn btn-primary btn-sm edit-category-btn" data-toggle="modal" data-target="#editCategoryModal" data-id="<?php echo $category['id']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <button type="button" class="btn btn-danger btn-sm delete-category-btn" data-id="<?php echo $category['id']; ?>">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </div>
                        </li>
                <?php
                    }
                } else {
                    // If no categories are available
                    echo "<li class='list-group-item'>No categories found</li>";
                }
                ?>
            </ol>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCategoryForm">
                <!-- Add an input field to hold the category ID -->
                <input type="hidden" id="categoryId" name="categoryId">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editCategoryName">English Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" required>
                    </div>
                    <div class="form-group">
                        <label for="editCategoryUrduName">Urdu Name</label>
                        <input type="text" class="form-control" id="editCategoryUrduName" name="editCategoryUrduName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- Change the type to button to prevent form submission -->
                    <button type="button" class="btn btn-primary" id="saveChangesBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="categoryName">Category English Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                    </div>
                    <div class="form-group">
                        <label for="categoryUrduName">Category Urdu Name</label>
                        <input type="text" class="form-control urdu-font" id="categoryUrduName" name="categoryUrduName" required>
                    </div>
                    <div id="addErrorContainer" class="mt-3"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addCategoryBtn">Add Category</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Add event listener for the Add Category button in the modal
    document.getElementById('addCategoryBtn').addEventListener('click', function() {
        var categoryName = document.getElementById('categoryName').value;
        var categoryUrduName = document.getElementById('categoryUrduName').value;

        // Validate input (optional)
        if (!categoryName || !categoryUrduName) {
            document.getElementById('addErrorContainer').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    Please provide both English and Urdu names for the category.
                </div>`;
            return; // Stop further execution if input is invalid
        }

        // Make an AJAX call to insert the category
        fetch('../functions/insert_category.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    categoryName: categoryName,
                    categoryUrduName: categoryUrduName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success===true) {
                    // If insertion is successful, close the modal
                    $('#addCategoryModal').modal('hide');
                    location.reload();
                    // Optionally, you can reload the category list page or perform any other action here
                } else {
                    // If insertion fails, display error message
                    document.getElementById('addErrorContainer').innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        ${data.error}
                    </div>`;
                }
            })
            .catch(error => {
                // If AJAX call fails, display error message
                document.getElementById('addErrorContainer').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    Failed to add category. Please try again later.
                </div>`;
            });
    });
</script>
<script>
    // Add event listener to handle button click for deletion
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('delete-category-btn')) {
            // Get the category ID from the button data attribute
            var categoryId = event.target.dataset.id;
            // Confirm deletion
            var confirmation = confirm("Are you sure you want to delete this category?");

            // If user confirms, proceed with deletion
            if (confirmation) {
                // Make a fetch request to delete the category
                fetch('../functions/delete_category.php?categoryId=' + categoryId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // If deletion is successful, reload the page to reflect changes
                            location.reload();
                        } else {
                            // If deletion fails, display error message
                            document.getElementById('errorContainer').innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        ${data.error}
                    </div>`;
                        }
                    })
                    .catch(error => {
                        // If fetch request fails, display error message
                        document.getElementById('errorContainer').innerHTML = `
                <div class="alert alert-danger" role="alert">
                    Failed to delete category. Please try again later.
                </div>`;
                    });
            }
        }
    });
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit-category-btn')) {
            // Get the category details from the button data attributes
            var categoryId = event.target.dataset.id;
            document.getElementById('categoryId').value = categoryId;
        }
    });
    // Add event listener to handle click on save changes button
    document.getElementById('saveChangesBtn').addEventListener('click', function() {
        // Get the updated category names from the input fields
        var updatedCategoryName = document.getElementById('editCategoryName').value;
        var updatedCategoryUrduName = document.getElementById('editCategoryUrduName').value;

        // Get the category ID from the modal
        var categoryId = document.getElementById('categoryId').value;

        // Make an AJAX call to update the category
        fetch('../functions/update_category.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    categoryId: categoryId,
                    updatedCategoryName: updatedCategoryName,
                    updatedCategoryUrduName: updatedCategoryUrduName
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // If update is successful, close the modal and reload the page
                    $('#editCategoryModal').modal('hide');
                    location.reload();
                } else {
                    // If update fails, display error message
                    alert(data.error);
                    console.log('Error:', data.error);

                }
            })
            .catch(error => {
                // If AJAX call fails, display error message
                alert('Failed to update category. Please try again later');
            });

    });
</script>

<?php require "../includes/adminfooter.php" ?>