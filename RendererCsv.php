<?php 

class RendererCsv implements Renderer {
  public function init() {
    printf("adapter_name;document_prototype_name;total_document_count;block_size;total_insert_time;net_insert_time;net_insert_time_per_document;datafile_size;errors\n");
  }

  public function output(array $results) {
    printf("\"%s\";\"%s\";%d;%d;%s;%s;%s;%d;%d\n", 
           $results["adaptername"],
           $results["providername"],
           $results["count"],
           $results["blocksize"],
           self::number($results["totaltime"]),
           self::number($results["adaptertime"]),
           self::number($results["doctime"]),
           $results["datafilesize"],
           $results["errors"]);
  }

  public function shutdown() {
  }
  
  private static function number($value) {
    $string = sprintf("%0.6f", $value);
    return str_replace(".", ",", $string);
  }
}
