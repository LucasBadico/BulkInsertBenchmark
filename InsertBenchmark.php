<?php

class InsertBenchmark {
  public function run($blockSize, array $dataProviders, array $adapters, Renderer $renderer) {
    $renderer->init();

    foreach ($dataProviders as $dataProvider) {
      $documentCount = $dataProvider->getDocumentCount();

      foreach ($adapters as $adapter) {
        $this->runSingleTest($documentCount, $blockSize, $dataProvider, $adapter, $renderer);
      }
    }

    $renderer->shutdown();
  }

  private function runSingleTest($count, $blockSize, DataProvider $dataProvider, Adapter $adapter, Renderer $renderer) {
    $dataProvider->init();

    $adapter->init();
    assert($adapter->getDocumentCount() == 0);
    $inserted = 0;
    $exit = false;

    $start = microtime(true);

    for ($i = 0; $i < $count; $i += $blockSize) {
      $documents = array();
      for ($j = 0; $j < $blockSize; ++$j) {
        $document = $dataProvider->getNextDocument($i + $j, $adapter->getNextId());
        if ($document === NULL) {
          $exit = true;
          break;
        }
        $documents[] = $document;
      }

      $inserted += count($documents);
      $adapter->addDocuments($documents);

      if ($exit) {
        break;
      }
    }

    $adapterTime = $adapter->getTime();
    $totalTime = microtime(true) - $start;

    assert($adapter->getDocumentCount() == $count);

    $datafileSize = $adapter->getFilesize();
    $errorCount = $adapter->getErrors();

    $results = array(
        "adaptername" => $adapter->getName(),
        "providername" => $dataProvider->getName(),
        "count" => $inserted,
        "blocksize" => $blockSize,
        "totaltime" => $totalTime,
        "adaptertime" => $adapterTime,
        "doctime" => $adapterTime / $count,
        "datafilesize" => $datafileSize,
        "errors" => $errorCount,
        );

    $renderer->output($results);

    $adapter->shutdown();
    $dataProvider->shutdown();
  }

}

