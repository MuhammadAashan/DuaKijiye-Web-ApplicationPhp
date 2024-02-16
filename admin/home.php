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
        <div class="col-6 text-left"> <!-- Center the content -->
            <label for="category">Select Category:</label>
            <select name="category" id="category" class="form-select urdu-font"> <!-- Add Bootstrap form-select class -->
                <?php
                $categories = getAllCat();

                if ($categories) {
                    foreach ($categories as $category) {
                        echo "<option class='urdu-font' value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No categories found</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-6 text-right">
            <a href="adddua.php" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill"></i> Add Dua
            </a>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <ol id="duaList" class="list-group">
                    <!-- Duas will be populated dynamically -->
                </ol>

            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category');
        const duaList = document.getElementById('duaList');

        function fetchAndDisplayDuas(categoryId) {
            fetch('../functions/fetch_duas.php?categoryId=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    console.log('Data:', data);
                    duaList.innerHTML = '';
                    if (data.length === 0) {
                        const listItem = document.createElement('li');
                        listItem.textContent = 'No Dua found in the category';
                        listItem.style.listStyleType = 'none';
                        listItem.classList.add('m-5', 'p-5')
                        duaList.appendChild(listItem);
                    } else {
                        data.forEach(dua => {
                            const duaNameObject = JSON.parse(dua.dua_name);
                            const romanDua = duaNameObject.name.urdu;

                            const card = document.createElement('div');
                            card.classList.add('card');

                            const cardBody = document.createElement('div');
                            cardBody.classList.add('card-body');

                            const listItem = document.createElement('li');
                            listItem.textContent = `${romanDua}`;
                            listItem.style.fontFamily="urdu";

                            const deleteButton = document.createElement('button');
                            deleteButton.classList.add('btn', 'btn-danger', 'float-right', 'm-2');
                            deleteButton.innerHTML = '<i class="bi bi-trash3"></i>';
                            deleteButton.addEventListener('click', function() {
                                // Call the deleteCategory function when the delete button is clicked
                                deleteDua(dua.id);
                            });

                            // const editButton = document.createElement('button');
                            // editButton.classList.add('btn', 'btn-primary', 'float-right', 'm-2');
                            // editButton.innerHTML = '<i class="bi bi-pencil-fill"></i>';
                            // editButton.addEventListener('click', function() {
                            //     // Implement edit functionality here
                            // });

                            listItem.appendChild(deleteButton);
                            // listItem.appendChild(editButton);

                            cardBody.appendChild(listItem);
                            card.appendChild(cardBody);

                            duaList.appendChild(card);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        categorySelect.addEventListener('change', function() {
            const categoryId = this.value;
            fetchAndDisplayDuas(categoryId);
        });

        // Initial fetch and display Duas based on the selected category
        fetchAndDisplayDuas(categorySelect.value);
    });
    function deleteDua(duaId) {
    // Prompt the user for confirmation
    const confirmDelete = confirm("Are you sure you want to delete this dua?");
    
    if (confirmDelete) {
        // If the user confirms, proceed with deletion
        // Make an AJAX request to delete_dua.php
        fetch('../functions/delete_dua.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ duaId: duaId }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Check if deletion was successful
            if (data.success) {
                // Optionally, perform any UI update or other actions after successful deletion
                console.log("Dua deleted successfully");
                window.location.reload()
            } else {
                // Handle deletion failure
                console.error("Failed to delete dua:", data.error);
            }
        })
        .catch(error => {
            // Handle network errors or server errors
            console.error("Error:", error);
        });
    } else {
        // If the user cancels, do nothing
        console.log("Deletion cancelled by user");
    }
}


    // PHP for deleting the category
</script>

<?php require "../includes/adminfooter.php" ?>
