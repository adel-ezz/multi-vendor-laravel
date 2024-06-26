<?php

namespace Modules\Stores\Entities;

use App\Http\Filters\Filterable;

use Modules\Support\Traits\Selectable;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Modules\Accounts\Entities\StoreOwner;

use Modules\Stores\Entities\Helpers\StoreHelper;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
/**
 * Class Store.
 */
class Store extends Model implements HasMedia
{
    use Translatable,
        Filterable,
        Selectable,
        InteractsWithMedia,
        StoreHelper;

    /**
     * @var string
     */
    protected $table = 'stores';

    /**
     * The translated attributes that are mass assignable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
        'meta_description',
        'keywords',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'translations',
        'StoreOwner',
        'media',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'owner_id',
        'plan',
        'domain',
        'rate',
        'is_verified',
    ];

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    public function getForeignKey()
    {
        return 'store_id';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function StoreOwner()
    {
        return $this->belongsTo(StoreOwner::class, 'owner_id', 'id');
    }

    /**
     * @return float|int
     */
    public function calculateRate()
    {
        return $this->getAttribute('rate') / 10;
    }

    /**
     * Define the media collections.
     *
     * @return void
     */
//    public function registerMediaCollections(Media $media = null)
//    {
//        $this
//            ->addMediaCollection('stores')
//            ->useFallbackUrl('https://www.gravatar.com/avatar/' . md5($this->name) . '?d=mm')
//            ->singleFile();
//    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
