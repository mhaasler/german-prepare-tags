german-prepare-tags
====================

PHP-Klasse für Verschlagwortung von deutschsprachigen Texten anhand von Wortstämmen.

Es werden außerdem englischsprachige Texte unterstützt, wobei eine automatische Spracherkennung stattfindet.

Optional kann das ausgegebene Schlagwort auch den Phonetikwert (analog zum php-Befehl ``` soundex() ``` ) 
für die deutsche Sprache nach dem Kölner Verfahren enthalten.
Dabei wird das SoundexGer Package von Andy Theiler genutzt.

Installation
-------------

Empfohlen mit [Composer](http://getcomposer.org/):

    curl -sS https://getcomposer.org/installer | php

Danach im Projekt die composer.json Datei anpassen,

    {
        "repositories": [ { "type": "vcs", "url": "https://github.com/mhaasler/german-prepare-tags.git"} ],
        "minimum-stability": "dev",
        "require": {
             "mhaasler/germanprepare": "dev-master"
        }
    }

jetzt mit Composer installieren,

    php composer.phar install

und den composer autoloader einbinden.

```php
require 'vendor/autoload.php';
```
Benötigt
-------------

 * php >=5.6
 * [paslandau/german-stemmer](https://github.com/paslandau/german-stemmer)
 * [webmil/text-language-detect](https://github.com/webmil/text-language-detect)
 
Handhabung
-------------
 
 ```php 
 <?php
 
 use mhaasler\GermanPrepare\GermanPrepare;
 
 $text = "Mein Text für die automatische Indexierung.";
 
 $germanPrepare = new GermanPrepare($text);
 
 var_dump (
    $germanPrepare->setMod('stem')->getTags()
  );
 
 ```
 
 Output:
```php 
     array (size=3)
      'text' => 
        object(mhaasler\GermanPrepare\Model\GermanPrepareModel)[414]
          protected 'orig' => string 'Text' (length=4)
          protected 'clean' => string 'text' (length=4)
          protected 'stem' => null
          protected 'soundex' => null
          protected 'occour' => int 1
      'automatische' => 
        object(mhaasler\GermanPrepare\Model\GermanPrepareModel)[415]
          protected 'orig' => string 'automatische' (length=12)
          protected 'clean' => string 'automatische' (length=12)
          protected 'stem' => null
          protected 'soundex' => null
          protected 'occour' => int 1
      'indexierung' => 
        object(mhaasler\GermanPrepare\Model\GermanPrepareModel)[416]
          protected 'orig' => string 'Indexierung' (length=11)
          protected 'clean' => string 'indexierung' (length=11)
          protected 'stem' => null
          protected 'soundex' => null
          protected 'occour' => int 1
     
```

Das GermanPrepareModel hat folgender Getter-Klassen

+ `getOrig()` 

    > gibt das Originalwort zurück
 
+ `getClean()` 

    > gibt das bereinigte Wort zurück

+ `getStem()` 

    > gibt den Wortstamm zurück

+ `getSoundex()` 

    > gibt den phonetischer Algorithmus nach dem Kölner Verfahren zurück
 
+ `getOccour()` 

    > gibt die Anzahl der Vorkommen zurück


     
Optionen
------

+ `setMod( int $mod )` 

    GermanPrepare::MOD_STEM_PHON (default) 
    
    > gibt phonetischen Wert UND Wortstamm UND bereinigtes Wort zurück
    
    GermanPrepare::MOD_ONLY_PHON 
    
    > gibt phonetischen Wert UND bereinigtes Wort zurück
    
    GermanPrepare::MOD_ONLY_STEM
    
    > gibt Wortstamm UND bereinigtes Wort zurück
    
    GermanPrepare::MOD_ONLY_TRIM
    
    > gibt bereinigtes Wort zurück


Die jeweilig weggelassene Eigenschaft gibt beim Getter dann NULL.


+ `setModCompare( string $mod)` 

     ohne Parameter
    > es werden exakt die Stopwörter aus der Stopwortliste entfernt
     "stem" (empfohlen)
    > es werden alle Wörter entfernt, die den gleichen Wortstamm mit der Wortstammstopwortliste haben.

+ `setText( string $text, $setLang = true)` 

    Parameter string $text
    > beliebiger Text
     $setLang (default: true)
    > bool Wert, ob der die Textsprache automatisch erkannt werden soll

+ `setStopWords(array $stopWords)` 

    Parameter array $stopWords
    > setzt eine eigene Stopwortliste (default: /src/Utility/data/stopwordlist_*.json)

+ `setLang( string $lang )` 

     Parameter "de" oder "en"
    > default: "de"
     
Autor
------
     
 M. Haasler - m.haasler@gmx.de
     
Lizenz
-------
     
http://www.fsf.org/ GNU GENERAL PUBLIC LICENSE