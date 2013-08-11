WebExam4Gewinnt
===============

Web Engeneering 2nd semester exam "4 Gewinnt"

Installationsanweisungen
========================

Das spiel ansich ist leicht zu konfigurieren.
Nach speichern des Codes in einem apache htdocs verzeichnis und einspielen
des Datenbank dumps müssen folgede Konfigurationen vorgenommen werden:

/conf/conf.php
==============
Datenbank Konfiguration vornehmen (ab line: 22):
define('RESOURCE_TYPE',         'DB');
define('RESOURCE_SYSTEM',       'MySQL');
define('RESOURCE_DB_HOST',      '127.0.0.1');
define('RESOURCE_DB_PORT',      '3306');
define('RESOURCE_DB_NAME',      'webexam');
define('RESOURCE_DB_USER',      'webexam');
define('RESOURCE_DB_PASSWORD',  'meinpw');

Web root angeben (notwendig für URL rewrite):
define('CFG_WEB_ROOT', '/WebExam4Gewinnt');

Hier muss der gesammte pfad ab der domain angegeben werden. Dabei ist zu bedenken
das die index.php im unterordner htdocs im Projekt liegt. Sollte daher keine umleitung
des root ordner nach htdocs vorgenommen werden über einen apache conf alias muss htdocs
mit in den web root pfad (anders gesagt der web root pfad ist der komplette pfad bis zur
index.php).

/htdocs/.htaccess
=================

In der .htaccess Datei muss der richtige pfad zur index.php angegeben werden ab root
RewriteRule .* /WebExam4Gewinnt/index.php [L]

ACHTUNG: Dies ist der gleiche pfad wie der für CFG_WEB_ROOT und muss ab der domain sein
damit der rewrite funktioniert und somit die seite.

E-Mails
=======

Sollte das Projekt auf einem windows XAMPP laufen werden die emails im mailoutput ordner
im XAMPP root verzeichnis abgelegt und nicht versand.