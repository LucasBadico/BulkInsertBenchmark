<?php 

class RendererCsvScreen extends RendererCsv implements Renderer {
  public function __construct($separator) {
    parent::__construct("php://stdout", $separator);
  }
}
