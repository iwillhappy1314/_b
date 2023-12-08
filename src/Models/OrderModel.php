<?php

namespace WenpriseSpaceName\Models;

use \Wenprise\Eloquent\Model;

/**
 * 序列号
 *
 * Class SerialNumber
 */
class OrderModel extends Model {

    /**
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $guarded = [ 'id' ];

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

}