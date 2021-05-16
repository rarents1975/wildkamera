<?php 

// Das php Script wird vom rksviewer als api aufgerufen und uebergibt die Aufnahmen die fuer den Mandant  
// in /video_recordings/<mandant> 

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

//$handle = fopen ( "./aufnahmen.txt", "w" );

// Pfad zum Ordner mit allen Video-Aufzeichnungen 
$ordner = "/video_recordings/".$ort; //auch komplette Pfade moeglich ($ordner = "download/files";)

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
  if ($datei != "." && $datei != ".."  && $datei != "aufzeichnung.mp4" && $datei != "scrolling.mp4") {

   if ($dateiinfo['extension'] == "mp4") { 
    // Dateigroesse der flv-Dateien feststellen
    $size = filesize($ordner."/".$dateiinfo['filename'].".mp4");
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

// Nur Zeilen schreiben fuer Dateien (mp4s) die groesser 0 KB sind
if($size > 0) { 
  $datei=$dateiname.";".wochentag($tag).";".$teile[3]."-".$teile[4]."-".$teile[5].";".$teile[6].":".$teile[7].";".date("H:i", filemtime($ordner."/".$dateiinfo['basename'])).";".byte_ausrechnen($size)."\n";
  //fwrite ( $handle, $datei );

  $methode = $_SERVER['REQUEST_METHOD'];
 
  switch ($methode) {
   case 'GET':
     echo ($datei);
     break;
  }


} // Ende if-Schleife fuer Datei Groessenabfrage
}; // Ende if-Schliefe fuer erste Abfrage 
}; // Ende if-Schleife fuer Datei Extension Abfrage
};

//fclose ( $handle );

?>
