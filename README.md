php-translator
==============

Gettext based translator for php with macros for [Nette Latte](http://nette.org/) templates.

Installation
------------
php-translator can be installed with composer. Just add `"voda/php-translator": "*"` to your require section of composer.json. This library depends on [php-gettext](https://launchpad.net/php-gettext), which unfortunately isn't a composer package. However you can add the following code to your composer.json file and the composer will install it for you.

```json
{
	"repositories": [
		{
			"type": "package",
			"package": {
				"name": "php-gettext/php-gettext",
				"version": "1.0.11",
				"dist": {
					"type": "tar",
					"url": "https://launchpad.net/php-gettext/trunk/1.0.11/+download/php-gettext-1.0.11.tar.gz"
				},
				"autoload": {
					"classmap": [
						"gettext.php",
						"streams.php"
					]
				}
			}
		}
	],
	"require": {
		"php-gettext/php-gettext" : "1.0.*"
	}
}
```

License
-------
php-translator is licensed under the New BSD License.

Copyright
---------
* 2011 Antee, s.r.o.
* 2011 Ondřej Vodáček
