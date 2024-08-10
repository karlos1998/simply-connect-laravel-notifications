<?php

namespace Karlos3098\SimplyConnectLaravelNotifications\Commands;

use Illuminate\Console\GeneratorCommand;

class SimplyConnectNotificationMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'simply:make:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class with a toSimplyConnect method';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Notification';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('simply-connect')) {
            return __DIR__ . '/../stubs/simplyConnectNotification.stub';
        }

        // Fallback to the default notification stub if --simply-connect is not used
        return $this->resolveStubPath('/stubs/notification.stub');
    }

    /**
     * Resolve the full path to the stub file.
     *
     * @param  string  $stubPath
     * @return string
     */
    protected function resolveStubPath($stubPath)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stubPath, '/')))
            ? $customPath
            : __DIR__ . $stubPath;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['simply-connect', 'S', null, 'Generate a notification class with toSimplyConnect method.']
        ];
    }
}
