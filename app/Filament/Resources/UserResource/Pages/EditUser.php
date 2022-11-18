<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\AssignedUser;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $user = $record->update([
            'name'=>$data['name'],
            'email'=> $data['email'],
            'phone'=>$data['phone'],
            'role_id'=>$data['role_id'] ,
            ]);

         if($data['role_id'] == 2){
             AssignedUser::where('seller_id',$record->id)->update([
                'shop_id'=>$data['shop_id']
             ]);
         }
         return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if($data['role_id'] == 2){
           $userId =  AssignedUser::where('seller_id',$data['id'])->first();
           $data['shop_id'] = $userId->shop_id;
        }
        return $data;
    }
}
