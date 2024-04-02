<?php

	/**
	 *  Мой личный Debag, Тошматов Т.Т.
	 */
	function debug($arr) : void
	{
		echo '<pre>' . print_r($arr, true) . '</pre>';
	}


	function get_age( $birthday )
	{
		$diff = date( 'Ymd' ) - date( 'Ymd', strtotime($birthday) );

		return substr( $diff, 0, -4 );
	}