<?php
 
$connect = mysqli_connect("localhost", "admin", "QsspNwur7az5", "d4g");
if ($connect->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$connect->set_charset("utf8");
$output = '';
 
if(isset($_POST["query"]))
{
	$search = mysqli_real_escape_string($connect, $_POST["query"]);

	$query = "
	SELECT nom_com FROM book3 
	WHERE nom_com LIKE '%".$search."%'
	UNION ALL SELECT nom_com FROM book3 
	WHERE code_postal LIKE '%".$search."%' LIMIT 0,50
    ";
    $result = mysqli_query($connect, $query);
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result))
        {
            echo '
            <div class="table-responsive">
                <form method="post" action="index.php">
                    <input type="hidden" name="ville" value="'.$row["nom_com"].'" />
                    <input type="submit" value="'.$row["nom_com"].'" />
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