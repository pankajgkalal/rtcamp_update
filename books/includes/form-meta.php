<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo PLUGIN_URL.'/books/public/js/bootstrap.min.css' ?>">
  <script src="<?php echo PLUGIN_URL.'/books/public/js/jquery.min.js' ?>"></script>
  <script src="<?php echo PLUGIN_URL.'/books/public/js/bootstrap.min.js' ?>"></script>
</head>
<body>


  <form action="#">
    <div class="form-group">
      <label for="book_author">Book Author:</label>
      <input type="text" class="form-control" id="book_author" placeholder="Enter Author Name" value="<?php echo get_book_meta($object->ID,"Author_name", true); ?>" name="book_author">
    </div>
    <div class="form-group">
      <label for="book_price">Book Price:</label>
      <input type="text" class="form-control" id="book_price" placeholder="Enter Price" name="book_price" value="<?php echo get_book_meta($object->ID,"Book_price", true); ?>">
    </div>
    <div class="form-group">
      <label for="book_publisher">Book Publisher:</label>
      <input type="text" class="form-control" id="book_publisher" placeholder="Enter Publisher" name="book_publisher" value="<?php echo get_book_meta($object->ID,"Book_publisher", true); ?>">
    </div>
    <div class="form-group">
      <label for="book_publishing_year">Book Publishing Year:</label>
      <input type="text" class="form-control" id="book_publishing_year" placeholder="Enter Year" name="book_publishing_year" value="<?php echo get_book_meta($object->ID,"Year", true); ?>">
    </div>
    <div class="form-group">
      <label for="book_edition">Book Edition:</label>
      <input type="text" class="form-control" id="book_edition" placeholder="Enter Edition" name="book_edition" value="<?php echo get_book_meta($object->ID,"Edition", true); ?>">
    </div>
  

  </form>

</body>
</html>

