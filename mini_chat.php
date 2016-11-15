<?phpsession_start()?> <!-- au cas où, mais on en aura pas besoin pour l'instant-->

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8"/>
			<title>mini chat</title>
			<link rel="stylesheet" href="style_mini_chat.css"/>
		</head>
		<body>
		<?php
			if(!isset($_COOKIE['pseudo']))
			{
			$pseudo=NULL;
			}
			else
			{
				$pseudo=($_COOKIE['pseudo']);
			}
			
			echo'
					<form method="post" action="traitement_mini_chat.php">
						<fieldset style="background-color:#fbf1a6;">
						<legend>Mini-chat</legend><br/>
							<label for="pseudo">Saisir votre Pseudo:</label>
							<input style="background-color:rgba(135,135,135,0.2);color:black;" type="text" name="pseudo" id="pseudo"  value="'.$pseudo.'" autofocus required/><br/><br/>
					
							<label for="message">Message:</label>
							<textarea name="message" id="message" cols="100" rows="5" placeholder="Saisissez votre message ici!"></textarea><br/><br/>
				
							<input style="background:red" type="submit" value="envoyer votre message" id="bouton"/>
						</fieldset>
					</form><br/>';
			?>	
			
			<!-- plusieurs methodes pour declencher le rafraichissement de la page, soit un bouton submit dans un formulaire qui renvoit vers la page actuelle, soit un lien vers cette page -->
			<!--methode 1 -->
			<div id="rafraichir">
			<form method="get" action="mini_chat.php">
			<input type="submit" value="rafraichir la page" />
			</form><br/>
			<!--methode 2 -->
			<a href="mini_chat.php" style="border:1px solid;text-decoration:none;padding:0px 5px;">rafraichir la page</a>
			</div>
			<!--  fin du rafraichissement-->
			
			
			<?php  
			//affichage du nombre de message et nombre de page
							try
							{
							$bdd = new PDO('mysql:host=localhost;dbname=test_oc;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
							}
							catch(Exception $e)
							{
							die('Erreur : '.$e->getMessage());
							}

				$req = $bdd->query("SELECT COUNT(*)AS nbr_message FROM mini_chat");
				while($nbr_message=$req->fetch())
				{
					$pages=round($nbr_message['nbr_message'] / 10);				
					echo '<p><strong>il y a :'.$nbr_message['nbr_message'].'  messages, ce qui représente :'.$pages.'  pages </strong></p>';
				}	
			
				
				$req->closeCursor();
				?>
				
			<!--Sélection de la page à afficher-->
			<form method="get" action="mini_chat.php">
				<label for="page">Sélection de la page</label>
				<input type="number" name="page" max="<?php echo$pages?>" id="page"/>
				<input type="submit" value="afficher la page"/>
			</form>
			
<?php
if (!isset($_GET['page']) OR empty($_GET['page']))
{
	$page=1;
	echo'<h3>( Page:'.$page.' )</h3>';

	$min=0;
}
else
{
	$page=htmlspecialchars($_GET['page']);
	echo'<h3>( Page:'.$page.' )</h3>';
	$max=10*$page;
	$min=$max-10;
	
}
			//affichage des 10 lignes de chat avec date et heure de création en format français par page
							try
							{
							$bdd = new PDO('mysql:host=localhost;dbname=test_oc;charset=utf8', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
							}
							catch(Exception $e)
							{
							die('Erreur : '.$e->getMessage());
							}

				$req = $bdd->query("SELECT pseudo,message,DATE_FORMAT(date_creation,'%d/%m/%y %hh%mmin%ss')AS date_crea FROM mini_chat ORDER BY ID DESC LIMIT $min,10");
				while ($donnee=$req->fetch())
				{
					echo '<p><strong>'.$donnee['pseudo'].':</strong>-->  <em style="color:blue;font-size:1.5em;">'. $donnee['message'].'</em><mark style="font-size:0.6em;">     '.$donnee['date_crea'].'</mark></p>';
				}
				$req->closeCursor();
				
				?>
				
				
				
		</body>
		
		
	
	
	
	
	</html>
	