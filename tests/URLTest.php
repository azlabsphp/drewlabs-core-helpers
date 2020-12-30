<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;


class URLTest extends TestCase
{
    public function testValidateSignedURLFunction()
    {
        $psr17Factory = (new \Nyholm\Psr7\Factory\Psr17Factory())->createServerRequest(
            'GET',
            "http://localhost:8888/api/user-account-verify-weburl?signature=be5e0a139b7b1992f86448a4c6ea8fe2748b8658813ed508c41e841aad99b826&expires=1609348375?account_id=AD2D7EE9-EEFF-4A43-8BC6-29BD62E5C250&token=n3fQKj1Wlslq.yDbYOmZLS54WGaouAPH2.XJCxmWapk3XbGRc0lF3HyLnEgrbYTgb8dwWDUaeSfIU7082MQZlJ.XHTr8437RdOniSh.l88eA04ZZLH85O.XkFsysUnmhYcpqBMosX3.Yf5OKZhlZNt1S..KakUs.TUb9GXfpWOk"
        );
        $this->assertEquals(\drewlabs_core_url_has_valid_signature(
            $psr17Factory,
            function () {
                return "base64:e2+htkaO2sWE3Jy3hBc4MjWcPRSyrkIHBGkMGHH+7eM=";
            }
        ), true, 'Expect signed url to be valid');
    }
}
