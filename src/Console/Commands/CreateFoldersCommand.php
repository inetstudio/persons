<?php

namespace InetStudio\Experts\Console\Commands;

use Illuminate\Console\Command;

class CreateFoldersCommand extends Command
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:experts:folders';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Create package folders';

    /**
     * Запуск команды.
     *
     * @return void
     */
    public function handle(): void
    {
        if (config('filesystems.disks.experts')) {
            $path = config('filesystems.disks.experts.root');

            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
    }
}
