<?php

declare(strict_types = 1);

namespace JuniorFontenele\LaravelExceptions\Console\Commands;

use Illuminate\Foundation\Console\ExceptionMakeCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppExceptionMakeCommand extends ExceptionMakeCommand
{
    protected $name = 'make:app-exception';

    protected $description = 'Create a new custom app exception class';

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/app-exception.stub';
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
    }
}
