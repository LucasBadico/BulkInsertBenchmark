<?php

class DataProviderStatic implements DataProvider {
  private $name;
  private $documentCount;
  private $protoType;

  public function __construct($name, $documentCount, array $protoType) {
    $this->name = $name;
    $this->documentCount = $documentCount;
    $this->protoType = $protoType;
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
    $document = $this->protoType;
    $document["_id"] = $id;

    return $document;
  }

  public function shutdown() {
  }
}
