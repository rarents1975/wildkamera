Funktion
- Das Programm STARTSCROLLER.sh erzeugt eine mp4 aus einem Text
- Der Text muss dazu in der Datei text.txt stehen (als eine Zeile)
- Das Ergebnis steht dann auf ergebnis.mp4
- alle benötigten Files sten im Verzeichnis scrollerups

Bedienung:
- sudo bash STARTSCROLLER.sh
-> Damit wird der File ergebnis.mp4 neu erzeugt

Hinweise:
- Das Format der mp4 Datei ist 1280 x 720
- sie wird aber auf die Hohe 100 reduziert (läuft dann unten am Bildschirm)
- die Farbvorgabe erfolgt über das jpg (ist auf gelb.jpg eingestellt)
- vor den Nutzung muß in STARTSCROLLER.sh der pfad angepasst werden
   pfad="/home/pi/scrollerups"       # Dieser Pfad muss editiert werden
- die Datei ergebnis.mp4 mss dann als scrolling.mp4 in /video-recordings/>mandant> geschrieben werden

Bedienung:
- Anzeige des Lauftextets erfolgt dann erst mit der RKS-Viewer Box, diese ist ja in den Pfegeheimen fest installiert
- Dort wird alle 2 min die Datei scrolling.mp4 aus dem jeweiligen Mandanten heruntergeladen
- wenn es keine gibt, gibt es halt keinen Lauftext
- Dann wird mit dem omxplayer die scolling.mp4 als Endlosschleife dargestellt und es erscheint ain gelbes Band mit laufendem Text
- Das dient gliechzeitig als Internetkontrolle. Wenn kein Internet da ist, verschwindet nach 1 -2 Min der gelbe Lauftext


Befuellung des Textes  (ist noch nicht gemacht, Ralf, kannst du das machern ?)
- Im Admin Frontend des Mandanten (erst mal nur in Gechingen, dann in Stammheim) gibt es einen Knopf Lauftext
- drückt man den, erscheint ein Eingabefenster, in dem man bis max 100 Zeichen eintippen kann
- Bei Start wird der bisherige Lauftext gezeigt (das was in text.txt steht)
- Mit einem Knop Speichern, wird die Datei text.txt neu geschrieben und das Script STARTSCROLLER.sh gestartet. 
- Dies muss so erweitert werden, dass es den File ergebnis.mp4 am Schluss auch scrolling.mp4 kopiert

