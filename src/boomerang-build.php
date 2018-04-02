<?php
/**
 * Config Part
 */

/**
 * Put here the address where you want to push the beacon data.
 *
 * For catching the data check https://github.com/revampix/boomerang-beacon-catcher
 *
 * Use something like https://www.mydomain.com/boomerang/catcher.php
 */
$beaconPushUrl = '';

if(!empty($argv[1])) {
    $beaconPushUrl = $argv[1];
}

if (empty($beaconPushUrl)) {
    print "\033[91m" . "Failed - Beacon url not specified!" . "\033[0m" . "\n";
    print "\033[91m" . "Please follow the example:" . "\033[0m" . "\n";
    print "\033[91m" . "php boomerang-build.php https://www.mydomain.com/boomerang/catcher.php" . "\033[0m" . "\n";
    return;
}

$boomerangFilesPackedFileName = 'boomerandg-compiled.js';

print "\033[92m" . "Building ..." . "\033[0m" . "\n";

$boomerangJsLib = 'boomerang.js';
$boomerangPlugins = [
    'boomerang-js/google-analytics-customised.js',
    'boomerang-js/guid-customised.js',
    'boomerang-js/mobile.js',
    'boomerang-js/navtiming.js',
    'boomerang-js/restiming.js'
];

$initPartTpl = <<<EOT
BOOMR.init({
    beacon_url: "{$beaconPushUrl}"
});
EOT;


$script = '';

print "\033[92m" . " - Adding library " . $boomerangJsLib . "\033[0m" . "\n";
$script .= file_get_contents($boomerangJsLib);

foreach ($boomerangPlugins as $pluginFile) {
    print "\033[92m" . " - Adding plugin " . $pluginFile . "\033[0m" . "\n";
    if (file_exists($pluginFile)) {
        $script .= file_get_contents($pluginFile);
    } else {
        print "\033[91m" . "File " . $pluginFile . "doesn't exist. Sorry not included!!! Check if file exists." . "\033[0m" . "\n";
    }

}

print "\033[92m" . " - Adding init code: " . "\033[0m" . "\n";
print "\033[92m" . $initPartTpl . "\033[0m" . "\n";
$script .= $initPartTpl;

print "\033[92m" . " - Reducing files size with Closure Compiler. For more information check: https://closure-compiler.appspot.com/home" . "\033[0m" . "\n";
$ch = curl_init('https://closure-compiler.appspot.com/compile');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'output_info=compiled_code&output_format=text&compilation_level=WHITESPACE_ONLY&js_code=' . urlencode($script));
$output = curl_exec($ch);
curl_close($ch);

print "\033[92m" . " - Writing result to "  . $boomerangFilesPackedFileName . "\033[0m" . "\n";
file_put_contents($boomerangFilesPackedFileName, $output);
