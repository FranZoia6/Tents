<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class PrimerasTablas extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $tableCity = $this->table('city');
        $tableCity->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addIndex(['name'], ['unique' => true, 'limit' => 63])
            ->create();

        $tableService = $this->table('service');
        $tableService->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addIndex(['name'], ['unique' => true, 'limit' => 63])
            ->create();

        $tableStateBeachResort = $this->table('state_beach_resort');
        $tableStateBeachResort->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->create();
        if ($this->isMigratingUp()) {
            $tableStateBeachResort->insert([
                ['id' => 1, 'name' => 'Activo'],
                ['id' => 2, 'name' => 'Inactivo'],
                ['id' => 3, 'name' => 'Eliminado']
            ])->saveData();
        }

        $tableUser = $this->table('user');
        $tableUser->addColumn('user', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('password', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addIndex(['user'], ['unique' => true, 'limit' => 63])
            ->create();

        $tableShade = $this->table('shade');
        $tableShade->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->create();
        if ($this->isMigratingUp()) {
            $tableShade->insert([
                ['id' => 1, 'name' => 'Carpa'],
                ['id' => 2, 'name' => 'Sombrilla']
            ])->saveData();
        }

        $tablePromotion = $this->table('promotion');
        $tablePromotion->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('code', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('discount', 'decimal', ['null' => false, 'precision' => 9, 'scale' => 2])
            ->addColumn('isPercentage', 'boolean', ['null' => false])
            ->addColumn('from', 'datetime', ['null' => false])
            ->addColumn('to', 'datetime', ['null' => false])
            ->create();

        $tableBeachResort = $this->table('beach_resort');
        $tableBeachResort->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('city', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('state', 'integer', ['null' => false, 'signed' => false])
            ->addIndex(['name'], ['unique' => true, 'limit' => 63])
            ->addForeignKey('city', 'city', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->addForeignKey('state', 'state_beach_resort', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();

        $tableServiceBeachResort = $this->table('service_beach_resort');
        $tableServiceBeachResort->addColumn('service', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('beachResort', 'integer', ['null' => false, 'signed' => false])
            ->addIndex(['service', 'beachResort'], ['unique' => true])
            ->addForeignKey('service', 'service', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->addForeignkey('beachResort', 'beach_resort', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->create();

        $tableUnit = $this->table('unit');
        $tableUnit->addColumn('beachResort', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('number', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('shade', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('price', 'decimal', ['null' => false, 'precision' => 9, 'scale' => 2])
            ->addIndex(['beachResort', 'number', 'shade'], ['unique' => true])
            ->addForeignKey('beachResort', 'beach_resort', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignKey('shade', 'shade', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();

        $tableReservation = $this->table('reservation');
        $tableReservation->addColumn('date', 'datetime', ['null' => false])
            ->addColumn('from', 'date', ['null' => false])
            ->addColumn('to', 'date', ['null' => false])
            ->addColumn('firstName', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('lastName', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('email', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('phone', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('reservationAmount', 'decimal', ['null' => false, 'precision' => 9, 'scale' => 2])
            ->addColumn('promotion', 'integer', ['signed' => false])
            ->addColumn('discountAmount', 'decimal', ['default' => 0, 'null' => false, 'precision' => 9, 'scale' => 2])
            ->addColumn('payed', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('voucher', 'text', ['limit' => MysqlAdapter::TEXT_TINY])
            ->addColumn('manual', 'boolean', ['default' => false, 'null' => false])
            ->addIndex(['date'])
            ->addForeignKey('promotion', 'promotion', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();

        $tableUnitReservation = $this->table('unit_reservation');
        $tableUnitReservation->addColumn('reservation', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('unit', 'integer', ['null' => false, 'signed' => false])
            ->addIndex(['reservation', 'unit'], ['unique' => true])
            ->addForeignKey('reservation', 'reservation', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignkey('unit', 'unit', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();
    }
}
