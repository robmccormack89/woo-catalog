<?php











namespace Composer;

use Composer\Autoload\ClassLoader;
use Composer\Semver\VersionParser;






class InstalledVersions
{
private static $installed = array (
  'root' => 
  array (
    'pretty_version' => '1.0.0+no-version-set',
    'version' => '1.0.0.0',
    'aliases' => 
    array (
    ),
    'reference' => NULL,
    'name' => '__root__',
  ),
  'versions' => 
  array (
    '__root__' => 
    array (
      'pretty_version' => '1.0.0+no-version-set',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => NULL,
    ),
    'altorouter/altorouter' => 
    array (
      'pretty_version' => '2.0.2',
      'version' => '2.0.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f6fede4f94ced7c22ba63a9b8af0bf2dc38e3cb2',
    ),
    'composer/installers' => 
    array (
      'pretty_version' => 'v2.1.1',
      'version' => '2.1.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'af93ba6e52236418f07a278033eba6959ee5b983',
    ),
    'psr/cache' => 
    array (
      'pretty_version' => '1.0.1',
      'version' => '1.0.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd11b50ad223250cf17b86e38383413f5a6764bf8',
    ),
    'psr/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0',
      ),
    ),
    'psr/container' => 
    array (
      'pretty_version' => '1.1.2',
      'version' => '1.1.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '513e0666f7216c7459170d56df27dfcefe1689ea',
    ),
    'psr/container-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/event-dispatcher' => 
    array (
      'pretty_version' => '1.0.0',
      'version' => '1.0.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dbefd12671e8a14ec7f180cab83036ed26714bb0',
    ),
    'psr/event-dispatcher-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0',
      ),
    ),
    'psr/log' => 
    array (
      'pretty_version' => '1.1.4',
      'version' => '1.1.4.0',
      'aliases' => 
      array (
      ),
      'reference' => 'd49695b909c3b7628b6289db5479a1c204601f11',
    ),
    'psr/log-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0',
      ),
    ),
    'psr/simple-cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0',
      ),
    ),
    'symfony/cache' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => 'ba06841ed293fcaf79a592f59fdaba471f7c756c',
    ),
    'symfony/cache-contracts' => 
    array (
      'pretty_version' => 'v2.5.1',
      'version' => '2.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '64be4a7acb83b6f2bf6de9a02cee6dad41277ebc',
    ),
    'symfony/cache-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0',
      ),
    ),
    'symfony/config' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '05624c386afa1b4ccc1357463d830fade8d9d404',
    ),
    'symfony/dependency-injection' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '35588b2afb08ea3a142d62fefdcad4cb09be06ed',
    ),
    'symfony/deprecation-contracts' => 
    array (
      'pretty_version' => 'v2.5.1',
      'version' => '2.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e8b495ea28c1d97b5e0c121748d6f9b53d075c66',
    ),
    'symfony/error-handler' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '060bc01856a1846e3e4385261bc9ed11a1dd7b6a',
    ),
    'symfony/event-dispatcher' => 
    array (
      'pretty_version' => 'v5.4.3',
      'version' => '5.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => 'dec8a9f58d20df252b9cd89f1c6c1530f747685d',
    ),
    'symfony/event-dispatcher-contracts' => 
    array (
      'pretty_version' => 'v2.5.1',
      'version' => '2.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => 'f98b54df6ad059855739db6fcbc2d36995283fe1',
    ),
    'symfony/event-dispatcher-implementation' => 
    array (
      'provided' => 
      array (
        0 => '2.0',
      ),
    ),
    'symfony/filesystem' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '3a4442138d80c9f7b600fb297534ac718b61d37f',
    ),
    'symfony/finder' => 
    array (
      'pretty_version' => 'v5.4.3',
      'version' => '5.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '231313534dded84c7ecaa79d14bc5da4ccb69b7d',
    ),
    'symfony/framework-bundle' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '7520f553c7a7721652c1b7ac95c09dae62a1676e',
    ),
    'symfony/http-foundation' => 
    array (
      'pretty_version' => 'v5.4.6',
      'version' => '5.4.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '34e89bc147633c0f9dd6caaaf56da3b806a21465',
    ),
    'symfony/http-kernel' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '509243b9b3656db966284c45dffce9316c1ecc5c',
    ),
    'symfony/polyfill-ctype' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '30885182c981ab175d4d034db0f6f469898070ab',
    ),
    'symfony/polyfill-intl-grapheme' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '81b86b50cf841a64252b439e738e97f4a34e2783',
    ),
    'symfony/polyfill-intl-normalizer' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '8590a5f561694770bdcd3f9b5c69dde6945028e8',
    ),
    'symfony/polyfill-mbstring' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '0abb51d2f102e00a4eefcf46ba7fec406d245825',
    ),
    'symfony/polyfill-php72' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '9a142215a36a3888e30d0a9eeea9766764e96976',
    ),
    'symfony/polyfill-php73' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => 'cc5db0e22b3cb4111010e48785a97f670b350ca5',
    ),
    'symfony/polyfill-php80' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '4407588e0d3f1f52efb65fbe92babe41f37fe50c',
    ),
    'symfony/polyfill-php81' => 
    array (
      'pretty_version' => 'v1.25.0',
      'version' => '1.25.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '5de4ba2d41b15f9bd0e19b2ab9674135813ec98f',
    ),
    'symfony/routing' => 
    array (
      'pretty_version' => 'v5.4.3',
      'version' => '5.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '44b29c7a94e867ccde1da604792f11a469958981',
    ),
    'symfony/service-contracts' => 
    array (
      'pretty_version' => 'v2.5.1',
      'version' => '2.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '24d9dc654b83e91aa59f9d167b131bc3b5bea24c',
    ),
    'symfony/service-implementation' => 
    array (
      'provided' => 
      array (
        0 => '1.0|2.0',
      ),
    ),
    'symfony/string' => 
    array (
      'pretty_version' => 'v5.4.3',
      'version' => '5.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '92043b7d8383e48104e411bc9434b260dbeb5a10',
    ),
    'symfony/translation-contracts' => 
    array (
      'pretty_version' => 'v2.5.1',
      'version' => '2.5.1.0',
      'aliases' => 
      array (
      ),
      'reference' => '1211df0afa701e45a04253110e959d4af4ef0f07',
    ),
    'symfony/twig-bridge' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => 'b43e9bdb57a39ffffb4c44a7ef0a47d338e9f1da',
    ),
    'symfony/twig-bundle' => 
    array (
      'pretty_version' => 'v5.4.3',
      'version' => '5.4.3.0',
      'aliases' => 
      array (
      ),
      'reference' => '45ae3ee8155f93042a1033b166a7a3ed57b96a92',
    ),
    'symfony/var-dumper' => 
    array (
      'pretty_version' => 'v5.4.6',
      'version' => '5.4.6.0',
      'aliases' => 
      array (
      ),
      'reference' => '294e9da6e2e0dd404e983daa5aa74253d92c05d0',
    ),
    'symfony/var-exporter' => 
    array (
      'pretty_version' => 'v5.4.7',
      'version' => '5.4.7.0',
      'aliases' => 
      array (
      ),
      'reference' => '7eacaa588c9b27f2738575adb4a8457a80d9c807',
    ),
    'timber/timber' => 
    array (
      'pretty_version' => '1.19.2',
      'version' => '1.19.2.0',
      'aliases' => 
      array (
      ),
      'reference' => 'e1460c04914c97e5d08be06d27f9df958fc17725',
    ),
    'twig/cache-extension' => 
    array (
      'pretty_version' => 'v1.5.0',
      'version' => '1.5.0.0',
      'aliases' => 
      array (
      ),
      'reference' => '2c243643f59132194458bd03c745b079bbb12e78',
    ),
    'twig/extra-bundle' => 
    array (
      'pretty_version' => 'v3.3.8',
      'version' => '3.3.8.0',
      'aliases' => 
      array (
      ),
      'reference' => '2e58256b0e9fe52f30149347c0547e4633304765',
    ),
    'twig/string-extra' => 
    array (
      'pretty_version' => 'v3.3.5',
      'version' => '3.3.5.0',
      'aliases' => 
      array (
      ),
      'reference' => '03608ae2e9c270a961e8cf1b75751e8635ad3e3c',
    ),
    'twig/twig' => 
    array (
      'pretty_version' => 'v2.14.13',
      'version' => '2.14.13.0',
      'aliases' => 
      array (
      ),
      'reference' => '66856cd0459df3dc97d32077a98454dc2a0ee75a',
    ),
    'upstatement/routes' => 
    array (
      'pretty_version' => '0.8.2',
      'version' => '0.8.2.0',
      'aliases' => 
      array (
      ),
      'reference' => '2fff0d92754d784529520b0f2ee5696a49089fb8',
    ),
  ),
);
private static $canGetVendors;
private static $installedByVendor = array();







public static function getInstalledPackages()
{
$packages = array();
foreach (self::getInstalled() as $installed) {
$packages[] = array_keys($installed['versions']);
}


if (1 === \count($packages)) {
return $packages[0];
}

return array_keys(array_flip(\call_user_func_array('array_merge', $packages)));
}









public static function isInstalled($packageName)
{
foreach (self::getInstalled() as $installed) {
if (isset($installed['versions'][$packageName])) {
return true;
}
}

return false;
}














public static function satisfies(VersionParser $parser, $packageName, $constraint)
{
$constraint = $parser->parseConstraints($constraint);
$provided = $parser->parseConstraints(self::getVersionRanges($packageName));

return $provided->matches($constraint);
}










public static function getVersionRanges($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

$ranges = array();
if (isset($installed['versions'][$packageName]['pretty_version'])) {
$ranges[] = $installed['versions'][$packageName]['pretty_version'];
}
if (array_key_exists('aliases', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['aliases']);
}
if (array_key_exists('replaced', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['replaced']);
}
if (array_key_exists('provided', $installed['versions'][$packageName])) {
$ranges = array_merge($ranges, $installed['versions'][$packageName]['provided']);
}

return implode(' || ', $ranges);
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['version'])) {
return null;
}

return $installed['versions'][$packageName]['version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getPrettyVersion($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['pretty_version'])) {
return null;
}

return $installed['versions'][$packageName]['pretty_version'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getReference($packageName)
{
foreach (self::getInstalled() as $installed) {
if (!isset($installed['versions'][$packageName])) {
continue;
}

if (!isset($installed['versions'][$packageName]['reference'])) {
return null;
}

return $installed['versions'][$packageName]['reference'];
}

throw new \OutOfBoundsException('Package "' . $packageName . '" is not installed');
}





public static function getRootPackage()
{
$installed = self::getInstalled();

return $installed[0]['root'];
}







public static function getRawData()
{
return self::$installed;
}



















public static function reload($data)
{
self::$installed = $data;
self::$installedByVendor = array();
}




private static function getInstalled()
{
if (null === self::$canGetVendors) {
self::$canGetVendors = method_exists('Composer\Autoload\ClassLoader', 'getRegisteredLoaders');
}

$installed = array();

if (self::$canGetVendors) {
foreach (ClassLoader::getRegisteredLoaders() as $vendorDir => $loader) {
if (isset(self::$installedByVendor[$vendorDir])) {
$installed[] = self::$installedByVendor[$vendorDir];
} elseif (is_file($vendorDir.'/composer/installed.php')) {
$installed[] = self::$installedByVendor[$vendorDir] = require $vendorDir.'/composer/installed.php';
}
}
}

$installed[] = self::$installed;

return $installed;
}
}
