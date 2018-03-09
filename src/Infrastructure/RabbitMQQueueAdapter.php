<?php namespace StraTDeS\SharedKernel\Infrastructure;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQQueueAdapter implements QueueAdapterInterface
{
    /** @var RabbitMQConfigProviderInterface */
    private $rabbitMQConfigProvider;

    /** @var AMQPStreamConnection */
    private $connection;

    /** @var AMQPChannel */
    private $channel;

    public function __construct(RabbitMQConfigProviderInterface $rabbitMQConfigProvider)
    {
        $this->rabbitMQConfigProvider = $rabbitMQConfigProvider;
    }

    public function get(string $queueName): QueueMessage
    {
        $channel = $this->getChannel();

        $this->declareQueue($queueName);

        /** @var AMQPMessage $message */
        $message = $channel->basic_get($queueName, false, null);

        return new QueueMessage(
            $message->delivery_info['delivery_tag'],
            $message->getBody()
        );
    }

    public function consume(string $queueName, callable $consumer, bool $ack = true): void
    {
        $channel = $this->getChannel();

        $this->declareQueue($queueName);

        // Set the consumer as ready to consume
        $channel->basic_consume($queueName, '', false, !$ack, false, false, $consumer);

        // Wait for callbacks of the channel (new messages)
        while (count($channel->callbacks)) {
            try {
                $channel->wait();
            } catch (AMQPTimeoutException $e) {
                die('timeout');
            } catch (AMQPRuntimeException $e) {
                die('runtime');
            } catch (\Exception $e) {
                die('other exception');
            }
        }

        // Close channel and connection
        $this->close();
    }

    public function send(string $message, string $queueName): void
    {
        $channel = $this->getChannel();

        $this->declareQueue($queueName);

        $msg = new AMQPMessage(
            $message,
            array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)
        );

        try {
            $channel->basic_publish($msg, '', $queueName);
        } catch (AMQPTimeoutException $e) {
            die('timeout');
        } catch (AMQPRuntimeException $e) {
            die('runtime exception');
        } catch (\Exception $e) {
            die('other exception');
        }
    }

    public function ack(QueueMessage $queueMessage): void
    {
        $this->getChannel()->basic_ack($queueMessage->getId());
    }

    private function connect()
    {
        if (!$this->isConnected()) {
            $this->connection = new AMQPStreamConnection(
                $this->rabbitMQConfigProvider->getHost(),
                $this->rabbitMQConfigProvider->getPort(),
                $this->rabbitMQConfigProvider->getUser(),
                $this->rabbitMQConfigProvider->getPassword()
            );

            $this->channel = $this->connection->channel();
        }
    }

    private function isConnected(): bool
    {
        return ($this->connection && $this->connection->isConnected());
    }

    public function getChannel()
    {
        $this->connect();

        return $this->channel;
    }

    private function declareQueue(string $queueName): void
    {
        $this->getChannel()->queue_declare($queueName, false, true, false, false);
    }

    public function close(): void
    {
        $this->getChannel()->close();
        $this->getChannel()->getConnection()->close();
    }
}