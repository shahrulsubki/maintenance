<?php namespace Stevebauman\Maintenance\Validators;

use Stevebauman\Maintenance\Services\WorkOrderService;
use Illuminate\Support\Facades\Route;

class WorkOrderReportUniqueValidator {
    
    public function __construct(WorkOrderService $workOrder) {
        $this->workOrder = $workOrder;
    }
    
     public function validateUniqueReport($attribute, $location_id, $parameters){
        $work_order_id = Route::getCurrentRoute()->getParameter('work_orders');
         
        if($workOrder = $this->workOrder->find($work_order_id)){
            
            if($workOrder->report){
                return false;
            } else{
                return true;
            }
            
        } return false;
        
     }
     
     protected function replaceUniqueReport($message, $attribute, $rule, $parameters){
        return 'This location already has a stock entry for this item.';
    }
    
}