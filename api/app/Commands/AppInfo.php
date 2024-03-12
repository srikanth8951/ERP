<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AppInfo extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'app:info';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Display application information';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'app:info';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('PHP Version ' . PHP_VERSION);
        CLI::write('CI Version ' . \CodeIgniter\CodeIgniter::CI_VERSION);
        CLI::write('ROOTPATH ' . ROOTPATH);
        CLI::write('Included Files ' . count(get_included_files()));
    }
}
