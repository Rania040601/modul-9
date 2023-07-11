<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// untuk mendefinisikan relasi antara position dan employee
class Position extends Model
{
    use HasFactory;
// sebuah metode pada kelas Employee yang mendefinisikan relasi "belongsTo" dengan model position. Metode ini menggunakan metode belongsTo untuk mendefinisikan hubungan antara model Employee dan Position.
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
