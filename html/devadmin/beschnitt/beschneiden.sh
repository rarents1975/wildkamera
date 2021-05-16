#!/bin/bash
# Der erste Parameter ist der Name der flv Datei, die bearbeitet wird
# Diese wird modifiziert, davor wird eine Kopie im Arbeitsverzeichnis angelegt
# Es werden immer Paare von bis übergeben, dies sind sek, die die Abschnitte definieren, die gekloescht werden
# Die Paare stehen in der Datei weg.txt
# Die ersten beiden Zeilen von weg.txt haben Sonderfunktion: vorneweg und hintenweg
#
# 0.) Vorgaben bitte anpassen und pruefen
#
dump=0     # bei dump=0 wird kein echo geschrieben
datei=$1   # Dateiname der flv
if [ $dump -eq 1 ]; then echo "Programm besschneiden.sh gestartet für datei="$datei;fi
anfang=$2  # Zeit in sec bis zu der vorne weggeschnitten wird
ende=$3  # Zeit in sec ab der hinten abgeschnitten wird (falls =0 oder ="" wird hinten nichts geschnitten)
von1=$4  # von bis Zeiten, die innen abgeschnitten werden (aufsteigend und nicht überlappend)
bis1=$5
von2=$6
bis2=$7
von3=$8
bis3=$9
if [ $dump -eq 1 ]
then
echo "eingabeparameter von beschneiden.sh"
echo $1
echo $2
echo $3
echo $4
echo $5
echo $6
echo $7
echo $8
echo $9
fi
xxx=$(pwd)   #liefert /usr/local/nginx/html/irgendingenadmin/beschnitt/
#echo $xxx
modul=${xxx##*/}  # liefert irgendingenadmin
modul=${modul%%admin}  #liefert irgendingen
#echo $modul
arbeit="/usr/local/nginx/html/"$modul"admin/beschnitt/" # Arbeitsverzeichnis muss existieren
pfadflv="/video_recordings/"$modul"/"$datei   # absoluter pfad zum flv file, der beschnitten wird
if [ $dump -eq 1 ]; then echo "----- START ----------------------------------------------------------------";fi
sudo rm $arbeit"weg.txt"  # Hilfsdatei wird geloescht
if [ $dump -eq 1 ]; then echo "Vorgabdatei=" $pfadflv;fi
#echo $pfadflv
if [ $dump -eq 1 ]; then echo Arbeitspfad= $arbeit;fi
echo "$anfang" > $arbeit"weg.txt"
echo "$ende" >> $arbeit"weg.txt"
echo "$von1 $bis1" >> $arbeit"weg.txt"
echo "$von2 $bis2" >> $arbeit"weg.txt"
echo "$von3 $bis3" >> $arbeit"weg.txt"
#
#1.)  Mit dieser function wird die laenge eines flvs ermittelt
#
function laengeflv() {
xx=$(/home/rarents/bin/ffmpeg -i $1 2>&1 | grep "Duration"| cut -d ' ' -f 4)
hh=${xx:0:2}
hh=$(echo "$hh" | bc -l)
mm=${xx:3:2}
mm=$(echo "$mm" | bc -l)
ss=${xx:6:2}
ss=$(echo "$ss" | bc -l)
ls=$((hh*60*60+mm*60+ss))
echo $ls
}
#
#2.) Eingaben pruefen
lg=$(laengeflv $pfadflv) # Laenge in sec des vorgegebenen flvs
if [ $dump -eq 1 ]; then echo Vorgabedatei= $pfadflv;fi
if [ $dump -eq 1 ]; then echo "Laenge der Vorgabedatei=" $lg;fi
typeset -i lauf=0
bisa=0
anzahl=0   # anzahl ist die Anzahl nichtleerer Zeilen in der Datei weg.txt
while read line
do
#  echo " In read Schleife , lauf=" $lauf
  lauf=$lauf+1
#  echo lauf= $lauf auf line steht $line
# Zeile 1 und 2 spielen eine Sonderrolle
  if [ $lauf -eq 1 ]; then anfangweg=$line; fi
  if [ $lauf -eq 2 ]; then endeweg=$line; fi
  if [ $lauf -lt 3 ]; then anzahl=$lauf; fi
  if [ $lauf -gt 2 ]
  then
    von=${line%" "*}
    bis=${line#*" "}
#    echo von= $von bis= $bis lg= $lg bisa= $bisa anfangweg= $anfangweg endeweg= $endeweg
    if [ "$von" != "" ] && [ $von -ne 0 ] && [ "$bis" != "" ] && [ $bis -ne 0 ]
    then
      ((anzahl=$anzahl+1))
      if [ $bis -le $von ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
      if [ $bis -ge $lg ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
      if [ $von -le $bisa ] || [ $von -le $anfangweg ] ; then echo ERROR-Unsinnige-Vorgabe; exit;fi
      if [ $bis -ge $endeweg ] && [ $endeweg -ne 0 ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
      bisa=$bis
      vonn[$lauf]=$von
      biss[$lauf]=$bis
    fi
  fi
done < $arbeit"weg.txt"
# Prüfung
if [ $dump -eq 1 ]; then echo "***** EINGABEDATEN *********************************";fi
if [ $dump -eq 1 ]; then
  echo anfangweg=$anfangweg
  echo endeweg=$endeweg
  echo lg=$lg
fi
if [ "$anfangweg" == "" ]; then anfangweg=0;fi
if [ "$endeweg" == "" ]; then endeweg=0;fi
if [ $anfangweg -lt 0 ] || [ $anfangweg -ge $lg ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
if [ $endeweg -lt 0 ] || [ $endeweg -ge $lg ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
# endeweg wandeln von "Zeit ab der geschnitten" wird in "sec um die geschnitten wird"
if [ $endeweg != 0 ]
then
   ((endeweg=$lg-$endeweg))
fi
if [ $dump -eq 1 ]; then 
  echo endeweg korrigiert= $endeweg
  echo anzahl= $anzahl
fi
if [ $((anfangweg+endeweg)) -gt $lg ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
if [ $((anfangweg+endeweg)) -eq 0 ] && [ $anzahl == 2 ]; then echo ERROR-Unsinnige-Vorgabe; exit;fi
if [ $dump -eq 1 ]; then 
  echo Vorgabedaten
  echo "anzahl= " $anzahl
fi
lauf=0
if [ $dump -eq 1 ]; then
  while [ $lauf -lt $anzahl ]
  do
     lauf=$lauf+1
     if [ $lauf -eq 1 ]
     then
       echo "vorneweg= "$anfangweg
     elif [ $lauf -eq 2 ]
     then
       echo "hintenweg= "$endeweg
     else
       echo  "Schnitt weg von= "${vonn[$lauf]} " bis= "${biss[$lauf]}
     fi
  done
  echo "**************************************"
  echo Es gab " $anzahl " Vorgaben
  echo "Laenge der Ausgangangs flv Datei= "$lg
  echo "------------------- Sinnvolle Vorgabe , es geht weiter ---------------"
fi
# 3.) Anfang und Ende beschneiden

  # ----------------------- RALF ANFANG ---------------------
  # Alte Datei zerlegen, Dateinamen änder (Nummer+1), dann Zeitstempel auslesen und neu erzeugter Datei Zeitstempel + 10s zuweisen
  # a) Datei zerlegen
  IFS="."
  originaldatei="/video_recordings/"$modul"/"$datei    #kompletter Pfad der Originaldatei
  if [ $dump -eq 1 ]; then echo "originaldatei: $originaldatei ";fi
  set -- $originaldatei
  filename=$1
  if [ $dump -eq 1 ]; then echo "Dateiname: $filename ";fi   # filename ist der Anteil ohne .flv der originaldatei
  set -- $datei
  dateiohneflv=$1          # hier wird hinten alles incl. und nach dem "." weggemacht
  IFS="-"
#  set -- $filename
  set -- $dateiohneflv
  if [ $dump -eq 1 ]; then echo "Zahl: $2 ";fi
  S01="$2"
  S02="1"
  dateinummer=$((S01+S02))
  if [ $dump -eq 1 ]; then echo "Dateinummer: $dateinummer";fi
  # a) Neuen Dateinamen zusammensetzen
  IFS=""
  dateinameneu=$1"-"$dateinummer"-"$3"-"$4"-"$5"-"$6"-"$7"-"$8".flv"
  #dateinameneuimage=$1"-"$dateinummer"-"$3"-"$4"-"$5"-"$6"-"$7"-"$8".img"
  dateipfadneu="/video_recordings/"$modul"/"$dateinameneu
  #dateipfadneuimage="/video_recordings/"$modul"/images/"$dateinameneuimage
  if [ $dump -eq 1 ]; then echo "Dateipfad Neu: $dateipfadneu ";fi
  # ----------------------- RALF ENDE -----------------------
if [ $anfangweg -gt 0 ] || [ $endeweg -gt 0 ]   # vorne und/ oder hinten abschneiden
then
  if [ $dump -eq 1 ]; then echo es wird vorne $anfangweg sec  und hinten $endeweg sec abgeschnitten;fi
  dauer=$((lg-anfangweg-endeweg))
  if [ $dump -eq 1 ]; then echo Die neue Laenge ist $lg"sec - "$anfangweg"sec  - "$endeweg"sec = " $dauer"sec";fi
  /home/rarents/bin/ffmpeg -i $pfadflv -ss $anfangweg -t $dauer -c:v copy -c:a copy -y $dateipfadneu  > /dev/null 2>&1
  lg=$(laengeflv $dateipfadneu)
  korr=$anfangweg   # um diesen Wert wurde der video vorne gekuerzt
else
  cp $pfadflv $dateipfadneu
  korr=0
fi
# Jetzt noch die Datei 10 Sekunden aelter machen als das Original
touch -r $pfadflv -d "-10 seconds" $dateipfadneu
if [ $dump -eq 1 ]; then
  echo Ergebnis nach vorne und hinten abschneiden
  echo Teilergebnis $dateipfadneu hat die Laenge lg=$lg
  echo "------------------------------------------------------------------"
fi
#
# 4.) Jetzt die inneren Teile herausschneiden
#
lauf=2
# Hauptschleife
if [ $dump -eq 1 ]; then echo Jetzt im Innern Teile heausschneiden;fi
while [ $lauf -lt $anzahl ]
do
  if [ $dump -eq 1 ]; then echo "------------------------------------------------------------------";fi
  lauf=lauf+1
  lg=$(laengeflv $dateipfadneu)
  von=${vonn[$lauf]}
  bis=${biss[$lauf]}
  if [ $dump -eq 1 ]; then 
    echo "                -------" Vorgaben bei lauf= $lauf "---------------------------"
    echo "                ----------------------> " von=$von bis=$bis lg=$lg korr=$korr
  fi
  von=$((von-korr))
  bis=$((bis-korr))
  if [ $dump -eq 1 ]; then echo "                nach Korrektur mit korr " von=$von bis=$bis;fi
# generelles Verfahren, im von bis rauszuschneiden
  if [ $bis -lt $lg ] && [ $von -gt 0 ]
  then
  # die 2 Teile bestimmen
  # erster Teil geht von Anfang bis $von
    /home/rarents/bin/ffmpeg -i $dateipfadneu -t $von -c:v copy -c:a copy -y $arbeit"output1.flv"  > /dev/null 2>&1
    x1=$(laengeflv $arbeit"output1.flv")
    if [ $dump -eq 1 ]; then echo "                Laenge Teil 1 = " $x1 sec;fi
  # zweiter Teil geht von $bis bis zum Ende
    /home/rarents/bin/ffmpeg -i $dateipfadneu -ss $bis -c:v copy -c:a copy -y $arbeit"output2.flv"  > /dev/null 2>&1
    x2=$(laengeflv $arbeit"output2.flv")
    if [ $dump -eq 1 ]; then echo "                Laenge Teil 2 = " $x2 sec;fi
    if [ $dump -eq 1 ]; then echo "                 -->" Jetzt Teile zusammenfuegen;fi
    /home/rarents/bin/ffmpeg -f concat -i $arbeit"inputs.txt" -c:v copy -c:a copy -y $dateipfadneu > /dev/null 2>&1       # die zwei Teile sind in der Datei vorgabe.txt vermerkt
  # Jetzt noch die Datei 10 Sekunden aelter machen als das Original
    touch -r $pfadflv -d "-10 seconds" $dateipfadneu
  # Vorschaubild der Aufzeichnung fuer den rks-viewer anlegen
  #  /home/rarents/bin/ffmpeg -ss 00:00:02 -i $path -c:v png -frames:v 1 -y $dateipfadneuimage > /dev/null 2>&1
    xerg=$(laengeflv $dateipfadneu)
    if [ $dump -eq 1 ]; then echo "                Laenge Ergebnis= " $xerg sec;fi
    korr=$((korr+bis-von))    # koor ist der Korrekturwert fuer weiteres Beschneiden
    if [ $dump -eq 1 ]; then echo "                neuer Korrekturwert korr=" $korr;fi
  fi
done
# Hilfsdaten löschen
sudo rm -f $arbeit"output1.flv"
sudo rm -f $arbeit"output2.flv"
echo Fertig

