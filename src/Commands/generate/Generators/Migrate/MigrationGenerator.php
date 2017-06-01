<?php

namespace Drush\Commands\generate\Generators\Migrate;

use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Implements `generate migration` command.
 */
class MigrationGenerator extends BaseGenerator
{

    protected $name = 'migration';
    protected $description = 'Generates the yml and PHP class for a Migration';
    protected $templatePath = __DIR__;

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = Utils::defaultQuestions() + [
            'plugin_label' => ['Plugin label', 'Example'],
            'plugin_id' => [
                'Plugin ID',
                function ($vars) {
                    return Utils::human2machine($vars['plugin_label']);
                },
            ],
            'migration_group' => ['Migration group', 'default'],
            'destination_plugin' => ['Destination plugin', 'entity:node'],
        ];

        $vars = $this->collectVars($input, $output, $questions);
        $vars['class'] = Utils::camelize($vars['plugin_label']);

        $path = 'src/Plugin/migrate/source/' . $vars['class'] . '.php';
        $this->files[$path] = $this->render('migration.twig', $vars);
        $path = 'config/install/migrate_plus.migration.' . $vars['plugin_id'] . '.yml';
        $this->files[$path] = $this->render('migration.yml.twig', $vars);
    }
}