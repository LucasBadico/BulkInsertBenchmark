<?php

class InsertBenchmark {
  private $count;
  private $blockSize;
  private $docProtoType;
  private $adapters = array();

  public function __construct() {
  }

  public function addAdapter(Benchmarkable $adapter) {
    $this->adapters[] = $adapter;
  }
  
  public function addAdapters(array $adapters) {
    foreach ($adapters as $adapter) {
      $this->addAdapter($adapter);
    }
  }

  public function run($count, $blockSize, $protoTypeName, array $protoTypeData, $csv = false) {
    $this->count = $count;
    $this->blockSize = $blockSize;

    if ($count / $blockSize != (int) ($count / $blockSize)) {
      throw new Exception("count must be divisable by blocksize");
    }


    foreach ($this->adapters as $adapter) {
      $adapter->init();
      assert($adapter->getDocumentCount() == 0);

      $start = microtime(true);

      for ($i = 0; $i < $this->count; $i += $this->blockSize) {
        $documents = array();
        for ($j = 0; $j < $this->blockSize; ++$j) {
          
          $document = $protoTypeData;
          $document["_id"] = $adapter->getNextId();
          $documents[] = $document;
        }

        $adapter->addDocuments($documents);
      }
     
      $adapterTime = $adapter->getTime();
      $totalTime = microtime(true) - $start;
        
      assert($adapter->getDocumentCount() == $this->count);

      $datafileSize = $adapter->getFilesize();
      $errorCount = $adapter->getErrors();
      
      if ($csv) {
        printf("\"%s\";\"%s\";%d;%d;%s;%s;%s;%d;%d\n", 
               $adapter->getName(), 
               $protoTypeName, 
               $this->count, 
               $this->blockSize, 
               self::number($totalTime), 
               self::number($adapterTime), 
               self::number($adapterTime / $this->count), 
               $datafileSize, 
               $errorCount);
      }
      else {
        printf("Adapter name                                          : %s\n", $adapter->getName());
        printf("Document prototype name                               : %s\n", $protoTypeName);
        printf("Total document count                                  : %d\n", $this->count);
        printf("Block/batch size                                      : %d\n", $this->blockSize);
        printf("Total insert time (including PHP client overhead)     : %0.6f s\n", $totalTime);
        printf("Net insert time (request/response only)               : %0.6f s\n", $adapterTime);
        printf("Net insert time (request/response only) per document  : %0.6f s\n", $adapterTime / $this->count);
      
        printf("Datafile size (before/without compaction)             : %d\n", $datafileSize);
        printf("Errors                                                : %d\n", $errorCount);
        printf("\n\n");
      }

      $adapter->shutdown();

    }
  }

  public static function printHeaders() {
    printf("adapter_name;document_prototype_name;total_document_count;block_size;total_insert_time;net_insert_time;net_insert_time_per_document;datafile_size;errors\n");
  }

  private static function number($value) {
    $string = sprintf("%0.6f", $value);
    return str_replace(".", ",", $string);
  }
}

