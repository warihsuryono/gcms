<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Models\ItemRequest;
use Romans\Filter\IntToRoman;
use Illuminate\Support\Facades\Auth;
use App\Traits\FilamentCreateFunctions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ItemRequestResource;

class CreateItemRequest extends CreateRecord
{
    use FilamentCreateFunctions;
    protected static string $resource = ItemRequestResource::class;
    protected static bool $canCreateAnother = false;
    protected $IntToRoman;

    public function __construct()
    {
        $this->IntToRoman = new IntToRoman();
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.' . env('PANEL_PATH') . '.resources.item-requests.edit', $this->record->id);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['item_request_no'] = "";
        $item_request_no = "IR/" . $this->IntToRoman->filter(date("m")) . "/" . date("Y") . "/";
        $last_doc = ItemRequest::whereLike('item_request_no', $item_request_no . "%")->orderBy('item_request_no', 'desc')->first();
        if (!$last_doc) $data['item_request_no'] = $item_request_no . "001";
        else $data['item_request_no'] = $item_request_no . str_pad((str_replace($item_request_no, "", $last_doc->item_request_no) * 1) + 1, 3, '0', STR_PAD_LEFT);

        return $data;
    }
}
