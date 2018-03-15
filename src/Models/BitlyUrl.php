<?php

namespace Vulcan\Bitly\Models;

use SilverStripe\ORM\DataObject;

/**
 * Class BitlyUrl
 * @package Vulcan\Bitly\Models
 *
 * @property string URL
 * @property int Clicks
 */
class BitlyUrl extends DataObject
{
    private static $table_name = 'BitlyUrl';

    private static $db = [
        'URL'    => 'Varchar(255)',
        'Clicks' => 'Int'
    ];
}
