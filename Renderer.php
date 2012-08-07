<?php

interface Renderer {
  public function init();
  
  public function output(array $results);

  public function shutdown();
}
