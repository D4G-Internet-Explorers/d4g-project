<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>recherche</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <link rel="stylesheet" href="main.css">
        <style>
        .team4{
            background-image: url('https://cdn.hipwallpaper.com/i/18/1/Vaq56r.jpg');
        }
        body {
            background-image: url('https://cdn.hipwallpaper.com/i/18/1/Vaq56r.jpg');
            background-repeat: no-repeat;
        }
        </style>
        <div class="team4" align="center">
            <h1>Internet Explorers</h1>
            <a href="https://twitter.com/Interne82423016?s=20" ><img src="https://upload.wikimedia.org/wikipedia/fr/thumb/c/c8/Twitter_Bird.svg/60px-Twitter_Bird.svg.png" alt="logo de Twitter"></a>
        </div>
        <br />
        <h2 align="center">Please type your informations</h2><br />
	</head>
	<body>
        <div class="form-group"  accept-charset="utf-8">
            <div class="input-group" align="center">
                <input type="text" name="search_city" id="search_city" placeholder="Your city" class="form-control" style="border-radius:5px;"/>
            </div>
        </div>
        <br />
        <div id="result"  align="center"></div>
		<br />
        <?php
        if(isset($_POST["ville"]))
        {
            try
            {
                $bdd = new PDO('mysql:host=localhost;dbname=d4g;charset=utf8', 'admin', 'QsspNwur7az5');
            }
            catch(Exception $e)
            {
                die('Erreur : '.$e->getMessage());
            }
            $reponse = $bdd->prepare('SELECT main.code_postal,nom_dep,nom_reg,nom_com,book3.code_dep,book3.code_reg,
            avg(acces_interface),avg(acces_info),avg(comp_admin),avg(comp_num),avg(score_global),count(main.code_postal) FROM D4G.main
            join book3 on book3.code_postal=main.code_postal
            where book3.nom_com=?');
            $reponse->execute(array($_POST["ville"]));

            $dep=0;
            $codedep=0;
            $reg=0;
            $zip=0;
            $pop=0;
            $global_score=0;
            $interfaces=0;
            $informations=0;
            $admin=0;
            $num=0;
            while ($donnees = $reponse->fetch())
            {
                $dep=$donnees['nom_dep'];
                $reg=$donnees['nom_reg'];
                $zip=$donnees['code_postal'];
                $global_score+=$donnees['avg(score_global)'];
                $interfaces+=$donnees['avg(acces_interface)'];
                $informations+=$donnees['avg(acces_info)'];
                $admin+=$donnees['avg(comp_admin)'];
                $num+=$donnees['avg(comp_num)'];
                $codedep=$donnees['code_dep'];
                $codereg=$donnees['code_reg'];
            }
            $reponse->closeCursor();
            $print="<div align=\"center\"><h2>The choosen city is ".$_POST["ville"]." (".$zip."), located in the ".$dep." departement, in the ".$reg." region</h2><h3><br /> It's fragility scores is :</h3>";
            $print.="<table style=\"text-align:center\">
                <thead>
                    <tr>
                        <th>City</th>
                        <th>Global Score</th>
                        <th>Access to Numeric interface</th>
                        <th>Access to Information</th>
                        <th>Administrative competence</th>
                        <th>Numeric/school competence</th>
                    </tr>
                </thead>
                ";
                
            $print .='
                <tr>
                    <td>'.$_POST["ville"].'</td>
                    <td><mark>'.$global_score.'</mark></td>
                    <td>'.$interfaces.'</td>
                    <td>'.$informations.'</td>
                    <td>'.$admin.'</td>
                    <td>'.$num.'</td>
                </tr>
            </table>';

            $avgdep=0;
            $reponse = $bdd->prepare('SELECT nom_dep,avag FROM D4G.department
            where nom_dep=?');
            $reponse->execute(array($dep));
            while ($donnees = $reponse->fetch())
            {
                if ($donnees['avag']==0)
                {
                    $rep = $bdd->prepare('SELECT code_dep,
                    avg(score_global) FROM D4G.main
                    where code_dep=?');
                    $rep->execute(array($codedep));
                    while ($data = $rep->fetch())
                    {
                        $avgdep=$data['avg(score_global)'];
                    }
                    /*$rempli = $bdd->prepare('UPDATE D4G.department SET avag=? WHERE code_dep=?');
                    $rempli->execute(array(
                        $avgdep,
                        $codedep
                    ));*/
                    $rep->closeCursor();
                }
                else
                {
                    $avgdep=$donnees['avag'];
                }
            }
            $reponse->closeCursor();
            
            
            $avgreg=0;
            $reponse = $bdd->prepare('SELECT nom_reg,avag FROM D4G.region
            where nom_reg=?');
            $reponse->execute(array($reg));
            while ($donnees = $reponse->fetch())
            {
                if ($donnees['avag']==0)
                {
                    $rep = $bdd->prepare('SELECT code_reg,
                    avg(score_global) FROM D4G.main
                    where code_reg=?');
                    $rep->execute(array($codereg));
                    while ($data = $rep->fetch())
                    {
                        $avgreg=$data['avg(score_global)'];
                    }
                    /*$rempli = $bdd->prepare('UPDATE D4G.region SET avag=? WHERE code_reg=?');
                    $rempli->execute(array(
                        $avgreg,
                        $codereg
                    ));*/
                    $rep->closeCursor();
                }
                else
                {
                    $avgreg=$donnees['avag'];
                }
            }
            $reponse->closeCursor();
            echo $print;
            echo '<p>Average of the department : <strong>'.$avgdep.'</strong>; average of the region : <strong>'.$avgreg.'</strong></p>';
            if ($global_score<$avgdep)
            {
                echo '<p>As you can see, your city is below the average of the department, that means you have less "fragility" issues than the department, and ';
            }
            else
            {
                echo '<p>As you can see, your city is above the average of the department, that means you have more "fragility" issues than the department, and ';
            }
            if ($global_score<$avgreg)
            {
                echo 'your city is below the average of the region, that means you have less "fragility" issues than the region</p>';
            }
            else
            {
                echo 'your city is above the average of the region, that means you have more "fragility" issues than the region</p>';
            }
            ?>
            <button onclick='document.location.href="index.php"'>Reset</button>
        </div>
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

