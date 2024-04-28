<?php

require_once __DIR__ . '\..\vendor\autoload.php';

use Ako\Zarinpal\Php\Api;

$zarinpal = new Api([
    'defaults' => [
        'merchant_id' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
        'currency' => 'IRT',
        'strategy' => 'sandbox'
    ],
    'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNmJhZmQwNjQzMDQ5ZjIxNGFlNzQ1NGM3Y2M2NTdhYzA3MWJhY2YyOTNhODZlYjEwOWM1MWZhN2ZkYTU2MGMwYzEwYTAxNmQyNzY1MTM0YzMiLCJpYXQiOjE3MTE1NDUzNzkuNTk4ODU5LCJuYmYiOjE3MTE1NDUzNzkuNTk4ODYyLCJleHAiOjE4NjkzMTE3NzkuNTY3NTkzLCJzdWIiOiIxMDYzNjU0Iiwic2NvcGVzIjpbXX0.eYa2L2i1zA02PD-3rJ4pvPcgFkCKQxekicw_CycAOuPytJq-sBmGcOOmGBvyXx6NmAesEdZ_cvg8Lx2r8JjXBeuXsGdcCFJpYh7KHpSzX3eUIe-9WNM2d3eBTKiIfUeNen963VbvtKoOaDFD58t8VnOk_S-rlBoku6ZGt2PQkLKuxhT2x02qYVQjsDZosp78_QZbip91ZPrmMA7ueQCTIVAi5WDPxDthVDO37KNUkf8WTl6o4vfZte1cm1aFKpEDiRkqggDgMrTX8-_JR9uPhy0AlNQR48oHDB-5CvZpeqVYn701hXnbS8ui5qmIAuuPzdwOQMa859VLnpkIjphYK9ek_OLsK7bE3ilthCCsCxZW2MQcMoQ7SWu4ao1dZNausvzZ4OKyQn65nrp96FUz_j48G5Io4LBp34jpe9WgiEKGWW4k8JTq2TIeUgkm54b5VqMl_IELO-2SMv9AxOVKR1GtSAXurD3N0q7qQ1_VeFRBcsdzOATLxqqBZR8Jk4vEGApB5dJa8G7Et0D1s8CGHuHnjdONOaClo4XggCnPX7AYSev0TiE3LikyCdlr7EHY8bTkoHl_gS45pWxd8zGtVKd6d9bsM2cStfG0hSri7nPTZvW-DqDbTX-FBnqJn1GVP8UVNP-ZWP7Zd5Plju_b_iUf9h5uEoA0diEHkeQwam0'
]);

$r = $zarinpal->terminals()->first();
var_dump($r->reconcile_priority);
