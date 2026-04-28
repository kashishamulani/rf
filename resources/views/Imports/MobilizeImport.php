<?php

namespace App\Imports;

use App\Models\Mobilization;
use Maatwebsite\Excel\Concerns\ToModel;

class MobilizeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Mobilization([
             'name' => $row[0],
              'email' =>  $row[1],
               'mobile'=>  $row[2],
        ]);
    }
}
