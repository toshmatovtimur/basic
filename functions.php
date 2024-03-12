<?php

	/**
	 *  Мой личный Debag, Тошматов Т.Т.
	 */
	function debug($arr) : void
	{
		echo '<pre>' . print_r($arr, true) . '</pre>';
	}