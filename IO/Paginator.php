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
 * Paginator
 * @author Omar Yepez <omar.yepez@yepsua.com>
 */
class Paginator {
    
  private $page;
  private $rows;
  private $entities;
  
  public function __construct($page, $rows){
      $this->entities = new ArrayObject;
      $this->setPage($page);
      $this->setRows($rows);
  }
  
  public function paginate($entities) {
      $this->entities = new ArrayObject;
      $page = $this->getPage();
      $rows = $this->getRows();      
      for ($i = ($rows * $page) - $rows; $i <= $rows * $page; $i++) {
        if (isset($entities[$i])) {
          $this->entities->append($entities[$i]);
        } else {
          break;
        }
      }
      return $this->getEntities()->getArrayCopy();
  }
  
  /**
   *
   * @return ArrayObject 
   */
  public function getEntities() {
    return $this->entities;
  }
  
  /**
   *
   * @param ArrayObject $entities 
   */
  public function setEntities(ArrayObject $entities) {
    $this->entities = $entities;
  }
  
  /**
   *
   * @return integer 
   */
  public function getPage() {
    return $this->page;
  }
  
  /**
   *
   * @param integer $page 
   */
  public function setPage($page) {
    $this->page = $page;
  }
  
  /**
   *
   * @return integer 
   */
  public function getRows() {
    return $this->rows;
  }
  
  /**
   *
   * @param integer $rows 
   */
  public function setRows($rows) {
    $this->rows = $rows;
  }  
}