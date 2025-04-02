<?php

namespace Php\Project\Parse;

function parseFiles($path1, $path2): void
{
    if (file_exists($path1) && file_exists($path2)) {
        $file1 =  get_object_vars(json_decode(file_get_contents($path1)));
        $file2 = get_object_vars(json_decode(file_get_contents($path2)));

        print_r("\nFirst File Decoded:\n");
        var_dump($file1);

        print_r("\nSecond File Decoded:\n");
        var_dump($file2);
    } else {
        print_r("Файл не найден!\n");
    }
}
