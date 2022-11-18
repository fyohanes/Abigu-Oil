<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Filament\Resources\ItemRequestResource;
use App\Models\assetAssignmentRecord;
use App\Models\AssetWarehouseRecord;
use App\Models\ItemRequest;
use App\Models\ShopAssignedAsset;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListItemRequests extends ListRecords
{
    protected static string $resource = ItemRequestResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public  function approve($data,$record){
        $user= Auth()->user();


        $assetId = assetAssignmentRecord::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)->pluck('id');

        $warehouseQuantity= AssetWarehouseRecord::where('asset_id',$record->asset_id)->value('quantity');
        $totalQuantity = ShopAssignedAsset::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)->value('quantity');
       if($warehouseQuantity >= $record->quantity){
        ItemRequest::where('asset_id',$record->asset_id)
        ->where('id',$record->id)
        ->update([
            'status'=>2,
            'approved_by'=>$user->id
        ]);
        AssetWarehouseRecord::where('asset_id',$record->asset_id)
        ->update([
            'quantity'=> $warehouseQuantity-$record->quantity
        ]);
        if(  $assetId->isEmpty()){
            assetAssignmentRecord::create([
            'asset_id'=>$record->asset_id,
            'shop_id'=>$record->shop_id,
            'assigned_by'=> $user->id
        ]);


    }
        ShopAssignedAsset::create([
            'asset_id'=>$record->asset_id,
            'shop_id'=>$record->shop_id,
            'ware_house_id'=>$data['warehouse'],
            'quantity'=>  $totalQuantity,
            'assigned_quantity'=>$record->quantity
        ]);


        Notification::make()
        ->title('Aproved successfully')
        ->success()
        ->send();
    }else{
        Notification::make()
        ->title('Max Quantity Reached')
        ->danger()
        ->send();
    }

    }


    public  function reject($data,$record){
        $user= Auth()->user();
        ItemRequest::where('id',$record->id)
        ->where('shop_id',$record->shop_id)
        ->update([
            'status'=>3,
            'approved_by'=>$user->id
        ]);
        Notification::make()
        ->title('Rejected successfully')
        ->danger()
        ->send();
    }
}