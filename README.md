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

The benchmark script also compares the datafile sizes so one can see how much disk space got allocated by the database for the documents inserted.

The benchmark can be started by running the driver.php script in CLI mode.
The connection parameters and datafile directories can be adjusted easily by editing this script.
The structure and values of the documents inserted can also be adjusted by editing the $protoTypes array in the same file. 

Needs PHP 5.3 and the native MongoDB driver for PHP. This can be installed by running "sudo pecl install mongo"
