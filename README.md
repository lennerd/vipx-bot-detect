# VipxBotDetect

This library helps you detecting bots like Google, Yahoo or Bing.

[![Build Status](https://travis-ci.org/lennerd/vipx-bot-detect.svg?branch=master)](https://travis-ci.org/lennerd/vipx-bot-detect)
[![Coverage Status](https://img.shields.io/coveralls/lennerd/vipx-bot-detect.svg)](https://coveralls.io/r/lennerd/vipx-bot-detect?branch=master)

## Installation using [composer](https://getcomposer.org/)

``` bash
$ php composer.phar require vipx/bot-detect
```
## Usage

``` php
use Vipx\BotDetect\BotDetector;
use Symfony\Component\Config\FileLocator;
use Vipx\BotDetect\Metadata\Loader\YamlFileLoader;

# Instantiate Symfony components required to load and parse YAML files.
$locator = new FileLocator();
$loader = new YamlFileLoader($locator);

# Use extended bot list prodivded in Resources directory.
$metadataFile = './Resources/metadata/extended.yml';

# Instantiate a BotDetector with the YamlFileLoader instance and path to YAML.
$detector = new BotDetector($loader, $metadataFile);

# Call detect() on BotDetector, passing in a user agent string and IP address,
# most commonly found in $_SERVER['HTTP_USER_AGENT'] and $_SERVER['REQUEST_ADDR']
# respectively.
# detect() will return a Vipx\BotDetect\Metadata\Metadata object containing the
# details of a matched bot and null on no match.
$bot = $detector->detect($agent, $ip);
```

**Note:** To greatly improve performance, `BotDetector` has a built in cache, which can be used to speed up parsing and loading of metadata.

``` php
# Instantiate a BotDetector with additional options.
$detector = new BotDetector($loader, $metadataFile, [
  'debug' => $debug,
  'cache_dir' => '/acme/cache',
]);
```

Via additional options `metadata_cache_file` and `metadata_dumper_class` you can further control how cache files are named or generated.

## ToDo's

- Add additional meta data loaders (xml, php)
- Replace static fixtures with dynamically added data for example from an external archive with some kind of public API or other repository e.g. on Github or Bitbucket

## Credits

The first version of list of bots is coming from `Manage_Bots` script created by the phpBB community. The following community members and contributors worked on the script and its list of bots:

### phpBB Community
[Marcus Wendel](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=6152), [ReptileGuy](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=447165), [Young Jedi Knight](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=962935), [Pony99CA](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=143537), [Clava](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=353237), [ricjonhay](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1222875), [roBBx](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=467205), [Sr X](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=298330), [HGN](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=821755), [AmigoJack](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1289605), [millipede](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=482675), [maxwell2](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=951565), [StandBy](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1288269), [ade74](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1296329), [heredia21](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1273765), [TheSnake](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=265871), [natalia26](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1287940), [Puchahawa](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1305487), [T50](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=317815), [Peter77sx](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=216463), [Schwpz](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=61230), [Vinny](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1065865), [lanesharon](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=94198), [leschek](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=261820), [fac7orx](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=848165), [Joshua203](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1047325), [Paul](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=153860), [doktornotor](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=1330502), [stokerpiller](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=302443) and [raimon](http://www.phpbb.com/community/memberlist.php?mode=viewprofile&u=253197)

### Contributors
[COil](https://github.com/COil), [jdeniau](https://github.com/jdeniau), [rubenrua](https://github.com/rubenrua), [smilesrg](https://github.com/smilesrg), [jbboehr](https://github.com/jbboehr)

**If you find one or more missing bots, simple fork this repository and add them to the fitting [metadata files](https://github.com/lennerd/vipx-bot-detect/tree/master/Resources/metadata).**
