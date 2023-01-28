<?php
  
namespace App\Enums;
 
enum LoanStatusEnum:string {
    case pending = 'pending';
    case approved = 'approved';
    case rejected = 'rejected';
    case paid = 'paid';
}