<?php

interface Adapter {
  public function __construct(array $options);

  public function getName();

  public function init();
  
  public function addDocuments(array $documents);
  
  public function getDocumentCount();

  public function shutdown();

  public function getFilesize();
  
  public function getNextId();

  public function getTime();
}

