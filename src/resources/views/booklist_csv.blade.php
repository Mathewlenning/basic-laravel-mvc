<?php
$headersLabels = [
    'book_title' => 'Title',
    'author_first_name' =>'First Name',
    'author_last_name' => 'Last Name'];

$headers = array();

foreach ($export['columns'] AS $key)
{
    $headers[] = $headersLabels[$key];
}

echo implode(',', $headers) . PHP_EOL;

foreach ($books AS $book)
{
    $includeData = array();

    foreach ($book AS $name => $value)
    {
        if (!in_array($name, $export['columns']))
        {
            continue;
        }

        $includeData[] = '"' . str_replace('"', '&quot;', str_replace(array("\n", "\r", "\r\n", "\n\r"), '', trim($value))) . '"';
    }

    echo implode(',', $includeData) . PHP_EOL;
}
