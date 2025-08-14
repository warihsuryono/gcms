<?php

namespace App\Filament\Resources\UrgentWorkOrderResource\Pages;

use App\Models\Field;
use App\Models\Division;
use Filament\Forms\Form;
use Livewire\WithFileUploads;
use App\Models\UrgentWorkOrder;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\UrgentWorkOrderResource;

class NewUrgentWorkOrder extends Page implements HasForms
{
    use InteractsWithForms, WithFileUploads;
    protected static string $resource = UrgentWorkOrderResource::class;
    protected static string $view = 'filament.resources.urgent-work-order-resource.pages.new-urgent-work-order';

    public ?string $work_at = '';
    public ?int $division_id;
    public ?int $field_id;
    public ?string $lat = '';
    public ?string $lon = '';
    public ?string $works = '';
    public ?int $work_order_status_id;
    public $photo1;
    public $photo2;
    public $photo3;
    public $photo4;
    public $photo5;
    public ?array $previewphoto = [
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
    ];
    public ?array $showphoto = [
        '1' => false,
        '2' => false,
        '3' => false,
        '4' => false,
        '5' => false,
    ];
    public ?array $photoName = [
        '1' => '',
        '2' => '',
        '3' => '',
        '4' => '',
        '5' => '',
    ];

    public function mount(): void
    {
        $this->form->fill([
            'work_at' => now(),
            'works' => '',
            'work_order_status_id' => 1,
            'lat' => '0',
            'lon' => '0',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('lat'),
                Hidden::make('lon'),
                Hidden::make('work_order_status_id'),
                DateTimePicker::make('work_at')->required()->label('Work At'),
                Select::make('division_id')->label('Division')->options(Division::all()->pluck('name', 'id'))->searchable()->preload(),
                Select::make('field_id')->label('Fields')->options(Field::all()->pluck('name', 'id'))->searchable()->preload(),
                RichEditor::make('works')->columnSpanFull()->required()->toolbarButtons(['undo', 'redo'])->helperText('Describe the work to be done.'),
            ]);
    }

    public function submit(): void
    {
        UrgentWorkOrder::create([
            'code' => 'WO-' . str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) . '-' . date('YmdHis'),
            'work_at' => $this->form->getState()['work_at'],
            'division_id' => $this->form->getState()['division_id'],
            'field_id' => $this->form->getState()['field_id'],
            'lat' => $this->form->getState()['lat'],
            'lon' => $this->form->getState()['lon'],
            'works' => $this->form->getState()['works'],
            'work_order_status_id' => $this->form->getState()['work_order_status_id'],
            'photo_1' => $this->photoName[1],
            'photo_2' => $this->photoName[2],
            'photo_3' => $this->photoName[3],
            'photo_4' => $this->photoName[4],
            'photo_5' => $this->photoName[5],
        ]);

        Notification::make()->title('Urgent Work Order created successfully!')->success()->send();
        redirect(route('filament.' . env('PANEL_PATH') . '.resources.urgent-work-orders.index'));
    }

    public function callNotification($notification): void
    {
        Notification::make()->title($notification)->success()->send();
    }

    public function updatedPhoto1($value)
    {
        $this->updatingPhoto(1);
    }

    public function updatedPhoto2($value)
    {
        $this->updatingPhoto(2);
    }

    public function updatedPhoto3($value)
    {
        $this->updatingPhoto(3);
    }

    public function updatedPhoto4($value)
    {
        $this->updatingPhoto(4);
    }

    public function updatedPhoto5($value)
    {
        $this->updatingPhoto(5);
    }

    public function updatingPhoto($photo_id)
    {
        $this->photoName[$photo_id] = 'photos/' . Auth::user()->id . time() . date("YmdHis") . '.' . $this->{'photo' . $photo_id}->extension();
        $this->{'photo' . $photo_id}->storeAs('public', $this->photoName[$photo_id]);
        $this->showphoto[$photo_id] = true;
        $this->previewphoto[$photo_id] = '/storage/' . $this->photoName[$photo_id];
    }
}
