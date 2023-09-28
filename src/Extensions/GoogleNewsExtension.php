<?php

namespace Icamys\SitemapGenerator\Extensions;

use InvalidArgumentException;
use XMLWriter;

class GoogleNewsExtension
{
    private static $requiredFields = [
        'title',
        'publication_date',
    ];

    public static function writeNewsTag(XMLWriter $xmlWriter, array $extFields)
    {
        self::validate($extFields);

        $xmlWriter->startElement('news:news');

        $xmlWriter->writeElement('news:title', htmlentities($extFields['title'], ENT_QUOTES));
        $xmlWriter->writeElement('news:publication_date', $extFields['publication_date']);

        if (isset($extFields['publication']) && is_array($extFields['publication'])) {
            $xmlWriter->startElement('news:publication');

            if (isset($extFields['publication']['name'])) {
                $xmlWriter->writeElement('news:name', htmlentities($extFields['publication']['name'], ENT_QUOTES));
            }

            if (isset($extFields['publication']['language'])) {
                $xmlWriter->writeElement('news:language', $extFields['publication']['language']);
            }

            $xmlWriter->endElement();
        }

        $xmlWriter->endElement();
    }

    public static function validate($extFields)
    {
        $extFieldNames = array_keys($extFields);

        if (count(array_intersect(self::$requiredFields, $extFieldNames)) !== count(self::$requiredFields)) {
            throw new InvalidArgumentException(
                sprintf("Missing required fields: %s", implode(', ', array_diff(self::$requiredFields, $extFieldNames)))
            );
        }
    }
}
