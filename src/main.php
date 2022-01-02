#!/usr/bin/php -d short_open_tag=true
<?php

include('common.php');

$FLAGS = getopt('', array('input:', 'output:', 'comment:', 'help'));

if (array_key_exists('help', $FLAGS)) {
    echo <<<EOD
    icgen file preprocessor.
        --input - Input file to preprocess.
        --output - Output file.
        --comment - Comment prefix.

    EOD;
    exit(0);
}

info("Input file {$FLAGS['input']}");

$processed  = "// AUTO GENERATED - DO NOT MODIFY THIS FILE\n";
$processed .= "//\n";
$processed .= "// icgen generated file\n";
$processed .= "// input:        " . realpath($FLAGS['input']) . "\n";
$processed .= "// input sha256: " . hash_file('sha256', $FLAGS['input']) . "\n";
$processed .= "// output:       " . realpath($FLAGS['output']) . "\n";
$processed .= "// ran at:       " . date('c') . "\n";
$processed .= "// icgen ver:    " . shell_exec('cd ' . realpath(dirname(__FILE__)) . ' && git describe --always --dirty') . "\n";
$processed .= "//\n";
$processed .= "// AUTO GENERATED - DO NOT MODIFY THIS FILE\n\n";

if (array_key_exists('comment', $FLAGS)) {
    $processed = str_replace('//', $FLAGS['comment'], $processed);
}

ob_implicit_flush(false);
ob_start();
include($FLAGS['input']);
$processed .= ob_get_clean();

$errors = error_get_last();
if ($errors !== NULL) {
    fatal("Got error while processing file {$FLAGS['input']}: " . print_r($errors, true));
}

info("Writing to file {$FLAGS['output']}");
$result = file_put_contents($FLAGS['output'], $processed);
if ($result === false) {
    fatal("Error writing to output file {$FLAGS['output']}");
}

info('Done');
