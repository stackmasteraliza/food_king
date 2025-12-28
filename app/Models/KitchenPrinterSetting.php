<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitchenPrinterSetting extends Model
{
    use HasFactory;


    protected $table = "kitchen_printers";


    protected $fillable = [
        'branch_id',
        'name',
        'ip_address',
        'is_default',
        'port',
    ];

    protected $casts = [
        'id'                => 'integer',
        'name'              => 'string',
        'ip_address'            => 'string',
        'is_default' => 'integer',
        'branch_id' => 'integer',
        'port' => 'integer',
    ];

    /**
     * Get the branch that owns the printer.
     */
    // public function branch(): BelongsTo
    // {
    //     return $this->belongsTo(Branch::class);
    // }

    /**
     * Scope a query to only include default printers.
     */
    // public function scopeDefault($query)
    // {
    //     return $query->where('default', true);
    // }

    /**
     * Set the printer as default.
     * This will automatically remove default status from other printers in the branch.
     */
    // public function setAsDefault()
    // {
    //     // Remove default status from other printers in the same branch
    //     KitchenPrinter::where('branch_id', $this->branch_id)
    //         ->where('id', '!=', $this->id)
    //         ->update(['default' => false]);

    //     $this->update(['default' => true]);
    // }

    /**
     * Get the connection string for the printer.
     */
    // public function getConnectionString(): string
    // {
    //     return "tcp://{$this->ip_address}:{$this->port}";
    // }
}
