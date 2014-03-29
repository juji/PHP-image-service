PHP Image Service
=================

Is a PHP server for serving, resizing images at run-time.<br />
It also adds filtering capabilities like instagram.

<br />
Filtering algo by [marchibbins](https://github.com/marchibbins)<br />
[https://github.com/marchibbins/GD-Filter-testing](https://github.com/marchibbins/GD-Filter-testing)


##Requirement
PHP 5+, GD


##example

For example, if you installed your image in the `img/` folder:

**Original Image** &nbsp; `http://example.com/img/image.jpg`

**Resized Image**: `http://example.com/img/300x400/image.jpg`  

**Width propotional resize**: `http://example.com/img/300-w/image.jpg`  

**Height propotional resize**: `http://example.com/img/400-h/image.jpg`

**Crop image** : `http://example.com/img/300x400/center/image.jpg`

**Filter image** : `http://example.com/img/vintage/image.jpg`

**Filter and resize image** : `http://example.com/img/vintage/300x400/image.jpg`

**Filter and Crop image** : `http://example.com/img/vintage/300x400/image.jpg`

<br /><br />
The crop parameter accept these values

`center, top, left, right, bottom, left-top, left-bottom, right-top, right-bottom`


The filter parameter accept these values

`dreamy, velvet, chrome, lift, canvas, vintage, monopin, antique, blackwhite, boost, sepia, blur`

