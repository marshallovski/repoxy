<?php
function write_ini_file($config, $file, $has_section = false, $write_to_file = true)
{
    $fileContent = '';
    if (!empty($config)) {
        foreach ($config as $i => $v) {
            if ($has_section) {
                $fileContent .= "[$i]" . PHP_EOL . write_ini_file($v, $file, false, false);
            } else {
                if (is_array($v)) {
                    foreach ($v as $t => $m) {
                        $fileContent .= "$i[$t] = " . (is_numeric($m) ? $m : '"' . $m . '"') . PHP_EOL;
                    }
                } else $fileContent .= "$i = " . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
            }
        }
    }

    if ($write_to_file && strlen($fileContent)) return file_put_contents($file, $fileContent, LOCK_EX);
    else return $fileContent;
}
