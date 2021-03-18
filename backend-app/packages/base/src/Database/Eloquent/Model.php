<?php
namespace Larabase\Database\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Yajra\Auditable\AuditableTrait as Blameable;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, Blameable;
}