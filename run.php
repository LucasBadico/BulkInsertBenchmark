<?php

function benchmarkAutoload($className) {
  require __DIR__ . "/" . $className . ".php";
}

spl_autoload_register("benchmarkAutoload");

require "config.php";

$renderer = new RendererCsv();

$benchmark = new InsertBenchmark();
$benchmark->run(1000, $dataProviders, $adapters, $renderer);

