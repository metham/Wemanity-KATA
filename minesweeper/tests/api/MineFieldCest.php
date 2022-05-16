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

        $I->sendPostAsJson(
            '/minefield',
            [
                'minefields' => $minefield,
            ]
        );

        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
        $I->seeResponseIsJson();
        $I->seeResponseContains('[["Field #1:","*100","2210","1*10","1110"]]');
    }

    public function postMultipleMineFieldsSuccessfully(ApiTester $I): void
    {
        $minefield = [
            "4 4",
            "*...",
            "....",
            ".*..",
            "....",
            "3 5",
            "**...",
            ".....",
            ".*...",
            "0 0"
        ];

        $I->sendPostAsJson(
            '/minefield',
            [
                'minefields' => $minefield,
            ]
        );

        $I->seeResponseCodeIs(HttpCode::ACCEPTED);
        $I->seeResponseIsJson();
        $I->seeResponseEquals('[["Field #1:","*100","2210","1*10","1110","Field #2:","**100","33200","1*100"]]');
    }

    public function postNoMineFieldsAndFail(ApiTester $I): void
    {
        $I->sendPostAsJson(
            '/minefield',
            []
        );

        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
