<?php
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
 * @param $arr
 * @return array
 */
function BitMap($arr)
{
    $bitmap = array_fill(0, 50, 0);
    $int_bit_size = PHP_INT_SIZE * 8; //一个整形占多少位 = 整数大小 * bit, 一个数组的位置，可以放多少个 位置的二进制
    foreach ($arr as $item) {
        $bytePos = $item / $int_bit_size;
        $bitPos = $item % $int_bit_size;
        $position = 1 << $bitPos;   //标记这个数存在
        $bitmap[$bytePos] = $bitmap[$bytePos] | $position; //合并这个数到原来去
    }
    return $bitmap;
}

//输出排序数组
function outPut($bitmap)
{
    $int_bit_size = PHP_INT_SIZE * 8;
    $result = array();
    foreach ($bitmap as $k => $item) {
        for ($i = 0; $i < $int_bit_size; $i++) {
            $temp = 1 << $i;
            $flag = $temp & $item;
            if ($flag) {
                $result[] = $k * $int_bit_size + $i;
            }
        }
    }
    return $result;
}

$test_arr = array(1, 4, 3, 50, 34, 60, 100, 88, 200, 150, 300,3200); //定义一个乱序的数组
$arr_temp = BitMap($test_arr);
$result = outPut($arr_temp);
echo "<pre>";
var_dump($result);

echo "<pre>";


var_dump(array_fill(50,10,0));