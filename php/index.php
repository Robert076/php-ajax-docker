<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/main.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
        button { padding: 5px 10px; margin: 5px; }
    </style>
</head>
<body>

<h1>Book List</h1>
<table id="bookTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Author</th>
            <th>Title</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Book entries will be populated here via AJAX -->
    </tbody>
</table>

<h2>Add a New Book</h2>
<form id="addBookForm">
    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required><br><br>
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required><br><br>
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required><br><br>
    <button type="submit">Add Book</button>
</form>

<div id="editModal" style="display:none;">
    <h2>Edit Book</h2>
    <form id="editBookForm">
        <label for="editAuthor">Author:</label>
        <input type="text" id="editAuthor" name="author" required><br><br>
        <label for="editTitle">Title:</label>
        <input type="text" id="editTitle" name="title" required><br><br>
        <label for="editPrice">Price:</label>
        <input type="number" id="editPrice" name="price" required><br><br>
        <input type="hidden" id="editBookId">
        <button type="submit">Update Book</button>
    </form>
    <button id="closeModal">Close</button>
</div>

<script>
    $(document).ready(function(){
        function fetchBooks() {
            $.ajax({
                url: 'getBooks.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        var tableContent = '';
                        $.each(data, function(index, book) {
                            tableContent += '<tr>';
                            tableContent += '<td>' + book.id + '</td>';
                            tableContent += '<td>' + book.author + '</td>';
                            tableContent += '<td>' + book.title + '</td>';
                            tableContent += '<td>' + book.price + '</td>';
                            tableContent += '<td>';
                            tableContent += '<button class="editBook" data-id="' + book.id + '" data-author="' + book.author + '" data-title="' + book.title + '" data-price="' + book.price + '">Edit</button>';
                            tableContent += '<button class="deleteBook" data-id="' + book.id + '">Delete</button>';
                            tableContent += '</td>';
                            tableContent += '</tr>';
                        });
                        $('#bookTable tbody').html(tableContent);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ', ' + error);
                }
            });
        }

        // Fetch books when the page loads
        fetchBooks();

        // Handle form submission for adding a new book
        $('#addBookForm').on('submit', function(e) {
            e.preventDefault();

            var author = $('#author').val();
            var title = $('#title').val();
            var price = $('#price').val();

            $.ajax({
                url: 'insertBook.php',
                type: 'POST',
                data: { author: author, title: title, price: price },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert(data.success);
                        $('#addBookForm')[0].reset();
                        fetchBooks(); // Refresh the book list
                    } else {
                        alert(data.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ', ' + error);
                }
            });
        });

        // Handle Edit button click
        $(document).on('click', '.editBook', function() {
            var bookId = $(this).data('id');
            var author = $(this).data('author');
            var title = $(this).data('title');
            var price = $(this).data('price');

            $('#editBookId').val(bookId);
            $('#editAuthor').val(author);
            $('#editTitle').val(title);
            $('#editPrice').val(price);

            $('#editModal').show();
        });

        // Handle Edit form submission
        $('#editBookForm').on('submit', function(e) {
            e.preventDefault();

            var bookId = $('#editBookId').val();
            var author = $('#editAuthor').val();
            var title = $('#editTitle').val();
            var price = $('#editPrice').val();

            $.ajax({
                url: 'updateBook.php',
                type: 'POST',
                data: { id: bookId, author: author, title: title, price: price },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert(data.success);
                        $('#editModal').hide();
                        fetchBooks(); 
                    } else {
                        alert(data.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ', ' + error);
                }
            });
        });

        // Close the modal
        $('#closeModal').on('click', function() {
            $('#editModal').hide();
        });

        // Handle Delete button click
        $(document).on('click', '.deleteBook', function() {
            var bookId = $(this).data('id');

            if (confirm("Are you sure you want to delete this book?")) {
                $.ajax({
                    url: 'deleteBook.php',
                    type: 'POST',
                    data: { id: bookId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            alert(data.success);
                            fetchBooks(); // Refresh the book list
                        } else {
                            alert(data.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ', ' + error);
                    }
                });
            }
        });
    });
</script>

</body>
</html>
