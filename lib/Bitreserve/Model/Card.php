<?php

namespace Bitreserve\Model;

use Bitreserve\BitreserveClient;
use Bitreserve\Paginator\Paginator;

/**
 * Card Model.
 */
class Card extends BaseModel implements CardInterface
{
    /**
     * @var id
     */
    protected $id;

    /**
     * @var address
     */
    protected $address;

    /**
     * @var addresses
     */
    protected $addresses;

    /**
     * @var available
     */
    protected $available;

    /**
     * @var balance
     */
    protected $balance;

    /**
     * @var currency
     */
    protected $currency;

    /**
     * @var label
     */
    protected $label;

    /**
     * @var lastTransactionAt
     */
    protected $lastTransactionAt;

    /**
     * @var settings
     */
    protected $settings;

    /**
     * @var transactions
     */
    protected $transactions;

    /**
     * Constructor.
     *
     * @param BitreserveClient $client Bitreserve client
     * @param array $data User data.
     */
    public function __construct(BitreserveClient $client, $data)
    {
        $this->client = $client;

        $this->updateFields($data);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * {@inheritdoc}
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastTransactionAt()
    {
        return $this->lastTransactionAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactions()
    {
        $pager = new Paginator($this->client, sprintf('/me/cards/%s/transactions', $this->id));
        $pager->setModel('Bitreserve\Model\Transaction');

        return $pager;
    }

    /**
     * {@inheritdoc}
     */
    public function createTransaction($destination, $amount, $currency)
    {
        $postData = array(
            'destination' => $destination,
            'denomination' => array(
                'amount' => $amount,
                'currency' => $currency,
        ));

        $response = $this->client->post(sprintf('/me/cards/%s/transactions', $this->id), $postData);

        $transaction = new Transaction($this->client, $response->getContent());

        return $transaction;
    }

    /**
     * {@inheritdoc}
     */
    public function update(array $params)
    {
        $response = $this->client->patch(sprintf('/me/cards/%s', $this->id), $params);

        $this->updateFields($response->getContent());

        return $this;
    }
}
