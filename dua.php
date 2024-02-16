<?php
session_start();
if (!isset($_SESSION['userId']) || $_SESSION['role'] != 'user') {
    header('location:login.php');
    exit; // Stop further execution
}
?>
<?php require "db/db.php" ?>
<?php require "includes/header.php" ?>
<?php require "functions/function.php" ?>
<style>
    .pressbtn {
        background-color: #4B3F3F;
        color: white;
        border: none;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-6 p-1">
            <div class="card text-center " style="background-color: #E9E4E4; border:none;">
                <div class="card-body pressbtn">
                    <button class="btn-ajax urdu-font" style="border: none; background-color:transparent;" data-lang="urdu">اردو</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 p-1">
            <div class="card text-center" style="background-color: #E9E4E4;border:none;">
                <div class="card-body">
                    <button class="btn-ajax" style="border: none; background-color:transparent;" data-lang="english">English</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 p-1">
            <div class="card text-center" style="background-color: #E9E4E4;border:none;">
                <div class="card-body">
                    <button class="btn-ajax" style="border: none; background-color:transparent;" data-lang="arabic">Arabic</button>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 p-1">
            <div class="card text-center" style="background-color: #E9E4E4;border:none;">
                <div class="card-body">
                    <button class="btn-ajax" style="border: none; background-color:transparent;" data-lang="roman">Transliteration</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row my-5">
        <div class="col-md-12">
            <ol id="dynamic-list" class="h2">
                <!-- List items will be dynamically added here -->
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h3 class="text-center" id="error">
                
            </h3>
        </div>
    </div>
</div>

<script>
    // Function to fetch data based on language
    function fetchData(language) {
        const categoryId = getUrlParameter('category_id'); // Replace with the actual category ID

        // Send the language value and category ID to PHP using AJAX
        fetch('functions/ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    language: language,
                    category_id: categoryId
                })
            })
            .then(response => response.json()) // Parse response as JSON
            .then(data => {
                // Get the existing ordered list
                const dynamicList = document.getElementById('dynamic-list');
                const error = document.getElementById('error');
                error.textContent = ""; // Clear previous error message

                // Clear previous content
                dynamicList.innerHTML = '';

                if (data.length === 0) {
                    error.textContent = "Dua Not Found";
                    error.style.margin="100px";
                } else {
                    // Loop through the data and create list items
                    data.forEach(item => {
                        // Create <a> tag
                        const link = document.createElement('a');
                        link.style.color = "black";
                        // Set href attribute with the ID and the destination page
                        link.href = 'duadetail.php?id=' + item.id; // Change 'another_page.php' to the actual destination page
                        // Create <li> tag
                        const li = document.createElement('li');
                        // Apply font-family for Urdu text
                        li.style.fontFamily = "urdu";
                        li.style.padding = "20px"; // Replace 'UrduFont' with your desired font name for Urdu
                        // Set the text content of the <a> tag
                        link.textContent = item.translation; // Change 'translation' to the property that holds the translation
                        // Append the <a> tag to the <li> tag
                        li.appendChild(link);
                        const hr=document.createElement('hr');
                        hr.style.marginTop="60px"
                        // Append the <hr> tag after the <li> tag
                        li.appendChild(hr);
                        // Append the <li> tag to the list
                        dynamicList.appendChild(li);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Initially fetch data with Urdu language
    fetchData('urdu');

    // Add event listeners to language buttons
    document.querySelectorAll('.btn-ajax').forEach(button => {
        button.addEventListener('click', function() {
            // Remove 'pressbtn' class from all cards
            document.querySelectorAll('.card-body').forEach(card => {
                card.classList.remove('pressbtn');
            });

            // Add 'pressbtn' class to the parent card of the clicked button
            this.closest('.card-body').classList.add('pressbtn');

            // Remove 'pressbtn' class from all language buttons
            document.querySelectorAll('.btn-ajax').forEach(btn => {
                btn.classList.remove('pressbtn');
            });

            // Add 'pressbtn' class to the clicked language button
            this.classList.add('pressbtn');

            // Fetch data based on the selected language
            const lang = this.getAttribute('data-lang');
            fetchData(lang);
        });
    });

    // Function to get URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };
</script>



<?php require "includes/footer.php" ?>