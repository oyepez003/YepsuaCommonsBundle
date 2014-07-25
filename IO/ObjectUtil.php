<?php

/*
 * This file is part of the YepsuaCommonsBundle.
 *
 * (c) Omar Yepez <omar.yepez@yepsua.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\CommonsBundle\IO;

/**
 * ObjectUtil
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class ObjectUtil {
  
  /**
   * 
   * @param type $entities
   * @param type $pattern
   * @return string 
   */
  public static function entityToKeyValue($entities, $pattern ='"%KEY%":"%VALUE%"'){
    $toStringVal = "";
    foreach($entities as $entitie){
       $_pattern = $pattern;
       $_pattern = str_replace('%KEY%', $entitie->getId(), $_pattern);
       $_pattern = str_replace('%VALUE%', $entitie->__toString(), $_pattern);
       $toStringVal .= $_pattern;
    }
    return $toStringVal;
  }
  
  /**
   * See __toString__ method.
   * @param type $entities
   * @param array $config
   * @return type
   */
  public static function collectionToString($entities, array $config = array('pattern' => '%s,','max' => 4, 'more_indicator' => '...')){
    return self::__toString__($entities, $config);
  }
  
  /**
   * Parse an object or a object collection to a string, using the method 
   * __toString for each object.
   * @param type $entities The object - The Collection
   * @param array $config
   * @return string
   */
  public static function __toString__($entities, array $config = array('pattern' => '%s,','max' => 4, 'more_indicator' => '...')){
    $toStringVal = "";
    if($entities !== null){
      if(!(is_array($entities) || $entities instanceof \Traversable)){
        $toStringVal = $entities->__toString();
      }else{
        if(sizeof($entities) > 0){
          $max = (sizeof($entities) > $config['max']) ? $config['max'] : sizeof($entities);

          for ($index = 0; $index < $max; $index++) {
             $toStringVal .= sprintf($config['pattern'], $entities[$index]->__toString());
          }

          if(sizeof($entities) > $max){
            $toStringVal .= $config['more_indicator'];
          }else{
            $toStringVal = substr($toStringVal, 0, -1);
          }
        }
      }
    }
    return $toStringVal;
  }
}