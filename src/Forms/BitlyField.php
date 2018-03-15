<?php

namespace Vulcan\Bitly\Forms;

use SilverStripe\Forms\ReadonlyField;
use SilverStripe\View\Requirements;
use Vulcan\Bitly\Models\BitlyUrl;

/**
 * Class BitlyField
 * @package Vulcan\Bitly\Forms
 */
class BitlyField extends ReadonlyField
{
    protected $readonly = false;

    protected $schemaDataType = self::SCHEMA_DATA_TYPE_HTML;

    /**
     * @var null|BitlyUrl
     */
    protected $bitly = null;

    /**
     * @skipUpgrade
     * @var string
     */
    protected $schemaComponent = 'HTMLField';

    /**
     * BitlyField constructor.
     *
     * @param string      $name
     * @param null|string $title
     * @param null|string $value
     * @param BitlyUrl    $record
     */
    public function __construct($name, $title = null, $value = null, BitlyUrl $record)
    {
        Requirements::javascript('vulcandigital/silverstripe-bitly:js/bitly.min.js');
        $this->setBitly($record);

        parent::__construct($name, $title, $value);
    }

    /**
     * @return string
     */
    public function Type()
    {
        return 'bitly-field';
    }

    /**
     * @param BitlyUrl $bitly
     *
     * @return BitlyField
     */
    public function setBitly(BitlyUrl $bitly)
    {
        $this->bitly = $bitly;
        return $this;
    }

    public function getBitly()
    {
        return $this->bitly;
    }
}
