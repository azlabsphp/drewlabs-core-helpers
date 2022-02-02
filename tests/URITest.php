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

use Drewlabs\Core\Helpers\URI;
use PHPUnit\Framework\TestCase;

class URITest extends TestCase
{
    public function test_verify()
    {
        $keyResolver = static function () {
            return 'base64:e2+htkaO2sWE3Jy3hBc4MjWcPRSyrkIHBGkMGHH+7eM=';
        };
        $url = "http://localhost:8888/api/user-account-verify-weburl?account_id=AD2D7EE9-EEFF-4A43-8BC6-29BD62E5C250&token=n3fQKj1Wlslq.yDbYOmZLS54WGaouAPH2.XJCxmWapk3XbGRc0lF3HyLnEgrbYTgb8dwWDUaeSfIU7082MQZlJ.XHTr8437RdOniSh.l88eA04ZZLH85O.XkFsysUnmhYcpqBMosX3.Yf5OKZhlZNt1S..KakUs.TUb9GXfpWOk";
        $psr17Factory = (new \Nyholm\Psr7\Factory\Psr17Factory())->createServerRequest(
            'GET',
            URI::withSignature($url, $keyResolver)
        );
        $this->assertTrue(
            URI::verify(
                $psr17Factory->getUri(),
                $keyResolver,
            ),
            'Expect signed url to be valid'
        );
    }

    public function test_verify_signature()
    {
        $keyResolver = static function () {
            return 'base64:e2+htkaO2sWE3Jy3hBc4MjWcPRSyrkIHBGkMGHH+7eM=';
        };
        $timestamp = (new \DateTimeImmutable())->sub(new \DateInterval('P1D'))->getTimestamp();
        $url = "http://localhost:8888/api/user-account-verify-weburl?expires=$timestamp&account_id=AD2D7EE9-EEFF-4A43-8BC6-29BD62E5C250&token=n3fQKj1Wlslq.yDbYOmZLS54WGaouAPH2.XJCxmWapk3XbGRc0lF3HyLnEgrbYTgb8dwWDUaeSfIU7082MQZlJ.XHTr8437RdOniSh.l88eA04ZZLH85O.XkFsysUnmhYcpqBMosX3.Yf5OKZhlZNt1S..KakUs.TUb9GXfpWOk";
        $psr17Factory = (new \Nyholm\Psr7\Factory\Psr17Factory())->createServerRequest(
            'GET',
            URI::withSignature($url, $keyResolver),
        );
        $this->assertTrue(
            URI::verifySignature(
                $psr17Factory->getUri(),
                $keyResolver,
            ),
            'Expect signed url signature to be valid'
        );
    }

    public function test_expires()
    {
        $keyResolver = static function () {
            return 'base64:e2+htkaO2sWE3Jy3hBc4MjWcPRSyrkIHBGkMGHH+7eM=';
        };
        $timestamp = (new \DateTimeImmutable())->add(new \DateInterval('P1D'))->getTimestamp();
        $url = "http://localhost:8888/api/user-account-verify-weburl?expires=$timestamp&account_id=AD2D7EE9-EEFF-4A43-8BC6-29BD62E5C250&token=n3fQKj1Wlslq.yDbYOmZLS54WGaouAPH2.XJCxmWapk3XbGRc0lF3HyLnEgrbYTgb8dwWDUaeSfIU7082MQZlJ.XHTr8437RdOniSh.l88eA04ZZLH85O.XkFsysUnmhYcpqBMosX3.Yf5OKZhlZNt1S..KakUs.TUb9GXfpWOk";
        $psr17Factory = (new \Nyholm\Psr7\Factory\Psr17Factory())->createServerRequest(
            'GET',
            URI::withSignature($url, $keyResolver),
        );
        $this->assertFalse(
            URI::expires(
                $psr17Factory->getUri(),
                $keyResolver,
            ),
            'Expect signed url signature to be valid'
        );
    }
}
