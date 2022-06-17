<?php
function update_ini_file(string $file, string $key, string $newvalue)
{
    $data = file($file, FILE_IGNORE_NEW_LINES);
    $find = $key;
    foreach ($data as &$line) {
        if (strpos($line, $key) !== false) {
            $line = "{$find} = \"{$newvalue}\"";
            break;
        }
    }
    file_put_contents($file, implode("\n", $data));
}
