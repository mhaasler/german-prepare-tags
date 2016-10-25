german-prepare-tags
====================

PHP class for tagging German language texts based on verbal stems.

Supports for English language.

Optionally, the specified keyword can also iclude the phonetic value 
for the German language according to the Cologne algorithm (like php ``` soundex() ``` )

Used SoundexGer Package by Andy Theiler.


installation
-------------

Recommended [Composer](http://getcomposer.org/):

    curl -sS https://getcomposer.org/installer | php

and  extend composer.json

    {
        "repositories": [ { "type": "vcs", "url": "https://github.com/mhaasler/german-prepare-tags.git"} ],
        "minimum-stability": "dev",
        "require": {
             "mhaasler/germanprepare": "dev-master"
        }
    }

install 

    php composer.phar install

and require composer autoloader.

```php
require 'vendor/autoload.php';
```
Required
-------------

 * php >=5.6
 * [paslandau/german-stemmer](https://github.com/paslandau/german-stemmer)
 * [webmil/text-language-detect](https://github.com/webmil/text-language-detect)
 
Using
-------------
 
 ```php 
 <?php
 
 use mhaasler\GermanPrepare\GermanPrepare;
 
 $text = "Mein Text für die automatische Indexierung.";
 
 $germanPrepare = new GermanPrepare($text);
 
 var_dump (
    $germanPrepare->setModCompare('stem')->getTags()
  );
 
 ```
 
 Output:
```php 
array (size=3)
  'text' => 
    object(mhaasler\GermanPrepare\Model\GermanPrepareModel)[415]
      protected 'orig' => string 'Text' (length=4)
      protected 'clean' => string 'text' (length=4)
      protected 'stem' => string 'text' (length=4)
      protected 'soundex' => string '2482' (length=4)
      protected 'occour' => int 1
  'automatische' => 
    object(mhaasler\GermanPrepare\Model\GermanPrepareModel)[416]
      protected 'orig' => string 'automatische' (length=12)
      protected 'clean' => string 'automatische' (length=12)
      protected 'stem' => string 'automat' (length=7)
      protected 'soundex' => string '02628' (length=5)
      protected 'occour' => int 1
  'indexierung' => 
    object(mhaasler\GermanPrepare\Model\GermanPrepareModel)[417]
      protected 'orig' => string 'Indexierung' (length=11)
      protected 'clean' => string 'indexierung' (length=11)
      protected 'stem' => string 'indexier' (length=8)
      protected 'soundex' => string '06248764' (length=8)
      protected 'occour' => int 1
     
```

The GermanPrepareModel has follow classes

+ `getOrig()` 

+ `getClean()` 

+ `getStem()` 

+ `getSoundex()` 

+ `getOccour()` 


     
Options
------

+ `setMod( int $mod )` 

    GermanPrepare::MOD_STEM_PHON (default) 
    
    GermanPrepare::MOD_ONLY_PHON 
    
    GermanPrepare::MOD_ONLY_STEM
    
    GermanPrepare::MOD_ONLY_TRIM
    


+ `setModCompare( string $mod)` 

     without parameter
    > exactly the stop words are removed from the stop word list
     "stem" (reqired)
    > use stem for compare the words to be deleted

+ `setText( string $text, $setLang = true)` 

    parameter string $text
    > any Text
     $setLang (default: true)
    > bool value, language recognition

+ `setStopWords(array $stopWords)` 

    parameter array $stopWords
    > you own stop word list  (default: /src/Utility/data/stopwordlist_*.json)

+ `setLang( string $lang )` 

     parameter "de" or "en"
    > default: "de"
     
Author
------
     
 M. Haasler - m.haasler@gmx.de
     
Licence
-------
     
http://www.fsf.org/ GNU GENERAL PUBLIC LICENSE