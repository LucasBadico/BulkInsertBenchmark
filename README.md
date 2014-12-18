BulkInsertBenchmark
===================

A very simple PHP-based benchmark program to compare the speed of bulk document insertions for
- CouchDB
- MongoDB
- ArangoDB

It sends batches of documents to the databases' bulk document APIs:
- the bulk_docs HTTP API for CouchDB
- the collection->batchInsert() native call for MongoDB
- /_api/import HTTP API for ArangoDB

It measures the total time necessary for sending each request and getting the response back. 
The time spend in PHP to prepare the documents is also measured but reported separately.

Note that these results are only partly comparable because individual HTTP requests will be made for CouchDB and ArangoDB but for MongoDB a persistent connection with a binary protocol will be used.
This is in the nature of things. 

The benchmark can be started by running the run.php script in CLI mode:
```
php run.php
```

Before running the script, the datasets need to be unpacked (they needed to be compressed due to Github's 100MB filesize limit).
Uncompressing the datafiles can be done by executing the following command in the benchmark directory:
```
./build_datasets.sh
```

The connection parameters and datafile directories for the individual database can be adjusted by renaming the config-example.php script to config.php and editing it. There is also a ready-to-go config file (`config.php`) which assumes an installation on localhost with default installation settings.

Needs PHP 5.3 or higher with curl extension. For MongoDB, you will need the native MongoDB driver for PHP. It can be installed by running `sudo pecl install mongo` or by installing the MongoDB driver with a package manager of choice, e.g. `sudo apt-get install php5-mongo`.
