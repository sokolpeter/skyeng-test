<?php

class LongIntCalc
{
  public static function add($a, $b)
  {
    if (self::isNegative($a) && self::isNegative($b)) {
      return '-' . self::addAbs($a, $b);
    } elseif (!self::isNegative($a) && !self::isNegative($b)) {
      return self::addAbs($a, $b);
    } elseif (!self::isNegative($a) && self::isNegative($b)) {
      return self::subAbs($a, $b);
    } else {
      return self::subAbs($b, $a);
    }
  }

  public static function abs($n)
  {
    $match = [];
    preg_match('/\d+/', $n, $match);
    return $match[0];
  }

  public static function isNegative($a)
  {
    return $a[0] === '-';
  }

  private static function addAbs($a, $b)
  {
    $a = self::abs($a);
    $b = self::abs($b);
    $len = self::alignAbs($a, $b);
    $carry = 0;

    for ($i = $len - 1; $i >= 0; $i--) {
      $carry += (int)$a[$i] + (int)$b[$i];
      $a[$i] = (string)($carry % 10);
      $carry = (int)($carry / 10);
    }

    if ($carry) {
      $a = (string)$carry . $a;
    }

    return $a;
  }

  private static function subAbs($a, $b)
  {
    $a = self::abs($a);
    $b = self::abs($b);
    $sign = '';

    if (self::compareAbs($a, $b) < 0) {
      $sign = '-';
      list($a, $b) = [$b, $a];
    }

    $len = self::alignAbs($a, $b);
    $carry = 0;

    for ($i = $len - 1; $i >= 0; $i--) {
      $carry += (int)$a[$i] - (int)$b[$i] + 10;
      $a[$i] = (string)($carry % 10);
      $carry = $carry < 10 ? -1 : 0;
    }

    $a = ltrim($a, '0');

    if (strlen($a) < 1) {
      return '0';
    }

    return $sign . $a;
  }

  private static function compareAbs($absA, $absB)
  {
    $lA = strlen($absA);
    $lB = strlen($absB);

    if ($lA < $lB) {
      return -1;
    }

    if ($lA > $lB) {
      return 1;
    }

    for ($i = 0; $i < $lA; $i++) {
      if ($absA[$i] > $absB[$i]) {
        return 1;
      }

      if ($absA[$i] < $absB[$i]) {
        return -1;
      }
    }

    return 0;
  }

  private static function alignAbs(&$absA, &$absB)
  {
    $len = max(strlen($absA), strlen($absB));
    $absA = str_pad($absA, $len, '0', STR_PAD_LEFT);
    $absB = str_pad($absB, $len, '0', STR_PAD_LEFT);
    return $len;
  }
}

function long_int_add($a, $b)
{
  return LongIntCalc::add($a, $b);
}
