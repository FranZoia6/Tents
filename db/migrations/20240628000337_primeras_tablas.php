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
            ->addColumn('lat', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('lon', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('img', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addIndex(['name'], ['unique' => true, 'limit' => 63])
            ->create();
        if ($this->isMigratingUp()) {
            $tableCity->insert([
                ['id' => 1,'name' => 'Mar Del Plata','lat' => '-38.0055','lon' => '-57.5426'],
                ['id' => 2,'name' => 'Villa Gesell','lat' => '-37.2636','lon' => '-56.9730'],
                ['id' => 3,'name' => 'Miramar','lat' => '-38.2712','lon' => '-57.8369'], 
                ['id' => 4,'name' => 'Necochea','lat' => '-38.5569','lon' => '-58.7396'],
                ['id' => 5,'name' => 'Pinamar','lat' => '-37.1079','lon' => '-56.8651']

            ])->saveData();
        }

        $tableService = $this->table('service');
        $tableService->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addIndex(['name'], ['unique' => true, 'limit' => 63])
            ->create();
        if ($this->isMigratingUp()) {
            $tableService->insert([
                ['id' => 1,'name' => 'Pileta'],
                ['id' => 2,'name' => 'SPA'],
                ['id' => 3,'name' => 'Masajes'],
                ['id' => 4,'name' => 'Barra'],
                ['id' => 5,'name' => 'Restaurant'],
                ['id' => 6,'name' => 'Baño'],
            ])->saveData();
        }

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
        if ($this->isMigratingUp()) {
            $tableUser->insert([
                ['id' => 1, 'user' => 'admin', 'password' => 'admin']
            ])->saveData();
        }

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
        if ($this->isMigratingUp()) {
            $tablePromotion->insert([
                ['id'=> 1, 'name' => 'descuento 10' ,'code' => 'descuento10', 'discount' => '10','isPercentage' => true, 'from' => '2024-07-06', 'to' =>'2024-08-06' ],
                ['id'=> 2, 'name' => 'descuento 20' , 'code' => 'descuento20', 'discount' => '20','isPercentage' => true, 'from' => '2024-07-06', 'to' =>'2024-08-06' ],
                ['id'=> 3, 'name' => 'descuento 5000' , 'code' => 'descuento5000', 'discount' => '5000','isPercentage' => false, 'from' => '2024-07-06', 'to' =>'2024-08-06' ]
            ])->saveData();
        }

        $tableBeachResort = $this->table('beach_resort');
        $tableBeachResort->addColumn('name', 'text', ['limit' => MysqlAdapter::TEXT_TINY, 'null' => false])
            ->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR])
            ->addColumn('city', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('state', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('img', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('lat', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('lon', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addIndex(['name'], ['unique' => true, 'limit' => 63])
            ->addForeignKey('city', 'city', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->addForeignKey('state', 'state_beach_resort', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();
        if ($this->isMigratingUp()){
            $tableBeachResort->insert([
                ['id' => 1, 'name' => 'Playa Grande', 'description' => 'Ubicado en la majestuosa costa de Playa Grande, nuestro balneario es el destino perfecto para aquellos que buscan combinar relajación y diversión en un entorno paradisíaco. Con vistas impresionantes al océano y una playa de arena blanca que se extiende por kilómetros, ofrecemos una experiencia inigualable para toda la familia.',
                'city' => 1, 'state' => 1],
                ['id' => 2, 'name' => 'Varese', 'description' => 'Ubicado en la pintoresca Playa Varese, nuestro balneario ofrece una combinación perfecta de elegancia y confort en un entorno sereno y exclusivo. Con vistas espectaculares al mar y una atmósfera relajante, es el lugar ideal para escapar del bullicio de la ciudad y disfrutar de un día de descanso y diversión.',
                'city' => 1, 'state' => 1],
                ['id' => 3, 'name' => 'Torreon del Monje', 'description' => 'Ubicado en el icónico Torreón del Monje, nuestro balneario combina historia, elegancia y belleza natural en un solo lugar. Con una ubicación privilegiada y vistas panorámicas al mar, es el destino perfecto para quienes buscan una experiencia de playa única y sofisticada en Mar del Plata.',
                'city' => 1, 'state' => 1],
                ['id' => 4, 'name' => 'Luz de Luna', 'description' => 'Ubicado en la encantadora Villa Gesell, el Balneario Luz de Luna es un refugio de tranquilidad y belleza natural. Con una playa de arena fina y un entorno sereno, es el lugar perfecto para desconectar y disfrutar de la naturaleza en su máxima expresión.',
                'city' => 2, 'state' => 1],
                ['id' => 5, 'name' => 'Amy', 'description' => 'Ubicado en la encantadora Villa Gesell, el Balneario Luz de Luna es un refugio de tranquilidad y belleza natural. Con una playa de arena fina y un entorno sereno, es el lugar perfecto para desconectar y disfrutar de la naturaleza en su máxima expresión.',
                'city' => 2, 'state' => 1],
                ['id' => 6, 'name' => 'Ola-La', 'description' => 'Ubicado en la encantadora Villa Gesell, el Balneario Luz de Luna es un refugio de tranquilidad y belleza natural. Con una playa de arena fina y un entorno sereno, es el lugar perfecto para desconectar y disfrutar de la naturaleza en su máxima expresión.',
                'city' => 2, 'state' => 1]

            ])->saveData();
        }

        $tableServiceBeachResort = $this->table('service_beach_resort');
        $tableServiceBeachResort->addColumn('service', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('beachResort', 'integer', ['null' => false, 'signed' => false])
            ->addIndex(['service', 'beachResort'], ['unique' => true])
            ->addForeignKey('service', 'service', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->addForeignkey('beachResort', 'beach_resort', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->create();
        if ($this->isMigratingUp()){
            $tableServiceBeachResort->insert([
                ['id' => 1,'service' => 1, 'beachResort' =>1],
                ['id' => 2,'service' => 2, 'beachResort' =>1],
                ['id' => 3,'service' => 3, 'beachResort' =>1],
                ['id' => 4,'service' => 1, 'beachResort' =>2],
                ['id' => 5,'service' => 2, 'beachResort' =>2],
                ['id' => 6,'service' => 4, 'beachResort' =>2],
                ['id' => 7,'service' => 1, 'beachResort' =>3],
                ['id' => 8,'service' => 3, 'beachResort' =>3],
                ['id' => 9,'service' => 5, 'beachResort' =>3],
                ['id' => 10,'service' => 1, 'beachResort' =>4],
                ['id' => 11,'service' => 2, 'beachResort' =>4],
                ['id' => 12,'service' => 1, 'beachResort' =>5],
                ['id' => 13,'service' => 5, 'beachResort' =>5],
                ['id' => 14,'service' => 6, 'beachResort' =>5],
                ['id' => 15,'service' => 1, 'beachResort' =>6],
                ['id' => 16,'service' => 2, 'beachResort' =>6],
                ['id' => 17,'service' => 6, 'beachResort' =>6],
            ])->saveData();
        }

        $tableUnit = $this->table('unit');
        $tableUnit->addColumn('beachResort', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('number', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('shade', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('price', 'decimal', ['null' => false, 'precision' => 9, 'scale' => 2])
            ->addIndex(['beachResort', 'number', 'shade'], ['unique' => true])
            ->addForeignKey('beachResort', 'beach_resort', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignKey('shade', 'shade', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();
            if ($this->isMigratingUp()){
                $tableUnit->insert([
                    ['id' => 1, 'beachResort'=>1,'number' =>1, 'shade' =>1,'price' =>20000 ],
                    ['id' => 2, 'beachResort'=>1,'number' =>2, 'shade' =>1,'price' =>20000 ],
                    ['id' => 3, 'beachResort'=>1,'number' =>3, 'shade' =>1,'price' =>20000 ],
                    ['id' => 4, 'beachResort'=>1,'number' =>4, 'shade' =>1,'price' =>20000 ],
                    ['id' => 5, 'beachResort'=>1,'number' =>5, 'shade' =>1,'price' =>20000 ],
                    ['id' => 6, 'beachResort'=>1,'number' =>6, 'shade' =>1,'price' =>20000 ],
                    ['id' => 7, 'beachResort'=>1,'number' =>7, 'shade' =>1,'price' =>20000 ],
                    ['id' => 8, 'beachResort'=>1,'number' =>8, 'shade' =>1,'price' =>20000 ],
                    ['id' => 9, 'beachResort'=>1,'number' =>9, 'shade' =>1,'price' =>20000 ],
                    ['id' => 10, 'beachResort'=>1,'number' =>10, 'shade' =>1,'price' =>20000 ],
                    ['id' => 11, 'beachResort'=>1,'number' =>11, 'shade' =>1,'price' =>20000 ],
                    ['id' => 12, 'beachResort'=>1,'number' =>12, 'shade' =>1,'price' =>20000 ],
                    ['id' => 13, 'beachResort'=>2,'number' =>1, 'shade' =>1,'price' =>20000 ],
                    ['id' => 14, 'beachResort'=>2,'number' =>2, 'shade' =>1,'price' =>20000 ],
                    ['id' => 15, 'beachResort'=>2,'number' =>3, 'shade' =>1,'price' =>20000 ],
                    ['id' => 16, 'beachResort'=>2,'number' =>4, 'shade' =>1,'price' =>20000 ],
                    ['id' => 17, 'beachResort'=>2,'number' =>5, 'shade' =>1,'price' =>20000 ],
                    ['id' => 18, 'beachResort'=>2,'number' =>6, 'shade' =>1,'price' =>20000 ],
                ])->saveData();
            }

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
            if ($this->isMigratingUp()){
                $tableReservation->insert([
                    ['id' => 1, 'date' => '2024-07-10','from' => '2024-07-10', 'to' =>'2024-07-13','firstName' =>'Example',
                    'lastName' => 'Example', 'email' => 'example@mail.com', 'phone' => '12341234','reservationAmount'=> 20000,
                    'promotion'=> NULL, 'discountAmount' => 0, 'payed' => TRUE, 'voucher' => NULL, 'manual' => FALSE ],
                    ['id' => 2, 'date' => '2024-07-10','from' => '2024-07-8', 'to' =>'2024-07-10','firstName' =>'Example',
                    'lastName' => 'Example', 'email' => 'example@mail.com', 'phone' => '12341234','reservationAmount'=> 20000,
                    'promotion'=> NULL, 'discountAmount' => 0, 'payed' => TRUE, 'voucher' => NULL, 'manual' => FALSE ]
                ])->saveData();
            }

        $tableUnitReservation = $this->table('unit_reservation');
        $tableUnitReservation->addColumn('reservation', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('unit', 'integer', ['null' => false, 'signed' => false])
            ->addIndex(['reservation', 'unit'], ['unique' => true])
            ->addForeignKey('reservation', 'reservation', 'id', ['delete' => 'CASCADE', 'update' => 'RESTRICT'])
            ->addForeignkey('unit', 'unit', 'id', ['delete' => 'RESTRICT', 'update' => 'RESTRICT'])
            ->create();
            if ($this->isMigratingUp()){
                $tableUnitReservation->insert([
                   ['id'=> 1, 'unit' =>1, 'reservation'=>1],
                   ['id'=> 2, 'unit' =>1, 'reservation'=>2]
                ])->saveData();
            }
    }
}
