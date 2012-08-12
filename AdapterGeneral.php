<?php

abstract class AdapterGeneral {
  protected $options;
  protected $totalTime;
  protected $errors;
  protected $id;

  public function getTime() {
    return $this->totalTime;
  }

  public function getErrors() {
    return $this->errors;
  }

  public function init() {
    $this->totalTime = 0.0;
    $this->errors = 0;
    $this->id = 1;
    if (isset($this->options["startid"])) {
      $this->id = $this->options["startid"];
    }
  }

}
