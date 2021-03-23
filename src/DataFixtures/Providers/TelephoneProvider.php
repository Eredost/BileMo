<?php


namespace App\DataFixtures\Providers;


class TelephoneProvider
{
    /**
     * Contains all telephones sorted by brand
     *
     * @var array[]
     */
    private const TELEPHONES = [
        'Apple'   => [
            'Iphone 10',
            'Iphone 11',
            'Iphone 12',
            'Iphone XR',
        ],
        'Google'  => [
            'Pixel 5',
            'Pixel 4',
        ],
        'Xiaomi'  => [
            'Mi 11',
            'Mi 10',
            'Redmi 9',
            'Redmi 9AT',
        ],
        'Samsung' => [
            'Galaxy S21',
            'Galaxy S20',
            'Galaxy A71',
        ],
        'ZTE'     => [
            'Blade A3',
            'Blade A5',
            'Blade A7',
        ],
        'Alcatel' => [
            '1B',
            '1SE power',
        ],
        'Nokia'   => [
            '8000',
            '2.4',
        ],
        'Huawei'  => [
            'Y5p',
        ],
        'LG'      => [
            'K30',
            'K22',
            'K41S',
            'K51S',
        ],
    ];

    /**
     * @return array
     */
    public static function telephonesSortedByBrand(): array
    {
        return static::TELEPHONES;
    }
}
