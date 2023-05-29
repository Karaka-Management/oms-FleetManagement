<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\FleetManagement\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\FleetManagement\Models;

/**
 * Inspection class.
 *
 * @package Modules\Attribute\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Inspection implements \JsonSerializable
{
    public int $id = 0;

    public string $description = '';

    public BaseStringL11nType $type;

    public int $status = InspectionStatus::TODO;

    // Automatically get's filled from the previous inspection if available
    // Alternatively define default interval from inspection type?
    public ?\DateTime $next = null;

    /**
     * Inspectio interval in months
     * 
     * @var int
     * @since 1.0.0
     */
    public int $interval = 0;


    public function __construct()
    {
        $this->type = new BaseStringL11nType();
    }

    use \Modules\Media\Models\MediaListTrait;
}
