<?php

namespace Vulcan\Bitly\Controllers;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Convert;
use Vulcan\Bitly\Bitly;
use Vulcan\Bitly\Extensions\PageExtension;
use Vulcan\Bitly\Models\BitlyUrl;

class BitlyController extends Controller
{
    private static $allowed_actions = [
        'index',
        'refresh'
    ];

    public function index()
    {
        $this->httpError(404);
    }

    public function refresh(HTTPRequest $request)
    {
        $id = $request->getVar('id');

        /** @var BitlyUrl $bitlyUrl */
        $bitlyUrl = BitlyUrl::get()->byID($id);

        if (!$bitlyUrl) {
            $this->httpError(404);
        }

        $bitly = Bitly::create();
        $clicks = $bitly->getClicksForUrl($bitlyUrl->URL);

        if (is_numeric($clicks) && $bitlyUrl->Clicks != $clicks) {
            $bitlyUrl->Clicks = $clicks;
            $bitlyUrl->write();
        }

        $response = new HTTPResponse();
        $response->addHeader('Content-Type', 'application/json');
        $response->setBody(Convert::array2json([
            'success' => true,
            'data'    => [
                'clicks' => $clicks
            ]
        ]));

        return $response;
    }
}
