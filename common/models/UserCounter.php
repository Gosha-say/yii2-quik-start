<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "user_counter".
 *
 * @property int $user_id
 * @property string|null|Counter $counter
 */
class UserCounter extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string {
        return 'user_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['counter'], 'safe'],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array {
        return [
            'user_id' => 'User ID',
            'counter' => 'Counter',
        ];
    }

    /**
     * @throws Exception
     */
    public function save($runValidation = false, $attributeNames = null) {
        $counter = $this->counter->jsonSerialize();
        Yii::$app->db->createCommand("insert into user_counter (user_id, counter) values ($this->user_id, '$counter')")->execute();
    }

    public function afterFind() {
        parent::afterFind();
        $this->counter = Counter::makeCounter($this);
    }

    public static function getByUser(User $user): Counter|null {
        $counter = static::find()->where(['user_id' => $user->id])->one();
        return $counter instanceof UserCounter ? $counter->counter : null;
    }
}
