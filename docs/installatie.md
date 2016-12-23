<h1>Installatie instructie</H1>
<b>Uitgangspunten</b>
- op de webserver draait php.
- in php.ini staat: short_open_tag = On.
- je kunt bestanden uploaden naar en mappen aanmaken op de webserver.
- je weet hoe je een database moet aanmaken op een webserver.
- je weet hoe je lees- en schrijfrechten op een webserver moet aanpassen.

<b>Voorbereiding</b>
- maak een database aan op de webserver en stel een gebruikersnaam en wachtwoord in. Onthoud deze. Je hebt ze straks nodig.
- maak een map aan op de websever. Deze map zal de url vormen voor de webapplicatie.

<b>Installatie</b>
- Download <b>Eland</b> van github (https://github.com/gtweening/eland/archive/master.zip).
- Pak het zip-bestand uit.
- Bewerk eland-master/inc/constants.inc.php en vul de juiste gebruikersnaam, wachtwoord, en databasenaam in.
- Kopieer de bestanden en mappen in de map 'eland-master' naar de zojuist aangemaakte map op de webserver.
- Zorg ervoor dat je leesrechten hebt op alle zojuist aangemaakte mappen en bestanden.
- Zorg ervoor dat je lees- en schrijfrechten hebt op de map: /img/Obstacles/ <br>
  In deze map worden afbeeldingen die worden geupload opgeslagen.
- Installeer de tabellen door in de browser het volgende bestand te starten: [server]/[map]/php/_createElandTables20.php<br>
  Bijvoorbeeld: http://www.survivalvereniging.nl/eland/php/_createElandTables20.php<br>
  Tijdens installatie krijg je twee meldingen: succesvol verwijderen van bestaande tabellen en succesvol aanmaken van tabellen. Na installatie wordt automatisch doorverwezen naar de startpagina. Je kunt nu inloggen en starten met het bijhouden van het logboek.

<br>
Ga verder met het doorlezen van de <a href="korteHandleiding.md">korte handleiding</a>.

