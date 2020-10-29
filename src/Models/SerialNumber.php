<?php

namespace WenpriseSpaceName\Models;


use WenpriseSpaceNameVendor\Wenprise\ORM\Eloquent\Model;

/**
 * 序列号
 *
 * Class SerialNumber
 */
class SerialNumber extends Model {

    /**
     * @var string
     */
    protected $table = 'serial_numbers';

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

}