<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\AssignedUser;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {



            $user= User::create([
            'name'=>$data['name'],
            'email'=> $data['email'],
            'phone'=>$data['phone'],
            'role_id'=>$data['role_id'] ,
            'password'=>$data['password']

        ]);

        if($user->role_id== 2){
            //seller

            AssignedUser::create([
                'seller_id'=>$user->id,
                'shop_id'=>$data['shop_id']
            ]);


        }

         return $user;


    }
}