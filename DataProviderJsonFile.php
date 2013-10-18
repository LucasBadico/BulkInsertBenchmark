<?php

class DataProviderJsonFile extends DataProviderGeneral implements DataProvider {
  private $filename;
  private $fp;
  private $buffer;

  const CHUNK_SIZE = 16384;

  public function __construct($name, $documentCount, $blockSize, $filename) {
    $this->filename = $filename;

    if (!file_exists($this->filename)) {
      throw new Exception("cannot open file ".$this->filename);
    }

    parent::__construct($name, $documentCount, $blockSize);
  }

  public function init() {
    $this->fp = fopen($this->filename, "rb");
    $this->buffer = "";
  }

  public function getNextDocument($offset, $id) {
    if ($offset >= $this->documentCount) {
      return NULL;
    }

    while (true) {
      $position = strpos($this->buffer, "\n");

      if ($position === false) {
        $result = fread($this->fp, self::CHUNK_SIZE);
        if ($result === false || $result === "") {
          return NULL;
        }

        $this->buffer .= $result;
      }
      else if ($position === 0) {
        $this->buffer = substr($this->buffer, 1);
      }
      else {
        $part = substr($this->buffer, 0, $position);

        $this->buffer = substr($this->buffer, $position + 1);
        return json_decode($part, true);
      }
    }
  }

  public function shutdown() {
    if ($this->fp) {
      fclose($this->fp);
    }

    $this->buffer = "";
  }

}
