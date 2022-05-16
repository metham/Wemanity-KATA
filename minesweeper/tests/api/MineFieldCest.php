<?php

namespace App\Tests\api;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use Faker\Factory;
use Faker\Generator;

class MineFieldCest
{
    private Generator $faker;

    public function _before(ApiTester $I): void
    {
        $this->faker = Factory::create();
    }

    public function postMineFieldSuccessfully(ApiTester $I): void
    {
        $minefield = [
            "4 4",
            "*...",
            "....",
            ".*..",
            "....",
            "0 0"
        ];

        $I->sendPost(
            '/minefield',
            [
                'minefields' => $minefield,
            ]
        );

        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('[["Field #1:","*100","2210","1*10","1110"]]');
    }
}
