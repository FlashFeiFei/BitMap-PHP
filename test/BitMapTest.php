<?php

use PHPUnit\Framework\TestCase;
use BitMap\BitMap;

class BitMapTest extends TestCase
{
    /**
     * 测试添加铭感词
     */
    public function testAddBit()
    {

        $bit1 = new BitMap();
        $bit1->addBit(1);
        $bit1->addBit(3);
        $bit1->addBit(5);
        $bit1->addBit(7);
        $bit1->addBit(9);
        $bit1->addBit(54785);

        $bit2 = new BitMap();
        $bit2->addBit(2);
        $bit2->addBit(4);
        $bit2->addBit(6);
        $bit2->addBit(8);
        $bit2->addBit(54785);

        return [
            [$bit1, $bit2]
        ];
    }

    /**
     * @dataProvider testAddBit
     */
    public function testIntersectByBitMap(BitMap $bit1, BitMap $bit2)
    {
        var_dump(BitMap::intersectByBitMap($bit1, $bit2));
    }

    /**
     * @dataProvider testAddBit
     */
    public function testMergeByBitMap(BitMap $bit1, BitMap $bit2)
    {
        var_dump(BitMap::mergeByBitMap($bit1, $bit2));
    }

}