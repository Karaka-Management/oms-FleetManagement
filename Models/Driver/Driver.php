<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\FleetManagement\Models\Driver
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models\Driver;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;

/**
 * Driver class.
 *
 * @package Modules\FleetManagement\Models\Driver
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Driver
{
    /**
     * ID value.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Status value.
     *
     * @var int
     * @since 1.0.0
     */
    public int $status = DriverStatus::ACTIVE;

    /**
     * Account associated with the client.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $account;

    public array $licenses = [];

    public array $inspections = [];

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->account = new NullAccount();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'     => $this->id,
            'status' => $this->status,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }

    use \Modules\Media\Models\MediaListTrait;
    use \Modules\Editor\Models\EditorDocListTrait;
    use \Modules\Attribute\Models\AttributeHolderTrait;
}
