<!doctype html>

<html lang="en">

<head>
  <meta charset="utf-8">

  <title>Dental Clinics</title>
  <meta name="main" content="all">

  <link rel="stylesheet" href="UserInput.css">
  <script src="UserInput.js"></script>
</head>

<body>
    <div class="main-container">
      <?php 
        include 'RequestInput.html';
        include 'DBModifications.html';
        include 'SQLQuery.html';
        include 'RequestResult.html';
      ?>
      <?php
        include 'queries.php'; // only needed tag to connect queries
      ?>
    </div>
    
</body>

</html>