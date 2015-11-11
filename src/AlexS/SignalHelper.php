<?php

namespace AlexS;

use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;

class SignalHelper implements HelperInterface
{
    /**
     * @var array
     */
    private $signals = [];

    /**
     * @var HelperSet
     */
    private $helperSet;

    public function __construct()
    {
        if (!extension_loaded('pcntl')) {
            throw new \RuntimeException('PCNTL extension is not loaded.');
        }
    }

    /**
     * Bind helper's handlers.
     */
    public function listen()
    {
        $handler = function ($code) {
            array_unshift($this->signals, $code);
        };

        // kill (default signal)
        pcntl_signal(SIGTERM, $handler);
        // Ctrl + C
        pcntl_signal(SIGINT, $handler);
        // kill -s HUP
        pcntl_signal(SIGHUP, $handler);
    }

    /**
     * Restore default handlers for signals.
     */
    public function restoreDefaultHandlers()
    {
        pcntl_signal(SIGTERM, SIG_DFL);
        pcntl_signal(SIGINT, SIG_DFL);
        pcntl_signal(SIGHUP, SIG_DFL);
    }

    /**
     * Use cases:
     *  in_array(SIGHUP, $helper->takeSignals())
     * or
     *  array_intersect([SIGINT, SIGTERM], $helper->takeSignals())
     *
     * @return array List with signals (integer codes), can contains duplicates. Newest signal will be first.
     */
    public function takeSignals()
    {
        // All signals will caught only inside this call.
        pcntl_signal_dispatch();

        $signals = $this->signals;
        $this->signals = [];

        return $signals;
    }

    /**
     * @param HelperSet|null $helperSet
     */
    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }

    /**
     * @return HelperSet
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'signal';
    }
}
