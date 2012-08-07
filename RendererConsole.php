<?php 

class RendererConsole implements Renderer {
  public function init() {
  }

  public function output(array $results) {
    printf("Adapter name                                          : %s\n", $results["adaptername"]);
    printf("Document prototype name                               : %s\n", $results["providername"]);
    printf("Total document count                                  : %d\n", $results["count"]);
    printf("Block/batch size                                      : %d\n", $results["blocksize"]);
    printf("Total insert time (including PHP client overhead)     : %0.6f s\n", $results["totaltime"]);
    printf("Net insert time (request/response only)               : %0.6f s\n", $results["adaptertime"]);
    printf("Net insert time (request/response only) per document  : %0.6f s\n", $results["doctime"]);
    printf("Datafile size (before/without compaction)             : %d\n", $results["datafilesize"]);
    printf("Errors                                                : %d\n", $results["errors"]);
    printf("\n\n");
  }

  public function shutdown() {
    printf("Tests finished.\n\n");
  }
  
}
