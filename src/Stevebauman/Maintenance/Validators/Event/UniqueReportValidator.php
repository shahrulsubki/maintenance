<?php

namespace Stevebauman\Maintenance\Validators\Event;

use Stevebauman\Maintenance\Services\Event\ReportService;

class UniqueReportValidator {
    
    public function __construct(ReportService $report)
    {
        $this->report = $report;
    }
    
    public function validateUniqueReport($attribute, $value, $parameters)
    {
        if(count($parameters) > 0) {
            
            if($this->report->where('event_id', $parameters[0])->first()) {
                
                /*
                 * Report was found
                 */
                return false;
            } else {
                /*
                 * No report found, must be unique
                 */
                return true;
            }
            
        }
        
        return false;
    }
}