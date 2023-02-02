<?php

use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m230201_153852_create_table_user_counter
 */
class m230201_153852_create_table_user_counter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->createTable('user_counter', [
                'user_id' => $this->integer()->unique()->notNull(),
                'counter' => $this->json(),
            ]);
        } catch (Exception $e) {
            Console::stdout("Json not supported: " . PHP_EOL . $e->getMessage() . PHP_EOL);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230201_153852_create_table_user_counter cannot be reverted.\n";

        return false;
    }
}
