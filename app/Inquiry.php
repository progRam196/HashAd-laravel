<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;


class Inquiry extends Model
{ 
    //
    use Notifiable;

    protected $fillable = [
        'inquirer_name', 'inquirer_email', 'inquirer_message'
     ];

}
