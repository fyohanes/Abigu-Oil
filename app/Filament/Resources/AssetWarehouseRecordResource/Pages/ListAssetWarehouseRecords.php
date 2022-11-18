<?php

namespace App\Filament\Resources\AssetWarehouseRecordResource\Pages;

use App\Filament\Resources\AssetWarehouseRecordResource;
use App\Models\assetAssignmentRecord;
use App\Models\AssetWarehouseRecord;
use App\Models\ShopAssignedAsset;
use App\Models\WareHouse;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetWarehouseRecords extends ListRecords
{
    protected static string $resource = AssetWarehouseRecordResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    public  function assignShop($data,$record){

        $assetId = assetAssignmentRecord::where('asset_id',$record->id)->where('shop_id',$data['shop_id'])->pluck('id');
       $user = Auth()->user();
       $assetId2 = ShopAssignedAsset::where('asset_id',$record->id)->where('shop_id',$data['shop_id'])->pluck('id');


       if($record->quantity >= $data['quantity']){
        AssetWarehouseRecord::where('id',$record->id)
        ->update([
            'quantity'=> $record->quantity-$data['quantity']
        ]);
        if(  $assetId->isEmpty()){
            assetAssignmentRecord::create([
            'asset_id'=>$record->id,
            'shop_id'=>$data['shop_id'],
            'assigned_by'=> $user->id
        ]);


    }
    if($assetId2->isEmpty()){
        ShopAssignedAsset::create([
            'asset_id'=>$record->id,
            'shop_id'=>$data['shop_id'],
            'ware_house_id'=>$record->warehouse_id,
            'quantity'=>  $data['quantity']
        ]);
    }
    else{


        $prevQuantity = ShopAssignedAsset::where('asset_id',$record->id)->where('shop_id',$data['shop_id'])->value('quantity');

        ShopAssignedAsset::where('asset_id',$record->id)
        ->where('shop_id',$data['shop_id'])
        ->update([
            'quantity'=> $prevQuantity + $data['quantity']
        ]);
    }

        Notification::make()
        ->title('Assigned successfully')
        ->success()
        ->send();
    }
        else{
            Notification::make()
            ->title('There is no Available Item In Store!')
            ->danger()
            ->send();
        }
    }

}