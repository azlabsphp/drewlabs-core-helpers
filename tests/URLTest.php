<?php

declare(strict_types=1);

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class URLTest extends TestCase
{
    public function testValidateSignedURLFunction()
    {
        $keyResolver = static function () {
            return 'base64:e2+htkaO2sWE3Jy3hBc4MjWcPRSyrkIHBGkMGHH+7eM=';
        };
        $timestamp = (new \DateTimeImmutable())->add(new \DateInterval('P1D'))->getTimestamp();
        $url = "http://localhost:8888/api/user-account-verify-weburl?expires=$timestamp?account_id=AD2D7EE9-EEFF-4A43-8BC6-29BD62E5C250&token=n3fQKj1Wlslq.yDbYOmZLS54WGaouAPH2.XJCxmWapk3XbGRc0lF3HyLnEgrbYTgb8dwWDUaeSfIU7082MQZlJ.XHTr8437RdOniSh.l88eA04ZZLH85O.XkFsysUnmhYcpqBMosX3.Yf5OKZhlZNt1S..KakUs.TUb9GXfpWOk";
        $signature = drewlabs_core_url_signature_from_url($url, $keyResolver);
        $psr17Factory = (new \Nyholm\Psr7\Factory\Psr17Factory())->createServerRequest(
            'GET',
            "http://localhost:8888/api/user-account-verify-weburl?signature=$signature&expires=$timestamp?account_id=AD2D7EE9-EEFF-4A43-8BC6-29BD62E5C250&token=n3fQKj1Wlslq.yDbYOmZLS54WGaouAPH2.XJCxmWapk3XbGRc0lF3HyLnEgrbYTgb8dwWDUaeSfIU7082MQZlJ.XHTr8437RdOniSh.l88eA04ZZLH85O.XkFsysUnmhYcpqBMosX3.Yf5OKZhlZNt1S..KakUs.TUb9GXfpWOk"
        );
        $this->assertSame(drewlabs_core_url_has_valid_signature(
            $psr17Factory,
            $keyResolver,
        ), true, 'Expect signed url to be valid');
    }
}
