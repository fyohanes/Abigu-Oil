<?php

namespace App\Filament\Resources\ShopAssignedAssetResource\Pages;

use App\Filament\Resources\ShopAssignedAssetResource;
use App\Models\selledAssets;
use App\Models\ShopAssignedAsset;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShopAssignedAssets extends ListRecords
{
    protected static string $resource = ShopAssignedAssetResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public  function sell($data,$record){
        $sell_req = Carbon::parse($record->created_at);
        $sell_reqt_date = Carbon::create(
              $sell_req->year,
              $sell_req->month,
              $sell_req->day,
              0,0,0,
          );
        $user = Auth()->user();
        $assetId = selledAssets::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)->where('sa_created_at',$sell_reqt_date)->pluck('id');
        $quantity = ShopAssignedAsset::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)->value('quantity');
        $formQuantity = $data['quantity'];
        //  dump($quantity);
    if($formQuantity <= $quantity ){
        ShopAssignedAsset::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)
        ->update([
            'quantity'=> $quantity-$formQuantity
        ]);
        if($assetId->isEmpty()){
            selledAssets::create([
                'seller_id'=>$user->id,
                    'asset_id'=>$record->asset_id,
                    'shop_id'=>$record->shop_id,
                    'quantity'=> $data['quantity']
                ]);
            }
            else{
                $prevQuantity = selledAssets::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)->where('sa_created_at',$sell_reqt_date)->value('quantity');
                selledAssets::where('asset_id',$record->asset_id)->where('shop_id',$record->shop_id)->where('sa_created_at',$sell_reqt_date)
                ->update([
                    'quantity'=> $prevQuantity + $data['quantity']
                ]);
            }
        Notification::make()
        ->title('Sold successfully')
        ->success()
        ->send();
    }
    else {
        Notification::make()
        ->title('Sorry! Max Quantity Reached')
        ->danger()
        ->send();
    }

    }
}
