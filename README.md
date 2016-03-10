# PHP fork example

## Setup

### git clone

```
$ git clone
```

### composer install

```
$ composer install
```

### Run unit test

- SyncProcessor

    ```
    $ cd /path/to/example
    $ vendor/bin/phpunit -c ./phpunit.xml.dist test/SyncProcessorTest.php
    ```

    ```
    PHPUnit 4.8.23 by Sebastian Bergmann and contributors.
    
    .
    
    Time: 55.44 seconds, Memory: 6.00Mb
    
    OK (1 test, 0 assertions)
    ```

- AsyncProcessor

    ```
    $ cd /path/to/example
    $ vendor/bin/phpunit -c ./phpunit.xml.dist test/AsyncProcessorTest.php
    ```

    ```
    PHPUnit 4.8.23 by Sebastian Bergmann and contributors.
    
    .
    
    Time: 8.87 seconds, Memory: 5.25Mb
    
    OK (1 test, 0 assertions)
    ```