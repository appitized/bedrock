<?php

namespace Appitized\Bedrock\Models;

use Appitized\Bedrock\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use GeneratesUuid;

    public $incrementing = false;

    public function hydrateData($data)
    {
        $class = get_called_class();
        $collection = new Collection();
        foreach ($data as $item) {
            $model = new $class;
            if (isset($item->entity)) {
                $item = $this->hydrateSubEntity($item, $model);
            }
            $collection->add($model->newFromBuilder($item));
        }

        return $collection;

    }

    protected function hydrateSubEntity($item, $model)
    {
        $item->id = $item->{$model->entityField};

        return $item;
    }
}
