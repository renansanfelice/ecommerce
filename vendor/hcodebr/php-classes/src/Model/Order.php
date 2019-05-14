<?php

namespace Hcode\Model;

use Hcode\DB\Sql;
use Hcode\Model;

class Order extends Model {

	public function save()
	{
		$sql = new Sql();

		$results = $sql->select("CALL sp_orders_save(:idorder, :idcart, :iduser, :idstatus, :idaddress, :vltotal)", [
			':idorder' => $this->getidorder(),
			':idcart' => $this->getidcart(),
			':iduser' => $this->getiduser(),
			':idstatus' => $this->getidstatus(),
			':idaddress' => $this->getidaddress(),
			':vltotal' => $this->getvltotal()
		]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}
	}

	public function get($idorder) 
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * from 
								tb_orders a 
								inner join tb_ordersstatus b USING(idstatus)
								inner join tb_carts c USING(idcart)
								inner join tb_users d ON d.iduser = a.iduser
								inner join tb_addresses e USING(idaddress)
								inner join tb_persons f ON f.idperson = d.idperson
								where a.idorder = :idorder", 
								[
									':idorder' => $idorder
								]);

		if (count($results) > 0) {
			$this->setData($results[0]);
		}
	}

}


?>