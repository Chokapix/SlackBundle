<?php

namespace DZunke\SlackBundle\Slack\Client\Actions;

use DZunke\SlackBundle\Slack\Client\Actions;
use DZunke\SlackBundle\Slack\Client\Identity;

class ChatUpdate implements ActionsInterface
{

    /**
     * @var array
     */
    protected $parameter = [
        'timestamp'    => null,
        'channel'      => null,
        'text'         => null
    ];

    /**
     * @return array
     * @throws \Exception
     */
    public function getRenderedRequestParams()
    {
        $this->parameter['ts'] = $this->parameter['timestamp'];
        unset($this->parameter['timestamp']);

        return $this->parameter;
    }

    /**
     * @param Identity $identity
     * @return $this
     */
    public function setIdentity(Identity $identity)
    {
        return $this;
    }

    /**
     * @param array $parameter
     * @return $this
     */
    public function setParameter(array $parameter)
    {
        foreach ($parameter as $key => $value) {
            if (array_key_exists($key, $this->parameter)) {
                $this->parameter[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return Actions::ACTION_CHAT_UPDATE;
    }

    /**
     * @param array $response
     * @return array
     */
    public function parseResponse(array $response)
    {
        return [
            'timestamp' => $response['ts']
        ];
    }
}
