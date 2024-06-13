<?php

namespace app\Observers;

use app\Commands\Command;

abstract class CommandObserver implements \SplObserver
{
    abstract public function doUpdate(Command $command): void;

    public function __construct(private Command $command)
    {
        $command->attach($this);
    }

    public function update(\SplSubject $subject): void
    {
        if ($subject === $this->command) $this->doUpdate($subject);
    }
}
