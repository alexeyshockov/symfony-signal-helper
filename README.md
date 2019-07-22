# SignalHelper

Helper for Symfony Console to handle process signals (like termination).

## Installation

```bash
$ composer require alexeyshockov/symfony-signal-helper
```

## Usage

Just register the helper in your application (`app/console`, for example):
```php
#!/usr/bin/env php
<?php

// ...

$console = new Application();

$console->getHelperSet()->set(new SignalHelper());

$console->run($input);
```

And use it inside your command:

```php
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('signal');
        $helper->listen();
        
        while (true) {
            if ($handler->signalReceived()) {
                // Stop by any of SIGINT or SIGTERM.
                break;
            }
            
            // Some business logic.
        }
    }
```


If you need to pass this to a service you can use the interface:

```php
class SomeServiceClass
{
    /** @var TerminationSignalInterface */
    private $terminationSignal;

    public function __construct(
        TerminationSignalInterface $terminationSignal,
    ) {
        $this->terminationSignal = $terminationSignal;
    }


    public function businessLogic()
    {
        do {
            if ($this->terminationSignal->signalReceived()) {
                return null;
            }
             ..
        } while (..);
    }
}
```
