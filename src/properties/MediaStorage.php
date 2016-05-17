<?php


namespace DevGroup\MediaStorage\properties;

use DevGroup\DataStructure\helpers\PropertiesHelper;
use DevGroup\DataStructure\models\Property;
use DevGroup\DataStructure\propertyStorage\AbstractPropertyStorage;
use DevGroup\MediaStorage\helpers\MediaTableGenerator;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class MediaStorage extends AbstractPropertyStorage
{

    /**
     * Fills $models array models with corresponding binded properties.
     * Models in $models array should be the the same class name.
     *
     * @param ActiveRecord[]|\DevGroup\DataStructure\traits\PropertiesTrait[]|\DevGroup\DataStructure\behaviors\HasProperties[] $models
     *
     * @return ActiveRecord[]|\DevGroup\DataStructure\traits\PropertiesTrait[]|\DevGroup\DataStructure\behaviors\HasProperties[]
     */
    public function fillProperties(&$models)
    {
        if (count($models) === 0) {
            return;
        }

        /** @var \yii\db\ActiveRecord|\DevGroup\DataStructure\traits\PropertiesTrait $firstModel */
        $firstModel = reset($models);

        /** @var \yii\db\Command $command */
        $query = new Query();
        // @todo models can be in different db (for DST also)
        $tableName = (new MediaTableGenerator(['db' => $firstModel->getDb()]))->getMediaTableName($firstModel->className());
        $query->select('*')->from($tableName)->where(PropertiesHelper::getInCondition($models))->orderBy(
            [
                'model_id' => SORT_ASC,
                'sort_order' => SORT_ASC,
            ]
        );

        $values = $query->createCommand($firstModel->getDb())->queryAll();

        $values = ArrayHelper::map(
            $values,
            'media_id',
            function ($item) {
                return $item;
            },
            'model_id'
        );

        foreach ($models as &$model) {
            if (isset($values[$model->id])) {
                $groupedByProperty = ArrayHelper::map(
                    $values[$model->id],
                    'media_id',
                    function ($item) {
                        return $item;
                    },
                    'property_id'
                );

                foreach ($groupedByProperty as $propertyId => $propertyRows) {
                    /** @var Property $property */
                    $property = Property::findById($propertyId);

                    $key = $property->key;

                    $value = array_reduce(
                        $propertyRows,
                        function ($carry, $item) use ($property) {
                            $value = $item['media_id'];
                            $carry[] = $value;
                            return $carry;
                        },
                        []
                    );

                    if ($property->allow_multiple_values === false) {
                        $value = reset($value);
                    }
                    $model->$key = $value;
                }
            }
        }
    }

    /**
     * Removes all properties binded to models.
     * Models in $models array should be the the same class name.
     *
     * @param ActiveRecord[]|\DevGroup\DataStructure\traits\PropertiesTrait[]|\DevGroup\DataStructure\behaviors\HasProperties[] $models
     *
     * @return void
     */
    public function deleteAllProperties(&$models)
    {
        if (count($models) === 0) {
            return;
        }

        /** @var \yii\db\ActiveRecord|\DevGroup\DataStructure\traits\PropertiesTrait $firstModel */
        $firstModel = reset($models);
        $tableName = (new MediaTableGenerator(['db' => $firstModel->getDb()]))->getMediaTableName($firstModel->className());
        /** @var \yii\db\Command $command */
        $command = $firstModel->getDb()->createCommand()->delete(
            $tableName,
            PropertiesHelper::getInCondition($models)
        );

        $command->execute();
    }

    /**
     * @param ActiveRecord[]|\DevGroup\DataStructure\traits\PropertiesTrait[]|\DevGroup\DataStructure\behaviors\HasProperties[] $models
     *
     * @return boolean
     */
    public function storeValues(&$models)
    {
        if (count($models) === 0) {
            return true;
        }

        $insertRows = [];
        $deleteRows = [];

        /** @var ActiveRecord|\DevGroup\DataStructure\traits\PropertiesTrait $firstModel */
        $firstModel = reset($models);
        $tableName = (new MediaTableGenerator(['db' => $firstModel->getDb()]))->getMediaTableName($firstModel->className());
        foreach ($models as $model) {
            foreach ($model->changedProperties as $propertyId) {
                /** @var Property $propertyModel */
                $propertyModel = Property::findById($propertyId);
                if ($propertyModel === null) {
                    continue;
                }
                if ($propertyModel->storage_id === $this->storageId) {
                    if (isset($deleteRows[$model->id])) {
                        $deleteRows[$model->id][] = $propertyId;
                    } else {
                        $deleteRows[$model->id] = [$propertyId];
                    }
                    $newRows = $this->saveModelPropertyRow($model, $propertyModel);

                    foreach ($newRows as $row) {
                        $insertRows[] = $row;
                    }
                }
            }
        }

        if (count($deleteRows) > 0) {
            if (count($deleteRows) > 1) {
                $condition = ['OR'];
                foreach ($deleteRows as $modelId => $propertyIds) {
                    $condition = array_merge($condition, [['model_id' => $modelId, 'property_id' => $propertyIds]]);
                }
            } else {
                $condition = [];
                foreach ($deleteRows as $modelId => $propertyIds) {
                    $condition = ['model_id' => $modelId, 'property_id' => $propertyIds];
                }
            }
            $firstModel->getDb()->createCommand()->delete($tableName, $condition)->execute();
        }

        if (count($insertRows) === 0) {
            return true;
        }

        $cmd = $firstModel->getDb()->createCommand();
        return $cmd->batchInsert(
            $tableName,
            [
                'property_id',
                'model_id',
                'media_id',
                'sort_order',
            ],
            $insertRows
        )->execute() > 0;
    }

    /**
     * @param ActiveRecord|\DevGroup\DataStructure\traits\PropertiesTrait $model
     * @param Property $propertyModel
     *
     * @return array
     */
    private function saveModelPropertyRow(ActiveRecord $model, Property $propertyModel)
    {
        $modelId = $model->id;
        $propertyId = $propertyModel->id;

        $key = $propertyModel->key;
        $values = (array)$model->$key;
        if (count($values) === 0) {
            return [];
        }

        $rows = [];

        foreach ($values as $index => $value) {
            $rows[] = [
                $propertyId,
                $modelId,
                $value,
                $index,
            ];
        }

        return $rows;
    }
}
