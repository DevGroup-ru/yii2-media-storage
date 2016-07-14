<?php


namespace DevGroup\MediaStorage\properties;

use DevGroup\DataStructure\models\Property;
use DevGroup\DataStructure\propertyHandler\AbstractPropertyHandler;

class MediaHandler extends AbstractPropertyHandler
{


    /** @inheritdoc */
    public static $multipleMode = Property::MODE_ALLOW_MULTIPLE;

    /** @inheritdoc */
    public static $allowedStorage = [
        MediaStorage::class,
    ];

    /** @inheritdoc */
    public static $allowedTypes = [
        Property::DATA_TYPE_STRING,
    ];

    /** @inheritdoc */
    public static $allowInSearch = true;
    /**
     * Get validation rules for a property.
     *
     * @param Property $property
     *
     * @return array of ActiveRecord validation rules
     */
    public function getValidationRules(Property $property)
    {
        $key = $property->key;

        $rule = Property::dataTypeValidator($property->data_type) ?: 'safe';

        if ($property->allow_multiple_values) {
            return [
                [$key, 'each', 'rule' => [$rule]],
            ];
        } else {
            return [
                [$key, $rule],
            ];
        }
    }
}
