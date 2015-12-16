<?php

namespace DZunke\SlackBundle\Slack\Client\Actions;

use DZunke\SlackBundle\Slack\Client\Actions;

/**
 * @see https://api.slack.com/methods/reactions.add
 */
class ChatPostMessage implements ActionsInterface
{
    /**
     * @var array
     */
    protected $parameter = [
        'timestamp'        => null,
        'name'     => null,
        'file'         => null,
        'file_comment'     => null,
        'channel'   => null,
    ];

    /**
     * @return array
     * @throws \Exception
     */
    public function getRenderedRequestParams()
    {
        return $this->parameter;
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
        return Actions::ACTION_REACTIONS_ADD;
    }

    /**
     * @param array $response
     * @return array
     */
    public function parseResponse(array $response)
    {
        return [
            'timestamp' => $response['ok']
        ];
    }
}
