<?php
namespace Ebb;

class CiviTypes
{
    public static $translation = [
        1 => 'number',
        2 => 'string',
        4 => 'Date',
        12 => 'Date',
        16 => 'boolean',
        32 => 'string',
        64 => 'string', // Not sure about this one. Used once in CaseType
        256 => 'Date',
        512 => 'number',
        1024 => 'number', // Might be worth switching to a Money type at some point
        4096 => 'string', // Might be worth switching to an Email type at some point (or should that be done at the component level?)
        16384 => 'string', // Not sure about this one. Used once in File
        // 8 => 'Time', // not used
        // 2048 => 'Date', // not used
        '' => 'string',
        'Array' => 'string', // Not sure about this one. Used once in Setting
        'Boolean' => 'boolean',
        'Date' => 'Date',
        'Int' => 'number',
        'Integer' => 'number',
        'String' => 'string',
        'Text' => 'string',
    ];
}
