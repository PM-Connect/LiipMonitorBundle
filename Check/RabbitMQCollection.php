<?php

namespace Liip\MonitorBundle\Check;

use ZendDiagnostics\Check\CheckCollectionInterface;
use ZendDiagnostics\Check\RabbitMQ;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class RabbitMQCollection implements CheckCollectionInterface
{
    private $checks = array();

    public function __construct(array $configs)
    {
        foreach ($configs as $name => $config) {
            $config = array_merge($config, parse_url($config['dsn']));

            if (isset($config['pass'])) {
                $config['password'] = $config['pass'];
            }

            if (isset($config['path'])) {
                $config['vhost'] = $config['path'];
            }

            $check = new RabbitMQ($config['host'], $config['port'], $config['user'], $config['password'], $config['vhost']);
            $check->setLabel(sprintf('Rabbit MQ "%s"', $name));

            $this->checks[sprintf('rabbit_mq_%s', $name)] = $check;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getChecks()
    {
        return $this->checks;
    }
}
