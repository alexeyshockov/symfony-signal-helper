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
<?php

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('signal');
        $helper->listen();
        
        while (true) {
            if (count(array_intersect([SIGINT, SIGTERM], $helper->takeSignals())) > 0) {
                // Stop by any of SIGINT or SIGTERM signals.
                break;
            }
            
            // Some business logic.
        }
    }
```
