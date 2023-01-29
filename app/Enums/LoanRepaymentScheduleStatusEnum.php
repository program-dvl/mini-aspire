<?php
  
namespace App\Enums;
 
enum LoanRepaymentScheduleStatusEnum:string {
    case pending = 'pending';
    case paid = 'paid';
}