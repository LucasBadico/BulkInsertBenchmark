<?php

function benchmarkAutoload($className) {
  require __DIR__ . "/" . $className . ".php";
}

spl_autoload_register("benchmarkAutoload");

require "config.php";

$benchmark = new InsertBenchmark();
$benchmark->run($dataProviders, $adapters, $renderers);

