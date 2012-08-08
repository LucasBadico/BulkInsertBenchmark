<?php

class DataProviderRandomColumns implements DataProvider {
  private $name;
  private $documentCount;
  private $columnCount;

  public function __construct($name, $documentCount, $columnCount) {
    $this->name = $name;
    $this->documentCount = $documentCount;
    $this->columnCount = $columnCount;
  }

  public function getName() {
    return $this->name;
  }

  public function getDocumentCount() {
    return $this->documentCount;
  }

  public function init() {
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

  public function shutdown() {
  }
}
