<?php

interface DataProvider {
  public function getName();

  public function getDocumentCount();

  public function init();

  public function getNextDocument($offset, $id);

  public function shutdown();
}
