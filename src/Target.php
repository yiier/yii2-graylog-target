<?php

namespace yiier\graylog;

use Gelf\Transport\TransportInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;

class Target extends \yii\log\Target
{
    /**
     * @var string
     */
    public $facility = 'yii2';

    /**
     * @var array
     */
    public $transport = [];

    /**
     * @var array default additional fields
     */
    public $additionalFields = [];

    /**
     * @var boolean whether to add authenticated user username to additional fields
     */
    public $addUsername = false;

    /**
     * @var MessageBuilderInterface|array|string
     */
    public $messageBuilder = MessageBuilder::class;

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function export()
    {
        /** @var MessageBuilderInterface $messageBuilder */
        $messageBuilder = Instance::ensure($this->messageBuilder, MessageBuilderInterface::class);

        $publisher = Yii::createObject(\Gelf\Publisher::class);
        /** @var TransportInterface $transport */
        $transport = Instance::ensure($this->transport, TransportInterface::class);
        $publisher->addTransport($transport);

        foreach ($this->messages as $message) {
            $gelfMessage = $messageBuilder->build($this, $message);
            $publisher->publish($gelfMessage);
        }
    }
}
