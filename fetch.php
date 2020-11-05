<?php
 
$connect = mysqli_connect("localhost", "admin", "", "db_mysql");
if ($connect->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$connect->set_charset("utf8");
$output = '';
 
if(isset($_POST["query"]))
{
	$search = mysqli_real_escape_string($connect, $_POST["query"]);

	$query = "
	SELECT nom, code FROM city 
	WHERE nom LIKE '%".$search."%'
	UNION ALL SELECT nom, code FROM city 
	WHERE code LIKE '%".$search."%' LIMIT 0,50
    ";
    $result = mysqli_query($connect, $query);
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            echo '
            <div class="table-responsive">
                <form method="post" action="index.php">
                    <input type="hidden" name="ville" value="'.$row["nom"].'" />
                    <input type="submit" value="'.$row["nom"].'" />
                </form>
            </div>
            ';
        }
    }
    else
    {
        echo 'Data Not Found';
    }
}
?>