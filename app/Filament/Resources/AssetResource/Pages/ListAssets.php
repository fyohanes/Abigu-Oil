<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\assetAssignmentRecord;
use App\Models\AssetRecord;
use App\Models\AssetWarehouseRecord;
use App\Models\AssignedUser;
use App\Models\ItemRequest;
use App\Models\ShopAssignedAsset;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use function PHPUnit\Framework\isEmpty;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getActions(): array
    {
        $user =Auth()->user();
        if($user->role_id==1){
        return [
            Actions\CreateAction::make(),
        ];
    }
    return [];
    }
    public  function assignWareHouse($data,$record){
        $assetId = AssetWarehouseRecord::where('asset_id',$record->id)->where('warehouse_id',$data['warehouse_id'])->pluck('id');
        if($assetId->isEmpty()){
       AssetWarehouseRecord::create([
            'asset_id'=>$record->id,
            'warehouse_id'=>$data['warehouse_id'],
            'quantity'=> $data['quantity'],
        ]);
    }else{
        $prevQuantity = AssetWarehouseRecord::where('asset_id',$record->id)->where('warehouse_id',$data['warehouse_id'])->value('quantity');
        AssetWarehouseRecord::where('asset_id',$record->id)->where('warehouse_id',$data['warehouse_id'])
        ->update([
            'quantity'=> $prevQuantity + $data['quantity']
        ]);
    }
        Notification::make()
        ->title('Assigned successfully')
        ->success()
        ->send();
    }

    public  function itemRequest($data,$record){
            $user = Auth()->user();
            $shop_id = AssignedUser::where('seller_id',$user->id)->value('shop_id');
            ItemRequest::create([
            'requested_by'=>$user->id,
            'asset_id'=>$record->id,
            'shop_id'=>$shop_id,
            'quantity'=> $data['quantity'],
        ]);


        Notification::make()
        ->title('Requested successfully')
        ->success()
        ->send();
    }



    // public function BulkAssignAsset($data,$records){
    //     foreach($records as $record){

    //         ShopAssignedAsset::where('id',$record->id)->update(
    //             [
    //             'status'=>2,
    //             'sub_region_id'=>$data['sub_region_id']
    //             ]
    //         );

    //     }


    //     Notification::make()
    //         ->title('Assigned successfully')
    //         ->success()
    //         ->send();
    // }
}