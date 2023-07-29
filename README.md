# PDO Wrapper

## What PDO Wrapper is

PDO Wrapper is a library intended to be a drop-in replacement for PHP's native PDO whilst adding a number of additional features that 

## What PDO offers
* Lazy connect.  When a PHP PDO object is instantiated, it connects to the database immediately, regardless of whether you're ready to execute queries or not.  PDO Wrapper won't actually connect until you start issuing queries or executing statements.  Even preparing statements is delayed until the statement is actually triggered.  This should in theory reduce the amount of time your application will be connected to the database and keep the connection busier while it is open, which can increase overall database throughput.
* Statement caching.  PDO Wrapper can optionally cache any statements you prepare and returned the already prepared statement if you attempt to prepare it again.  This should in theory reduce database workload and memory usage.
* More robust instantiation.  The native PDO class takes constructor arguments in the form of strings and arrays.  PDO Wrapper uses a connection factory which takes a ConnectionSpec object with stricter typing rules, aiding in the troubleshooting of issues caused by an invalid configuration.
* More robust error reporting.  PDO Wrapper makes use of enums for representing error codes, ensuring that the error code is always valid for the database you're connected to.  The error reporting mechanism also simplifies identifying whether the error can be recovered from by retrying or not.
* Optional automatic reconnect.  In the event of a connection failure PDO Wrapper can be configured to retry a failed connection attempt.  
* Optional transaction replay.  PDO Wrapper allows you to define a transaction as a block of work that will be executed atomically by the underlying database.  In the event of the transaction failing due to a transient error (such as a deadlock), PDO Wrapper can be configured to automatically retry the block of work instead of failing instantly.  

## What PDO Wrapper isn't
* A database abstraction layer.  This library is intended to be a drop-in replacement for PHP's PDO class with some additional functionality.  As such you should be able to use it with pre-existing database abstractions so long as they connect to the database via PDO.  If you use it directly, you will still have to be aware of the foibles of the database you're connection to, just like with native PDO.
* Always faster than native PDO.  This library sits on top of the native PDO library and as such it can't actually do anything faster than PDO does.  While features like lazy connect and statement caching are intended to improve performance, they can only be of benefit if your application actually makes use of them.  Preparing a statement and using it only once will not be faster than native PDO, and in fact will carry a slight additional overhead.  
* Ready for production.  This project is very much a work in progress, as such it's experimental and should not be used in production yet.  As the code reaches production quality and feature completeness we will start tagging releases.  
