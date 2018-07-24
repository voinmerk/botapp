<?php

namespace voinmerk\botapp;

class DB {
	private $adaptor;

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	string	$hostname
	 * @param	string	$username
     * @param	string	$password
	 * @param	string	$database
	 * @param	int		$port
	 *
 	*/
	public function __construct($db) {
		$class = 'voinmerk\\botapp\\db\\' . $db['adaptor'];

		if (class_exists($class)) {
			$this->adaptor = new $class($db['hostname'], $db['username'], $db['password'], $db['database'], $db['port']);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $db['adaptor'] . '!');
		}
	}

	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	array
     */
	public function query($sql) {
		return $this->adaptor->query($sql);
	}

	/**
     * 
     *
     * @param	string	$value
	 * 
	 * @return	string
     */
	public function escape($value) {
		return $this->adaptor->escape($value);
	}

	/**
     * 
     *
     * @param	string	$value
	 * 
	 * @return	string
     */
	public function escapeString($value) {
		return "'" . $this->adaptor->escape($value) . "'";
	}

	/**
     * 
	 * 
	 * @return	int
     */
	public function countAffected() {
		return $this->adaptor->countAffected();
	}

	/**
     * 
	 * 
	 * @return	int
     */
	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	/**
     * 
	 * 
	 * @return	bool
     */	
	public function connected() {
		return $this->adaptor->connected();
	}
}