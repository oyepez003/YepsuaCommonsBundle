<?php

/*
 * This file is part of the YepsuaGeneratorBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\CommonsBundle\Persistence;

use Yepsua\CommonsBundle\Persistence\Filter\CommonsFilter;

/**
 * Util Object to generate selection queries in SQL <-> DQL
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class Dao {

  public static function buildQuery($repository, $entityName, $orderBy = null, $sord = 'ASC', $filters = null) {
    $query = $repository->createQueryBuilder($entityName)
            ->orderBy($orderBy, $sord)
            ->getQuery();
    if ($orderBy !== null) {
      if ($filters !== null) {
        $filterData = CommonsFilter::buildQueryFilter($filters);
        if (sizeof($filterData['parameters']) > 0) {
          $query = $repository->createQueryBuilder($entityName)
                  ->where($filterData['where'])
                  ->setParameters($filterData['parameters'])
                  ->orderBy($orderBy, $sord)
                  ->getQuery();
        }
      }
    } else {
      $query = $repository->createQueryBuilder($entityName)
              ->getQuery();
    }
    return $query;
  }

}