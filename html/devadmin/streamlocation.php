<!-- Dieses Script wird aus index.php aufgerufen (ueber include), wenn in der config.txt 
die Variable streamlocation=on gesetzt ist
Es wird dann im Adminforntend eine Auswahl der Kirchen angezeigt, von denen der Gottesdienst gestreamt werden soll
Zugehoerige Dateien:
locationapi.php => wird ueber https://rk-solutions-stream.de/stammheimadmin/locationapi.php aufgerufen u. zeigt an, 
welcher godi gestreamt wird. 
streamlocation.txt => hier steht nur der Name der Kirche drin, welcher uber locationapi.php ausgelesen wird 
-->


<?php
$location = file_get_contents('./streamlocation.txt');
?>

<p>
<form name="form1" action="index.php" method="get">

  <p><b>Auswahl des Gottesdienstes f&uuml;r das Live-Streaming:</b></p>

    <input type="radio" id="stammheim" name="streamlocation" value="stammheim" <?php if ($location == "stammheim") { echo "checked"; } ?>>
    <label for="stammheim"> Stammheim</label>&nbsp;
    <input type="radio" id="gechingenn" name="streamlocation" value="gechingen" <?php if ($location == "gechingen") { echo "checked"; } ?>>
    <label for="gechingen"> Gechingen</label>&nbsp;
<input name="" type="submit" class="submit" value="Speichern" />
</form>

<?php 
if ( $_GET['streamlocation'] <> "" )
{
    // und nun die Daten in die Datei streamlocation.txt den selektierten Namen schreiben
    // Datei wird zum Schreiben geöffnet
    $handle = fopen ( "./streamlocation.txt", "w" );

    // ----- Erste Zeile -----
    fwrite ( $handle, $_GET['streamlocation'] );
    // Datei schließen
    fclose ( $handle );
    // Seitenrefresh, damit neuer Wert in die Radio Box im Admin Frontend uebernommen wird.
    echo "<meta http-equiv=\"refresh\" content=\"0; URL=".$modus."://".$server."/".$ort."admin/index.php\"> \n";
}

?>
