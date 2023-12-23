<?php

namespace SimonMarcelLinden\JWT\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JWTKeyCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'jwt:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generates a new JWT secret key';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$this->setEnvironmentValue('JWT_SECRET', Str::random(32));
	}

	protected function setEnvironmentValue($key, $value) {
		$path = base_path('.env');
		$env = file_get_contents($path);

		$keyExists = preg_match("/^{$key}=/m", $env);

		if ($keyExists) {
			$env = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $env);
		} else {
			$env .= "\n{$key}={$value}\n";
		}

		file_put_contents($path, $env);
	}
}
