<?php

namespace InetStudio\PersonsPackage\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:persons-package:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup persons package';

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Persons setup',
                'command' => 'inetstudio:persons-package:persons:setup',
            ],
        ];
    }
}
