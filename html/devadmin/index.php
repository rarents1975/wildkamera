<!-- Dieses Script liest die Videoaufzeichnungen in einem bestimmten Verzeichnis und gibt diese in Form einer HTML Tabelle aus -->

<?php 

// Konfigurationsdatei fuer den Ort des Mandanten auslesen und Variablen setzen
$json=file_get_contents("./config.txt");
$obj = json_decode($json);
$modus=$obj->{'modus'};
$ort=$obj->{'ort'};
$server=$obj->{'server'};
$titel=$obj->{'titel'};
$streamname=$obj->{'streamname'};
$streamkey=$obj->{'streamkey'};
$passwdcheck=$obj->{'passwdcheck'};
$passwd=$obj->{'password'};
$scheduler=$obj->{'scheduler'};
$schnittfunktion=$obj->{'schnittfunktion'};
$streamlocation=$obj->{'streamlocation'};
$scrolling=$obj->{'scrolling'};
$youtubeadmin=$obj->{'youtubeadmin'};
$adminpw=$obj->{'adminpw'};
$changename=$obj->{'changename'};
?>

<!DOCTYPE html>
<html lang="de">
<head>
<link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="512x512" href="./apple-touch-icon.png">
<!-- link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
<link rel="manifest" href="../site.webmanifest">
<link rel="mask-icon" href="../safari-pinned-tab.svg" color="#5bbad5" -->
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
<title><?php echo $titel ?></title>
<?
php session_cache_limiter('nocache');
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
* {
    box-sizing: border-box;
}

body {
  margin: 0;
}

/* Style the header */
.header {
    background-color: #f1f1f1;
    padding: 10px;
    font-size:100%;
}

/* Style the content */
.content {
    background-color: #ddd;
    padding: 10px;
    font-size:80%;
}

/* Style the footer */
.footer {
    background-color: #f1f1f1;
    padding: 5px;
}
</style>
</head>

<body>

<div class="header">
  <h2><?php echo $titel ?></h2>
  <h3>Administrations-Oberfl&auml;che</h3>

</div>

<div class="content">

<?php

// Pfad zur aktuellsten Aufzeichnung festlegen und Lesen der Datei Informationen
$aufzeichnung = "/video_recordings/".$ort."/aufzeichnung.mp4";

?>

<a href="predeleteall.html"><img src="../images/delete.png" title="Alles L&ouml;schen" 
width="20" height="20"></a>&nbsp; <a href="predeleteall.html">Alles L&ouml;schen</a><p>

<h4>Aufnahmen:</h4>
<div class="w3-responsive">

<table class="w3-table-all w3-small w3-hoverable w3-card-4 w3-centered"><tr>
<th>Vorschau</th>
<th>Tag</th>
<th>Datum</th>
<th>Uhrzeit</th>
<th>Bild<br>l&ouml;schen</th>
</tr></b>

<?php
function byte_ausrechnen($byte) { 
     
    if($byte < 1024) { 
        $ergebnis = round($byte, 2). ' Byte'; 
    }elseif($byte >= 1024 and $byte < pow(1024, 2)) { 
        $ergebnis = round($byte/1024, 2).' KByte'; 
    }elseif($byte >= pow(1024, 2) and $byte < pow(1024, 3)) { 
        $ergebnis = round($byte/pow(1024, 2), 2).' MByte'; 
    }elseif($byte >= pow(1024, 3) and $byte < pow(1024, 4)) { 
        $ergebnis = round($byte/pow(1024, 3), 2).' GByte'; 
    }elseif($byte >= pow(1024, 4) and $byte < pow(1024, 5)) { 
        $ergebnis = round($byte/pow(1024, 4), 2).' TByte'; 
    }elseif($byte >= pow(1024, 5) and $byte < pow(1024, 6)) { 
        $ergebnis = round($byte/pow(1024, 5), 2).' PByte'; 
    }elseif($byte >= pow(1024, 6) and $byte < pow(1024, 7)) { 
        $ergebnis = round($byte/pow(1024, 6), 2).' EByte'; 
    } 

return $ergebnis; 
     
}

function wochentag($tag) { 
     
    if($tag=="Sunday") { 
        $weekday = "Sonntag"; 
    }elseif($tag=="Monday") { 
       $weekday = "Montag"; 
    }elseif($tag=="Tuesday") { 
       $weekday = "Dienstag"; 
    }elseif($tag=="Wednesday") { 
       $weekday = "Mittwoch";
    }elseif($tag=="Thursday") { 
       $weekday = "Donnerstag";
    }elseif($tag=="Friday") { 
       $weekday = "Freitag";
    }elseif($tag=="Saturday") { 
       $weekday = "Samstag";
    }

return $weekday; 
     
}

// Pfad zum Ordner mit allen Video-Aufzeichnungen 
$ordner = "/video_recordings/".$ort."/images/"; //auch komplette Pfade moeglich ($ordner = "download/files";)

// Ordner auslesen und Array in Variable speichern
$alledateien = scandir($ordner, 1); // Sortierung A-Z
// Sortierung Z-A mit scandir($ordner, 1)               				

// Schleife um Array "$alledateien" aus scandir Funktion auszugeben
// Einzeldateien werden dabei in der Variabel $datei abgelegt
foreach ($alledateien as $datei) {

	// Zusammentragen der Dateiinfo
	$dateiinfo = pathinfo($ordner."/".$datei); 
	//Folgende Variablen stehen nun zur Verfuegung
	// $dateiinfo['filename'] = Dateiname ohne Dateiendung  *erst mit PHP 5.2
	// $dateiinfo['dirname'] = Verzeichnisname
	// $dateiinfo['extension'] = Dateityp -/endung
	// $dateiinfo['basename'] = voller Dateiname mit Dateiendung

	// scandir liest alle Dateien im Ordner aus, zusaetzlich noch "." , ".." als Ordner
	// Nur echte Dateien anzeigen lassen und keine "Punkt" Ordner
	// aufzeichnung.mp4 ist die aktuellste Aufzeichnung. Diese soll nicht in der Tabelle erscheinen
 	if ($datei != "." && $datei != "..") {
          if ($dateiinfo['extension'] == "jpg") { 
          // Dateigroesse der jpg-Dateien feststellen
          $size = filesize($ordner."/".$dateiinfo['filename'].".jpg");
          // Splitten des Dateinamens
          $dateiname=$dateiinfo['filename'];
          $teile = explode("-", $dateiname);
          // test-1481403588-Saturday-10-12-16-21-59
          // $teile[0] = test
          // $teile[1] = string
          // $teile[2] = Wochentag (Name)
          // $teile[3] = Wochentag (Zahl)
          // $teile[4] = Monat
          // $teile[5] = Jahr
          // $teile[6] = Stunde
          // $teile[7] = Minute
          // ...
         $tag=$teile[2];

         // Nur Tabellenzeilen fuer Dateien erzeugen die groesser 0 KB sind
         if($size > 0) { 
           echo "<tr>";
           echo"<td><a href=\"http://".$server."/".$ort."recordings/images/".$dateiinfo['filename'].".jpg\"><img src=\"http://".$server."/".$ort."recordings/images/".$dateiinfo['filename'].".jpg\" width=\"100\"></a></td>";
           echo"<td>".wochentag($tag)."</td><td>".$teile[3].".".$teile[4].".".$teile[5]."</td><td>".$teile[6].":".$teile[7]."</td>";
           echo"<td><a href=\"./predelete.html?filename=".$dateiinfo['filename']."\"><img src=\"../images/delete.png\" title=\"Video l&ouml;schen\" width=\"20\" height=\"20\"></a></td>";
           echo"</tr>"; 

       } // Ende if-Schleife fuer Datei Groessenabfrage
      }; // Ende if-Schliefe fuer erste Abfrage	
     }; // Ende if-Schleife fuer Datei Extension Abfrage
 };
?>     

</table></div><p>

</div>

</body>
</html>
