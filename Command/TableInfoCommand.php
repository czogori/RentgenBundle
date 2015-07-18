<?php

namespace Czogori\RentgenBundle\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TableInfoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('rentgen:table')
            ->setDescription('Table information.')
            ->setDefinition(array(
                new InputArgument('table_name', InputArgument::REQUIRED, 'Table name'),
            ));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableName = $input->getArgument('table_name');
        $rows = array();
        try {
            $table = $this->getContainer()
                ->get('rentgen.get_table')
                ->setTableName($tableName)
                ->execute();
            foreach ($table->getColumns() as $column) {
                $rows[] = array($column->getName(), $column->getType(), $column->isNotNull() ? 'Yes' : 'No', $column->getDefault());
            }
        } catch (\Rentgen\Exception\TableNotExistsException $exception) {
            $output->writeln(sprintf("\n<error>Table %s does not exist.</error>\n" , $exception->getTableName()));
            return;
        }
        $table = $this->getHelperSet()->get('table');
        $table
            ->setHeaders(array('Name', 'Type', 'Not null', 'Default'))
            ->setRows($rows)
            ->render($output);

    }
}
