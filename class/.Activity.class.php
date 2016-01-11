<?php
/**COPYRIGHTS**/ 
// Copyright 2008 - 2010 all rights reserved, SQLFusion LLC, info@sqlfusion.com

  /**
   * Class Activity
   * Update a time stamp, used to track anytime of activity for a contact
   * 
   * @author SQLFusion's Dream Team <info@sqlfusion.com>
   * @package OfuzCore
   * @license ##License##
   * @version 0.6
   * @date 2010-09-04
   * @since 0.6
   */


class Activity extends DataObject {
    public $table = "activity";
    public $primary_key = "idactivity";
    
    /**
     * Overload the update() method so we can fall back on idcontact as an update key
     */
    function update() {
      if ($this->getPrimaryKeyValue()) {
          parent::update();
      } else {
          $this->query("UPDATE ".$this->getTable()." SET `when`=now() WHERE idcontact=".$this->idcontact);
      }
    }
}
