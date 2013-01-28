<?php

/*
 * This file is part of the YepsuaCommonsBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Yepsua\CommonsBundle\IO;

use \ArrayObject;

/**
 * ObjectUtil
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class ObjectUtil {
  
  /**
   * 
   * @param type $entities
   * @param type $pattern
   * @return type 
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
}