<?php
  
namespace App\Enums;
 
enum LoanStatusEnum:string {
    case Pending = 'pending';
    case Inactive = 'approved';
    case Rejected = 'rejected';
    case Paid = 'paid';
}