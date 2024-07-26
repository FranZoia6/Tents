<?php

namespace Tents\App\Models;

use Tents\Core\Model;
use Tents\Core\Exceptions\InvalidValueFormatException;
use Exception;

class Reservation extends Model {

    public $table = 'reservation';

    public $fields = [
        "date" => null,
        "from" => null,
        "to" => null,
        "firstName" => null,
        "lastName" => null,
        "email" => null,
        "phone" => null,
        "reservationAmount" => null,
        "promotion" => null,
        "discountAmount" => null,
        "payed" => null,
        "voucher" => null,
        "manual" => null,
    ];

    public function setDate($date) {
        $this -> fields["date"] = $date;
    }

    public function setFrom($from) {
        $this -> fields["from"] = $from;
    }

    public function setTo($to) {
        $this -> fields["to"] = $to;
    }

    public function setFirstName(string $firstName) {
        $this -> fields["firstName"] = $firstName;
    }

    public function setLastName(string $lastName) {
        $this -> fields["lastName"] = $lastName;
    }

    public function setEmail(string $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidValueFormatException("Formato de email no valido");
        }
        $this -> fields["email"] = $email;
    }

    public function setPhone(string $phone) {
        $pattern = '/[a-zA-Z]/';
        if (preg_match($pattern, $phone)) {
            throw new InvalidValueFormatException("Formato de telefono no valido");
        }
        $this -> fields["phone"] = $phone;
    }    
    
    public function setReservationAmount(float $reservation_amount) {
        $this -> fields["reservationAmount"] = $reservation_amount;
    }

    public function setPromotion(int $promotion_id) {
        $this -> fields["promotion"] = $promotion_id;
    }

    public function setDiscountAmount(float $discount_amount) {
        $this -> fields["discountAmount"] = $discount_amount;
    }

    public function setIsPayed($isPayed) {
        // if (!is_bool($isPayed)) {
        //     throw new InvalidValueFormatException("El campo debe ser booleano.");
        // }
        $this -> fields["payed"] = $isPayed;
    }

    public function setVoucher(string $voucher) {
        $this -> fields["voucher"] = $voucher;
    }

    public function setManual($manual) {
        // if (!is_bool($manual)) {
        //     throw new InvalidValueFormatException("El campo debe ser booleano.");
        // }
        $this -> fields["manual"] = $manual;
    }

    public function set(array $values) {
        foreach(array_keys($this -> fields) as $field) {
            if (!isset($values[$field])) {
                continue;
            }
            $method = "set" . ucfirst($field);
            $this -> $method($values[$field]);
        }
    }

}