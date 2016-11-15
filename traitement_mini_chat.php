<?php   //au cas où, mais on en aura pas besoin pour l'instant
session_start() 
?>

<?php setcookie('pseudo', htmlspecialchars($_POST['pseudo']), time() + 365*24*3600, null, null, false, true); ?>  <!-- mis en place du cookie avec le pseudo-->
<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8"/>
			<title>traitement_mini_chat</title>
			<link rel="stylesheet" href="style_mini_chat.css"/>
		</head>
		<body>
		
		
<?php
  if (empty($_POST['pseudo']) OR empty($_POST['message']))
  {
	  echo '<p style="color:red;font-size:3em;text-align:center;"><strong> ATTENTION </strong> </br>Vous devez saisir votre pseudo et votre message avant de valider!!!!!</p>';
	
	header('Refresh: 2;mini_chat.php');  // Redirection vers page 'mini_chat.php';*/
 
  }
  else
  {
	try
	{
    $bdd = new PDO('mysql:host=localhost;dbname=test_oc;charset=utf8', 'root', '');
	}
	catch(Exception $e)
	{
        die('Erreur : '.$e->getMessage());
	}

	$req = $bdd->prepare("INSERT INTO mini_chat(pseudo, message,date_creation) VALUES(:pseudo, :message,NOW())");
	$req->execute(array(
		'pseudo' => htmlspecialchars($_POST['pseudo']),//ici on utilise htmlspecialchars pour eviter les failles XSS en échapant les code html (ils seront affichés mais pas executés).

		'message' => strip_tags($_POST['message']), // On peut aussi éviter les failles XSS avec "strip_tags" qui supprime le code html parasite.
					));
	$req->closeCursor();
	header('Location: mini_chat.php');  // Redirection vers page 'mini_chat.php'
}
?>

</body>
</html>