<?php

namespace DZunke\SlackBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MessageCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dzunke:slack:message')
            ->setDescription('Sending a Message to a Channel or User')
            ->addArgument('channel', InputArgument::REQUIRED, 'an existing channel in your team to send to')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'an username from configured identities to send with')
            ->addOption('timestamp', 't', InputOption::VALUE_REQUIRED, 'the timestamp for a message you want to edit')
            ->addOption('discover', 'd', InputOption::VALUE_NONE, 'channel name is given, so discover the id')
            ->addArgument('message', InputArgument::REQUIRED, 'the message to send');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $channels = $this->getContainer()->get('dz.slack.channels');
        if ($input->getOption('discover')) {
            $channelId = $channels->getId($input->getArgument('channel'));
        } else {
            $channelId = $input->getArgument('channel');
        }

        try {

            $this->validateInput($input);
            $messenger = $this->getContainer()->get('dz.slack.messaging');

            if (!empty($input->getOption('username'))) {

                var_dump('SEND MESSAGE');

                $response  = $messenger->message(
                    $channelId,
                    $input->getArgument('message'),
                    $input->getOption('username')
                );
            }

            if (!empty($input->getOption('timestamp'))) {
                var_dump('UPDATE MESSAGE');

                $response  = $messenger->update(
                    $channelId,
                    $input->getArgument('message'),
                    $input->getOption('timestamp')
                );
            }

            if ($response->getStatus() === false) {
                $output->writeln('<error>✘ error from slack: "' . $response->getError() . '"</error>');
                return;
            }

            $output->writeln('<info>✔ message was sent: ' . $response->getData()['timestamp'] . '</info>');
        } catch (\Exception $e) {
            $output->writeln('<error>✘ error: "' . $e->getMessage() . '"</error>');
        }
    }

    protected function validateInput(InputInterface $input)
    {
        if (empty($input->getOption('username')) && empty($input->getOption('timestamp'))) {
            throw new \Exception('Timestamp or Username must be Set. Timestamp for updating a Message and Username for writing a message');
        }

        if (!empty($input->getOption('username')) && !empty($input->getOption('timestamp'))) {
            throw new \Exception('Only one of the Options Username and Timestamp must be set.');
        }
    }
}
