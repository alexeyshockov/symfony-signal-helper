<?php

namespace AlexS;

use Traversable;

/**
 * @param iterable $flow
 *
 * @return Traversable
 */
function with_signal_brake($flow)
{
    $handler = new SignalHelper();
    $handler->listen();

    foreach ($flow as $key => $value) {
        if (count(array_intersect([SIGINT, SIGTERM], $handler->takeSignals())) > 0) {
            // Stop by any of SIGINT or SIGTERM.
            break;
        }

        yield $key => $value;
    }
}
