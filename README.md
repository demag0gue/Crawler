# Crawler

Dieses Crawler liest Informationen aus dem [Untis](http://www.grupet.at/) Vertretungsplan aus und speichert diese auf dem Server. Zusätzlich liest er eine Liste der unterrichtenden Lehrer aus und speichert sie ebenfalls auf dem Server. Dabei meldet der Crawler sich automatisch bei [iServ](https://iserv.eu/) an.

Das Projekt ist Teil meiner Stundenplan App und soll als Inspiration für ähnliche Projekte helfen.

Zum Umsetzen wurde neben [Simple HTML DOM](http://simplehtmldom.sourceforge.net/) auch [PdfParser](http://pdfparser.org) genutzt. Es war notwendig den PdfParser leicht zu modifzieren. Die notwendige Datei dazu befindet sich [hier](https://github.com/demag0gue/crawler/blob/master/Smalot/PdfParser/Object.php).

# Todo
* Dokumentation hinzufügen
