<?php

namespace App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// untuk mendefinisikan relasi antara employee dengan position
class Employee extends Model
{
    use HasFactory;

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

}
