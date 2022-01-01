<?php

ini_set('display_errors', 'stderr');

function info($s) {
    fwrite(STDERR, date('c') . " INFO: $s\n");
}

function error($s) {
    fwrite(STDERR, date('c') . " ERROR: $s\n");
}

function fatal($s) {
    fwrite(STDERR, date('c') . " FATAL: $s\n");
    exit(1);
}
