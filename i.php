<html>
<head>
<title>string replace</title>
</head>
<body>
<h2>string to replaceto another string</h2>

<!-- form for user input -->
<form method = "POST" action "">
<label>orginalstring</label>
<input type = "text" name = "orginalstring"><br><br>
<label>stringtoreplace</label>
<input type = "text" name = "stringtoreplace"><br><br>
<label>replacementstring</label>
<input type = "text" name = "replacementstring"><br><br>
<input type = "submit" value = "replacestring">
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')

$orginal=$_POST['orginalstring'];
$replace=$_POST['stringtoreplace'];
$replacement=$_POST['replacementstring'];

$modifiedstring=str_replace($replace,$replacement,$orginal);


echo "<h3>orginalstring:</h3><p>$orginal</p>";
echo "<h3>modifiedstring:</h3><p>$modifiedstring</p>";
?>
</body>
</html>
