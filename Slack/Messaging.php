<?php

namespace DZunke\SlackBundle\Slack;

use DZunke\SlackBundle\Slack\Client\Actions;

class Messaging
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function  __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * if $returnTimestamp is set to true it will return the Timestamp of the Messgae.
     * The Request must be successfull if not it will return the complete Response
     *
     * @param string $channel
     * @param string $message
     * @param string $identity
     * @param bool   $returnTimestamp
     * @return Client\Response|string
     */
    public function message($channel, $message, $identity, $returnTimestamp = false)
    {
        $response = $this->client->send(
            Actions::ACTION_POST_MESSAGE,
            [
                'channel' => $channel,
                'text'    => $message
            ],
            $identity
        );

        if ($returnTimestamp && $response->getStatus()) {
            $response = $response->getData()['timestamp'];
        }

        return $response;
    }

    /**
     * if $returnTimestamp is set to true it will return the Timestamp of the Messgae.
     * The Request must be successfull if not it will return the complete Response
     *
     * @param string $channel
     * @param string $message
     * @param string $timestamp
     * @param bool   $returnTimestamp
     * @return Client\Response|string
     */
    public function update($channel, $message, $timestamp, $returnTimestamp = false)
    {
        $response = $this->client->send(
            Actions::ACTION_CHAT_UPDATE,
            [
                'channel'   => $channel,
                'text'      => $message,
                'timestamp' => $timestamp
            ]
        );

        if ($returnTimestamp && $response->getStatus()) {
            $response = $response->getData()['timestamp'];
        }

        return $response;
    }

}
