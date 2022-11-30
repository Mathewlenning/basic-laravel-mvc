<?php
/**
 *  I know you're probably wondering this view is 100% PHP
 *  I'm a big fan of using views to render the content of all "Documents"
 *  in my applications because it makes it easy to reuse.
 *
 * Going forward this view can be used to render the xml directly on the site
 * by just rendering the view like we do in get / route.
 */

/** @var SimpleXMLElement $xml */
$xml = simplexml_load_string('<?xml version="1.0" encoding="UTF-8"?><records></records>');

foreach ($books AS $book)
{
    $record = $xml->addChild('record');

    foreach ($book AS $name => $value)
    {
        if (!in_array($name, $export['columns']))
        {
            continue;
        }

        if ($name == 'book_title')
        {
            $child = $record->addChild($name);
            $childNode = dom_import_simplexml($child);
            $childOwner = $childNode->ownerDocument;
            $childNode->appendChild($childOwner->createCDATASection($value));
            continue;
        }

        $record->addChild($name, htmlentities(trim($value), ENT_COMPAT, 'UTF-8', false));
    }
}

echo $xml->asXML();
