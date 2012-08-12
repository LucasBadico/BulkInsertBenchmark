<?php 

class RendererCsv implements Renderer {
  private $fp;
  private $separator;

  public function __construct($filename, $separator) {
    $this->fp = fopen($filename, "w");
    if (!$this->fp) {
      throw new Exception("cannot write csv output file ".$filename);
    }

    $this->separator = $separator;
  }

  public function init() {
    fprintf($this->fp, 
            "adapter_name%sdocument_prototype_name%stotal_document_count%sblock_size%stotal_insert_time%snet_insert_time%snet_insert_time_per_document%sdatafile_size%serrors\n",
            $this->separator,
            $this->separator,
            $this->separator,
            $this->separator,
            $this->separator,
            $this->separator,
            $this->separator,
            $this->separator
           );
  }

  public function output(array $results) {
    fprintf($this->fp,
            "\"%s\"%s\"%s\"%s\"%d\"%s\"%d\"%s\"%s\"%s\"%s\"%s\"%s\"%s\"%s\"%s\"%d\"\n", 
            $results["adaptername"],
            $this->separator,
            $results["providername"],
            $this->separator,
            $results["count"],
            $this->separator,
            $results["blocksize"],
            $this->separator,
            self::number($results["totaltime"]),
            $this->separator,
            self::number($results["adaptertime"]),
            $this->separator,
            self::number($results["doctime"]),
            $this->separator,
            $results["datafilesize"],
            $this->separator,
            $results["errors"]);
  }

  public function shutdown() {
    fclose($this->fp);
  }
  
  private static function number($value) {
    $string = sprintf("%0.6f", $value);
    return str_replace(".", ",", $string);
  }
}
