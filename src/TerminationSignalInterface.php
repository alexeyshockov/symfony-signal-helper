<?php

namespace AlexS;

interface TerminationSignalInterface
{
    public function listen();
    
    public function signalReceived();

    public function restoreDefaultHandlers();
}