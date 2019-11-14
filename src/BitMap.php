<?php

namespace BitMap;

/**
 * 单位换算:
 * $int_bit_size ,一个整数可以多少个二进制
 * 二进制最大的位置等于   数组元素 * PHP_INT_SIZE * B - 1 ;这里是 50 * PHP_INT_SIZE * 8
 * 算法解释:
 * 定义一个数组$bitmap = array_fill(0,50,0)，长度为50，并且填充上0，
 * 我们知道PHP_INT_SIZE在64位系统中是8，则这个数组能容下50*8*8=3200个数据，
 * 字节范围（0~49），位范围（0~93），
 * 怎么求一个数的字节位置和位位置呢，
 * 比如1234，字节位置：1234/64=19，位位置：1234%64=18。
 * 字节位置就是$bitmap的下标，而位位置就是在该下标数组的这个数的二进制中的位数。
 *
 * Class BitMap
 * @package BitMap
 */
class BitMap
{
    const INIT_BIT_SIZE = PHP_INT_SIZE * 8;
    /**
     * bitmap
     * @var array
     */
    private $bitmap;


    public function __construct()
    {
        $this->bitmap = array_fill(0, 50, 0);
    }

    /**
     * 添加状态到bitmap的位置
     * @param int $offset
     */
    public function addBit(int $offset)
    {
        //怎么求一个数的字节位置和位位置呢，
        //比如1234，字节位置：1234/64=19，位位置：1234%64=18。
        $bytePos = $offset / self::INIT_BIT_SIZE;
        $bitPos = $offset % self::INIT_BIT_SIZE;
        $position = 1 << $bitPos;   //标记这个数存在
        if (!isset($this->bitmap[$bytePos])) {
            //范围超出的时候，扩容一下
            $bitmap_count = count($this->bitmap);
            $diff_number = $bytePos - $bitmap_count + 1;
            $this->bitmap = array_merge($this->bitmap, array_fill($bitmap_count, $diff_number, 0));
        }

        $this->bitmap[$bytePos] = $this->bitmap[$bytePos] | $position; //合并这个数到原来去

    }

    /**
     * 返回bitmap集合
     * @return array
     */
    public function getBitMap(): array
    {
        return $this->bitmap;
    }

    /**
     * 输出bitmap，将bitmap的某一位，对应转化成int
     * @param array $bitmap
     * @return array
     */
    private static function outPrint(array $bitmap): array
    {
        $result = array();
        foreach ($bitmap as $k => $item) {
            for ($i = 0; $i < self::INIT_BIT_SIZE; $i++) {
                $temp = 1 << $i;
                $flag = $temp & $item;
                if ($flag) {
                    $result[] = $k * self::INIT_BIT_SIZE + $i;
                }
            }
        }
        return $result;
    }

    /**
     * 两个bitmap取交集
     * @param BitMap $bitmap_object_1
     * @param BitMap $bitmap_object_2
     * @return array
     * @throws \Exception
     */
    public static function intersectByBitMap(BitMap $bitmap_object_1, BitMap $bitmap_object_2)
    {
        $bitmap_1 = $bitmap_object_1->getBitMap();
        $bitmap_2 = $bitmap_object_2->getBitMap();

        if (count($bitmap_2) > count($bitmap_1)) {
            return $bitmap_object_2->intersect($bitmap_object_1);
        }

        return $bitmap_object_1->intersect($bitmap_object_2);

    }

    /**
     * 交集
     * @param BitMap $bitmap_object
     * @return array
     * @throws \Exception
     */
    public function intersect(BitMap $bitmap_object)
    {
        $my_bitmap = $this->getBitMap();
        $bitmap = $bitmap_object->getBitMap();
        if (count($my_bitmap) < count($bitmap_object->getBitMap())) {
            throw new \Exception('bitmap_object的集合不能比this的多');
        }

        $c = array();
        foreach ($my_bitmap as $k => $v) {
            if (isset($bitmap[$k])) {
                $c[$k] = $my_bitmap[$k] & $bitmap[$k]; //二进制 & 计算求交集
            } else {
                $c[$k] = $my_bitmap[$k] & 0; //二进制 & 计算求交集
            }
        }

        return self::outPrint($c);
    }

    /**
     * 合并集合
     * @param BitMap $bitmap_object_1
     * @param BitMap $bitmap_object_2
     * @return array
     * @throws \Exception
     */
    public static function mergeByBitMap(BitMap $bitmap_object_1, BitMap $bitmap_object_2)
    {
        $bitmap_1 = $bitmap_object_1->getBitMap();
        $bitmap_2 = $bitmap_object_2->getBitMap();

        if (count($bitmap_2) > count($bitmap_1)) {
            return $bitmap_object_2->merge($bitmap_object_1);

        }
        return $bitmap_object_1->merge($bitmap_object_2);

    }

    /**
     * bitmap合并
     * @param BitMap $bitmap_object
     * @return array
     * @throws \Exception
     */
    public function merge(BitMap $bitmap_object)
    {
        $my_bitmap = $this->getBitMap();
        $bitmap = $bitmap_object->getBitMap();
        if (count($my_bitmap) < count($bitmap_object->getBitMap())) {
            throw new \Exception('bitmap_object的集合不能比this的多');
        }

        $c = array();

        foreach ($my_bitmap as $k => $v) {
            if (isset($bitmap[$k])) {
                $c[$k] = $my_bitmap[$k] | $bitmap[$k]; //二进制 & 计算求交集
            } else {
                $c[$k] = $my_bitmap[$k] | 0; //二进制 & 计算求交集
            }
        }

        return self::outPrint($c);
    }
}