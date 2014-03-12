<?php

/*
 * This file is part of the YepsuaCommonsBundle.
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
  
  /**
   * 
   * @param type $repository
   * @param type $entityName
   * @param type $orderBy
   * @param type $sord
   * @param type $filters
   * @return \Doctrine\ORM\QueryBuilder
   */
  public static function buildQuery($repository, $entityName, $orderBy = null, $sord = 'ASC', $filters = null) {
    $query = $repository->createQueryBuilder($entityName)
                        ->orderBy($orderBy, $sord);
    if ($orderBy !== null) {
      if ($filters !== null) {
        $filterData = CommonsFilter::buildQueryFilter($filters);
        if (sizeof($filterData['parameters']) > 0) {
          $query = $repository->createQueryBuilder($entityName)
                              ->where($filterData['where'])
                              ->setParameters($filterData['parameters'])
                              ->orderBy($orderBy, $sord);
        }
      }
    } else {
      $query = $repository->createQueryBuilder($entityName);
    }
    return $query;
  }
  
  public static function count($repository, $entityName = null){
    return self::countQuery($repository, $entityName)->getQuery()->getSingleScalarResult();
  }
  
  public static function countQuery($repository, $entityName = null){
    if($entityName === null){
       $entityName = $repository->getClassName();
       $entityName = substr_replace($entityName,':', strrpos($entityName, '\\'), 1); 
    }
    return $repository->createQueryBuilder($entityName)->select(sprintf('COUNT(%s)',$entityName));
  }

}