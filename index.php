<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>recherche</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <link rel="stylesheet" href="main.css">
        <div class="team4" align="center">
            <h1>Internet Explorers</h1>
            <a href="https://twitter.com/Interne82423016?s=20" ><img src="https://upload.wikimedia.org/wikipedia/fr/thumb/c/c8/Twitter_Bird.svg/60px-Twitter_Bird.svg.png" alt="logo de Twitter"></a>
        </div>
        <br />
        <h2 align="center">Please type your informations</h2><br />
	</head>
	<body>
        <div class="form-group"  accept-charset="utf-8">
            <div class="input-group">
                <input type="text" name="search_city" id="search_city" placeholder="Your city" class="form-control" />
            </div>
        </div>
        <br />
        <div id="result"></div>
		<br />
        <?php
        if(isset($_POST["ville"]))
        {
            try
            {
                $bdd = new PDO('mysql:host=localhost;dbname=db_mysql;charset=utf8', 'admin', '');
            }
            catch(Exception $e)
            {
                die('Erreur : '.$e->getMessage());
            }
            $print="<table>
                <thead>
                    <tr>
                        <th>City</th>
                        <th>Population</th>
                        <th>Global Score</th>
                        <th>Access to Numeric interface</th>
                        <th>Access to Information</th>
                        <th>Administrative competence</th>
                        <th>Numeric/school competence</th>
                        <th>Global Access</th>
                        <th>Global Competence</th>
                    </tr>
                </thead>
                ";
                
            $reponse = $bdd->prepare('SELECT * FROM final WHERE nom_commune = ?');
            $reponse->execute(array($_POST["ville"]));
            $i=0;
            $dep=0;
            $reg=0;
            $zip=0;
            $pop=0;
            $global_score=0;
            $interfaces=0;
            $informations=0;
            $admin=0;
            $num=0;
            $global_access=0;
            $global_competence=0;
            while ($donnees = $reponse->fetch())
            {
                $i++;
                $dep=$donnees['nom_dep'];
                $reg=$donnees['nom_reg'];
                $zip=$donnees['code_postal'];
                $pop+=$donnees['pop'];
                $global_score+=$donnees['global_score'];
                $interfaces+=$donnees['interfaces'];
                $informations+=$donnees['informations'];
                $admin+=$donnees['admin'];
                $num+=$donnees['num'];
                $global_access+=$donnees['global_access'];
                $global_competence+=$donnees['global_competence'];
            }
            echo '<h2>The choosen city is '.$_POST["ville"].' ('.$zip.'), located in the '.$dep.' departement, in the '.$reg.' region</h2><h3><br /> It\'s fragility scores is :</h3>';
            $print .='
                <tr>
                    <td>'.$_POST["ville"].'</td>
                    <td>'.$pop.'</td>
                    <td>'.$global_score/$i.'</td>
                    <td>'.$interfaces/$i.'</td>
                    <td>'.$informations/$i.'</td>
                    <td>'.$admin/$i.'</td>
                    <td>'.$num/$i.'</td>
                    <td>'.$global_access/$i.'</td>
                    <td>'.$global_competence/$i.'</td>
                </tr>
            </table>';
            echo $print;
            ?>
            <button onclick='document.location.href="index.php"'>Reset</button>
            <?php
        }
        ?>
		<br />
		<br />
		<br />
	</body>
</html>
<script>
$(document).ready(function(){
    load_data();
    function load_data(query)
    {
        $.ajax({
            url:"fetch.php",
            method:"post",
            data:{query:query},
            success:function(data)
            {
                $('#result').html(data);
            }
        });
    }
    $('#search_city').keyup(function(){
        var search = $(this).val();
        if(search != '')
        {
            load_data(search);
        }
        else
        {
            load_data();                    
        }
    });
});
</script>

