<?php
	use MongoDB\Driver\Manager;
	use MongoDB\Driver\Session;
	use MongoDB\Driver\ReadConcern;
	use MongoDB\Driver\ReadPreference;
	use MongoDB\Driver\WriteConcern;
	use MongoDB\Driver\Query;
	use MongoDb\Driver\Cursor;
        
        session_start();
        
	try {
		$manager = new Manager("mongodb://localhost:27017");

		//var_dump($connection);

		$sessionOptions = array(
			"maxCommitTimeMS" => 1000,
			"readConcern"	  => new ReadConcern("linearizable"),
			"readPreference"  => new ReadPreference(1, [], []),
			"writeConcern"	  => new WriteConcern(1, 0, true),
		);
		$session = $manager->startSession([]);
                
		//construyendo query
		$query = new Query([]);

		//ejecutando query
		$cursor = $manager->executeQuery("itca_inv_2019_CalidadAire_Test_01.Estacion", $query, [$session]);

		foreach ($cursor as $document) {
			print_r($document);
			echo "<br>";
		}
                echo "<br><br>"; 
                
		echo "<br><br>todo correcto :D";
	} catch (Exception $e) {
		var_dump($e);
		echo "<br><br>algo salió mal :C";
	}
        print_r(sizeof($_SESSION));
?>