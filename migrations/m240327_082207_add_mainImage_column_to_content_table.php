<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%content}}`.
 */
class m240327_082207_add_mainImage_column_to_content_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%content}}', 'mainImage', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%content}}', 'mainImage');
    }
}
