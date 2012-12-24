<?php

/*
 * This file is part of the YepsuaGeneratorBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\CommonsBundle\Persistence\Filter;

/**
 * Util to generate filters in SQL <-> DQL
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class CommonsFilter {
  
  public static function buildQueryFilter($filters) {
    $where = "";
    $parameters = array();
    $filters = json_decode($filters, true);
    $rules = $filters['rules'];
    if (sizeof($rules) > 0) {
      $groupOp = $filters['groupOp'];
      $i = 0;
      foreach ($rules as $rule) {
        $param = str_replace('.', '_', $rule['field']);
        $where .= sprintf(self::getFilterType($rule['data']), $rule['field'], $param, $i, $groupOp);
        $parameters[sprintf('%s_%s', $param, $i++)] = sprintf(self::getFilterType2($rule['data']), strtolower($rule['data']));
      }
      $where = substr_replace($where, "", strrpos($where, $groupOp), strlen($groupOp));
    }
    return array("where" => $where, "parameters" => $parameters);
  }

  protected static function getFilterType($param) {
    $dateParser = date_parse($param);
    if (sizeof($dateParser['errors']) == 0) {
      $sintax = '%s = :%s_%s %s ';
    } elseif (!is_numeric($param)) {
      $sintax = 'LOWER(%s) LIKE :%s_%s %s ';
    } else {
      $sintax = '%s = :%s_%s %s ';
    }
    return $sintax;
  }

  protected static function getFilterType2($param) {
    $dateParser = date_parse($param);
    if (sizeof($dateParser['errors']) == 0) {
      $sintax = '%s';
    } elseif (!is_numeric($param)) {
      $sintax = '%%%s%%';
    } else {
      $sintax = '%s';
    }
    return $sintax;
  }
}