<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use DB;
use Carbon\Carbon;

class TenantProrrogaContrato extends Model
{
    use SoftDeletes;
    protected $table = 'tenant_contrato_prorroga';
    protected $fillable = ['id_tenant','duracion','fecha_inicio_prorroga','fecha_fin_prorroga'];
}
