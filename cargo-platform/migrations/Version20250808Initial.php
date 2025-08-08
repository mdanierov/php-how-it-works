<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250808Initial extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial schema for users, courier_profiles, orders, price_rules, payments';
    }

    public function up(Schema $schema): void
    {
        // users
        $users = $schema->createTable('users');
        $users->addColumn('id', 'integer', ['autoincrement' => true]);
        $users->addColumn('email', 'string', ['length' => 180]);
        $users->addColumn('roles', 'json');
        $users->addColumn('password', 'string');
        $users->addColumn('first_name', 'string', ['length' => 100, 'notnull' => false]);
        $users->addColumn('last_name', 'string', ['length' => 100, 'notnull' => false]);
        $users->addColumn('phone', 'string', ['length' => 50, 'notnull' => false]);
        $users->addColumn('country', 'string', ['length' => 2, 'notnull' => false]);
        $users->addColumn('verified_at', 'datetime', ['notnull' => false]);
        $users->addColumn('created_at', 'datetime');
        $users->addColumn('updated_at', 'datetime');
        $users->setPrimaryKey(['id']);
        $users->addUniqueIndex(['email']);

        // courier_profiles
        $cp = $schema->createTable('courier_profiles');
        $cp->addColumn('id', 'integer', ['autoincrement' => true]);
        $cp->addColumn('user_id', 'integer');
        $cp->addColumn('routes', 'json', ['notnull' => false]);
        $cp->addColumn('travel_dates', 'json', ['notnull' => false]);
        $cp->addColumn('capacity_kg', 'integer');
        $cp->addColumn('passport_path', 'string', ['length' => 255, 'notnull' => false]);
        $cp->addColumn('passport_uploaded_at', 'datetime', ['notnull' => false]);
        $cp->addColumn('passport_deleted_at', 'datetime', ['notnull' => false]);
        $cp->addColumn('rating', 'float');
        $cp->addColumn('reliability_score', 'float');
        $cp->setPrimaryKey(['id']);
        $cp->addForeignKeyConstraint('users', ['user_id'], ['id'], ['onDelete' => 'CASCADE']);

        // orders
        $orders = $schema->createTable('orders');
        $orders->addColumn('id', 'integer', ['autoincrement' => true]);
        $orders->addColumn('client_id', 'integer');
        $orders->addColumn('assigned_courier_id', 'integer', ['notnull' => false]);
        $orders->addColumn('weight_kg', 'integer');
        $orders->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2]);
        $orders->addColumn('currency', 'string', ['length' => 3]);
        $orders->addColumn('from_address', 'string', ['length' => 255]);
        $orders->addColumn('to_address', 'string', ['length' => 255]);
        $orders->addColumn('direction', 'string', ['length' => 32]);
        $orders->addColumn('status', 'string', ['length' => 32]);
        $orders->addColumn('created_at', 'datetime');
        $orders->addColumn('updated_at', 'datetime');
        $orders->setPrimaryKey(['id']);
        $orders->addForeignKeyConstraint('users', ['client_id'], ['id']);
        $orders->addForeignKeyConstraint('users', ['assigned_courier_id'], ['id']);

        // price_rules
        $rules = $schema->createTable('price_rules');
        $rules->addColumn('id', 'integer', ['autoincrement' => true]);
        $rules->addColumn('min_weight_kg', 'integer');
        $rules->addColumn('max_weight_kg', 'integer');
        $rules->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2]);
        $rules->addColumn('currency', 'string', ['length' => 3]);
        $rules->addColumn('direction', 'string', ['length' => 32]);
        $rules->addColumn('active', 'boolean');
        $rules->setPrimaryKey(['id']);

        // payments
        $payments = $schema->createTable('payments');
        $payments->addColumn('id', 'integer', ['autoincrement' => true]);
        $payments->addColumn('order_id', 'integer');
        $payments->addColumn('paypal_id', 'string', ['length' => 64]);
        $payments->addColumn('status', 'string', ['length' => 32]);
        $payments->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 2]);
        $payments->addColumn('currency', 'string', ['length' => 3]);
        $payments->addColumn('created_at', 'datetime');
        $payments->setPrimaryKey(['id']);
        $payments->addForeignKeyConstraint('orders', ['order_id'], ['id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('payments');
        $schema->dropTable('price_rules');
        $schema->dropTable('orders');
        $schema->dropTable('courier_profiles');
        $schema->dropTable('users');
    }
}