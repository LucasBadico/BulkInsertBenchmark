<?php

class DataProviderStatic extends DataProviderGeneral implements DataProvider {
  private $protoType;

  public function __construct($name, $documentCount, $blockSize, array $protoType) {
    $this->protoType = $protoType;

    parent::__construct($name, $documentCount, $blockSize);
  }

  public function getNextDocument($offset, $id) {
    if ($offset >= $this->documentCount) {
      return NULL;
    }

    $document = $this->protoType;
    $document["_id"] = $id;

    return $document;
  }

}
