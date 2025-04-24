<?php

namespace App\Models;

use App\Traits\HasFooter;
use App\Traits\HasFilters;
use App\Traits\HasSorting;
use App\Traits\HasPagination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
    use HasFactory, HasFilters, HasSorting, HasFooter, HasPagination, SoftDeletes;

    const STATUS_AWAITING = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLTED = 3;

    protected $fillable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'end',
        'commission',
        'costs',
        'distribution',
        'visualization', 
        'created_by_user_id',
        'client_id',
        'status_id',
        'type_id'
    ];

    //protected $appends = ['editable'];

    protected $filterable = [
        'search' => 'string',
        'dates' => [
            ['start_start' => 'date', 'start_end' => 'date'],
            ['deadline_start' => 'date', 'deadline_end' => 'date'],
            ['end_start' => 'date', 'end_end' => 'date']
        ],
        'numbers' => [
            ['price_start' => 'numeric', 'price_end' => 'numeric'],
            ['visualization_start' => 'numeric', 'visualization_end' => 'numeric']
        ],
        'dictionary' => [
            ['type_id' => 'string'],
            ['status_id' => 'string'],
            ['created_by_user_id' => 'string'],
        ],
        'others' => [
            ['deleted' => 'boolean'],
            ['created_by_user' => 'boolean'],
            ['after_deadline' => 'boolean'],
        ],
        'pagination' => 'string',
    ];

    protected $sortable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'end',
        'visualization', 
    ];

    protected $searchable = [
        'title',
        'remarks',
        'price',
        'start',
        'deadline',
        'end',
        'visualization', 
    ];

    protected $footer = [
        'price' => 'sum',
        'visualization' => 'sum',
    ];

    protected $casts = [
        'distribution' => 'array'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function client(): BelongsTo{
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function status(): BelongsTo{
        return $this->belongsTo(ProjectStatus::class, 'status_id');
    }

    public function type(): BelongsTo{
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function images(): HasMany{
        return $this->hasMany(ProjectImage::class, 'project_id');
    }

    public function income()
    {
        return $this->hasOne(Income::class, 'project_id', 'id');
    }

    public function previewImage()
    {
        return $this->hasOne(ProjectImage::class)->inRandomOrder();
    }

    public function getEndAttribute($value)
    {
        return $value === null ? '' : $value;
    }

    public function getDeletableAttribute()
    {
        $income = $this->income;

        if ($this->deleted_at != null) {
            return false;
        }

        if ($income && $income->status_id == Income::STATUS_SETTLED) {
            return false;
        }

        return true;
    }
    
    public function getRestorableAttribute()
    {
        $income = $this->income;

        if ($this->deleted_at == null) {
            return false;
        }

        if ($income && $income->status_id == Income::STATUS_SETTLED) {
            return false;
        }

        return true;
    }

    public function getEditableAttribute()
    {   
        $income = $this->income;

        if ($this->deleted_at != null) {
            return false;
        }

        if ($income && $income->status_id == Income::STATUS_SETTLED) {
            return false;
        }

        return true;
    }

    public function scopeUseModelFilters($query, $filters) {
        $query->when(
            $filters['after_deadline'] ?? false,
            function ($query, $value){
                $query->whereNull($this->table_name . '.end')
                      ->where($this->table_name . '.deadline', '<', now());
            }
        );
    }

    public function addImages($images, $type_id)
    {
        foreach($images as $image){
            $disk = Storage::disk('private');

            if (!$disk->exists('tmp/'. $image)) {
                continue;
            }

            $disk->move(
                'tmp/'. $image, 
                'projects/'. $image
            );

            $disk->delete('tmp/'. $image);

            ProjectImage::create([
                'type_id' => $type_id,
                'project_id' => $this->id,
                'file' => $image
            ]);
        }
    }

    public function removeImages($images) {
        $disk = Storage::disk('private');

        foreach($images as $image) {
            $disk->delete('projects/' . $image);

            $this->images()
                ->where('file', $image)
                ->delete();
        }
    }

    public function replaceImages($images, $type_id) {
        $this->addImages($images, $type_id);
        $current_images = $this->images()->where('type_id', $type_id)->pluck('file')->toArray();
        $images_to_delete = array_diff($current_images, $images);
        $this->removeImages($images_to_delete);
    }

    public function getRelatedIncome($withThrashed = false)
    {
        if ($withThrashed) {
            return Income::withTrashed()->where('project_id', $this->id)->first();
        }
        
        return $this->income;
    }

    public function createRelatedIncome() {
        $income = $this->getRelatedIncome(true);

        if ($income != null) {
            throw new \Exception('Cannot create income: a related income already exists.');
        }

        if ($this->status_id != 1) {
            $income = new Income();
            $income->title = $this->title;
            $income->price = $this->price;
            $income->project_id = $this->id;
            $income->costs = $this->costs;
            $income->commission = $this->commission;
            $income->distribution = $this->distribution;
            $income->status_id = 1;
            $income->save();

            return $income;
        }

        throw new \Exception('Cannot create income: the project status is not valid.');
    }

    public function editRelatedIncome() {
        $income = $this->getRelatedIncome();

        if ($income) {
            $income->price = $this->price;
            $income->title = $this->title;
            $income->costs = $this->costs;
            $income->commission = $this->commission;
            $income->distribution = $this->distribution;
            $income->save();

            return $income;
        }

        throw new \Exception('Income does not belong to the given project or is not editable');
    }

    public function deleteRelatedIncome() {
        $income = $this->getRelatedIncome();

        if ($income) {
            $income->delete();

            return $income;
        }

        throw new \Exception('Income does not belong to the given project or is not deletable');
    }

    public function restoreRelatedIncome() {
        $income = $this->getRelatedIncome(true);

        if ($income && $income->trashed()) {
            $income->restore();

            return $income;
        }
    
        throw new \Exception('Income does not belong to the given project or is not trashed or is not restorable');
    }
}
