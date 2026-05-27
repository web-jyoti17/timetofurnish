<?php

namespace Tests\Unit;

use Tests\TestCase;

class ProductVariantStockTest extends TestCase
{
    public function testGetProductChoiceValuesSorting()
    {
        $choice = new \stdClass();
        $choice->values = [
            [
                'value' => 'Ty243',
                'sort_order' => 5,
            ],
            [
                'value' => 'Tyy24s',
                'sort_order' => 1,
            ],
            [
                'value' => 'Default',
                'sort_order' => 3,
            ]
        ];

        $sorted = get_product_choice_values($choice);

        $this->assertEquals('Tyy24s', $sorted[0]['value']);
        $this->assertEquals('Default', $sorted[1]['value']);
        $this->assertEquals('Ty243', $sorted[2]['value']);
    }
}
