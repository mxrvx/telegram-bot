<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmModx3539635c08e03a42aea7e3d2650c61d8 extends Migration
{
    protected const DATABASE = 'modx';

    public function up(): void
    {
        $this->table('mxrvx_telegram_bot_users')
            ->addColumn('id', 'bigPrimary', [
                'nullable' => false,
                'defaultValue' => null,
                'size' => 20,
                'autoIncrement' => true,
                'unsigned' => true,
                'zerofill' => false,
                'comment' => '',
            ])
            ->addColumn('first_name', 'string', ['nullable' => false, 'defaultValue' => '', 'size' => 191, 'comment' => ''])
            ->addColumn('last_name', 'string', ['nullable' => false, 'defaultValue' => '', 'size' => 191, 'comment' => ''])
            ->addColumn('username', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 191, 'comment' => ''])
            ->addColumn('status', 'string', ['nullable' => false, 'defaultValue' => 'unknown', 'size' => 255, 'comment' => ''])
            ->addColumn('created_at', 'datetime', ['nullable' => false, 'defaultValue' => 'CURRENT_TIMESTAMP', 'comment' => ''])
            ->addColumn('updated_at', 'datetime', ['nullable' => true, 'defaultValue' => null, 'comment' => ''])
            ->addIndex(['first_name'], ['name' => 'first_name', 'unique' => false])
            ->addIndex(['last_name'], ['name' => 'last_name', 'unique' => false])
            ->addIndex(['username'], ['name' => 'username', 'unique' => false])
            ->addIndex(['status'], ['name' => 'status', 'unique' => false])
            ->addIndex(['created_at'], ['name' => 'created_at', 'unique' => false])
            ->addIndex(['updated_at'], ['name' => 'updated_at', 'unique' => false])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('mxrvx_telegram_bot_users')->drop();
    }
}
