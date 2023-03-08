<?php
// Connexion à la base de données
$dsn = 'pgsql:host=localhost;dbname=todolist';
$user = 'root';
$password = '';
//

try {
    $db = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo "Une erreur est survenue";
    echo $e;
}

// Traitement de la création d'une tâche
if (isset($_POST['creerTache'])) {
    if (empty($_POST['creerTache'])) {
        $erreur = "Vous devez indiqué la valeur de la tache";
        echo $erreur;
    } else {
        $tache = $_POST['creerTache'];
        $req = "INSERT INTO tach (tache) VALUES (:tache)";
        $rs_req = $db->prepare($req);
        $rs_req->bindValue(':tache', $tache, PDO::PARAM_STR);
        try{
            $rs_req->execute();
        }catch(PDOException $e){
            echo 'Erreur lors de l\'insertion de la tache';
        }
    }
}

// Traitement de la suppression d'une tâche
if (isset($_GET['supprimer_tache'])) {
    $id = $_GET['supprimer_tache'];
    $db->exec("delete from tach where id=$id");
}

// Récupération de toutes les tâches dans la base de données
$sql = "select * from tach";
$rs_sql = $db->prepare($sql);
$rs_sql->execute();
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
<!-- 
    <style>
        body {
            background-color: beige;
        }
    </style> -->
</head>

<body>
    <p align="center"> Todo List</p>

    <!-- Formulaire pour créer une nouvelle tâche -->
    <form align="center" action="index.php" method="post">
        <input  class="Input-text" type="text" name="creerTache">
        <button id="envoyer">Créer</button>
    </form>

    <!-- Tableau pour afficher toutes les tâches -->
    <table align="center">
        <thead>
            <tr>
                <th>N</th>
                <th>Nom</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($donnees = $rs_sql->fetch()) : ?>
                <tr>
                    <!-- Affichage de l'ID de la tâche -->
                    <td><?php echo $donnees['id']; ?></td>
                    <!-- Affichage de la description de la tâche -->
                    <td><?php echo $donnees['tache']; ?></td>
                    <!-- Affichage de la colonne Action -->
                    <td><?php echo 'supprimer'?></td>
                    <!-- Lien de suppression pour la tâche -->
                    <td><a class="suppr" href="index.php?supprimer_tache=<?php echo $donnees['id'] ?>"> X</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
