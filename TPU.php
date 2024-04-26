<?php

	namespace app;

	class TPU extends \OAuth
	{
		/**
		 * {@inheritdoc}
		 */
		public $authUrl = 'https://oauth.tpu.ru/authorize';
		/**
		 * {@inheritdoc}
		 */
		public $tokenUrl = 'https://oauth.tpu.ru/access_token';

		/**
		 * @var string
		 */
		public $api_key;

		/**
		 * {@inheritdoc}
		 */
		public $apiBaseUrl = 'https://api.tpu.ru/v2';

		/**
		 * @inheritdoc
		 */
		protected function initUserAttributes()
		{
			$params = [];
			$params['access_token'] = $this->accessToken->getToken();
			$params['apiKey'] = $this->api_key;

			return $this->api('auth/user', 'GET', $params);
		}

		/**
		 * @inheritdoc
		 */
		protected function defaultNormalizeUserAttributeMap()
		{
			return [
				'id' => 'user_id'
			];
		}
	}