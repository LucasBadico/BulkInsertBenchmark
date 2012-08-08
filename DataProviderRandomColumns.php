<?php

class DataProviderRandomColumns extends DataProviderGeneral implements DataProvider {
  private $columnCount;

  public function __construct($name, $documentCount, $blockSize, $columnCount) {
    $this->columnCount = $columnCount;
    
    parent::__construct($name, $documentCount, $blockSize);
  }

  public function getNextDocument($offset, $id) {
    if ($offset >= $this->documentCount) {
      return NULL;
    }

    $document = array();
    for ($i = 0; $i < $this->columnCount; ++$i) {
      $document["column" . ($i + 1) . "-" . $offset] = "column" . ($i + 1). "-" . $offset;
    }

    return $document;
  }

}
