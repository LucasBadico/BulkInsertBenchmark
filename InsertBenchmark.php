<?php

class InsertBenchmark {
  public function run(array $dataProviders, array $dbAdapters, array $renderers) {
    foreach ($renderers as $renderer) {
      $renderer->init();
    }

    foreach ($dataProviders as $dataProvider) {
      foreach ($dbAdapters as $dbAdapter) {
        $this->runSingleTest($dataProvider, $dbAdapter, $renderers);
      }
    }

    foreach ($renderers as $renderer) {
      $renderer->shutdown();
    }
  }

  private function runSingleTest(DataProvider $dataProvider, Adapter $dbAdapter, array $renderers) {
    $dataProvider->init();
    $documentCount = $dataProvider->getDocumentCount();
    $blockSize = $dataProvider->getBlockSize();

    $dbAdapter->init();
    if ($dbAdapter->getDocumentCount() != 0) {
      throw new Exception(sprintf("actual document count is not the expected value (%s vs. %s)", (int) $dbAdapter->getDocumentCount(), 0));
    }
    $inserted = 0;
    $exit = false;

    $start = microtime(true);

    for ($i = 0; $i < $documentCount; $i += $blockSize) {
      $documents = array();
      for ($j = 0; $j < $blockSize; ++$j) {
        $document = $dataProvider->getNextDocument($i + $j, $dbAdapter->getNextId());
        if ($document === NULL) {
          $exit = true;
          break;
        }
        $documents[] = $document;
      }

      $inserted += count($documents);
      $dbAdapter->addDocuments($documents);

      if ($exit) {
        break;
      }
    }

    $adapterTime = $dbAdapter->getTime();
    $totalTime = microtime(true) - $start;

    if ($dbAdapter->getDocumentCount() != $documentCount) {
      throw new Exception(sprintf("actual document count is not the expected value (%s vs. %s)", (int) $dbAdapter->getDocumentCount(), $documentCount));
    }

    $datafileSize = $dbAdapter->getFilesize();
    $errorCount = $dbAdapter->getErrors();

    $results = array(
        "adaptername" => $dbAdapter->getName(),
        "providername" => $dataProvider->getName(),
        "count" => $inserted,
        "blocksize" => $blockSize,
        "totaltime" => $totalTime,
        "adaptertime" => $adapterTime,
        "doctime" => $adapterTime / $documentCount,
        "datafilesize" => $datafileSize,
        "errors" => $errorCount,
        );

    foreach ($renderers as $renderer) {
      $renderer->output($results);
    }

    $dbAdapter->shutdown();
    $dataProvider->shutdown();
  }

}

