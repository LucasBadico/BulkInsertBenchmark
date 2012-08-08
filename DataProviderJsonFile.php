<?php

class DataProviderJsonFile implements DataProvider {
  private $name;
  private $documentCount;
  private $filename;
  private $fp;
  private $offset;
  private $count;
  private $buffer;

  public function __construct($name, $documentCount, $filename) {
    $this->name = $name;
    $this->documentCount = $documentCount;
    $this->filename = $filename;

    if (!file_exists($this->filename)) {
      throw new Exception("cannot open file ".$this->filename);
    }
  }

  public function getName() {
    return $this->name;
  }

  public function getDocumentCount() {
    return $this->documentCount;
  }

  public function init() {
    $this->fp = fopen($this->filename, "rb");
    $this->offset = 0;
    $this->buffer = "";
    $this->count = 0;
  }

  public function getNextDocument($offset, $id) {
    $document = $this->getNextRow();

    return $document;
  }

  public function shutdown() {
    if ($this->fp) {
      fclose($this->fp);
    }

    $this->buffer = "";
  }

  private function getNextRow() {
    if ($this->count >= $this->documentCount) {
      return NULL;
    }

    while (true) {
      $position = strpos($this->buffer, "\n");

      if ($position === false or $position === 0) {
        $result = fread($this->fp, 8192);
        if ($result === false) {
          return NULL;
        }

        $this->buffer .= $result;
      }
      else {
        $part = substr($this->buffer, 0, $position);

        $this->buffer = substr($this->buffer, $position + 1);
        $this->count++;

        return json_decode(trim($part));
      }
    }
  }
}
