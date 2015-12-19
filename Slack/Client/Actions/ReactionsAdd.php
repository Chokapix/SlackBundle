<?php

namespace DZunke\SlackBundle\Slack\Client\Actions;

use DZunke\SlackBundle\Slack\Client\Actions;
use DZunke\SlackBundle\Slack\Messaging\Identity;

/**
 * @see https://api.slack.com/methods/reactions.add
 */
class ReactionsAdd implements ActionsInterface
{
    /**
     * @var array
     */
    protected $parameter = [
        'identity'     => null,
        'timestamp'    => null,
        'name'         => null,
        'file'         => null,
        'file_comment' => null,
        'channel'      => null,
    ];

    /**
     * @return array
     * @throws \Exception
     */
    public function getRenderedRequestParams()
    {
        if (is_null($this->parameter['identity']) || !$this->parameter['identity'] instanceof Identity) {
            throw new \Exception('no identity given');
        }

        $this->parseIdentity();

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

    private function parseIdentity()
    {
        $this->parameter['username'] = $this->parameter['identity']->getUsername();
        if (empty($this->parameter['icon_url']) && $iconUrl = $this->parameter['identity']->getIconUrl()) {
            $this->parameter['icon_url'] = $iconUrl;
        }
        if (empty($this->parameter['icon_emoji']) && $iconEmoji = $this->parameter['identity']->getIconEmoji()) {
            $this->parameter['icon_emoji'] = $iconEmoji;
        }
        unset($this->parameter['identity']);
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
            'ok' => $response['ok']
        ];
    }
}
