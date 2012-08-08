<?php

interface DataProvider {
  public function getName();

  public function getDocumentCount();
  
  public function getBlockSize();

  public function init();

  public function getNextDocument($offset, $id);

  public function shutdown();
}
