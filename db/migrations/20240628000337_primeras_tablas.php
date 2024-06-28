<?php

declare(strict_types=1);

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
        $tableService = $this->table('service');
        $tableService->addColumn('name','string',['limit'=>100] )
            ->create();
        
        $tableReservation = $this->table('reservation');
        $tableReservation->addColumn('date', 'datetime')
            ->addColumn('from', 'date')
            ->addColumn('to', 'date')
            ->addColumn('firstName', 'string', ['limit' => 30])
            ->addColumn('lastName', 'string', ['limit' => 30])
            ->addColumn('email', 'string', ['limit' => 100])
            ->addColumn('phone', 'string', ['limit' => 20])
            ->addColumn('reservationAmount', 'float')
            ->addColumn('promotion', 'integer')
            ->addColumn('discountAmount', 'float')
            ->addColumn('payed', 'boolean')
            ->addColumn('voucher', 'string', ['limit' => 100])
            ->addColumn('manual', 'boolean')
            ->create();
    

        $tableUser = $this->table('user');
        $tableUser->addColumn('user', 'string', ['limit' => 30])
            ->addColumn('password', 'string', ['limit' => 100])
            ->create();

        $tableRoom = $this->table('room');
        $tableRoom->addColumn('description', 'string', ['limit'=>300])
            ->addColumn('name','string', ['limit'=>100])
            ->addColumn('price', 'float')
            ->addColumn('visible', 'boolean',['default'=> true])
            ->create();
    
        $tableCity = $this->table('city');
        $tableCity->addColumn('name','string',['limit'=>100] )
        ->create();

        $tableStateBeachResort = $this->table('state_beach_resort');
        $tableStateBeachResort->addColumn('name','string',['limit'=>100] )
        ->create();

        $tableShade = $this->table('shade');
        $tableShade->addColumn('name','string',['limit'=>100] )
            ->create();

        $tableBeachResort = $this->table('beach_resort');
        $tableBeachResort->addColumn('name','string',['limit'=>100])
            ->addColumn('description', 'string', ['limit'=>300])
            ->addColumn('city','integer', ['signed' => false])
            ->addForeignKey('city','city','id')
            ->addColumn('state', 'integer',['signed' => false])
            ->addForeignKey('state','state_beach_resort','id')
            ->create();



        $tableServiceBeachResort = $this->table('service_beach_resort');
        $tableServiceBeachResort->addColumn('service','integer',['signed' => false])
            ->addColumn('beachResort', 'integer',['signed' => false])
            ->addForeignKey('service','service','id')
            ->addForeignkey('beachResort','beach_resort','id')
            ->create();


        $tableUnit = $this->table('unit');
        $tableUnit->addColumn('number','integer',['signed' => false])
            ->addColumn('shade','integer',['signed' => false])
            ->addForeignKey('shade','shade','id')
            ->addColumn('price','float')
            ->create();

        $tablePromotion = $this->table('promotion');
        $tablePromotion->addColumn('name', 'string', ['limit' => 100])
               ->addColumn('code', 'string', ['limit' => 30])
               ->addColumn('discount', 'float')
               ->addColumn('isPercentage', 'boolean')
               ->addColumn('from', 'date')
               ->addColumn('to', 'date')
               ->create();

        $tableUnitReservation = $this->table('unit_reservation');
        $tableUnitReservation->addColumn('reservation','integer',['signed' => false])
            ->addColumn('unit', 'integer',['signed' => false])
            ->addForeignKey('reservation','reservation','id')
            ->addForeignkey('unit','unit','id')
            ->create();
        

    }
}
