<?php
function sendjson(array $json)
{
    header('Content-type: application/json; charset=utf-8');
    return json_encode($json);
}