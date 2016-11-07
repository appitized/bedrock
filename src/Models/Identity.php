<?php

namespace Appitized\Bedrock\Models;

use Appitized\Bedrock\Traits\GeneratesUuid;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Identity extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes, GeneratesUuid;

    protected $table = 'users';
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
    // the field that stores the subclass
    protected $subclassField = 'entity';
    // must be overridden and set to true in subclasses
    protected $isSubclass = false;
    public $incrementing = false;

    public function isSubclass()
    {
        return $this->isSubclass;
    }

    public function mapData(array $attributes)
    {
        if (!$this->subclassField) {
            return $this->newInstance();
        }

        return new $attributes[$this->subclassField];
    }

    public function newFromBuilder($attributes = array(), $connection = null)
    {
        $instance = $this->mapData((array)$attributes)->newInstance(array(), TRUE);
        $instance->setRawAttributes((array)$attributes, TRUE);

        return $instance;
    }

    public function newQuery($excludeDeleted = true)
    {
        $builder = parent::newQuery($excludeDeleted);

        if ($this->subclassField && $this->isSubclass()) {
            $builder->where($this->subclassField, '=', get_class($this));
        }

        return $builder;
    }

    public function save(array $options = array())
    {
        if ($this->subclassField) {
            $this->attributes[$this->subclassField] = get_class($this);
        }

        return parent::save($options);
    }
}
