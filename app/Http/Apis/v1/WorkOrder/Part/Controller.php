<?php

namespace App\Http\Apis\v1\WorkOrder\Part;

use App\Http\Apis\v1\Controller as BaseController;
use App\Models\InventoryStock;
use App\Repositories\WorkOrder\Repository as WorkOrderRepository;

class Controller extends BaseController
{
    /**
     * @var WorkOrderRepository
     */
    protected $workOrder;

    /**
     * Constructor.
     *
     * @param WorkOrderRepository $workOrder
     */
    public function __construct(WorkOrderRepository $workOrder)
    {
        $this->workOrder = $workOrder;
    }

    /**
     * Returns a new grid instance of
     * parts added to the specified work order.
     *
     * @param int|string $workOrderId
     *
     * @return \Cartalyst\DataGrid\DataGrid
     */
    public function grid($workOrderId)
    {
        $columns = [
            'inventory_stocks.id',
            'inventory_id',
            'location_id',
            'work_order_parts.created_at',
        ];

        $settings = [
            'sort'      => 'created_at',
            'direction' => 'desc',
            'threshold' => 10,
            'throttle'  => 11,
        ];

        $transformer = function (InventoryStock $stock) use ($workOrderId) {
            return [
                'item_id'        => $stock->inventory_id,
                'item_sku'       => ($stock->item->sku_code ? $stock->item->sku_code : '<em>None</em>'),
                'item_name'      => $stock->item->name,
                'item_view_url'  => route('maintenance.inventory.show', [$stock->inventory_id]),
                'location'       => ($stock->location ? $stock->location->trail : '<em>None</em>'),
                'quantity_taken' => $stock->pivot->quantity,
                'date_taken'     => $stock->pivot->created_at->format('Y-m-d h:i a'),
                'put_back_url'   => route('maintenance.work-orders.parts.stocks.put', [$workOrderId, $stock->inventory_id, $stock->id]),
            ];
        };

        return $this->workOrder->gridParts($workOrderId, $columns, $settings, $transformer);
    }
}