PHP Image Service
=================

Is a PHP image server with image processing capabilities on the fly: 

1. resize
2. crop
3. instagram-like filters

<br />
Filtering algo by [marchibbins](https://github.com/marchibbins)<br />
[https://github.com/marchibbins/GD-Filter-testing](https://github.com/marchibbins/GD-Filter-testing)


##Requirement
PHP 5+, GD


##Installation
Extract the files in your preferred directory. Such as `/img`

Change `Variables.php` to your preference.

```php
define('IMAGEDIR','image/');    //relative to current directory, with backslash
define('CACHEDIR','tmp/');      //relative to current directory, with backslash
define('CACHETIME',1000);       //in seconds
```

Done.


Optionally, run `cleancache.php` as a cronjob to clean cache directory.
<br />


##Usage

For example, if you installed the files in the `img/` folder:

**Original Image** &nbsp; `http://example.com/img/image.jpg`

**Resized Image**: `http://example.com/img/300x400/image.jpg`  

**Width propotional resize**: `http://example.com/img/300-w/image.jpg`  

**Height propotional resize**: `http://example.com/img/400-h/image.jpg`

**Crop image** : `http://example.com/img/300x400/center/image.jpg`

**Filter image** : `http://example.com/img/vintage/image.jpg`

**Filter and resize image** : `http://example.com/img/vintage/300x400/image.jpg`

**Filter and Crop image** : `http://example.com/img/vintage/300x400/center/image.jpg`

<br /><br />
The crop parameter accept these values

`center, top, left, right, bottom, left-top, left-bottom, right-top, right-bottom`


The filter parameter accept these values

`dreamy, velvet, chrome, lift, canvas, vintage, monopin, antique, blackwhite, boost, sepia, blur`

