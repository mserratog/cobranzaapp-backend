<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDeuda_SP extends Model
{
    use HasFactory;
    protected $table = 'pagodeuda_sp';
    protected $primaryKey = 'idPagoDeuda_SP';

}
