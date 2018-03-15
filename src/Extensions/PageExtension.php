<?php

namespace Vulcan\Bitly\Extensions;

use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use Vulcan\Bitly\Bitly;
use Vulcan\Bitly\Forms\BitlyField;
use Vulcan\Bitly\Models\BitlyUrl;

/**
 * Class PageExtension
 * @package Vulcan\Bitly\Extensions
 *
 * @property int BitlyID
 *
 * @method \Page|static getOwner()
 * @method BitlyUrl Bitly()
 */
class PageExtension extends DataExtension
{
    private static $db = [
        'BitlyUrl'    => 'Varchar(255)',
        'BitlyClicks' => 'Int'
    ];

    private static $has_one = [
        'Bitly' => BitlyUrl::class
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        parent::updateCMSFields($fields);

        if (!$this->getOwner()->Bitly()->exists()) {
            $fields->addFieldsToTab('Root.Main', [
                DropdownField::create('GenerateBitly', 'Bitly URL', ['I don\'t need one', 'Generate one for me'])
            ], 'MenuTitle');

            return;
        }

        $fields->addFieldsToTab('Root.Main', [
            BitlyField::create('BitlyURL', 'Bitly URL', $this->getOwner()->Bitly()->URL, $this->getOwner()->Bitly())
        ], 'MenuTitle');
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        /** @var \Page $original */
        $original = \Page::get()->byID($this->getOwner()->ID);
        $linkChanged = false;

        if ($original->URLSegment != $this->getOwner()->URLSegment || $original->ParentID != $this->getOwner()->ParentID) {
            $linkChanged = true;
        }

        if ($this->getOwner()->GenerateBitly || $linkChanged) {
            if ($this->getOwner()->Bitly()->exists()) {
                $this->getOwner()->Bitly()->delete();
            }

            $bitly = Bitly::create();

            /** @var BitlyUrl $record */
            $record = BitlyUrl::create();

            $url = $bitly->shorten($this->getOwner()->AbsoluteLink());
            $record->URL = $url;
            $this->getOwner()->BitlyID = $record->write();
            $this->getOwner()->Bitly()->URL = $url;
        }
    }
}
