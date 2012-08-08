<?php

abstract class DataProviderGeneral {
  protected $name;
  protected $documentCount;
  protected $blockSize;

  public function __construct($name, $documentCount, $blockSize) {
    $this->name = $name;
    $this->documentCount = $documentCount;
    $this->blockSize = $blockSize;
  }

  public function getName() {
    return $this->name;
  }
  
  public function getDocumentCount() {
    return $this->documentCount;
  }
  
  public function getBlockSize() {
    return $this->blockSize;
  }

  public function init() {
  }
  
  public function shutdown() {
  }

}
