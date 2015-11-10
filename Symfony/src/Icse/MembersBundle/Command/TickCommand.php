<?php
namespace Icse\MembersBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Icse\MembersBundle\Service\NoMembershipProductException;


class TickCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('icse:tick')
            ->setDescription('Perform one periodic update cycle')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $members_updater = $container->get('icse.members_auto_updater');
        $product_updater = $container->get('icse.membership_product_auto_updater');

        try
        {
            $members_updater->start();
            $output->writeln("Members updated: <fg=green>OK</> (". $members_updater->stats_string() .")");

            $product_updater->start();
            $output->writeln("Membership product updated: <fg=green>OK</> (". $product_updater->stats_string() .")");
        }
        catch (NoMembershipProductException $e)
        {
            $output->writeln("<error>No membership product was found</>");
        }
    }
}
