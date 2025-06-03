<?php

namespace App\Filament\Resources\PrivilegeResource\Pages;

use Exception;
use App\Models\menu;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PrivilegeResource;
use App\Traits\FilamentEditFunctions;

class EditPrivilege extends EditRecord
{
    protected $routename = 'privileges';
    use FilamentEditFunctions;
    protected static string $resource = PrivilegeResource::class;
    protected static string $view = 'privileges.form';
    public $mainmenu = [];
    public $childmenu = [];
    public $childmenu_add = [];
    public $childmenu_edit = [];
    public $childmenu_view = [];
    public $childmenu_delete = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $menu_ids = explode(",", $data["menu_ids"]);
        $privileges = explode(",", $data["privileges"]);
        foreach ($menu_ids as $key => $menu_id) {
            if ((int)$privileges[$key] > 0) $this->mainmenu[$menu_id] = true;
            if ((int)$privileges[$key] == 15) $this->childmenu[$menu_id] = true;
            if ((int)$privileges[$key] & 1) $this->childmenu_add[$menu_id] = true;
            if ((int)$privileges[$key] & 2) $this->childmenu_edit[$menu_id] = true;
            if ((int)$privileges[$key] & 4) $this->childmenu_view[$menu_id] = true;
            if ((int)$privileges[$key] & 8) $this->childmenu_delete[$menu_id] = true;
        }

        return $data;
    }

    protected function getViewData(): array
    {

        return [
            'menus' => menu::where('parent_id', '=', 0)->get()
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $menu_ids = "";
        $privileges = "";
        foreach (menu::where('parent_id', '=', '0')->get() as $mainmenu) {
            if (@$this->mainmenu[$mainmenu->id]) {
                $privileges .= "15,";
                $menu_ids .= $mainmenu->id . ",";
            }
            if (count($mainmenu->childmenu) > 0) {
                foreach ($mainmenu->childmenu as $childmenu) {
                    $privilege = 0;
                    if (@$this->childmenu_add[$childmenu->id]) $privilege += 1;
                    if (@$this->childmenu_edit[$childmenu->id]) $privilege += 2;
                    if (@$this->childmenu_view[$childmenu->id]) $privilege += 4;
                    if (@$this->childmenu_delete[$childmenu->id]) $privilege += 8;
                    if ($privilege > 0) {
                        $menu_ids .= $childmenu->id . ",";
                        $privileges .= $privilege . ",";
                    }
                }
            }
        }

        $data["menu_ids"] = substr($menu_ids, 0, -1);
        $data["privileges"] = substr($privileges, 0, -1);
        $record->update($data);
        return $record;
    }

    public function checkbox_toggle($menu_id)
    {
        $menu = menu::find($menu_id);
        if (count($menu->childmenu) > 0) {
            foreach ($menu->childmenu as $childmenu) {
                $this->childmenu[$childmenu->id] = $this->mainmenu[$menu_id];
                $this->childmenu_add[$childmenu->id] = $this->mainmenu[$menu_id];
                $this->childmenu_edit[$childmenu->id] = $this->mainmenu[$menu_id];
                $this->childmenu_view[$childmenu->id] = $this->mainmenu[$menu_id];
                $this->childmenu_delete[$childmenu->id] = $this->mainmenu[$menu_id];
            }
        } else {
            try {
                $this->childmenu_add[$menu_id] = $this->childmenu[$menu_id];
                $this->childmenu_edit[$menu_id] = $this->childmenu[$menu_id];
                $this->childmenu_view[$menu_id] = $this->childmenu[$menu_id];
                $this->childmenu_delete[$menu_id] = $this->childmenu[$menu_id];
            } catch (Exception $e) {
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
